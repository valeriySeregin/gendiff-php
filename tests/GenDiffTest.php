<?php

namespace Php\Package\Tests;

use PHPUnit\Framework\TestCase;

use function App\getDiff;

class GenDiffTest extends TestCase
{
    public function testGetDiff(): void
    {
        $argsWithJsonExt = [
            '<path/to/file1>' => __DIR__ . '/fixtures/before.json',
            '<path/to/file2>' => __DIR__ . '/fixtures/after.json'
        ];

        $argsWithYamlExt = [
            '<path/to/file1>' => __DIR__ . '/fixtures/before.yaml',
            '<path/to/file2>' => __DIR__ . '/fixtures/after.yaml'
        ];

        $diffForJsonExt = getDiff($argsWithJsonExt);
        $diffForYamlExt = getDiff($argsWithYamlExt);

        $expected = file_get_contents(__DIR__ . '/fixtures/diff.txt');

        $this->assertEquals($diffForJsonExt, $expected);
        $this->assertEquals($diffForYamlExt, $expected);
    }
}
