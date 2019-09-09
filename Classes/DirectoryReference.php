<?php namespace JayBeeR\Flops {

    use JayBeeR\Flops\Constraints\TimeConstraints;
    use JayBeeR\Flops\Constraints\TimeConstraintsMethods;
    use JayBeeR\Flops\Failures\CannotChangeDirectory;
    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotFile;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Operations\CopyFolderOperation;
    use JayBeeR\Flops\Operations\CreateFolderOperation;
    use JayBeeR\Flops\Operations\DeleteFolderOperation;
    use JayBeeR\Flops\Operations\FindOperations;
    use JayBeeR\Flops\Operations\FolderOperations;
    use JayBeeR\Flops\Operations\MoveFolderOperation;
    use JayBeeR\Flops\Operations\RenameFolderOperation;
    use JayBeeR\Flops\Properties\DirectoryProperty;

    abstract class DirectoryReference extends Reference implements Directory, FolderOperations, TimeConstraints
    {
        use DirectoryProperty;

        use DeleteFolderOperation;
        use CreateFolderOperation;
        use MoveFolderOperation;
        use RenameFolderOperation;
        use CopyFolderOperation;
        use FindOperations;

        use TimeConstraintsMethods;
        use WildcardMatcher;

        /**
         * @param string $reference
         *
         * @return SubDirectoryReference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceNotFound
         */
        public static function get(string $reference): ReferenceObject
        {
            $reference = parent::getAbsolutePath(rtrim($reference, '/\\'));

            if (!is_dir($reference)) {
                static::assertReferenceExists($reference);

                throw new ReferenceIsNotDirectory($reference);
            }

            // TODO: better solution to prevent double slashing
            if ($reference !== DIRECTORY_SEPARATOR) {
                $reference .= DIRECTORY_SEPARATOR;
            }

            $directoryReference = parent::get($reference);

            return $directoryReference;
        }

        /**
         * @throws CannotChangeDirectory
         */
        public function asWorkingDirectory()
        {
            LocalFileSystem::changeWorkingDirectory($this);
        }

        /**
         * @param string $reference
         *
         * @return FileReference
         * @throws Failures\InvalidCharacterForFileName
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public function getFile(string $reference)
        {
            Reference::assertValidName($reference);

            return LocalFileSystem::getFile($this->reference . $reference);
        }

        /**
         * @param string $reference
         *
         * @return DirectoryReference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getFolder(string $reference): DirectoryReference
        {
            return LocalFileSystem::getFolder($this->reference . $reference);
        }

        /**
         * @param string $reference
         *
         * @return bool
         */
        public function existsFile(string $reference)
        {
            return SymbolicReference::isFile($reference);
        }

        /**
         * @param string $reference
         *
         * @return bool
         */
        public function existsFolder(string $reference)
        {
            return SymbolicReference::isFolder($reference);
        }

        /**
         * @return FolderOperations
         */
        public function _folderOperations(): FolderOperations
        {
            return $this;
        }
    }
}