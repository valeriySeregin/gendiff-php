<?php

namespace App\Parsers\JsonParser;

function parse(string $data): array
{
    return json_decode($data, true);
}
