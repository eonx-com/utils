<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Stubs\Collection;

use ArrayIterator;
use IteratorAggregate;

class TraversableStub implements IteratorAggregate
{
    /**
     * Get iterator for collection
     *
     * @return \ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator([[1], [2]]);
    }
}
