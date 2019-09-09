<?php namespace JayBeeR\Flops\Properties {

    use JayBeeR\Flops\ContentBuilder;

    trait ContentProperty
    {
        use ReferenceProperty;

        protected ?ContentBuilder $content = null;

        /**
         * @return string
         */
        public function getContentBuilder(): string
        {
            return $this->content ??= ContentBuilder::get($this->reference);
        }
    }
}