<?php

namespace Differ;

use Symfony\Component\Yaml\Yaml;

function genDiff($firstPath, $secondPath)
{
    $firstFileFormat = pathinfo($firstPath, PATHINFO_EXTENSION);
    $secondFileFormat = pathinfo($secondPath, PATHINFO_EXTENSION);

    if ($firstFileFormat === $secondFileFormat) {
        switch ($firstFileFormat) {
            case 'json':
                $firstFile = file_get_contents($firstPath);
                $secondFile = file_get_contents($secondPath);
                break;
            case 'yml':
                $firstFile = json_encode(Yaml::parseFile($firstPath, Yaml::PARSE_OBJECT_FOR_MAP));
                $secondFile = json_encode(Yaml::parseFile($secondPath, Yaml::PARSE_OBJECT_FOR_MAP));
                break;
        }
        return genDiffText($firstFile, $secondFile);
    }
}

function genDiffText($firstFile, $secondFile)
{
    $firstFile = json_decode($firstFile, true);
    $secondFile = json_decode($secondFile, true);

    $keys = array_unique(array_keys(array_merge($firstFile, $secondFile)));

    $res = array_reduce($keys, function ($acc, $item) use ($firstFile, $secondFile) {
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
