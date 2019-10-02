<?php namespace JayBeeR\Flops\Modes {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    class FileMode
    {
        /**
         *    MODE  | Reading   | Writing   | Beginning | Creating  | Appending | Truncate  | Seeking   | not exists    | exists
         *  --------|-----------|-----------|-----------|-----------|-----------|-----------|-----------|---------------|---------
         *     r    |     x     |     -     |     x     |     -     |     -     |     -     |     x     |       -       |
         *     r+   |     x     |     x     |     x     |     -     |     -     |     -     |     x     |       -       |
         *     w    |     -     |     x     |     x     |     -     |     -     |     x     |     x     |    c-reate    |
         *     w+   |     x     |     x     |     x     |     -     |     -     |     x     |     x     |    c-reate    |
         *     a    |     -     |     x     |     -     |     -     |     x     |     -     |     -     |    c-reate    |
         *     a+   |     x     |     x     |     -     |     -     |     x     |     -     |     x (r) |    c-reate    |
         *     x    |     -     |     x     |     x     |     x     |     -     |     -     |     x     |    c-reate    | fail
         *     x+   |     x     |     x     |     x     |     x     |     -     |     -     |     x     |    c-reate    | fail
         *     c    |     -     |     x     |     x     |     -     |     -     |     -     |     x     |    c-reate    |
         *     c+   |     x     |     x     |     x     |     -     |     -     |     -     |     x     |    c-reate    |
         *
         * @see https://www.php.net/manual/en/function.fopen.php
         */

        // x, x+
        public const CREATING = 'creating';

        // r, r+, w+, a+, x+
        public const READING = 'reading';

        // r+, w, w+, a, a+, x, x+
        public const WRITING = 'writing';

        // a, a+
        public const APPENDING = 'appending';

        // w, w+
        public const TRUNCATING = 'truncating';

        // r, r+
        public const EXISTING = 'existing';

        public const CURRENT = 'current';

        protected static array $modeVariants = [
            self::TRUNCATING => [
                self::CURRENT => 'w',

                self::WRITING => [
                    self::CURRENT => 'w',
                    self::READING => 'w+'
                ],
                self::READING => [
                    self::CURRENT => 'w+',
                    self::WRITING => 'w+'
                ]
            ],

            self::APPENDING => [
                self::CURRENT => 'a',

                self::WRITING => [
                    self::CURRENT => 'a',
                    self::READING => 'a+'
                ],
                self::READING => [
                    self::CURRENT => 'a+',
                    self::WRITING => 'a+'
                ]
            ],

            self::CREATING => [
                self::CURRENT => 'x',

                self::WRITING => [
                    self::CURRENT => 'x',
                    self::READING => 'x+'
                ],
                self::READING => [
                    self::CURRENT => 'x+',
                    self::WRITING => 'x+'
                ]
            ],

            self::EXISTING => [
                self::CURRENT => 'r',

                self::WRITING => [
                    self::CURRENT => 'r+',
                    self::READING => 'r+'
                ],
                self::READING => [
                    self::CURRENT => 'r',

                    self::WRITING => 'r+'
                ]
            ],

            self::READING => [
                self::CURRENT => 'r',

                self::WRITING => [
                    self::CURRENT => 'r+',

                    self::TRUNCATING => 'w+',
                    self::APPENDING => 'a+',
                    self::CREATING => 'x+',
                    self::EXISTING => 'r+'
                ],
                self::TRUNCATING => [
                    self::CURRENT => 'w+',

                    self::WRITING => 'w+'
                ],
                self::APPENDING => [
                    self::CURRENT => 'a+',

                    self::WRITING => 'a+'
                ],
                self::CREATING => [
                    self::CURRENT => 'x+',

                    self::WRITING => 'x+'
                ],
                self::EXISTING => [
                    self::CURRENT => 'r',

                    self::WRITING => 'r+'
                ]
            ],

            self::WRITING => [
                self::CURRENT => 'w',

                self::READING => [
                    self::CURRENT => 'w+',

                    self::TRUNCATING => 'w+',
                    self::APPENDING => 'a+',
                    self::CREATING => 'x+',
                    self::EXISTING => 'r+'
                ],
                self::TRUNCATING => [
                    self::CURRENT => 'w',

                    self::READING => 'w+'
                ],
                self::APPENDING => [
                    self::CURRENT => 'a',

                    self::READING => 'a+'
                ],
                self::CREATING => [
                    self::CURRENT => 'x',

                    self::READING => 'x+'
                ],
                self::EXISTING => [
                    self::CURRENT => 'r+',

                    self::READING => 'r+'
                ]
            ]
        ];

        /**
         * @return Creating
         */
        public static function mustNotExists()
        {
            return Creating::get(static::$modeVariants[FileMode::CREATING]);
        }

        /**
         * @return Existing
         */
        public static function mustExists()
        {
            return Existing::get(static::$modeVariants[FileMode::EXISTING]);
        }

        /**
         * @return Appending
         */
        public static function appending()
        {
            return Appending::get(static::$modeVariants[FileMode::APPENDING]);
        }

        /**
         * @return Truncating
         */
        public static function truncating()
        {
            return Truncating::get(static::$modeVariants[FileMode::TRUNCATING]);
        }

        /**
         * @return Writing
         */
        public static function writing()
        {
            return Writing::get(static::$modeVariants[FileMode::WRITING]);
        }

        /**
         * @return Reading
         */
        public static function reading()
        {
            return Reading::get(static::$modeVariants[FileMode::READING]);
        }
    }
} 