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
        $mockResult = $mock->foo();

        $this->assertSame($mockResult, 42);
    }
}
