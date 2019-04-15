<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($format, $data)
{
    switch ($format) {
        case 'json':
            return json_decode($data, true);
        case 'yml':
            return Yaml::parse($data);
    }
}
