<?php namespace JayBeeR\Flops\Permissions {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Operations\FlushOperation;
    use JayBeeR\Flops\Properties\ReferenceProperty;
    use JayBeeR\Flops\Reference;
    use JayBeeR\Flops\ReferenceObject;

    class Permissions implements ReferenceObject
    {
        use ReferenceProperty;
        use FlushOperation; // TODO: Really?

        protected ?SpecialPermission $special = null;

        protected ?AccessPermission $owner = null;

        protected ?AccessPermission $group = null;

        protected ?AccessPermission $other = null;

        /**
         * @return SpecialPermission
         */
        public function getSpecialPermission(): SpecialPermission
        {
            return $this->special ??= SpecialPermission::get($this->reference[1]);
        }

        /**
         * @return AccessPermission
         */
        public function getOwnerPermission(): AccessPermission
        {
            return $this->owner ??= AccessPermission::get($this->reference[3]);
        }

        /**
         * @return AccessPermission
         */
        public function getGroupPermission(): AccessPermission
        {
            return $this->group ??= AccessPermission::get($this->reference[2]);
        }

        /**
         * @return AccessPermission
         */
        public function getOtherPermission(): AccessPermission
        {
            return $this->other ??= AccessPermission::get($this->reference[4]);
        }

        /**
         * @param Reference $reference
         */
        public function inheritAccessRights(Reference $reference)
        {

        }

        /**
         *
         */
        public function inheritSpecialRights()
        {

        }

        /**
         *
         */
        public function noExecutionAccessRightsForAll()
        {
            $this->setExecutionAccessRightsForAll(false);
        }

        /**
         *
         */
        public function noWriteAccessRightsForAll()
        {
            $this->setWriteAccessRightsForAll(false);
        }

        /**
         *
         */
        public function noReadAccessRightsForAll()
        {
            $this->setReadAccessRightsForAll(false);
        }

        /**
         * @param bool $not
         */
        public function setExecutionAccessRightsForAll(bool $not = false)
        {
            $this->getOwnerPermission()->setExecutionAccessRight($not);
            $this->getGroupPermission()->setExecutionAccessRight($not);
            $this->getOtherPermission()->setExecutionAccessRight($not);
        }

        /**
         * @param bool $not
         */
        public function setWriteAccessRightsForAll(bool $not = false)
        {
            $this->getOwnerPermission()->setWriteAccessRight($not);
            $this->getGroupPermission()->setWriteAccessRight($not);
            $this->getOtherPermission()->setWriteAccessRight($not);
        }

        /**
         * @param bool $not
         */
        public function setReadAccessRightsForAll(bool $not = false)
        {
            $this->getOwnerPermission()->setReadAccessRight($not);
            $this->getGroupPermission()->setReadAccessRight($not);
            $this->getOtherPermission()->setReadAccessRight($not);
        }

        /**
         * @param int $owner
         * @param int $group
         * @param int $other
         */
        public function setAccessRights(int $owner, int $group = 0, int $other = 0): void
        {
            // TODO: set user rights to "not read", throws an error

            $this->getOwnerPermission()->setAccessRights($owner & 1, $owner & 2, $owner & 4);
            $this->getGroupPermission()->setAccessRights($group & 1, $group & 2, $group & 4);
            $this->getOtherPermission()->setAccessRights($other & 1, $other & 2, $other & 4);
        }

        /**
         * @param bool $setUid
         * @param bool $setGid
         * @param bool $sticky
         */
        public function setSpecialRights(bool $setUid, bool $setGid, bool $sticky): void
        {
            $this->getSpecialPermission()->setUserIdRight($setUid);
            $this->getSpecialPermission()->setGroupIdRight($setGid);
            $this->getSpecialPermission()->setStickyRight($sticky);
        }

        /**
         * @return string
         */
        public function __toString(): string
        {
            return $this->getSpecialPermission() . $this->getOwnerPermission() . $this->getGroupPermission() . $this->getOtherPermission();
        }

        /**
         * @param string $reference
         *
         * @return Permissions
         */
        public static function get(string $reference): Permissions
        {
            return new static(static::format($reference));
        }

        /**
         * @param string $reference
         * @return string
         */
        public static function format(string $reference): string
        {
            return sprintf('%05s', $reference);
        }
    }
}
