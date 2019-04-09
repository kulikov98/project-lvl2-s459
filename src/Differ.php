<?php

namespace Differ;

function genDiff($firstPath, $secondPath)
{
    $firstFile = json_decode(file_get_contents($firstPath), true);
    $secondFile = json_decode(file_get_contents($secondPath), true);

    $keys = array_unique(array_keys(array_merge($firstFile, $secondFile)));

    $res = array_reduce($keys, function($acc, $item) use ($firstFile, $secondFile) {
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
