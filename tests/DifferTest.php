<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff()
    {
        $expected = "{
  host: hexlet.io
+ timeout: 20
- timeout: 50
- proxy: 123.234.53.22
+ verbose: 1
}";
        $actual = genDiff('before.json', 'after.json');
        $this->assertEquals($expected, $actual);
    }
}