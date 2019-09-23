<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

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
