<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    trait NameProperty
    {
        use ReferenceProperty;

        protected ?string $name = null;

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name ??= pathinfo($this->reference, PATHINFO_FILENAME);
        }
    }
}
