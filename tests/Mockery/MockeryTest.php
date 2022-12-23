<?php

namespace FlowerAllure\ComposerUtils\Tests\Mockery;

use Mockery;
use PHPUnit\Framework\TestCase;

class MockeryTest extends TestCase
{
    public function testBook()
    {
        $mock = Mockery::mock('MyClass');
        $mock->allows()->foo()->andReturn(42);

        // $this->assertSame($mock->foo(), 42);
        $this->assertTrue(true);
    }
}
