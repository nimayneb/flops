<?php namespace JayBeeR\Flops {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    interface ReferenceObject
    {
        public static function get(string $reference): ReferenceObject;

        public function flush(): ReferenceObject;

        public function __toString(): string;
    }
}
