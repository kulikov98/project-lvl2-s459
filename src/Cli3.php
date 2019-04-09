<?php

namespace Gendiff\Cli;

use function \cli\line;
use function \cli\prompt;

function run($doc)
{
    $args = (new \Docopt\Handler)->handle($doc);

    $firstFilePath = $args->args['<firstFile>'];
    $secondFilePath = $args->args['<secondFile>'];

    $firstFile = new \SplFileObject($firstFilePath);
    $firstFileContents = $firstFile->fread($firstFile->getSize());
    $firstFileContents = json_decode($firstFileContents);

    $secondFile = new \SplFileObject($secondFilePath);
    $secondFileContents = $secondFile->fread($secondFile->getSize());
    $secondFileContents = json_decode($secondFileContents);

    $output = '{'.PHP_EOL;

    foreach ($firstFileContents as $key1 => $value1) {
        // ключ найден
        if (isset($secondFileContents->$key1)) {
            // значения ключей равны
            if ($secondFileContents->$key1 === $value1) {
                $output .= "  {$key1}: {$value1}".PHP_EOL;
            //значения ключей не равны
            } else {
                $output .= "+ {$key1}: {$secondFileContents->$key1}".PHP_EOL;
                $output .= "- {$key1}: {$value1}".PHP_EOL;
            }
        // ключ не найден
        } else {
            $output .= "- {$key1}: {$value1}".PHP_EOL;
        }
    }
    // поиск новых ключей
    foreach ($secondFileContents as $key2 => $value2) {
        if (!isset($firstFileContents->$key2)) {
            $output .= "+ {$key2}: {$value2}".PHP_EOL;
        }
    }

    $output .= '}'.PHP_EOL;

    print $output;
}
