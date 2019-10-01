<?php namespace JayBeeR\Flops {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Charsets\Encoding;
    use JayBeeR\Flops\Charsets\Utf8;
    use JayBeeR\Flops\Failures\CannotCloseFile;
    use JayBeeR\Flops\Failures\CannotOpenFile;
    use JayBeeR\Flops\Failures\CannotReadBytesFromFile;
    use JayBeeR\Flops\Failures\CannotSeekToPosition;
    use JayBeeR\Flops\Failures\CannotWriteContentToFile;
    use JayBeeR\Flops\Failures\InvalidZeroLengthForReading;
    use JayBeeR\Flops\Failures\ReferenceIsNotFile;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Modes\FileMode;
    use JayBeeR\Flops\Modes\OpenMode;
    use JayBeeR\Flops\Properties\ReferenceProperty;

    class FileResource
    {
        use ReferenceProperty;

        protected Encoding $encoding;

        protected ?int $position = null;

        /**
         * @var resource|null
         */
        protected $resource = null;

        /**
         * @param FileReference $reference
         * @param OpenMode $mode
         *
         * @throws CannotOpenFile
         */
        protected function __construct(FileReference $reference, OpenMode $mode)
        {
            $this->reference = $reference;
            $this->setResource($mode);
        }

        /**
         * @throws CannotCloseFile
         */
        public function __destruct()
        {
            if ((null !== $this->resource) && (!fclose($this->resource))) {
                throw new CannotCloseFile($this->reference);
            }
        }

        /**
         * @param OpenMode $mode
         *
         * @throws CannotOpenFile
         */
        protected function setResource(OpenMode $mode)
        {
            $this->resource = fopen($this->reference, (string)$mode);

            if (!is_resource($this->resource)) {
                throw new CannotOpenFile($this->reference);
            }
        }

        /**
         * @param Encoding $encoding
         *
         * @return FileResource
         */
        public function setEncoding(Encoding $encoding): FileResource
        {
            $this->encoding = $encoding;

            return $this;
        }

        /**
         * @return int
         */
        public function getFileSize(): ?int
        {
            return $this->reference->getSize();
        }

        public function getPosition()
        {
            return $this->position = ftell($this->resource);
        }

        /**
         * @param int $offset
         *
         * @throws CannotSeekToPosition
         */
        public function seekToPosition(int $offset): void
        {
            if (-1 === fseek($this->resource, $offset)) {
                throw new CannotSeekToPosition($this, $offset);
            }

            $this->position = $offset;
        }

        /**
         * @param int $offset
         *
         * @return array
         * @throws CannotSeekToPosition
         */
        public function getPositionsFrom(int $offset): array
        {
            $this->seekToPosition(0);

            $lastOffset = 0;
            $lineNumber = 0;

            while ((false !== ($line = fgets($this->resource))) && ($offset > ($current = ftell($this->resource)))) {
                $lineNumber++;
                $lastOffset = $current;
            }

            return [
                'rows' => $lineNumber,
                'columns' => $offset - $lastOffset
            ];
        }

        /**
         * @return bool
         */
        public function isEndOfFile(): bool
        {
            return feof($this->resource);
        }

        /**
         * @param int $length
         * @param bool $exact
         *
         * @return string|null
         * @throws InvalidZeroLengthForReading
         */
        public function readCharacter(int $length = 1, bool $exact = false): ?string
        {
            if (1 === $length) {
                $byteReader = fn () => fgetc($this->resource);

                if ($exact) {
                    $content = $byteReader();
                } else {
                    $content = $this->encoding->assemble($byteReader);
                }
            } elseif (1 < $length) {
                $content = fgets($this->resource, $length);
            } else {
                throw new InvalidZeroLengthForReading();
            }

            if ((false === $content) || '' === $content) {
                $content = null;
            } else {
                $this->position += strlen($content);
            }

            return $content;
        }

        /**
         * @param int $length
         *
         * @throws CannotTruncateFile
         */
        public function truncate(int $length = 0)
        {
            if (ftruncate($this->resource, $length)) {
                throw new CannotTruncateFile($this->reference);
            }

            $this->position = 0;
        }

        /**
         * @param string $content
         *
         * @throws CannotWriteContentToFile
         */
        public function write(string $content)
        {
            if (false === ($length = fwrite($this->resource, $content))) {
                throw new CannotWriteContentToFile($this->reference, $content);
            }

            $this->position += $length;
        }

        /**
         * @return string
         */
        public function readLine(): ?string
        {
            $content = fgets($this->resource);

            if (false === $content) {
                $content = null;
            } else {
                $this->position += strlen($content);
            }

            return $content;
        }

        /**
         * @param int $length
         *
         * @return string
         * @throws CannotReadBytesFromFile
         */
        public function readBytes(int $length): string
        {
            $content = fread($this->resource, $length);

            if (false === $content) {
                throw new CannotReadBytesFromFile($this, $length);
            }

            $this->position += $length;

            return $content;
        }

        /**
         * @param string $file
         * @param OpenMode $mode
         *
         * @return FileResource
         * @throws CannotOpenFile
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public static function get(string $file, OpenMode $mode): FileResource
        {
            $resource = FileReference::get($file);

            return new static($resource, $mode);
        }

        /**
         * @param string $file
         * @param Encoding $encoding
         *
         * @return FileResource
         * @throws CannotOpenFile
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public static function reading(string $file, Encoding $encoding = null): FileResource
        {
            $resource = static::get($file, FileMode::reading());
            $resource->setEncoding($encoding ?? Utf8::encoding());

            return $resource;
        }

        /**
         * @param string $file
         * @param Encoding $encoding
         *
         * @return FileResource
         * @throws CannotOpenFile
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public static function writing(string $file, Encoding $encoding = null): FileResource
        {
            $resource = static::get($file, FileMode::writing());
            $resource->setEncoding($encoding ?? Utf8::encoding());

            return $resource;
        }

        /**
         * @param string $file
         * @param Encoding $encoding
         *
         * @return FileResource
         * @throws CannotOpenFile
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public static function creating(string $file, Encoding $encoding = null): FileResource
        {
            $resource = static::get($file, FileMode::creating());
            $resource->setEncoding($encoding ?? Utf8::encoding());

            return $resource;
        }

        /**
         * @param string $file
         * @param Encoding $encoding
         *
         * @return FileResource
         * @throws CannotOpenFile
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public static function truncating(string $file, Encoding $encoding = null): FileResource
        {
            $resource = static::get($file, FileMode::truncating());
            $resource->setEncoding($encoding ?? Utf8::encoding());

            return $resource;
        }

        /**
         * @param string $file
         * @param Encoding $encoding
         *
         * @return FileResource
         * @throws CannotOpenFile
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public static function appending(string $file, Encoding $encoding = null): FileResource
        {
            $resource = static::get($file, FileMode::appending());
            $resource->setEncoding($encoding ?? Utf8::encoding());

            return $resource;
        }
    }
} 
