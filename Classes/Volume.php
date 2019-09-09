<?php namespace JayBeeR\Flops {

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