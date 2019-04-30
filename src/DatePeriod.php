<?php
declare(strict_types=1);

namespace EoneoPay\Utils;

use DateInterval;
use DatePeriod as BaseDatePeriod;
use DateTime as BaseDateTime;
use EoneoPay\Utils\Exceptions\InvalidAnchorException;
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
     * @var \DateTime|null
     */
    private $anchor;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var \DateInterval
     */
    private $interval;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * DatePeriod constructor
     *
     * @param \DateTime $start
     * @param \DateInterval $interval
     * @param \DateTime $end
     * @param \DateTime|null $anchor
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidAnchorException
     */
    public function __construct(
        BaseDateTime $start,
        DateInterval $interval,
        BaseDateTime $end,
        ?BaseDateTime $anchor = null
    ) {
        $this->start = $start;
        $this->interval = $interval;
        $this->end = $end;
        $this->anchor = $anchor;

        // Check if the anchor is less than one period ahead of the provided
        // start date
        $second = clone $this->start;
        $second->add($this->interval);

        if ($this->anchor && ($this->anchor > $second || $this->anchor < $this->start)) {
            throw new InvalidAnchorException(\sprintf(
                'The anchor "%s" must be less than one period ahead of the specified start date "%s".',
                $this->anchor->format(BaseDateTime::RFC3339),
                $second->format(BaseDateTime::RFC3339)
            ));
        }
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
        $periods = $this->getPeriods();
        $predictableDays = $this->hasPredictableDayIteration();
        $firstPeriod = null;

        if ($this->anchor) {
            // When an anchor is provided, the start time is advanced to the anchor
            // which means we're going to yield the original start date
            // first, before iterating.

            yield new DateTime(
                $this->start->format(BaseDateTime::RFC3339),
                $this->start->getTimezone()
            );
        }

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
                // If we've got predictable days, attempt to fix the PHP bug that
                // causes an overflow. 31 January + P1M = 2 or 3 of March, not 28 Feb

                $this->fixPeriod($period, $firstPeriod);
            }

            if ($period > $this->end) {
                // We got further than the user provided $end

                return null;
            }

            yield new DateTime(
                $period->format(BaseDateTime::RFC3339),
                $period->getTimezone()
            );
        }
    }

    /**
     * Build the base \DatePeriod iterator.
     *
     * @return \DatePeriod
     */
    private function getPeriods(): BaseDatePeriod
    {
        // Clone $end 1 further period to catch the PHP bug
        $end = clone $this->end;
        $end->add($this->interval);

        return new BaseDatePeriod($this->anchor ?? $this->start, $this->interval, $end);
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
