<?php namespace JayBeeR\Flops\Modes {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    class SpecialModeFinal implements OpenMode
    {
        use ModesProperty;

        /**
         * @return FinalMode
         */
        public function creating()
        {
            return FinalMode::get($this->modes[FileMode::CREATING]);
        }

        /**
         * @return FinalMode
         */
        public function appending()
        {
            return FinalMode::get($this->modes[FileMode::APPENDING]);
        }

        /**
         * @return FinalMode
         */
        public function truncating()
        {
            return FinalMode::get($this->modes[FileMode::TRUNCATING]);
        }
    }
} 