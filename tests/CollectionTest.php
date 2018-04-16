<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Collection;
use EoneoPay\Utils\XmlConverter;
use Tests\EoneoPay\Utils\Stubs\Collection\JsonSerializableStub;
use Tests\EoneoPay\Utils\Stubs\Collection\TraversableStub;

/**
 * @covers \EoneoPay\Utils\Collection
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class CollectionTest extends TestCase
{
    /**
     * Generic associative array for testing
     *
     * @var array
     */
    private static $associative = [
        'a' => [
            'b' => [
                'c' => '1',
                'd' => '2'
            ],
            'e' => '3'
        ],
        'f' => '4'
    ];

    /**
     * Generic non-associative array for testing
     *
     * @var array
     */
    private static $numeric = [[1], [2]];

    /**
     * Test adding an item to a collection
     *
     * @return void
     */
    public function testAddToCollection(): void
    {
        // Create collection
        $collection = new Collection(self::$numeric);
        self::assertCount(\count(self::$numeric), $collection);

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
        $collection = new Collection(self::$numeric);

        // Test offsetExists and offsetGet
        self::assertArrayHasKey(0, $collection);
        /** @var \EoneoPay\Utils\Collection $collection [0] */
        self::assertEquals(new Collection(\reset(self::$numeric)), $collection[0]);

        // offsetUnset
        unset($collection[0]);
        self::assertCount(1, $collection);

        // offsetSet
        $original = $collection[0];
        $collection[0] = 'replaced value';
        $first = $collection->first();
        self::assertNotSame($original, $first);
        self::assertSame('replaced value', $first);
    }

    /**
     * Test using the iterator to iterate over collection
     *
     * @return void
     */
    public function testArrayIteratorInterface(): void
    {
        $collection = new Collection(self::$numeric);

        foreach ($collection as $index => $item) {
            self::assertSame($collection->nth($index + 1), $item);
        }
    }

    /**
     * Test clearing a collection
     *
     * @return void
     */
    public function testClearResetsCollectionContents(): void
    {
        $collection = new Collection(self::$numeric);
        self::assertCount(\count(self::$numeric), $collection);

        $collection->clear();
        self::assertCount(0, $collection);
    }

    /**
     * Test collapse merges array into a single array
     *
     * @return void
     */
    public function testCollapseConvertsMultipleArraysIntoSingleArray(): void
    {
        $collection = new Collection(self::$numeric);

        self::assertSame([1, 2], $collection->collapse()->toArray());
    }

    /**
     * Test creating a collection from data
     *
     * @return void
     */
    public function testCollectionConstructorSetsCollectionContents(): void
    {
        // Test without values
        $collection = new Collection();
        self::assertCount(0, $collection);

        // Test with object which can be cast to array
        $collection = new Collection('string');
        self::assertCount(1, $collection);
        self::assertSame(['string'], $collection->toArray());
        self::assertInternalType('string', $collection->first());

        // Test with different object types
        $collections = [
            new Collection(self::$numeric),
            new Collection(new Collection(self::$numeric)),
            new Collection(\json_encode(self::$numeric)),
            new Collection(new JsonSerializableStub()),
            new Collection(new TraversableStub())
        ];

        foreach ($collections as $collection) {
            self::assertCount(\count(self::$numeric), $collection);
            self::assertSame(self::$numeric, $collection->toArray());
            self::assertInstanceOf(Collection::class, $collection->first());
        }
    }

    /**
     * Test getting the nth item from a collection
     *
     * @return void
     */
    public function testCollectionReturnsNthItemViaNth(): void
    {
        $collection = new Collection(self::$numeric);
        self::assertSame(\reset(self::$numeric), $collection->nth(1)->toArray());
        self::assertNull($collection->nth(3));
    }

    /**
     * Test getting the first and last items
     *
     * @return void
     */
    public function testFirstLastCorrectlyReturnsFirstLastItemFromCollection(): void
    {
        $collection = new Collection(self::$numeric);

        self::assertSame($collection->nth(1), $collection->first());
        self::assertSame($collection->nth(2), $collection->last());
    }

    /**
     * Test getting original item array
     *
     * @return void
     */
    public function testGetItemsReturnsOriginalCollectionContents(): void
    {
        $collection = new Collection(self::$numeric);

        // Set up expectation
        $expected = [
            new Collection(\reset(self::$numeric)),
            new Collection(\end(self::$numeric))
        ];

        self::assertEquals($expected, $collection->getItems());
    }

    /**
     * Test get with default value is returned if key is invalid
     *
     * @return void
     */
    public function testGetWithDefaultValueReturnedIfKeyIsInvalid(): void
    {
        self::assertSame('default value', (new Collection(self::$associative))->get('invalid.key', 'default value'));
    }

    /**
     * Test values are able to be fetched from collection by dot notation
     *
     * @return void
     */
    public function testGetWithDotNotationRetrievesValue(): void
    {
        self::assertEquals(
            new Collection(self::$associative['a']['b']),
            (new Collection(self::$associative))->get('a.b')
        );
    }

    /**
     * Test has returns whether a key exists in a collection or not
     *
     * @return void
     */
    public function testHasBasesResultOnKeyAndNotValue(): void
    {
        $collection = new Collection(self::$associative);

        self::assertTrue($collection->has('a'));
        self::assertFalse($collection->has('x'));
    }

    /**
     * Test intersect correctly intersects values from another collection
     *
     * @return void
     */
    public function testIntersectCopiesValuesFromAnotherCollection(): void
    {
        $collection = new Collection(self::$associative);

        $array = ['z' => '9'];
        $copy = new Collection($array);
        $expected = self::$associative;
        $expected['a'] = $array['z'];

        $collection->intersect($copy, ['a' => 'z']);

        self::assertSame($expected, $collection->toArray());
    }

    /**
     * Test map alters the contents of the collection correctly
     *
     * @return void
     */
    public function testMapAltersContentsOfCollectionWithCallback(): void
    {
        $collection = new Collection(['abc', 'def']);

        self::assertSame(['ABC', 'DEF'], $collection->map(function ($string) {
            return \mb_strtoupper($string);
        })->toArray());
    }

    /**
     * Test merge into the collection merges the same as array merge would
     *
     * @return void
     */
    public function testMergeUpdatesCollectionContentsLikeArrayMergeRecursive(): void
    {
        $collection = new Collection(self::$associative);

        $array = ['a' => '15', 'z' => '9'];
        $collection->merge($array);

        self::assertSame(\array_merge_recursive(self::$associative, $array), $collection->toArray());
    }

    /**
     * Test deleting items from the collection
     *
     * @return void
     */
    public function testRemoveRemovesItemFromCollection(): void
    {
        $collection = new Collection(self::$numeric);

        self::assertCount(2, $collection);
        $itemToDelete = $collection->nth(1);
        $collection->remove($itemToDelete);
        self::assertCount(1, $collection);
        self::assertSame(['1' => \end(self::$numeric)], $collection->toArray());
    }

    /**
     * Test serialisation
     *
     * @return void
     */
    public function testSerialisationInterface(): void
    {
        $collection = new Collection(self::$numeric);

        self::assertSame(self::$numeric, $collection->toArray());
        self::assertSame(\json_encode(self::$numeric), $collection->toJson());
        self::assertSame(\json_encode(self::$numeric), \json_encode($collection));
        self::assertSame(\json_encode(self::$numeric), (string)$collection);
    }

    /**
     * Test set updates contents on a collection
     *
     * @return void
     */
    public function testSetUpdatesCollectionContents(): void
    {
        $collection = new Collection(self::$associative);
        $collection->set('z', '9');

        self::assertSame(\array_merge(self::$associative, ['z' => '9']), $collection->toArray());
    }

    /**
     * Test the to xml function returns the collection contents as xml
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function testToXmlReturnsCollectionContentsAsXml(): void
    {
        self::assertSame(
            (new XmlConverter())->arrayToXml(self::$associative),
            (new Collection(self::$associative))->toXml()
        );
    }

    /**
     * Test the to xml function returns null if the collection contains invalid xml
     *
     * @return void
     */
    public function testToXmlReturnsNullIfCollectionContentsIsInvalid(): void
    {
        self::assertNull((new Collection(['@invalid' => true]))->toXml());
    }
}
