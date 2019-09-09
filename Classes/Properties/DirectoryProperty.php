<?php namespace JayBeeR\Flops\Properties {

    use JayBeeR\Flops\DirectoryReference;
    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotFile;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound as ReferenceNotFoundAlias;
    use JayBeeR\Flops\RootDirectoryReference;
    use JayBeeR\Flops\SubDirectoryReference;

    trait DirectoryProperty
    {
        use ReferenceProperty;

        protected ?DirectoryReference $directory = null;

        /**
         * @return DirectoryReference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFoundAlias
         */
        public function getDirectory(): ?DirectoryReference
        {
            $directoryReference = pathinfo($this->reference, PATHINFO_DIRNAME);

            if (RootDirectoryReference::isRootObject($this)) {
                $this->directory = null;
            } elseif (RootDirectoryReference::isRootPath($directoryReference)) {
                $this->directory ??= RootDirectoryReference::get($directoryReference);
            } else {
                $this->directory ??= SubDirectoryReference::get($directoryReference);
            }

            return $this->directory;
        }

        /**
         * @return RootDirectoryReference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotFile
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFoundAlias
         */
        public function getRootDirectory(): ?RootDirectoryReference
        {
            $parent = $this->getDirectory();

            if (!RootDirectoryReference::isRootObject($parent)) {
                $parent = $parent->getRootDirectory();
            }

            return $parent;
        }
    }
}