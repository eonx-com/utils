<?php declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Exceptions\InvalidXmlException;
use EoneoPay\Utils\Exceptions\InvalidXmlTagException;
use EoneoPay\Utils\XmlConverter;

/**
 * @covers \EoneoPay\Utils\XmlConverter
 */
class XmlConverterTest extends TestCase
{
    /**
     * Array of test data to create an XML from, without attributes
     *
     * @var array
     */
    private static $array = [
        'customers' => [
            [
                'name' => 'John Smith',
                'card' => '4242424242424242'
            ],
            [
                'name' => 'Jane Burns',
                'card' => '5353535353535353',
                'notes' => 'Test note ><'
            ],
            [
                'name' => 'Bob Martin',
                'card' => '4111111111111111',
                'disabled' => true
            ]
        ],
        '@rootNode' => 'Message'
    ];

    /**
     * Array of test data to create an XML from, with attributes
     *
     * @var array
     */
    private static $attributeArray = [
        'customers' => [
            [
                'name' => [
                    '@value' => 'John Smith'
                ],
                'card' => [
                    '@value' => '4242424242424242'
                ],
                '@attributes' => [
                    'id' => '1234'
                ]
            ],
            [
                'name' => [
                    '@value' => 'Jane Burns',
                    '@attributes' => [
                        'version' => '2'
                    ]
                ],
                'card' => [
                    '@value' => '5353535353535353'
                ],
                'notes' => [
                    '@value' => 'Test note ><'
                ],
                '@attributes' => [
                    'id' => '7890'
                ]
            ],
            [
                'name' => [
                    '@value' => 'Bob Martin'
                ],
                'card' => [
                    '@value' => '4111111111111111'
                ],
                'disabled' => [
                    '@value' => true
                ],
                '@attributes' => [
                    'id' => '4353'
                ]
            ]
        ],
        '@attributes' => [
            'action' => '1'
        ],
        '@rootNode' => 'Message'
    ];

    /**
     * The expected XML response with attributes
     *
     * @var string
     */
    private static $attributeXml = '<?xml version="1.0" encoding="UTF-8"?>
<Message action="1">
  <customers id="1234">
    <name>John Smith</name>
    <card>4242424242424242</card>
  </customers>
  <customers id="7890">
    <name version="2">Jane Burns</name>
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
     * The expected XML response without attributes
     *
     * @var string
     */
    private static $xml = '<?xml version="1.0" encoding="UTF-8"?>
<Message>
  <customers>
    <name>John Smith</name>
    <card>4242424242424242</card>
  </customers>
  <customers>
    <name>Jane Burns</name>
    <card>5353535353535353</card>
    <notes><![CDATA[Test note ><]]></notes>
  </customers>
  <customers>
    <name>Bob Martin</name>
    <card>4111111111111111</card>
    <disabled>true</disabled>
  </customers>
</Message>
'; // The trailing line break here is important

    /**
     * Test empty xml return a null
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
     */
    public function testEmptyXmlGivenException(): void
    {
        $this->expectException(InvalidXmlException::class);

        // This should throw exception as XML can not be an empty string
        (new XmlConverter())->xmlToArray('');
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

        self::assertSame(['customers' => '', '@rootNode' => 'Message'], (new XmlConverter())->xmlToArray($xml));
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

        // This should throw exception as XML is invalid
        (new XmlConverter())->xmlToArray('<@attribute>');
    }

    /**
     * Test converting XML to an array with attributes
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
     */
    public function testXmlToArrayConversionWithAttributes(): void
    {
        self::assertSame(
            self::$attributeArray,
            (new XmlConverter())->xmlToArray(self::$attributeXml, XmlConverter::XML_INCLUDE_ATTRIBUTES)
        );
    }

    /**
     * Test converting XML to an array without attributes
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException
     */
    public function testXmlToArrayConversionWithoutAttributes(): void
    {
        self::assertSame(self::$array, (new XmlConverter())->xmlToArray(self::$xml));
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

        // This should return null as @invalid is not XML compliant
        (new XmlConverter())->arrayToXml(['customers' => ['@attributes' => ['@invalid' => '']]]);
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

        // This should return null as @invalid is not XML compliant
        (new XmlConverter())->arrayToXml(['customers' => ['@invalid' => '']]);
    }

    /**
     * Test converting an array without attributes to xml
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function testarrayToXmlConversion(): void
    {
        self::assertSame(
            self::$xml,
            (new XmlConverter())->arrayToXml(self::$array, 'Message')
        );
    }

    /**
     * Test converting an array with attributes to xml
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function testattributeArrayToXmlConversion(): void
    {
        self::assertSame(
            self::$attributeXml,
            (new XmlConverter())->arrayToXml(self::$attributeArray, 'Message')
        );
    }
}
