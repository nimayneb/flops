<?php namespace JayBeeR\Flops\Failures {

    use JayBeeR\Flops\Application;

    class CannotLinkToReference extends Application
    {
        public function __construct(string $reference, string $path = null)
        {
            parent::__construct($reference);

            if (!empty($newName)) {
                $this->message .= sprintf(' (%s)', $path);
            }
        }
    }
}