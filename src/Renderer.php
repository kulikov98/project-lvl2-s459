<?php

namespace Differ\Renderer;

function toString($value): string
{
    $stringValue = $value;
    if (is_bool($value)) {
        $stringValue = $value ? 'true' : 'false';
    } elseif (is_null($value)) {
        $stringValue = 'null';
    }
    return $stringValue;
}

function astToPlain(array $ast, $path = '')
{
    $diff = array_reduce($ast, function ($diff, $node) use ($path) {
        if (isset($node['beforeValue'])) {
            $before = is_array($node['beforeValue']) ? 'complex value' : toString($node['beforeValue']);
        }
        if (isset($node['afterValue'])) {
            $after = is_array($node['afterValue']) ? 'complex value' : toString($node['afterValue']);
        }
        if (isset($node['type'])) {
            switch ($node['type']) {
                case 'added':
                    $diff[] = "Property '{$path}{$node['name']}' was added with value: '{$after}'";
                    break;
                case 'removed':
                    $diff[] = "Property '{$path}{$node['name']}' was removed";
                    break;
                case 'changed':
                    $diff[] = "Property '{$path}{$node['name']}' was changed. From '{$before}' to '{$after}'";
                    break;
                case 'nested':
                    $diff[] = astToPlain($node['children'], "{$path}{$node['name']}.");
                    break;
            }
        }
        return $diff;
    }, []);
    return implode(PHP_EOL, $diff);
}
