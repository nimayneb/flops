<?php namespace JayBeeR\Flops\Failures {

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