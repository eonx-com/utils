<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Exceptions\InvalidXmlTagException;
use EoneoPay\Utils\Interfaces\RepositoryInterface;

class Repository implements RepositoryInterface
{
    /**
     * The repository data
     *
     * @var array
     */
    private $data = [];

    /**
     * Create a new repository from a data array
     *
     * @param array|null $data The data to initially populate into the repository
     */
    public function __construct(?array $data = null)
    {
        $this->replace($data ?? []);
    }

    /**
     * Remove all data from the repository
     *
     * @return void
     */
    public function clear(): void
    {
        $this->data = [];
    }

    /**
     * Get a value from the repository or return the default value
     *
     * @param string $key The key to search for, can use dot notation
     * @param mixed $default The value to return if the key isn't found
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return (new Arr())->get($this->data, $key, $default);
    }

    /**
     * Determine if the repository has a specific key
     *
     * @param string $key The key to search for, can use dot notation
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return (new Arr())->has($this->data, $key);
    }

    /**
     * Copy keys from one repository to this repository if keys exist in both
     *
     * @param \EoneoPay\Utils\Interfaces\RepositoryInterface $source The source to check for the key in
     * @param array $keys The destination/source key pairs to process
     *
     * @return void
     */
    public function intersect(RepositoryInterface $source, array $keys): void
    {
        $this->replace((new Arr())->intersect($this->data, $source->toArray(), $keys));
    }

    /**
     * Get repository contents to be json serialized
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Recursively merge an array into the repository
     *
     * @param array $data The data to merge into the repository
     *
     * @return void
     */
    public function merge(array $data): void
    {
        $this->data = (new Arr())->merge($this->data, $data);
    }

    /**
     * Recursively replace an array's values into the repository
     *
     * @param array $data The data to replace in the repository
     *
     * @return void
     */
    public function replace(array $data): void
    {
        $this->data = (new Arr())->replace($this->data, $data);
    }

    /**
     * Set a value to the repository
     *
     * @param string $key The key to set to the repository, can use dot notation
     * @param mixed $value The value to set for this key
     *
     * @return void
     */
    public function set(string $key, $value): void
    {
        (new Arr())->set($this->data, $key, $value);
    }

    /**
     * Get the contents of the repository as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return (new Arr())->sort($this->data);
    }

    /**
     * Generate json from the repository
     *
     * @return string
     */
    public function toJson(): string
    {
        return \json_encode($this->jsonSerialize());
    }

    /**
     * Generate XML string from the repository
     *
     * @param string|null $rootNode The name of the root node
     *
     * @return string|null
     */
    public function toXml(?string $rootNode = null): ?string
    {
        try {
            return (new XmlConverter())->arrayToXml($this->toArray(), $rootNode);
        } /** @noinspection BadExceptionsProcessingInspection */ catch (InvalidXmlTagException $exception) {
            return null;
        }
    }
}
