<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\basen;

class Base85 implements Interfaces\Encoder
{
    public const ALPHABET         = "!\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstu";
    public const ALPHABET_Z85     = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz.-:+=^!/*?&<>()[]{}@%$#';
    public const ALPHABET_RFC1924 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!#$%&()*+-;<=>?@^_`{|}~';

    public const ALPHABETS = [
        self::ALPHABET,
        self::ALPHABET_Z85,
        self::ALPHABET_RFC1924,
    ];

    use Traits\Encoder;

    private static function getBaseConverter() :BaseN {
        return self::$converter
            ? self::$converter
            : self::$converter = new BaseN(self::ALPHABET, true);
    }
}