<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Failures\InvalidReferenceForEnvironment;
    use JayBeeR\Flops\Failures\ReferenceIsNotDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotFile;
    use JayBeeR\Flops\Failures\ReferenceIsNotSubDirectory;
    use JayBeeR\Flops\Failures\ReferenceIsNotSymbolicLink;
    use JayBeeR\Flops\Failures\ReferenceNotFound;
    use JayBeeR\Flops\Failures\UnsupportedReferenceFound;
    use JayBeeR\Flops\LocalFileSystem;
    use JayBeeR\Flops\Reference;

    trait PhysicalReferenceProperty
    {
        use ReferenceProperty;

        protected Reference $physicalReference;

        /**
         * @return Reference
         * @throws InvalidReferenceForEnvironment
         * @throws ReferenceIsNotDirectory
         * @throws ReferenceIsNotFile
         * @throws ReferenceIsNotSubDirectory
         * @throws ReferenceIsNotSymbolicLink
         * @throws UnsupportedReferenceFound
         * @throws ReferenceNotFound
         */
        public function getPhysicalReference(): Reference
        {
            return LocalFileSystem::get(realpath($this->reference));
        }
    }
}
