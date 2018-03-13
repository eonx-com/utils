<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Str;

use Tests\EoneoPay\Utils\TestCases\StrTestCase;

class CamelTest extends StrTestCase
{
    /**
     * @var array
     */
    private static $tests = [
        'studly_name' => 'studlyName',
        'studly name' => 'studlyName',
        'studlyname' => 'studlyname',
        'STUDLY NAME' => 'sTUDLYNAME'
    ];

    /**
     * Test studly function returns right formatted string.
     *
     * @return void
     */
    public function testStudly(): void
    {
        $this->processSimpleTests('camel', static::$tests);
    }
}
