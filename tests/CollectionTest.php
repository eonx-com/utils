<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Collection;
use EoneoPay\Utils\Exceptions\InvalidXmlException;
use EoneoPay\Utils\Repository;

/**
 * @covers \EoneoPay\Utils\Collection
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class CollectionTest extends TestCase
{
    /**
     * Generic array for testing
     *
     * @var array
     */
    private static $data = [[1], [2]];

    /**
     * Test adding an item to a collection
     *
     * @return void
     */
    public function testAddToCollection(): void
    {
        // Create collection
        $collection = new Collection(self::$data);
        self::assertCount(\count(self::$data), $collection);

        // Add an item
        $collection->add(3);
        self::assertCount(3, $collection);
    }

    /**
     * Test array access
     *
     * @return void
     */
    public function testArrayAccessInterface(): void
    {
        $collection = new Collection(self::$data);

        // Test offsetExists and offsetGet
        self::assertArrayHasKey(0, $collection);
        /** @var \EoneoPay\Utils\Collection $collection [0] */
        self::assertSame(\reset(self::$data), $collection[0]->toArray());

        // offsetUnset
        unset($collection[0]);
        self::assertCount(1, $collection);

        // offsetSet
        $original = $collection[0];
        $collection[0] = 'replaced value';
        $first = $collection->first();
        self::assertNotSame($original, $first->toArray());
        self::assertSame(['replaced value'], $first->toArray());
    }

    /**
     * Test clearing a collection
     *
     * @return void
     */
    public function testClearCollection(): void
    {
        $collection = new Collection(self::$data);
        self::assertCount(\count(self::$data), $collection);

        $collection->clear();
        self::assertCount(0, $collection);
    }

    /**
     * Test creating a collection from data
     *
     * @return void
     */
    public function testCreateCollection(): void
    {
        // Test with values
        $collection = new Collection(self::$data);
        self::assertCount(\count(self::$data), $collection);
        self::assertInstanceOf(Repository::class, $collection->first());

        // Test without values
        $collection = new Collection();
        self::assertCount(0, $collection);
    }

    /**
     * Test deleting items from the collection
     *
     * @return void
     */
    public function testDeleteItem(): void
    {
        $collection = new Collection(self::$data);

        self::assertCount(2, $collection);
        $itemToDelete = $collection->nth(1);
        $collection->delete($itemToDelete);
        self::assertCount(1, $collection);
        self::assertSame([\end(self::$data)], $collection->toArray());
    }

    /**
     * Test getting the first and last items
     *
     * @return void
     */
    public function testFirstLast(): void
    {
        $collection = new Collection(self::$data);

        self::assertSame($collection->nth(1), $collection->first());
        self::assertSame($collection->nth(2), $collection->last());
    }

    /**
     * Test getting original item array
     *
     * @return void
     */
    public function testGetOriginalItems(): void
    {
        $collection = new Collection(self::$data);

        // Set up expectation
        $expected = [
            new Repository(\reset(self::$data)),
            new Repository(\end(self::$data))
        ];

        self::assertEquals($expected, $collection->getItems());
    }

    /**
     * Test using the iterator to iterate over collection
     *
     * @return void
     */
    public function testIterator(): void
    {
        $collection = new Collection(self::$data);

        foreach ($collection as $index => $item) {
            self::assertSame($collection->nth($index + 1), $item);
        }
    }

    /**
     * Test serialisation
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlException Collections can't be converted to XML
     */
    public function testSerialisation(): void
    {
        $collection = new Collection(self::$data);

        self::assertSame(self::$data, $collection->toArray());
        self::assertSame(\json_encode(self::$data), $collection->toJson());
        self::assertSame(\json_encode(self::$data), \json_encode($collection));
        self::assertSame(\json_encode(self::$data), (string)$collection);

        $this->expectException(InvalidXmlException::class);
        $collection->toXml();
    }
}
