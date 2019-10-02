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
                self::CURRENT => Mode::WRITE,

                self::WRITING => [
                    self::CURRENT => Mode::WRITE,
                    self::READING => Mode::WRITE_READ
                ],
                self::READING => [
                    self::CURRENT => Mode::WRITE_READ,
                    self::WRITING => Mode::WRITE_READ
                ]
            ],

            self::APPENDING => [
                self::CURRENT => Mode::APPEND,

                self::WRITING => [
                    self::CURRENT => Mode::APPEND,
                    self::READING => Mode::APPEND_READ
                ],
                self::READING => [
                    self::CURRENT => Mode::APPEND_READ,
                    self::WRITING => Mode::APPEND_READ
                ]
            ],

            self::CREATING => [
                self::CURRENT => MODE::CREATE,

                self::WRITING => [
                    self::CURRENT => MODE::CREATE,
                    self::READING => Mode::CREATE_READ
                ],
                self::READING => [
                    self::CURRENT => Mode::CREATE_READ,
                    self::WRITING => Mode::CREATE_READ
                ]
            ],

            self::EXISTING => [
                self::CURRENT => Mode::READ,

                self::WRITING => [
                    self::CURRENT => Mode::READ_WRITE,
                    self::READING => Mode::READ_WRITE
                ],
                self::READING => [
                    self::CURRENT => Mode::READ,

                    self::WRITING => Mode::READ_WRITE
                ]
            ],

            self::READING => [
                self::CURRENT => Mode::READ,

                self::WRITING => [
                    self::CURRENT => Mode::READ_WRITE,

                    self::TRUNCATING => Mode::WRITE_READ,
                    self::APPENDING => Mode::APPEND_READ,
                    self::CREATING => Mode::CREATE_READ,
                    self::EXISTING => Mode::READ_WRITE
                ],
                self::TRUNCATING => [
                    self::CURRENT => Mode::WRITE_READ,

                    self::WRITING => Mode::WRITE_READ
                ],
                self::APPENDING => [
                    self::CURRENT => Mode::APPEND_READ,

                    self::WRITING => Mode::APPEND_READ
                ],
                self::CREATING => [
                    self::CURRENT => Mode::CREATE_READ,

                    self::WRITING => Mode::CREATE_READ
                ],
                self::EXISTING => [
                    self::CURRENT => Mode::READ,

                    self::WRITING => Mode::READ_WRITE
                ]
            ],

            self::WRITING => [
                self::CURRENT => Mode::WRITE,

                self::READING => [
                    self::CURRENT => Mode::WRITE_READ,

                    self::TRUNCATING => Mode::WRITE_READ,
                    self::APPENDING => Mode::APPEND_READ,
                    self::CREATING => Mode::CREATE_READ,
                    self::EXISTING => Mode::READ_WRITE
                ],
                self::TRUNCATING => [
                    self::CURRENT => Mode::WRITE,

                    self::READING => Mode::WRITE_READ
                ],
                self::APPENDING => [
                    self::CURRENT => Mode::APPEND,

                    self::READING => Mode::APPEND_READ
                ],
                self::CREATING => [
                    self::CURRENT => MODE::CREATE,

                    self::READING => Mode::CREATE_READ
                ],
                self::EXISTING => [
                    self::CURRENT => Mode::READ_WRITE,

                    self::READING => Mode::READ_WRITE
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