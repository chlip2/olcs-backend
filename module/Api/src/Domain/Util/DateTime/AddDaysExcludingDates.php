<?php

namespace Dvsa\Olcs\Api\Domain\Util\DateTime;

use Olcs\Logging\Log\Logger;

/**
 * AddDaysExcludingDates
 */
class AddDaysExcludingDates implements DateTimeCalculatorInterface
{
    private $wrapped;
    private $excluded;

    public function __construct(DateTimeCalculatorInterface $wrapped, DateProviderInterface $excluded)
    {
        $this->wrapped = $wrapped;
        $this->excluded = $excluded;
    }

    /**
     * Calculates a date that is $days before/after $date. Takes into account weekends and holidays.
     *
     * @param \DateTime $date Should be
     * @param integer $days The number of days to offset (can be a negative number)
     * @return \DateTime
     */
    public function calculateDate(\DateTime $date, $days)
    {
        Logger::debug('AddDaysExcludingDates : Calculating SLA date ' . $days . ' days from ' . $date->format('d-m-Y'));

        // calculate using AddWorkingDays after weekend days have been added
        $endDate = $date;
        $processedHolidays = [];
        $count = 0;
        while ($days !== 0 || $count > 10) {
            Logger::debug('Recursion ' . $count . "\n");
            Logger::debug('days => ' . $days);

            $wdEndDate = $this->wrapped->calculateDate($endDate, $days);

            Logger::debug('new endDate => ' . $wdEndDate->format('d/m/Y'));
            Logger::debug(
                'Getting holidays to exclude between ' . $endDate->format('d/m/Y') . ' and ' . $wdEndDate->format
                ('d/m/Y')
            );

            $excludedDates = $this->excluded->between($endDate, $wdEndDate);

            $excludedDateCount = 0;
            foreach ($excludedDates as $ed) {
                if (!in_array($ed['publicHolidayDate'], $processedHolidays)) {
                    $processedHolidays[] = $ed['publicHolidayDate'];
                    Logger::debug('Excluding date -> ' . $ed['publicHolidayDate']);
                    $excludedDateCount++;
                } else {
                    Logger::debug('Skipping date -> ' . $ed['publicHolidayDate']);
                }
            }
            
            $endDate = $wdEndDate;

            $days = $excludedDateCount;
            Logger::debug('END Recursion ' . $count . "\n\n");
            $count++;
        }

        return $endDate;
    }
}
