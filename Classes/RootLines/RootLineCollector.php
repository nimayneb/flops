<?php namespace JayBeeR\Flops\RootLines {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use Closure;
    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Permissions\AccessPermission;
    use JayBeeR\Flops\Permissions\SpecialPermission;
    use JayBeeR\Flops\Properties\DirectoryProperty;
    use JayBeeR\Flops\Properties\PermissionsProperty;

    trait RootLineCollector
    {
        use DirectoryProperty;
        use PermissionsProperty;

        /**
         * @param Closure $referenceHandler
         * @param Closure $parentReferenceHandler
         *
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function collect(Closure $referenceHandler, Closure $parentReferenceHandler): array
        {
            $rootLines = [];
            $rootLines[(string)$this] = $referenceHandler();

            {
                $parent = $this->getDirectory();

                do {
                    $rootLines[(string)$parent] = $parentReferenceHandler($parent);
                } while ($parent = $parent->getDirectory());
            }

            return array_filter($rootLines, fn ($item) => null !== $item);
        }

        /**
         * @param AccessPermission $reference
         *
         * @return bool
         */
        protected function getWriteAccessRight(AccessPermission $reference): bool
        {
            return $reference->hasWriteAccessRight();
        }

        /**
         * @param AccessPermission $reference
         *
         * @return bool
         */
        protected function getExecutionAccessRight(AccessPermission $reference): bool
        {
            return $reference->hasExecutionAccessRight();
        }

        /**
         * @param AccessPermission $reference
         *
         * @return bool
         */
        protected function getReadAccessRight(AccessPermission $reference): bool
        {
            return $reference->hasReadAccessRight();
        }

        /**
         * @param SpecialPermission|SpecialRootLines $reference
         *
         * @return bool
         */
        protected function getUserIdRight(SpecialPermission $reference): bool
        {
            return $reference->hasUserIdRight();
        }

        /**
         * @param SpecialPermission|SpecialRootLines $reference
         *
         * @return bool
         */
        protected function getStickyRight(SpecialPermission $reference): bool
        {
            return $reference->hasStickyRight();
        }

        /**
         * @param SpecialPermission|SpecialRootLines $reference
         *
         * @return bool
         */
        protected function getGroupIdRight(SpecialPermission $reference): bool
        {
            return $reference->hasGroupIdRight();
        }
    }
}
