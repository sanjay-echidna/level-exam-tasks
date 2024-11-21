<?php
namespace Pyz\Zed\CustomCompanyData\Business\Importer;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Pyz\Zed\CustomCompanyData\Persistence\CustomCompanyDataEntityManagerInterface;
use SplFileObject;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;


class CustomCompanyDataImporter extends PublishAwareStep
{
    protected CustomCompanyDataEntityManagerInterface $entityManager;
    private int $chunkSize = 100;
    private const CSV_PATH = '/data/import/common/common/custom_company_data.csv';

    public function __construct(CustomCompanyDataEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        $reportTransfer = new DataImporterReportTransfer();
        $reportTransfer->setIsSuccess(true);  
    
        $filePath = $dataImporterConfigurationTransfer
            ? $dataImporterConfigurationTransfer->getReaderConfigurationOrFail()->getFileName()
            : static::CSV_PATH;

        if (!file_exists($filePath)) {
            $reportTransfer->setIsSuccess(false);
            return $reportTransfer;
        }

        $file = new SplFileObject($filePath);
        $file->setFlags(SplFileObject::READ_CSV);
        $file->setCsvControl(',');

        $headers = $file->fgetcsv();
        if ($headers === false) {
            $reportTransfer->setIsSuccess(false);
            return $reportTransfer;
        }

        $importedRowCount = 0;
        $expectedImportableDataSetCount = 0;
        $dataChunk = [];

        while (!$file->eof()) {
            $dataRow = $file->fgetcsv();
            
            if (empty($dataRow[0]) || count($dataRow) < count($headers)) {
                continue;
            }

            $dataRowAssoc = array_combine($headers, $dataRow);
            if ($dataRowAssoc === false) {
                $reportTransfer->setIsSuccess(false);
                return $reportTransfer;
            }

            $dataChunk[] = $dataRowAssoc;
            $expectedImportableDataSetCount++;

            if (count($dataChunk) >= $this->chunkSize) {
                if (!$this->processDataChunk($dataChunk, $reportTransfer)) {
                    return $reportTransfer;
                }
                $importedRowCount += count($dataChunk);
                $dataChunk = []; 
            }
        }

        if (!empty($dataChunk)) {
            if (!$this->processDataChunk($dataChunk, $reportTransfer)) {
                return $reportTransfer;
            }
            $importedRowCount += count($dataChunk);
        }

        $reportTransfer->setImportedDataSetCount($importedRowCount);
        $reportTransfer->setExpectedImportableDataSetCount($expectedImportableDataSetCount);

        return $reportTransfer;
    }

    /**
     *
     * @param array $dataChunk
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $reportTransfer
     * @return bool 
     */
    protected function processDataChunk(array $dataChunk, DataImporterReportTransfer $reportTransfer): bool
    {
        try {
            $this->entityManager->saveCustomCompanyData($dataChunk);
            return true;
        } catch (\Exception $e) {
            $reportTransfer->setIsSuccess(false);
            return false;
        }
    }
}
