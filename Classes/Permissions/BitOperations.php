<?php namespace JayBeeR\Flops\Permissions {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Properties\ReferenceProperty;

    trait BitOperations
    {
        use ReferenceProperty;

        protected function hasBit(int $bit): bool
        {
            return (($this->reference & $bit) === $bit);
        }

        public function mergeBit(bool $one, bool $two, bool $four): int
        {
            return $one * 1 | $two * 2 | $four * 4;
        }
    }
}
