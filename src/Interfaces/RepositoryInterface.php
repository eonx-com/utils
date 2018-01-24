<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface RepositoryInterface
{
    /**
     * Remove all data from the repository
     *
     * @return void
     */
    public function clear(): void;

    /**
     * Get a value from the repository or return the default value
     *
     * @param string $key The key to search for, can use dot notation
     * @param mixed $default The value to return if the key isn't found
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Determine if the repository has a specific key
     *
     * @param string $key The key to search for, can use dot notation
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Recursively merge an array into the repository
     *
     * @param array $data The data to merge into the repository
     *
     * @return void
     */
    public function merge(array $data): void;

    /**
     * Recursively replace an array's values into the repository
     *
     * @param array $data The data to replace in the repository
     *
     * @return void
     */
    public function replace(array $data): void;

    /**
     * Set a value to the repository
     *
     * @param string $key The key to set to the repository, can use dot notation
     * @param mixed $value The value to set for this key
     *
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * Get the contents of the repository as an array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Generate json from the repository
     *
     * @return string
     */
    public function toJson(): string;

    /**
     * Generate XML string from the repository
     *
     * @param string|null $rootNode The name of the root node
     *
     * @return string|null
     */
    public function toXml(?string $rootNode = null): ?string;
}
