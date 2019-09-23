<?php namespace JayBeeR\Flops {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use Exception;

    abstract class Application extends Exception
    {
        public function __construct(string $reference)
        {
            $speakingClassName = substr(strrchr(static::class, "\\"), 1);
            $wordsFromClassName = array_filter(preg_split('/(?=[A-Z])/', $speakingClassName));
            $sentence = ucfirst(strtolower(implode(' ', $wordsFromClassName)));

            parent::__construct(sprintf('%s: "%s"', $sentence, $reference));
        }
    }
}
