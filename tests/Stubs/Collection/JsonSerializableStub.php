<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\Collection;

use JsonSerializable;

class JsonSerializableStub implements JsonSerializable
{
    /**
     * Return contents for json_encode
     *
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [[1], [2]];
    }
}
