<?php namespace JayBeeR\Flops\Operations {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\DirectoryReference;
    use JayBeeR\Flops\Failures\CannotCreateAlreadyExistingFolder;
    use JayBeeR\Flops\Failures\CannotCreateFolder;
    use JayBeeR\Flops\Failures\CannotDeleteFolder;
    use JayBeeR\Flops\Failures\CannotDeleteNotEmptyFolder;
    use JayBeeR\Flops\Failures\InvalidCharacterForFolderName;
    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\LocalFileSystem;
    use JayBeeR\Flops\Properties\ReferenceProperty;
    use JayBeeR\Flops\Reference;

    trait CreateFolderOperation
    {
        use ReferenceProperty;
        use DeleteFolderOperation;

        /**
         * @param string $pathName
         * @param bool $overwrite
         *
         * @return DirectoryReference
         * @throws CannotCreateAlreadyExistingFolder
         * @throws CannotCreateFolder
         * @throws CannotDeleteFolder
         * @throws CannotDeleteNotEmptyFolder
         * @throws InvalidCharacterForFolderName
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function createFolder(string $pathName, bool $overwrite = false): DirectoryReference
        {
            Reference::assertValidFolderName($pathName);

            $path = $this->reference . $pathName;

            if (!$this->ifFolderExistsThenTryToDeleteIt($path, $overwrite)) {
                throw new CannotCreateAlreadyExistingFolder($path);
            }

            if (!mkdir($path, LocalFileSystem::getDefaultFolderPermission())) {
                throw new CannotCreateFolder($path);
            }

            return LocalFileSystem::getFolder($path);
        }

        /**
         * @param string $pathName
         * @param bool $overwrite
         *
         * @return DirectoryReference
         * @throws CannotCreateAlreadyExistingFolder
         * @throws CannotCreateFolder
         * @throws CannotDeleteFolder
         * @throws CannotDeleteNotEmptyFolder
         * @throws InvalidCharacterForFolderName
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function createFolders(string $pathName, bool $overwrite = false): DirectoryReference
        {
            Reference::assertValidFolderName($pathName);

            $path = $this->reference . $pathName;

            if (!$this->ifFolderExistsThenTryToDeleteIt($path, $overwrite)) {
                throw new CannotCreateAlreadyExistingFolder($path);
            }

            if (!mkdir($path, LocalFileSystem::getDefaultFolderPermission(), true)) {
                throw new CannotCreateFolder($path);
            }

            return LocalFileSystem::getFolder($path);
        }
    }
}
