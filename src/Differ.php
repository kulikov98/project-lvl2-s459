<?php

namespace Differ;

use function Differ\Parser\parse;

function genDiff(string $firstPath, string $secondPath)
{
    $firstFileExt = pathinfo($firstPath, PATHINFO_EXTENSION);
    $secondFileExt = pathinfo($secondPath, PATHINFO_EXTENSION);

    if ($firstFileExt !== $secondFileExt) {
        return 'Cannot compare files of different extensions.';
    }

    $firstFileData = file_get_contents($firstPath, true);
    $secondFileData = file_get_contents($secondPath, true);

    $firstFileArray = parse($firstFileExt, $firstFileData);
    $secondFileArray = parse($secondFileExt, $secondFileData);
    
    $ast = genDiffAST($firstFileArray, $secondFileArray);

    return $ast;
}

function genDiffAST(array $firstFile, array $secondFile)
{
    $mergedFiles = array_merge($firstFile, $secondFile);
    $allKeys = array_keys($mergedFiles);
    $uniqueKeys = array_unique($allKeys);

    $ast = array_reduce($uniqueKeys, function ($ast, $name) use ($firstFile, $secondFile) {

        $firstFileValue = $firstFile[$name] ?? null;
        $secondFileValue = $secondFile[$name] ?? null;

        // removed value
        if (!key_exists($name, $secondFile)) {
            $ast[] = ['type' => 'removed', 'name' => $name, 'before' => $firstFileValue];
            return $ast;
        }
        // added value
        if (!key_exists($name, $firstFile)) {
            $ast[] = ['type' => 'added', 'name' => $name, 'after' => $secondFileValue];
            return $ast;
        }
        // nested children
        if (is_array($firstFileValue) && is_array($secondFileValue)) {
            $children = genDiffAST($firstFileValue, $secondFileValue);
            $ast[] = ['type' => 'nested', 'name' => $name,
                      'before' => $firstFileValue, 'after' => $secondFileValue, 'children' => $children];
            return $ast;
        }
        // same value
        if ($firstFileValue === $secondFileValue) {
            $ast[] = ['type' => 'unchanged', 'name' => $name, 'before' => $firstFileValue, 'after' => $secondFileValue];
            return $ast;
        }
        // changed
        if (key_exists($name, $firstFile) && key_exists($name, $secondFile) && $firstFileValue !== $secondFileValue) {
            $ast[] = ['type' => 'changed', 'name' => $name, 'before' => $firstFileValue, 'after' => $secondFileValue];
            return $ast;
        }
    }, []);
    return $ast;
}
