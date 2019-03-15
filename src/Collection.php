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
     * @inheritdoc
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * @inheritdoc
     */
    public function add($item): self
    {
        $this->offsetSet(null, $item);

        // Make chainable
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clear(): self
    {
        $this->items = [];

        // Make chainable
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function collapse(): self
    {
        // Replace contents with collapsed array
        $this->replace((new Arr())->collapse($this->toArray()));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return \count($this->getItems());
    }

    /**
     * @inheritdoc
     */
    public function filter(callable $callback): self
    {
        $this->replace(\array_filter($this->toArray(), $callback, \ARRAY_FILTER_USE_BOTH));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function first()
    {
        $keys = \array_keys($this->getItems());

        return (new Arr())->get($this->getItems(), \reset($keys));
    }

    /**
     * @inheritdoc
     */
    public function get($key, $default = null)
    {
        return $this->offsetGet($key) ?? $default;
    }

    /**
     * @inheritdoc
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->getItems());
    }

    /**
     * @inheritdoc
     */
    public function has(string $key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * @inheritdoc
     */
    public function intersect(SerializableInterface $source, array $keys): void
    {
        $this->replace((new Arr())->intersect($this->toArray(), $source->toArray(), $keys));
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @inheritdoc
     */
    public function last()
    {
        $keys = \array_keys($this->getItems());

        return (new Arr())->get($this->getItems(), \end($keys));
    }

    /**
     * @inheritdoc
     */
    public function map(callable $callback): self
    {
        $keys = \array_keys($this->toArray());
        $items = \array_map($callback, $this->toArray(), $keys);

        // Replace collection contents
        $this->replace(\array_combine($keys, $items) ?: []);

        return $this;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function offsetExists($key): bool
    {
        return (new Arr())->has($this->toArray(), $key);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($key)
    {
        $value = (new Arr())->get($this->toArray(), $key);

        // Convert array values to collection
        return \is_array($value) ? new static($value) : $value;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function offsetUnset($key): void
    {
        (new Arr())->remove($this->items, $key);
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function set(string $key, $value): void
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function toJson(): string
    {
        return \json_encode($this->toArray()) ?: '';
    }

    /**
     * @inheritdoc
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
