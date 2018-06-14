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

    public function testIntegers() {
        $base8 = new BaseN('01234567');

        $this->assertEquals('20', $base8->encodeInt(16));
        $this->assertEquals(16, $base8->decodeInt('20'));
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