<?php namespace JayBeeR\Flops {

    interface ReferenceObject
    {
        public static function get(string $reference): ReferenceObject;

        public function flush(): ReferenceObject;

        public function __toString(): string;
    }
}