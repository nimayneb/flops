<?php namespace JayBeeR\Flops\Properties {

    use JayBeeR\Flops\Volume;

    trait VolumeProperty
    {
        use ReferenceProperty;

        protected ?Volume $volume = null;

        /**
         * @return Volume
         */
        public function getVolume(): Volume
        {
            return $this->volume ??= Volume::get($this->reference);
        }
    }
}