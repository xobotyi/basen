<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\basen;

class Base62
{
    public const ALPHABET          = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const ALPHABET_INVERTED = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    public const ALPHABETS = [
        self::ALPHABET,
        self::ALPHABET_INVERTED,
    ];

    use Traits\Encoder;

    private static function getBaseConverter() :BaseN {
        return self::$converter
            ? self::$converter
            : self::$converter = new BaseN(self::ALPHABET, true);
    }
}