<?php

namespace Differ\Renderer\Plain;

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

function astToPlain(array $ast, string $path = '') : string
{
    $diff = array_reduce($ast, function ($diff, $node) use ($path) {
        switch ($node['type']) {
            case 'added':
                $after = is_array($node['afterValue']) ? 'complex value' : toString($node['afterValue']);
                $diff[] = "Property '{$path}{$node['name']}' was added with value: '{$after}'";
                break;
            case 'removed':
                $diff[] = "Property '{$path}{$node['name']}' was removed";
                break;
            case 'changed':
                $before = is_array($node['beforeValue']) ? 'complex value' : toString($node['beforeValue']);
                $after = is_array($node['afterValue']) ? 'complex value' : toString($node['afterValue']);
                $diff[] = "Property '{$path}{$node['name']}' was changed. From '{$before}' to '{$after}'";
                break;
            case 'nested':
                $diff[] = astToPlain($node['children'], "{$path}{$node['name']}.");
                break;
        }
        return $diff;
    }, []);
    return implode(PHP_EOL, $diff);
}
