<?php

namespace Olcs\Db\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Olcs\Db\Entity\Traits;

/**
 * LegacyOffence Entity
 *
 * Auto-Generated
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="legacy_offence",
 *    indexes={
 *        @ORM\Index(name="fk_legacy_offence_user1_idx", columns={"created_by"}),
 *        @ORM\Index(name="fk_legacy_offence_user2_idx", columns={"last_modified_by"})
 *    }
 * )
 */
class LegacyOffence implements Interfaces\EntityInterface
{
    use Traits\CustomBaseEntity,
        Traits\IdIdentity,
        Traits\CreatedByManyToOne,
        Traits\LastModifiedByManyToOne,
        Traits\Notes4000Field,
        Traits\Vrm20Field,
        Traits\CustomCreatedOnField,
        Traits\CustomLastModifiedOnField,
        Traits\CustomVersionField;

    /**
     * Case
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Olcs\Db\Entity\Cases", mappedBy="legacyOffences")
     */
    protected $cases;

    /**
     * Definition
     *
     * @var string
     *
     * @ORM\Column(type="string", name="definition", length=255, nullable=true)
     */
    protected $definition;

    /**
     * Is trailer
     *
     * @var boolean
     *
     * @ORM\Column(type="yesnonull", name="is_trailer", nullable=true)
     */
    protected $isTrailer;

    /**
     * Num of offences
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="num_of_offences", nullable=true)
     */
    protected $numOfOffences;

    /**
     * Offence authority
     *
     * @var string
     *
     * @ORM\Column(type="string", name="offence_authority", length=100, nullable=true)
     */
    protected $offenceAuthority;

    /**
     * Offence date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="offence_date", nullable=true)
     */
    protected $offenceDate;

    /**
     * Offence to date
     *
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="offence_to_date", nullable=true)
     */
    protected $offenceToDate;

    /**
     * Offender name
     *
     * @var string
     *
     * @ORM\Column(type="string", name="offender_name", length=100, nullable=true)
     */
    protected $offenderName;

    /**
     * Points
     *
     * @var int
     *
     * @ORM\Column(type="integer", name="points", nullable=true)
     */
    protected $points;

    /**
     * Position
     *
     * @var string
     *
     * @ORM\Column(type="string", name="position", length=100, nullable=true)
     */
    protected $position;

    /**
     * Offence type
     *
     * @var string
     *
     * @ORM\Column(type="string", name="offence_type", length=100, nullable=true)
     */
    protected $offenceType;

    /**
     * Initialise the collections
     */
    public function __construct()
    {
        $this->cases = new ArrayCollection();
    }

    /**
     * Set the case
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $cases

     * @return \Olcs\Db\Entity\LegacyOffence
     */
    public function setCases($cases)
    {
        $this->cases = $cases;

        return $this;
    }

    /**
     * Get the case
     *
     * @return \Doctrine\Common\Collections\ArrayCollection

     */
    public function getCases()
    {
        return $this->cases;
    }

    /**
     * Set the definition
     *
     * @param string $definition
     * @return \Olcs\Db\Entity\LegacyOffence
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * Get the definition
     *
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Set the is trailer
     *
     * @param boolean $isTrailer
     * @return \Olcs\Db\Entity\LegacyOffence
     */
    public function setIsTrailer($isTrailer)
    {
        $this->isTrailer = $isTrailer;

        return $this;
    }

    /**
     * Get the is trailer
     *
     * @return boolean
     */
    public function getIsTrailer()
    {
        return $this->isTrailer;
    }

    /**
     * Set the num of offences
     *
     * @param int $numOfOffences
     * @return \Olcs\Db\Entity\LegacyOffence
     */
    public function setNumOfOffences($numOfOffences)
    {
        $this->numOfOffences = $numOfOffences;

        return $this;
    }

    /**
     * Get the num of offences
     *
     * @return int
     */
    public function getNumOfOffences()
    {
        return $this->numOfOffences;
    }

    /**
     * Set the offence authority
     *
     * @param string $offenceAuthority
     * @return \Olcs\Db\Entity\LegacyOffence
     */
    public function setOffenceAuthority($offenceAuthority)
    {
        $this->offenceAuthority = $offenceAuthority;

        return $this;
    }

    /**
     * Get the offence authority
     *
     * @return string
     */
    public function getOffenceAuthority()
    {
        return $this->offenceAuthority;
    }

    /**
     * Set the offence date
     *
     * @param \DateTime $offenceDate
     * @return \Olcs\Db\Entity\LegacyOffence
     */
    public function setOffenceDate($offenceDate)
    {
        $this->offenceDate = $offenceDate;

        return $this;
    }

    /**
     * Get the offence date
     *
     * @return \DateTime
     */
    public function getOffenceDate()
    {
        return $this->offenceDate;
    }

    /**
     * Set the offence to date
     *
     * @param \DateTime $offenceToDate
     * @return \Olcs\Db\Entity\LegacyOffence
     */
    public function setOffenceToDate($offenceToDate)
    {
        $this->offenceToDate = $offenceToDate;

        return $this;
    }

    /**
     * Get the offence to date
     *
     * @return \DateTime
     */
    public function getOffenceToDate()
    {
        return $this->offenceToDate;
    }

    /**
     * Set the offender name
     *
     * @param string $offenderName
     * @return \Olcs\Db\Entity\LegacyOffence
     */
    public function setOffenderName($offenderName)
    {
        $this->offenderName = $offenderName;

        return $this;
    }

    /**
     * Get the offender name
     *
     * @return string
     */
    public function getOffenderName()
    {
        return $this->offenderName;
    }

    /**
     * Set the points
     *
     * @param int $points
     * @return \Olcs\Db\Entity\LegacyOffence
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get the points
     *
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set the position
     *
     * @param string $position
     * @return \Olcs\Db\Entity\LegacyOffence
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get the position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set the offence type
     *
     * @param string $offenceType
     * @return \Olcs\Db\Entity\LegacyOffence
     */
    public function setOffenceType($offenceType)
    {
        $this->offenceType = $offenceType;

        return $this;
    }

    /**
     * Get the offence type
     *
     * @return string
     */
    public function getOffenceType()
    {
        return $this->offenceType;
    }
}
