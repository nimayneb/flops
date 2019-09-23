<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

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
