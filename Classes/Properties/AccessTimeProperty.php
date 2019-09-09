<?php namespace JayBeeR\Flops\Properties {

    trait AccessTimeProperty
    {
        use ReferenceProperty;

        protected ?int $accessTime = null;

        /**
         * @return int
         */
        public function getLastAccessTime(): int
        {
            return $this->accessTime ??= fileatime($this->reference);
        }
    }
}