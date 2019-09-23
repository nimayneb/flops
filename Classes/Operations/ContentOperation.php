<?php namespace JayBeeR\Flops\Operations {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use Closure;
    use Generator;
    use JayBeeR\Flops\Failures\CannotReadContentFromFile;
    use JayBeeR\Flops\Failures\CannotOpenFile;
    use JayBeeR\Flops\Failures\UnexpectedAvailableContent;
    use JayBeeR\Flops\FileResource;
    use JayBeeR\Flops\Reference;

    class ContentOperation extends Reference
    {
        protected FileResource $resource;

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
         */
        public function truncate(int $length = 0)
        {
            $resource = fopen($this->reference, 'r+');

            if (is_resource($resource)) {
                ftruncate($resource, $length);
                fclose($resource);
            } else {
                throw new CannotOpenFile($this->reference);
            }
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
         * @param string $mode
         *
         * @throws CannotOpenFile
         */
        public function writeContent(Closure $generatorHandler, string $mode): void
        {
            $resource = fopen($this->reference, $mode);

            if (is_resource($resource)) {
                foreach ($generatorHandler() as $content) {
                    fwrite($resource, $content);
                }

                fclose($resource);
            } else {
                throw new CannotOpenFile($this->reference);
            }
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
         * @throws UnexpectedAvailableContent
         */
        public function readContent()
        {
            $resource = fopen($this->reference, 'r');

            if (is_resource($resource)) {
                while (($buffer = fgets($resource)) !== false) {
                    yield $buffer;
                }

                if (!feof($resource)) {
                    throw new UnexpectedAvailableContent($this->reference);
                }

                fclose($resource);
            } else {
                throw new CannotOpenFile($this->reference);
            }
        }

        /**
         * @param Closure $generatorHandler
         *
         * @throws CannotOpenFile
         */
        public function newContent(Closure $generatorHandler)
        {
            $this->writeContent($generatorHandler, 'w');
        }

        /**
         * @param Closure $generatorHandler
         *
         * @throws CannotOpenFile
         */
        public function addContent(Closure $generatorHandler)
        {
            $this->writeContent($generatorHandler, 'a');
        }
    }
}
