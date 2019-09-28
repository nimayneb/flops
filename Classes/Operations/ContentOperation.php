<?php namespace JayBeeR\Flops\Operations {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use Closure;
    use Generator;
    use JayBeeR\Flops\CannotTruncateFile;
    use JayBeeR\Flops\Failures\CannotReadContentFromFile;
    use JayBeeR\Flops\Failures\CannotOpenFile;
    use JayBeeR\Flops\Failures\CannotWriteContentToFile;
    use JayBeeR\Flops\Failures\InvalidZeroLengthForReading;
    use JayBeeR\Flops\Failures\ReferenceIsNotFile;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Failures\UnexpectedAvailableContent;
    use JayBeeR\Flops\FileResource;
    use JayBeeR\Flops\Modes\FileMode;
    use JayBeeR\Flops\Properties\ReferenceProperty;

    trait ContentOperation
    {
        use ReferenceProperty;

        protected ?FileResource $resource = null;

        /**
         * @return string
         * @throws CannotReadContentFromFile
         */
        public function getContent(): string
        {
            $content = file_get_contents($this->reference);

            if (!$content) {
                throw new CannotReadContentFromFile($this->reference);
            }

            return $content;
        }

        /**
         * @param string $data
         *
         * @return bool
         */
        public function setContent(string $data): bool
        {
            $result = file_put_contents($this->reference, $data);

            return ($result !== strlen($data));
        }

        /**
         * @param int $length
         *
         * @throws CannotOpenFile
         * @throws CannotTruncateFile
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public function truncate(int $length = 0)
        {
            $this->resource = FileResource::get($this->reference, FileMode::reading()->writing());
            $this->resource->truncate($length);
            $this->resource = null;
        }

        /**
         * Example:
         *      $outputFile = LocalFileSystem::createFile('/var/log/output.log');
         *      $outputFile->writeContent(function() {
         *          for ($i < 0; $i < 100; $i++) {
         *              yield sprintf("Row %d written\n", $i);
         *          }
         *      });
         *
         * @param Closure|Generator $generatorHandler
         *
         * @throws CannotWriteContentToFile
         */
        protected function writeContent(Closure $generatorHandler): void
        {
            foreach ($generatorHandler() as $content) {
                $this->resource->write($content);
            }

            $this->resource = null;
        }

        /**
         * Example:
         *      $outputFile = LocalFileSystem::createFile('/var/log/output.log');
         *      $i = 0;
         *
         *      while($outputFile->readContent() as $content) {
         *          echo sprintf('Row %d: %s', ++$i, $content);
         *      }
         *
         * @return Generator|string[]
         * @throws CannotOpenFile
         * @throws InvalidZeroLengthForReading
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         * @throws UnexpectedAvailableContent
         */
        public function readCharacter()
        {
            $this->resource = FileResource::get($this->reference, FileMode::reading());

            while (($buffer = $this->resource->readCharacter()) !== false) {
                yield $buffer;
            }

            if (!$this->resource->isEndOfFile()) {
                throw new UnexpectedAvailableContent($this->reference);
            }
        }

        /**
         * @return Generator
         * @throws CannotOpenFile
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         * @throws UnexpectedAvailableContent
         */
        public function readLine()
        {
            $this->resource = FileResource::get($this->reference, FileMode::reading());

            while (($buffer = $this->resource->readLine()) !== false) {
                yield $buffer;
            }

            if (!$this->resource->isEndOfFile()) {
                throw new UnexpectedAvailableContent($this->reference);
            }
        }

        /**
         * @param Closure $generatorHandler
         *
         * @throws CannotOpenFile
         * @throws CannotWriteContentToFile
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public function newContent(Closure $generatorHandler)
        {
            $this->resource = FileResource::writing($this->reference);
            $this->writeContent($generatorHandler);
        }

        /**
         * @param Closure $generatorHandler
         *
         * @throws CannotOpenFile
         * @throws CannotWriteContentToFile
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public function addContent(Closure $generatorHandler)
        {
            $this->resource = FileResource::appending($this->reference);
            $this->writeContent($generatorHandler);
        }
    }
}
