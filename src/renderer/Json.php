<?php

namespace Differ\Renderer\Json;

function genJsonDiff($ast)
{
    return json_encode($ast);
}