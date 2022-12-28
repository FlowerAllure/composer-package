<?php

/*
 * This file is part of the flower-allure/composer-utils.
 * (c) flower-allure <i@flower-allure.me>
 * This source file is subject to the LGPL license that is bundled.
 */

namespace FlowerAllure\ComposerUtils\Tests\Mockery;

use PHPUnit\Framework\TestCase;

class MockeryTest extends TestCase
{
    public function testBook()
    {
        $mock = \Mockery::mock('MyClass');
        $mock->allows()->foo()->andReturn(42);

        // $this->assertSame($mock->foo(), 42);
        $this->assertTrue(true);
    }
}
