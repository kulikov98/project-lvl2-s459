<?php

namespace Differ;

use function Differ\Parser\parse;
use function Differ\Ast\genDiffAST;
use function Differ\Renderer\Plain\astToPlain;
use function Differ\Renderer\Text\astToText;

function genDiff(string $firstPath, string $secondPath, string $format) : string
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
        case 'plain':
            return astToPlain($ast);
            break;
        case 'text':
            return "{". PHP_EOL .astToText($ast). PHP_EOL ."}";
            break;
    }
}
