<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($fileExt, $fileData)
{
    switch ($fileExt) {
        case 'json':
            return json_decode($fileData, true);
        case 'yml':
            return Yaml::parse($fileData);
    }
}
