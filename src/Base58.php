<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\basen;

class Base58 implements Interfaces\Encoder
{
    public const ALPHABET         = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuv';
    public const ALPHABET_BITCOIN = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    public const ALPHABET_FLICKR  = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    public const ALPHABET_RIPPLE  = 'rpshnaf39wBUDNEGHJKLM4PQRST7VWXYZ2bcdeCg65jkm8oFqi1tuvAxyz';

    public const ALPHABETS = [
        self::ALPHABET,
        self::ALPHABET_BITCOIN,
        self::ALPHABET_FLICKR,
        self::ALPHABET_RIPPLE,
    ];

    use Traits\Encoder;

    private static function getBaseConverter() :BaseN {
        return self::$converter
            ? self::$converter
            : self::$converter = new BaseN(self::ALPHABET, true);
    }
}