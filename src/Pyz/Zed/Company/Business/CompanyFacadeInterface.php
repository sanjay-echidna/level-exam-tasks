<?php

namespace Pyz\Zed\Company\Business;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\Company\Business\CompanyFacadeInterface as SprykerCompanyFacadeInteface;

interface CompanyFacadeInterface extends SprykerCompanyFacadeInteface{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function publishCompany(EventEntityTransfer $eventTransfers);
}
?>