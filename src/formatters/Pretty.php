<?php

namespace App\Formatters\Pretty;

function changeInvisibleTypes($value)
{
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_string($value) && strlen($value) === 0) {
        return '';
    }

    return $value;
}

function getStrByStatus($node)
{
    switch ($node['status']) {
        case 'added':
            return "  + {$node['key']}: {$node['value']}";
        case 'removed':
            return "  - {$node['key']}: {$node['value']}";
        case 'unchanged':
            return "    {$node['key']}: {$node['value']}";
        case 'changed':
            return "  - {$node['key']}: {$node['value'][0]}\n  + {$node['key']}: {$node['value'][1]}";
        default:
            throw new \Exception('Invalid node status!');
    }
}

function render($data)
{
    $dataWithChangedBools = array_map(function ($node) {
        if ($node['status'] === 'changed') {
            return [
                'key' => $node['key'],
                'value' => [
                    changeInvisibleTypes($node['value'][0]),
                    changeInvisibleTypes($node['value'][1])
                ],
                'status' => $node['status']
            ];
        }

        return [
            'key' => $node['key'],
            'value' => changeInvisibleTypes($node['value']),
            'status' => $node['status']
        ];
    }, $data);

    $output = array_map(fn($node) => getStrByStatus($node), $dataWithChangedBools);

    $result = ["{", ...$output, "}\n"];

    return implode("\n", $result);
}
