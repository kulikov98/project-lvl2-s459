<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff()
    {
        $expectedFlat = file_get_contents(
            'tests' . DIRECTORY_SEPARATOR . 'fixtures'. DIRECTORY_SEPARATOR . 'expected'
        );
        $expectedNested = file_get_contents(
            'tests' . DIRECTORY_SEPARATOR . 'fixtures'. DIRECTORY_SEPARATOR . 'nested' . DIRECTORY_SEPARATOR . 'expected'
        );
        
        $actual = genDiff(
            'tests' . DIRECTORY_SEPARATOR . 'fixtures'. DIRECTORY_SEPARATOR .'before.json',
            'tests' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'after.json'
        );
        //$this->assertEquals($expectedFlat, $actual);

        $actual = genDiff(
            'tests' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR .'before.yml',
            'tests' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'after.yml'
        );
        //$this->assertEquals($expectedFlat, $actual);

        $actual = genDiff(
            'tests' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'nested' . DIRECTORY_SEPARATOR . 'before.json',
            'tests' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'nested' . DIRECTORY_SEPARATOR . 'after.json'
        );
        $this->assertEquals($expectedNested, $actual);
    }
}
