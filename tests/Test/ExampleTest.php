<?php

namespace App\Tests\Test;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testAdd()
    {
        $result = 30 + 12;

        $this->assertEquals(42, $result);
    }
}