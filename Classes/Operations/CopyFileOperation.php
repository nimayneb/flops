<?php namespace JayBeeR\Flops\Operations {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\DirectoryReference;
    use JayBeeR\Flops\Failures\CannotCopyFile;
    use JayBeeR\Flops\Failures\CannotCopyToExistingName;
    use JayBeeR\Flops\Failures\ReferenceIsNotFile;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\FileReference;
    use JayBeeR\Flops\LocalFileSystem;
    use JayBeeR\Flops\Properties\ReferenceProperty;

    trait CopyFileOperation
    {
        use ReferenceProperty;
        use DeleteFolderOperation;

        /**
         * @param DirectoryReference $pathReference
         * @param string|null $newName
         * @param bool $overwrite
         *
         * @return FileReference|$this
         * @throws CannotCopyFile
         * @throws CannotCopyToExistingName
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public function copyTo(DirectoryReference $pathReference, string $newName = null, bool $overwrite = false): FileReference
        {
            $newPath = $pathReference;

            if (empty($newName)) {
                $newPath .= $this->getFullName();
            } else {
                $newPath = $this->getNameWithExtension($newName);
            }

            if (!$this->ifFileExistsThenTryToDeleteIt($newPath, $overwrite)) {
                throw new CannotCopyToExistingName($newPath);
            }

            if (!copy($this->reference, $newPath)) {
                throw new CannotCopyFile($this->reference, $newPath);
            }

            return LocalFileSystem::getFile($newPath);
        }
    }
}
