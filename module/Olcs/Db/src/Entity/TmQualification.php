<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Olcs\Db\Entity\Traits;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TmQualification Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedDate", timeAware=true)
 * @ORM\Table(name="tm_qualification",
 *    indexes={
 *        @ORM\Index(name="fk_qualification_transport_manager1_idx", columns={"transport_manager_id"}),
 *        @ORM\Index(name="fk_qualification_country1_idx", columns={"country_code"}),
 *        @ORM\Index(name="fk_qualification_ref_data1_idx", columns={"qualification_type"}),
 *        @ORM\Index(name="fk_tm_qualification_user1_idx", columns={"created_by"}),
 *        @ORM\Index(name="fk_tm_qualification_user2_idx", columns={"last_modified_by"})
 *    }
 * )
 */
class TmQualification implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\CreatedByManyToOne,
        Traits\CustomCreatedOnField,
        Traits\CustomDeletedDateField,
        Traits\IdIdentity,
        Traits\LastModifiedByManyToOne,
        Traits\CustomLastModifiedOnField,
        Traits\CustomVersionField;

    /**
     * Country code
     *
     * @var \Olcs\Db\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\Country")
     * @ORM\JoinColumn(name="country_code", referencedColumnName="id", nullable=false)
     */
    protected $countryCode;

    /**
     * Issued date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="issued_date", nullable=true)
     */
    protected $issuedDate;

    /**
     * Qualification type
     *
     * @var \Olcs\Db\Entity\RefData
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\RefData")
     * @ORM\JoinColumn(name="qualification_type", referencedColumnName="id", nullable=false)
     */
    protected $qualificationType;

    /**
     * Serial no
     *
     * @var string
     *
     * @ORM\Column(type="string", name="serial_no", length=20, nullable=true)
     */
    protected $serialNo;

    /**
     * Transport manager
     *
     * @var \Olcs\Db\Entity\TransportManager
     *
     * @ORM\ManyToOne(targetEntity="Olcs\Db\Entity\TransportManager", inversedBy="qualifications")
     * @ORM\JoinColumn(name="transport_manager_id", referencedColumnName="id", nullable=false)
     */
    protected $transportManager;

    /**
     * Set the country code
     *
     * @param \Olcs\Db\Entity\Country $countryCode
     * @return TmQualification
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get the country code
     *
     * @return \Olcs\Db\Entity\Country
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set the issued date
     *
     * @param \DateTime $issuedDate
     * @return TmQualification
     */
    public function setIssuedDate($issuedDate)
    {
        $this->issuedDate = $issuedDate;

        return $this;
    }

    /**
     * Get the issued date
     *
     * @return \DateTime
     */
    public function getIssuedDate()
    {
        return $this->issuedDate;
    }

    /**
     * Set the qualification type
     *
     * @param \Olcs\Db\Entity\RefData $qualificationType
     * @return TmQualification
     */
    public function setQualificationType($qualificationType)
    {
        $this->qualificationType = $qualificationType;

        return $this;
    }

    /**
     * Get the qualification type
     *
     * @return \Olcs\Db\Entity\RefData
     */
    public function getQualificationType()
    {
        return $this->qualificationType;
    }

    /**
     * Set the serial no
     *
     * @param string $serialNo
     * @return TmQualification
     */
    public function setSerialNo($serialNo)
    {
        $this->serialNo = $serialNo;

        return $this;
    }

    /**
     * Get the serial no
     *
     * @return string
     */
    public function getSerialNo()
    {
        return $this->serialNo;
    }

    /**
     * Set the transport manager
     *
     * @param \Olcs\Db\Entity\TransportManager $transportManager
     * @return TmQualification
     */
    public function setTransportManager($transportManager)
    {
        $this->transportManager = $transportManager;

        return $this;
    }

    /**
     * Get the transport manager
     *
     * @return \Olcs\Db\Entity\TransportManager
     */
    public function getTransportManager()
    {
        return $this->transportManager;
    }
}
