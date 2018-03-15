<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\UtcDateTime;

class UtcDateTimeTest extends TestCase
{

    /**
     * Test zulu format datetime string generation
     *
     * @return void
     */
    public function testGetZulu(): void
    {
        $utcDateTime = new UtcDateTime('2018-03-20 00:00:00');
        self::assertEquals('2018-03-20T00:00:00Z', $utcDateTime->getZulu());
    }
}
