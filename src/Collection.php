<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use ArrayAccess;
use ArrayIterator;
use Countable;
use EoneoPay\Utils\Exceptions\InvalidCollectionException;
use EoneoPay\Utils\Exceptions\InvalidXmlException;
use EoneoPay\Utils\Interfaces\CollectionInterface;
use EoneoPay\Utils\Interfaces\SerializableInterface;
use IteratorAggregate;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods) Collection requires many public methods to work
 */
class Collection implements ArrayAccess, CollectionInterface, Countable, IteratorAggregate
{
    /**
     * Items in this series
     *
     * @var array
     */
    private $items = [];

    /**
     * Create a new collection
     *
     * @param array $items The items to set to the collection
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidCollectionException If the collection is invalid
     */
    public function __construct(array $items = null)
    {
        // If no items are passed, skip
        if (null === $items) {
            return;
        }

        // Loop through items
        foreach ($items as $key => $item) {
            // If key is not numeric, this isn't a collection
            if (!\is_numeric($key)) {
                throw new InvalidCollectionException('Collections can only be used for non-associative arrays');
            }

            // If item is an array, convert to repository
            $this->add($item);
        }
    }

    /**
     * Convert series to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Add an item to the collection
     *
     * @param mixed $item The item to add to the collection
     *
     * @return \EoneoPay\Utils\Collection
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidCollectionException If the item isn't valid
     */
    public function add($item): self
    {
        $this->offsetSet(null, $item);

        // Make chainable
        return $this;
    }

    /**
     * Clear all items from a collection
     *
     * @return \EoneoPay\Utils\Collection
     */
    public function clear(): self
    {
        $this->items = [];

        // Make chainable
        return $this;
    }

    /**
     * Get the number of items in this series
     *
     * @return int The number of items in this series
     */
    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * Delete an item from a collection
     *
     * @param mixed $item The item to delete
     *
     * @return \EoneoPay\Utils\Collection
     */
    public function delete($item): self
    {
        // Find item to delete
        /** @var int $index */
        foreach ($this->items as $index => $value) {
            // Skip items which don't match
            if ($item !== $value) {
                continue;
            }

            // Remove matching item
            $this->offsetUnset($index);
        }

        // Make chainable
        return $this;
    }

    /**
     * Delete an item from the collection
     *
     * @param int $nth The item to delete
     *
     * @return \EoneoPay\Utils\Collection
     */
    public function deleteNth(int $nth): self
    {
        // Subtract 1 from nth to get the item to remove, set to -1 if $nth
        // is invalid to prevent random removal
        $nth = 0 < $nth ? $nth - 1 : -1;

        // Only remove if $nth is zero or more and key exists
        if (0 <= $nth && \array_key_exists($nth, $this->items)) {
            $this->offsetUnset($nth);
        }

        // Make chainable
        return $this;
    }

    /**
     *
     */

    /**
     * Get the first item in this series
     *
     * @return mixed The first item
     */
    public function & first()
    {
        $keys = \array_keys($this->items);

        return $this->items[\reset($keys)];
    }

    /**
     * Get the items from the collection
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Get iterator for collection
     *
     * @return \ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Get nth item from the items
     *
     * @param int $nth The item to get
     *
     * @return mixed
     */
    public function & getNth(int $nth)
    {
        // Determine nth item, 1 = first item, 2 = second item and so on
        $nth = $nth > 0 ? $nth - 1 : -1;

        // Return item or null if index doesn't exist
        $item = $this->items[$nth] ?? null;

        return $item;
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
     * Get the last item in this series
     *
     * @return mixed
     */
    public function & last()
    {
        $keys = \array_keys($this->items);

        return $this->items[\end($keys)];
    }

    /**
     * Determine if an item exists at an offset
     *
     * @param int $key The key to check
     *
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return \array_key_exists($key, $this->items);
    }

    /**
     * Get an item at a given offset
     *
     * @param int $key The key to get
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    /**
     * Set the item at a given offset
     *
     * @param int $key The key to set
     * @param mixed $value The value to set
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidCollectionException If the item isn't valids
     */
    public function offsetSet($key, $value): void
    {
        // If item isn't an array make it one
        if (\is_scalar($value)) {
            $value = [$value];
        }

        if (!$value instanceof Repository && !\is_array($value)) {
            throw new InvalidCollectionException('Collection items must be a repository or array');
        }

        $item = $value instanceof Repository ? $value : new Repository($value);

        if ($key !== null) {
            // Add by key
            $this->items[$key] = $item;

            return;
        }

        // Add directly to array
        $this->items[] = $item;
    }

    /**
     * Unset the item at a given offset
     *
     * @param string $key The key to unset
     *
     * @return void
     */
    public function offsetUnset($key): void
    {
        unset($this->items[$key]);

        // Reset keys
        $this->items = \array_values($this->items);
    }

    /**
     * Return collection items as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $items = $this->getItems();

        // Convert serializable objects back to array
        foreach ($items as &$item) {
            if ($item instanceof SerializableInterface) {
                $item = $item->toArray();
            }
        }

        return $items;
    }

    /**
     * Convert collection to json
     *
     * @return string
     */
    public function toJson(): string
    {
        return \json_encode($this->toArray());
    }

    /**
     * Generate XML string from the repository
     *
     * @param string|null $rootNode The name of the root node
     *
     * @return string|null
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException Collections can't be converted to XML
     */
    public function toXml(?string $rootNode = null): ?string
    {
        throw new InvalidXmlException('Collections are not compatible with xml schema therefore can\'t be converted');
    }
}
