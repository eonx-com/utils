<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Exceptions\BcmathNotLoadedException;
use EoneoPay\Utils\Interfaces\MathInterface;

class Math implements MathInterface
{
    /**
     * The precision to use for calculations
     *
     * @var int
     *
     * @deprecated This should be set in the math functions itself
     */
    private $precision;

    /**
     * How to round results
     *
     * @var int
     *
     * @deprecated This should be set in the math functions itself
     */
    private $roundingMode;

    /**
     * Create math instance
     *
     * @param int|null $precision The precision to use for calculations
     * @param int|null $roundingMode How to round results
     *
     * @throws \EoneoPay\Utils\Exceptions\BcmathNotLoadedException
     *
     * @deprecated The use of constructor to set precision and roundingMode has been deprecated.
     * Precision and Rounding mode should be added into the math functions as a parameter.
     */
    public function __construct(?int $precision = null, ?int $roundingMode = null)
    {
        if (\extension_loaded('bcmath') === false) {
            // @codeCoverageIgnoreStart
            // Thrown in instances where bcmath is not present.
            throw new BcmathNotLoadedException(\sprintf(
                'bcmath must be loaded to use the "%s" class',
                __CLASS__
            ));
            // @codeCoverageIgnoreEnd
        }

        $this->precision = $precision ?? self::DEFAULT_PRECISION;
        $this->roundingMode = $roundingMode ?? \PHP_ROUND_HALF_UP;
    }

    /**
     * @inheritdoc
     */
    public function add(string $start, string $addition, ?int $precision = null, ?int $roundingMode = null): string
    {
        return $this->round(\bcadd($start, $addition, 99), $precision, $roundingMode);
    }

    /**
     * @inheritdoc
     */
    public function comp(
        string $leftOperand,
        string $rightOperand,
        ?int $scale = null
    ): int {
        return \bccomp($leftOperand, $rightOperand, $scale ?? 0);
    }

    /**
     * @inheritdoc
     */
    public function divide(string $dividend, string $divisor, ?int $precision = null, ?int $roundingMode = null): string
    {
        return $this->round(\bcdiv($dividend, $divisor, 99), $precision, $roundingMode);
    }

    /**
     * @inheritdoc
     */
    public function multiply(
        string $multiplicand,
        string $multiplier,
        ?int $precision = null,
        ?int $roundingMode = null
    ): string {
        return $this->round(\bcmul($multiplicand, $multiplier, 99), $precision, $roundingMode);
    }

    /**
     * @inheritdoc
     */
    public function subtract(
        string $start,
        string $subtraction,
        ?int $precision = null,
        ?int $roundingMode = null
    ): string {
        return $this->round(\bcsub($start, $subtraction, 99), $precision, $roundingMode);
    }

    /**
     * Round result to precision, will zero pad if required
     *
     * @param string $value The value to round
     * @param int|null $precision The precision to use for calculations
     * @param int|null $roundingMode How to round results
     *
     * @return string
     */
    private function round(string $value, ?int $precision = null, ?int $roundingMode = null): string
    {
        $precision = $precision ?? $this->precision;
        $roundingMode = $roundingMode ?? $this->roundingMode;

        return \number_format(\round((float)$value, $precision, $roundingMode), $precision, '.', '');
    }
}
