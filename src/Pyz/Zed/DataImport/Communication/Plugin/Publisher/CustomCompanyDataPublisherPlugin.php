<?php

namespace Pyz\Zed\DataImport\Communication\Plugin\Publisher;

use Pyz\Shared\Company\CompanyConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Pyz\Zed\CollectAccountDataSync\Communication\CollectAccountDataSyncCommunicationFactory getFactory()
 * @method \Pyz\Zed\CollectAccountDataSync\Business\CollectAccountDataSyncFacadeInterface getFacade()
 * @method \Pyz\Zed\Customer\CustomerConfig getConfig()
 */
class CustomCompanyDataPublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        foreach ($eventTransfers as $eventTransfer) {
            // echo $eventTransfer;
            $this->getFacade()->writeStorageByEvents($eventTransfers);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            CompanyConfig::ENTITY_SPY_COMPANY_CREATE,
            CompanyConfig::ENTITY_SPY_COMPANY_UPDATE,
        ];
    }
}