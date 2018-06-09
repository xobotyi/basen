<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\basen;

class Base16 implements Interfaces\Encoder
{
    public const ALPHABET  = '0123456789abcdef';
    public const ALPHABETS = [
        self::ALPHABET,
    ];

    use Traits\Encoder;

    private static function getBaseConverter() :BaseN {
        return self::$converter
            ? self::$converter
            : self::$converter = new BaseN(self::ALPHABET, false, true, false);
    }
}