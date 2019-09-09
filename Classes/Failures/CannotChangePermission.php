<?php namespace JayBeeR\Flops\Failures {

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