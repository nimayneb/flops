<?php namespace JayBeeR\Flops\Operations {

    use JayBeeR\Flops\Reference;

    interface LinkOperations
    {
        public function linkTo(Reference $pathReference, string $newName = null, bool $existence = false): Reference;
    }
}