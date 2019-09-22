<?php namespace JayBeeR\Flops\Failures {

    use JayBeeR\Flops\Application;
    use JayBeeR\Flops\FileResource;

    class CannotSeekToPosition extends Application
    {
        public function __construct(FileResource $resource, int $offset)
        {
        }
    }
} 