<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Interfaces;

interface MathInterface
{
    /**
     * The precision to use
     *
     * @const int
     */
    public const DEFAULT_PRECISION = 8;

    /**
     * @noinspection PhpDocSignatureInspection Signature matches parameters but phpstorm doesn't understand it
     *
     * Add numbers together using bcmath
     *
     * @param string $start The number to start with
     * @param string ...$additions Additional numbers to add
     *
     * @return string
     */
    public function add(string $start, string ... $additions): string;

    /**
     * Divide one number by another using bcmath
     *
     * @param string $dividend The number to divide
     * @param string $divisor The number to divide by
     *
     * @return string
     */
    public function divide(string $dividend, string $divisor): string;

    /**
     * Multiply one number by another using bcmath
     *
     * @param string $multiplicand The number to multiply
     * @param string $multiplier The number to multiply by
     *
     * @return string
     */
    public function multiply(string $multiplicand, string $multiplier): string;

    /**
     * @noinspection PhpDocSignatureInspection Signature matches parameters but phpstorm doesn't understand it
     *
     * Subtract numbers using bcmath
     *
     * @param string $start The number to start with
     * @param string ...$subtractions Additional numbers to subtract
     *
     * @return string
     */
    public function subtract(string $start, string ... $subtractions): string;
}
