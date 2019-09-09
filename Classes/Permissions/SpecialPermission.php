<?php namespace JayBeeR\Flops\Permissions {

    use JayBeeR\Flops\Operations\FlushOperation;
    use JayBeeR\Flops\Properties\ReferenceProperty;
    use JayBeeR\Flops\ReferenceObject;

    class SpecialPermission implements ReferenceObject
    {
        use ReferenceProperty;
        use BitOperations;
        use FlushOperation; // TODO: Really?

        public const BIT_USER_ID = 4;

        public const BIT_GROUP_ID = 2;

        public const BIT_STICKY = 1;

        protected ?bool $userId = null;

        protected ?bool $groupId = null;

        protected ?bool $sticky = null;

        protected function __construct(string $reference)
        {
            $this->reference = $reference;
            $this->userId = $this->hasBit(static::BIT_USER_ID);
            $this->groupId = $this->hasBit(static::BIT_GROUP_ID);
            $this->sticky = $this->hasBit(static::BIT_STICKY);
        }

        public function setStickyRight(bool $not = false): void
        {
            $this->sticky = !$not;
        }

        public function setGroupIdRight(bool $not = false): void
        {
            $this->groupId = !$not;
        }

        public function setUserIdRight(bool $not = false)
        {
            $this->userId = !$not;
        }

        public function hasUserIdRight(): bool
        {
            return $this->userId;
        }

        public function hasGroupIdRight(): bool
        {
            return $this->groupId;
        }

        public function hasStickyRight(): bool
        {
            return $this->sticky;
        }

        public function noUserIdRight(): void
        {
            $this->setUserIdRight(false);
        }

        public function noGroupIdRight(): void
        {
            $this->setGroupIdRight(false);
        }

        public function noStickyRight(): void
        {
            $this->setStickyRight(false);
        }

        public function __toString(): string
        {
            return $this->mergeBit($this->sticky, $this->groupId, $this->userId);
        }

        /**
         * @param string $reference
         *
         * @return SpecialPermission
         */
        public static function get(string $reference): SpecialPermission
        {
            return new static($reference);
        }
    }
}