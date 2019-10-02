<?php namespace JayBeeR\Flops\Modes {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    class Appending extends Mode
    {
        /**
         * @return WritingMode
         */
        public function reading(): WritingMode
        {
            return OperationModeFinal::get($this->modes[FileMode::READING]);
        }

        /**
         * @return ReadingMode
         */
        public function writing(): ReadingMode
        {
            return OperationModeFinal::get($this->modes[FileMode::WRITING]);
        }
    }
} 