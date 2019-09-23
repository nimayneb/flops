<?php namespace JayBeeR\Flops\Properties {

    use JayBeeR\Flops\Operations\ContentOperation;

    trait ContentProperty
    {
        use ReferenceProperty;

        protected ?ContentOperation $content = null;

        /**
         * @return string
         */
        public function withContent(): string
        {
            return $this->content ??= ContentOperation::get($this->reference);
        }
    }
}