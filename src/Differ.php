<?php

namespace Differ;

use Symfony\Component\Yaml\Yaml;
use function Functions\genDiffText;

function genDiff($firstPath, $secondPath)
{
    $firstFileFormat = pathinfo($firstPath, PATHINFO_EXTENSION);
    $secondFileFormat = pathinfo($secondPath, PATHINFO_EXTENSION);

    if ($firstFileFormat === $secondFileFormat) {
        switch ($firstFileFormat) {
            case 'json':
                $firstFileContentJson = file_get_contents($firstPath);
                $secondFileContentJson = file_get_contents($secondPath);
                break;
            case 'yml':
                $firstFileContentJson = json_encode(Yaml::parseFile($firstPath, Yaml::PARSE_OBJECT_FOR_MAP));
                $secondFileContentJson = json_encode(Yaml::parseFile($secondPath, Yaml::PARSE_OBJECT_FOR_MAP));
                break;
        }
        return genDiffText($firstFileContentJson, $secondFileContentJson);
    }
}
