<?php

namespace Php\Package\Tests;

use PHPUnit\Framework\TestCase;

use function App\run;

class GenDiffTest extends TestCase
{
    public function testGetName(): void
    {
        $args = [
            '<path/to/file1>' => __DIR__ . '/fixtures/before.json',
            '<path/to/file2>' => __DIR__ . '/fixtures/after.json'
        ];
        $diff = run($args);
        $expected = file_get_contents(__DIR__ . '/fixtures/diff.txt');

        $this->assertEquals($diff, $expected);
    }
}
