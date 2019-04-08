<?php

namespace Gendiff\Cli;

use function \cli\line;
use function \cli\prompt;

function run($doc)
{
    $args = (new \Docopt\Handler)->handle($doc);
}
