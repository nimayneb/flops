<?php namespace JayBeeR\Flops\Tests {

    use JayBeeR\Flops\Failures\InvalidCharacterForWildcardPattern;
    use JayBeeR\Flops\WildcardMatcher;
    use PHPUnit\Framework\TestCase;

    class WildcardMatcherTest extends TestCase
    {
        /**
         * @var WildcardMatcher
         */
        protected $subject;

        public function setUp(): void
        {
            $this->subject = $this->getObjectForTrait(WildcardMatcher::class);
        }

        /**
         * @return array
         */
        public function wildcardVariantsProvider()
        {
            return [
                ['s', '?', true],
                ['s', '*', true],
                ['search phrase', '*', true],
                ['search phrase', '*phrase', true],
                ['search phrase', 'search*', true],
                ['search phrase', '*h p*', true],
                ['search phrase', '*h?p*', true],
                ['search phrase', '*h*p*', true],
                ['search phrase', 'search?phrase', true],
                ['search phrase', '?????? phrase', true],

                ['search phrase', '?', false],
                ['search phrase', '????? phrase', false],
                ['search phrase', '*search', false],
                ['search phrase', 'false phrase', false],
                ['search phrase', 'false*', false],
                ['search phrase', '*false', false],
                ['search phrase', 'false?', false],
                ['search phrase', '?false', false],
                ['search phrase', '?fa?s?', false],

                ['search phrase', '?earch phra*', true],
                ['search phrase', '?ea?ch*ph?a*e', true],

                ['search phrase', '??earch phra*', false],
                ['search phrase', '?ea??ch**ph?a*e', false],

                // Escape characters
                ['?', '\\?', true],
                ['*', '\\*', true],
                ['\\', '\\\\', true],
                ['search phrase?', '*\\?', true],
                ['?search phrase?', '\\?*', true],
                ['search\\phrase', '*\\\\*', true],
                ['search\\*', '*\\\\\\*', true],
                ['search\\phrase', '*\\phrase', true],
                ['search\\phrase', 'search\\p*', true],
                ['search\\phrase', 'search\\p?', false],
                ['search\\phrase', '*\\\\phrase', true],
                ['search\\phrase', '*\\\\\\phrase', false],
            ];
        }

        /**
         * @return array
         */
        public function wildcardVariantsWithExceptionsProvider()
        {
            return [
                ['search phrase', '?*'],
                ['search phrase', '*?'],
                ['search phrase', '**']
            ];
        }

        /**
         * @param string $subject
         * @param string $pattern
         * @param bool $valid
         *
         * @throws InvalidCharacterForWildcardPattern
         *
         * @dataProvider wildcardVariantsProvider
         * @test
         */
        public function hasWildcardMatchReturnsResultForMatchingWildcard(string $subject, string $pattern, bool $valid)
        {
            $result = $this->subject->hasWildcardMatch($subject, $pattern);

            $this->assertEquals($valid, $result);
        }

        /**
         * @param string $subject
         * @param string $pattern
         * @param bool $valid
         *
         * @throws InvalidCharacterForWildcardPattern
         *
         * @dataProvider wildcardVariantsWithExceptionsProvider
         * @test
         */
        public function hasWildcardMatchThrowsException(string $subject, string $pattern)
        {
            $this->expectException(InvalidCharacterForWildcardPattern::class);
            $this->subject->hasWildcardMatch($subject, $pattern);
        }
    }
}