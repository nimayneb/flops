<?php namespace JayBeeR\Flops {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use Generator;
    use JayBeeR\Flops\Failures\InvalidCharacterForWildcardPattern;

    /**
     *  Known wildcard logic:
     *  ---------------------
     *  zero or many characters =   *   (0-x characters)
     *  one character           =   ?   (1 character)
     *
     *
     *  Extended wildcard logic:
     *  ------------------------
     *  many of characters      =   **  (1-x characters)
     *  zero or one character   =   ?*  (0-1 characters)
     *
     *
     *  Valid:
     *  ------
     *          ??      (2 characters)
     *          ?????*  (4-5 characters)
     *
     *
     *  Invalid:
     *  --------
     *          ***
     *          ?**
     *          ?*?
     *          *?
     *
     *  Escaping:
     *  ---------
     *          \?
     *          \*
     *
     *
     *  Notice:
     *  -------
     *  Invalid wildcard pattern can not be fully recognized if a partial pattern has not already been found:
     *
     *      "search phrase" => "sea??ch*****"
     *                               ^   ^
     *             pattern not found |   | invalid pattern (not recognized)
     *
     * @see https://de.wikipedia.org/wiki/Wildcard_(Informatik)
     */
    trait WildcardMatcher
    {
        /**
         * @param string $subject
         * @param string $pattern
         *
         * @return bool
         * @throws InvalidCharacterForWildcardPattern
         */
        public function hasWildcardMatch(string $subject, string $pattern): bool
        {
            $found = true;
            $canBeNull = true;
            $neededLength = 0;

            foreach ($this->getWildcardToken($pattern) as $token) {
                if (0 === strlen($subject)) {
                    $found = (
                        (WildcardToken::ZERO_OR_ONE_CHARACTER === $token)
                        || (WildcardToken::ZERO_OR_MANY_CHARACTERS === $token)
                    );

                    break;
                }

                if (WildcardToken::ONE_CHARACTER === $token) {
                    $subject = substr($subject, 1);
                    $neededLength = 0;
                    $canBeNull = true;
                } elseif (WildcardToken::ZERO_OR_ONE_CHARACTER === $token) {
                    $neededLength = 1;
                    $canBeNull = true;
                } elseif (WildcardToken::ZERO_OR_MANY_CHARACTERS === $token) {
                    $neededLength = PHP_INT_MAX;
                    $canBeNull = true;
                } elseif (WildcardToken::MANY_OF_CHARACTERS === $token) {
                    $neededLength = PHP_INT_MAX;
                    $canBeNull = false;
                } else {
                    if (chr(0) === $token[0]) {
                        $token = $token[1];
                    }

                    if (
                        (false === ($position = strpos($subject, $token)))
                        || ((false === $canBeNull) && (0 === $position))
                        || ((0 === $neededLength) && (0 !== $position))
                    ) {
                        $subject = '';
                        $found = false;

                        break;
                    }

                    $subject = substr($subject, $position + strlen($token));
                    $neededLength = 0;
                    $canBeNull = true;
                }
            }

            if (0 !== ($length = strlen($subject))) {
                $found = ($length <= $neededLength);
            }

            return $found;
        }

        /**
         * @param string $pattern
         *
         * @return Generator|string[]
         * @throws InvalidCharacterForWildcardPattern
         */
        protected function getWildcardToken(string $pattern): Generator
        {
            $previousToken = null;

            while (null !== ($position = $this->findNextToken($pattern))) {
                $token = $pattern[$position];
                $nextToken = $pattern[$position + 1];

                // search phrase

                if (0 < $position) {
                    $previousToken = null;

                    yield substr($pattern, 0, $position);
                }

                $pattern = substr($pattern, $position + 1);

                // 1. no combination of token (***)
                // 2. no combination of token (?**)
                // 3. no combination of token (?*?)
                // 4. no combination of token (*?)

                if (
                    ((WildcardToken::MANY_OF_CHARACTERS === $previousToken) && (WildcardToken::ZERO_OR_MANY_CHARACTERS === $token))
                    || ((WildcardToken::ZERO_OR_MANY_CHARACTERS === $previousToken) && (WildcardToken::ONE_CHARACTER === $token))
                    || ((WildcardToken::ZERO_OR_ONE_CHARACTER === $previousToken) && (WildcardToken::ONE_CHARACTER === $token))
                    || ((WildcardToken::ZERO_OR_ONE_CHARACTER === $previousToken) && (WildcardToken::ZERO_OR_MANY_CHARACTERS === $token))
                ) {
                    throw new InvalidCharacterForWildcardPattern($pattern);
                }

                // 1. combine two tokens (**) 1-x
                // 2. combine two tokens (?*) 0-1

                if ((WildcardToken::ZERO_OR_MANY_CHARACTERS === $token) && (WildcardToken::ZERO_OR_MANY_CHARACTERS === $nextToken)) {
                    $token = WildcardToken::MANY_OF_CHARACTERS;
                    $pattern = substr($pattern, 1);
                } elseif ((WildcardToken::ONE_CHARACTER === $token) && (WildcardToken::ZERO_OR_MANY_CHARACTERS === $nextToken)) {
                    $token = WildcardToken::ZERO_OR_ONE_CHARACTER;
                    $pattern = substr($pattern, 1);
                }

                $previousToken = $token;

                //  escaped characters: \? \*
                // backslash character: \

                if (WildcardToken::ESCAPE_CHAR === $token) {
                    $escapeChar = $pattern[0];

                    if (null === $this->findNextToken($escapeChar)) {
                        yield chr(0) . $token;
                    } else {
                        yield chr(0) . $escapeChar;

                        $pattern = substr($pattern, 1);
                    }

                    continue;
                }

                yield $token;
            }

            // search phrase

            if (0 < strlen($pattern)) {
                yield $pattern;
            }
        }

        /**
         * @param string $pattern
         *
         * @return int|null
         */
        protected function findNextToken(string $pattern): ?int
        {
            $positions = array_filter(
                [
                    strpos($pattern, WildcardToken::ZERO_OR_MANY_CHARACTERS),
                    strpos($pattern, WildcardToken::ONE_CHARACTER),
                    strpos($pattern, WildcardToken::ESCAPE_CHAR)
                ],
                fn ($value) => false !== $value
            );

            return (!empty($positions) ? min($positions) : null);
        }
    }
}
