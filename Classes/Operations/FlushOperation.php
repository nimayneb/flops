<?php namespace JayBeeR\Flops\Operations {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

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
