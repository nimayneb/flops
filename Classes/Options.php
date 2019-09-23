<?php namespace JayBeeR\Flops {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Permissions\Permissions;

    trait Options
    {
        protected static string $defaultFilePermission = '00770';

        protected static string $defaultFolderPermission = '02770';

        protected static bool $sanitizeInvalidFileNames = false;

        protected static bool $overwriteExistingFiles = false;

        protected static bool $linkingToNoneExistingTarget = false;

        protected static bool $filesOrFoldersCanBeSymbolicLinks = false;

        public static function setDefaultFilePermission(string $permission): void
        {
            static::$defaultFilePermission = Permissions::format($permission);
        }

        public static function setDefaultFolderPermission(string $permission): void
        {
            static::$defaultFolderPermission = Permissions::format($permission);
        }

        public static function getDefaultFilePermission(): string
        {
            return static::$defaultFilePermission;
        }

        public static function getDefaultFolderPermission(): string
        {
            return static::$defaultFolderPermission;
        }

        public static function linkingToNoneExistingFiles(): bool
        {
            return static::$linkingToNoneExistingTarget;
        }

        public static function sanitizeInvalidFileNames(): bool
        {
            return static::$sanitizeInvalidFileNames;
        }

        public static function overwriteExistingFiles(): bool
        {
            return static::$overwriteExistingFiles;
        }

        public static function filesOrFoldersCanBeSymbolicLinks(): bool
        {
            return static::$filesOrFoldersCanBeSymbolicLinks;
        }

        public static function setOptions(
            bool $sanitizeInvalidFileNames = false,
            bool $overwriteExistingFiles = false,
            bool $linkingToNoneExistingFiles = false,
            bool $filesOrFoldersCanBeSymbolicLinks = false
        )
        {
            static::$sanitizeInvalidFileNames = $sanitizeInvalidFileNames;
            static::$overwriteExistingFiles = $overwriteExistingFiles;
            static::$linkingToNoneExistingTarget = $linkingToNoneExistingFiles;
            static::$filesOrFoldersCanBeSymbolicLinks = $filesOrFoldersCanBeSymbolicLinks;
        }
    }
}
