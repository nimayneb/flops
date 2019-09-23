<?php namespace JayBeeR\Flops\Constraints {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\FileReference;
    use JayBeeR\Flops\Properties\HashProperty;
    use JayBeeR\Flops\Properties\SizeProperty;

    trait ContentConstraintsMethods
    {
        use HashProperty;
        use SizeProperty;

        public function hasSmallerSizeThan(FileReference $reference): bool
        {
            return ($this->getSize() < $reference->getSize());
        }

        public function hasGreaterSizeThan(FileReference $reference): bool
        {
            return ($this->getSize() > $reference->getSize());
        }

        public function hasNotEqualSizeLike(FileReference $reference): bool
        {
            return ($this->getSize() !== $reference->getSize());
        }

        public function hasEqualSizeLike(FileReference $reference): bool
        {
            return ($this->getSize() === $reference->getSize());
        }

        public function hasNotEqualContentLike(FileReference $reference): bool
        {
            $notEqualContent = true;

            if ($this->hasEqualSizeLike($reference)) {
                // TODO: add strategy with fopen, from end and at beginning the file content
                $notEqualContent = ($this->getContentHash() !== $reference->getContentHash());
            }

            return $notEqualContent;
        }

        public function hasEqualContentLike(FileReference $reference): bool
        {
            $equalContent = false;

            if ($this->hasEqualSizeLike($reference)) {
                // TODO: add strategy with fopen, check from end and at beginning the file content
                $equalContent = ($this->getContentHash() === $reference->getContentHash());
            }

            return $equalContent;
        }

        /**
         * @return ContentConstraints|$this
         */
        public function _contentConstraints(): ContentConstraints
        {
            return $this;
        }
    }
}
