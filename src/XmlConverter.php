<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use DOMDocument;
use DOMElement;
use DOMNode;
use EoneoPay\Utils\Exceptions\InvalidXmlException;
use EoneoPay\Utils\Exceptions\InvalidXmlTagException;
use EoneoPay\Utils\Interfaces\XmlConverterInterface;

/**
 * Convert an array to XML with attributes or vice versa
 *
 * Array to XML based on http://www.lalit.org/lab/convert-php-array-to-xml-with-attributes/
 * and updated for PHP 7.1
 */
class XmlConverter implements XmlConverterInterface
{
    /**
     * XML parsing constants
     *
     * @const int
     */
    public const XML_IGNORE_ATTRIBUTES = 0;
    public const XML_INCLUDE_ATTRIBUTES = 1;

    /**
     * XML DOMDocument
     *
     * @var \DOMDocument
     */
    private $xml;

    /**
     * Convert array to XML
     *
     * @param array $array The array to convert
     * @param string|null $rootNode The name of the root node
     *
     * @return string
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException Inherited, if xml contains an invalid tag
     */
    public function arrayToXml(array $array, ?string $rootNode = null): string
    {
        $this->xml = new DOMDocument('1.0', 'UTF-8');
        $this->xml->formatOutput = true;

        // Determine root node
        $rootNode = $rootNode ?? $array['@rootNode'] ?? 'data';

        if (!$this->isValidXmlTag($rootNode)) {
            throw new InvalidXmlTagException(\sprintf('RootNode %s is not a valid xml tag', $rootNode));
        }

        $this->xml->appendChild($this->createXmlElement($rootNode, $array));

        return $this->xml->saveXML();
    }

    /**
     * Convert xml to an array with attributes
     *
     * @param string $xml The xml to convert
     * @param int|null $options Additional xml parsing options
     *
     * @return array
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException If the XML is invalid and can't be loaded
     */
    public function xmlToArray(string $xml, ?int $options = null): array
    {
        // Prevent errors on xml load
        \libxml_use_internal_errors(true);

        // Test if xml empty, DOMDocument will throw an exception which can't be internalised
        if ($xml === '') {
            throw new InvalidXmlException('XML can not be converted: empty string given');
        }

        // Load XML
        $document = new DOMDocument();
        $parsed = $document->loadXML($xml);

        // Test xml validity
        if ($parsed === false) {
            throw new InvalidXmlException('XML can not be converted: invalid or contains invalid tag');
        }

        // Convert node to array
        return $this->documentToArray($document, $options);
    }

    /**
     * Append an attribute from a mixed value to a XML node
     *
     * @param \DOMElement $node The node to add the value to
     * @param string $name The node name to add
     * @param bool|int|string $value The value to attach to the node
     *
     * @return \DOMElement
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException Inherited, if xml contains an invalid tag
     */
    private function appendXmlAttribute(DOMElement $node, string $name, $value): DOMElement
    {
        // Add value and return
        $node->appendChild($this->createXmlElement($name, $value));

        return $node;
    }

    /**
     * Append an attribute from an array to a XML node
     *
     * @param \DOMElement $node The node to add the value to
     * @param string $name The node name to add
     * @param array $values The value to attach to the node
     *
     * @return \DOMElement
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException Inherited, if xml contains an invalid tag
     */
    private function appendXmlAttributeArray(DOMElement $node, string $name, array $values): DOMElement
    {
        foreach ($values as $value) {
            $node->appendChild($this->createXmlElement($name, $value));
        }

        return $node;
    }

    /**
     * Create an XML node from an array, recursively
     *
     * @param string $name The name of the node to convert this array to
     * @param mixed $value The value to add, can be array or scalar value
     *
     * @return \DOMElement
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException Inherited, if xml contains an invalid tag
     */
    private function createXmlElement(string $name, $value): DOMElement
    {
        // If value is an array, attempt to process attributes and values
        if (\is_array($value)) {
            return $this->processNodeValuesArray($name, $value);
        }

        // If node isn't an array, add it directly
        $node = $this->xml->createElement($name);
        $node->appendChild($this->createXmlNode($value));

        return $node;
    }

    /**
     * Create the correct xml correct for a value
     *
     * @param mixed $value The value to create a child from
     *
     * @return \DOMNode
     */
    private function createXmlNode($value): DOMNode
    {
        $value = $this->xToString($value);

        // If value contains characters that need to be escaped use CDATA
        return \strpbrk($value, '\'"<>&') ? $this->xml->createCDATASection($value) : $this->xml->createTextNode($value);
    }

    /**
     * Recursively convert a DOMDocument to array
     *
     * @param \DOMDocument $document The document to convert
     * @param int|null $options Additional xml parsing options
     *
     * @return array
     */
    private function documentToArray(DOMDocument $document, ?int $options = null): array
    {
        // Get document element
        $element = $document->documentElement;

        $array = ['element' => $this->domElementToArray($element)];

        // The DOMElement array will come back with @value and @attributes tags as well as every
        // element contained within it's own array, post process the array to flatten single value
        // elements and alter based on options
        $array = $this->postProcessDomElementArray($array, $options ?? self::XML_IGNORE_ATTRIBUTES);

        // Avoid ErrorException for illegal string offset when only one empty node is provided
        if (!\is_array($array['element'])) {
            $array['element'] = [];
        }

        // Preserve root tag
        $array['element']['@rootNode'] = $element->tagName;

        return $array['element'];
    }

    /**
     * Recursively convert a DOMElement to an array
     *
     * @param \DOMElement $element The element to convert
     *
     * @return array
     */
    private function domElementToArray(DOMElement $element): array
    {
        $array = [];

        /** @var \DOMElement $childElement */
        foreach ($element->childNodes as $childElement) {
            // Convert node based on type
            switch ($childElement->nodeType) {
                // For plain text, return string
                case XML_CDATA_SECTION_NODE:
                case XML_TEXT_NODE:
                    if (\trim($childElement->textContent) !== '') {
                        $array['@value'] = $this->stringToX(\trim($childElement->textContent));
                    }
                    break;

                case XML_ELEMENT_NODE:
                    // Convert element to array recursively
                    $array[$childElement->tagName][] = $this->domElementToArray($childElement);
                    break;
            }
        }

        // If there are no child nodes because the element is empty or self-closing, add empty string
        if ($element->childNodes->length === 0) {
            $array['@value'] = '';
        }

        // If attributes exist, add to array
        if ($element->attributes->length) {
            $array['@attributes'] = [];

            /**
             * @var string $name
             * @var \DOMAttr $attribute
             */
            foreach ($element->attributes as $name => $attribute) {
                $array['@attributes'][$name] = $this->stringToX($attribute->value);
            }
        }

        return $array;
    }

    /**
     * Ensure the node name or attribute only contains valid characters
     *
     * @param string $name The name to validate
     *
     * @return bool
     */
    private function isValidXmlTag(string $name): bool
    {
        $pattern = '/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i';

        return \preg_match($pattern, $name, $matches) && \reset($matches) === $name;
    }

    /**
     * Post-process a converted DOMNode array and flatten based on options
     *
     * @param array $array The array to process
     * @param int $options Additional xml parsing options
     *
     * @return array
     */
    private function postProcessDomElementArray(array $array, int $options): array
    {
        foreach ($array as $key => $value) {
            // Skip non-array values
            if (!\is_array($value)) {
                continue;
            }

            // If value only contains 1 item, flatten
            $value = \count($value) === 1 && array_key_exists(0, $value) ? $value[0] : $value;

            // If attributes are ignored, process value remove @attributes and further flatten @values tags
            if ($options === self::XML_IGNORE_ATTRIBUTES) {
                unset($value['@attributes']);
                $value = \count($value) === 1 ? $value['@value'] ?? $value : $value;
            }

            // If value is still an array, recurse
            $array[$key] = \is_array($value) ? $this->postProcessDomElementArray($value, $options) : $value;
        }

        return $array;
    }

    /**
     * Process an array of node attributes
     *
     * @param \DOMElement $node The node to add attributes to
     * @param string $name The node name
     * @param array $attributes The attributes to set on the node
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException If the xml is invalid or contains invalid tag
     */
    private function processNodeAttributes(DOMElement $node, string $name, array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            // Ensure the attribute key is valid
            if (!$this->isValidXmlTag($key)) {
                $message = \sprintf('Attribute name is invalid for "%s" in node "%s"', $key, $name);
                throw new InvalidXmlTagException($message);
            }

            $node->setAttribute($key, $this->xToString($value));
        }
    }

    /**
     * Create an XML node from an array of node values
     *
     * @param string $name The node name
     * @param array $values
     *
     * @return \DOMElement
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException If the xml is invalid or contains invalid tag
     */
    private function processNodeValuesArray(string $name, array $values): DOMElement
    {
        // Create node
        $node = $this->xml->createElement($name);

        // Ignore root node
        unset($values['@rootNode']);

        // Process attributes
        if (isset($values['@attributes']) && \is_array($values['@attributes'])) {
            $this->processNodeAttributes($node, $name, $values['@attributes']);

            // Remove attributes array
            unset($values['@attributes']);
        }

        // Set values directly
        if (isset($values['@value'])) {
            $node->appendChild($this->createXmlNode($values['@value']));

            // Remove value from array
            unset($values['@value']);

            // If there was a value, there is no recursion
            return $node;
        }

        foreach ($values as $key => $value) {
            // Ensure node name is valid
            if (!$this->isValidXmlTag($key)) {
                throw new InvalidXmlTagException(\sprintf('Node name is invalid for "%s" in node "%s"', $key, $name));
            }

            // Process node
            $node = \is_array($value) && \is_numeric(\key($value)) ?
                $this->appendXmlAttributeArray($node, $key, $value) :
                $this->appendXmlAttribute($node, $key, $value);

            // Remove array key to prevent double processing
            unset($values[$key]);
        }

        // Nothing further to process, return
        return $node;
    }

    /**
     * Convert a value to a string
     *
     * @param mixed $value The value to convert
     *
     * @return string
     */
    private function xToString($value): string
    {
        // Convert booleans to string true/false as (string) converts to 1/0
        if (\is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        // Cast to string
        return (string)$value;
    }

    /**
     * Convert boolean strings to boolean
     *
     * @param string $value The value to convert
     *
     * @return bool|string
     */
    private function stringToX(string $value)
    {
        // Convert booleans to boolean type
        if (\in_array(\mb_strtolower($value), ['true', 'false'], true)) {
            return \mb_strtolower($value) === 'true';
        }

        // Leave as is
        return $value;
    }
}
