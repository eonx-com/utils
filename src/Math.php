<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use EoneoPay\Utils\Exceptions\BcmathNotLoadedException;
use EoneoPay\Utils\Interfaces\MathInterface;

class Math implements MathInterface
{
    /**
     * Create math instance
     *
     * @throws \EoneoPay\Utils\Exceptions\BcmathNotLoadedException
     */
    public function __construct()
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
        $precision = $precision ?? self::DEFAULT_PRECISION;
        $roundingMode = $roundingMode ?? \PHP_ROUND_HALF_UP;

        return \number_format(\round((float)$value, $precision, $roundingMode), $precision, '.', '');
    }
}
