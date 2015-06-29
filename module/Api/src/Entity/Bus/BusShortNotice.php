<?php

namespace Dvsa\Olcs\Api\Entity\Bus;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusShortNotice Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="bus_short_notice",
 *    indexes={
 *        @ORM\Index(name="ix_bus_short_notice_created_by", columns={"created_by"}),
 *        @ORM\Index(name="ix_bus_short_notice_last_modified_by", columns={"last_modified_by"})
 *    },
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="uk_bus_short_notice_bus_reg_id", columns={"bus_reg_id"}),
 *        @ORM\UniqueConstraint(name="uk_bus_short_notice_olbs_key", columns={"olbs_key"})
 *    }
 * )
 */
class BusShortNotice extends AbstractBusShortNotice
{
    public function getCalculatedValues()
    {
        return [
            'busReg' => null,
            'isLatestVariation' => $this->getBusReg()->isLatestVariation()
        ];
    }

    /**
     * Updates a short notice record
     *
     * @param string $bankHolidayChange
     * @param string $unforseenChange
     * @param string $unforseenDetail
     * @param string $timetableChange
     * @param string $timetableDetail
     * @param string $replacementChange
     * @param string $replacementDetail
     * @param string $notAvailableChange
     * @param string $notAvailableDetail
     * @param string $specialOccasionChange
     * @param string $specialOccasionDetail
     * @param string $connectionChange
     * @param string $connectionDetail
     * @param string $holidayChange
     * @param string $holidayDetail
     * @param string $trcChange
     * @param string $trcDetail
     * @param string $policeChange
     * @param string $policeDetail
     */
    public function update(
        $bankHolidayChange,
        $unforseenChange,
        $unforseenDetail,
        $timetableChange,
        $timetableDetail,
        $replacementChange,
        $replacementDetail,
        $notAvailableChange,
        $notAvailableDetail,
        $specialOccasionChange,
        $specialOccasionDetail,
        $connectionChange,
        $connectionDetail,
        $holidayChange,
        $holidayDetail,
        $trcChange,
        $trcDetail,
        $policeChange,
        $policeDetail
    )
    {
        $this->getBusReg()->canEdit();

        $this->bankHolidayChange = $bankHolidayChange;
        $this->unforseenChange = $unforseenChange;
        $this->unforseenDetail = $unforseenDetail;
        $this->timetableChange = $timetableChange;
        $this->timetableDetail = $timetableDetail;
        $this->replacementChange = $replacementChange;
        $this->replacementDetail = $replacementDetail;
        $this->notAvailableChange = $notAvailableChange;
        $this->notAvailableDetail = $notAvailableDetail;
        $this->specialOccasionChange = $specialOccasionChange;
        $this->specialOccasionDetail = $specialOccasionDetail;
        $this->connectionChange = $connectionChange;
        $this->connectionDetail = $connectionDetail;
        $this->holidayChange = $holidayChange;
        $this->holidayDetail = $holidayDetail;
        $this->trcChange = $trcChange;
        $this->trcDetail = $trcDetail;
        $this->policeChange = $policeChange;
        $this->policeDetail = $policeDetail;
    }
}
