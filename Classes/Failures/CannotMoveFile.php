<?php namespace JayBeeR\Flops\Failures {

    use JayBeeR\Flops\Application;

    class CannotMoveFile extends Application
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