<?php namespace JayBeeR\Flops\Failures {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Application;

    class InvalidEncodingByte extends Application
    {
        public function __construct(string $character)
        {
            $byte = ord($character);

            parent::__construct($character);

            $this->message .= sprintf(' (%u => %08s)', $byte, decbin($byte));
        }
    }
} 
