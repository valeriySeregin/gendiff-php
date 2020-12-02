<?php

namespace GenDiff;

use function GenDiff\Parsers\parseData;
use function GenDiff\Ast\generateAst;
use function GenDiff\Formatters\formatData;

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
