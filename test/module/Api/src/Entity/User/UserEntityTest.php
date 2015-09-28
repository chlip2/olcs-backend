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
        $data = [
            'loginId' => 'loginId',
            'roles' => [m::mock(RoleEntity::class)],
            'memorableWord' => 'aWord',
            'mustResetPassword' => 'Y',
            'accountDisabled' => 'Y',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [
                m::mock(OrganisationEntity::class)
            ],
        ];

        $entity = Entity::create(Entity::USER_TYPE_INTERNAL, $data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['memorableWord'], $entity->getMemorableWord());
        $this->assertEquals($data['mustResetPassword'], $entity->getMustResetPassword());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertInstanceOf(\DateTime::class, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_INTERNAL, $entity->getUserType());
        $this->assertEquals($data['team'], $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
    }

    public function testUpdateInternal()
    {
        $data = [
            'userType' => Entity::USER_TYPE_INTERNAL,
            'loginId' => 'loginId',
            'roles' => [m::mock(RoleEntity::class)],
            'memorableWord' => 'aWord',
            'mustResetPassword' => 'Y',
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
        $this->assertEquals($data['memorableWord'], $entity->getMemorableWord());
        $this->assertEquals($data['mustResetPassword'], $entity->getMustResetPassword());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertEquals(null, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_INTERNAL, $entity->getUserType());
        $this->assertEquals($data['team'], $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
    }

    public function testCreateTransportManager()
    {
        $data = [
            'loginId' => 'loginId',
            'roles' => [m::mock(RoleEntity::class)],
            'memorableWord' => 'aWord',
            'mustResetPassword' => 'Y',
            'accountDisabled' => 'Y',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [
                m::mock(OrganisationEntity::class)
            ],
        ];

        $entity = Entity::create(Entity::USER_TYPE_TRANSPORT_MANAGER, $data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['memorableWord'], $entity->getMemorableWord());
        $this->assertEquals($data['mustResetPassword'], $entity->getMustResetPassword());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertInstanceOf(\DateTime::class, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_TRANSPORT_MANAGER, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals($data['transportManager'], $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
    }

    public function testUpdateTransportManager()
    {
        $data = [
            'userType' => Entity::USER_TYPE_TRANSPORT_MANAGER,
            'loginId' => 'loginId',
            'roles' => [m::mock(RoleEntity::class)],
            'memorableWord' => 'aWord',
            'mustResetPassword' => 'Y',
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
        $this->assertEquals($data['memorableWord'], $entity->getMemorableWord());
        $this->assertEquals($data['mustResetPassword'], $entity->getMustResetPassword());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertEquals(null, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_TRANSPORT_MANAGER, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals($data['transportManager'], $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
    }

    public function testCreatePartner()
    {
        $data = [
            'loginId' => 'loginId',
            'roles' => [m::mock(RoleEntity::class)],
            'memorableWord' => 'aWord',
            'mustResetPassword' => 'Y',
            'accountDisabled' => 'Y',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [
                m::mock(OrganisationEntity::class)
            ],
        ];

        $entity = Entity::create(Entity::USER_TYPE_PARTNER, $data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['memorableWord'], $entity->getMemorableWord());
        $this->assertEquals($data['mustResetPassword'], $entity->getMustResetPassword());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertInstanceOf(\DateTime::class, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_PARTNER, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals($data['partnerContactDetails'], $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
    }

    public function testUpdatePartner()
    {
        $data = [
            'userType' => Entity::USER_TYPE_PARTNER,
            'loginId' => 'loginId',
            'roles' => [m::mock(RoleEntity::class)],
            'memorableWord' => 'aWord',
            'mustResetPassword' => 'Y',
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
            Entity::USER_TYPE_TRANSPORT_MANAGER,
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
        $this->assertEquals($data['memorableWord'], $entity->getMemorableWord());
        $this->assertEquals($data['mustResetPassword'], $entity->getMustResetPassword());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertEquals(null, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_PARTNER, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals($data['partnerContactDetails'], $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
    }

    public function testCreateLocalAuthority()
    {
        $data = [
            'loginId' => 'loginId',
            'roles' => [m::mock(RoleEntity::class)],
            'memorableWord' => 'aWord',
            'mustResetPassword' => 'Y',
            'accountDisabled' => 'Y',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [
                m::mock(OrganisationEntity::class)
            ],
        ];

        $entity = Entity::create(Entity::USER_TYPE_LOCAL_AUTHORITY, $data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['memorableWord'], $entity->getMemorableWord());
        $this->assertEquals($data['mustResetPassword'], $entity->getMustResetPassword());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertInstanceOf(\DateTime::class, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_LOCAL_AUTHORITY, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals($data['localAuthority'], $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
    }

    public function testUpdateLocalAuthority()
    {
        $data = [
            'userType' => Entity::USER_TYPE_LOCAL_AUTHORITY,
            'loginId' => 'loginId',
            'roles' => [m::mock(RoleEntity::class)],
            'memorableWord' => 'aWord',
            'mustResetPassword' => 'Y',
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
            Entity::USER_TYPE_TRANSPORT_MANAGER,
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
        $this->assertEquals($data['memorableWord'], $entity->getMemorableWord());
        $this->assertEquals($data['mustResetPassword'], $entity->getMustResetPassword());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertEquals(null, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_LOCAL_AUTHORITY, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals($data['localAuthority'], $entity->getLocalAuthority());
        $this->assertEquals(0, $entity->getOrganisationUsers()->count());
    }

    public function testCreateOperator()
    {
        $adminRole = m::mock(RoleEntity::class)->makePartial();
        $adminRole->setId(RoleEntity::ROLE_OPERATOR_ADMIN);

        $data = [
            'loginId' => 'loginId',
            'roles' => [$adminRole],
            'memorableWord' => 'aWord',
            'mustResetPassword' => 'Y',
            'accountDisabled' => 'Y',
            'team' => m::mock(TeamEntity::class),
            'transportManager' => m::mock(TransportManagerEntity::class),
            'partnerContactDetails' => m::mock(ContactDetailsEntity::class),
            'localAuthority' => m::mock(LocalAuthorityEntity::class),
            'organisations' => [
                m::mock(OrganisationEntity::class)->makePartial()
            ],
        ];

        $entity = Entity::create(Entity::USER_TYPE_OPERATOR, $data);

        $this->assertEquals($data['loginId'], $entity->getLoginId());
        $this->assertEquals($data['roles'], $entity->getRoles()->toArray());
        $this->assertEquals($data['memorableWord'], $entity->getMemorableWord());
        $this->assertEquals($data['mustResetPassword'], $entity->getMustResetPassword());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertInstanceOf(\DateTime::class, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_OPERATOR, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(1, $entity->getOrganisationUsers()->count());
        $this->assertEquals('Y', $entity->getOrganisationUsers()->first()->getIsAdministrator());
    }

    public function testUpdateOperator()
    {
        $nonAdminRole = m::mock(RoleEntity::class)->makePartial();

        $data = [
            'userType' => Entity::USER_TYPE_OPERATOR,
            'loginId' => 'loginId',
            'roles' => [$nonAdminRole],
            'memorableWord' => 'aWord',
            'mustResetPassword' => 'Y',
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
            Entity::USER_TYPE_TRANSPORT_MANAGER,
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
        $this->assertEquals($data['memorableWord'], $entity->getMemorableWord());
        $this->assertEquals($data['mustResetPassword'], $entity->getMustResetPassword());
        $this->assertEquals($data['accountDisabled'], $entity->getAccountDisabled());
        $this->assertEquals(null, $entity->getLockedDate());

        $this->assertEquals(Entity::USER_TYPE_OPERATOR, $entity->getUserType());
        $this->assertEquals(null, $entity->getTeam());
        $this->assertEquals(null, $entity->getTransportManager());
        $this->assertEquals(null, $entity->getPartnerContactDetails());
        $this->assertEquals(null, $entity->getLocalAuthority());
        $this->assertEquals(1, $entity->getOrganisationUsers()->count());
        $this->assertEquals('N', $entity->getOrganisationUsers()->first()->getIsAdministrator());
    }

    public function testUpdateOperatorIsAdministratorOnly()
    {
        $adminRole = m::mock(RoleEntity::class)->makePartial();
        $adminRole->setId(RoleEntity::ROLE_OPERATOR_ADMIN);

        $nonAdminRole = m::mock(RoleEntity::class)->makePartial();

        $data = [
            'userType' => Entity::USER_TYPE_OPERATOR,
            'loginId' => 'loginId',
            'roles' => [$adminRole],
        ];

        $entity = Entity::create(
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
     * @dataProvider getPermissionProvider
     */
    public function testGetPermission($userType, $roleIds, $expected)
    {
        $roles = array_map(
            function ($id) {
                $role = m::mock(RoleEntity::class)->makePartial();
                $role->setId($id);

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

        $entity = Entity::create($userType, $data);

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
}
