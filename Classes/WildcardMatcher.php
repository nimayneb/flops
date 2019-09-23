<?php namespace JayBeeR\Flops {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use Generator;
    use JayBeeR\Flops\Failures\InvalidCharacterForWildcardPattern;

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
            $anyLength = false;

            foreach ($this->getWildcardToken($pattern) as $token) {
                if (0 === strlen($subject)) {
                    break;
                }

                if (WildcardToken::ONE_CHARACTER === $token) {
                    $subject = substr($subject, 1);
                } elseif (WildcardToken::ZERO_OR_MANY_CHARACTERS === $token) {
                    $anyLength = true;
                } else {
                    if (
                        (false === ($position = strpos($subject, $token)))
                        || (($anyLength) && (0 === $position))
                        || ((!$anyLength) && (0 !== $position))
                    ) {
                        $subject = '';
                        $found = false;

                        break;
                    }

                    $subject = substr($subject, $position + strlen($token));
                    $anyLength = false;
                }
            }

            if (0 !== strlen($subject)) {
                $found = $anyLength;
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

                // search phrase
                if (0 < $position) {
                    $previousToken = null;

                    yield substr($pattern, 0, $position);
                }

                $pattern = substr($pattern, $position + 1);

                // 1. no repeating token (**) TODO: 1-x
                // 2. no combination of token (*?) TODO: 0-1
                // 3. no combination of token (?*)
                if (
                    (($token === $previousToken) && (WildcardToken::ZERO_OR_MANY_CHARACTERS === $token))
                    || ((WildcardToken::ZERO_OR_MANY_CHARACTERS === $previousToken) && (WildcardToken::ONE_CHARACTER === $token))
                    || ((WildcardToken::ONE_CHARACTER === $previousToken) && (WildcardToken::ZERO_OR_MANY_CHARACTERS === $token))
                ) {
                    throw new InvalidCharacterForWildcardPattern($pattern);
                }

                $previousToken = $token;

                // escape characters for: \? \* \\
                if (WildcardToken::ESCAPE_CHAR === $token) {
                    $escapeChar = $pattern[0];

                    if (null === $this->findNextToken($escapeChar)) {
                        yield $token;
                    } else {
                        yield $escapeChar;

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
