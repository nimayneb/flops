<?php namespace JayBeeR\Flops {

    use JayBeeR\Flops\Constraints\TimeConstraints;
    use JayBeeR\Flops\Constraints\TimeConstraintsMethods;
    use JayBeeR\Flops\Failures\InvalidCharacterForFileExtension;
    use JayBeeR\Flops\Failures\InvalidCharacterForFileName;
    use JayBeeR\Flops\Failures\InvalidCharacterForFolderName;
    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Operations\FlushOperation;
    use JayBeeR\Flops\Properties\AccessTimeProperty;
    use JayBeeR\Flops\Properties\ChangeInodeTimeProperty;
    use JayBeeR\Flops\Properties\GroupProperty;
    use JayBeeR\Flops\Properties\LogicalProperty;
    use JayBeeR\Flops\Properties\ModificationTimeProperty;
    use JayBeeR\Flops\Properties\NameProperty;
    use JayBeeR\Flops\Properties\OwnerProperty;
    use JayBeeR\Flops\Properties\PermissionsProperty;
    use JayBeeR\Flops\Properties\PhysicalReferenceProperty;
    use JayBeeR\Flops\Properties\RootLinesProperty;

    abstract class Reference implements ReferenceObject, TimeConstraints
    {
        use NameProperty;
        use PermissionsProperty;
        use OwnerProperty;
        use GroupProperty;
        use LogicalProperty;
        use AccessTimeProperty;
        use ModificationTimeProperty;
        use ChangeInodeTimeProperty;
        use PhysicalReferenceProperty;
        use RootLinesProperty;

        use FlushOperation;
        use TimeConstraintsMethods;

        public const WINDOWS_VOLUME = 1;

        public const UNIX_VOLUME = 2;

        public function isReadable(): bool
        {
            return is_readable($this->reference);
        }

        public function isWritable(): bool
        {
            return is_writable($this->reference);
        }

        public function isExecutable(): bool
        {
            return is_executable($this->reference);
        }

        /**
         *
         */
        public function flush(): ReferenceObject
        {
            clearstatcache(true, $this->reference);

            $this->name = null;
            $this->accessTime = null;
            $this->permissions = null;
            $this->changeInodeTime = null;
            $this->modificationTime = null;

            return $this;
        }

        /**
         * @param string $reference
         *
         * @return string
         */
        protected static function ifRelativePathThenPrependCurrentDirectory(string $reference): string
        {
            if ((!empty($reference)) && ('/' !== $reference[0]) && ('\\' !== $reference[0])) {
                $reference = getcwd() . DIRECTORY_SEPARATOR . $reference;
            }

            return $reference ?: DIRECTORY_SEPARATOR;
        }

        /**
         * @param string $reference
         *
         * @return string
         * @throws InvalidReferenceForEnvironment
         */
        protected static function getAbsolutePath(string $reference): string
        {
            $reference = static::ifRelativePathThenPrependCurrentDirectory(ltrim($reference));

            switch (static::getVolumeType($reference)) {
                case static::WINDOWS_VOLUME:
                {
                    $reference = static::sanitizeFilePath($reference, '\\');

                    break;
                }

                case static::UNIX_VOLUME:
                {
                    $reference = static::sanitizeFilePath($reference, '/');

                    break;
                }
            }

            return $reference;
        }

        /**
         * @param string $reference
         *
         * @return int
         * @throws InvalidReferenceForEnvironment
         */
        protected static function getVolumeType(string $reference): int
        {
            if (':\\' === substr($reference, 1, 2)) {
                if (DIRECTORY_SEPARATOR !== '\\') {
                    throw new InvalidReferenceForEnvironment($reference);
                }

                $type = static::WINDOWS_VOLUME;
            } else {
                $type = static::UNIX_VOLUME;
            }

            return $type;
        }

        /**
         * @param string $reference
         * @param string $separator
         *
         * @return string
         */
        protected static function sanitizeFilePath(string $reference, $separator): string
        {
            $normalizePath = str_replace(['/', '\\'], $separator, $reference);
            $pathElements = array_filter(explode($separator, $normalizePath));

            return $separator . implode($separator, $pathElements);
        }

        /**
         * @param string $reference
         *
         * @throws ReferenceNotFound
         */
        public static function assertReferenceExists(string $reference): void
        {
            if (!file_exists($reference)) {
                throw new ReferenceNotFound($reference);
            }
        }

        /**
         * @param string $name
         *
         * @throws InvalidCharacterForFileName
         */
        public static function assertValidFullName(string $name): void
        {
            if (
                (strpos($name, '..'))
                || (strpos($name, '/'))
                || (strpos($name, '\\'))
            ) {
                throw new InvalidCharacterForFileName($name);
            }
        }

        /**
         * @param string $name
         *
         * @throws InvalidCharacterForFolderName
         */
        public static function assertValidFolderName(string $name): void
        {
            if (
                (strpos($name, '..'))
                || (strpos($name, '/'))
                || (strpos($name, '\\'))
            ) {
                throw new InvalidCharacterForFolderName($name);
            }
        }

        /**
         * @param string $name
         *
         * @throws InvalidCharacterForFileName
         */
        public static function assertValidName(string $name): void
        {
            if (
                (strpos($name, '.'))
                || (strpos($name, '..'))
                || (strpos($name, '/'))
                || (strpos($name, '\\'))
            ) {
                throw new InvalidCharacterForFileName($name);
            }
        }

        /**
         * @param string $name
         *
         * @throws InvalidCharacterForFileExtension
         */
        public static function assertValidExtension(string $name): void
        {
            if (
                (strpos($name, '..'))
                || (strpos($name, '/'))
                || (strpos($name, '\\'))
            ) {
                throw new InvalidCharacterForFileExtension($name);
            }
        }
    }
}