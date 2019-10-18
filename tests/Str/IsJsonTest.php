<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Str;

use Tests\EoneoPay\Utils\TestCases\StrTestCase;

class IsJsonTest extends StrTestCase
{
    /**
     * @var mixed[]
     */
    private static $tests = [
        '{}' => true,
        '{"test": "value"}' => true,
        'string' => false
    ];

    /**
     * Test studly function returns right formatted string.
     *
     * @return void
     */
    public function testIsJson(): void
    {
        $this->processSimpleTests('isJson', static::$tests);
    }
}
