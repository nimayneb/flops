<?php namespace JayBeeR\Flops\Failures {

    use JayBeeR\Flops\Application;

    class MissingRequiredEncodingBytes extends Application
    {
        public function __construct(string $character, int $bytesToLoad)
        {
            $byte = ord($character);

            parent::__construct($character);

            $this->message .= sprintf(' (%u => %08s, bytes to load: %u)', $byte, decbin($byte), $bytesToLoad);
        }
    }
} 