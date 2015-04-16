<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Olcs\Db\Entity\Traits;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SeriousInfringement Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedDate", timeAware=true)
 * @ORM\Table(name="serious_infringement",
 *    indexes={
 *        @ORM\Index(name="ix_serious_infringement_case_id", columns={"case_id"}),
 *        @ORM\Index(name="ix_serious_infringement_erru_response_user_id", columns={"erru_response_user_id"}),
 *        @ORM\Index(name="ix_serious_infringement_member_state_code", columns={"member_state_code"}),
 *        @ORM\Index(name="ix_serious_infringement_si_category_id", columns={"si_category_id"}),
 *        @ORM\Index(name="ix_serious_infringement_si_category_type_id", columns={"si_category_type_id"}),
 *        @ORM\Index(name="ix_serious_infringement_created_by", columns={"created_by"}),
 *        @ORM\Index(name="ix_serious_infringement_last_modified_by", columns={"last_modified_by"})
 *    },
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="uk_serious_infringement_olbs_key_olbs_type", columns={"olbs_key","olbs_type"}),
 *        @ORM\UniqueConstraint(name="uk_serious_infringement_notification_number", columns={"notification_number"})
 *    }
 * )
 */
class SeriousInfringement implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\CreatedByManyToOne,
        Traits\CustomCreatedOnField,
        Traits\CustomDeletedDateField,
        Traits\IdIdentity,
        Traits\LastModifiedByManyToOne,
        Traits\CustomLastModifiedOnField,
        Traits\OlbsKeyField,
        Traits\SiCategoryManyToOne,
        Traits\CustomVersionField;

    /**
     * Case
     *
     * @var \Olcs\Db\Entity\Cases
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\Cases", inversedBy="seriousInfringements")
     * @ORM\JoinColumn(name="case_id", referencedColumnName="id", nullable=false)
     */
    protected $case;

    /**
     * Check date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="check_date", nullable=true)
     */
    protected $checkDate;

    /**
     * Erru response sent
     *
     * @var string
     *
     * @ORM\Column(type="yesno", name="erru_response_sent", nullable=false, options={"default": 0})
     */
    protected $erruResponseSent = 0;

    /**
     * Erru response time
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="erru_response_time", nullable=true)
     */
    protected $erruResponseTime;

    /**
     * Erru response user
     *
     * @var \Olcs\Db\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\User")
     * @ORM\JoinColumn(name="erru_response_user_id", referencedColumnName="id", nullable=true)
     */
    protected $erruResponseUser;

    /**
     * Infringement date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="infringement_date", nullable=true)
     */
    protected $infringementDate;

    /**
     * Member state code
     *
     * @var \Olcs\Db\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\Country")
     * @ORM\JoinColumn(name="member_state_code", referencedColumnName="id", nullable=true)
     */
    protected $memberStateCode;

    /**
     * Notification number
     *
     * @var string
     *
     * @ORM\Column(type="string", name="notification_number", length=36, nullable=true)
     */
    protected $notificationNumber;

    /**
     * Olbs type
     *
     * @var string
     *
     * @ORM\Column(type="string", name="olbs_type", length=50, nullable=true)
     */
    protected $olbsType;

    /**
     * Reason
     *
     * @var string
     *
     * @ORM\Column(type="string", name="reason", length=500, nullable=true)
     */
    protected $reason;

    /**
     * Si category type
     *
     * @var \Olcs\Db\Entity\SiCategoryType
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\SiCategoryType")
     * @ORM\JoinColumn(name="si_category_type_id", referencedColumnName="id", nullable=false)
     */
    protected $siCategoryType;

    /**
     * Applied penaltie
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Olcs\Db\Entity\SiPenalty", mappedBy="seriousInfringement")
     */
    protected $appliedPenalties;

    /**
     * Imposed erru
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Olcs\Db\Entity\SiPenaltyErruImposed", mappedBy="seriousInfringement")
     */
    protected $imposedErrus;

    /**
     * Requested erru
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Olcs\Db\Entity\SiPenaltyErruRequested", mappedBy="seriousInfringement")
     */
    protected $requestedErrus;

    /**
     * Initialise the collections
     */
    public function __construct()
    {
        $this->appliedPenalties = new ArrayCollection();
        $this->imposedErrus = new ArrayCollection();
        $this->requestedErrus = new ArrayCollection();
    }

    /**
     * Set the case
     *
     * @param \Olcs\Db\Entity\Cases $case
     * @return SeriousInfringement
     */
    public function setCase($case)
    {
        $this->case = $case;

        return $this;
    }

    /**
     * Get the case
     *
     * @return \Olcs\Db\Entity\Cases
     */
    public function getCase()
    {
        return $this->case;
    }

    /**
     * Set the check date
     *
     * @param \DateTime $checkDate
     * @return SeriousInfringement
     */
    public function setCheckDate($checkDate)
    {
        $this->checkDate = $checkDate;

        return $this;
    }

    /**
     * Get the check date
     *
     * @return \DateTime
     */
    public function getCheckDate()
    {
        return $this->checkDate;
    }

    /**
     * Set the erru response sent
     *
     * @param string $erruResponseSent
     * @return SeriousInfringement
     */
    public function setErruResponseSent($erruResponseSent)
    {
        $this->erruResponseSent = $erruResponseSent;

        return $this;
    }

    /**
     * Get the erru response sent
     *
     * @return string
     */
    public function getErruResponseSent()
    {
        return $this->erruResponseSent;
    }

    /**
     * Set the erru response time
     *
     * @param \DateTime $erruResponseTime
     * @return SeriousInfringement
     */
    public function setErruResponseTime($erruResponseTime)
    {
        $this->erruResponseTime = $erruResponseTime;

        return $this;
    }

    /**
     * Get the erru response time
     *
     * @return \DateTime
     */
    public function getErruResponseTime()
    {
        return $this->erruResponseTime;
    }

    /**
     * Set the erru response user
     *
     * @param \Olcs\Db\Entity\User $erruResponseUser
     * @return SeriousInfringement
     */
    public function setErruResponseUser($erruResponseUser)
    {
        $this->erruResponseUser = $erruResponseUser;

        return $this;
    }

    /**
     * Get the erru response user
     *
     * @return \Olcs\Db\Entity\User
     */
    public function getErruResponseUser()
    {
        return $this->erruResponseUser;
    }

    /**
     * Set the infringement date
     *
     * @param \DateTime $infringementDate
     * @return SeriousInfringement
     */
    public function setInfringementDate($infringementDate)
    {
        $this->infringementDate = $infringementDate;

        return $this;
    }

    /**
     * Get the infringement date
     *
     * @return \DateTime
     */
    public function getInfringementDate()
    {
        return $this->infringementDate;
    }

    /**
     * Set the member state code
     *
     * @param \Olcs\Db\Entity\Country $memberStateCode
     * @return SeriousInfringement
     */
    public function setMemberStateCode($memberStateCode)
    {
        $this->memberStateCode = $memberStateCode;

        return $this;
    }

    /**
     * Get the member state code
     *
     * @return \Olcs\Db\Entity\Country
     */
    public function getMemberStateCode()
    {
        return $this->memberStateCode;
    }

    /**
     * Set the notification number
     *
     * @param string $notificationNumber
     * @return SeriousInfringement
     */
    public function setNotificationNumber($notificationNumber)
    {
        $this->notificationNumber = $notificationNumber;

        return $this;
    }

    /**
     * Get the notification number
     *
     * @return string
     */
    public function getNotificationNumber()
    {
        return $this->notificationNumber;
    }

    /**
     * Set the olbs type
     *
     * @param string $olbsType
     * @return SeriousInfringement
     */
    public function setOlbsType($olbsType)
    {
        $this->olbsType = $olbsType;

        return $this;
    }

    /**
     * Get the olbs type
     *
     * @return string
     */
    public function getOlbsType()
    {
        return $this->olbsType;
    }

    /**
     * Set the reason
     *
     * @param string $reason
     * @return SeriousInfringement
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get the reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set the si category type
     *
     * @param \Olcs\Db\Entity\SiCategoryType $siCategoryType
     * @return SeriousInfringement
     */
    public function setSiCategoryType($siCategoryType)
    {
        $this->siCategoryType = $siCategoryType;

        return $this;
    }

    /**
     * Get the si category type
     *
     * @return \Olcs\Db\Entity\SiCategoryType
     */
    public function getSiCategoryType()
    {
        return $this->siCategoryType;
    }

    /**
     * Set the applied penaltie
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $appliedPenalties
     * @return SeriousInfringement
     */
    public function setAppliedPenalties($appliedPenalties)
    {
        $this->appliedPenalties = $appliedPenalties;

        return $this;
    }

    /**
     * Get the applied penalties
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAppliedPenalties()
    {
        return $this->appliedPenalties;
    }

    /**
     * Add a applied penalties
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $appliedPenalties
     * @return SeriousInfringement
     */
    public function addAppliedPenalties($appliedPenalties)
    {
        if ($appliedPenalties instanceof ArrayCollection) {
            $this->appliedPenalties = new ArrayCollection(
                array_merge(
                    $this->appliedPenalties->toArray(),
                    $appliedPenalties->toArray()
                )
            );
        } elseif (!$this->appliedPenalties->contains($appliedPenalties)) {
            $this->appliedPenalties->add($appliedPenalties);
        }

        return $this;
    }

    /**
     * Remove a applied penalties
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $appliedPenalties
     * @return SeriousInfringement
     */
    public function removeAppliedPenalties($appliedPenalties)
    {
        if ($this->appliedPenalties->contains($appliedPenalties)) {
            $this->appliedPenalties->removeElement($appliedPenalties);
        }

        return $this;
    }

    /**
     * Set the imposed erru
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $imposedErrus
     * @return SeriousInfringement
     */
    public function setImposedErrus($imposedErrus)
    {
        $this->imposedErrus = $imposedErrus;

        return $this;
    }

    /**
     * Get the imposed errus
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getImposedErrus()
    {
        return $this->imposedErrus;
    }

    /**
     * Add a imposed errus
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $imposedErrus
     * @return SeriousInfringement
     */
    public function addImposedErrus($imposedErrus)
    {
        if ($imposedErrus instanceof ArrayCollection) {
            $this->imposedErrus = new ArrayCollection(
                array_merge(
                    $this->imposedErrus->toArray(),
                    $imposedErrus->toArray()
                )
            );
        } elseif (!$this->imposedErrus->contains($imposedErrus)) {
            $this->imposedErrus->add($imposedErrus);
        }

        return $this;
    }

    /**
     * Remove a imposed errus
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $imposedErrus
     * @return SeriousInfringement
     */
    public function removeImposedErrus($imposedErrus)
    {
        if ($this->imposedErrus->contains($imposedErrus)) {
            $this->imposedErrus->removeElement($imposedErrus);
        }

        return $this;
    }

    /**
     * Set the requested erru
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $requestedErrus
     * @return SeriousInfringement
     */
    public function setRequestedErrus($requestedErrus)
    {
        $this->requestedErrus = $requestedErrus;

        return $this;
    }

    /**
     * Get the requested errus
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRequestedErrus()
    {
        return $this->requestedErrus;
    }

    /**
     * Add a requested errus
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $requestedErrus
     * @return SeriousInfringement
     */
    public function addRequestedErrus($requestedErrus)
    {
        if ($requestedErrus instanceof ArrayCollection) {
            $this->requestedErrus = new ArrayCollection(
                array_merge(
                    $this->requestedErrus->toArray(),
                    $requestedErrus->toArray()
                )
            );
        } elseif (!$this->requestedErrus->contains($requestedErrus)) {
            $this->requestedErrus->add($requestedErrus);
        }

        return $this;
    }

    /**
     * Remove a requested errus
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $requestedErrus
     * @return SeriousInfringement
     */
    public function removeRequestedErrus($requestedErrus)
    {
        if ($this->requestedErrus->contains($requestedErrus)) {
            $this->requestedErrus->removeElement($requestedErrus);
        }

        return $this;
    }
}
