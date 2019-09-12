<?php namespace JayBeeR\Flops\Operations {

    use JayBeeR\Flops\DirectoryReference;

    interface FolderOperations
    {
        public function deleteFolder(): void;

        public function moveTo(DirectoryReference $pathReference, string $newName = null, bool $overwrite = false): DirectoryReference;

        public function renameTo(string $newName, bool $overwrite = false): DirectoryReference;

        public function copyTo(DirectoryReference $pathReference, string $newName = null, bool $overwrite = false): DirectoryReference;

        public function createFolders(string $pathName, bool $overwrite = false): DirectoryReference;

        public function createFolder(string $pathName, bool $overwrite = false): DirectoryReference;
    }
}