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

function getValue($value)
{
    if (is_array($value)) {
        return 'complex value';
    }
    return toString($value);
}

function genPlainDiff(array $ast, string $path = ''): string
{
    $diff = array_reduce($ast, function ($diff, $node) use ($path) {
        switch ($node['type']) {
            case 'added':
                $after = getValue($node['afterValue']);
                $diff[] = "Property '{$path}{$node['name']}' was added with value: '{$after}'";
                break;
            case 'removed':
                $diff[] = "Property '{$path}{$node['name']}' was removed";
                break;
            case 'changed':
                $before = getValue($node['beforeValue']);
                $after = getValue($node['afterValue']);
                $diff[] = "Property '{$path}{$node['name']}' was changed. From '{$before}' to '{$after}'";
                break;
            case 'nested':
                $diff[] = genPlainDiff($node['children'], "{$path}{$node['name']}.");
                break;
        }
        return $diff;
    }, []);
    return implode(PHP_EOL, $diff);
}
