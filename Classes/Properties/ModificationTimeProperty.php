<?php namespace JayBeeR\Flops\Properties {

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