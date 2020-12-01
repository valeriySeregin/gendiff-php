<?php

namespace App\Parsers\YamlParser;

use Symfony\Component\Yaml\Yaml;

function parse(string $data): array
{
    return Yaml::parse($data);
}
