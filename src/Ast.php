<?php

namespace Differ\Ast;

use function Funct\Collection\union;

function genDiffAST(array $beforeData, array $afterData) : array
{
    $keys = union(
        array_keys($beforeData),
        array_keys($afterData)
    );

    $ast = array_reduce($keys, function ($ast, $key) use ($beforeData, $afterData) {

        $beforeValue = $beforeData[$key] ?? null;
        $afterValue = $afterData[$key] ?? null;

        // removed value
        if (!key_exists($key, $afterData)) {
            $ast[] = ['type' => 'removed', 'name' => $key, 'beforeValue' => $beforeValue];
            return $ast;
        }
        // added value
        if (!key_exists($key, $beforeData)) {
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
        if (key_exists($key, $beforeData) && key_exists($key, $afterData)
        && $beforeValue !== $afterValue) {
            $ast[] = ['type' => 'changed', 'name' => $key, 'beforeValue' => $beforeValue,
            'afterValue' => $afterValue];
            return $ast;
        }
    }, []);
    return $ast;
}
