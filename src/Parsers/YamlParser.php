<?php

namespace GenDiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseYaml(string $data): array
{
    return Yaml::parse($data);
}
