<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Company;
use Spryker\Shared\Kernel\AbstractBundleConfig;

class CompanyConfig extends AbstractBundleConfig
{
    /**
    *
    * @var string
    */
   public const ENTITY_SPY_COMPANY_CREATE = 'Entity.spy_company.create';

   /**
    *
    * @var string
    */
    public const ENTITY_SPY_COMPANY_UPDATE = 'Entity.spy_company.update';

    public const PUBLISH_SPY_COMPANY_SYNC = 'publish.company';

    /**
     * Defines queue name as used for processing translation messages.
     */
    public const SYNC_SPY_COMPANY = 'sync.storage.company';
    
    /**
     * Defines queue name as used for processing translation messages.
     */
    public const SYNC_SEARCH_SPY_COMPANY = 'sync.search.company';
}