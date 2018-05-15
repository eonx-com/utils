<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\TestCases;

use EoneoPay\Utils\Str;
use Tests\EoneoPay\Utils\TestCase;

abstract class StrTestCase extends TestCase
{
    /**
     * Process simple tests on Str.
     *
     * @param string $method
     * @param mixed[] $tests
     *
     * @return void
     */
    protected function processSimpleTests(string $method, array $tests): void
    {
        foreach ($tests as $input => $expected) {
            self::assertEquals($expected, (new Str())->$method($input));
        }
    }
}
