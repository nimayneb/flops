<?php namespace JayBeeR\Flops\Properties {

    use JayBeeR\Flops\Failures\InvalidCharacterForFileName;
    use JayBeeR\Flops\Reference;

    trait NameAndExtension
    {
        use NameProperty;
        use ExtensionProperty;

        /**
         * @return string
         */
        public function getFullName(): string
        {
            $fullName = $this->getName();
            $extension = $this->getExtension();

            if (!empty($extension)) {
                $fullName .= sprintf('.%s', $extension);
            }

            return $fullName;
        }

        /**
         * @param string $name
         *
         * @return string
         * @throws InvalidCharacterForFileName
         */
        public function getNameWithExtension(string $name): string
        {
            Reference::assertValidName($name);

            $extension = $this->getExtension();

            if (empty($extension)) {
                $newFilePath = $name;
            } else {
                $newFilePath = sprintf('%s.%s', $name, $extension);
            }

            return $newFilePath;
        }
    }
}