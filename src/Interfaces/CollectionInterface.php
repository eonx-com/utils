<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface CollectionInterface extends SerializableInterface
{
    /**
     * Convert collection to string
     *
     * @return string
     */
    public function __toString();

    /**
     * Add an item to the collection
     *
     * @param mixed $item The item to add to the collection
     *
     * @return static
     */
    public function add($item);

    /**
     * Clear all items from a collection
     *
     * @return static
     */
    public function clear();

    /**
     * Collapse the collection of items into a single array
     *
     * @return static
     */
    public function collapse();

    /**
     * Run a filter over each of the items
     *
     * @param callable $callback A callback to process against the items
     *
     * @return static
     */
    public function filter(callable $callback);

    /**
     * Get the first item in the collection
     *
     * @return mixed The first item
     */
    public function first();

    /**
     * Get item by key
     *
     * @param mixed $key The item to get
     * @param mixed $default The value to return if key isn't found
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Get the items from the collection
     *
     * @return array
     */
    public function getItems(): array;

    /**
     * Determine if the collection has a specific key
     *
     * @param string $key The key to search for, can use dot notation
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Copy keys from one collection to this collection if keys exist in both
     *
     * @param \EoneoPay\Utils\Interfaces\SerializableInterface $source The source to check for the key in
     * @param array $keys The destination/source key pairs to process
     *
     * @return void
     */
    public function intersect(SerializableInterface $source, array $keys): void;

    /**
     * Get the last item in the collection
     *
     * @return mixed
     */
    public function last();

    /**
     * Run a map over each of the items
     *
     * @param callable $callback A callback to process against the items
     *
     * @return static
     */
    public function map(callable $callback);

    /**
     * Recursively merge an array into the collection
     *
     * @param array $data The data to merge into the collection
     *
     * @return void
     */
    public function merge(array $data): void;

    /**
     * Get nth item from the items
     *
     * @param int $nth The item to get
     *
     * @return mixed
     */
    public function nth(int $nth);

    /**
     * Remove an item from a collection
     *
     * @param mixed $item The item to remove
     *
     * @return static
     */
    public function remove($item);

    /**
     * Recursively replace an array's values into the collection
     *
     * @param array $data The data to replace in the collection
     *
     * @return void
     */
    public function replace(array $data): void;

    /**
     * Set a value to the collection
     *
     * @param string $key The key to set to the collection, can use dot notation
     * @param mixed $value The value to set for this key
     *
     * @return void
     */
    public function set(string $key, $value): void;
}
