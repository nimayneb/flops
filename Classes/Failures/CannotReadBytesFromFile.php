<?php namespace JayBeeR\Flops\Failures {

    use JayBeeR\Flops\Application;
    use JayBeeR\Flops\FileResource;

    class CannotReadBytesFromFile extends Application
    {

        /**
         * @param FileResource $resource
         * @param int|null $length
         */
        public function __construct(FileResource $resource, ?int $length)
        {
            parent::__construct();
        }
    }
} 