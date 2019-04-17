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
     * Add numbers together using bcmath
     *
     * @param string $start The number to start with
     * @param string $addition Additional numbers to add
     * @param int|null $precision The precision to use for calculations
     * @param int|null $roundingMode How to round results
     *
     * @return string
     */
    public function add(string $start, string $addition, ?int $precision = null, ?int $roundingMode = null): string;

    /**
     * Divide one number by another using bcmath
     *
     * @param string $dividend The number to divide
     * @param string $divisor The number to divide by
     * @param int|null $precision The precision to use for calculations
     * @param int|null $roundingMode How to round results
     *
     * @return string
     */
    public function divide(
        string $dividend,
        string $divisor,
        ?int $precision = null,
        ?int $roundingMode = null
    ): string;

    /**
     * Multiply one number by another using bcmath
     *
     * @param string $multiplicand The number to multiply
     * @param string $multiplier The number to multiply by
     * @param int|null $precision The precision to use for calculations
     * @param int|null $roundingMode How to round results
     *
     * @return string
     */
    public function multiply(
        string $multiplicand,
        string $multiplier,
        ?int $precision = null,
        ?int $roundingMode = null
    ): string;

    /**
     * Subtract numbers using bcmath
     *
     * @param string $start The number to start with
     * @param string $subtraction Additional numbers to subtract
     * @param int|null $precision The precision to use for calculations
     * @param int|null $roundingMode How to round results
     *
     * @return string
     */
    public function subtract(
        string $start,
        string $subtraction,
        ?int $precision = null,
        ?int $roundingMode = null
    ): string;
}
