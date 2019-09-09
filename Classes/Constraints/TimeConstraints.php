<?php namespace JayBeeR\Flops\Constraints {

    use JayBeeR\Flops\FileReference;

    interface TimeConstraints
    {
        public function isNewerChangeInodeTimeThan(FileReference $reference): bool;

        public function isOlderChangeInodeTimeThan(FileReference $reference): bool;

        public function isNewerModificationTimeThan(FileReference $reference): bool;

        public function isOlderModificationTimeThan(FileReference $reference): bool;

        public function isNewerAccessTimeThan(FileReference $reference): bool;

        public function isOlderAccessTimeThan(FileReference $reference): bool;
    }
}