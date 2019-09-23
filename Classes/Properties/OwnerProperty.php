<?php namespace JayBeeR\Flops\Properties {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Failures\CannotGetOwner;
    use JayBeeR\Flops\Failures\CannotSetOwner;
    use JayBeeR\Flops\Failures\UnsupportedOperation;
    use JayBeeR\Flops\Reference;

    trait OwnerProperty
    {
        use ReferenceProperty;

        protected ?string $ownerName = null;

        /**
         * @return string
         * @throws CannotGetOwner
         * @throws UnsupportedOperation
         */
        public function getOwnerName(): string
        {
            if ('\\' === DIRECTORY_SEPARATOR) {
                // TODO: Windows support?

                throw new UnsupportedOperation('fileowner');
            }

            if ($group = fileowner($this->reference)) {
                throw new CannotGetOwner($this->reference);
            }

            if (!extension_loaded('posix')) {
                throw new UnsupportedOperation('posix_getpwuid');
            }

            ['name' => $name] = posix_getpwuid($group);

            return $this->ownerName ??= $name;
        }

        /**
         * @param string $name
         *
         * @return Reference|$this
         * @throws CannotSetOwner
         * @throws UnsupportedOperation
         */
        public function setOwnerName(string $name): Reference
        {
            if ('\\' === DIRECTORY_SEPARATOR) {
                // TODO: Windows support?

                throw new UnsupportedOperation('chgrp');
            }

            if (!chgrp($this->reference, $name)) {
                throw new CannotSetOwner($this->reference, $name);
            }

            return $this;
        }
    }
}
