<?php namespace JayBeeR\Flops {

    interface WildcardToken
    {
        public const ANY_CHARACTER = '?';

        public const ANY_OF_CHARACTERS = '*';

        public const ESCAPE_CHAR = '\\';
    }
}