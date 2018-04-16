<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils;

use EoneoPay\Utils\Repository;
use EoneoPay\Utils\XmlConverter;

/**
 * @covers \EoneoPay\Utils\Repository
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class RepositoryTest extends TestCase
{
    /**
     * The array to use for testing
     *
     * @var array
     */
    private $array = [
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
     * The populated repository used for testing
     *
     * @var \EoneoPay\Utils\Repository
     */
    private $repository;

    /**
     * Create a populated repository for testing
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->repository = new Repository($this->array);
    }

    /**
     * Test array access
     *
     * @return void
     */
    public function testArrayAccessInterface(): void
    {
        $repository = new Repository($this->array);

        // Test offsetExists and offsetGet
        self::assertArrayHasKey('a', $repository);
        self::assertSame($this->array['a'], $repository['a']);

        // offsetUnset
        unset($repository['a']);
        self::assertCount(1, $repository);

        // offsetSet
        $original = $repository['f'];
        $repository['f'] = 'replaced value';
        self::assertNotSame($original, $repository['f']);
        self::assertSame('replaced value', $repository['f']);
    }

    /**
     * Test clear empties repository completely
     *
     * @return void
     */
    public function testClearRemovesAllContentsFromRepository(): void
    {
        self::assertSame($this->array, $this->repository->toArray());

        $this->repository->clear();
        self::assertEmpty($this->repository->toArray());
    }

    /**
     * Test constructor populates repository
     *
     * @return void
     */
    public function testConstructorPopulatesRepository(): void
    {
        $repository = new Repository();
        self::assertEmpty($repository->toArray());

        $repository = new Repository($this->array);
        self::assertSame($this->array, $repository->toArray());
    }

    /**
     * Test counting a repository
     *
     * @return void
     */
    public function testCountable(): void
    {
        $repository = new Repository($this->array);

        self::assertCount(2, $repository);
    }

    /**
     * Test get with default value is returned if key is invalid
     *
     * @return void
     */
    public function testGetWithDefaultValueReturnedIfKeyIsInvalid(): void
    {
        self::assertSame('default value', $this->repository->get('invalid.key', 'default value'));
    }

    /**
     * Test values are able to be fetched from repository by dot notation
     *
     * @return void
     */
    public function testGetWithDotNotationRetrievesValue(): void
    {
        self::assertSame($this->array['a']['b'], $this->repository->get('a.b'));
    }

    /**
     * Test has returns whether a key exists in a repository or not
     *
     * @return void
     */
    public function testHasBasesResultOnKeyAndNotValue(): void
    {
        self::assertTrue($this->repository->has('a'));
        self::assertFalse($this->repository->has('x'));
    }

    /**
     * Test intersect correctly intersects values from another repository
     *
     * @return void
     */
    public function testIntersectCopiesValuesFromAnotherRepository(): void
    {
        $array = ['z' => '9'];
        $repository = new Repository($array);
        $expected = $this->array;
        $expected['a'] = $array['z'];

        $this->repository->intersect($repository, ['a' => 'z']);

        self::assertSame($expected, $this->repository->toArray());
    }

    /**
     * Test using the iterator to iterate over collection
     *
     * @return void
     */
    public function testIterator(): void
    {
        $repository = new Repository($this->array);

        foreach ($repository as $index => $item) {
            self::assertSame($repository->get($index), $item);
        }
    }

    /**
     * Test json serialize returns the same thing as to array
     *
     * @return void
     */
    public function testJsonSerializeReturnsRepositoryContentsAsArray(): void
    {
        self::assertSame($this->array, $this->repository->jsonSerialize());
    }

    /**
     * Test merge into the repository merges the same as array merge would
     *
     * @return void
     */
    public function testMergeUpdatesRepositoryContents(): void
    {
        $array = ['a' => '15', 'z' => '9'];
        $this->repository->merge($array);

        self::assertSame(\array_merge_recursive($this->array, $array), $this->repository->toArray());
    }

    /**
     * Test replace into the repository merges the same as array replace would
     *
     * @return void
     */
    public function testReplaceReplacesRepositoryContents(): void
    {
        $array = ['a' => '15', 'z' => '9'];
        $this->repository->replace($array);

        self::assertSame(\array_replace_recursive($this->array, $array), $this->repository->toArray());
    }

    /**
     * Test set updates contents on a repository
     *
     * @return void
     */
    public function testSetUpdatesRepositoryContents(): void
    {
        $this->repository->set('z', '9');

        self::assertSame(\array_merge($this->array, ['z' => '9']), $this->repository->toArray());
    }

    /**
     * Test the to array function correctly converts the repository back to an array
     *
     * @return void
     */
    public function testToArrayReturnsRepositoryContentsAsArray(): void
    {
        self::assertSame($this->array, $this->repository->toArray());
    }

    /**
     * Test the to json function returns the repository contents as json
     *
     * @return void
     */
    public function testToJsonReturnsRepositoryContentsAsJson(): void
    {
        self::assertSame(\json_encode($this->array), $this->repository->toJson());
    }

    /**
     * Test the to xml function returns null if the repository contains invalid xml
     *
     * @return void
     */
    public function testToXmlReturnsNullIfRepositoryContentsIsInvalid(): void
    {
        $repository = new Repository(['@invalid' => true]);

        self::assertNull($repository->toXml());
    }

    /**
     * Test the to xml function returns the repository contents as xml
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function testToXmlReturnsRepositoryContentsAsXml(): void
    {
        self::assertSame((new XmlConverter())->arrayToXml($this->array), $this->repository->toXml());
    }
}
