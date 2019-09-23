<?php namespace JayBeeR\Flops {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Failures\CannotLinkToReference;
    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotFile;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSymbolicLink;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Failures\UnsupportedReferenceFound;
    use JayBeeR\Flops\Operations\DeleteFolderOperation;
    use JayBeeR\Flops\Operations\LinkOperations;
    use JayBeeR\Flops\Operations\RenameFileOperation;
    use JayBeeR\Flops\Properties\DirectoryProperty;
    use JayBeeR\Flops\Properties\ExtensionProperty;
    use JayBeeR\Flops\Properties\NameProperty;

    class SymbolicReference extends Reference implements LinkOperations
    {
        use NameProperty;
        use DeleteFolderOperation;
        use RenameFileOperation;
        use DirectoryProperty;
        use ExtensionProperty;

        /**
         * @return DirectoryReference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getBaseDirectory(): DirectoryReference
        {
            return $this->getDirectory();
        }

        /**
         * @param string $reference
         *
         * @return SymbolicReference
         * @throws ReferenceIsNotSymbolicLink
         */
        public static function get(string $reference): ReferenceObject
        {
            if (!is_link($reference)) {
                throw new ReferenceIsNotSymbolicLink($reference);
            }

            return new static($reference);
        }

        /**
         * @return Reference
         * @throws ReferenceNotFound
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotFile
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceIsNotSymbolicLink
         * @throws UnsupportedReferenceFound
         */
        public function getTargetLink(): Reference
        {
            return LocalFileSystem::get(readlink($this->reference));
        }

        /**
         * @param string $reference
         *
         * @return bool
         */
        public static function isFile(string $reference): bool
        {
            return (
                (is_file($reference))
                && (
                    LocalFileSystem::filesOrFoldersCanBeSymbolicLinks()
                    || (!is_link($reference))
                )
            );
        }

        /**
         * @param string $reference
         *
         * @return bool
         */
        public static function isFolder(string $reference): bool
        {
            return (
                (is_dir($reference))
                && (
                    LocalFileSystem::filesOrFoldersCanBeSymbolicLinks()
                    || (!is_link($reference))
                )
            );
        }

        /**
         * @param Reference $pathReference
         * @param string|null $newName
         * @param bool $existence
         *
         * @return Reference
         * @throws CannotLinkToReference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotFile
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceIsNotSymbolicLink
         * @throws ReferenceNotFound
         * @throws UnsupportedReferenceFound
         */
        public function linkTo(Reference $pathReference, string $newName = null, bool $existence = false): Reference
        {
            $targetPath = (string)$pathReference;

            if (!symlink($pathReference, $this->reference)) {
                throw new CannotLinkToReference($this->reference, $pathReference);
            }

            return LocalFileSystem::get($pathReference);
        }

        public function moveTo(DirectoryReference $pathReference, string $newName = null, bool $overwrite = false)
        {

        }

        public function copyTo(DirectoryReference $pathReference, string $newName = null, bool $overwrite = false)
        {

        }
    }
}
