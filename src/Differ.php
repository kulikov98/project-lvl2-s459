<?php

namespace Differ;

use function Differ\Parser\parse;
use function Differ\Renderer\astToPlain;
use function Differ\Ast\genDiffAST;

function genDiff(string $firstPath, string $secondPath, string $format) : string
{
    $firstFileFormat = pathinfo($firstPath, PATHINFO_EXTENSION);
    $secondFileFormat = pathinfo($secondPath, PATHINFO_EXTENSION);

    if ($firstFileFormat !== $secondFileFormat) {
        throw new \Exception('Cannot compare files of different formats.');
    }

    $firstFile = file_get_contents($firstPath, true);
    $secondFile = file_get_contents($secondPath, true);

    $firstFileData = parse($firstFileFormat, $firstFile);
    $secondFileData = parse($secondFileFormat, $secondFile);

    $ast = genDiffAST($firstFileData, $secondFileData);

    switch ($format) {
        case 'plain':
            return astToPlain($ast);
    }
}
