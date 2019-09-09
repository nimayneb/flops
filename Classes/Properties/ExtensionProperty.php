<?php namespace JayBeeR\Flops\Properties {

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