<?php namespace JayBeeR\Flops\Modes {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    class SpecialModeFinal extends Mode
    {
        /**
         * @return EmptyMode
         */
        public function mustNotExists(): EmptyMode
        {
            return FinalMode::get($this->modes[FileMode::CREATING]);
        }

        /**
         * @return EmptyMode
         */
        public function mustExists(): EmptyMode
        {
            return FinalMode::get($this->modes[FileMode::EXISTING]);
        }

        /**
         * @return EmptyMode
         */
        public function appending(): EmptyMode
        {
            return FinalMode::get($this->modes[FileMode::APPENDING]);
        }

        /**
         * @return EmptyMode
         */
        public function truncating(): EmptyMode
        {
            return FinalMode::get($this->modes[FileMode::TRUNCATING]);
        }
    }
} 