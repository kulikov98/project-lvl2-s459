<?php

namespace Differ\Renderer;

function boolToStr($item)
{
    return $item === true ? 'true' : 'false';
}

function toString($arr, $indent)
{
    $keys = array_keys($arr);
    $res = array_reduce($keys, function ($acc, $item) use ($arr, $indent) {
        if (!is_array($arr[$item])) {
            $arr[$item] = is_bool($arr[$item]) ? boolToStr($arr[$item]) : $arr[$item];
            $acc[] = "{$indent}{$item}: {$arr[$item]}";
            return $acc;
        }
        if (is_array($arr[$item])) {
            $acc[] = toString($arr[$item], $indent);
            return $acc;
        }
    }, []);
    $res = implode(PHP_EOL, $res);
    return "{" . PHP_EOL .'      '.$res. PHP_EOL . $indent."  }";
}

function renderAST(array $ast, $indent = '    ')
{
    $diff = array_reduce($ast, function ($diff, $node) use ($indent) {
        if (isset($node['before'])) {
            $before = is_array($node['before']) ? toString($node['before'], $indent) : $node['before'];
        }
        if (isset($node['after'])) {
            $after = is_array($node['after']) ? toString($node['after'], $indent) : $node['after'];
        }
        if (isset($node['type'])) {
            switch ($node['type']) {
                case 'unchanged':
                    $after = is_bool($after) ? boolToStr($after) : $after;
                    $diff[] = "{$indent}  {$node['name']}: {$after}";
                    break;
                case 'added':
                    $after = is_bool($after) ? boolToStr($after) : $after;
                    $diff[] = "{$indent}+ {$node['name']}: {$after}";
                    break;
                case 'removed':
                    $before = is_bool($before) ? boolToStr($before) : $before;
                    $diff[] = "{$indent}- {$node['name']}: {$before}";
                    break;
                case 'changed':
                    $after = is_bool($after) ? boolToStr($after) : $after;
                    $before = is_bool($before) ? boolToStr($before) : $before;
                    $diff[] = "{$indent}+ {$node['name']}: {$after}";
                    $diff[] = "{$indent}- {$node['name']}: {$before}";
                    break;
                case 'nested':
                    $diff[] = "{$indent}  {$node['name']}: {".renderAST($node['children'], $indent.$indent).$indent."  }";
                    break;
            }
        }
        return $diff;
    }, []);
    $diff = implode(PHP_EOL, $diff);
    $res = PHP_EOL . $diff . PHP_EOL;
    return $res;
}
