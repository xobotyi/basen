<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\basen;

class Base36 implements Interfaces\Encoder
{
    public const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyz';
    public const ALPHABET_INVERTED = 'abcdefghijklmnopqrstuvwxyz0123456789';

    public const ALPHABETS = [
        self::ALPHABET,
    ];

    use Traits\Encoder;

    private static function getBaseConverter() :BaseN {
        return self::$converter
            ? self::$converter
            : self::$converter = new BaseN(self::ALPHABET, false);
    }
}