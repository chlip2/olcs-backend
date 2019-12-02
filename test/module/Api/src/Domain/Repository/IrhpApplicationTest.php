<?php

namespace Dvsa\OlcsTest\Api\Domain\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\QueryBuilder;
use Dvsa\Olcs\Api\Domain\Repository\IrhpApplication;
use Dvsa\Olcs\Api\Domain\Repository\Query\Permits\ExpireIrhpApplications as ExpireIrhpApplicationsQuery;
use Dvsa\Olcs\Api\Entity\IrhpInterface;
use Dvsa\Olcs\Api\Entity\Licence\Licence as LicenceEntity;
use Dvsa\Olcs\Api\Entity\Permits\IrhpApplication as Entity;
use Dvsa\Olcs\Api\Entity\Permits\IrhpCandidatePermit as IrhpCandidatePermitEntity;
use Dvsa\Olcs\Api\Entity\System\RefData;
use Mockery as m;

/**
 * Irhp Application test
 *
 * @author Scott Callaway <scott.callaway@capgemini.com>
 */
class IrhpApplicationTest extends RepositoryTestCase
{
    public function setUp()
    {
        $this->setUpSut(IrhpApplication::class);
    }

    public function testFetchLicenceByOrganisation()
    {
        $expectedResult = [1, 7, 706];
        $organisation = 1;

        $queryBuilder = m::mock(QueryBuilder::class);
        $this->em->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);

        $queryBuilder->shouldReceive('select')
            ->with('l.id')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('from')
            ->with(LicenceEntity::class, 'l')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('where')
            ->with('l.organisation = ' . $organisation)
            ->andReturnSelf()
            ->shouldReceive('getQuery->execute')
            ->once()
            ->andReturn(
                [
                    [
                        'id' => 1,
                    ],
                    [
                        'id' => 7,
                    ],
                    [
                        'id' => 706,
                    ]
                ]
            );

        $this->assertEquals(
            $expectedResult,
            $this->sut->fetchLicenceByOrganisation($organisation)
        );
    }

    public function testFetchByWindowId()
    {
        $qb = $this->createMockQb('BLAH');

        $this->mockCreateQueryBuilder($qb);

        $qb->shouldReceive('getQuery')->andReturn(
            m::mock()->shouldReceive('execute')
                ->shouldReceive('getResult')
                ->andReturn(['RESULTS'])
                ->getMock()
        );
        $this->assertEquals(['RESULTS'], $this->sut->fetchByWindowId('ID', ['S1', 'S2']));

        $expectedQuery = 'BLAH '
            . 'INNER JOIN ia.irhpPermitApplications ipa '
            . 'INNER JOIN ipa.irhpPermitWindow ipw '
            . 'AND ipw.id = [[ID]] '
            . 'AND ia.status IN [[["S1","S2"]]]';

        $this->assertEquals($expectedQuery, $this->query);
    }

    public function testFetchAllAwaitingFee()
    {
        $queryBuilder = m::mock(QueryBuilder::class);
        $this->em->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);

        $irhpApplications = [
            m::mock(Entity::class),
            m::mock(Entity::class),
        ];

        $queryBuilder->shouldReceive('select')
            ->with('ia')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('from')
            ->with(Entity::class, 'ia')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('where')
            ->with('ia.status = :status')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with('status', IrhpInterface::STATUS_AWAITING_FEE)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('getQuery->getResult')
            ->once()
            ->andReturn($irhpApplications);

        $this->assertEquals(
            $irhpApplications,
            $this->sut->fetchAllAwaitingFee()
        );
    }

    public function testMarkAsExpired()
    {
        $this->expectQueryWithData(ExpireIrhpApplicationsQuery::class, []);
        $this->sut->markAsExpired();
    }

    public function testFetchApplicationIdsAwaitingScoring()
    {
        $stockId = 14;

        $statement = m::mock(Statement::class);
        $statement->shouldReceive('fetchAll')
            ->once()
            ->andReturn(
                [
                    [ 'id' => 14 ],
                    [ 'id' => 15 ],
                    [ 'id' => 16 ],
                ]
            );

        $connection = m::mock(Connection::class);
        $connection->shouldReceive('executeQuery')
            ->with(
                'select e.id from irhp_application e ' .
                'inner join licence as l on e.licence_id = l.id ' .
                'where e.id in (' .
                '    select irhp_application_id from irhp_permit_application where irhp_permit_window_id in (' .
                '        select id from irhp_permit_window where irhp_permit_stock_id = :stockId' .
                '    )' .
                ') ' .
                'and e.status = :status ' .
                'and l.licence_type in (:licenceType1, :licenceType2, :licenceType3) ' .
                'and l.status in (:licenceStatus1, :licenceStatus2, :licenceStatus3)',
                [
                    'stockId' => $stockId,
                    'status' => IrhpInterface::STATUS_UNDER_CONSIDERATION,
                    'licenceType1' => LicenceEntity::LICENCE_TYPE_RESTRICTED,
                    'licenceType2' => LicenceEntity::LICENCE_TYPE_STANDARD_INTERNATIONAL,
                    'licenceType3' => LicenceEntity::LICENCE_TYPE_STANDARD_NATIONAL,
                    'licenceStatus1' => LicenceEntity::LICENCE_STATUS_VALID,
                    'licenceStatus2' => LicenceEntity::LICENCE_STATUS_SUSPENDED,
                    'licenceStatus3' => LicenceEntity::LICENCE_STATUS_CURTAILED
                ]
            )
            ->once()
            ->andReturn($statement);

        $this->em->shouldReceive('getConnection')->once()->andReturn($connection);

        $this->assertEquals(
            [14, 15, 16],
            $this->sut->fetchApplicationIdsAwaitingScoring($stockId)
        );
    }

    public function testFetchInScopeUnderConsiderationApplicationIds()
    {
        $stockId = 14;

        $statement = m::mock(Statement::class);
        $statement->shouldReceive('fetchAll')
            ->once()
            ->andReturn(
                [
                    [ 'id' => 14 ],
                    [ 'id' => 15 ],
                    [ 'id' => 16 ],
                ]
            );

        $connection = m::mock(Connection::class);
        $connection->shouldReceive('executeQuery')
            ->with(
                'select e.id from irhp_application e ' .
                'where e.id in (' .
                '    select irhp_application_id from irhp_permit_application where irhp_permit_window_id in (' .
                '        select id from irhp_permit_window where irhp_permit_stock_id = :stockId' .
                '    )' .
                ') ' .
                'and e.in_scope = 1 ' .
                'and e.status = :status',
                [
                    'stockId' => $stockId,
                    'status' => IrhpInterface::STATUS_UNDER_CONSIDERATION
                ]
            )
            ->once()
            ->andReturn($statement);

        $this->em->shouldReceive('getConnection')->once()->andReturn($connection);

        $this->assertEquals(
            [14, 15, 16],
            $this->sut->fetchInScopeUnderConsiderationApplicationIds($stockId)
        );
    }

    public function testClearScope()
    {
        $stockId = 7;

        $statement = m::mock(Statement::class);
        $statement->shouldReceive('execute')
            ->once();

        $connection = m::mock(Connection::class);
        $connection->shouldReceive('executeQuery')
            ->with(
                'update irhp_application e ' .
                'set e.in_scope = 0 ' .
                'where e.id in (' .
                '    select irhp_application_id from irhp_permit_application where irhp_permit_window_id in (' .
                '        select id from irhp_permit_window where irhp_permit_stock_id = :stockId' .
                '    )' .
                ')',
                ['stockId' => $stockId]
            )
            ->once()
            ->andReturn($statement);

        $this->em->shouldReceive('getConnection')->once()->andReturn($connection);

        $this->sut->clearScope($stockId);
    }

    public function testApplyScope()
    {
        $stockId = 7;

        $statement = m::mock(Statement::class);
        $statement->shouldReceive('execute')
            ->once();

        $connection = m::mock(Connection::class);
        $connection->shouldReceive('executeQuery')
            ->with(
                'update irhp_application as e ' .
                'inner join licence as l on e.licence_id = l.id ' .
                'set e.in_scope = 1 ' .
                'where e.id in (' .
                '    select irhp_application_id from irhp_permit_application where irhp_permit_window_id in (' .
                '        select id from irhp_permit_window where irhp_permit_stock_id = :stockId' .
                '    )' .
                ') ' .
                'and e.status = :status ' .
                'and l.licence_type in (:licenceType1, :licenceType2, :licenceType3) ' .
                'and l.status in (:licenceStatus1, :licenceStatus2, :licenceStatus3)',
                [
                    'stockId' => $stockId,
                    'status' => IrhpInterface::STATUS_UNDER_CONSIDERATION,
                    'licenceType1' => LicenceEntity::LICENCE_TYPE_RESTRICTED,
                    'licenceType2' => LicenceEntity::LICENCE_TYPE_STANDARD_INTERNATIONAL,
                    'licenceType3' => LicenceEntity::LICENCE_TYPE_STANDARD_NATIONAL,
                    'licenceStatus1' => LicenceEntity::LICENCE_STATUS_VALID,
                    'licenceStatus2' => LicenceEntity::LICENCE_STATUS_SUSPENDED,
                    'licenceStatus3' => LicenceEntity::LICENCE_STATUS_CURTAILED
                ]
            )
            ->once()
            ->andReturn($statement);

        $this->em->shouldReceive('getConnection')->once()->andReturn($connection);

        $this->sut->applyScope($stockId);
    }

    public function testGetScoreOrderedBySectorInScope()
    {
        $stockId = 6;
        $sectorsId = 8;

        $result = [
            ['id' => 18, 'emissions_category' => RefData::EMISSIONS_CATEGORY_EURO5_REF],
            ['id' => 24, 'emissions_category' => RefData::EMISSIONS_CATEGORY_EURO6_REF],
            ['id' => 25, 'emissions_category' => RefData::EMISSIONS_CATEGORY_EURO5_REF],
            ['id' => 31, 'emissions_category' => RefData::EMISSIONS_CATEGORY_EURO6_REF],
            ['id' => 34, 'emissions_category' => RefData::EMISSIONS_CATEGORY_EURO5_REF],
        ];

        $queryBuilder = m::mock(QueryBuilder::class);
        $this->em->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);

        $queryBuilder->shouldReceive('select')
            ->with('icp.id, IDENTITY(icp.requestedEmissionsCategory) as emissions_category')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('from')
            ->with(IrhpCandidatePermitEntity::class, 'icp')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('icp.irhpPermitApplication', 'ipa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpPermitWindow', 'ipw')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpApplication', 'epa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('where')
            ->with('IDENTITY(ipw.irhpPermitStock) = ?1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('IDENTITY(epa.sectors) = ?2')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('epa.inScope = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('orderBy')
            ->with('icp.randomizedScore', 'DESC')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(1, $stockId)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(2, $sectorsId)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('getQuery->getScalarResult')
            ->once()
            ->andReturn($result);

        $this->assertEquals(
            $result,
            $this->sut->getScoreOrderedBySectorInScope($stockId, $sectorsId)
        );
    }

    public function testGetSuccessfulDaCountInScope()
    {
        $successfulDaCount = 35;
        $stockId = 8;
        $jurisdictionId = 12;

        $queryBuilder = m::mock(QueryBuilder::class);
        $this->em->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);

        $queryBuilder->shouldReceive('select')
            ->with('count(icp.id)')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('from')
            ->with(IrhpCandidatePermitEntity::class, 'icp')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('icp.irhpPermitApplication', 'ipa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpApplication', 'epa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpPermitWindow', 'ipw')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('epa.licence', 'l')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('where')
            ->with('IDENTITY(ipw.irhpPermitStock) = ?1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('icp.successful = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('IDENTITY(l.trafficArea) = ?2')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('epa.inScope = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(1, $stockId)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(2, $jurisdictionId)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('getQuery->getSingleScalarResult')
            ->once()
            ->andReturn($successfulDaCount);

        $this->assertEquals(
            $successfulDaCount,
            $this->sut->getSuccessfulDaCountInScope($stockId, $jurisdictionId)
        );
    }

    public function testGetUnsuccessfulScoreOrderedInScopeWithoutTrafficAreaId()
    {
        $result = [
            ['id' => 35, 'emissions_category' => RefData::EMISSIONS_CATEGORY_EURO5_REF],
            ['id' => 37, 'emissions_category' => RefData::EMISSIONS_CATEGORY_EURO6_REF],
            ['id' => 41, 'emissions_category' => RefData::EMISSIONS_CATEGORY_EURO5_REF]
        ];

        $stockId = 3;

        $queryBuilder = m::mock(QueryBuilder::class);
        $this->em->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);

        $queryBuilder->shouldReceive('select')
            ->with('icp.id, IDENTITY(icp.requestedEmissionsCategory) as emissions_category')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('from')
            ->with(IrhpCandidatePermitEntity::class, 'icp')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('icp.irhpPermitApplication', 'ipa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpPermitWindow', 'ipw')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpApplication', 'epa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('where')
            ->with('IDENTITY(ipw.irhpPermitStock) = ?1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('icp.successful = 0')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('epa.inScope = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('orderBy')
            ->with('icp.randomizedScore', 'DESC')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(1, $stockId)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('getQuery->getScalarResult')
            ->once()
            ->andReturn($result);

        $this->assertEquals(
            $result,
            $this->sut->getUnsuccessfulScoreOrderedInScope($stockId)
        );
    }

    public function testGetUnsuccessfulScoreOrderedInScopeWithTrafficAreaId()
    {
        $result = [
            ['id' => 35, 'emissions_category' => RefData::EMISSIONS_CATEGORY_EURO5_REF],
            ['id' => 37, 'emissions_category' => RefData::EMISSIONS_CATEGORY_EURO6_REF],
            ['id' => 41, 'emissions_category' => RefData::EMISSIONS_CATEGORY_EURO5_REF]
        ];

        $stockId = 3;
        $trafficAreaId = 12;

        $queryBuilder = m::mock(QueryBuilder::class);
        $this->em->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);

        $queryBuilder->shouldReceive('select')
            ->with('icp.id, IDENTITY(icp.requestedEmissionsCategory) as emissions_category')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('from')
            ->with(IrhpCandidatePermitEntity::class, 'icp')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('icp.irhpPermitApplication', 'ipa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpPermitWindow', 'ipw')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpApplication', 'epa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('where')
            ->with('IDENTITY(ipw.irhpPermitStock) = ?1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('icp.successful = 0')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('epa.inScope = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('orderBy')
            ->with('icp.randomizedScore', 'DESC')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(1, $stockId)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('epa.licence', 'l')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('IDENTITY(l.trafficArea) = ?2')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(2, $trafficAreaId)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('getQuery->getScalarResult')
            ->once()
            ->andReturn($result);

        $this->assertEquals(
            $result,
            $this->sut->getUnsuccessfulScoreOrderedInScope($stockId, $trafficAreaId)
        );
    }

    public function testGetSuccessfulCountInScopeWithoutEmissionsCategoryId()
    {
        $stockId = 7;
        $successfulCount = 15;

        $queryBuilder = m::mock(QueryBuilder::class);
        $this->em->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);

        $queryBuilder->shouldReceive('select')
            ->with('count(icp)')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('from')
            ->with(IrhpCandidatePermitEntity::class, 'icp')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('icp.irhpPermitApplication', 'ipa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpPermitWindow', 'ipw')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpApplication', 'epa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('where')
            ->with('IDENTITY(ipw.irhpPermitStock) = ?1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('icp.successful = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('epa.inScope = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(1, $stockId)
            ->andReturnSelf()
            ->shouldReceive('getQuery->getSingleScalarResult')
            ->once()
            ->andReturn($successfulCount);

        $this->assertEquals(
            $successfulCount,
            $this->sut->getSuccessfulCountInScope($stockId)
        );
    }

    public function testGetSuccessfulCountInScopeWithEmissionsCategoryId()
    {
        $stockId = 7;
        $assignedEmissionsCategoryId = RefData::EMISSIONS_CATEGORY_EURO5_REF;
        $successfulCount = 15;

        $queryBuilder = m::mock(QueryBuilder::class);
        $this->em->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);

        $queryBuilder->shouldReceive('select')
            ->with('count(icp)')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('from')
            ->with(IrhpCandidatePermitEntity::class, 'icp')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('icp.irhpPermitApplication', 'ipa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpPermitWindow', 'ipw')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpApplication', 'epa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('where')
            ->with('IDENTITY(ipw.irhpPermitStock) = ?1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('icp.successful = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('epa.inScope = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(1, $stockId)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('IDENTITY(icp.assignedEmissionsCategory) = ?2')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(2, $assignedEmissionsCategoryId)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('getQuery->getSingleScalarResult')
            ->once()
            ->andReturn($successfulCount);

        $this->assertEquals(
            $successfulCount,
            $this->sut->getSuccessfulCountInScope($stockId, $assignedEmissionsCategoryId)
        );
    }

    public function testGetSuccessfulScoreOrderedInScope()
    {
        $stockId = 7;

        $expectedResult = [
            m::mock(IrhpCandidatePermitEntity::class),
            m::mock(IrhpCandidatePermitEntity::class),
            m::mock(IrhpCandidatePermitEntity::class),
        ];

        $queryBuilder = m::mock(QueryBuilder::class);
        $this->em->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);

        $queryBuilder->shouldReceive('select')
            ->with('icp')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('from')
            ->with(IrhpCandidatePermitEntity::class, 'icp')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('icp.irhpPermitApplication', 'ipa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpPermitWindow', 'ipw')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpApplication', 'epa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('where')
            ->with('IDENTITY(ipw.irhpPermitStock) = ?1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('icp.successful = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('epa.inScope = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('orderBy')
            ->with('icp.randomizedScore', 'DESC')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(1, $stockId)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('getQuery->getResult')
            ->once()
            ->andReturn($expectedResult);

        $this->assertEquals(
            $expectedResult,
            $this->sut->getSuccessfulScoreOrderedInScope($stockId)
        );
    }

    public function testFetchDeviationSourceValues()
    {
        $deviationSourceValues = [
            [
                'candidatePermitId' => 102,
                'licNo' => 'PD2737280',
                'applicationId' => 202,
                'permitsRequired' => 12
            ],
            [
                'candidatePermitId' => 104,
                'licNo' => 'OG4569803',
                'applicationId' => 205,
                'permitsRequired' => 6
            ]
        ];

        $stockId = 3;

        $queryBuilder = m::mock(QueryBuilder::class);
        $this->em->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);

        $queryBuilder->shouldReceive('select')
            ->with(
                'icp.id as candidatePermitId, l.licNo, epa.id as applicationId,' .
                '(ipa.requiredEuro5 + ipa.requiredEuro6) as permitsRequired'
            )
            ->once()
            ->andReturnSelf()
            ->shouldReceive('from')
            ->with(IrhpCandidatePermitEntity::class, 'icp')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('icp.irhpPermitApplication', 'ipa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpPermitWindow', 'ipw')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpApplication', 'epa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('epa.licence', 'l')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('where')
            ->with('IDENTITY(ipw.irhpPermitStock) = ?1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('epa.inScope = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(1, $stockId)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('getQuery->getScalarResult')
            ->once()
            ->andReturn($deviationSourceValues);

        $this->assertEquals(
            $deviationSourceValues,
            $this->sut->fetchDeviationSourceValues($stockId)
        );
    }

    public function testFetchApplicationIdToCountryIdAssociations()
    {
        $stockId = 14;

        $associations = [
            102 => 'AT',
            102 => 'RU',
            103 => 'GR'
        ];

        $statement = m::mock(Statement::class);
        $statement->shouldReceive('fetchAll')
            ->once()
            ->andReturn($associations);

        $connection = m::mock(Connection::class);
        $connection->shouldReceive('executeQuery')
            ->with(
                'select e.id as applicationId, eacl.country_id as countryId ' .
                'from irhp_application_country_link eacl ' .
                'inner join irhp_application as e on e.id = eacl.irhp_application_id ' .
                'where e.id in (' .
                '    select irhp_application_id from irhp_permit_application where irhp_permit_window_id in (' .
                '        select id from irhp_permit_window where irhp_permit_stock_id = :stockId' .
                '    )' .
                ') ' .
                'and e.in_scope = 1 ',
                ['stockId' => $stockId]
            )
            ->once()
            ->andReturn($statement);

        $this->em->shouldReceive('getConnection')->once()->andReturn($connection);

        $this->assertEquals(
            $associations,
            $this->sut->fetchApplicationIdToCountryIdAssociations($stockId)
        );
    }

    public function testFetchScoringReport()
    {
        $scoringReport = [
            'row1' => 'rowContent1',
            'row2' => 'rowContent2'
        ];

        $stockId = 3;

        $queryBuilder = m::mock(QueryBuilder::class);
        $this->em->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);

        $queryBuilder->shouldReceive('select')
            ->with(
                'icp.id as candidatePermitId, ' .
                'epa.id as applicationId, ' .
                'o.name as organisationName, ' .
                'icp.applicationScore as candidatePermitApplicationScore, ' .
                'icp.intensityOfUse as candidatePermitIntensityOfUse, ' .
                'icp.randomFactor as candidatePermitRandomFactor, ' .
                'icp.randomizedScore as candidatePermitRandomizedScore, ' .
                'IDENTITY(icp.requestedEmissionsCategory) as candidatePermitRequestedEmissionsCategory, ' .
                'IDENTITY(icp.assignedEmissionsCategory) as candidatePermitAssignedEmissionsCategory, ' .
                'IDENTITY(epa.internationalJourneys) as applicationInternationalJourneys, ' .
                'COALESCE(s.name, \'N/A\') as applicationSectorName, ' .
                'l.licNo as licenceNo, ' .
                'ta.id as trafficAreaId, ' .
                'ta.name as trafficAreaName, ' .
                'icp.successful as candidatePermitSuccessful, ' .
                'IDENTITY(icp.irhpPermitRange) as candidatePermitRangeId'
            )
            ->once()
            ->andReturnSelf()
            ->shouldReceive('from')
            ->with(IrhpCandidatePermitEntity::class, 'icp')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('icp.irhpPermitApplication', 'ipa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpPermitWindow', 'ipw')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('ipa.irhpApplication', 'epa')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('epa.licence', 'l')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('leftJoin')
            ->with('epa.sectors', 's')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('l.trafficArea', 'ta')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('innerJoin')
            ->with('l.organisation', 'o')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('where')
            ->with('IDENTITY(ipw.irhpPermitStock) = ?1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('epa.status = ?2')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with('epa.inScope = 1')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(1, $stockId)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with(2, IrhpInterface::STATUS_UNDER_CONSIDERATION)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('getQuery->getScalarResult')
            ->once()
            ->andReturn($scoringReport);

        $this->assertEquals(
            $scoringReport,
            $this->sut->fetchScoringReport($stockId)
        );
    }
}
