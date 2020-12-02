<?php

namespace Php\Package\Tests;

use PHPUnit\Framework\TestCase;

use function App\getDiff;

class GenDiffTest extends TestCase
{
    public function testGetDiff(): void
    {
        $diffForPrettyFormat = getDiff(__DIR__ . '/fixtures/before.json', __DIR__ . '/fixtures/after.json', 'pretty');
        $diffForPlainFormat = getDiff(__DIR__ . '/fixtures/before.yaml', __DIR__ . '/fixtures/after.yaml', 'plain');
        $diffForJsonFormat = getDiff(__DIR__ . '/fixtures/before.json', __DIR__ . '/fixtures/after.json', 'json');

        $expectedPretty = file_get_contents(__DIR__ . '/fixtures/expectedPretty.txt');
        $expectedPlain = file_get_contents(__DIR__ . '/fixtures/expectedPlain.txt');
        $expectedJson = file_get_contents(__DIR__ . '/fixtures/expectedJson.txt');

        $this->assertEquals($diffForPrettyFormat, $expectedPretty);
        $this->assertEquals($diffForPlainFormat, $expectedPlain);
        $this->assertEquals($diffForJsonFormat, $expectedJson);
    }
}
