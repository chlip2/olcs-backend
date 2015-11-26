<?php

namespace Dvsa\OlcsTest\Api\Entity\User;

use Dvsa\OlcsTest\Api\Entity\Abstracts\EntityTester;
use Dvsa\Olcs\Api\Entity\Bus\LocalAuthority as LocalAuthorityEntity;
use Dvsa\Olcs\Api\Entity\ContactDetails\ContactDetails as ContactDetailsEntity;
use Dvsa\Olcs\Api\Entity\Organisation\Organisation as OrganisationEntity;
use Dvsa\Olcs\Api\Entity\Tm\TransportManager as TransportManagerEntity;
use Dvsa\Olcs\Api\Entity\User\Role as RoleEntity;
use Dvsa\Olcs\Api\Entity\User\User as Entity;
use Dvsa\Olcs\Api\Entity\User\Team as TeamEntity;
use Mockery as m;

/**
 * User Entity Unit Tests
 *
 * Initially auto-generated but won't be overridden
 */
class UserEntityTest extends EntityTester
{
    /**
     * Define the entity to test
     *
     * @var string
     */
    protected $entityClass = Entity::class;

    public function setUp()
    {
        $this->entity = $this->instantiate($this->entityClass);
    }

    /**
     * @dataProvider getUserTypeDataProvider
     *
     */
    public function testGetUserType($team, $localAuthority, $transportManager, $partnerContactDetails, $expected)
    {
        $this->entity->setTeam($team);
        $this->entity->setLocalAuthority($localAuthority);
        $this->entity->setTransportManager($transportManager);
        $this->entity->setPartnerContactDetails($partnerContactDetails);

        $this->assertEquals($expected, $this->entity->getUserType());
    }

    public function getUserTypeDataProvider()
    {
        $team = m::mock(TeamEntity::class);
        $localAuthority = m::mock(LocalAuthorityEntity::class);
        $transportManager = m::mock(TransportManagerEntity::class);
        $partnerContactDetails = m::mock(ContactDetailsEntity::class);

        return [
            [$team, null, null, null, Entity::USER_TYPE_INTERNAL],
            [null, $localAuthority, null, null, Entity::USER_TYPE_LOCAL_AUTHORITY],
            [null, null, $transportManager, null, Entity::USER_TYPE_TRANSPORT_MANAGER],
            [null, null, null, $partnerContactDetails, Entity::USER_TYPE_PARTNER],
            [null, null, null, null, Entity::USER_TYPE_OPERATOR],
        ];
    }

    public function testCreateInternal()
    {
        $role = m::mock(RoleEntity::class)->makePartial();
        $role->setRole(RoleEntity::ROLE_INTERNAL_LIMITED_READ_ONLY);

        $data = [
            'loginId' => 'loginId',
            'roles' => [$role],
            'accountDisabled' => 'Y',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [
                m::mock(OrganisationEntity::class)
            ],
        ];

        $entity = Entity::create('pid', Entity::USER_TYPE_INTERNAL, $data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertInstanceOf(\DateTime::class, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_INTERNAL, $entity->getUserType());
        $this->assertEquals($data['team'], $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
        $this->assertEquals('DVSA', $entity->getRelatedOrganisationName());
    }

    public function testUpdateInternal()
    {
        $role = m::mock(RoleEntity::class)->makePartial();
        $role->setRole(RoleEntity::ROLE_INTERNAL_LIMITED_READ_ONLY);

        $data = [
            'userType' => Entity::USER_TYPE_INTERNAL,
            'loginId' => 'loginId',
            'roles' => [$role],
            'accountDisabled' => 'N',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [
                m::mock(OrganisationEntity::class)
            ],
        ];

        // create an object of different type first
        $entity = Entity::create(
            'pid',
            Entity::USER_TYPE_PARTNER,
            [
                'loginId' => 'currentLoginId',
                'accountDisabled' => 'Y',
                'team' => m::mock(TeamEntity::class),
                'transportManager' => m::mock(TransportManagerEntity::class),
                'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
                'localAuthority' => m::mock(LocalAuthorityEntity::class),
                'organisations' => [
                    m::mock(OrganisationEntity::class)
                ],
            ]
        );

        // update the entity
        $entity->update($data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertEquals(null, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_INTERNAL, $entity->getUserType());
        $this->assertEquals($data['team'], $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
        $this->assertEquals('DVSA', $entity->getRelatedOrganisationName());
    }

    public function testCreateTransportManager()
    {
        $adminRole = m::mock(RoleEntity::class)->makePartial();
        $adminRole->setRole(RoleEntity::ROLE_OPERATOR_ADMIN);

        $orgName = 'Org Name';
        $org = m::mock(OrganisationEntity::class)->makePartial();
        $org->setName($orgName);

        $data = [
            'loginId' => 'loginId',
            'roles' => [$adminRole],
            'accountDisabled' => 'Y',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [$org],
        ];

        $entity = Entity::create('pid', Entity::USER_TYPE_TRANSPORT_MANAGER, $data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertInstanceOf(\DateTime::class, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_TRANSPORT_MANAGER, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals($data['transportManager'], $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(1, $entity->getOrganisationUsers()->count());
        $this->assertEquals('Y', $entity->getOrganisationUsers()->first()->getIsAdministrator());
        $this->assertEquals($orgName, $entity->getRelatedOrganisationName());
    }

    public function testUpdateTransportManager()
    {
        $nonAdminRole = m::mock(RoleEntity::class)->makePartial();
        $nonAdminRole->setRole(RoleEntity::ROLE_OPERATOR_USER);

        $orgName = 'Org Name';
        $org = m::mock(OrganisationEntity::class)->makePartial();
        $org->setName($orgName);

        $data = [
            'userType' => Entity::USER_TYPE_TRANSPORT_MANAGER,
            'loginId' => 'loginId',
            'roles' => [$nonAdminRole],
            'accountDisabled' => 'N',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [$org],
        ];

        // create an object of different type first
        $entity = Entity::create(
            'pid',
            Entity::USER_TYPE_PARTNER,
            [
                'loginId' => 'currentLoginId',
                'accountDisabled' => 'Y',
                'team' => m::mock(TeamEntity::class),
                'transportManager' => m::mock(TransportManagerEntity::class),
                'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
                'localAuthority' => m::mock(LocalAuthorityEntity::class),
                'organisations' => [
                    m::mock(OrganisationEntity::class)
                ],
            ]
        );

        // update the entity
        $entity->update($data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertEquals(null, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_TRANSPORT_MANAGER, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals($data['transportManager'], $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(1, $entity->getOrganisationUsers()->count());
        $this->assertEquals('N', $entity->getOrganisationUsers()->first()->getIsAdministrator());
        $this->assertEquals($orgName, $entity->getRelatedOrganisationName());
    }

    public function testCreatePartner()
    {
        $role = m::mock(RoleEntity::class)->makePartial();
        $role->setRole(RoleEntity::ROLE_PARTNER_USER);

        $orgName = 'Org Name';
        $contactDetails = m::mock(ContactDetailsEntity::class)->makePartial();
        $contactDetails->setDescription($orgName);

        $data = [
            'loginId' => 'loginId',
            'roles' => [$role],
            'accountDisabled' => 'Y',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => $contactDetails,
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [
                m::mock(OrganisationEntity::class)
            ],
        ];

        $entity = Entity::create('pid', Entity::USER_TYPE_PARTNER, $data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertInstanceOf(\DateTime::class, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_PARTNER, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals($data['partnerContactDetails'], $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
        $this->assertEquals($orgName, $entity->getRelatedOrganisationName());
    }

    public function testUpdatePartner()
    {
        $role = m::mock(RoleEntity::class)->makePartial();
        $role->setRole(RoleEntity::ROLE_PARTNER_USER);

        $orgName = 'Org Name';
        $contactDetails = m::mock(ContactDetailsEntity::class)->makePartial();
        $contactDetails->setDescription($orgName);

        $data = [
            'userType' => Entity::USER_TYPE_PARTNER,
            'loginId' => 'loginId',
            'roles' => [$role],
            'accountDisabled' => 'N',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => $contactDetails,
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [
                m::mock(OrganisationEntity::class)
            ],
        ];

        // create an object of different type first
        $entity = Entity::create(
            'pid',
            Entity::USER_TYPE_LOCAL_AUTHORITY,
            [
                'loginId' => 'currentLoginId',
                'accountDisabled' => 'Y',
                'team' => m::mock(TeamEntity::class),
                'transportManager' => m::mock(TransportManagerEntity::class),
                'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
                'localAuthority' => m::mock(LocalAuthorityEntity::class),
                'organisations' => [
                    m::mock(OrganisationEntity::class)
                ],
            ]
        );

        // update the entity
        $entity->update($data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertEquals(null, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_PARTNER, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals($data['partnerContactDetails'], $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
        $this->assertEquals($orgName, $entity->getRelatedOrganisationName());
    }

    public function testCreateLocalAuthority()
    {
        $role = m::mock(RoleEntity::class)->makePartial();
        $role->setRole(RoleEntity::ROLE_LOCAL_AUTHORITY_USER);

        $orgName = 'Org Name';
        $localAuthority = m::mock(LocalAuthorityEntity::class)->makePartial();
        $localAuthority->setDescription($orgName);

        $data = [
            'loginId' => 'loginId',
            'roles' => [$role],
            'accountDisabled' => 'Y',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => $localAuthority,
            'organisations' => [
                m::mock(OrganisationEntity::class)
            ],
        ];

        $entity = Entity::create('pid', Entity::USER_TYPE_LOCAL_AUTHORITY, $data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertInstanceOf(\DateTime::class, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_LOCAL_AUTHORITY, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals($data['localAuthority'], $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
        $this->assertEquals($orgName, $entity->getRelatedOrganisationName());
    }

    public function testUpdateLocalAuthority()
    {
        $role = m::mock(RoleEntity::class)->makePartial();
        $role->setRole(RoleEntity::ROLE_LOCAL_AUTHORITY_USER);

        $orgName = 'Org Name';
        $localAuthority = m::mock(LocalAuthorityEntity::class)->makePartial();
        $localAuthority->setDescription($orgName);

        $data = [
            'userType' => Entity::USER_TYPE_LOCAL_AUTHORITY,
            'loginId' => 'loginId',
            'roles' => [$role],
            'accountDisabled' => 'N',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => $localAuthority,
            'organisations' => [
                m::mock(OrganisationEntity::class)
            ],
        ];

        // create an object of different type first
        $entity = Entity::create(
            'pid',
            Entity::USER_TYPE_PARTNER,
            [
                'loginId' => 'currentLoginId',
                'accountDisabled' => 'Y',
                'team' => m::mock(TeamEntity::class),
                'transportManager' => m::mock(TransportManagerEntity::class),
                'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
                'localAuthority' => m::mock(LocalAuthorityEntity::class),
                'organisations' => [
                    m::mock(OrganisationEntity::class)
                ],
            ]
        );

        // update the entity
        $entity->update($data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertEquals(null, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_LOCAL_AUTHORITY, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals($data['localAuthority'], $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
        $this->assertEquals($orgName, $entity->getRelatedOrganisationName());
    }

    public function testCreateOperator()
    {
        $adminRole = m::mock(RoleEntity::class)->makePartial();
        $adminRole->setRole(RoleEntity::ROLE_OPERATOR_ADMIN);

        $orgName = 'Org Name';
        $org = m::mock(OrganisationEntity::class)->makePartial();
        $org->setName($orgName);

        $data = [
            'loginId' => 'loginId',
            'roles' => [$adminRole],
            'accountDisabled' => 'Y',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [$org],
        ];

        $entity = Entity::create('pid', Entity::USER_TYPE_OPERATOR, $data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertInstanceOf(\DateTime::class, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_OPERATOR, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(1, $entity->getOrganisationUsers()->count());
        $this->assertEquals('Y', $entity->getOrganisationUsers()->first()->getIsAdministrator());
        $this->assertEquals($orgName, $entity->getRelatedOrganisationName());
    }

    public function testUpdateOperator()
    {
        $nonAdminRole = m::mock(RoleEntity::class)->makePartial();
        $nonAdminRole->setRole(RoleEntity::ROLE_OPERATOR_USER);

        $orgName = 'Org Name';
        $org = m::mock(OrganisationEntity::class)->makePartial();
        $org->setName($orgName);

        $data = [
            'userType' => Entity::USER_TYPE_OPERATOR,
            'loginId' => 'loginId',
            'roles' => [$nonAdminRole],
            'accountDisabled' => 'N',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [$org],
        ];

        // create an object of different type first
        $entity = Entity::create(
            'pid',
            Entity::USER_TYPE_PARTNER,
            [
                'loginId' => 'currentLoginId',
                'accountDisabled' => 'Y',
                'team' => m::mock(TeamEntity::class),
                'transportManager' => m::mock(TransportManagerEntity::class),
                'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
                'localAuthority' => m::mock(LocalAuthorityEntity::class),
                'organisations' => [
                    m::mock(OrganisationEntity::class)
                ],
            ]
        );

        // update the entity
        $entity->update($data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertEquals(null, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_OPERATOR, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(1, $entity->getOrganisationUsers()->count());
        $this->assertEquals('N', $entity->getOrganisationUsers()->first()->getIsAdministrator());
        $this->assertEquals($orgName, $entity->getRelatedOrganisationName());
    }

    public function testUpdateOperatorIsAdministratorOnly()
    {
        $adminRole = m::mock(RoleEntity::class)->makePartial();
        $adminRole->setRole(RoleEntity::ROLE_OPERATOR_ADMIN);

        $nonAdminRole = m::mock(RoleEntity::class)->makePartial();
        $nonAdminRole->setRole(RoleEntity::ROLE_OPERATOR_USER);

        $data = [
            'userType' => Entity::USER_TYPE_OPERATOR,
            'loginId' => 'loginId',
            'roles' => [$adminRole],
        ];

        $entity = Entity::create(
            'pid',
            Entity::USER_TYPE_OPERATOR,
            [
                'loginId' => 'currentLoginId',
                'roles' => [$nonAdminRole],
                'organisations' => [
                    m::mock(OrganisationEntity::class)->makePartial()
                ],
            ]
        );

        // update the entity
        $entity->update($data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());

        $this->assertEquals(Entity::USER_TYPE_OPERATOR, $entity->getUserType());
        $this->assertEquals(1, $entity->getOrganisationUsers()->count());
        $this->assertEquals('Y', $entity->getOrganisationUsers()->first()->getIsAdministrator());
    }

    /**
     * @expectedException \Dvsa\Olcs\Api\Domain\Exception\ValidationException
     */
    public function testCreateThrowsInvalidRoleException()
    {
        $role = m::mock(RoleEntity::class)->makePartial();
        $role->setRole(RoleEntity::ROLE_INTERNAL_ADMIN);

        $data = [
            'loginId' => 'loginId',
            'roles' => [$role],
            'accountDisabled' => 'Y',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [
                m::mock(OrganisationEntity::class)->makePartial()
            ],
        ];

        Entity::create('pid', Entity::USER_TYPE_OPERATOR, $data);
    }

    /**
     * @expectedException \Dvsa\Olcs\Api\Domain\Exception\ValidationException
     */
    public function testUpdateThrowsInvalidRoleException()
    {
        $role = m::mock(RoleEntity::class)->makePartial();
        $role->setRole(RoleEntity::ROLE_INTERNAL_ADMIN);

        $data = [
            'userType' => Entity::USER_TYPE_OPERATOR,
            'loginId' => 'loginId',
            'roles' => [$role],
            'accountDisabled' => 'N',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [
                m::mock(OrganisationEntity::class)->makePartial()
            ],
        ];

        // create an object of different type first
        $entity = Entity::create(
            'pid',
            Entity::USER_TYPE_PARTNER,
            [
                'loginId' => 'currentLoginId',
                'accountDisabled' => 'Y',
                'team' => m::mock(TeamEntity::class),
                'transportManager' => m::mock(TransportManagerEntity::class),
                'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
                'localAuthority' => m::mock(LocalAuthorityEntity::class),
                'organisations' => [
                    m::mock(OrganisationEntity::class)
                ],
            ]
        );

        // update the entity
        $entity->update($data);
    }

    /**
     * @dataProvider getPermissionProvider
     */
    public function testGetPermission($userType, $roleIds, $expected)
    {
        $roles = array_map(
            function ($id) {
                $role = m::mock(RoleEntity::class)->makePartial();
                $role->setRole($id);

                return $role;
            },
            $roleIds
        );

        $data = [
            'loginId' => 'loginId',
            'roles' => $roles,
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [
                m::mock(OrganisationEntity::class)->makePartial()
            ],
        ];

        $entity = Entity::create('pid', $userType, $data);

        $this->assertEquals($expected, $entity->getPermission());
    }

    public function getPermissionProvider()
    {
        return [
            // local authority - admin
            [
                Entity::USER_TYPE_LOCAL_AUTHORITY,
                Entity::getRolesByUserType(Entity::USER_TYPE_LOCAL_AUTHORITY, Entity::PERMISSION_ADMIN),
                Entity::PERMISSION_ADMIN
            ],
            // local authority - user
            [
                Entity::USER_TYPE_LOCAL_AUTHORITY,
                Entity::getRolesByUserType(Entity::USER_TYPE_LOCAL_AUTHORITY, Entity::PERMISSION_USER),
                Entity::PERMISSION_USER
            ],
            // operator - admin
            [
                Entity::USER_TYPE_OPERATOR,
                Entity::getRolesByUserType(Entity::USER_TYPE_OPERATOR, Entity::PERMISSION_ADMIN),
                Entity::PERMISSION_ADMIN
            ],
            // operator - user
            [
                Entity::USER_TYPE_OPERATOR,
                Entity::getRolesByUserType(Entity::USER_TYPE_OPERATOR, Entity::PERMISSION_USER),
                Entity::PERMISSION_USER
            ],
            // operator - tm
            [
                Entity::USER_TYPE_OPERATOR,
                Entity::getRolesByUserType(Entity::USER_TYPE_OPERATOR, Entity::PERMISSION_TM),
                Entity::PERMISSION_TM
            ],
            // operator - admin with tm role
            [
                Entity::USER_TYPE_OPERATOR,
                array_merge(
                    Entity::getRolesByUserType(Entity::USER_TYPE_OPERATOR, Entity::PERMISSION_ADMIN),
                    Entity::getRolesByUserType(Entity::USER_TYPE_OPERATOR, Entity::PERMISSION_TM)
                ),
                Entity::PERMISSION_ADMIN
            ],
            // operator - user with tm role
            [
                Entity::USER_TYPE_OPERATOR,
                array_merge(
                    Entity::getRolesByUserType(Entity::USER_TYPE_OPERATOR, Entity::PERMISSION_USER),
                    Entity::getRolesByUserType(Entity::USER_TYPE_OPERATOR, Entity::PERMISSION_TM)
                ),
                Entity::PERMISSION_USER
            ],
            // partner - admin
            [
                Entity::USER_TYPE_PARTNER,
                Entity::getRolesByUserType(Entity::USER_TYPE_PARTNER, Entity::PERMISSION_ADMIN),
                Entity::PERMISSION_ADMIN
            ],
            // partner - user
            [
                Entity::USER_TYPE_PARTNER,
                Entity::getRolesByUserType(Entity::USER_TYPE_PARTNER, Entity::PERMISSION_USER),
                Entity::PERMISSION_USER
            ],
            // internal - user
            [
                Entity::USER_TYPE_INTERNAL,
                Entity::getRolesByUserType(Entity::USER_TYPE_INTERNAL, Entity::PERMISSION_USER),
                null
            ],
        ];
    }

    public function testAnon()
    {
        $user = Entity::anon();

        $role = $user->getRoles()->current();

        $this->assertEquals(1, $user->getRoles()->count());
        $this->assertInstanceOf(RoleEntity::class, $role);
        $this->assertEquals(RoleEntity::ROLE_ANON, $role->getId());
        $this->assertEquals('anon', $user->getLoginId());
    }

    /**
     * @expectedException \Dvsa\Olcs\Api\Domain\Exception\ValidationException
     */
    public function testAnonUsernameReserved()
    {
        Entity::create('123456', Entity::USER_TYPE_INTERNAL, ['loginId' => 'anon']);
    }
}
