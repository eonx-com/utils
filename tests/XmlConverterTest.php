<?php declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Exceptions\InvalidXmlException;
use EoneoPay\Utils\Exceptions\InvalidXmlTagException;
use EoneoPay\Utils\XmlConverter;
use Tests\EoneoPay\Utils\TestCase;

/**
 * @covers \EoneoPay\Utils\XmlConverter
 */
class XmlConverterTest extends TestCase
{
    /**
     * Array of test data to create an XML from
     *
     * @var array
     */
    private $array = [
        '@attributes' => [
            'action' => '1'
        ],
        'customers' => [
            [
                '@attributes' => [
                    'id' => '1234'
                ],
                'name' => 'John Smith',
                'card' => '4242424242424242'
            ],
            [
                '@attributes' => [
                    'id' => '7890'
                ],
                'name' => 'Jane Burns',
                'card' => '5353535353535353',
                'notes' => [
                    '@cdata' => 'Test note ><'
                ]
            ],
            [
                '@attributes' => [
                    'id' => '4353'
                ],
                'name' => 'Bob Martin',
                'card' => [
                    '@value' => '4111111111111111'
                ],
                'disabled' => true
            ]
        ]
    ];

    /**
     * The expected XML response
     *
     * @var string
     */
    private $xml = '<?xml version="1.0" encoding="UTF-8"?>
<Message action="1">
  <customers id="1234">
    <name>John Smith</name>
    <card>4242424242424242</card>
  </customers>
  <customers id="7890">
    <name>Jane Burns</name>
    <card>5353535353535353</card>
    <notes><![CDATA[Test note ><]]></notes>
  </customers>
  <customers id="4353">
    <name>Bob Martin</name>
    <card>4111111111111111</card>
    <disabled>true</disabled>
  </customers>
</Message>
'; // The trailing line break here is important

    /**
     * Test converting an xml to array
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function testArrayToXmlConversion(): void
    {
        self::assertSame((new XmlConverter())->arrayToXml($this->array, 'Message'), $this->xml);
    }

    /**
     * Test empty xml tags/self closed tags are preserved when converting xml to array
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
     */
    public function testEmptyXmlTagsArePreservedWhenConvertingToArray(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><Message><customers/></Message>';

        self::assertSame(['customers' => ''], (new XmlConverter())->xmlToArray($xml));
    }

    /**
     * Test invalid xml return a null
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
     */
    public function testInvalidXmlReturnsNull(): void
    {
        $this->expectException(InvalidXmlException::class);

        // This should throw exception as XML can not be an empty string
        (new XmlConverter())->xmlToArray('');
    }

    /**
     * Test converting XML to an array
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
     */
    public function testXmlToArrayConversion(): void
    {
        // Rendered arrays don't contain @value or @cdata tags and all values are strings, so updated expectation
        $array = $this->array;
        $array['customers'][1]['notes'] = 'Test note ><';
        $array['customers'][2]['card'] = '4111111111111111';
        $array['customers'][2]['disabled'] = 'true';

        self::assertSame($array, (new XmlConverter())->xmlToArray($this->xml));
    }

    /**
     * Test invalid attributes return a null
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function testXmlWithInvalidAttributesReturnsNull(): void
    {
        $this->expectException(InvalidXmlTagException::class);

        $array = $this->array;
        $array['customers'][1]['@attributes']['@invalid'] = '';

        // This should return null as @invalid is not XML compliant
        (new XmlConverter())->arrayToXml($array);
    }

    /**
     * Test invalid tags return a null
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function testXmlWithInvalidTagsReturnsNull(): void
    {
        $this->expectException(InvalidXmlTagException::class);

        $array = $this->array;
        $array['customers'][1]['@invalid'] = '';

        // This should return null as @invalid is not XML compliant
        (new XmlConverter())->arrayToXml($array);
    }
}
