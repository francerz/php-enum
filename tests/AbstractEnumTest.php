<?php

namespace Francerz\Enum\Tests;

use Francerz\Enum\Dev\MyEnum;
use Francerz\Enum\EnumException;
use PHPUnit\Framework\TestCase;

class AbstractEnumTest extends TestCase
{
    public function testGetConstants()
    {
        $constants = MyEnum::getConstants();
        $expected = [
            'FIRST' => 1,
            'SECOND' => 2,
            'THIRD' => 3
        ];

        $this->assertEquals($expected, $constants);
    }

    public function testInstanceGetValue()
    {
        $first = new MyEnum(MyEnum::FIRST);
        $this->assertEquals(MyEnum::FIRST, $first->getValue());

        $second = new MyEnum(MyEnum::SECOND);
        $this->assertEquals(MyEnum::SECOND, $second->getValue());

        $third = new MyEnum(MyEnum::THIRD);
        $this->assertEquals(MyEnum::THIRD, $third->getValue());

        $third2 = MyEnum::fromValue(MyEnum::THIRD);
        $this->assertEquals(MyEnum::THIRD, $third2->getValue());

        $third3 = MyEnum::fromKey('THIRD');
        $this->assertEquals(MyEnum::THIRD, $third3->getValue());
    }

    public function testIsMethod()
    {
        $first = MyEnum::fromValue(MyEnum::FIRST);

        $this->assertTrue($first->is(MyEnum::FIRST));
        $this->assertTrue($first->is(1));
        $this->assertFalse($first->is('1'));
        $this->assertTrue($first->is('1', false));
        $this->assertTrue($first->is($first));
        $this->assertTrue($first->is(new MyEnum(MyEnum::FIRST)));

        $this->assertFalse($first->is(MyEnum::SECOND));
        $this->assertFalse($first->is(MyEnum::THIRD));
    }

    public function testInMethod()
    {
        $first = new MyEnum(MyEnum::FIRST);

        $this->assertTrue($first->in([$first]));
        $this->assertTrue($first->in(MyEnum::getValues()));

        $this->assertFalse($first->in([MyEnum::SECOND, MyEnum::THIRD]));
    }

    /**
     * @return void
     */
    public function testFailedInstance()
    {
        $this->expectException(EnumException::class);
        $enum = new MyEnum(null);
        $enum = new MyEnum(0);
        $enum = new MyEnum(4);
        $enum = new MyEnum('');
        $enum = new MyEnum('A');
    }
}
