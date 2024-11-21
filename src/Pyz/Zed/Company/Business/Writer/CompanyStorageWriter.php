<?php

namespace Pyz\Zed\Company\Business\Writer;

use Orm\Zed\Company\Persistence\Base\SpyCompany;
use Orm\Zed\Company\Persistence\Base\SpyCompanyQuery;
use Orm\Zed\Company\Persistence\Base\SpyCompanyStorageQuery;

class CompanyStorageWriter
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function publishCompany(array $eventTransfers): void
    {
        $accountNumbers = [];
        foreach ($eventTransfers as $eventTransfer) {
            $accountNumbers[] = $eventTransfer->getId();
        }

        // Fetch customers based on collected IDs
        $collectAccounts = SpyCompanyQuery::create()
            ->filterByAccountNumber_In($accountNumbers)
            ->find();

        // // Store each customer's data in the customer storage table
        foreach ($collectAccounts as $collectAccount) {
            $customerStorageTransfer = new CollectAccountDataSyncTransfer();
            $customerStorageTransfer->fromArray($collectAccount->toArray(), true);
            $this->store($collectAccount->getAccountNumber(), $customerStorageTransfer);
        }
    }

    /**
     * Stores the customer data in the spy_customer_storage table.
     *
     * @param int $accountNumber
     * @param \Generated\Shared\Transfer\CollectAccountDataSyncTransfer $collectAccountDataSyncTransfer
     *
     * @return void
     */
    protected function store(int $accountNumber, CollectAccountDataSyncTransfer $collectAccountDataSyncTransfer): void
    {
        $storageEntityQuery = SpyCompanyStorageQuery::create()
            ->filterByFkCollectAccount($accountNumber);

        $storageEntity = $storageEntityQuery->findOneOrCreate();        
        $storageEntity->setFkCollectAccount($accountNumber);
        $storageEntity->setData($collectAccountDataSyncTransfer->modifiedToArray());

        // $rawSql = $storageEntity->toString();
        // var_dump($rawSql);
        $storageEntity->save();
    }
}