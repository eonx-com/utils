<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Utils\Str;

use EoneoPay\Utils\Str;
use Tests\EoneoPay\Utils\TestCases\StrTestCase;

class SnakeTest extends StrTestCase
{
    /**
     * @var array
     */
    private static $snakeDashes = [
        'SnakeCase' => 'snake-case',
        'snakecase' => 'snakecase',
        'snakeCase' => 'snake-case',
        'snake case' => 'snake-case'
    ];

    /**
     * @var array
     */
    private static $snakeUnderscore = [
        'SnakeCase' => 'snake_case',
        'snakecase' => 'snakecase',
        'snakeCase' => 'snake_case',
        'snake case' => 'snake_case'
    ];

    /**
     * Test snake function with dash delimiter.
     *
     * @return void
     */
    public function testSnakeWithDashes(): void
    {
        foreach (static::$snakeDashes as $input => $expected) {
            self::assertEquals($expected, (new Str())->snake($input, '-'));
        }
    }

    /**
     * Test snake function with underscore delimiter.
     *
     * @return void
     */
    public function testSnakeWithUnderscores(): void
    {
        $this->processSimpleTests('snake', static::$snakeUnderscore);
    }
}
