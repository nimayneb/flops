<?php namespace JayBeeR\Flops {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    class Volume extends Reference
    {
        public function getFreeSpace(): float
        {
            return disk_free_space($this->reference);
        }

        public function getTotalSpace(): float
        {
            return disk_total_space($this->reference);
        }
    }
}
