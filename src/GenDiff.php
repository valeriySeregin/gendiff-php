<?php

namespace App;

use function App\Parsers\JsonParser\parse as parseJson;
use function App\Parsers\YamlParser\parse as parseYaml;
use function App\Ast\generateAst;
use function App\Formatters\Pretty\render as renderInPretty;

const PATH_TO_FIRST_FILE = '<path/to/file1>';
const PATH_TO_SECOND_FILE = '<path/to/file2>';
const FORMAT = '--format';

function getDiff($args)
{
    $firstFilepath = $args[PATH_TO_FIRST_FILE];
    $secondFilepath = $args[PATH_TO_SECOND_FILE];

    $diffAst = getDiffAst($firstFilepath, $secondFilepath);

    $formatters = [
        'pretty' => fn($data) => renderInPretty($data)
    ];

    $render = $formatters[$args[FORMAT]];

    $diff = $render($diffAst);

    return $diff;
}

function getDiffAst($firstFilepath, $secondFilepath)
{
    $parsers = [
        'json' => fn($json) => parseJson($json),
        'yaml' => fn($yaml) => parseYaml($yaml),
        'yml' => fn($yaml) => parseYaml($yaml)
    ];

    $firstFileExt = getFileExtension($firstFilepath);
    $secondFileExt = getFileExtension($secondFilepath);

    $firstArr = $parsers[$firstFileExt](getFileContents($firstFilepath));
    $secondArr = $parsers[$secondFileExt](getFileContents($secondFilepath));

    $ast = generateAst($firstArr, $secondArr);

    return $ast;
}

function getFileContents($filepath)
{
    $absolutePath = realpath($filepath);
    if (!file_exists($absolutePath)) {
        throw new \Exception('This file does not exist!');
    }

    return file_get_contents($filepath);
}

function getFileExtension($filepath)
{
    $pathParts = pathinfo($filepath);

    return $pathParts['extension'];
}
