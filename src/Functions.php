<?php

namespace Functions;

function genDiffText($firstFileContentContentJson, $secondFileContentContentJson)
{
    $firstFileContent = json_decode($firstFileContentContentJson, true);
    $secondFileContent = json_decode($secondFileContentContentJson, true);

    $keys = array_unique(array_keys(array_merge($firstFileContent, $secondFileContent)));

    $res = array_reduce($keys, function ($acc, $item) use ($firstFileContent, $secondFileContent) {
        // deleted value
        if (key_exists($item, $firstFileContent) && !key_exists($item, $secondFileContent)) {
            $acc[] = "- {$item}: {$firstFileContent[$item]}";
        }
        // new value
        if (!key_exists($item, $firstFileContent) && key_exists($item, $secondFileContent)) {
            $acc[] = "+ {$item}: {$secondFileContent[$item]}";
        }
        if (key_exists($item, $firstFileContent) && key_exists($item, $secondFileContent)) {
            // same value
            if ($firstFileContent[$item] === $secondFileContent[$item]) {
                $acc[] = "  {$item}: {$firstFileContent[$item]}";
                // changed value
            } else {
                $acc[] = "+ {$item}: {$secondFileContent[$item]}";
                $acc[] = "- {$item}: {$firstFileContent[$item]}";
            }
        }
        return $acc;
    }, ['{']);

    $res[] = '}';

    return implode(PHP_EOL, $res);
}
