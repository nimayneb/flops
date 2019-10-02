<?php namespace JayBeeR\Flops\Modes {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    class Writing extends Mode
    {
        /**
         * @return SpecialModeFinal
         */
        public function reading()
        {
            return SpecialModeFinal::get($this->modes[FileMode::READING]);
        }

        /**
         * @return WritingMode
         */
        public function mustNotExists(): WritingMode
        {
            return OperationModeFinal::get($this->modes[FileMode::CREATING]);
        }

        /**
         * @return WritingMode
         */
        public function mustExists(): WritingMode
        {
            return OperationModeFinal::get($this->modes[FileMode::EXISTING]);
        }

        /**
         * @return WritingMode
         */
        public function appending(): WritingMode
        {
            return OperationModeFinal::get($this->modes[FileMode::APPENDING]);
        }

        /**
         * @return WritingMode
         */
        public function truncating(): WritingMode
        {
            return OperationModeFinal::get($this->modes[FileMode::TRUNCATING]);
        }
    }
} 