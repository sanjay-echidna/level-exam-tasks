<?php

namespace Pyz\Zed\CustomCompanyData\Business;

use Pyz\Zed\CustomCompanyData\Business\Importer\CustomCompanyDataImporter;
use Pyz\Zed\CustomCompanyData\Business\Writer\CustomStorageWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class CustomCompanyDataBusinessFactory extends AbstractBusinessFactory
{
    /**
     * Creates and returns the custom entity importer.
     *
     * @return \Pyz\Zed\CustomCompanyData\Business\Importer\CustomCompanyDataImporter
     */
    public function createCustomCompanyDataImporter(): CustomCompanyDataImporter
    {
        return new CustomCompanyDataImporter($this->getEntityManager());
    }

    /**
     * @return \Pyz\Zed\CustomCompanyData\Business\Writer\CustomStorageWriter
     */
    public function createCustomWriter(): CustomStorageWriter
    {
        return new CustomStorageWriter();
    }
}
