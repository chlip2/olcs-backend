<?php

namespace Dvsa\Olcs\Api\Entity\Tm;

use Doctrine\ORM\Mapping as ORM;

/**
 * TransportManagerApplication Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="transport_manager_application",
 *    indexes={
 *        @ORM\Index(name="ix_transport_manager_application_transport_manager_id", columns={"transport_manager_id"}),
 *        @ORM\Index(name="ix_transport_manager_application_application_id", columns={"application_id"}),
 *        @ORM\Index(name="ix_transport_manager_application_created_by", columns={"created_by"}),
 *        @ORM\Index(name="ix_transport_manager_application_last_modified_by", columns={"last_modified_by"}),
 *        @ORM\Index(name="ix_transport_manager_application_tm_type", columns={"tm_type"}),
 *        @ORM\Index(name="ix_transport_manager_application_tm_application_status", columns={"tm_application_status"})
 *    },
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="uk_transport_manager_application_olbs_key", columns={"olbs_key"})
 *    }
 * )
 */
class TransportManagerApplication extends AbstractTransportManagerApplication
{
    const ACTION_ADD    = 'A';
    const ACTION_UPDATE = 'U';
    const ACTION_DELETE = 'D';

    const STATUS_INCOMPLETE = 'tmap_st_incomplete';
    const STATUS_AWAITING_SIGNATURE = 'tmap_st_awaiting_signature';
    const STATUS_TM_SIGNED = 'tmap_st_tm_signed';
    const STATUS_OPERATOR_SIGNED = 'tmap_st_operator_signed';
    const STATUS_POSTAL_APPLICATION = 'tmap_st_postal_application';
    const STATUS_RECEIVED = 'tmap_st_received';

    const ERROR_TM_EXIST = 'tm_exist';
}
