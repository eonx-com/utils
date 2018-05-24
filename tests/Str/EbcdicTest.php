<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Str;

use Tests\EoneoPay\Utils\TestCases\StrTestCase;

class EbcdicTest extends StrTestCase
{
    /**
     * Tests to run to check the method works as expected
     *
     * @var string[]
     */
    private static $tests = [
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_+@ !^$%&'()*-:;=?.#,[]/" =>
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_+@ !^$%&'()*-:;=?.#,[]/",
        '{ABC}' => 'ABC'
    ];

    /**
     * Test ebcdic function returns right formatted string.
     *
     * @return void
     */
    public function testEbcdic(): void
    {
        $this->processSimpleTests('ebcdic', static::$tests);
    }
}
