<?php

namespace Gendiff\Cli;

use function \cli\line;
use function \cli\prompt;
use function Docopt\array_merge;

function run($doc)
{
    $args = (new \Docopt\Handler)->handle($doc);

    define('FIRST_PATH', $args->args['<firstFile>']);
    define('SECOND_PATH', $args->args['<secondFile>']);

    $firstFile = json_decode(file_get_contents(FIRST_PATH), true);
    $secondFile = json_decode(file_get_contents(SECOND_PATH), true);

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
        $acc[] = '}';

		return $acc;
    }, ['{']);
    
    var_dump($res);
}
