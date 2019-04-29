<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use DateInterval as BaseDateInterval;
use EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException;
use Exception;

class DateInterval extends BaseDateInterval
{
    /**
     * Create a DateInterval object from string and throw exception if invalid interval provided
     *
     * @param \DateInterval|string $interval Interval spec string
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
     */
    public function __construct($interval)
    {
        if (($interval instanceof \DateInterval) === true) {
            $interval = $interval->format('P%yY%mM%dDT%hH%mM%sS');
        }

        if (\is_string($interval) === false) {
            throw new InvalidDateTimeIntervalException('The date/time interval is invalid');
        }

        try {
            // Create parent object
            parent::__construct($interval);
        } catch (Exception $exception) {
            throw new InvalidDateTimeIntervalException('The date/time interval is invalid', null, null, $exception);
        }
    }

    /**
     * Detects if the day will change when using this Interval. It will
     * return true when there are only Years or Months present, any other
     * values will not result in stable day iteration.
     *
     * @return bool
     */
    public function hasPredictableDayIteration(): bool
    {
        $hasYearly = $this->y > 0;
        $hasMonthly = $this->m > 0;
        $hasOthers = $this->d > 0 ||
            $this->h > 0 ||
            $this->i > 0 ||
            $this->s > 0;

        return $hasOthers === false && ($hasYearly || $hasMonthly);
    }
}
