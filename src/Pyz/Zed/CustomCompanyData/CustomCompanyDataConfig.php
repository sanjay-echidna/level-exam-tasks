<?php

namespace Pyz\Zed\CustomCompanyData;

use Spryker\Zed\DataImport\DataImportConfig;

class CustomCompanyDataConfig extends DataImportConfig
{
    public const IMPORT_TYPE_CUSTOM_COMPANY_DATA = 'custom-company-data';

    public function getFilePath(): string
    {
        return '/data/import/custom_company_data.csv';
    }
}
