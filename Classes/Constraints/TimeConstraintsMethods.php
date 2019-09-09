<?php namespace JayBeeR\Flops\Constraints {

    use JayBeeR\Flops\FileReference;
    use JayBeeR\Flops\Properties\AccessTimeProperty;
    use JayBeeR\Flops\Properties\ChangeInodeTimeProperty;
    use JayBeeR\Flops\Properties\ModificationTimeProperty;

    trait TimeConstraintsMethods
    {
        use ModificationTimeProperty;
        use ChangeInodeTimeProperty;
        use AccessTimeProperty;

        public function isNewerChangeInodeTimeThan(FileReference $reference): bool
        {
            return ($this->getChangeInodeTime() > $reference->getChangeInodeTime());
        }

        public function isOlderChangeInodeTimeThan(FileReference $reference): bool
        {
            return ($this->getChangeInodeTime() < $reference->getChangeInodeTime());
        }

        public function isNewerModificationTimeThan(FileReference $reference): bool
        {
            return ($this->getLastModificationTime() > $reference->getLastModificationTime());
        }

        public function isOlderModificationTimeThan(FileReference $reference): bool
        {
            return ($this->getLastModificationTime() < $reference->getLastModificationTime());
        }

        public function isNewerAccessTimeThan(FileReference $reference): bool
        {
            return ($this->getLastAccessTime() > $reference->getLastAccessTime());
        }

        public function isOlderAccessTimeThan(FileReference $reference): bool
        {
            return ($this->getLastAccessTime() < $reference->getLastAccessTime());
        }

        /**
         * @return TimeConstraints|$this
         */
        public function _timeConstraints(): TimeConstraints
        {
            return $this;
        }
    }
}