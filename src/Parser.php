<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $format, string $data) : array
{
    switch ($format) {
        case 'json':
            return json_decode($data, true);
        case 'yml':
            return Yaml::parse($data);
    }
}
