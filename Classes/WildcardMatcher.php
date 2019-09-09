<?php namespace JayBeeR\Flops {

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

                if (WildcardToken::ANY_CHARACTER === $token) {
                    $subject = substr($subject, 1);
                } elseif (WildcardToken::ANY_OF_CHARACTERS === $token) {
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

                // no repeating of "*" token - ignore them
                if (($token === $previousToken) && (WildcardToken::ANY_OF_CHARACTERS === $token)) {
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
                    strpos($pattern, WildcardToken::ANY_OF_CHARACTERS),
                    strpos($pattern, WildcardToken::ANY_CHARACTER),
                    strpos($pattern, WildcardToken::ESCAPE_CHAR)
                ],
                fn ($value) => false !== $value
            );

            return (!empty($positions) ? min($positions) : null);
        }
    }
}