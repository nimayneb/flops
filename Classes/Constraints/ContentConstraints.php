<?php namespace JayBeeR\Flops\Constraints {

    use JayBeeR\Flops\FileReference;

    interface ContentConstraints
    {
        public function hasSmallerSizeThan(FileReference $reference): bool;

        public function hasGreaterSizeThan(FileReference $reference): bool;

        public function hasNotEqualSizeLike(FileReference $reference): bool;

        public function hasEqualSizeLike(FileReference $reference): bool;

        public function hasNotEqualContentLike(FileReference $reference): bool;

        public function hasEqualContentLike(FileReference $reference): bool;
    }
}