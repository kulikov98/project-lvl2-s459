<?php

namespace Differ;

use function Functions\genDiffText;
use function Parser\parse;

function genDiff($firstPath, $secondPath)
{
    $firstFileExt = pathinfo($firstPath, PATHINFO_EXTENSION);
    $firstFileExt = pathinfo($secondPath, PATHINFO_EXTENSION);

    if ($firstFileExt !== $firstFileExt) {
        return 'Cannot compare files of different extensions.';
    }

    $files = parse($firstFileExt, $firstPath, $secondPath);

    return genDiffText($files['first'], $files['second']);
}
