<?php namespace JayBeeR\Flops\Operations {

    use JayBeeR\Flops\DirectoryReference;
    use JayBeeR\Flops\Properties\ExtensionProperty;
    use JayBeeR\Flops\Properties\NameProperty;
    use JayBeeR\Flops\Properties\ReferenceProperty;

    trait MoveFolderOperation
    {
        use ReferenceProperty;
        use FlushOperation;
        use NameProperty;
        use ExtensionProperty;
        use DeleteFolderOperation;

        /**
         * @param DirectoryReference $pathReference
         * @param string|null $newName
         * @param bool $overwrite
         *
         * @return DirectoryReference|$this
         */
        public function moveTo(DirectoryReference $pathReference, string $newName = null, bool $overwrite = false): DirectoryReference
        {
            return $this;
        }
    }
}