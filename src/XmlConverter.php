<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use DOMDocument;
use DOMElement;
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

        $this->xml->appendChild($this->createXmlNode($rootNode ?? 'data', $array));

        return $this->xml->saveXML();
    }

    /**
     * Convert xml to an array with attributes
     *
     * @param string $xml The xml to convert
     *
     * @return array
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException If the XML is invalid and can't be loaded
     */
    public function xmlToArray(string $xml): array
    {
        // Load xml without escaping CDATA
        $element = \simplexml_load_string($xml, 'SimpleXMLElement', \LIBXML_NOCDATA);

        // If xml failed to load, return null
        if ($element === false) {
            throw new InvalidXmlException('XML can not be converted: invalid or contains invalid tag');
        }

        // Encode and decode to convert to array
        $array = \json_decode(\json_encode($element), true);

        // The encode/decode works mostly however self closing tags are converted to empty
        // arrays so they need to be recursively converted into strings
        $array = $this->convertEmptyArraysToString($array);

        // Force an array to be returned
        return \is_array($array) ? $array : [];
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
        $node->appendChild($this->createXmlNode($name, $value));

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
            $node->appendChild($this->createXmlNode($name, $value));
        }

        return $node;
    }

    /**
     * Recursively convert empty arrays to strings
     *
     * @param array $array The array to convert
     * @param string|null $replacement The string to replace empty arrays with
     *
     * @return array
     */
    private function convertEmptyArraysToString(array $array, ?string $replacement = null): array
    {
        foreach ($array as $key => $value) {
            // Ignore non-arrays
            if (!\is_array($value)) {
                continue;
            }

            // If value is an empty array, replace
            if (\is_array($value) && \count($value) === 0) {
                $array[$key] = $replacement ?? '';
                continue;
            }

            // Recurse
            $array[$key] = $this->convertEmptyArraysToString($value, $replacement ?? '');
        }

        return $array;
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
    private function createXmlNode(string $name, $value): DOMElement
    {
        // If value is an array, attempt to process attributes and values
        if (\is_array($value)) {
            return $this->processNodeValuesArray($name, $value);
        }

        // If node isn't an array, add it directly
        $node = $this->xml->createElement($name);
        $node->appendChild($this->xml->createTextNode($this->xToString($value)));

        return $node;
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

        // Process attributes
        if (isset($values['@attributes']) && \is_array($values['@attributes'])) {
            $this->processNodeAttributes($node, $name, $values['@attributes']);

            // Remove attributes array
            unset($values['@attributes']);
        }

        // Set values directly
        if (isset($values['@value'])) {
            $node->appendChild($this->xml->createTextNode($this->xToString($values['@value'])));

            // Remove value from array
            unset($values['@value']);

            // If there was a value, there is no recursion
            return $node;
        }

        // Set cname directly
        if (isset($values['@cdata'])) {
            $node->appendChild($this->xml->createCDATASection($this->xToString($values['@cdata'])));

            // Remove cdata from array
            unset($values['@cdata']);

            // If there was cdata, there is no recursion
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
}
