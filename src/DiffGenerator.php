<?php

namespace GenDiff;

use function GenDiff\Parsers\parseData;
use function GenDiff\Formatters\formatData;
use function Funct\Collection\union;

function genDiff(string $firstFilepath, string $secondFilepath, string $format = 'pretty'): string
{
    $firstFileContent = read($firstFilepath);
    $secondFileContent = read($secondFilepath);

    $firstParserType = pathinfo($firstFilepath, PATHINFO_EXTENSION);
    $secondParserType = pathinfo($secondFilepath, PATHINFO_EXTENSION);

    $firstFileData = parseData($firstFileContent, $firstParserType);
    $secondFileData = parseData($secondFileContent, $secondParserType);

    $tree = generateDiffTree($firstFileData, $secondFileData);
    $diff = formatData($tree, $format);

    return "{$diff}\n";
}

function read(string $filepath): string
{
    $absoluteFilepath = (string) realpath($filepath);
    if (!file_exists($absoluteFilepath)) {
        throw new \Exception("File {$filepath} does not exist!");
    }

    return (string) file_get_contents($absoluteFilepath);
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

function generateDiffTree(array $dataBefore, array $dataAfter): array
{
    $unitedKeys = union(array_keys($dataBefore), array_keys($dataAfter));

    return array_map(function ($key) use ($dataBefore, $dataAfter) {
        if (!array_key_exists($key, $dataBefore)) {
            return [
                'name' => $key,
                'value' => processUnprintableValues($dataAfter[$key]),
                'state' => 'added'
            ];
        }

        if (!array_key_exists($key, $dataAfter)) {
            return [
                'name' => $key,
                'value' => processUnprintableValues($dataBefore[$key]),
                'state' => 'removed'
            ];
        }

        if (
            (isset($dataBefore[$key]) && is_array($dataBefore[$key]))
            && (isset($dataAfter[$key]) && is_array($dataAfter[$key]))
        ) {
            return [
                'name' => $key,
                'state' => 'nested',
                'children' => generateDiffTree($dataBefore[$key], $dataAfter[$key])
            ];
        }

        if ($dataBefore[$key] === $dataAfter[$key]) {
            return [
                'name' => $key,
                'value' => processUnprintableValues($dataBefore[$key]),
                'state' => 'unchanged'
            ];
        }

        return [
            'name' => $key,
            'oldValue' => processUnprintableValues($dataBefore[$key]),
            'newValue' => processUnprintableValues($dataAfter[$key]),
            'state' => 'changed'
        ];
    }, array_values($unitedKeys));
}
