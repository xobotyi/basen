<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\basen;

use PHPUnit\Framework\TestCase;

class PredefinedEncodersTest extends TestCase
{
    public function testExceptionUnsupportedAlphabet() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Given alphabet is not supported");

        Base16::encodeInt(16, Base32::ALPHABET);
    }

    public function testBase16() {
        $this->assertEquals('10', Base16::encodeInt(16));
        $this->assertEquals(16, Base16::decodeInt('10'));
    }

    public function testBase32() {
        $this->assertEquals('q', Base32::encodeInt(16));
        $this->assertEquals(16, Base32::decodeInt('q'));

        $this->assertEquals('g', Base32::encodeInt(16, Base32::ALPHABET_EXTENDED_HEX));
        $this->assertEquals(16, Base32::decodeInt('g', Base32::ALPHABET_EXTENDED_HEX));
    }

    public function testBase36() {
        $this->assertEquals('2678lx5gvn5c0uydr1d', Base36::encode('Hello world!'));
        $this->assertEquals('Hello world!', Base36::decode('2678lx5gvn5c0uydr1d'));
    }

    public function testBase58() {
        $this->assertEquals('1LDlk6QWOejX6rPrJ', Base58::encode('Hello world!'));
        $this->assertEquals('Hello world!', Base58::decode('1LDlk6QWOejX6rPrJ'));
    }

    public function testBase62() {
        $this->assertEquals('t8DGCJrgUyuUEwHT', Base62::encode('Hello world!'));
        $this->assertEquals('Hello world!', Base62::decode('t8DGCJrgUyuUEwHT'));
    }

    public function testBase64() {
        $this->assertEquals('SGVsbG8gd29ybGQh', Base64::encode('Hello world!'));
        $this->assertEquals('Hello world!', Base64::decode('SGVsbG8gd29ybGQh'));

        $this->assertEquals('SGVsbG8gd29ybGQh', Base64::encode('Hello world!', Base64::ALPHABET_URI_SAFE));
        $this->assertEquals('Hello world!', Base64::decode('SGVsbG8gd29ybGQh', Base64::ALPHABET_URI_SAFE));

        $this->assertEquals('SGVsbG8gd29ybGQh', Base64::encode('Hello world!', Base64::ALPHABET_FREENET_URI_SAFE));
        $this->assertEquals('Hello world!', Base64::decode('SGVsbG8gd29ybGQh', Base64::ALPHABET_FREENET_URI_SAFE));

        $this->assertEquals('SGVsbG8gd29ybGQh', Base64::encode('Hello world!', Base64::ALPHABET_REGEX_SAFE));
        $this->assertEquals('Hello world!', Base64::decode('SGVsbG8gd29ybGQh', Base64::ALPHABET_REGEX_SAFE));
    }

    public function testBase85() {
        $this->assertEquals('6e-8Mi9*1%oPeX-', Base85::encode('Hello world!'));
        $this->assertEquals('Hello world!', Base85::decode('6e-8Mi9*1%oPeX-'));
    }
}