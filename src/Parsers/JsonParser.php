<?php

namespace App\Parsers;

function parseJson(string $data): array
{
    return json_decode($data, true);
}
