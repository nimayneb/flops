<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\RootLines\RootLines;

    trait RootLinesProperty
    {
        use ReferenceProperty;

        protected ?RootLines $rootLines = null;

        /**
         * @return RootLines
         */
        public function getRootLines(): RootLines
        {
            return $this->rootLines ??= RootLines::get($this->reference);
        }
    }
}
