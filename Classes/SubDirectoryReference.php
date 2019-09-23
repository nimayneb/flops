<?php namespace JayBeeR\Flops {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound;

    class SubDirectoryReference extends DirectoryReference
    {
        /**
         * @return DirectoryReference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getParentDirectory(): DirectoryReference
        {
            return $this->getDirectory();
        }

        /**
         * @param string $reference
         *
         * @return SubDirectoryReference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public static function get(string $reference): SubDirectoryReference
        {
            if (RootDirectoryReference::isRootPath($reference)) {
                throw new ReferenceIsNotSubDirectory($reference);
            }

            return parent::get($reference);
        }
    }
}
