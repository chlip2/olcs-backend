<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;

/**
 * IrfoGvPermit Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="irfo_gv_permit",
 *    indexes={
 *        @ORM\Index(name="fk_irfo_gv_permit_user1_idx", columns={"created_by"}),
 *        @ORM\Index(name="fk_irfo_gv_permit_user2_idx", columns={"last_modified_by"}),
 *        @ORM\Index(name="fk_irfo_gv_permit_organisation1_idx", columns={"organisation_id"}),
 *        @ORM\Index(name="fk_irfo_gv_permit_irfo_gv_permit_type1_idx", columns={"irfo_gv_permit_type_id"}),
 *        @ORM\Index(name="fk_irfo_gv_permit_ref_data1_idx", columns={"irfo_permit_status"}),
 *        @ORM\Index(name="fk_irfo_gv_permit_ref_data2_idx", columns={"withdrawn_reason"})
 *    }
 * )
 */
class IrfoGvPermit implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\IdIdentity,
        Traits\WithdrawnReasonManyToOne,
        Traits\OrganisationManyToOne,
        Traits\LastModifiedByManyToOne,
        Traits\CreatedByManyToOne,
        Traits\ExemptionDetails255Field,
        Traits\ExpiryDateField,
        Traits\InForceDateField,
        Traits\IrfoFeeId10Field,
        Traits\CustomCreatedOnField,
        Traits\CustomLastModifiedOnField,
        Traits\CustomVersionField;

    /**
     * Irfo permit status
     *
     * @var \Olcs\Db\Entity\RefData
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\RefData")
     * @ORM\JoinColumn(name="irfo_permit_status", referencedColumnName="id")
     */
    protected $irfoPermitStatus;

    /**
     * Irfo gv permit type
     *
     * @var \Olcs\Db\Entity\IrfoGvPermitType
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\IrfoGvPermitType")
     * @ORM\JoinColumn(name="irfo_gv_permit_type_id", referencedColumnName="id")
     */
    protected $irfoGvPermitType;

    /**
     * Is fee exempt
     *
     * @var boolean
     *
     * @ORM\Column(type="yesnonull", name="is_fee_exempt", nullable=false)
     */
    protected $isFeeExempt = 0;

    /**
     * No of copies
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="no_of_copies", nullable=false)
     */
    protected $noOfCopies = 0;

    /**
     * Note
     *
     * @var string
     *
     * @ORM\Column(type="string", name="note", length=2000, nullable=true)
     */
    protected $note;

    /**
     * Permit printed
     *
     * @var boolean
     *
     * @ORM\Column(type="yesnonull", name="permit_printed", nullable=false)
     */
    protected $permitPrinted = 0;

    /**
     * Year required
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="year_required", nullable=true)
     */
    protected $yearRequired;

    /**
     * Set the irfo permit status
     *
     * @param \Olcs\Db\Entity\RefData $irfoPermitStatus
     * @return \Olcs\Db\Entity\IrfoGvPermit
     */
    public function setIrfoPermitStatus($irfoPermitStatus)
    {
        $this->irfoPermitStatus = $irfoPermitStatus;

        return $this;
    }

    /**
     * Get the irfo permit status
     *
     * @return \Olcs\Db\Entity\RefData
     */
    public function getIrfoPermitStatus()
    {
        return $this->irfoPermitStatus;
    }

    /**
     * Set the irfo gv permit type
     *
     * @param \Olcs\Db\Entity\IrfoGvPermitType $irfoGvPermitType
     * @return \Olcs\Db\Entity\IrfoGvPermit
     */
    public function setIrfoGvPermitType($irfoGvPermitType)
    {
        $this->irfoGvPermitType = $irfoGvPermitType;

        return $this;
    }

    /**
     * Get the irfo gv permit type
     *
     * @return \Olcs\Db\Entity\IrfoGvPermitType
     */
    public function getIrfoGvPermitType()
    {
        return $this->irfoGvPermitType;
    }

    /**
     * Set the is fee exempt
     *
     * @param boolean $isFeeExempt
     * @return \Olcs\Db\Entity\IrfoGvPermit
     */
    public function setIsFeeExempt($isFeeExempt)
    {
        $this->isFeeExempt = $isFeeExempt;

        return $this;
    }

    /**
     * Get the is fee exempt
     *
     * @return boolean
     */
    public function getIsFeeExempt()
    {
        return $this->isFeeExempt;
    }

    /**
     * Set the no of copies
     *
     * @param int $noOfCopies
     * @return \Olcs\Db\Entity\IrfoGvPermit
     */
    public function setNoOfCopies($noOfCopies)
    {
        $this->noOfCopies = $noOfCopies;

        return $this;
    }

    /**
     * Get the no of copies
     *
     * @return int
     */
    public function getNoOfCopies()
    {
        return $this->noOfCopies;
    }

    /**
     * Set the note
     *
     * @param string $note
     * @return \Olcs\Db\Entity\IrfoGvPermit
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get the note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set the permit printed
     *
     * @param boolean $permitPrinted
     * @return \Olcs\Db\Entity\IrfoGvPermit
     */
    public function setPermitPrinted($permitPrinted)
    {
        $this->permitPrinted = $permitPrinted;

        return $this;
    }

    /**
     * Get the permit printed
     *
     * @return boolean
     */
    public function getPermitPrinted()
    {
        return $this->permitPrinted;
    }

    /**
     * Set the year required
     *
     * @param int $yearRequired
     * @return \Olcs\Db\Entity\IrfoGvPermit
     */
    public function setYearRequired($yearRequired)
    {
        $this->yearRequired = $yearRequired;

        return $this;
    }

    /**
     * Get the year required
     *
     * @return int
     */
    public function getYearRequired()
    {
        return $this->yearRequired;
    }
}
