<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Str;

use Tests\EoneoPay\Utils\TestCases\StrTestCase;

class StudlyTest extends StrTestCase
{
    /**
     * @var string[]
     */
    private static $tests = [
        'studly_name' => 'StudlyName',
        'studly name' => 'StudlyName',
        'studlyname' => 'Studlyname',
        'STUDLY NAME' => 'STUDLYNAME'
    ];

    /**
     * Test studly function returns right formatted string.
     *
     * @return void
     */
    public function testStudly(): void
    {
        $this->processSimpleTests('studly', static::$tests);
    }
}
