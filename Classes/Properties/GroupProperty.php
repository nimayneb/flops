<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Failures\CannotGetGroup;
    use JayBeeR\Flops\Failures\CannotSetGroup;
    use JayBeeR\Flops\Failures\UnsupportedOperation;
    use JayBeeR\Flops\Reference;

    trait GroupProperty
    {
        use ReferenceProperty;

        protected ?string $groupName = null;

        /**
         * @return string
         * @throws CannotGetGroup
         * @throws UnsupportedOperation
         */
        public function getGroupName(): string
        {
            if ('\\' === DIRECTORY_SEPARATOR) {
                // TODO: Windows support?

                throw new UnsupportedOperation('filegroup');
            }

            if ($group = filegroup($this->reference)) {
                throw new CannotGetGroup($this->reference);
            }

            if (!extension_loaded('posix')) {
                throw new UnsupportedOperation('posix_getgrgid');
            }

            ['name' => $name] = posix_getgrgid($group);

            return $this->groupName ??= $name;
        }

        /**
         * @param string $name
         *
         * @return Reference|$this
         * @throws CannotSetGroup
         * @throws UnsupportedOperation
         */
        public function setGroupName(string $name): Reference
        {
            if ('\\' === DIRECTORY_SEPARATOR) {
                // TODO: Windows support?

                throw new UnsupportedOperation('chgrp');
            }

            if (!chgrp($this->reference, $name)) {
                throw new CannotSetGroup($this->reference, $name);
            }

            return $this;
        }
    }
}
