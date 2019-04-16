<?php

namespace Differ\Ast;

use function Funct\Collection\union;

function genDiffAST(array $firstParsed, array $secondParsed) : array
{
    $keys = union(
        array_keys($firstParsed),
        array_keys($secondParsed)
    );

    $ast = array_reduce($keys, function ($ast, $key) use ($firstParsed, $secondParsed) {

        $beforeValue = $firstParsed[$key] ?? null;
        $afterValue = $secondParsed[$key] ?? null;

        // removed value
        if (!key_exists($key, $secondParsed)) {
            $ast[] = ['type' => 'removed', 'name' => $key, 'beforeValue' => $beforeValue];
            return $ast;
        }
        // added value
        if (!key_exists($key, $firstParsed)) {
            $ast[] = ['type' => 'added', 'name' => $key, 'afterValue' => $afterValue];
            return $ast;
        }
        // nested children
        if (is_array($beforeValue) && is_array($afterValue)) {
            $children = genDiffAST($beforeValue, $afterValue);
            $ast[] = [
                'type' => 'nested', 'name' => $key,
                'beforeValue' => $beforeValue, 'afterValue' => $afterValue, 'children' => $children
            ];
            return $ast;
        }
        // same value
        if ($beforeValue === $afterValue) {
            $ast[] = [
                'type' => 'unchanged', 'name' => $key, 'beforeValue' => $beforeValue,
                'afterValue' => $afterValue
            ];
            return $ast;
        }
        // changed
        if (key_exists($key, $firstParsed) && key_exists($key, $secondParsed)
        && $beforeValue !== $afterValue) {
            $ast[] = ['type' => 'changed', 'name' => $key, 'beforeValue' => $beforeValue,
            'afterValue' => $afterValue];
            return $ast;
        }
    }, []);
    return $ast;
}
