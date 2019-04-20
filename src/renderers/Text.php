<?php

namespace Differ\Renderer\Text;

function genTextDiff($ast)
{
    return "{" . PHP_EOL . astToText($ast) . PHP_EOL . "}";
}

function toString($arr, $depth)
{
    $indent = str_repeat('  ', $depth);
    $keys = array_keys($arr);

    $res = array_map(function ($key) use ($arr, $indent, $depth) {
        if (is_array($arr[$key])) {
            return toString($arr[$key], $depth);
        }
        return "{$indent}  {$key}: {$arr[$key]}";
    }, $keys);

    $res = implode(PHP_EOL, $res);
    return "{" . PHP_EOL . $res . PHP_EOL;
}

function getValue($value, $depth, $indent)
{
    if (is_array($value)) {
        return toString($value, $depth + 1) . "{$indent}  }";
    }
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }
    return $value;
}

function astToText(array $ast, $depth = 1): string
{
    $indent = str_repeat('  ', $depth);

    $res = array_map(function ($item) use ($indent, $depth) {

        switch ($item['type']) {
            case 'added':
                $after = getValue($item['afterValue'], $depth + 1, $indent);
                return "{$indent}+ {$item['name']}: {$after}";

            case 'removed':
                $before = getValue($item['beforeValue'], $depth + 1, $indent);
                return "{$indent}- {$item['name']}: {$before}";

            case 'unchanged':
                $before = getValue($item['beforeValue'], $depth + 1, $indent);
                return "{$indent}  {$item['name']}: {$before}";

            case 'changed':
                $after = getValue($item['afterValue'], $depth + 1, $indent);
                $before = getValue($item['beforeValue'], $depth + 1, $indent);
                return "{$indent}+ {$item['name']}: {$after}" . PHP_EOL . "{$indent}- {$item['name']}: {$before}";

            case 'nested':
                $nested = astToText($item['children'], $depth + 2);
                return "{$indent}  {$item['name']}: {" . PHP_EOL . "{$nested}" . PHP_EOL . "{$indent}  }";
        }
    }, $ast);
    return implode(PHP_EOL, $res);
}
