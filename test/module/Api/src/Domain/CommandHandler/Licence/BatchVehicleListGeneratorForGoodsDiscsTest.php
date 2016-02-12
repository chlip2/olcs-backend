<?php

/**
 * BatchVehicleListGeneratorForGoodsDiscs test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Licence;

use Dvsa\Olcs\Transfer\Command\Licence\CreateVehicleListDocument as CreateVehicleListDocumentCommand;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Mockery as m;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;
use Dvsa\Olcs\Api\Domain\CommandHandler\Licence\BatchVehicleListGeneratorForGoodsDiscs as Batch;
use Dvsa\Olcs\Api\Domain\Command\Licence\BatchVehicleListGeneratorForGoodsDiscs as Cmd;
use Dvsa\Olcs\Api\Domain\Command\Queue\Create as CreatQueue;
use Dvsa\Olcs\Api\Entity\Queue\Queue;

/**
 * BatchVehicleListGeneratorForGoodsDiscs test
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class BatchVehicleListGeneratorForGoodsDiscsTest extends CommandHandlerTestCase
{
    public function setUp()
    {
        $this->sut = new Batch();

        parent::setUp();
    }

    public function testHandleCommand()
    {
        $licences = $this->getLicences();
        $data = [
            'licences' => $licences
        ];
        $queuedLicences = array_slice($licences, Batch::BATCH_SIZE);
        $options = [
            'licences' => $queuedLicences,
        ];

        $command = Cmd::create($data);
        $expected = ['id' => []];

        for ($i = 0; $i < Batch::BATCH_SIZE; $i++) {
            $data = [
                'id' =>  $i,
            ];
            $this->expectedSideEffect(CreateVehicleListDocumentCommand::class, $data, new Result());
            $expected['messages'][] = 'Vehicle list generated for licence ' . $i;
        }

        $data = [
            'type' => Queue::TYPE_CREATE_GOODS_VEHICLE_LIST,
            'status' => Queue::STATUS_QUEUED,
            'options' => json_encode($options)
        ];
        $this->expectedSideEffect(CreatQueue::class, $data, new Result());

        $result = $this->sut->handleCommand($command);
        $this->assertEquals($expected, $result->toArray());
    }

    protected function getLicences()
    {
        $licences = [];
        for ($i = 0; $i < Batch::BATCH_SIZE + 2; $i++) {
            $licences[] = ['id' => $i];
        }
        return $licences;
    }
}