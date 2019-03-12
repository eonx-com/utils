<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use DateInterval;
use DatePeriod as BaseDatePeriod;
use DateTime as BaseDateTime;
use EoneoPay\Utils\Exceptions\UnexpectedTypeException;
use IteratorAggregate;

/**
 * Extended class that replaces the built in DatePeriod that correctly
 * handles 31st January + P1M being the 28th of Feburary, not the 3rd
 * of March.
 */
class DatePeriod implements IteratorAggregate
{
    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateInterval
     */
    private $interval;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * DatePeriod constructor
     *
     * @param \DateTime $start
     * @param \DateInterval $interval
     * @param \DateTime $end
     */
    public function __construct(BaseDateTime $start, DateInterval $interval, BaseDateTime $end)
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
     * @throws \Exception
     */
    public function getIterator(): iterable
    {
        $periods = new BaseDatePeriod($this->start, $this->interval, $this->end);
        $predictableDays = $this->hasPredictableDayIteration();
        $firstPeriod = null;

        foreach ($periods as $period) {
            /**
             * @var \DateTime $period
             *
             * @see https://youtrack.jetbrains.com/issue/WI-37859 - required until PhpStorm recognises === check
             */
            if (($period instanceof BaseDateTime) === false) {
                // @codeCoverageIgnoreStart
                // This exception will never be thrown in real life - DatePeriod will
                // only ever return DateTime objects
                throw new UnexpectedTypeException($period, BaseDateTime::class);
                // @codeCoverageIgnoreEnd
            }

            if ($firstPeriod === null) {
                $firstPeriod = $period;
            }

            if ($predictableDays) {
                $this->fixPeriod($period, $firstPeriod);
            }

            yield new DateTime(
                $period->format(BaseDateTime::RFC3339),
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
    private function fixPeriod(BaseDateTime $period, BaseDateTime $firstPeriod): void
    {
        if ($period->format('d') === $firstPeriod->format('d')) {
            return;
        }

        $period->modify('last day of last month');
    }
}
