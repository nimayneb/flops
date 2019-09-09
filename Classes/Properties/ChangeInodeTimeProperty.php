<?php namespace JayBeeR\Flops\Properties {

    trait ChangeInodeTimeProperty
    {
        use ReferenceProperty;

        protected ?int $changeInodeTime = null;

        /**
         * @return int
         */
        public function getChangeInodeTime(): int
        {
            return $this->changeInodeTime ??= filectime($this->reference);
        }
    }
}