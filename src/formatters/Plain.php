<?php

namespace App\Formatters\Plain;

/**
 * @param mixed $value
 * @return string
 */
function stringify($value)
{
    return is_array($value) ? '[complex value]' : $value;
}

function getStrByStatus(array $node, array $propertyNames): string
{
    $name = implode('.', $propertyNames);

    switch ($node['status']) {
        case 'added':
            $value = stringify($node['value']);
            return "Property '{$name}' was added with value: '{$value}'";
        case 'removed':
            return "Property '{$name}' was removed";
        case 'unchanged':
            return "Property '{$name}' was not changed";
        case 'changed':
            $oldValue = stringify($node['oldValue']);
            $newValue = stringify($node['newValue']);
            return "Property '{$name}' was updated. From '{$oldValue}' to '{$newValue}'";
        default:
            throw new \Exception('Invalid node status!');
    }
}

function render(array $data, array $propertyNames = []): string
{
    $output = array_map(function ($node) use ($propertyNames) {
        if ($node['status'] === 'nested') {
            return render($node['children'], [...$propertyNames, $node['name']]);
        }

        return getStrByStatus($node, [...$propertyNames, $node['name']]);
    }, $data);

    return implode("\n", $output);
}
