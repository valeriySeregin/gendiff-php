<?php

namespace GenDiff\Formatters\Pretty;

function getIndent(int $num): string
{
    return str_repeat('    ', $num);
}

/**
 * @param mixed $value
 * @param int $depth
 * @return string
 */
function stringify($value, $depth)
{
    if (!is_array($value)) {
        return $value;
    }

    $stringifiedItems = array_map(function ($key, $value) use ($depth) {
        if (is_array($value)) {
            return stringify($value, $depth + 1);
        }
        $indent = getIndent($depth + 2);

        return "{$indent}{$key}: {$value}";
    }, array_keys($value), $value);

    $indent = fn($num) => str_repeat('    ', $num);

    $result = ["{", ...$stringifiedItems, "{$indent($depth + 1)}}"];

    return implode("\n", $result);
}

function getStrByStatus(array $node, int $depth): string
{
    $indent = getIndent($depth);

    $value = '';
    $deleted = '';
    $added = '';

    if ($node['state'] === 'changed') {
        $deleted = $node['oldValue'];
        $added = $node['newValue'];

        if (is_array($deleted)) {
            $deleted = stringify($deleted, $depth);
        }

        if (is_array($added)) {
            $added = stringify($added, $depth);
        }
    } else {
        $value = $node['value'];
        if (is_array($value)) {
            $value = stringify($value, $depth);
        }
    }

    switch ($node['state']) {
        case 'added':
            return "{$indent}  + {$node['name']}: {$value}";
        case 'removed':
            return "{$indent}  - {$node['name']}: {$value}";
        case 'unchanged':
            return "{$indent}    {$node['name']}: {$value}";
        case 'changed':
            return "{$indent}  - {$node['name']}: {$deleted}\n{$indent}  + {$node['name']}: {$added}";
        default:
            throw new \Exception('Invalid node status!');
    }
}

/**
 * @param array $data
 * @param int $depth
 * @return string
 */
function render($data, $depth = 0)
{
    $indent = getIndent($depth);

    $output = array_map(function ($node) use ($depth) {
        if ($node['state'] === 'nested') {
            $stringifiedArr = render($node['children'], $depth + 1);
            $indent = fn($num) => str_repeat('    ', $num);
            return "{$indent($depth + 1)}{$node['name']}: {$stringifiedArr}";
        }

        return getStrByStatus($node, $depth);
    }, $data);

    $result = ["{", ...$output, "{$indent}}"];

    return implode("\n", $result);
}
