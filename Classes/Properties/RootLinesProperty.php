<?php namespace JayBeeR\Flops\Properties {

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