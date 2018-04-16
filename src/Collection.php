<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use ArrayAccess;
use ArrayIterator;
use Countable;
use EoneoPay\Utils\Exceptions\InvalidXmlException;
use EoneoPay\Utils\Interfaces\CollectionInterface;
use EoneoPay\Utils\Interfaces\SerializableInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

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
     */
    public function __construct(array $items = null)
    {
        // If no items are passed, skip
        if (null === $items) {
            return;
        }

        // Loop through items
        foreach ($this->getArrayableItems($items) as $key => $item) {
            $this->offsetSet($key, $item);
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
     * Collapse the collection of items into a single array.
     *
     * @return static
     */
    public function collapse(): self
    {
        return new static((new Arr())->collapse($this->items));
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
     * @return static
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
     * Map a collection and flatten the result by a single level
     *
     * @param callable $callback A callback to process against the items
     *
     * @return static
     */
    public function flatMap(callable $callback): self
    {
        return $this->map($callback)->collapse();
    }

    /**
     * Get item by key
     *
     * @param mixed $key The item to get
     *
     * @return mixed
     */
    public function & get($key)
    {
        return $this->items[$key] ?? null;
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
     * Run a map over each of the items
     *
     * @param callable $callback A callback to process against the items
     *
     * @return static
     */
    public function map(callable $callback): self
    {
        $keys = \array_keys($this->items);

        $items = \array_map($callback, $this->items, $keys);

        return new static(\array_combine($keys, $items));
    }

    /**
     * Get nth item from the items
     *
     * @param int $nth The item to get
     *
     * @return mixed
     */
    public function & nth(int $nth)
    {
        // Determine nth item, 1 = first item, 2 = second item and so on
        $nth = $nth > 0 ? $nth - 1 : -1;

        // Loop through items and return
        $counter = 0;
        foreach ($this->items as $index => $item) {
            // Keep incrementing counter until correct value is reached
            if ($counter !== $nth) {
                ++$counter;
                continue;
            }

            return $this->items[$index];
        }

        // Item not found
        return null;
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
     * @param int|null $key The key to set
     * @param mixed $value The value to set
     *
     * @return void
     */
    public function offsetSet($key, $value): void
    {
        // If item isn't an array make it one
        if (\is_scalar($value)) {
            $value = [$value];
        }

        $item = \is_array($value) ? new Repository($value) : $value;

        if (null !== $key) {
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
     * @param int $key The key to unset
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

    /**
     * Results array of items from an item
     *
     * @param mixed $items
     *
     * @return array
     */
    private function getArrayableItems($items): array
    {
        if (\is_array($items)) {
            return $items;
        }

        if ($items instanceof Arrayable) {
            return $items->toArray();
        }

        if ($items instanceof Jsonable) {
            return \json_decode($items->toJson(), true);
        }

        if ($items instanceof JsonSerializable) {
            return $items->jsonSerialize();
        }

        if ($items instanceof Traversable) {
            return \iterator_to_array($items);
        }

        return (array)$items;
    }
}
