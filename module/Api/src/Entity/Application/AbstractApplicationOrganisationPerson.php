<?php

namespace Dvsa\Olcs\Api\Entity\Application;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApplicationOrganisationPerson Abstract Entity
 *
 * Auto-Generated
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="application_organisation_person",
 *    indexes={
 *        @ORM\Index(name="ix_application_organisation_person_person_id", columns={"person_id"}),
 *        @ORM\Index(name="ix_application_organisation_person_organisation_id",
     *     columns={"organisation_id"}),
 *        @ORM\Index(name="ix_application_organisation_person_application_id",
     *     columns={"application_id"}),
 *        @ORM\Index(name="ix_application_organisation_person_last_modified_by",
     *     columns={"last_modified_by"}),
 *        @ORM\Index(name="ix_application_organisation_person_created_by", columns={"created_by"}),
 *        @ORM\Index(name="fk_application_organisation_person_person1_idx",
     *     columns={"original_person_id"})
 *    }
 * )
 */
abstract class AbstractApplicationOrganisationPerson
{

    /**
     * Action
     *
     * @var string
     *
     * @ORM\Column(type="string", name="action", length=1, nullable=false)
     */
    protected $action;

    /**
     * Application
     *
     * @var \Dvsa\Olcs\Api\Entity\Application\Application
     *
     * @ORM\ManyToOne(
     *     targetEntity="Dvsa\Olcs\Api\Entity\Application\Application",
     *     fetch="LAZY",
     *     inversedBy="applicationOrganisationPersons"
     * )
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=false)
     */
    protected $application;

    /**
     * Created by
     *
     * @var \Dvsa\Olcs\Api\Entity\User\User
     *
     * @ORM\ManyToOne(targetEntity="Dvsa\Olcs\Api\Entity\User\User", fetch="LAZY")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=true)
     */
    protected $createdBy;

    /**
     * Created on
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="created_on", nullable=true)
     */
    protected $createdOn;

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
     * Last modified by
     *
     * @var \Dvsa\Olcs\Api\Entity\User\User
     *
     * @ORM\ManyToOne(targetEntity="Dvsa\Olcs\Api\Entity\User\User", fetch="LAZY")
     * @ORM\JoinColumn(name="last_modified_by", referencedColumnName="id", nullable=true)
     */
    protected $lastModifiedBy;

    /**
     * Last modified on
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="last_modified_on", nullable=true)
     */
    protected $lastModifiedOn;

    /**
     * Organisation
     *
     * @var \Dvsa\Olcs\Api\Entity\Organisation\Organisation
     *
     * @ORM\ManyToOne(targetEntity="Dvsa\Olcs\Api\Entity\Organisation\Organisation", fetch="LAZY")
     * @ORM\JoinColumn(name="organisation_id", referencedColumnName="id", nullable=false)
     */
    protected $organisation;

    /**
     * Original person
     *
     * @var \Dvsa\Olcs\Api\Entity\Person\Person
     *
     * @ORM\ManyToOne(targetEntity="Dvsa\Olcs\Api\Entity\Person\Person", fetch="LAZY")
     * @ORM\JoinColumn(name="original_person_id", referencedColumnName="id", nullable=true)
     */
    protected $originalPerson;

    /**
     * Person
     *
     * @var \Dvsa\Olcs\Api\Entity\Person\Person
     *
     * @ORM\ManyToOne(targetEntity="Dvsa\Olcs\Api\Entity\Person\Person", fetch="LAZY")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=false)
     */
    protected $person;

    /**
     * Position
     *
     * @var string
     *
     * @ORM\Column(type="string", name="position", length=45, nullable=true)
     */
    protected $position;

    /**
     * Version
     *
     * @var int
     *
     * @ORM\Column(type="smallint", name="version", nullable=false, options={"default": 1})
     * @ORM\Version
     */
    protected $version = 1;

    /**
     * Set the action
     *
     * @param string $action
     * @return ApplicationOrganisationPerson
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get the action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set the application
     *
     * @param \Dvsa\Olcs\Api\Entity\Application\Application $application
     * @return ApplicationOrganisationPerson
     */
    public function setApplication($application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get the application
     *
     * @return \Dvsa\Olcs\Api\Entity\Application\Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set the created by
     *
     * @param \Dvsa\Olcs\Api\Entity\User\User $createdBy
     * @return ApplicationOrganisationPerson
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get the created by
     *
     * @return \Dvsa\Olcs\Api\Entity\User\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set the created on
     *
     * @param \DateTime $createdOn
     * @return ApplicationOrganisationPerson
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get the created on
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set the id
     *
     * @param int $id
     * @return ApplicationOrganisationPerson
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
     * Set the last modified by
     *
     * @param \Dvsa\Olcs\Api\Entity\User\User $lastModifiedBy
     * @return ApplicationOrganisationPerson
     */
    public function setLastModifiedBy($lastModifiedBy)
    {
        $this->lastModifiedBy = $lastModifiedBy;

        return $this;
    }

    /**
     * Get the last modified by
     *
     * @return \Dvsa\Olcs\Api\Entity\User\User
     */
    public function getLastModifiedBy()
    {
        return $this->lastModifiedBy;
    }

    /**
     * Set the last modified on
     *
     * @param \DateTime $lastModifiedOn
     * @return ApplicationOrganisationPerson
     */
    public function setLastModifiedOn($lastModifiedOn)
    {
        $this->lastModifiedOn = $lastModifiedOn;

        return $this;
    }

    /**
     * Get the last modified on
     *
     * @return \DateTime
     */
    public function getLastModifiedOn()
    {
        return $this->lastModifiedOn;
    }

    /**
     * Set the organisation
     *
     * @param \Dvsa\Olcs\Api\Entity\Organisation\Organisation $organisation
     * @return ApplicationOrganisationPerson
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;

        return $this;
    }

    /**
     * Get the organisation
     *
     * @return \Dvsa\Olcs\Api\Entity\Organisation\Organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * Set the original person
     *
     * @param \Dvsa\Olcs\Api\Entity\Person\Person $originalPerson
     * @return ApplicationOrganisationPerson
     */
    public function setOriginalPerson($originalPerson)
    {
        $this->originalPerson = $originalPerson;

        return $this;
    }

    /**
     * Get the original person
     *
     * @return \Dvsa\Olcs\Api\Entity\Person\Person
     */
    public function getOriginalPerson()
    {
        return $this->originalPerson;
    }

    /**
     * Set the person
     *
     * @param \Dvsa\Olcs\Api\Entity\Person\Person $person
     * @return ApplicationOrganisationPerson
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get the person
     *
     * @return \Dvsa\Olcs\Api\Entity\Person\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set the position
     *
     * @param string $position
     * @return ApplicationOrganisationPerson
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
     * Set the version
     *
     * @param int $version
     * @return ApplicationOrganisationPerson
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get the version
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set the createdOn field on persist
     *
     * @ORM\PrePersist
     */
    public function setCreatedOnBeforePersist()
    {
        $this->createdOn = new \DateTime();
    }

    /**
     * Set the lastModifiedOn field on persist
     *
     * @ORM\PreUpdate
     */
    public function setLastModifiedOnBeforeUpdate()
    {
        $this->lastModifiedOn = new \DateTime();
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
