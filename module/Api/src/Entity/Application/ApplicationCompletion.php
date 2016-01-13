<?php

namespace Dvsa\Olcs\Api\Entity\Application;

use Doctrine\ORM\Mapping as ORM;
use Zend\Filter\Word\UnderscoreToCamelCase;

/**
 * ApplicationCompletion Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="application_completion",
 *    indexes={
 *        @ORM\Index(name="ix_application_completion_created_by", columns={"created_by"}),
 *        @ORM\Index(name="ix_application_completion_last_modified_by", columns={"last_modified_by"}),
 *        @ORM\Index(name="ix_application_completion_application_id", columns={"application_id"})
 *    },
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="uk_application_completion_application_id", columns={"application_id"})
 *    }
 * )
 */
class ApplicationCompletion extends AbstractApplicationCompletion
{
    const STATUS_NOT_STARTED = 0;
    const STATUS_INCOMPLETE = 1;
    const STATUS_COMPLETE = 2;

    public function __construct(Application $application)
    {
        $this->setApplication($application);
    }

    protected function getCalculatedValues()
    {
        return ['application' => null];
    }

    public function isComplete($required)
    {
        return count($this->getIncompleteSections($required)) < 1;
    }

    public function getIncompleteSections($required)
    {
        $incompleteSections = [];

        $filter = new UnderscoreToCamelCase();

        foreach ($required as $section) {
            $getter = 'get' . ucfirst($filter->filter($section)) . 'Status';
            if ($this->$getter() !== self::STATUS_COMPLETE) {
                $incompleteSections[] = $section;
            }
        }

        return $incompleteSections;
    }
}
