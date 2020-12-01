<?php

namespace App\Ast;

/**
 * @param mixed $value
 * @return mixed
 */
function processUnprintableValues($value)
{
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    return $value;
}

function generateAst(array $arrBefore, array $arrAfter): array
{
    $unitedKeys = array_keys(array_merge($arrBefore, $arrAfter));

    return array_map(function ($key) use ($arrBefore, $arrAfter) {
        if (!array_key_exists($key, $arrBefore)) {
            return [
                'name' => $key,
                'value' => processUnprintableValues($arrAfter[$key]),
                'status' => 'added'
            ];
        }

        if (!array_key_exists($key, $arrAfter)) {
            return [
                'name' => $key,
                'value' => processUnprintableValues($arrBefore[$key]),
                'status' => 'removed'
            ];
        }

        if (
            (isset($arrBefore[$key]) && is_array($arrBefore[$key]))
            && (isset($arrAfter[$key]) && is_array($arrAfter[$key]))
        ) {
            return [
                'name' => $key,
                'status' => 'nested',
                'children' => generateAst($arrBefore[$key], $arrAfter[$key])
            ];
        }

        if ($arrBefore[$key] === $arrAfter[$key]) {
            return [
                'name' => $key,
                'value' => processUnprintableValues($arrBefore[$key]),
                'status' => 'unchanged'
            ];
        }

        return [
            'name' => $key,
            'oldValue' => processUnprintableValues($arrBefore[$key]),
            'newValue' => processUnprintableValues($arrAfter[$key]),
            'status' => 'changed'
        ];
    }, $unitedKeys);
}
