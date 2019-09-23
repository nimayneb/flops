<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\ReferenceObject;

    trait ReferenceProperty
    {
        protected string $reference;

        /**
         * ReferenceSingletonGetter constructor.
         * @param string $reference
         */
        protected function __construct(string $reference)
        {
            $this->reference = $reference;
        }

        /**
         * @param string $reference
         *
         * @return ReferenceObject|$this
         */
        public static function get(string $reference): ReferenceObject
        {
            return new static($reference);
        }

        /**
         * @return string
         */
        public function __toString(): string
        {
            return $this->reference;
        }
    }
}
