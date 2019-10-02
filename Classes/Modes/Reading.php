<?php namespace JayBeeR\Flops\Modes {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    class Reading extends Mode
    {
        /**
         * @return SpecialModeFinal
         */
        public function writing(): SpecialModeFinal
        {
            return SpecialModeFinal::get($this->modes[FileMode::WRITING]);
        }

        /**
         * @return ReadingMode
         */
        public function mustNotExists(): ReadingMode
        {
            return OperationModeFinal::get($this->modes[FileMode::CREATING]);
        }

        /**
         * @return ReadingMode
         */
        public function mustExists(): ReadingMode
        {
            return OperationModeFinal::get($this->modes[FileMode::EXISTING]);
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