<?php namespace JayBeeR\Flops\Operations {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\DirectoryReference;
    use JayBeeR\Flops\FileReference;

    interface FileOperations
    {
        public function deleteFile(): void;

        public function moveTo(DirectoryReference $pathReference, string $newName = null, bool $overwrite = false): FileReference;

        public function changeName(string $newName, bool $overwrite = false): FileReference;

        public function changeExtension(string $newExtension, bool $overwrite = false): FileReference;

        public function omitExtension(bool $overwrite = false): FileReference;

        public function copyTo(DirectoryReference $pathReference, string $newName = null, bool $overwrite = false): FileReference;
    }
}
