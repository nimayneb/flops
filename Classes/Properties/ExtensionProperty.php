<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    trait ExtensionProperty
    {
        use ReferenceProperty;

        protected ?string $extension = null;

        /**
         * @return string
         */
        public function getExtension(): string
        {
            return $this->extension ??= pathinfo($this->reference, PATHINFO_EXTENSION);
        }
    }
}
