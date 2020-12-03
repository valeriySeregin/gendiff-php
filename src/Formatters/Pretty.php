<?php

namespace GenDiff\Formatters\Pretty;

const INDENT_LENGTH = 4;

function getIndent(int $num): string
{
    return str_repeat(' ', INDENT_LENGTH * $num);
}

/**
 * @param mixed $value
 * @param int $depth
 * @return string
 */
function stringify($value, $depth)
{
    $stringifyComplexValue = function ($value, $depth) {
        $indent = getIndent($depth);
        $complexValue = array_map(function ($key, $value) use ($indent) {
            return "{$indent}    {$key}: {$value}";
        }, array_keys($value), $value);

        return implode("\n", ["{", ...$complexValue, "{$indent}}"]);
    };

    $typeFormats = [
        'string' => fn($value) => $value,
        'integer' => fn($value) => (string) $value,
        'object' => fn($value) => $stringifyComplexValue(get_object_vars($value), $depth + 1),
        'array' => fn($value) => $stringifyComplexValue($value, $depth + 1),
        'boolean' => fn($value) => $value ? "true" : "false",
        'NULL' => fn($value) => 'null'
    ];

    $type = gettype($value);

    return $typeFormats[$type]($value);
}

function generatePrettyOutput(array $tree, int $depth = 0): string
{
    $indent = getIndent($depth);
    $output = array_map(function ($node) use ($depth, $indent) {
        switch ($node['state']) {
            case 'added':
                $formattedValue = stringify($node['value'], $depth);
                return "{$indent}  + {$node['name']}: {$formattedValue}";

            case 'removed':
                $formattedValue = stringify($node['value'], $depth);
                return "{$indent}  - {$node['name']}: {$formattedValue}";

            case 'unchanged':
                $formattedValue = stringify($node['value'], $depth);
                return "{$indent}    {$node['name']}: {$formattedValue}";

            case 'changed':
                $deleted = stringify($node['oldValue'], $depth);
                $added = stringify($node['newValue'], $depth);
                return "{$indent}  - {$node['name']}: {$deleted}\n{$indent}  + {$node['name']}: {$added}";

            case 'nested':
                $prettyOutput = generatePrettyOutput($node['children'], $depth + 1);
                $indent = getIndent($depth + 1);
                return "{$indent}{$node['name']}: {$prettyOutput}";

            default:
                throw new \Exception('Invalid node status!');
        }
    }, $tree);

    return implode("\n", ["{", ...$output, "{$indent}}"]);
}

function render(array $tree): string
{
    return generatePrettyOutput($tree);
}
