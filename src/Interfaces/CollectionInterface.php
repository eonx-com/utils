<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

use ArrayIterator;

interface CollectionInterface extends SerializableInterface
{
    /**
     * Convert series to string
     *
     * @return string
     */
    public function __toString(): string;

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
     * Collapse the collection of items into a single array.
     *
     * @return static
     */
    public function collapse();

    /**
     * Get the number of items in this series
     *
     * @return int The number of items in this series
     */
    public function count(): int;

    /**
     * Delete an item from a collection
     *
     * @param mixed $item The item to delete
     *
     * @return static
     */
    public function delete($item);

    /**
     * Get the first item in this series
     *
     * @return mixed The first item
     */
    public function & first();

    /**
     * Map a collection and flatten the result by a single level
     *
     * @param callable $callback A callback to process against the items
     *
     * @return static
     */
    public function flatMap(callable $callback);

    /**
     * Get item by key
     *
     * @param mixed $key The item to get
     *
     * @return mixed
     */
    public function & get($key);

    /**
     * Get the items from the collection
     *
     * @return array
     */
    public function getItems(): array;

    /**
     * Get iterator for collection
     *
     * @return \ArrayIterator
     */
    public function getIterator(): ArrayIterator;

    /**
     * Get repository contents to be json serialized
     *
     * @return array
     */
    public function jsonSerialize(): array;

    /**
     * Get the last item in this series
     *
     * @return mixed
     */
    public function & last();

    /**
     * Run a map over each of the items
     *
     * @param callable $callback A callback to process against the items
     *
     * @return static
     */
    public function map(callable $callback);

    /**
     * Get nth item from the items
     *
     * @param int $nth The item to get
     *
     * @return mixed
     */
    public function & nth(int $nth);
}
