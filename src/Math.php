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
     */
    private $precision;

    /**
     * How to round results
     *
     * @var int
     */
    private $roundingMode;

    /**
     * Create math instance
     *
     * @param int|null $precision The precision to use for calculations
     * @param int|null $roundingMode How to round results
     *
     * @throws \EoneoPay\Utils\Exceptions\BcmathNotLoadedException
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
    public function add(string $start, string ... $additions): string
    {
        foreach ($additions as $addition) {
            $result = \bcadd($result ?? $start, $addition, 99);
        }

        return $this->round($result ?? $start);
    }

    /**
     * Round result to precision, will zero pad if required
     *
     * @param string $value The value to round
     *
     * @return string
     */
    private function round(string $value): string
    {
        return \number_format(\round((float)$value, $this->precision, $this->roundingMode), $this->precision, '.', '');
    }

    /**
     * @inheritdoc
     */
    public function divide(string $dividend, string $divisor): string
    {
        return $this->round(\bcdiv($dividend, $divisor, 99));
    }

    /**
     * @inheritdoc
     */
    public function multiply(string $multiplicand, string $multiplier): string
    {
        return $this->round(\bcmul($multiplicand, $multiplier, 99));
    }

    /**
     * @inheritdoc
     */
    public function subtract(string $start, string ... $subtractions): string
    {
        foreach ($subtractions as $subtraction) {
            $result = \bcsub($result ?? $start, $subtraction, 99);
        }

        return $this->round($result ?? $start);
    }
}
