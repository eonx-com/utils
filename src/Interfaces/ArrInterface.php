<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface ArrInterface
{
    /**
     * Collapse an array of arrays into a single array
     *
     * @param mixed[] $array The array to collapse
     *
     * @return mixed[]
     */
    public function collapse(array $array): array;

    /**
     * Flatten an array into dot notation
     *
     * @param mixed[] $array The array to flatten
     * @param string|null $prepend The flattened array key so far
     *
     * @return mixed[]
     */
    public function flatten(array $array, ?string $prepend = null): array;

    /**
     * Get a value from an array or return the default value
     *
     * @param mixed[] $array The array to search in
     * @param mixed $key The key to search for, can use dot notation
     * @param mixed $default The value to return if the key isn't found
     *
     * @return mixed
     */
    public function get(array $array, $key, $default = null);

    /**
     * Determine if the repository has a specific key
     *
     * @param mixed[] $array The array to search in
     * @param mixed $key The key to search for, can use dot notation
     *
     * @return bool
     */
    public function has(array $array, $key): bool;

    /**
     * Copy keys from source to destination if the key exists in the source
     *
     * @param mixed[] $destination The destination to copy the value to
     * @param mixed[] $source The source to check for the key in
     * @param mixed[] $keys The destination/source key pairs to process
     *
     * @return mixed[]
     */
    public function intersect(array $destination, array $source, array $keys): array;

    /**
     * Recursively merge two or more arrays together allowing dot notation
     *
     * @param mixed[] $array The array to merge the additional arrays into
     * @param mixed[] ...$arrays The additional arrays to merge in
     *
     * @return mixed[]
     */
    public function merge(array $array, array ...$arrays): array;

    /**
     * Remove one or many array items from a given array using "dot" notation
     *
     * @param mixed[] $array The array to unset keys from
     * @param mixed[]|string $keys The keys to unset
     *
     * @return void
     */
    public function remove(array &$array, $keys): void;

    /**
     * Recursively replace values from two or more arrays together allowing dot notation
     *
     * @param mixed[] $array The array to replace the additional array values into
     * @param mixed[] ...$arrays The additional arrays to get values from
     *
     * @return mixed[]
     */
    public function replace(array $array, array ...$arrays): array;

    /**
     * Look for a value in an array without punctuation or case sensitivity, this will allow USERID, userId
     * and user_id to all resolve to the same key
     *
     * @param mixed[] $array The array to search through
     * @param string $search The value to search for
     *
     * @return string
     */
    public function search(array $array, string $search): ?string;

    /**
     * Set a value on an array using dot notation
     *
     * @param mixed[] $array The array to set the value on
     * @param mixed $key The key to set the value for
     * @param mixed $value The value to set
     *
     * @return void
     */
    public function set(array &$array, $key, $value): void;

    /**
     * Recursively sort an array by key
     *
     * @param mixed[] $array The array to sort recursively
     *
     * @return mixed[]
     */
    public function sort(array $array): array;

    /**
     * Restore a flattened array to three dimensional
     *
     * @param mixed[] $array The array to restore
     *
     * @return mixed[]
     */
    public function unflatten(array $array): array;
}
