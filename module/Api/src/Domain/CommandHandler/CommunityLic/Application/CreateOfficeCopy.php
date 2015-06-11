<?php

/**
 * Create Office Copy / Application Version
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler\CommunityLic\Application;

use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Entity\Application\Application as ApplicationEntity;
use Dvsa\Olcs\Api\Entity\Licence\Licence as LicenceEntity;
use Dvsa\Olcs\Api\Entity\CommunityLic\CommunityLic as CommunityLicEntity;
use Dvsa\Olcs\Api\Domain\Command\CommunityLic\GenerateBatch as GenerateBatchCommand;

/**
 * Create Office Copy / Application Version
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
final class CreateOfficeCopy extends AbstractCommandHandler
{
    protected $repoServiceName = 'CommunityLic';
    protected $extraRepos = ['Licence', 'Application'];

    public function handleCommand(CommandInterface $command)
    {
        $result = new Result();
        $licenceId = $command->getLicence();
        $identifier = $command->getIdentifier();

        $interimStatus = $this->getRepo('Application')->getInterimStatus($identifier);

        if ($interimStatus !== ApplicationEntity::INTERIM_STATUS_INFORCE) {
            $data = [
                'status' => $this->getRepo()->getRefdataReference(CommunityLicEntity::STATUS_PENDING),
            ];
        } else {
            $data = [
                'status' => $this->getRepo()->getRefdataReference(CommunityLicEntity::STATUS_ACTIVE),                
                'specifiedDate' => new \DateTime()
                // @TODO: need such a helper on backend
                // $this->getCommandHandler()->getServiceLocator()->get('Helper\Date')->getDate()
            ];
        }
        
        $data['serialNoPrefix'] = $this->getRepo('Licence')->getSerialNoPrefixFromTrafficArea($licenceId);
        $data['licence'] = $this->getRepo()->getReference(LicenceEntity::class, $licenceId);
        $data['issueNo'] = 0;
            
        $communityLic = $this->createCommunityLicObject($data);            
        $this->getRepo()->save($communityLic);
        $result->addId('communityLic' . $communityLic->getId(), $communityLic->getId());
        $result->addMessage('Office copy created successfully');

        $sideEffects = $this->determineSideEffects($interimStatus, $licenceId, $identifier, $communityLic->getId());
        foreach ($sideEffects as $sideEffect) {
            $result->merge($this->getCommandHandler()->handleCommand($sideEffect));
        }

        return $result;
    }

    /**
     * @param array $data
     * @return CommunityLic
     */    
    private function createCommunityLicObject($data)
    {
        $communityLic = new CommunityLicEntity();
        $communityLic->updateCommunityLic($data);
        return $communityLic;
    }

    private function determineSideEffects($interimStatus, $licenceId, $applicationId, $communityLicenceId)
    {
        $sideEffects = [];

        if ($interimStatus === ApplicationEntity::INTERIM_STATUS_INFORCE) {
            $sideEffects[] = $this->createGenerateBatchCommand($licenceId, $applicationId, [$communityLicenceId]);
        }

        return $sideEffects;
    }

    private function createGenerateBatchCommand($licenceId, $applicationId, $communityLicenceIds)
    {
        return GenerateBatchCommand::create(
            [
                'licence' => $licenceId,
                'application' => $applicationId,
                'communityLicenceIds' => $communityLicenceIds
            ]
        );
    }
}