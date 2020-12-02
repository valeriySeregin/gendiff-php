<?php

namespace App\Formatters;

use function App\Formatters\Pretty\render as renderInPretty;
use function App\Formatters\Plain\render as renderInPlain;
use function App\Formatters\Json\render as renderInJson;

function formatData(array $data, string $format): string
{
    $formatters = [
        'pretty' => fn($data) => renderInPretty($data),
        'plain' => fn($data) => renderInPlain($data),
        'json' => fn($data) => renderInJson($data)
    ];

    if (!array_key_exists($format, $formatters)) {
        throw new \Exception("Unsupported format: {$format}");
    }

    return $formatters[$format]($data);
}
