<?php

/**
 * ContactDetails
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\Repository;

use Dvsa\Olcs\Api\Entity\ContactDetails\ContactDetails as Entity;
use Dvsa\Olcs\Api\Entity\ContactDetails\Country as CountryEntity;
use Dvsa\Olcs\Transfer\Query\QueryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * ContactDetails
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class ContactDetails extends AbstractRepository
{
    protected $entity = Entity::class;

    /**
     * @param array $contactParams Array of data as defined by Dvsa\Olcs\Transfer\Command\Partial\ContactDetails
     * @return array
     */
    public function populateRefDataReference(array $contactParams)
    {
        if (!empty($contactParams['address']['countryCode'])) {
            $contactParams['address']['countryCode'] = $this->getReference(
                CountryEntity::class, $contactParams['address']['countryCode']
            );
        }

        if (!empty($contactParams['phoneContacts'])) {
            foreach ($contactParams['phoneContacts'] as $i => $phoneContact) {
                $contactParams['phoneContacts'][$i]['phoneContactType']
                    = $this->getRefdataReference($phoneContact['phoneContactType']);
            }
        }

        if (!empty($contactParams['person']['title'])) {
            $contactParams['person']['title'] = $this->getRefdataReference(
                $contactParams['person']['title']
            );
        }

        return $contactParams;
    }

    /**
     * @param array $contactParams Array of data as defined by Dvsa\Olcs\Transfer\Command\Partial\OperatorContactDetails
     * @return array
     */
    public function populateOperatorRefDataReferences(array $contactParams)
    {
        if (!empty($contactParams['address']['countryCode'])) {
            $contactParams['address']['countryCode'] = $this->getReference(
                CountryEntity::class, $contactParams['address']['countryCode']
            );
        }

        $phoneContacts = [
            'businessPhoneContact',
            'homePhoneContact',
            'mobilePhoneContact',
            'faxPhoneContact',
        ];
        foreach ($phoneContacts as $property) {
            if (!empty($contactParams[$property])) {
                $contactParams[$property]['phoneContactType']
                    = $this->getRefdataReference($contactParams[$property]['phoneContactType']);
            }
        }

        return $contactParams;
    }

    /**
     * Filter list
     *
     * @param \Dvsa\Olcs\Api\Domain\Repository\QueryBuilder $qb
     * @param \Dvsa\Olcs\Api\Domain\Repository\QueryInterface $query
     */
    protected function applyListFilters(QueryBuilder $qb, QueryInterface $query)
    {
        if ($query->getContactType()) {
            $qb->andWhere($qb->expr()->eq($this->alias .'.contactType', ':contactType'))
                ->setParameter('contactType', $query->getContactType());
        }
    }
}
