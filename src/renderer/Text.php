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

        switch ($item['type']) {
            case 'added':
                is_array($item['afterValue']) ? $after = toString($item['afterValue'], $depth + 1) . "{$indent}  }" : $after = $item['afterValue'];
                return "{$indent}+ {$item['name']}: {$after}";
                
            case 'removed':
                is_array($item['beforeValue']) ? $before = toString($item['beforeValue'], $depth + 1) . "{$indent}  }" : $before = $item['beforeValue'];
                return "{$indent}- {$item['name']}: {$before}";
                
            case 'unchanged':
                is_array($item['beforeValue']) ? $before = toString($item['beforeValue'], $depth + 1) . "{$indent}  }" : $before = $item['beforeValue'];
                return "{$indent}  {$item['name']}: {$before}";
                
            case 'changed':
                is_array($item['afterValue']) ? $after = toString($item['afterValue'], $depth + 1) . "{$indent}  }" : $after = $item['afterValue'];
                is_array($item['beforeValue']) ? $before = toString($item['beforeValue'], $depth + 1) . "{$indent}  }" : $before = $item['beforeValue'];
                return "{$indent}+ {$item['name']}: {$after}" . PHP_EOL . "{$indent}- {$item['name']}: {$before}";
                
            case 'nested':
                $nested = astToText($item['children'], $depth + 1);
                return "{$indent}  {$item['name']}: {" . PHP_EOL . "{$nested}" . PHP_EOL ."{$indent}  }";
                
        }
    }, $ast);
    return implode(PHP_EOL, $res);
}
