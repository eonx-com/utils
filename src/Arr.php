<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use Closure;
use EoneoPay\Utils\Interfaces\ArrInterface;
use EoneoPay\Utils\Interfaces\SerializableInterface;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods) Arr requires many public methods to work
 */
class Arr implements ArrInterface
{
    /**
     * @inheritdoc
     */
    public function collapse(array $array): array
    {
        $results = [];

        foreach ($array as $values) {
            // De-serialise serialisables
            if ($values instanceof SerializableInterface) {
                $values = $values->toArray();
            }

            // If item is not an array, skip
            if (\is_array($values) === false) {
                continue;
            }

            $results[] = $values;
        }

        return \count($results) ? \array_merge(...$results) : [];
    }

    /**
     * @inheritdoc
     */
    public function flatten(array $array, ?string $prepend = null): array
    {
        $flattened = [];

        foreach ($array as $key => $value) {
            // If value is an array, recurse
            if (\is_array($value) && \count($value)) {
                $flattened[] = $this->flatten($value, \sprintf('%s%s.', (string)$prepend, $key));
            }

            // Set value
            $flattened[] = [\sprintf('%s%s', (string)$prepend, $key) => $value];
        }

        // Merge flattened keys if some were found otherwise return an empty array
        return \count($flattened) ? \array_merge(...$flattened) : [];
    }

    /**
     * @inheritdoc
     */
    public function get(array $array, $key, $default = null)
    {
        $flattened = $this->flatten($array);

        return $flattened[$key] ?? $default;
    }

    /**
     * @inheritdoc
     */
    public function groupBy(array $array, $groupBy, $default = null): array
    {
        $ret = [];

        foreach ($array as $key => $value) {
            $groupByValue = ($groupBy instanceof Closure)
                ? $groupBy($value)
                : $this->get($value, $groupBy, $default);

            if (\array_key_exists($groupByValue, $ret) === false) {
                $ret[$groupByValue] = [];
            }

            $ret[$groupByValue][$key] = $value;
        }

        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function has(array $array, $key): bool
    {
        return \array_key_exists($key, $this->flatten($array));
    }

    /**
     * @inheritdoc
     */
    public function intersect(array $destination, array $source, array $keys): array
    {
        foreach ($keys as $destinationKey => $sourceKey) {
            // If destinationKey is numeric, use sourceKey as destinationKey, this allows arrays like
            // ['key1', 'key2' => 'value'] where the source and destination have the same key
            $destinationKey = \is_numeric($destinationKey) ? $sourceKey : $destinationKey;

            // Only add if source contains sourceKey
            if (\array_key_exists($sourceKey, $source)) {
                $this->set($destination, $destinationKey, $source[$sourceKey]);
            }
        }

        return $destination;
    }

    /**
     * @inheritdoc
     */
    public function merge(array $array, array ... $arrays): array
    {
        // Unflatten all arrays
        $unflattened[] = $this->unflatten($array);

        foreach ($arrays as &$mergeable) {
            $unflattened[] = $this->unflatten($mergeable);
        }

        return \array_merge_recursive(...$unflattened);
    }

    /**
     * @inheritdoc
     */
    public function only(array $array, $keys): array
    {
        return \array_intersect_key($array, \array_flip((array) $keys));
    }

    /**
     * @inheritdoc
     */
    public function remove(array &$array, $keys): void
    {
        $original = &$array;

        // Force keys to be an array
        $keys = (array)$keys;

        // If there are no keys there is nothing to do
        if (\count($keys) === 0) {
            return;
        }

        // Remove each key based on dot notation
        foreach ($keys as $key) {
            // If the exact key exists in the top-level, remove it
            if (\array_key_exists($key, $array)) {
                unset($array[$key]);

                continue;
            }

            // Convert dot key to array
            $parts = \explode('.', $key);

            // Reset array before each pass
            $array = &$original;

            // Process each level until the final value is found to unset
            while (\count($parts) > 1) {
                $part = \array_shift($parts);

                // Skip invalid parts
                if (isset($array[$part]) === false || \is_array($array[$part]) === false) {
                    continue 2;
                }

                // Shift array down level
                $array = &$array[$part];
            }

            // Remove final part
            unset($array[\array_shift($parts)]);
        }
    }

    /**
     * @inheritdoc
     */
    public function replace(array $array, array ... $arrays): array
    {
        // Flatten all arrays
        $flattened[] = $this->flatten($array);

        foreach ($arrays as &$replaceable) {
            $flattened[] = $this->flatten($replaceable);
        }

        return $this->unflatten(\array_replace(...$flattened) ?? []);
    }

    /**
     * @inheritdoc
     */
    public function search(array $array, string $search): ?string
    {
        // Clean up search term by lower casing and removing anything exception alphanumeric characters
        $clean = \mb_strtolower(\preg_replace('/[^\da-zA-Z]/', '', $search) ?? '');

        // Loop through array and compare values with the same cleansing as the search term
        foreach ($array as $value) {
            // If value isn't a string, skip
            if (\is_string($value) === false) {
                continue;
            }

            if ($clean === \mb_strtolower(\preg_replace('/[^\da-zA-Z]/', '', $value) ?? '')) {
                return $value;
            }
        }

        // If value isn't found return null
        return null;
    }

    /**
     * @inheritdoc
     */
    public function set(array &$array, $key, $value): void
    {
        $keys = \explode('.', (string)$key);

        // Iterate through key parts to find the position to set the value
        while (\count($keys) > 1) {
            $key = \array_shift($keys);

            if (isset($array[$key]) === false || \is_array($array[$key]) === false) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        // Set value
        $array[\array_shift($keys)] = $value;
    }

    /**
     * @inheritdoc
     */
    public function sort(array $array): array
    {
        // Recursive values
        foreach ($array as $index => $value) {
            if (\is_array($value)) {
                $array[$index] = $this->sort($value);
            }
        }

        \ksort($array);

        return $array;
    }

    /**
     * @inheritdoc
     */
    public function unflatten(array $array): array
    {
        $unpacked = [];

        // set() recurses the array and unflattens dot notations correctly, so just pass-through
        foreach ($array as $key => $value) {
            $this->set($unpacked, (string)$key, $value);
        }

        return $unpacked;
    }
}
