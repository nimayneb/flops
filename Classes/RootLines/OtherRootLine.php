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

    trait OtherRootLine
    {
        use RootLineCollector;

        /**
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getOtherExecutions(): array
        {
            return $this->collect(
                fn () => $this->getExecutionAccessRight($this->getPermissions()->getOtherPermission()),
                fn (Reference $reference) => $this->getExecutionAccessRight($this->getOtherPermission($reference))
            );
        }

        /**
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getOtherWrites(): array
        {
            return $this->collect(
                fn () => $this->getWriteAccessRight($this->getPermissions()->getOtherPermission()),
                fn (Reference $reference) => $this->getWriteAccessRight($this->getOtherPermission($reference))
            );
        }

        /**
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getOtherReads(): array
        {
            return $this->collect(
                fn () => $this->getReadAccessRight($this->getPermissions()->getOtherPermission()),
                fn (Reference $reference) => $this->getReadAccessRight($this->getOtherPermission($reference))
            );
        }
    }
}
