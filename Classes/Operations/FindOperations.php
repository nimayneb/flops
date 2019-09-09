<?php namespace JayBeeR\Flops\Operations {

    use Closure;
    use Generator;
    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotFile;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSymbolicLink;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Failures\UnsupportedReferenceFound;
    use JayBeeR\Flops\FileReference;
    use JayBeeR\Flops\LocalFileSystem;
    use JayBeeR\Flops\SymbolicReference;

    trait FindOperations
    {
        /**
         * @param string $wildcardPattern
         * @param bool $recursive
         *
         * @return Generator|FileReference[]
         * @throws ReferenceNotFound
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         */
        public function findAllFolders(string $wildcardPattern = null, bool $recursive = false): Generator
        {
            foreach ($this->findAllOf($this->reference, fn ($file) => SymbolicReference::isFolder($file), $wildcardPattern, $recursive) as $file) {
                yield LocalFileSystem::getFolder($file);
            }
        }

        /**
         * @param string $wildcardPattern
         * @param bool $recursive
         *
         * @return Generator|FileReference[]
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public function findAllFiles(string $wildcardPattern = null, bool $recursive = false): Generator
        {
            foreach ($this->findAllOf($this->reference, fn ($file) => SymbolicReference::isFile($file), $wildcardPattern, $recursive) as $file) {
                yield LocalFileSystem::getFile($file);
            }
        }

        /**
         * @param string $wildcardPattern
         * @param bool $recursive
         *
         * @return Generator|FileReference[]
         * @throws ReferenceIsNotSymbolicLink
         */
        public function findAllSymbolicLinks(string $wildcardPattern = null, bool $recursive = false): Generator
        {
            foreach ($this->findAllOf($this->reference, fn ($file) => is_link($file), $wildcardPattern, $recursive) as $file) {
                yield LocalFileSystem::getSymbolicLink($file);
            }
        }

        /**
         * @param string $wildcardPattern
         * @param bool $recursive
         *
         * @return Generator|FileReference[]
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotFile
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceIsNotSymbolicLink
         * @throws ReferenceNotFound
         * @throws UnsupportedReferenceFound
         */
        public function findAll(string $wildcardPattern = null, bool $recursive = false): Generator
        {
            foreach ($this->findAllOf($this->reference, fn ($file) => $file, $wildcardPattern, $recursive) as $file) {
                yield LocalFileSystem::get($file);
            }
        }

        /**
         * @param string $reference
         * @param Closure $filterHandler
         * @param string $wildcardPattern
         * @param bool $recursive
         *
         * @return Generator|FileReference[]
         */
        protected function findAllOf(string $reference, Closure $filterHandler, ?string $wildcardPattern, bool $recursive): Generator
        {
            $directories = [$reference];

            while (null !== ($directory = array_pop($directories))) {
                if ($directoryResource = opendir($directory)) {
                    while (false !== ($file = readdir($directoryResource))) {
                        if ($file == '.' || $file == '..') {
                            continue;
                        }

                        $path = $directory . '/' . $file;

                        if (is_dir($path)) {
                            $directories[] = $path;
                        }

                        if ($filterHandler($path) && ((empty($wildcardPattern)) || ($this->hasWildcardMatch($path, $wildcardPattern)))) {
                            yield $path;
                        }
                    }

                    closedir($directoryResource);
                }

                if (!$recursive) {
                    break;
                }
            }
        }
    }
}