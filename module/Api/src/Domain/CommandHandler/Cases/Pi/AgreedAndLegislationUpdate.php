<?php

/**
 * Updates Pi with agreed and legislation info
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler\Cases\Pi;

use Doctrine\Common\Collections\ArrayCollection;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Entity\System\RefData;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\Olcs\Api\Entity\Pi\PresidingTc as PresidingTcEntity;
use Dvsa\Olcs\Api\Entity\Pi\Pi as PiEntity;
use Dvsa\Olcs\Api\Entity\Pi\Reason as ReasonEntity;
use Dvsa\Olcs\Api\Domain\CommandHandler\TransactionedInterface;
use Dvsa\Olcs\Transfer\Command\Cases\Pi\UpdateAgreedAndLegislation as UpdateCmd;
use Doctrine\ORM\Query;

/**
 * Updates Pi with agreed and legislation info
 */
final class AgreedAndLegislationUpdate extends AbstractCommandHandler implements TransactionedInterface
{
    protected $repoServiceName = 'Pi';

    /**
     * Updates agreed and legislation on pi
     *
     * @param CommandInterface $command
     * @return Result
     */
    public function handleCommand(CommandInterface $command)
    {
        $result = new Result();

        /** @var UpdateCmd $command */
        $types = $this->processTypes($command->getPiTypes());
        $reasons = $this->processReasons($command->getReasons());

        /** @var PresidingTcEntity $agreedByTc */
        $agreedByTc = $this->getRepo()->getReference(PresidingTcEntity::class, $command->getAgreedByTc());

        /** @var RefData $decidedByTcRole */
        $decidedByTcRole = $this->getRepo()->getRefdataReference($command->getAgreedByTcRole());

        $agreedDate = \DateTime::createFromFormat('Y-m-d', $command->getAgreedDate());

        /** @var PiEntity $pi */
        $pi = $this->getRepo()->fetchUsingId($command, Query::HYDRATE_OBJECT, $command->getVersion());

        $pi->updateAgreedAndLegislation(
            $agreedByTc,
            $decidedByTcRole,
            $types,
            $reasons,
            $agreedDate,
            $command->getComment()
        );

        $this->getRepo()->save($pi);
        $result->addMessage('Pi updated');
        $result->addId('Pi', $pi->getId());

        return $result;
    }

    /**
     * Returns collection of reasons.
     *
     * @param array $reasons
     * @return ArrayCollection
     */
    private function processReasons($reasons)
    {
        $result = new ArrayCollection();

        if (!empty($reasons)) {
            foreach ($reasons as $reason) {
                $result->add($this->getRepo()->getReference(ReasonEntity::class, $reason));
            }
        }

        return $result;
    }

    /**
     * Returns collection of types.
     *
     * @param array $types
     * @return ArrayCollection
     */
    private function processTypes($types)
    {
        $result = new ArrayCollection();

        if (!empty($types)) {
            foreach ($types as $type) {
                $result->add($this->getRepo()->getRefdataReference($type));
            }
        }

        return $result;
    }
}
