<?php namespace JayBeeR\Flops\Failures {

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