<?php

namespace Pyz\Zed\Company\Business;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\Company\Business\CompanyFacade as SprykerCompanyFacade;

/**
 * @method \Pyz\Zed\Company\Business\CompanyBusinessFactory getFactory()
 */
Class CompanyFacade extends SprykerCompanyFacade{
    
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function publishCompany(EventEntityTransfer $eventTransfers){
        $this->getFactory()
            ->createCompanyDataWriter()
            ->publishCompany($eventTransfers);
    }
}
?>