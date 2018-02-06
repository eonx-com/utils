<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface GeneratorInterface
{
    /**
     * Generate a random string
     *
     * @param int|null $length The length of the string to return, defaults to 16
     *
     * @return string
     *
     * @throws \Exception If sufficient entropy can't be gathered by `random_bytes` or `random_int`
     */
    public function randomString(?int $length = null): string;
}
