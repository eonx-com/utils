<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Str;

use EoneoPay\Utils\Str;
use Tests\EoneoPay\Utils\TestCases\StrTestCase;

class StartsWithTest extends StrTestCase
{
    /**
     * @var string
     */
    private static $input = 'Namespace\\Of\\Objects\\';

    /**
     * @var mixed[]
     */
    private static $tests = [
        'Name' => true,
        'Namespace\\Of\\' => true,
        'Something' => false
    ];

    /**
     * Test studly function returns right formatted string.
     *
     * @return void
     */
    public function testStudly(): void
    {
        foreach (static::$tests as $needles => $expected) {
            self::assertEquals($expected, (new Str())->startsWith(static::$input, $needles));
        }
    }
}
