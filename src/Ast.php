<?php

namespace Differ\Ast;

use function Funct\Collection\union;

function genDiffAST(array $firstFile, array $secondFile)
{
    $keys = union(
        array_keys($firstFile),
        array_keys($secondFile)
    );

    $ast = array_reduce($keys, function ($ast, $key) use ($firstFile, $secondFile) {

        $firstFileValue = $firstFile[$key] ?? null;
        $secondFileValue = $secondFile[$key] ?? null;

        // removed value
        if (!key_exists($key, $secondFile)) {
            $ast[] = ['type' => 'removed', 'name' => $key, 'beforeValue' => $firstFileValue];
            return $ast;
        }
        // added value
        if (!key_exists($key, $firstFile)) {
            $ast[] = ['type' => 'added', 'name' => $key, 'afterValue' => $secondFileValue];
            return $ast;
        }
        // nested children
        if (is_array($firstFileValue) && is_array($secondFileValue)) {
            $children = genDiffAST($firstFileValue, $secondFileValue);
            $ast[] = [
                'type' => 'nested', 'name' => $key,
                'beforeValue' => $firstFileValue, 'afterValue' => $secondFileValue, 'children' => $children
            ];
            return $ast;
        }
        // same value
        if ($firstFileValue === $secondFileValue) {
            $ast[] = [
                'type' => 'unchanged', 'name' => $key, 'beforeValue' => $firstFileValue,
                'afterValue' => $secondFileValue
            ];
            return $ast;
        }
        // changed
        if (key_exists($key, $firstFile) && key_exists($key, $secondFile)
        && $firstFileValue !== $secondFileValue) {
            $ast[] = ['type' => 'changed', 'name' => $key, 'beforeValue' => $firstFileValue,
            'afterValue' => $secondFileValue];
            return $ast;
        }
    }, []);
    return $ast;
}
