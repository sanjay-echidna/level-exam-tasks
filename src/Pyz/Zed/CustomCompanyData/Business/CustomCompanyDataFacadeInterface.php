<?php

namespace Pyz\Zed\CustomCompanyData\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;

interface CustomCompanyDataFacadeInterface
{
    /**
     * Imports data for CustomCompanyData.
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importCustomCompanyData(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer;

    public function writeStorageByCustomEvents(array $eventTransfers);
}
