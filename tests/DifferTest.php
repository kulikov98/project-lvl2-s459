<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff()
    {
        $expected = file_get_contents('tests' . DIRECTORY_SEPARATOR . 'expected.txt');
        $actual = genDiff('tests' . DIRECTORY_SEPARATOR . 'before.json', 'tests' . DIRECTORY_SEPARATOR . 'after.json');
        $this->assertEquals($expected, $actual);

        $actual = genDiff('tests' . DIRECTORY_SEPARATOR . 'before.yml', 'tests' . DIRECTORY_SEPARATOR . 'after.yml');
        $this->assertEquals($expected, $actual);
    }
}
