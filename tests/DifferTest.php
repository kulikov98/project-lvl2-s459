<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff()
    {
        // Plain
        $expected = file_get_contents(
            'tests' . DIRECTORY_SEPARATOR . 'fixtures'. DIRECTORY_SEPARATOR . 'nested'
            . DIRECTORY_SEPARATOR . 'expectedPlain'
        );

        $actual = genDiff(
            'tests' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'nested'
            . DIRECTORY_SEPARATOR . 'before.json',
            'tests' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'nested'
            . DIRECTORY_SEPARATOR . 'after.json',
            'plain'
        );
        $this->assertEquals($expected, $actual);

        // Text
        $expected = file_get_contents(
            'tests' . DIRECTORY_SEPARATOR . 'fixtures'. DIRECTORY_SEPARATOR . 'nested'
            . DIRECTORY_SEPARATOR . 'expectedText'
        );

        $actual = genDiff(
            'tests' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'nested'
            . DIRECTORY_SEPARATOR . 'before.json',
            'tests' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'nested'
            . DIRECTORY_SEPARATOR . 'after.json',
            'text'
        );
        $this->assertEquals($expected, $actual);

        // Json
        $expected = file_get_contents(
            'tests' . DIRECTORY_SEPARATOR . 'fixtures'. DIRECTORY_SEPARATOR . 'nested'
            . DIRECTORY_SEPARATOR . 'expectedJson'
        );

        $actual = genDiff(
            'tests' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'nested'
            . DIRECTORY_SEPARATOR . 'before.json',
            'tests' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'nested'
            . DIRECTORY_SEPARATOR . 'after.json',
            'json'
        );
        $this->assertEquals($expected, $actual);
    }
}
