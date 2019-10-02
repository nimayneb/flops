<?php namespace JayBeeR\Flops\Modes {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    class OperationModeFinal extends Mode implements ReadingMode, WritingMode
    {
        /**
         * @return EmptyMode
         */
        public function writing(): EmptyMode
        {
            return FinalMode::get($this->modes[FileMode::WRITING]);
        }

        /**
         * @return EmptyMode
         */
        public function reading(): EmptyMode
        {
            return FinalMode::get($this->modes[FileMode::READING]);
        }
    }
} 