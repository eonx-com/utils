<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Interfaces\ArrInterface;
use EoneoPay\Utils\Interfaces\SerializableInterface;

class Arr implements ArrInterface
{
    /**
     * Collapse an array of arrays into a single array
     *
     * @param mixed[] $array The array to collapse
     *
     * @return mixed[]
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

        return \array_merge(...$results);
    }

    /**
     * Flatten an array into dot notation
     *
     * @param mixed[] $array The array to flatten
     * @param string|null $prepend The flattened array key so far
     *
     * @return mixed[]
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
     * Get a value from an array or return the default value
     *
     * @param mixed[] $array The array to search in
     * @param mixed $key The key to search for, can use dot notation
     * @param mixed $default The value to return if the key isn't found
     *
     * @return mixed
     */
    public function get(array $array, $key, $default = null)
    {
        $flattened = $this->flatten($array);

        return $flattened[$key] ?? $default;
    }

    /**
     * Determine if the repository has a specific key
     *
     * @param mixed[] $array The array to search in
     * @param mixed $key The key to search for, can use dot notation
     *
     * @return bool
     */
    public function has(array $array, $key): bool
    {
        return \array_key_exists($key, $this->flatten($array));
    }

    /**
     * Copy keys from source to destination if the key exists in the source
     *
     * @param mixed[] $destination The destination to copy the value to
     * @param mixed[] $source The source to check for the key in
     * @param mixed[] $keys The destination/source key pairs to process
     *
     * @return mixed[]
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
     * Recursively merge two or more arrays together allowing dot notation
     *
     * @param mixed[] $array The array to merge the additional arrays into
     * @param mixed[] ...$arrays The additional arrays to merge in
     *
     * @return mixed[]
     */
    public function merge(array $array, array ...$arrays): array
    {
        // Unflatten all arrays
        $unflattened[] = $this->unflatten($array);

        foreach ($arrays as &$mergeable) {
            $unflattened[] = $this->unflatten($mergeable);
        }

        return \array_merge_recursive(...$unflattened);
    }

    /**
     * Remove one or many array items from a given array using "dot" notation
     *
     * @param mixed[] $array The array to unset keys from
     * @param mixed $keys The keys to unset
     *
     * @return void
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
     * Recursively replace values from two or more arrays together allowing dot notation
     *
     * @param mixed[] $array The array to replace the additional array values into
     * @param mixed[] ...$arrays The additional arrays to get values from
     *
     * @return mixed[]
     */
    public function replace(array $array, array ...$arrays): array
    {
        // Flatten all arrays
        $flattened[] = $this->flatten($array);

        foreach ($arrays as &$replaceable) {
            $flattened[] = $this->flatten($replaceable);
        }

        return $this->unflatten(\array_replace(...$flattened));
    }

    /**
     * Look for a value in an array without punctuation or case sensitivity, this will allow USERID, userId
     * and user_id to all resolve to the same key
     *
     * @param mixed[] $array The array to search through
     * @param string $search The value to search for
     *
     * @return string
     */
    public function search(array $array, string $search): ?string
    {
        // Clean up search term by lower casing and removing anything exception alphanumeric characters
        $clean = \mb_strtolower(\preg_replace('/[^\da-zA-Z]/', '', $search));

        // Loop through array and compare values with the same cleansing as the search term
        foreach ($array as $value) {
            // If value isn't a string, skip
            if (\is_string($value) === false) {
                continue;
            }

            if ($clean === \mb_strtolower(\preg_replace('/[^\da-zA-Z]/', '', $value))) {
                return $value;
            }
        }

        // If value isn't found return null
        return null;
    }

    /**
     * Set a value on an array using dot notation
     *
     * @param mixed[] $array The array to set the value on
     * @param mixed $key The key to set the value for
     * @param mixed $value The value to set
     *
     * @return void
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
     * Recursively sort an array by key
     *
     * @param mixed[] $array The array to sort recursively
     *
     * @return mixed[]
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
     * Restore a flattened array to three dimensional
     *
     * @param mixed[] $array The array to restore
     *
     * @return mixed[]
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
