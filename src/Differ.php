<?php

namespace Differ;

use function Differ\Parser\parse;
use function Differ\Renderer\renderAST;

function genDiff(string $firstPath, string $secondPath)
{
    $firstFileExt = pathinfo($firstPath, PATHINFO_EXTENSION);
    $secondFileExt = pathinfo($secondPath, PATHINFO_EXTENSION);

    if ($firstFileExt !== $secondFileExt) {
        throw new Error('Cannot compare files of different extensions.');
    }

    $firstFileData = file_get_contents($firstPath, true);
    $secondFileData = file_get_contents($secondPath, true);

    $firstFileArray = parse($firstFileExt, $firstFileData);
    $secondFileArray = parse($secondFileExt, $secondFileData);
    
    $ast = genDiffAST($firstFileArray, $secondFileArray);

    return renderAST($ast);
}

function genDiffAST(array $firstFile, array $secondFile)
{
    $keys = array_merge(
        array_keys($firstFile),
        array_keys($secondFile)
    );
    $uniqueKeys = array_unique($keys);

    $ast = array_reduce($uniqueKeys, function ($ast, $key) use ($firstFile, $secondFile) {

        $firstFileValue = $firstFile[$key] ?? null;
        $secondFileValue = $secondFile[$key] ?? null;

        // removed value
        if (!key_exists($key, $secondFile)) {
            $ast[] = ['type' => 'removed', 'name' => $key, 'before' => $firstFileValue];
            return $ast;
        }
        // added value
        if (!key_exists($key, $firstFile)) {
            $ast[] = ['type' => 'added', 'name' => $key, 'after' => $secondFileValue];
            return $ast;
        }
        // nested children
        if (is_array($firstFileValue) && is_array($secondFileValue)) {
            $children = genDiffAST($firstFileValue, $secondFileValue);
            $ast[] = ['type' => 'nested', 'name' => $key,
                      'before' => $firstFileValue, 'after' => $secondFileValue, 'children' => $children];
            return $ast;
        }
        // same value
        if ($firstFileValue === $secondFileValue) {
            $ast[] = ['type' => 'unchanged', 'name' => $key, 'before' => $firstFileValue, 'after' => $secondFileValue];
            return $ast;
        }
        // changed
        if (key_exists($key, $firstFile) && key_exists($key, $secondFile) && $firstFileValue !== $secondFileValue) {
            $ast[] = ['type' => 'changed', 'name' => $key, 'before' => $firstFileValue, 'after' => $secondFileValue];
            return $ast;
        }
    }, []);
    return $ast;
}
