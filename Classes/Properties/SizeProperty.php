<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    trait SizeProperty
    {
        use ReferenceProperty;

        protected ?int $size = null;

        /**
         * @return int
         */
        public function getSize(): int
        {
            return $this->size ??= filesize($this->reference);
        }
    }
}
