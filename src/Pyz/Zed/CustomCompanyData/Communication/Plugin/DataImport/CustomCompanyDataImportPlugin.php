<?php

namespace Pyz\Zed\CustomCompanyData\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Pyz\Zed\CustomCompanyData\CustomCompanyDataConfig;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\CustomCompanyData\Business\CustomCompanyDataFacadeInterface getFacade()
 * @method \Pyz\Zed\CustomCompanyData\CustomCompanyDataConfig getConfig()
 */
class CustomCompanyDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * 
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        
        return $this->getFacade()->importCustomCompanyData($dataImporterConfigurationTransfer);
    }

    /**
     * Defines the import type for this plugin.
     *
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return CustomCompanyDataConfig::IMPORT_TYPE_CUSTOM_COMPANY_DATA;
    }
}