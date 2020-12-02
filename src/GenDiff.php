<?php

namespace App;

use function App\Parsers\parseData;
use function App\Ast\generateAst;
use function App\Formatters\Pretty\render as renderInPretty;
use function App\Formatters\Plain\render as renderInPlain;
use function App\Formatters\Json\render as renderInJson;

const PATH_TO_FIRST_FILE = '<path/to/file1>';
const PATH_TO_SECOND_FILE = '<path/to/file2>';
const FORMAT = '--format';

function getDiff(array $args): string
{
    $firstFilepath = $args[PATH_TO_FIRST_FILE];
    $secondFilepath = $args[PATH_TO_SECOND_FILE];

    $diffAst = getDiffAst($firstFilepath, $secondFilepath);

    $formatters = [
        'pretty' => fn($data) => renderInPretty($data),
        'plain' => fn($data) => renderInPlain($data),
        'json' => fn($data) => renderInJson($data)
    ];

    $render = $formatters[$args[FORMAT]];

    $diff = $render($diffAst);

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
