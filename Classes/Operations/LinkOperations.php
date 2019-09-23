<?php namespace JayBeeR\Flops\Operations {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Reference;

    interface LinkOperations
    {
        public function linkTo(Reference $pathReference, string $newName = null, bool $existence = false): Reference;
    }
}
