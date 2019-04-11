<?php

namespace Parser;

use Symfony\Component\Yaml\Yaml;

function parse($firstFileExt, $firstPath, $secondPath)
{
    switch ($firstFileExt) {
        case 'json':
            $firstFileJson = file_get_contents($firstPath);
            $secondFileJson = file_get_contents($secondPath);
            break;
        case 'yml':
            $firstFileJson = json_encode(Yaml::parseFile($firstPath, Yaml::PARSE_OBJECT_FOR_MAP));
            $secondFileJson = json_encode(Yaml::parseFile($secondPath, Yaml::PARSE_OBJECT_FOR_MAP));
            break;
    }
    return ['first' => $firstFileJson, 'second' => $secondFileJson];
}
