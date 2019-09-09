<?php namespace JayBeeR\Flops\RootLines {

    use JayBeeR\Flops\Failures\CannotGetPermissions;
    use JayBeeR\Flops\Operations\FlushOperation;
    use JayBeeR\Flops\Permissions\AccessPermission;
    use JayBeeR\Flops\Permissions\SpecialPermission;
    use JayBeeR\Flops\Properties\ReferenceProperty;
    use JayBeeR\Flops\Reference;
    use JayBeeR\Flops\ReferenceObject;

    class RootLines implements ReferenceObject
    {
        use ReferenceProperty;
        use FlushOperation;

        use SpecialRootLines;
        use GroupRootLine;
        use OwnerRootLine;
        use OtherRootLine;

        use CounterRootLine;

        /**
         * @param Reference|SpecialRootLines $reference
         *
         * @return SpecialPermission
         * @throws CannotGetPermissions
         */
        protected function getSpecialPermission(Reference $reference): SpecialPermission
        {
            return $reference->getPermissions()->getSpecialPermission();
        }

        /**
         * @param Reference|OwnerRootLine $reference
         *
         * @return AccessPermission
         * @throws CannotGetPermissions
         */
        protected function getOwnerPermission(Reference $reference): AccessPermission
        {
            return $reference->getPermissions()->getOwnerPermission();
        }

        /**
         * @param Reference|OwnerRootLine $reference
         *
         * @return AccessPermission
         * @throws CannotGetPermissions
         */
        protected function getGroupPermission(Reference $reference): AccessPermission
        {
            return $reference->getPermissions()->getGroupPermission();
        }

        /**
         * @param Reference|OwnerRootLine $reference
         *
         * @return AccessPermission
         * @throws CannotGetPermissions
         */
        protected function getOtherPermission(Reference $reference): AccessPermission
        {
            return $reference->getPermissions()->getOtherPermission();
        }

        /**
         * @param string $reference
         *
         * @return RootLines
         */
        public static function get(string $reference): RootLines
        {
            return new static($reference);
        }
    }
}