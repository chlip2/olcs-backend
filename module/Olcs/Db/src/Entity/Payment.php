<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;

/**
 * Payment Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="payment",
 *    indexes={
 *        @ORM\Index(name="IDX_6D28840DF41D6079", columns={"receipt_document_id"}),
 *        @ORM\Index(name="IDX_6D28840DDE12AB56", columns={"created_by"}),
 *        @ORM\Index(name="IDX_6D28840D65CF370E", columns={"last_modified_by"})
 *    }
 * )
 */
class Payment implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\IdIdentity,
        Traits\CreatedByManyToOne,
        Traits\LastModifiedByManyToOne,
        Traits\CustomCreatedOnField,
        Traits\CustomLastModifiedOnField,
        Traits\CustomVersionField;

    /**
     * Receipt document
     *
     * @var \Olcs\Db\Entity\Document
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\Document", fetch="LAZY")
     * @ORM\JoinColumn(name="receipt_document_id", referencedColumnName="id", nullable=true)
     */
    protected $receiptDocument;

    /**
     * Legacy status
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="legacy_status", nullable=true)
     */
    protected $legacyStatus;

    /**
     * Legacy method
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="legacy_method", nullable=true)
     */
    protected $legacyMethod;

    /**
     * Legacy choice
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="legacy_choice", nullable=true)
     */
    protected $legacyChoice;

    /**
     * Legacy guid
     *
     * @var string
     *
     * @ORM\Column(type="string", name="legacy_guid", length=255, nullable=true)
     */
    protected $legacyGuid;

    /**
     * Completed date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="completed_date", nullable=true)
     */
    protected $completedDate;

    /**
     * Guid
     *
     * @var string
     *
     * @ORM\Column(type="string", name="guid", length=255, nullable=true)
     */
    protected $guid;

    /**
     * Set the receipt document
     *
     * @param \Olcs\Db\Entity\Document $receiptDocument
     * @return Payment
     */
    public function setReceiptDocument($receiptDocument)
    {
        $this->receiptDocument = $receiptDocument;

        return $this;
    }

    /**
     * Get the receipt document
     *
     * @return \Olcs\Db\Entity\Document
     */
    public function getReceiptDocument()
    {
        return $this->receiptDocument;
    }

    /**
     * Set the legacy status
     *
     * @param int $legacyStatus
     * @return Payment
     */
    public function setLegacyStatus($legacyStatus)
    {
        $this->legacyStatus = $legacyStatus;

        return $this;
    }

    /**
     * Get the legacy status
     *
     * @return int
     */
    public function getLegacyStatus()
    {
        return $this->legacyStatus;
    }

    /**
     * Set the legacy method
     *
     * @param int $legacyMethod
     * @return Payment
     */
    public function setLegacyMethod($legacyMethod)
    {
        $this->legacyMethod = $legacyMethod;

        return $this;
    }

    /**
     * Get the legacy method
     *
     * @return int
     */
    public function getLegacyMethod()
    {
        return $this->legacyMethod;
    }

    /**
     * Set the legacy choice
     *
     * @param int $legacyChoice
     * @return Payment
     */
    public function setLegacyChoice($legacyChoice)
    {
        $this->legacyChoice = $legacyChoice;

        return $this;
    }

    /**
     * Get the legacy choice
     *
     * @return int
     */
    public function getLegacyChoice()
    {
        return $this->legacyChoice;
    }

    /**
     * Set the legacy guid
     *
     * @param string $legacyGuid
     * @return Payment
     */
    public function setLegacyGuid($legacyGuid)
    {
        $this->legacyGuid = $legacyGuid;

        return $this;
    }

    /**
     * Get the legacy guid
     *
     * @return string
     */
    public function getLegacyGuid()
    {
        return $this->legacyGuid;
    }

    /**
     * Set the completed date
     *
     * @param \DateTime $completedDate
     * @return Payment
     */
    public function setCompletedDate($completedDate)
    {
        $this->completedDate = $completedDate;

        return $this;
    }

    /**
     * Get the completed date
     *
     * @return \DateTime
     */
    public function getCompletedDate()
    {
        return $this->completedDate;
    }

    /**
     * Set the guid
     *
     * @param string $guid
     * @return Payment
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get the guid
     *
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }
}
