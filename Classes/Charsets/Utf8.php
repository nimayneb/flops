<?php namespace JayBeeR\Flops\Charsets {

    /**
     *  UTF-8 - 8-Bit Universal Coded Character Set (UCS) Transformation Format
     *  =======================================================================
     *
     *  Bytes | Bits      | Calculate | Characters            | Mask
     *  ------|-----------|-----------|----------------------:|----------------------------------------------------------------------------------------
     *   1    | 2^7       | 8-1       | (ASCII)          128  | `0-------` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx`
     *   2    | 2^11 (+4) | 5+(1*6)   |                2.048  | `110-----` `10------` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx`
     *   3    | 2^16 (+5) | 4+(2*6)   |               65.536  | `1110----` `10------` `10------` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx`
     *   4    | 2^21 (+5) | 3+(3*6)   |            2.097.152  | `11110---` `10------` `10------` `10------` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx`
     *   5    | 2^26 (+5) | 2+(4*6)   |           67.108.864  | `111110--` `10------` `10------` `10------` `10------` `xxxxxxxx` `xxxxxxxx` `xxxxxxxx`
     *   6    | 2^31 (+5) | 1+(5*6)   |        2.147.483.648  | `1111110-` `10------` `10------` `10------` `10------` `10------` `xxxxxxxx` `xxxxxxxx`
     *   7    | 2^36 (+5) | 0+(6*6)   |       68.719.476.736  | `11111110` `10------` `10------` `10------` `10------` `10------` `10------` `xxxxxxxx`
     *   8    | 2^42 (+6) | 0+(7*6)   |    4.398.046.511.104  | `11111111` `10------` `10------` `10------` `10------` `10------` `10------` `10------`
     *
     *
     *  Summary:
     *  --------
     *
     *          8 bytes: `18.446.744.073.709.551.616` information (trillion)
     *  8 bytes (UTF-8): `         4.398.046.511.104` characters  (billion, 2^48 = 5 bytes [3 bytes lost])
     *
     *
     *  Important note
     *  --------------
     *
     *  Do not use "Overlong encoding":
     *
     *  `11110--- 10------ 10------ 10------`
     *  `     000   000010   000010   101100` - 21 bits
     *
     *  Truncate nth 4-5 leading zeros:
     *
     *  `000000010000010101100`
     *  `     0010000010101100`
     *
     *  Encoding again:
     *
     *  `1110---- 10------ 10------`
     *  `    0010   000010   101100`
     *
     *
     *  References
     *  ----------
     *
     * @see https://en.wikipedia.org/wiki/UTF-8
     */

    use Closure;
    use JayBeeR\Flops\Failures\InvalidEncodingByte;
    use JayBeeR\Flops\Failures\MissingRequiredEncodingBytes;

    class Utf8 implements Encoding
    {
        public const ENCODING = 'UTF-8';

        /**
         *  111 0 11111 0 111 0 111 0 111111  (frequency cycle number: 315131316)
         *
         *  1: 11101111 => 239 => ï (latin small letter i with diaeresis) (314)
         *  2: 10111011 => 187 => » (right double angle quotes)
         *  3: 10111111 => 191 => ¿ (inverted question mark)
         *     --------
         *     10101011 (AND) => 171 => « (left double angle quotes)
         *
         * @see https://en.wikipedia.org/wiki/Byte_order_mark
         */
        public const BYTE_ORDER_MARK = [0xEF, 0xBB, 0xBF];

        /**
         * @return null|string
         */
        public function getByteOrderMark(): ?string
        {
            return pack('CCC', ...static::BYTE_ORDER_MARK) ?: null;
        }

        /**
         * Closure must return NULL for nothing, not empty string, false or 0 or -1.
         *
         * @param Closure $byteHandler
         *
         * @return string
         * @throws InvalidEncodingByte
         * @throws MissingRequiredEncodingBytes
         */
        public function assemble(Closure $byteHandler): string
        {
            $character = $byteHandler();

            if (null !== $character) {
                $firstByte = ord($character);

                if (($firstByte & 128) === 0) {
                    /**
                     *  Bit mask: 0------- (ASCII: 0 - 127)
                     */
                    goto nothingToLoad;
                } elseif ((($firstByte & 192) === 192) && (($firstByte & 32) === 0)) {
                    /**
                     *  Bit mask: 110----- (hex: C0) => À (latin capital letter A with grave)
                     */
                    $bytesToLoad = 1;
                } elseif ((($firstByte & 224) === 224) && (($firstByte & 16) === 0)) {
                    /**
                     *  Bit mask: 1110---- (hex: E0) => à (latin small letter a with grave)
                     */
                    $bytesToLoad = 2;
                } elseif ((($firstByte & 240) === 240) && (($firstByte & 8) === 0)) {
                    /**
                     *  Bit mask: 11110--- (hex: F0) => ð (latin small letter eth)
                     */
                    $bytesToLoad = 3;
                } elseif ((($firstByte & 248) === 248) && (($firstByte & 4) === 0)) {
                    /**
                     *  Bit mask: 111110-- (hex: F8) => ø (latin small letter o with slash)
                     */
                    $bytesToLoad = 4;
                } elseif ((($firstByte & 252) === 252) && (($firstByte & 2) === 0)) {
                    /**
                     *  Bit mask: 1111110- (hex: FC) => ü (latin small letter u with diaeresis)
                     */
                    $bytesToLoad = 5;
                } elseif ((($firstByte & 254) === 254) && (($firstByte & 1) === 0)) {
                    /**
                     *  Bit mask: 11111110 (hex: FE) => þ (latin small letter thorn)
                     */
                    $bytesToLoad = 6;
                } elseif ((($firstByte & 255) === 255)) {
                    /**
                     *  Bit mask: 11111111 (hex: FF) => ÿ (latin small letter y with diaeresis)
                     */
                    $bytesToLoad = 7;
                } else {
                    /**
                     *  Bit mask: 1------- (invalid UTF-8 encoding)
                     */
                    throw new InvalidEncodingByte($character);
                }

                for ($i = 0; $i < $bytesToLoad; $i++) {
                    if (null === ($nextByte = $byteHandler())) {
                        throw new MissingRequiredEncodingBytes($character, $bytesToLoad);
                    }

                    /**
                     *  Bit mask: 10------ (hex: 80) => € (Euro sign)
                     */
                    if ((($nextByte & 128) !== 128) && (($nextByte & 64) !== 0)) {
                        throw new InvalidEncodingByte($character);
                    }

                    $character .= $nextByte;
                }
            }

            nothingToLoad:

            return $character;
        }

        public static function encoding()
        {
            return new static();
        }
    }
} 