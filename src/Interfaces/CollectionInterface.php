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
     * @return mixed The original collection object
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidCollectionException If the item isn't valid
     */
    public function add($item);

    /**
     * Clear all items from a collection
     *
     * @return mixed The original collection object
     */
    public function clear();

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
     * @return mixed The original collection object
     */
    public function delete($item);

    /**
     * Delete an item from the collection
     *
     * @param int $nth The item to delete
     *
     * @return mixed The original collection object
     */
    public function deleteNth(int $nth);

    /**
     * Get the first item in this series
     *
     * @return mixed The first item
     */
    public function & first();

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
     * Get nth item from the items
     *
     * @param int $nth The item to get
     *
     * @return mixed
     */
    public function & getNth(int $nth);

    /**
     * Get the last item in this series
     *
     * @return mixed
     */
    public function & last();

    /**
     * Return collection items as an array
     *
     * @return array
     */
    public function toArray(): array;
}
