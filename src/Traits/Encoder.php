<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\basen\Traits;

use xobotyi\basen\BaseN;

trait Encoder
{
    /**
     * @var BaseN
     */
    private static $converter;

    protected static function validateAlphabet(?string $alphabet = null) {
        if ($alphabet === null) {
            return self::ALPHABET;
        }
        else if (!in_array($alphabet, self::ALPHABETS)) {
            throw new \InvalidArgumentException("Given alphabet is not supported");
        }

        return $alphabet;
    }

    public static function encode(string $rawString, string $alphabet = null, bool $padding = null) :string {
        return self::getBaseConverter()
                   ->setAlphabet(self::validateAlphabet($alphabet))
                   ->setPadFinalGroup($padding === null ? self::$converter->isPaddingFinalGroup() : !$padding)
                   ->encode($rawString);
    }

    public static function decode(string $encodedString, string $alphabet = null, bool $padding = null) :string {
        return self::getBaseConverter()
                   ->setAlphabet(self::validateAlphabet($alphabet))
                   ->setPadFinalGroup($padding === null ? self::$converter->isPaddingFinalGroup() : !$padding)
                   ->decode($encodedString);
    }

    public static function encodeInt(int $int, string $alphabet = null) :string {
        return self::getBaseConverter()
                   ->setAlphabet(self::validateAlphabet($alphabet))
                   ->encodeInt($int);
    }

    public static function decodeInt(string $encodedInt, string $alphabet = null) :int {
        return self::getBaseConverter()
                   ->setAlphabet(self::validateAlphabet($alphabet))
                   ->decodeInt($encodedInt);
    }

    private static function getBaseConverter() :BaseN {
        return self::$converter
            ? self::$converter
            : self::$converter = new BaseN(self::ALPHABET, true, true, true);
    }
}