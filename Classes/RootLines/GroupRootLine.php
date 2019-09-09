<?php namespace JayBeeR\Flops\RootLines {

    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Reference;

    trait GroupRootLine
    {
        use RootLineCollector;

        /**
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getGroupExecutions(): array
        {
            return $this->collect(
                fn () => $this->getExecutionAccessRight($this->getPermissions()->getGroupPermission()),
                fn (Reference $reference) => $this->getExecutionAccessRight($this->getGroupPermission($reference))
            );
        }

        /**
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getGroupWrites(): array
        {
            return $this->collect(
                fn () => $this->getWriteAccessRight($this->getPermissions()->getGroupPermission()),
                fn (Reference $reference) => $this->getWriteAccessRight($this->getGroupPermission($reference))
            );
        }

        /**
         * @return array
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceNotFound
         */
        public function getGroupReads(): array
        {
            return $this->collect(
                fn () => $this->getReadAccessRight($this->getPermissions()->getGroupPermission()),
                fn (Reference $reference) => $this->getReadAccessRight($this->getGroupPermission($reference))
            );
        }
    }
}