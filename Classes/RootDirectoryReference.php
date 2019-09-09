<?php namespace JayBeeR\Flops {

    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Properties\VolumeProperty;

    class RootDirectoryReference extends DirectoryReference
    {
        use VolumeProperty;

        /**
         * @param string $reference
         *
         * @return RootDirectoryReference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceNotFound
         */
        public static function get(string $reference): RootDirectoryReference
        {
            return parent::get($reference);
        }

        /**
         * @param string $reference
         *
         * @return bool
         */
        public static function isRootPath(string $reference)
        {
            $directoryReference = rtrim($reference, '/\\');

            return (
                (empty($directoryReference))
                || ((2 === strlen($directoryReference)) && (':' === $directoryReference[1]))
            );
        }

        /**
         * @param mixed $directoryReference
         *
         * @return bool
         */
        public static function isRootObject($directoryReference): bool
        {
            return ($directoryReference instanceof RootDirectoryReference);
        }
    }
}