<?php

namespace GenDiff\Parsers;

function parseJson(string $data): array
{
    return json_decode($data, true);
}
