<?php namespace JayBeeR\Flops\Modes {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    class Reading implements OpenMode
    {
        use ModesProperty;

        /**
         * @return SpecialModeFinal
         */
        public function writing()
        {
            return SpecialModeFinal::get($this->modes[FileMode::WRITING]);
        }

        /**
         * @return ReadingMode
         */
        public function creating(): ReadingMode
        {
            return OperationModeFinal::get($this->modes[FileMode::CREATING]);
        }

        /**
         * @return ReadingMode
         */
        public function appending(): ReadingMode
        {
            return OperationModeFinal::get($this->modes[FileMode::APPENDING]);
        }

        /**
         * @return ReadingMode
         */
        public function truncating(): ReadingMode
        {
            return OperationModeFinal::get($this->modes[FileMode::TRUNCATING]);
        }
    }
} 