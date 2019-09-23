<?php namespace JayBeeR\Flops\Failures {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Application;
    use JayBeeR\Flops\FileResource;

    class CannotSeekToPosition extends Application
    {
        public function __construct(FileResource $resource, int $offset)
        {
        }
    }
} 
