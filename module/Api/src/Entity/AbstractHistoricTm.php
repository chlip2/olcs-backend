<?php

namespace Dvsa\Olcs\Api\Entity;

use Dvsa\Olcs\Api\Domain\QueryHandler\BundleSerializableInterface;
use JsonSerializable;
use Dvsa\Olcs\Api\Entity\Traits\BundleSerializableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricTm Abstract Entity
 *
 * Auto-Generated
 *
 * @ORM\MappedSuperclass
 * @ORM\Table(name="historic_tm",
 *    indexes={
 *        @ORM\Index(name="ix_historic_tm_historic_id", columns={"historic_id"}),
 *        @ORM\Index(name="ix_historic_tm_forename", columns={"forename"}),
 *        @ORM\Index(name="ix_historic_tm_family_name", columns={"family_name"}),
 *        @ORM\Index(name="ix_historic_tm_lic_no", columns={"lic_no"}),
 *        @ORM\Index(name="ix_historic_tm_birth_date", columns={"birth_date"})
 *    }
 * )
 */
abstract class AbstractHistoricTm implements BundleSerializableInterface, JsonSerializable
{
    use BundleSerializableTrait;

    /**
     * Application id
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="application_id", nullable=true)
     */
    protected $applicationId;

    /**
     * Birth date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="birth_date", nullable=true)
     */
    protected $birthDate;

    /**
     * Certificate no
     *
     * @var string
     *
     * @ORM\Column(type="string", name="certificate_no", length=45, nullable=true)
     */
    protected $certificateNo;

    /**
     * Date added
     *
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="date_added", nullable=true)
     */
    protected $dateAdded;

    /**
     * Date removed
     *
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="date_removed", nullable=true)
     */
    protected $dateRemoved;

    /**
     * Family name
     *
     * @var string
     *
     * @ORM\Column(type="string", name="family_name", length=45, nullable=true)
     */
    protected $familyName;

    /**
     * Forename
     *
     * @var string
     *
     * @ORM\Column(type="string", name="forename", length=45, nullable=true)
     */
    protected $forename;

    /**
     * Historic id
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="historic_id", nullable=false)
     */
    protected $historicId;

    /**
     * Hours per week
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="hours_per_week", nullable=true)
     */
    protected $hoursPerWeek;

    /**
     * Identifier - Id
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Lic no
     *
     * @var string
     *
     * @ORM\Column(type="string", name="lic_no", length=10, nullable=true)
     */
    protected $licNo;

    /**
     * Lic or app
     *
     * @var string
     *
     * @ORM\Column(type="string", name="lic_or_app", length=1, nullable=true)
     */
    protected $licOrApp;

    /**
     * Qualification type
     *
     * @var string
     *
     * @ORM\Column(type="string", name="qualification_type", length=45, nullable=true)
     */
    protected $qualificationType;

    /**
     * Seen contract
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="seen_contract", nullable=false)
     */
    protected $seenContract;

    /**
     * Seen qualification
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="seen_qualification", nullable=false)
     */
    protected $seenQualification;

    /**
     * Set the application id
     *
     * @param int $applicationId
     * @return HistoricTm
     */
    public function setApplicationId($applicationId)
    {
        $this->applicationId = $applicationId;

        return $this;
    }

    /**
     * Get the application id
     *
     * @return int
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }

    /**
     * Set the birth date
     *
     * @param \DateTime $birthDate
     * @return HistoricTm
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get the birth date
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set the certificate no
     *
     * @param string $certificateNo
     * @return HistoricTm
     */
    public function setCertificateNo($certificateNo)
    {
        $this->certificateNo = $certificateNo;

        return $this;
    }

    /**
     * Get the certificate no
     *
     * @return string
     */
    public function getCertificateNo()
    {
        return $this->certificateNo;
    }

    /**
     * Set the date added
     *
     * @param \DateTime $dateAdded
     * @return HistoricTm
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get the date added
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set the date removed
     *
     * @param \DateTime $dateRemoved
     * @return HistoricTm
     */
    public function setDateRemoved($dateRemoved)
    {
        $this->dateRemoved = $dateRemoved;

        return $this;
    }

    /**
     * Get the date removed
     *
     * @return \DateTime
     */
    public function getDateRemoved()
    {
        return $this->dateRemoved;
    }

    /**
     * Set the family name
     *
     * @param string $familyName
     * @return HistoricTm
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;

        return $this;
    }

    /**
     * Get the family name
     *
     * @return string
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * Set the forename
     *
     * @param string $forename
     * @return HistoricTm
     */
    public function setForename($forename)
    {
        $this->forename = $forename;

        return $this;
    }

    /**
     * Get the forename
     *
     * @return string
     */
    public function getForename()
    {
        return $this->forename;
    }

    /**
     * Set the historic id
     *
     * @param int $historicId
     * @return HistoricTm
     */
    public function setHistoricId($historicId)
    {
        $this->historicId = $historicId;

        return $this;
    }

    /**
     * Get the historic id
     *
     * @return int
     */
    public function getHistoricId()
    {
        return $this->historicId;
    }

    /**
     * Set the hours per week
     *
     * @param int $hoursPerWeek
     * @return HistoricTm
     */
    public function setHoursPerWeek($hoursPerWeek)
    {
        $this->hoursPerWeek = $hoursPerWeek;

        return $this;
    }

    /**
     * Get the hours per week
     *
     * @return int
     */
    public function getHoursPerWeek()
    {
        return $this->hoursPerWeek;
    }

    /**
     * Set the id
     *
     * @param int $id
     * @return HistoricTm
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the lic no
     *
     * @param string $licNo
     * @return HistoricTm
     */
    public function setLicNo($licNo)
    {
        $this->licNo = $licNo;

        return $this;
    }

    /**
     * Get the lic no
     *
     * @return string
     */
    public function getLicNo()
    {
        return $this->licNo;
    }

    /**
     * Set the lic or app
     *
     * @param string $licOrApp
     * @return HistoricTm
     */
    public function setLicOrApp($licOrApp)
    {
        $this->licOrApp = $licOrApp;

        return $this;
    }

    /**
     * Get the lic or app
     *
     * @return string
     */
    public function getLicOrApp()
    {
        return $this->licOrApp;
    }

    /**
     * Set the qualification type
     *
     * @param string $qualificationType
     * @return HistoricTm
     */
    public function setQualificationType($qualificationType)
    {
        $this->qualificationType = $qualificationType;

        return $this;
    }

    /**
     * Get the qualification type
     *
     * @return string
     */
    public function getQualificationType()
    {
        return $this->qualificationType;
    }

    /**
     * Set the seen contract
     *
     * @param boolean $seenContract
     * @return HistoricTm
     */
    public function setSeenContract($seenContract)
    {
        $this->seenContract = $seenContract;

        return $this;
    }

    /**
     * Get the seen contract
     *
     * @return boolean
     */
    public function getSeenContract()
    {
        return $this->seenContract;
    }

    /**
     * Set the seen qualification
     *
     * @param boolean $seenQualification
     * @return HistoricTm
     */
    public function setSeenQualification($seenQualification)
    {
        $this->seenQualification = $seenQualification;

        return $this;
    }

    /**
     * Get the seen qualification
     *
     * @return boolean
     */
    public function getSeenQualification()
    {
        return $this->seenQualification;
    }



    /**
     * Clear properties
     *
     * @param type $properties
     */
    public function clearProperties($properties = array())
    {
        foreach ($properties as $property) {

            if (property_exists($this, $property)) {
                if ($this->$property instanceof Collection) {

                    $this->$property = new ArrayCollection(array());

                } else {

                    $this->$property = null;
                }
            }
        }
    }
}