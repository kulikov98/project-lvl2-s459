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

    define('FIRST_FILE', json_decode(file_get_contents(FIRST_PATH), true));
    define('SECOND_FILE', json_decode(file_get_contents(SECOND_PATH), true));

    $res = array_reduce(FIRST_FILE, function($carry, $value) {
        print "{$carry}";
    }, []);
    var_dump($res);

    $unchanged = array_intersect_assoc(FIRST_FILE, SECOND_FILE);
    $old = array_diff_key(FIRST_FILE, SECOND_FILE);
    $new = array_diff_key(SECOND_FILE, FIRST_FILE);
    $changed = array_diff_assoc(FIRST_FILE, SECOND_FILE);
    $changed2 = array_diff_assoc(SECOND_FILE, FIRST_FILE);
    $a = array_intersect_key($changed2, $changed);

    print PHP_EOL."Не изменились: ";
    var_dump($unchanged);
    print PHP_EOL."Удалены: ";
    var_dump($old);
    print PHP_EOL."Созданы: ";
    var_dump($new);
    print PHP_EOL."Изменены: ";
    var_dump($changed);
    var_dump($changed2);
    var_dump($a);

    
    $different = array_diff_assoc(FIRST_FILE, SECOND_FILE);
    



    
}
