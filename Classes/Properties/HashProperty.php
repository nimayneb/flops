<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    trait HashProperty
    {
        use ReferenceProperty;

        protected ?string $hash = null;

        /**
         * @return string
         */
        public function getContentHash(): string
        {
            return $this->hash ??= sha1_file($this->reference);
        }
    }
}
