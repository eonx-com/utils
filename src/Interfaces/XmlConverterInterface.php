<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface XmlConverterInterface
{
    /**
     * Convert array to XML
     *
     * @param mixed[] $array The array to convert
     * @param string|null $rootNode The name of the root node
     *
     * @return string|null Null if xml is invalid
     */
    public function arrayToXml(array $array, ?string $rootNode = null): ?string;

    /**
     * Convert xml to an array with attributes
     *
     * @param string $xml The xml to convert
     *
     * @return mixed[]|null Null if xml is invalid
     */
    public function xmlToArray(string $xml): ?array;
}
