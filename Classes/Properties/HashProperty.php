<?php namespace JayBeeR\Flops\Properties {

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