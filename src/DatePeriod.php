<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use IteratorAggregate;

/**
 * Extended class that replaces the built in DatePeriod that correctly
 * handles 31st January + P1M being the 28th of Feburary, not the 3rd
 * of March.
 */
class DatePeriod implements IteratorAggregate
{
    /**
     * @var \EoneoPay\Utils\DateTime
     */
    private $start;

    /**
     * @var \EoneoPay\Utils\DateInterval
     */
    private $interval;

    /**
     * @var \EoneoPay\Utils\DateTime
     */
    private $end;

    /**
     * DatePeriod constructor
     *
     * @param \EoneoPay\Utils\DateTime $start
     * @param \EoneoPay\Utils\DateInterval $interval
     * @param \EoneoPay\Utils\DateTime $end
     */
    public function __construct(DateTime $start, DateInterval $interval, DateTime $end)
    {
        $this->start = $start;
        $this->interval = $interval;
        $this->end = $end;
    }

    /**
     * Iterates over the period
     *
     * @return \EoneoPay\Utils\DateTime[]
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function getIterator(): iterable
    {
        /** @var \DateTime[] $periods */
        $periods = new \DatePeriod($this->start, $this->interval, $this->end);
        $predictableDays = $this->hasPredictableDayIteration();
        $firstPeriod = null;

        foreach ($periods as $period) {
            if ($firstPeriod === null) {
                $firstPeriod = $period;
            }

            if ($predictableDays) {
                $this->fixPeriod($period, $firstPeriod);
            }

            yield new DateTime(
                $period->format(DateTime::RFC3339),
                $period->getTimezone()
            );
        }
    }

    /**
     * Returns true if the interval is a one or more month interval without years
     * or days.
     *
     * @return bool
     */
    private function hasPredictableDayIteration(): bool
    {
        $hasYearly = $this->interval->y > 0;
        $hasMonthly = $this->interval->m > 0;
        $hasOthers = $this->interval->d > 0 ||
            $this->interval->h > 0 ||
            $this->interval->i > 0 ||
            $this->interval->s > 0;

        return $hasOthers === false && ($hasYearly || $hasMonthly);
    }

    /**
     * Fixes the period if their day doesnt match.
     *
     * @param \DateTime $period
     * @param \DateTime $firstPeriod
     *
     * @return void
     */
    private function fixPeriod(\DateTime $period, \DateTime $firstPeriod): void
    {
        if ($period->format('d') === $firstPeriod->format('d')) {
            return;
        }

        $period->modify('last day of last month');
    }
}
