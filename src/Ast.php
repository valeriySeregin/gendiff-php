<?php

namespace App\Ast;

function processsUnprintableValues($value)
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

function generateAst($arrBefore, $arrAfter)
{
    $unitedKeys = array_keys(array_merge($arrBefore, $arrAfter));

    return array_map(function ($key) use ($arrBefore, $arrAfter) {
        if (!array_key_exists($key, $arrBefore)) {
            return [
                'name' => $key,
                'value' => processsUnprintableValues($arrAfter[$key]),
                'status' => 'added'
            ];
        }

        if (!array_key_exists($key, $arrAfter)) {
            return [
                'name' => $key,
                'value' => processsUnprintableValues($arrBefore[$key]),
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
                'value' => processsUnprintableValues($arrBefore[$key]),
                'status' => 'unchanged'
            ];
        }

        return [
            'name' => $key,
            'oldValue' => processsUnprintableValues($arrBefore[$key]),
            'newValue' => processsUnprintableValues($arrAfter[$key]),
            'status' => 'changed'
        ];
    }, $unitedKeys);
}
