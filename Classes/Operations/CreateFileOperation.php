<?php namespace JayBeeR\Flops\Operations {

    use JayBeeR\Flops\Failures\CannotCreateAlreadyExistingFile;
    use JayBeeR\Flops\Failures\CannotCreateFile;
    use JayBeeR\Flops\Failures\CannotDeleteFile;
    use JayBeeR\Flops\Failures\CannotSetPermission;
    use JayBeeR\Flops\Failures\InvalidCharacterForFileName;
    use JayBeeR\Flops\Failures\ReferenceIsNotFile;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\FileReference;
    use JayBeeR\Flops\LocalFileSystem;
    use JayBeeR\Flops\Properties\ReferenceProperty;
    use JayBeeR\Flops\Reference;

    trait CreateFileOperation
    {
        use ReferenceProperty;
        use DeleteFileOperation;

        /**
         * @param string $fileName
         * @param bool $overwrite
         *
         * @return FileReference
         * @throws CannotCreateAlreadyExistingFile
         * @throws CannotCreateFile
         * @throws CannotDeleteFile
         * @throws CannotSetPermission
         * @throws InvalidCharacterForFileName
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public function createFile(string $fileName, bool $overwrite): FileReference
        {
            Reference::assertValidFullName($fileName);

            $filePath = $this->reference . $fileName;

            if (!$this->ifFileExistsThenTryToDeleteIt($filePath, $overwrite)) {
                throw new CannotCreateAlreadyExistingFile($filePath);
            }

            if (!touch($filePath)) {
                throw new CannotCreateFile($filePath);
            }

            if (!chmod($filePath, LocalFileSystem::getDefaultFilePermission())) {
                throw new CannotSetPermission($filePath);
            }

            return LocalFileSystem::getFile($filePath);
        }
    }
}