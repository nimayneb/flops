<?php namespace JayBeeR\Flops\Failures {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Application;

    class CannotSetGroup extends Application
    {
        public function __construct(string $reference, string $group)
        {
            parent::__construct($reference);

            $this->message .= sprintf(' (chgrp: %s)', $group);
        }
    }
}
