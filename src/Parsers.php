<?php

namespace App\Parsers;

use function App\Parsers\parseJson;
use function App\Parsers\parseYaml;

function parseData(string $data, string $parserType): array
{
    $parsers = [
        'json' => fn($data) => parseJson($data),
        'yaml' => fn($data) => parseYaml($data),
        'yml' => fn($data) => parseYaml($data)
    ];

    if (!array_key_exists($parserType, $parsers)) {
        throw new \Exception("Unsupported parser type: {$parserType}");
    }

    return $parsers[$parserType]($data);
}
