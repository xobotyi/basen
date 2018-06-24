<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\basen;

class Base64
{
    public const ALPHABET                  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    public const ALPHABETS                 = [
        self::ALPHABET,
        self::ALPHABET_FREENET_URI_SAFE,
        self::ALPHABET_REGEX_SAFE,
        self::ALPHABET_URI_SAFE,
    ];
    public const ALPHABET_FREENET_URI_SAFE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789~-';
    public const ALPHABET_REGEX_SAFE       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!-';
    public const ALPHABET_URI_SAFE         = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';

    use Traits\Encoder;

    public static function encode(string $rawString, string $alphabet = null) :string {
        $alphabet = self::validateAlphabet($alphabet);

        switch ($alphabet) {
            case self::ALPHABET_URI_SAFE:
                return \str_replace('+', '-',
                                    \str_replace('/', '_',
                                                 \str_replace('=', '',
                                                              \base64_encode($rawString))));

            case self::ALPHABET_FREENET_URI_SAFE:
                return \str_replace('+', '~',
                                    \str_replace('/', '-',
                                                 \str_replace('=', '',
                                                              \base64_encode($rawString))));

            case self::ALPHABET_REGEX_SAFE:
                return \str_replace('+', '!',
                                    \str_replace('/', '-',
                                                 \str_replace('=', '',
                                                              \base64_encode($rawString))));

            default:
                return \base64_encode($rawString);
        }
    }

    public static function decode(string $encodedString, string $alphabet = null) :string {
        $alphabet = self::validateAlphabet($alphabet);

        switch ($alphabet) {
            case self::ALPHABET_URI_SAFE:
                return \base64_decode(\str_replace('-', '+',
                                                   \str_replace('_', '/', $encodedString)));

            case self::ALPHABET_FREENET_URI_SAFE:
                return \base64_decode(\str_replace('~', '+',
                                                   \str_replace('-', '/',
                                                                $encodedString)));

            case self::ALPHABET_REGEX_SAFE:
                return \base64_decode(\str_replace('!', '+',
                                                   \str_replace('-', '/',
                                                                $encodedString)));
            default:
                return \base64_decode($encodedString);
        }
    }
}