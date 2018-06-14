<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

include_once __DIR__ . '/../vendor/autoload.php';

use xobotyi\basen\Base58;
use xobotyi\basen\BaseN;

// use it for something usual
$base8 = new BaseN('01234567', false, false, false);
echo $base8->encode(16) . "\n"; // 142330
echo $base8->encodeInt(16) . "\n"; // 20

// or create your own encoder with own alphabet if needed
$myOwnEncoder = new BaseN('a123d8e4fiwnmqkl', false, true, true);
echo $myOwnEncoder->encode(16) . "\n"; // 313e
echo $myOwnEncoder->encodeInt(16) . "\n"; // 1a

// predefined encoder
echo Base58::encode(16) . "\n"; // 3hC
// or, with alternative alphabet
echo Base58::encode(16, Base58::ALPHABET_RIPPLE) . "\n"; // hkD
echo Base58::encodeInt(16) . "\n"; // G