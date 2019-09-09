<?php namespace JayBeeR\Flops\RootLines {

    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Reference;

    trait OwnerRootLine
    {
        use RootLineCollector;

        /**
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getOwnerExecutions(): array
        {
            return $this->collect(
                fn () => $this->getExecutionAccessRight($this->getPermissions()->getOwnerPermission()),
                fn (Reference $reference) => $this->getExecutionAccessRight($this->getOwnerPermission($reference))
            );
        }

        /**
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getOwnerWrites(): array
        {
            return $this->collect(
                fn () => $this->getWriteAccessRight($this->getPermissions()->getOwnerPermission()),
                fn (Reference $reference) => $this->getWriteAccessRight($this->getOwnerPermission($reference))
            );
        }

        /**
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getOwnerReads(): array
        {
            return $this->collect(
                fn () => $this->getReadAccessRight($this->getPermissions()->getOwnerPermission()),
                fn (Reference $reference) => $this->getReadAccessRight($this->getOwnerPermission($reference))
            );
        }
    }
}