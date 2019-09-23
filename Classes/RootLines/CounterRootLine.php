<?php namespace JayBeeR\Flops\RootLines {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\DirectoryReference;
    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Operations\FindOperations;

    trait CounterRootLine
    {
        use RootLineCollector;
        use FindOperations;

        /**
         * @param string|null $wildcardPattern
         *
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getCountOfFiles(string $wildcardPattern = null): array
        {
            return $this->collect(
                fn () => is_dir($this->reference) ? iterator_count($this->findAllFiles($wildcardPattern)) : null,
                fn (DirectoryReference $reference) => iterator_count($reference->findAllFiles($wildcardPattern))
            );
        }

        /**
         * @param string $wildcardPattern
         *
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getCountOfFolders(string $wildcardPattern = null): array
        {
            return $this->collect(
                fn () => is_dir($this->reference) ? iterator_count($this->findAllFolders($wildcardPattern)) : null,
                fn (DirectoryReference $reference) => iterator_count($reference->findAllFolders($wildcardPattern))
            );
        }

        /**
         * @param string|null $wildcardPattern
         *
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getCountOfSymbolicLinks(string $wildcardPattern = null): array
        {
            return $this->collect(
                fn () => is_dir($this->reference) ? iterator_count($this->findAllSymbolicLinks($wildcardPattern)) : null,
                fn (DirectoryReference $reference) => iterator_count($reference->findAllSymbolicLinks($wildcardPattern))
            );
        }
    }
}
