<?php namespace JayBeeR\Flops\Properties {

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