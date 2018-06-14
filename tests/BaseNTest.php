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
        $base8 = new BaseN('01234567', false, false, false);

        self::assertEquals('01234567', $base8->getAlphabet());
        $base8->setAlphabet('01');
        self::assertEquals('01', $base8->setAlphabet('01')->getAlphabet());
        self::assertEquals('=', $base8->getPadCharacter());
        self::assertEquals('+', $base8->setPadCharacter('+')->getPadCharacter());
        self::assertEquals(false, $base8->isPaddingFinalBits());
        self::assertEquals(true, $base8->setPadFinalBits(true)->isPaddingFinalBits());
        self::assertEquals(false, $base8->isPaddingFinalGroup());
        self::assertEquals(true, $base8->setPadFinalGroup(true)->isPaddingFinalGroup());
        self::assertEquals(false, $base8->isCaseSensitive());
        self::assertEquals(true, $base8->setCaseSensitive(true)->isCaseSensitive());
    }
}