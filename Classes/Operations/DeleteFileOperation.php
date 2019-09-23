<?php namespace JayBeeR\Flops\Operations {

    /*
     * This file belongs to the package "nimayneb.flops".
     * See LICENSE.txt that was shipped with this package.
     */

    use JayBeeR\Flops\Failures\CannotDeleteFile;
    use JayBeeR\Flops\Properties\ReferenceProperty;

    trait DeleteFileOperation
    {
        use ReferenceProperty;
        use FlushOperation;

        /**
         * @return void
         * @throws CannotDeleteFile
         */
        public function deleteFile(): void
        {
            // TODO: lost + found => /tmp/base64.iterator

            if (!unlink($this->reference)) {
                throw new CannotDeleteFile($this->reference);
            }

            $this->flush();
        }

        /**
         * @param string $reference
         * @param bool $overwrite
         *
         * @return bool
         * @throws CannotDeleteFile
         */
        protected function ifFileExistsThenTryToDeleteIt(string $reference, bool $overwrite): bool
        {
            $tryDelete = is_file($reference);

            if (($tryDelete) && ($overwrite)) {
                $this->deleteFile();
                $tryDelete = false;
            }

            return (!$tryDelete);
        }
    }
}
