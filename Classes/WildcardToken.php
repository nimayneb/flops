<?php namespace JayBeeR\Flops {

    interface WildcardToken
    {
        // TODO: 0-1
        public const ZERO_OR_ONE_CHARACTER = '*?';

        // 0-x
        public const ZERO_OR_MANY_CHARACTERS = '*';

        // 1
        public const ONE_CHARACTER = '?';

        // TODO: 1-x
        public const MANY_OF_CHARACTERS = '**';

        public const ESCAPE_CHAR = '\\';
    }
}