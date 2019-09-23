<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

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
