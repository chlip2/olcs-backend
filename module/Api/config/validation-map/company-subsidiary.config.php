<?php

use Dvsa\Olcs\Api\Domain\QueryHandler\CompanySubsidiary as QueryHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler\Application as AppCommandHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler\Licence as LicCommandHandler;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\CompanySubsidiary as Handler;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\CompanySubsidiary\Application as AppHandler;
use Dvsa\Olcs\Api\Domain\Validation\Handlers\CompanySubsidiary\Licence as LicHandler;

return [
    AppCommandHandler\CreateCompanySubsidiary::class => AppHandler\Create::class,
    AppCommandHandler\UpdateCompanySubsidiary::class => AppHandler\Update::class,
    AppCommandHandler\DeleteCompanySubsidiary::class => AppHandler\Delete::class,

    LicCommandHandler\CreateCompanySubsidiary::class => LicHandler\Create::class,
    LicCommandHandler\UpdateCompanySubsidiary::class => LicHandler\Update::class,
    LicCommandHandler\DeleteCompanySubsidiary::class => LicHandler\Delete::class,

    QueryHandler\CompanySubsidiary::class => Handler\Modify::class,
];
