<?php namespace JayBeeR\Flops\Charsets {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use Closure;

    interface Encoding
    {
        /**
         * @param Closure $byteHandler
         *
         * @return string
         */
        public function assemble(Closure $byteHandler): string;

        /**
         * @return null|string
         */
        public function getByteOrderMark(): ?string;
    }
}
