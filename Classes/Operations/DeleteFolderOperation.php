<?php namespace JayBeeR\Flops\Operations {

    use JayBeeR\Flops\Failures\CannotDeleteFolder;
    use JayBeeR\Flops\Failures\CannotDeleteNotEmptyFolder;
    use JayBeeR\Flops\Properties\ReferenceProperty;

    trait DeleteFolderOperation
    {
        use ReferenceProperty;
        use FlushOperation;

        /**
         * @return void
         * @throws CannotDeleteFolder
         * @throws CannotDeleteNotEmptyFolder
         */
        public function deleteFolder(): void
        {
            if (0 !== iterator_count($this->findAll(true))) {
                throw new CannotDeleteNotEmptyFolder($this->reference);
            }

            if (!rmdir($this->reference)) {
                throw new CannotDeleteFolder($this->reference);
            }

            $this->flush();
        }

        /**
         * @param string $reference
         * @param bool $overwrite
         *
         * @return bool
         * @throws CannotDeleteFolder
         * @throws CannotDeleteNotEmptyFolder
         */
        protected function ifFolderExistsThenTryToDeleteIt(string $reference, bool $overwrite): bool
        {
            $tryDelete = is_dir($reference);

            if (($tryDelete) && ($overwrite)) {
                $this->deleteFolder();
                $tryDelete = false;
            }

            return (!$tryDelete);
        }
    }
}