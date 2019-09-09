<?php namespace JayBeeR\Flops\Properties {

    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotFile;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSymbolicLink;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Failures\UnsupportedReferenceFound;

    trait LogicalProperty
    {
        use ReferenceProperty;
        use PhysicalReferenceProperty;

        protected ?bool $logical = null;

        /**
         * @return bool
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotFile
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceIsNotSymbolicLink
         * @throws UnsupportedReferenceFound
         * @throws ReferenceNotFound
         */
        public function isLogical(): bool
        {
            return $this->logical ??= ($this->getPhysicalReference() !== $this->reference);
        }
    }
}