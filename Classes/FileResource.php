<?php namespace JayBeeR\Flops {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Charsets\Encoding;
    use JayBeeR\Flops\Charsets\Utf8;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Failures\CannotOpenFile;
    use JayBeeR\Flops\Failures\CannotReadBytesFromFile;
    use JayBeeR\Flops\Failures\CannotSeekToPosition;
    use JayBeeR\Flops\Failures\InvalidZeroLengthForReading;
    use JayBeeR\Flops\Properties\ReferenceProperty;

    class FileResource
    {
        use ReferenceProperty;

        /**
         * @var resource
         */
        protected $context;

        protected Encoding $encoding;

        /**
         * @param string $reference
         * @param Encoding $encoding
         *
         * @throws CannotOpenFile
         */
        protected function __construct(string $reference, Encoding $encoding)
        {
            $this->encoding = $encoding;

            if (false === ($context = fopen($reference, 'r'))) {
                throw new CannotOpenFile($reference);
            }

            $this->context = $context;
            $this->reference = $reference;
        }

        public function __destruct()
        {
            fclose($this->context);
        }

        /**
         * @return int
         */
        public function getFileSize(): ?int
        {
            $size = filesize($this->reference);

            return (false !== $size) ? $size : null;
        }

        /**
         * @param int $offset
         *
         * @throws CannotSeekToPosition
         */
        public function seekToPosition(int $offset): void
        {
            if (-1 === fseek($this->context, $offset)) {
                throw new CannotSeekToPosition($this, $offset);
            }
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

            while ((false !== ($line = fgets($this->context))) && ($offset > ($current = ftell($this->context)))) {
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
            return feof($this->context);
        }

        /**
         * @param int $length
         * @param bool $fixed
         *
         * @return string|null
         * @throws InvalidZeroLengthForReading
         */
        public function readContent(int $length = 1, bool $fixed = false): ?string
        {
            if (1 === $length) {
                $byteReader = fn () => fgetc($this->context);

                if ($fixed) {
                    $content = $byteReader();
                } else {
                    $content = $this->encoding->assemble($byteReader);
                }
            } elseif (1 < $length) {
                $content = fgets($this->context, $length);
            } else {
                throw new InvalidZeroLengthForReading();
            }

            if (false === $content) {
                $content = null;
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
            $content = fread($this->context, $length);

            if (false === $content) {
                throw new CannotReadBytesFromFile($this, $length);
            }

            return $content;
        }

        /**
         * @param string $file
         * @param Encoding|null $encoding
         *
         * @return FileResource
         * @throws ReferenceNotFound
         * @throws CannotOpenFile
         */
        public static function get(string $file, Encoding $encoding = null): FileResource
        {
            if (!is_file($file)) {
                throw new ReferenceNotFound($file);
            }

            return new static($file, $encoding??Utf8::encoding());
        }
    }
} 
