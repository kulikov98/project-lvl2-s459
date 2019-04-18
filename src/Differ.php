<?php

namespace Differ;

use function Differ\Parser\parse;
use function Differ\Ast\genDiffAST;
use function Differ\Renderer\Plain\genPlainDiff;
use function Differ\Renderer\Text\genTextDiff;
use function Differ\Renderer\Json\genJsonDiff;

function genDiff(string $firstPath, string $secondPath, string $format): string
{
    $firstFormat = pathinfo($firstPath, PATHINFO_EXTENSION);
    $secondFormat = pathinfo($secondPath, PATHINFO_EXTENSION);

    if ($firstFormat !== $secondFormat) {
        throw new \Exception('Cannot compare files of different formats.');
    }

    $firstData = file_get_contents($firstPath, true);
    $secondData = file_get_contents($secondPath, true);

    $firstParsed = parse($firstFormat, $firstData);
    $secondParsed = parse($secondFormat, $secondData);

    $ast = genDiffAST($firstParsed, $secondParsed);

    switch ($format) {
        case 'text':
            return genTextDiff($ast);
        case 'json':
            return genJsonDiff($ast);
        default:
            return genPlainDiff($ast);
    }
}
