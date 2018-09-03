<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use ArrayIterator;
use EoneoPay\Utils\Exceptions\InvalidXmlTagException;
use EoneoPay\Utils\Interfaces\CollectionInterface;
use EoneoPay\Utils\Interfaces\SerializableInterface;
use JsonSerializable;
use Traversable;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity) Collection is massive and requires all functionality
 * @SuppressWarnings(PHPMD.TooManyPublicMethods) Collection requires many public methods to work
 */
class Collection implements CollectionInterface
{
    /**
     * Items in this collection
     *
     * @var mixed[]
     */
    private $items = [];

    /**
     * Create a new collection
     *
     * @param mixed $items The items to set to the collection
     */
    public function __construct($items = null)
    {
        // If no items are passed, skip
        if ($items === null) {
            return;
        }

        // Loop through items
        foreach ($this->getArrayableItems($items) as $key => $item) {
            $this->offsetSet($key, $item);
        }
    }

    /**
     * Convert collection to string
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
     * @return static
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
     * @return static
     */
    public function clear(): self
    {
        $this->items = [];

        // Make chainable
        return $this;
    }

    /**
     * Collapse the collection of items into a single array
     *
     * @return static
     */
    public function collapse(): self
    {
        // Replace contents with collapsed array
        $this->replace((new Arr())->collapse($this->toArray()));

        return $this;
    }

    /**
     * Get the number of items in the collection
     *
     * @return int
     */
    public function count(): int
    {
        return \count($this->getItems());
    }

    /**
     * Run a filter over each of the items
     *
     * @param callable $callback A callback to process against the items
     *
     * @return static
     */
    public function filter(callable $callback): self
    {
        $this->replace(\array_filter($this->toArray(), $callback, \ARRAY_FILTER_USE_BOTH));

        return $this;
    }

    /**
     * Get the first item in the collection
     *
     * @return mixed The first item
     */
    public function first()
    {
        $keys = \array_keys($this->getItems());

        return (new Arr())->get($this->getItems(), \reset($keys));
    }

    /**
     * Get item by key
     *
     * @param mixed $key The item to get
     * @param mixed $default The value to return if key isn't found
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->offsetGet($key) ?? $default;
    }

    /**
     * Get the items from the collection
     *
     * @return mixed[]
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
        return new ArrayIterator($this->getItems());
    }

    /**
     * Determine if the collection has a specific key
     *
     * @param string $key The key to search for, can use dot notation
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Copy keys from one collection to this collection if keys exist in both
     *
     * @param \EoneoPay\Utils\Interfaces\SerializableInterface $source The source to check for the key in
     * @param string[] $keys The destination/source key pairs to process
     *
     * @return void
     */
    public function intersect(SerializableInterface $source, array $keys): void
    {
        $this->replace((new Arr())->intersect($this->toArray(), $source->toArray(), $keys));
    }

    /**
     * Get collection contents to pass to json_encode
     *
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Get the last item in the collection
     *
     * @return mixed
     */
    public function last()
    {
        $keys = \array_keys($this->getItems());

        return (new Arr())->get($this->getItems(), \end($keys));
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
        $keys = \array_keys($this->toArray());
        $items = \array_map($callback, $this->toArray(), $keys);

        // Replace collection contents
        $this->replace(\array_combine($keys, $items));

        return $this;
    }

    /**
     * Recursively merge an array into the collection
     *
     * @param mixed[] $data The data to merge into the collection
     *
     * @return void
     */
    public function merge(array $data): void
    {
        // Convert arrays to collections
        foreach ($data as &$value) {
            $value = \is_array($value) ? new static($value) : $value;
        }

        // Unset reference
        unset($value);

        $this->replace((new Arr())->merge($this->toArray(), $data));
    }

    /**
     * Get nth item from the items
     *
     * @param int $nth The item to get
     *
     * @return mixed
     */
    public function nth(int $nth)
    {
        // Determine nth item, 1 = first item, 2 = second item and so on
        $nth = $nth > 0 ? $nth - 1 : -1;

        // Loop through items and return
        $counter = 0;
        foreach (\array_keys($this->getItems()) as $index) {
            // Keep incrementing counter until correct value is reached
            if ($counter !== $nth) {
                ++$counter;
                continue;
            }

            return (new Arr())->get($this->getItems(), $index);
        }

        // Item not found
        return null;
    }

    /**
     * Determine if an item exists at an offset
     *
     * @param mixed $key The key to check
     *
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return (new Arr())->has($this->toArray(), $key);
    }

    /**
     * Get an item at a given offset
     *
     * @param int $key The key to get
     *
     * @return mixed
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    public function offsetGet($key)
    {
        $value = (new Arr())->get($this->toArray(), $key);

        // Convert array values to collection
        return \is_array($value) ? new static($value) : $value;
    }

    /**
     * Set the item at a given offset
     *
     * @param int|string|null $key The key to set
     * @param mixed $value The value to set
     *
     * @return void
     */
    public function offsetSet($key, $value): void
    {
        $item = \is_array($value) ? new static($value) : $value;

        // Add by key if one is specified
        if ($key !== null) {
            (new Arr())->set($this->items, $key, $item);

            return;
        }

        // Add directly to array
        $this->items[] = $item;
    }

    /**
     * Unset the item at a given offset
     *
     * @param mixed[]|string $key The key(s) to unset
     *
     * @return void
     */
    public function offsetUnset($key): void
    {
        (new Arr())->remove($this->items, $key);
    }

    /**
     * Remove an item from a collection
     *
     * @param mixed $item The item to remove
     *
     * @return static
     */
    public function remove($item): self
    {
        // Find item in collection
        $flattened = (new Arr())->flatten($this->getItems());

        foreach ($flattened as $index => $value) {
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
     * Recursively replace an array's values into the collection
     *
     * @param mixed[] $data The data to replace in the collection
     *
     * @return void
     */
    public function replace(array $data): void
    {
        // Remove collection contents
        $this->clear();

        // Update contents
        foreach ($data as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * Set a value to the collection
     *
     * @param string $key The key to set to the collection, can use dot notation
     * @param mixed $value The value to set for this key
     *
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Return collection items as an array
     *
     * @return mixed[]
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
     * Generate XML string from the collection
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

    /**
     * Results array of items from an item
     *
     * @param mixed $items
     *
     * @return mixed[]
     */
    private function getArrayableItems($items): array
    {
        if (\is_array($items)) {
            return $items;
        }

        if ($items instanceof SerializableInterface) {
            return $items->toArray();
        }

        if (\is_string($items) && $this->isJson($items)) {
            return \json_decode($items, true);
        }

        if ($items instanceof JsonSerializable) {
            return $items->jsonSerialize();
        }

        if ($items instanceof Traversable) {
            return \iterator_to_array($items);
        }

        return (array)$items;
    }

    /**
     * Determine if a string is json
     *
     * @param string $string The string to check
     *
     * @return bool
     */
    private function isJson(string $string): bool
    {
        \json_decode($string);

        return \json_last_error() === \JSON_ERROR_NONE;
    }
}
