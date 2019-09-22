<?php namespace JayBeeR\Flops\Charsets {

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