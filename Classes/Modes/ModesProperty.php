<?php namespace JayBeeR\Flops\Modes {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    trait ModesProperty
    {
        protected $modes;

        protected function __construct($modes)
        {
            $this->modes = $modes;
        }

        public function __toString()
        {
            if (is_array($this->modes)) {
                $content = $this->modes[FileMode::CURRENT];
            } else {
                $content = $this->modes;
            }

            return $content;
        }

        public static function get($modes)
        {
            return new static($modes);
        }
    }
}