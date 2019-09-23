<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

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
