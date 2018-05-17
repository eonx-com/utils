<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

use JsonSerializable;

interface SerializableInterface extends JsonSerializable
{
    /**
     * Get the contents of the repository as an array
     *
     * @return mixed[]
     */
    public function toArray(): array;

    /**
     * Generate json from the repository
     *
     * @return string
     */
    public function toJson(): string;

    /**
     * Generate XML string from the repository
     *
     * @param string|null $rootNode The name of the root node
     *
     * @return string|null
     */
    public function toXml(?string $rootNode = null): ?string;
}
