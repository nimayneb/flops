<?php namespace JayBeeR\Flops\Operations {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Failures\CannotRenameFile;
    use JayBeeR\Flops\Failures\CannotRenameToExistingFileName;
    use JayBeeR\Flops\Failures\InvalidCharacterForFileExtension;
    use JayBeeR\Flops\Failures\InvalidCharacterForFileName;
    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\FileReference;
    use JayBeeR\Flops\Properties\DirectoryProperty;
    use JayBeeR\Flops\Properties\NameAndExtension;
    use JayBeeR\Flops\Properties\ReferenceProperty;
    use JayBeeR\Flops\Reference;

    trait RenameFileOperation
    {
        use ReferenceProperty;
        use DirectoryProperty;
        use DeleteFolderOperation;
        use FlushOperation;
        use NameAndExtension;

        /**
         * @param string $newName
         * @param bool $overwrite
         *
         * @return FileReference|$this
         * @throws CannotRenameFile
         * @throws CannotRenameToExistingFileName
         * @throws InvalidCharacterForFileName
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function changeName(string $newName, bool $overwrite = false): FileReference
        {
            Reference::assertValidName($newName);

            $newPath = sprintf('%s%s%s.%s', $this->getDirectory(), DIRECTORY_SEPARATOR, $newName, $this->getExtension());

            $this->renameTo($newPath, $overwrite);

            return $this;
        }

        /**
         * @param string $newExtension
         * @param bool $overwrite
         *
         * @return FileReference|$this
         * @throws CannotRenameFile
         * @throws CannotRenameToExistingFileName
         * @throws InvalidCharacterForFileExtension
         * @throws InvalidCharacterForFileName
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function changeExtension(string $newExtension, bool $overwrite = false): FileReference
        {
            Reference::assertValidExtension($newExtension);

            $newPath = sprintf('%s%s%s.%s', $this->getDirectory(), DIRECTORY_SEPARATOR, $this->getName(), $newExtension);

            $this->renameTo($newPath, $overwrite);

            return $this;
        }

        /**
         * @param bool $overwrite
         *
         * @return FileReference|$this
         * @throws CannotRenameFile
         * @throws CannotRenameToExistingFileName
         * @throws InvalidCharacterForFileName
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function omitExtension(bool $overwrite = false): FileReference
        {
            $newPath = sprintf('%s%s%s', $this->getDirectory(), DIRECTORY_SEPARATOR, $this->getName());

            $this->renameTo($newPath, $overwrite);

            $this->extension = null;
            $this->flush();

            return $this;
        }

        /**
         * @param string $newName
         * @param bool $overwrite
         *
         * @return FileReference|$this
         * @throws CannotRenameFile
         * @throws CannotRenameToExistingFileName
         * @throws InvalidCharacterForFileName
         */
        protected function renameTo(string $newName, bool $overwrite): FileReference
        {
            if (!$this->ifFileExistsThenTryToDeleteIt($newName, $overwrite)) {
                throw new CannotRenameToExistingFileName($newName);
            }

            $newFilePath = $this->getNameWithExtension($newName);

            if (!rename($this->reference, $newFilePath)) {
                throw new CannotRenameFile($this->reference, $newFilePath);
            }

            $this->flush();
            $this->reference = $newName;

            return $this;
        }
    }
}
