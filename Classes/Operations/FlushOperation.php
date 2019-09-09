<?php namespace JayBeeR\Flops\Operations {

    use JayBeeR\Flops\Properties\ReferenceProperty;
    use JayBeeR\Flops\ReferenceObject;

    trait FlushOperation
    {
        use ReferenceProperty;

        /**
         * @return ReferenceObject|$this
         */
        public function flush(): ReferenceObject
        {
            clearstatcache(true, $this->reference);

            return $this;
        }
    }
}