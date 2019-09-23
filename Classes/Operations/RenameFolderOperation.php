<?php namespace JayBeeR\Flops\Operations {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\DirectoryReference;
    use JayBeeR\Flops\Failures\CannotDeleteFolder;
    use JayBeeR\Flops\Failures\CannotDeleteNotEmptyFolder;
    use JayBeeR\Flops\Failures\CannotRenameFile;
    use JayBeeR\Flops\Failures\CannotRenameToExistingFileName;
    use JayBeeR\Flops\Failures\InvalidCharacterForFileName;
    use JayBeeR\Flops\Properties\DirectoryProperty;
    use JayBeeR\Flops\Properties\NameAndExtension;
    use JayBeeR\Flops\Properties\ReferenceProperty;

    trait RenameFolderOperation
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
         * @return DirectoryReference|$this
         * @throws CannotDeleteFolder
         * @throws CannotRenameFile
         * @throws CannotRenameToExistingFileName
         * @throws InvalidCharacterForFileName
         * @throws CannotDeleteNotEmptyFolder
         */
        public function renameTo(string $newName, bool $overwrite = false): DirectoryReference
        {
            if (!$this->ifFolderExistsThenTryToDeleteIt($newName, $overwrite)) {
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
