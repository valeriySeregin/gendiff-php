<?php

namespace GenDiff\Formatters;

use function GenDiff\Formatters\Pretty\render as renderInPretty;
use function GenDiff\Formatters\Plain\render as renderInPlain;
use function GenDiff\Formatters\Json\render as renderInJson;

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
