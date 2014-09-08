<?php

namespace Olcs\Db\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description70 field trait
 *
 * Auto-Generated (Shared between 2 entities)
 */
trait Description70Field
{
    /**
     * Description
     *
     * @var string
     *
     * @ORM\Column(type="string", name="description", length=70, nullable=true)
     */
    protected $description;

    /**
     * Set the description
     *
     * @param string $description
     * @return \Olcs\Db\Entity\Interfaces\EntityInterface
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

}
