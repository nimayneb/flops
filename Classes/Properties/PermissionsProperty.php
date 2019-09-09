<?php namespace JayBeeR\Flops\Properties {

    use Closure;
    use JayBeeR\Flops\Failures\CannotChangePermission;
    use JayBeeR\Flops\Failures\CannotGetPermissions;
    use JayBeeR\Flops\Permissions\Permissions;

    trait PermissionsProperty
    {
        use ReferenceProperty;

        protected ?Permissions $permissions = null;

        /**
         * @return Permissions
         * @throws CannotGetPermissions
         */
        public function getPermissions(): Permissions
        {
            $permissions = fileperms($this->reference);

            if (!$permissions) {
                throw new CannotGetPermissions($this->reference);
            }

            $this->permissions ??= Permissions::get(decoct($permissions));

            return $this->permissions;
        }

        /**
         * @param Closure $referencePermissionHandler
         *
         * @throws CannotChangePermission
         * @throws CannotGetPermissions
         */
        public function setPermissions(Closure $referencePermissionHandler): void
        {
            $permissions = $this->getPermissions();

            {
                $oldPermission = (string)$permissions;

                $referencePermissionHandler($permissions);

                $newPermission = (string)$permissions;
            }

            if ($oldPermission !== $newPermission) {
                if (!chmod($this->reference, $newPermission)) {
                    throw new CannotChangePermission($this->reference, $newPermission);
                }

                $this->permissions = null;
            }
        }
    }
}