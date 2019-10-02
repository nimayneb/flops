<?php namespace JayBeeR\Flops\Modes {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    abstract class Mode implements EmptyMode
    {
        use ModesProperty;

        protected const READING = 1;

        protected const WRITING = 2;

        protected const CREATING = 4;

        protected const APPENDING = 8;

        protected const TRUNCATING = 16;

        protected const EXISTING = 32;

        protected const BINARY = 64;

        public const READ = 'r';

        public const READ_WRITE = 'r+';

        public const WRITE = 'w';

        public const WRITE_READ = 'w+';

        public const APPEND = 'a';

        public const APPEND_READ = 'a+';

        public const CREATE = 'x';

        public const CREATE_READ = 'x+';

        /**
         *  Mode    | Creating              | Appending     | Truncating    | Existing              | Creating (non-existing)   | Writing   | Reading  | Cursor starts      | Purpose
         *  --------|-----------------------|---------------|---------------|-----------------------|---------------------------|-----------|----------|--------------------|------------------------------------------------------------------
         *   r      | -                     | -             | -             | y (fails by missing)  | -                         | -         | y        | beginning          | basic read existing file
         *   r+     | -                     | -             | -             | y (fails by missing)  | -                         | y         | y        | beginning          | basic r/w existing file
         *   w      | -                     | -             | y             | -                     | y                         | y         | -        | beginning + end    | create, erase, write file
         *   w+     | -                     | -             | y             | -                     | y                         | y         | y        | beginning + end    | create, erase, write file with read option
         *   a      | -                     | y             | -             | -                     | y                         | y         | -        | end                | write from end of file, create if needed
         *   a+     | -                     | y             | -             | -                     | y                         | y         | y        | end                | write from end of file, create if needed, with read options
         *   x      | y (fails by existing) | -             | -             | -                     | y                         | y         | -        | beginning          | like w, but prevents over-writing an existing file
         *   x+     | y (fails by existing) | -             | -             | -                     | y                         | y         | y        | beginning          | like w+, but prevents over writing an existing file
         */

        protected const MODES = [
            self::READ => self::EXISTING + self::READING,
            self::READ_WRITE => self::EXISTING + self::READING + self::WRITING,
            self::WRITE => self::TRUNCATING + self::WRITING,
            self::WRITE_READ => self::TRUNCATING + self::WRITING + self::READING,
            self::APPEND => self::APPENDING + self::WRITING,
            self::APPEND_READ => self::APPENDING + self::WRITING + self::READING,
            self::CREATE => self::CREATING + self::WRITING,
            self::CREATE_READ => self::CREATING + self::WRITING + self::READING,
        ];

        public function isReading()
        {
            return static::MODES[(string) $this->modes] & self::READING;
        }

        public function isWriting()
        {
            return static::MODES[(string) $this->modes] & self::WRITING;
        }

        public function hadToBeCreated()
        {
            return static::MODES[(string) $this->modes] & self::CREATING;
        }

        public function isAppending()
        {
            return static::MODES[(string) $this->modes] & self::APPENDING;
        }

        public function isTruncating()
        {
            return static::MODES[(string) $this->modes] & self::TRUNCATING;
        }

        public function hadToExist()
        {
            return static::MODES[(string) $this->modes] & self::EXISTING;
        }
    }
} 