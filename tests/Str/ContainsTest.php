<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Str;

use EoneoPay\Utils\Str;
use Tests\EoneoPay\Utils\TestCases\StrTestCase;

class ContainsTest extends StrTestCase
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
     * Test contains function returns right formatted string.
     *
     * @return void
     */
    public function testContains(): void
    {
        foreach (static::$tests as $needles => $expected) {
            self::assertEquals($expected, (new Str())->contains(static::$input, $needles));
        }
    }
}
