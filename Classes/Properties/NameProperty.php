<?php namespace JayBeeR\Flops\Properties {

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