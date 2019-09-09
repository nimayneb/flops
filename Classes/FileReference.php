<?php namespace JayBeeR\Flops {

    use JayBeeR\Flops\Constraints\ContentConstraints;
    use JayBeeR\Flops\Constraints\ContentConstraintsMethods;
    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotFile;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Operations\CopyFileOperation;
    use JayBeeR\Flops\Operations\CreateFileOperation;
    use JayBeeR\Flops\Operations\DeleteFileOperation;
    use JayBeeR\Flops\Operations\FileOperations;
    use JayBeeR\Flops\Operations\MoveFileOperation;
    use JayBeeR\Flops\Operations\RenameFileOperation;
    use JayBeeR\Flops\Properties\ContentProperty;
    use JayBeeR\Flops\Properties\DirectoryProperty;
    use JayBeeR\Flops\Properties\ExtensionProperty;
    use JayBeeR\Flops\Properties\HashProperty;
    use JayBeeR\Flops\Properties\NameAndExtension;
    use JayBeeR\Flops\Properties\SizeProperty;

    class FileReference extends Reference implements FileOperations, ContentConstraints
    {
        use DirectoryProperty;
        use ExtensionProperty;
        use HashProperty;
        use SizeProperty;
        use ContentProperty;

        use NameAndExtension;

        use DeleteFileOperation;
        use MoveFileOperation;
        use RenameFileOperation;
        use CopyFileOperation;
        use CreateFileOperation;

        use ContentConstraintsMethods;

        /**
         * @return SubDirectoryReference
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
         *
         */
        public function flush(): ReferenceObject
        {
            parent::flush();

            $this->hash = null;
            $this->size = null;
            $this->extension = null;

            return $this;
        }

        /**
         * @param string $reference
         *
         * @return FileReference
         * @throws ReferenceNotFound
         * @throws ReferenceIsNotFile
         */
        public static function get(string $reference): ReferenceObject
        {
            if (!is_file($reference)) {
                static::assertReferenceExists($reference);

                throw new ReferenceIsNotFile($reference);
            }

            return parent::get($reference);
        }

        /**
         * @return FileOperations
         */
        public function _fileOperations(): FileOperations
        {
            return $this;
        }
    }
}