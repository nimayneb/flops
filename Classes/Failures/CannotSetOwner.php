<?php namespace JayBeeR\Flops\Failures {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Application;

    class CannotSetOwner extends Application
    {
        public function __construct(string $reference, string $owner)
        {
            parent::__construct($reference);

            $this->message .= sprintf(' (chown: %s)', $owner);
        }
    }
}
