<?php namespace JayBeeR\Flops\Operations {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\DirectoryReference;
    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\LocalFileSystem;
    use JayBeeR\Flops\Properties\ReferenceProperty;

    trait CopyFolderOperation
    {
        use ReferenceProperty;
        use DeleteFolderOperation;

        /**
         * @param DirectoryReference $pathReference
         * @param string|null $newName
         * @param bool $overwrite
         *
         * @return DirectoryReference|$this
         * @throws ReferenceNotFound
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         */
        public function copyTo(DirectoryReference $pathReference, string $newName = null, bool $overwrite = false): DirectoryReference
        {
            return LocalFileSystem::getFolder($pathReference);
        }
    }
}
