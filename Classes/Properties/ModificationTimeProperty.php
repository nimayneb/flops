<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    trait ModificationTimeProperty
    {
        use ReferenceProperty;

        protected ?int $modificationTime = null;

        /**
         * @return int
         */
        public function getLastModificationTime(): int
        {
            return $this->modificationTime ??= filemtime($this->reference);
        }
    }
}
