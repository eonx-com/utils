<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Str;

use Tests\EoneoPay\Utils\TestCases\StrTestCase;

class IsXmlTest extends StrTestCase
{
    /**
     * @var mixed[]
     */
    private static $tests = [
        '<?xml version="1.0" encoding="UTF-8"?><data><test>value</test></data>' => true,
        '<?xml version="1.0" encoding="UTF-8"?><data/>' => true,
        'string' => false
    ];

    /**
     * Test isXml correctly detects xml.
     *
     * @return void
     */
    public function testIsXml(): void
    {
        $this->processSimpleTests('isXml', static::$tests);
    }
}
