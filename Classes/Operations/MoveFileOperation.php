<?php namespace JayBeeR\Flops\Operations {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\DirectoryReference;
    use JayBeeR\Flops\Failures\CannotMoveFile;
    use JayBeeR\Flops\Failures\CannotMoveToExistingName;
    use JayBeeR\Flops\FileReference;
    use JayBeeR\Flops\Properties\ExtensionProperty;
    use JayBeeR\Flops\Properties\NameProperty;
    use JayBeeR\Flops\Properties\ReferenceProperty;

    trait MoveFileOperation
    {
        use ReferenceProperty;
        use FlushOperation;
        use NameProperty;
        use ExtensionProperty;
        use DeleteFolderOperation;

        /**
         * @param DirectoryReference $pathReference
         * @param string|null $newName
         * @param bool $overwrite
         *
         * @return FileReference|$this
         * @throws CannotMoveFile
         * @throws CannotMoveToExistingName
         */
        public function moveTo(DirectoryReference $pathReference, string $newName = null, bool $overwrite = false): FileReference
        {
            $newPath = $pathReference . DIRECTORY_SEPARATOR;

            if (empty($newName)) {
                $newPath .= $this->getFullName();
            } else {
                $newPath .= $this->getNameWithExtension($newName);
            }

            if (!$this->ifFileExistsThenTryToDeleteIt($newPath, $overwrite)) {
                throw new CannotMoveToExistingName($newPath);
            }

            if (!rename($this->reference, $newPath)) {
                throw new CannotMoveFile($this->reference, $newName);
            }

            $this->flush();
            $this->reference = $newPath;

            return $this;
        }
    }
}
