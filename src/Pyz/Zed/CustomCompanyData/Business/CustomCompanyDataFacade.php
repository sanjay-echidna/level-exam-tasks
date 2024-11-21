<?php

namespace Pyz\Zed\CustomCompanyData\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\CustomCompanyData\Business\CustomCompanyDataBusinessFactory getFactory()
 */
class CustomCompanyDataFacade extends AbstractFacade implements CustomCompanyDataFacadeInterface
{
    /**
     * {@inheritDoc}
     */
    public function importCustomCompanyData(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFactory()->createCustomCompanyDataImporter()->import($dataImporterConfigurationTransfer);
    }
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeStorageByCustomEvents(array $eventTransfers)
    {
        $this->getFactory()
            ->createCustomWriter()
            ->writeStorageByCustomEvents($eventTransfers);
    }
}
