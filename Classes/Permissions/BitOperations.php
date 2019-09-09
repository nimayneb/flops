<?php namespace JayBeeR\Flops\Permissions {

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