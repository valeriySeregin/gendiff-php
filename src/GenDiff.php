<?php

namespace GenDiff;

use function GenDiff\Parsers\parseData;
use function GenDiff\Formatters\formatData;
use function Funct\Collection\union;

function genDiff(string $firstFilepath, string $secondFilepath, string $format = 'pretty'): string
{
    $diffAst = getDiffAst($firstFilepath, $secondFilepath);
    $diff = formatData($diffAst, $format);

    return "{$diff}\n";
}

function getDiffAst(string $firstFilepath, string $secondFilepath): array
{
    $firstParserType = pathinfo($firstFilepath, PATHINFO_EXTENSION);
    $secondParserType = pathinfo($secondFilepath, PATHINFO_EXTENSION);

    $firstArr = parseData(getFileContents($firstFilepath), $firstParserType);
    $secondArr = parseData(getFileContents($secondFilepath), $secondParserType);

    $ast = generateAst($firstArr, $secondArr);

    return $ast;
}

function getFileContents(string $filepath): string
{
    $absolutePath = (string) realpath($filepath);
    if (!file_exists($absolutePath)) {
        throw new \Exception('This file does not exist!');
    }

    return (string) file_get_contents($filepath);
}

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
    $unitedKeys = union(array_keys($arrBefore), array_keys($arrAfter));

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
    }, array_values($unitedKeys));
}
