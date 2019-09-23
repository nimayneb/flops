<?php namespace JayBeeR\Flops\Permissions {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Operations\FlushOperation;
    use JayBeeR\Flops\Properties\ReferenceProperty;
    use JayBeeR\Flops\ReferenceObject;

    class AccessPermission implements ReferenceObject
    {
        use ReferenceProperty;
        use BitOperations;
        use FlushOperation; // TODO: Really?

        public const BIT_READ = 4;

        public const BIT_WRITE = 2;

        public const BIT_EXECUTION = 1;

        /**
         * Read Bit = 4
         */
        protected ?bool $read = null;

        /**
         * Write Bit = 2
         */
        protected ?bool $write = null;

        /**
         * Execution Bit = 1
         */
        protected ?bool $execution = null;

        protected function __construct(string $reference)
        {
            $this->reference = $reference;
            $this->read = $this->hasBit(static::BIT_READ);
            $this->write = $this->hasBit(static::BIT_WRITE);
            $this->execution = $this->hasBit(static::BIT_EXECUTION);
        }

        public function noWriteAccessRight(): void
        {
            $this->setWriteAccessRight(false);
        }

        public function noReadAccessRight(): void
        {
            $this->setReadAccessRight(false);
        }

        public function noExecutionAccessRight(): void
        {
            $this->setExecutionAccessRight(false);
        }

        public function setFullAccessRights(bool $not = false): void
        {
            $this->setReadAccessRight($not);
            $this->setWriteAccessRight($not);
            $this->setExecutionAccessRight($not);
        }

        public function setWriteAccessRight(bool $not = false): void
        {
            $this->write = !$not;
        }

        public function setReadAccessRight(bool $not = false): void
        {
            $this->read = !$not;
        }

        public function setExecutionAccessRight(bool $not = false): void
        {
            $this->execution = !$not;
        }

        public function hasWriteAccessRight(): bool
        {
            return $this->write;
        }

        public function hasReadAccessRight(): bool
        {
            return $this->read;
        }

        public function hasExecutionAccessRight(): bool
        {
            return $this->execution;
        }

        public function setAccessRights(bool $read, bool $write, bool $execution): void
        {
            $this->read = $read;
            $this->write = $write;
            $this->execution = $execution;
        }

        public function __toString(): string
        {
            return $this->mergeBit($this->execution, $this->write, $this->read);
        }

        /**
         * @param string $reference
         *
         * @return AccessPermission
         */
        public static function get(string $reference): AccessPermission
        {
            return new static($reference);
        }
    }
}
