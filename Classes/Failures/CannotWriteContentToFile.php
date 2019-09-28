<?php namespace JayBeeR\Flops\Failures {

    use JayBeeR\Flops\Application;

    class CannotWriteContentToFile extends Application
    {

        /**
         * CannotWriteContentToFile constructor.
         * @param string $reference
         * @param string $content
         */
        public function __construct(string $reference, string $content)
        {
            parent::__construct();
        }
    }
} 