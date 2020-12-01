<?php

namespace Php\Package\Tests;

use PHPUnit\Framework\TestCase;

use function App\getDiff;

class GenDiffTest extends TestCase
{
    public function testGetDiff(): void
    {
        $argsWithPrettyFormat = [
            '<path/to/file1>' => __DIR__ . '/fixtures/before.json',
            '<path/to/file2>' => __DIR__ . '/fixtures/after.json',
            '--format' => 'pretty'
        ];

        $argsWithPlainFormat = [
            '<path/to/file1>' => __DIR__ . '/fixtures/before.yaml',
            '<path/to/file2>' => __DIR__ . '/fixtures/after.yaml',
            '--format' => 'plain'
        ];

        $argsWithJsonFormat = [
            '<path/to/file1>' => __DIR__ . '/fixtures/before.yaml',
            '<path/to/file2>' => __DIR__ . '/fixtures/after.json',
            '--format' => 'json'
        ];

        $diffForPrettyFormat = getDiff($argsWithPrettyFormat);
        $diffForPlainFormat = getDiff($argsWithPlainFormat);
        $diffForJsonFormat = getDiff($argsWithJsonFormat);

        $expectedPretty = file_get_contents(__DIR__ . '/fixtures/expectedPretty.txt');
        $expectedPlain = file_get_contents(__DIR__ . '/fixtures/expectedPlain.txt');
        $expectedJson = file_get_contents(__DIR__ . '/fixtures/expectedJson.txt');

        $this->assertEquals($diffForPrettyFormat, $expectedPretty);
        $this->assertEquals($diffForPlainFormat, $expectedPlain);
        $this->assertEquals($diffForJsonFormat, $expectedJson);
    }
}
