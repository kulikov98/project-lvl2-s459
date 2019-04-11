<?php

namespace Differ;

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

function genDiffText($firstFileJson, $secondFileJson)
{
    $firstFile = json_decode($firstFileJson, true);
    $secondFile = json_decode($secondFileJson, true);

    $allKeys = array_keys(array_merge($firstFile, $secondFile));
    $uniqueKeys = array_unique($allKeys);

    $res = array_reduce($uniqueKeys, function ($acc, $item) use ($firstFile, $secondFile) {
        // deleted value
        if (key_exists($item, $firstFile) && !key_exists($item, $secondFile)) {
            $acc[] = "- {$item}: {$firstFile[$item]}";
        }
        // new value
        if (!key_exists($item, $firstFile) && key_exists($item, $secondFile)) {
            $acc[] = "+ {$item}: {$secondFile[$item]}";
        }
        if (key_exists($item, $firstFile) && key_exists($item, $secondFile)) {
            // same value
            if ($firstFile[$item] === $secondFile[$item]) {
                $acc[] = "  {$item}: {$firstFile[$item]}";
                // changed value
            } else {
                $acc[] = "+ {$item}: {$secondFile[$item]}";
                $acc[] = "- {$item}: {$firstFile[$item]}";
            }
        }
        return $acc;
    }, ['{']);

    $res[] = '}';

    return implode(PHP_EOL, $res);
}
