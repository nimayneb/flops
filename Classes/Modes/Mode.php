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
         *   c      | -                     | -             | -             | -                     | y                         | y         | -        | beginning          | open/create a file for writing without deleting current content
         *   c+     | -                     | -             | -             | -                     | y                         | y         | y        | beginning          | open/create a file that is read, and then written back down
         */

        protected const MODES = [
            'r' => self::EXISTING + self::READING,
            'r+' => self::EXISTING + self::READING + self::WRITING,
            'w' => self::TRUNCATING + self::WRITING,
            'w+' => self::TRUNCATING + self::WRITING + self::READING,
            'a' => self::APPENDING + self::WRITING,
            'a+' => self::APPENDING + self::WRITING + self::READING,
            'x' => self::CREATING + self::WRITING,
            'x+' => self::CREATING + self::WRITING + self::READING,
        ];

        public function isReading()
        {
            return static::MODES[(string) $this->modes] & 1;
        }

        public function isWriting()
        {
            return static::MODES[(string) $this->modes] & 2;
        }

        public function hadToBeCreated()
        {
            return static::MODES[(string) $this->modes] & 4;
        }

        public function isAppending()
        {
            return static::MODES[(string) $this->modes] & 8;
        }

        public function isTruncating()
        {
            return static::MODES[(string) $this->modes] & 16;
        }

        public function hadToExist()
        {
            return static::MODES[(string) $this->modes] & 32;
        }
    }
} 