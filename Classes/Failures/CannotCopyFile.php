<?php namespace JayBeeR\Flops\Failures {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Application;

    class CannotCopyFile extends Application
    {
        public function __construct(string $reference, string $newName = null)
        {
            parent::__construct($reference);

            if (!empty($newName)) {
                $this->message .= sprintf(' (%s)', $newName);
            }
        }
    }
}
