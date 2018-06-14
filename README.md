<h1 align="center">BaseN</h1>
<p align="center">
    <a href="https://packagist.org/packages/xobotyi/basen">
        <img alt="License" src="https://poser.pugx.org/xobotyi/basen/license" />
    </a>
    <a href="https://packagist.org/packages/xobotyi/basen">
        <img alt="PHP 7 ready" src="http://php7ready.timesplinter.ch/xobotyi/basen/badge.svg" />
    </a>
    <a href="https://travis-ci.org/xobotyi/basen">
        <img alt="Build Status" src="https://travis-ci.org/xobotyi/basen.svg?branch=master" />
    </a>
    <a href="https://www.codacy.com/app/xobotyi/basen">
        <img alt="Codacy Grade" src="https://api.codacy.com/project/badge/Grade/4b87c746d8d14a70a1ac399c48fad64d" />
    </a>
    <a href="https://www.codacy.com/app/xobotyi/basen">
        <img alt="Codacy Coverage" src="https://api.codacy.com/project/badge/Coverage/4b87c746d8d14a70a1ac399c48fad64d" />
    </a>
    <a href="https://packagist.org/packages/xobotyi/basen">
        <img alt="Latest Stable Version" src="https://poser.pugx.org/xobotyi/basen/v/stable" />
    </a>
    <a href="https://packagist.org/packages/xobotyi/basen">
        <img alt="Total Downloads" src="https://poser.pugx.org/xobotyi/basen/downloads" />
    </a>
</p>

## About
PHP is a great language but unfortunately provides us with only one text encoding (base64) which even not URL safe. And there are no straight way to change its alphabet.  
BaseN solves that problem and implements common binary-to-text algorithm for encodings whose alphabet fully covers number of bits that corresponds its length. And rough algorithm which will encode each byte separately, it is less compact but guarantee the encoding with given alphabet.  
Furthermore it gives you methods to encode and decode integers themselves instead of their text representation.

## Requirements
- [PHP](//php.net/) 7.1+

## Installation
Install with composer
```bash
composer require xobotyi/basen
```

## Usage
```php
use xobotyi\basen\BaseN;
use xobotyi\basen\Base58;

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
```

## Builtin encodings
BaseN provides few classes implementing most popular encodings: 
 - [Base16](https://en.wikipedia.org/wiki/Base16) (0-9a-f)
 - [Base32](https://en.wikipedia.org/wiki/Base32) (a-z2-7)
 - [Base36](https://en.wikipedia.org/wiki/Base36) (0-9a-z)
 - [Base58](https://en.wikipedia.org/wiki/Base58) (0-9A-Za-v)
 - Base62 (0-9A-Za-z)
 - [Base64](https://en.wikipedia.org/wiki/Base64) (0-9A-Za-z+/)
 - [Base85](https://en.wikipedia.org/wiki/Base85) (!"#$%&'()*+,-./0-9:;<=>?@A-Z[\]^_`a-u)