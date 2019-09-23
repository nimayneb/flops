<?php namespace JayBeeR\Flops {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use Closure;
    use JayBeeR\Flops\Failures\CannotChangeDirectory;
    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotFile;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSymbolicLink;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Failures\UnsupportedReferenceFound;

    class LocalFileSystem
    {
        use Options;

        /**
         * @param DirectoryReference $pathReference
         *
         * @throws CannotChangeDirectory
         */
        public static function changeWorkingDirectory(DirectoryReference $pathReference)
        {
            if (!chdir((string)$pathReference)) {
                throw new CannotChangeDirectory($pathReference);
            }
        }

        /**
         * @param DirectoryReference $pathReference
         * @param Closure $anyHandler
         *
         * @throws CannotChangeDirectory
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public static function temporaryChangeWorkingDirectory(DirectoryReference $pathReference, Closure $anyHandler)
        {
            $previousWorkingDirectory = getcwd();

            {
                static::changeWorkingDirectory($pathReference);
                $anyHandler();
                static::changeWorkingDirectory(static::getFolder($previousWorkingDirectory));
            }
        }

        /**
         * @param string $reference
         *
         * @return Reference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotFile
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceIsNotSymbolicLink
         * @throws ReferenceNotFound
         * @throws UnsupportedReferenceFound
         */
        public static function get(string $reference): Reference
        {
            if (SymbolicReference::isFile($reference)) {
                $reference = static::getFile($reference);
            } elseif (SymbolicReference::isFolder($reference)) {
                $reference = static::getFolder($reference);
            } elseif (is_link($reference)) {
                $reference = static::getSymbolicLink($reference);
            } else {
                Reference::assertReferenceExists($reference);

                throw new UnsupportedReferenceFound($reference);
            }

            return $reference;
        }

        /**
         * @param string $fileReference
         *
         * @return DirectoryReference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public static function getFolder(string $fileReference): DirectoryReference
        {
            if (RootDirectoryReference::isRootPath($fileReference)) {
                $reference = static::getRootDirectory($fileReference);
            } else {
                $reference = static::getSubDirectory($fileReference);
            }

            return $reference;
        }

        /**
         * @param string $fileReference
         *
         * @return FileReference
         * @throws ReferenceIsNotFile
         * @throws ReferenceNotFound
         */
        public static function getFile(string $fileReference): FileReference
        {
            return FileReference::get($fileReference);
        }

        /**
         * @param string $directoryReference
         *
         * @return RootDirectoryReference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceNotFound
         */
        public static function getRootDirectory(string $directoryReference): RootDirectoryReference
        {
            return RootDirectoryReference::get($directoryReference);
        }

        /**
         * @param string $directoryReference
         *
         * @return SubDirectoryReference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public static function getSubDirectory(string $directoryReference): SubDirectoryReference
        {
            return SubDirectoryReference::get($directoryReference);
        }

        /**
         * @param string $symbolicReference
         *
         * @return SymbolicReference
         * @throws ReferenceIsNotSymbolicLink
         */
        public static function getSymbolicLink(string $symbolicReference): SymbolicReference
        {
            return SymbolicReference::get($symbolicReference);
        }
    }
}
