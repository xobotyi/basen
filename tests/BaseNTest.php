<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\basen;

use PHPUnit\Framework\TestCase;

class BaseNTest extends TestCase
{
    public function testCommonMethods() {
        $base8 = new BaseN('01234567');

        $this->assertEquals('01234567', $base8->getAlphabet());
        $base8->setAlphabet('01');
        $this->assertEquals('01', $base8->setAlphabet('01')->getAlphabet());
        $this->assertEquals('=', $base8->getPadCharacter());
        $base8->setPadCharacter('+');
        $this->assertEquals('+', $base8->setPadCharacter('+')->getPadCharacter());
        $this->assertEquals(false, $base8->isPaddingFinalBits());
        $this->assertEquals(true, $base8->setPadFinalBits(true)->isPaddingFinalBits());
        $this->assertEquals(false, $base8->isPaddingFinalGroup());
        $this->assertEquals(true, $base8->setPadFinalGroup(true)->isPaddingFinalGroup());
        $this->assertEquals(true, $base8->isCaseSensitive());
        $this->assertEquals(false, $base8->setCaseSensitive(false)->isCaseSensitive());
    }

    public function testEncoding() {
        $base8 = new BaseN('01234567');

        $this->assertEquals('142330', $base8->encode(16));
        $this->assertEquals(16, $base8->decode('142330'));
        $this->assertEquals('', $base8->encode(''));
        $this->assertEquals('', $base8->decode(''));

        $base64 = new BaseN('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/', true, true, true);

        $this->assertEquals('YQ==', $base64->encode('a'));
        $this->assertEquals('a', $base64->decode('YQ=='));

        $base16 = new BaseN('0123456789abcdef', false, true, true);

        $this->assertEquals('48656c6c6f20776f726c6421', $base16->encode('Hello world!'));
        $this->assertEquals('Hello world!', $base16->decode('48656C6C6F20776F726C6421'));
        $base16->setAlphabet('0123456789ABCDEF');
        $this->assertEquals('Hello world!', $base16->decode('48656c6c6f20776f726c6421'));
    }

    public function testIntegers() {
        $base8 = new BaseN('01234567');

        $this->assertEquals('20', $base8->encodeInt(16));
        $this->assertEquals(16, $base8->decodeInt('20'));
    }

    public function testRoughEncodings() {
        $base8 = new BaseN('abcdefghijk', false);

        $this->assertEquals('bhiiiakgkgabgbfikigbdbiegbke', $base8->encode('Hello world!'));
        $this->assertEquals('Hello world!', $base8->decode('BHIIIAKGKGABGBFIKIGBDBIEGBKE'));
        $base8->setAlphabet('ABCDEFGHIJK');
        $this->assertEquals('Hello world!', $base8->decode('bhiiiakgkgabgbfikigbdbiegbke'));
    }

    public function testExceptionUnknownCharacter() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unable to decode string, character 8 is out of alphabet");

        $base8 = new BaseN('01234567', false);
        $base8->decode('8');
    }

    public function testExceptionUnknownCharacterRough() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unable to decode string, character 0 is out of alphabet");

        $base8 = new BaseN('abcdefghijk', false);
        $base8->decode('0123');
    }

    public function testExceptionTooSmallAlphabet() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('alphabet must contain at least 2 characters');

        new BaseN('1');
    }

    public function testExceptionTooBigAlphabet() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('given alphabet requires more than 8 bits peer character which is maximal');

        new BaseN('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
    }

    public function testExceptionLongPadCharacter() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('pad character must be a single character string');

        (new BaseN('01234567'))->setPadCharacter('01');
    }

    public function testExceptionPadCharacterFromAlphabet() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('pad character can not be a member of alphabet');

        (new BaseN('01234567'))->setPadCharacter('0');
    }
}