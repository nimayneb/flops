<?php namespace JayBeeR\Flops\Failures {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Application;

    class CannotChangePermission extends Application
    {
        public function __construct(string $reference, string $newPermission)
        {
            parent::__construct($reference);

            $this->message .= sprintf(' (chmod: %s)', $newPermission);
        }
    }
}
