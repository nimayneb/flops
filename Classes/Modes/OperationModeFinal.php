<?php namespace JayBeeR\Flops\Modes {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    class OperationModeFinal implements OpenMode, ReadingMode, WritingMode
    {
        use ModesProperty;

        /**
         * @return FinalMode
         */
        public function writing()
        {
            return FinalMode::get($this->modes[FileMode::WRITING]);
        }

        /**
         * @return FinalMode
         */
        public function reading()
        {
            return FinalMode::get($this->modes[FileMode::READING]);
        }
    }
} 