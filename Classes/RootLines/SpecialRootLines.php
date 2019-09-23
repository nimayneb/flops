<?php namespace JayBeeR\Flops\RootLines {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Reference;

    trait SpecialRootLines
    {
        use RootLineCollector;

        /**
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getGroupIdRights(): array
        {
            return $this->collect(
                fn () => $this->getGroupIdRight($this->getPermissions()->getSpecialPermission()),
                fn (Reference $reference) => $this->getGroupIdRight($this->getSpecialPermission($reference))
            );
        }

        /**
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getStickyRights(): array
        {
            return $this->collect(
                fn () => $this->getStickyRight($this->getPermissions()->getSpecialPermission()),
                fn (Reference $reference) => $this->getStickyRight($this->getSpecialPermission($reference))
            );
        }

        /**
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getUserIdRights(): array
        {
            return $this->collect(
                fn () => $this->getUserIdRight($this->getPermissions()->getSpecialPermission()),
                fn (Reference $reference) => $this->getUserIdRight($this->getSpecialPermission($reference))
            );
        }
    }
}
