<?php

namespace Differ\Renderer\Text;

function toString($arr, $depth)
{
    $indent = str_repeat('    ', $depth);
    $keys = array_keys($arr);

    $res = array_map(function ($key) use ($arr, $indent, $depth) {
        if (is_array($arr[$key])) {
            return toString($arr[$key], $depth);
        }
        return "{$indent}  {$key}: {$arr[$key]}";
    }, $keys);

    $res = implode(PHP_EOL, $res);
    return "{". PHP_EOL .$res. PHP_EOL;
}

function astToText(array $ast, $depth = 1) : string
{
    $indent = str_repeat('    ', $depth);
    
    $res = array_map(function ($item) use ($indent, $depth) {
        if (isset($item['beforeValue'])) {
            if (is_array($item['beforeValue'])) {
                $before = toString($item['beforeValue'], $depth + 1) . "{$indent}  }";
            } else {
                $before = $item['beforeValue'];
            }
        }
        if (isset($item['afterValue'])) {
            if (is_array($item['afterValue'])) {
                $after = toString($item['afterValue'], $depth + 1) . "{$indent}  }";
            } else {
                $after = $item['afterValue'];
            }
        }

        switch ($item['type']) {
            case 'added':
                return "{$indent}+ {$item['name']}: {$after}";
                break;
            case 'removed':
                return "{$indent}- {$item['name']}: {$before}";
                break;
            case 'unchanged':
                return "{$indent}  {$item['name']}: {$before}";
                break;
            case 'changed':
                return "{$indent}+ {$item['name']}: {$after}" . PHP_EOL . "{$indent}- {$item['name']}: {$before}";
                break;
            case 'nested':
                $nested = astToText($item['children'], $depth + 1);
                return "{$indent}  {$item['name']}: {" . PHP_EOL . "{$nested}" . PHP_EOL ."{$indent}  }";
                break;
        }
    }, $ast);
    return implode(PHP_EOL, $res);
}
