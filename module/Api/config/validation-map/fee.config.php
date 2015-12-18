<?php

use Dvsa\Olcs\Api\Domain\QueryHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\Misc\IsInternalUser;

return [
    CommandHandler\Fee\CreateOverpaymentFee::class => IsInternalUser::class,
    CommandHandler\Fee\ResetFees::class => IsInternalUser::class,
];