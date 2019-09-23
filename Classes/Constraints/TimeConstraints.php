<?php namespace JayBeeR\Flops\Constraints {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

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
