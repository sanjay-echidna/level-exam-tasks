<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\CustomCompany;

use Orm\Zed\Company\Persistence\SpyCompany;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRole;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CustomCompanyWriterStep implements DataImportStepInterface
{
    public const KEY_COMPANY_KEY = 'company_key';
    public const KEY_COMPANY_NAME = 'company_name';
    public const KEY_COMPANY_IS_ACTIVE = 'company_is_active';
    public const KEY_COMPANY_STATUS = 'company_status';

    public const KEY_CUSTOMER_REFERENCE = 'customer_reference';
    public const KEY_CUSTOMER_LOCALE_NAME = 'customer_locale_name';
    public const KEY_CUSTOMER_PHONE = 'customer_phone';
    public const KEY_CUSTOMER_EMAIL = 'customer_email';
    public const KEY_CUSTOMER_SALUTATION = 'customer_salutation';
    public const KEY_CUSTOMER_FIRST_NAME = 'customer_first_name';
    public const KEY_CUSTOMER_LAST_NAME = 'customer_last_name';
    public const KEY_CUSTOMER_GENDER = 'customer_gender';
    public const KEY_CUSTOMER_DOB = 'customer_dob';
    public const KEY_CUSTOMER_PASSWORD = 'customer_password';
    public const KEY_CUSTOMER_REGISTERED_ON = 'customer_registered_on';

    public const KEY_COMPANY_ROLE_KEY = 'company_role_key';
    public const KEY_COMPANY_ROLE_NAME = 'company_role_name';
    public const KEY_COMPANY_ROLE_IS_DEFAULT = 'company_role_is_default';

    public const KEY_BUSINESS_UNIT_KEY = 'business_unit_key';
    public const KEY_BUSINESS_UNIT_NAME = 'business_unit_name';
    public const KEY_BUSINESS_UNIT_EMAIL = 'business_unit_email';
    public const KEY_BUSINESS_UNIT_PHONE = 'business_unit_phone';
    public const KEY_EXTERNAL_URL = 'external_url';
    public const KEY_IBAN = 'iban';
    public const KEY_BIC = 'bic';    
    public const BULK_INSERT_CHUNK_SIZE = 100;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companyEntity = $this->saveCompany($dataSet);
        $customerEntity = $this->saveCustomer($dataSet);
        $businessUnitEntity = $this->saveBusinessUnit($companyEntity->getIdCompany() ,$dataSet);
        $this->saveCompanyRole($companyEntity->getIdCompany() ,$dataSet);
        $this->saveCompanyUser($customerEntity->getIdCustomer(), $companyEntity->getIdCompany() ,$businessUnitEntity->getIdCompanyBusinessUnit() ,$dataSet);

    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @return \Orm\Zed\Company\Persistence\SpyCompany
     */
    protected function saveCompany($dataSet): SpyCompany
    {
        $companyEntity = SpyCompanyQuery::create()
        ->filterByKey($dataSet[self::KEY_COMPANY_KEY])
        ->findOneOrCreate();

        $companyEntity = $this->mapDataSetToCompanyEntity($companyEntity ,$dataSet);
        $companyEntity->save();
        
        return $companyEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    protected function saveCustomer($dataSet): SpyCustomer
    {
        $customerEntity = SpyCustomerQuery::create()
            ->filterByCustomerReference($dataSet[self::KEY_CUSTOMER_REFERENCE])
            ->filterByEmail($dataSet[self::KEY_CUSTOMER_EMAIL])
            ->findOneOrCreate();

        $customerEntity = $this->mapDataSetToCustomerEntity($customerEntity ,$dataSet);
        $customerEntity->save();

        return $customerEntity;
    }

    /**
     * @param int $idCompany
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit
     */
    protected function saveBusinessUnit($idCompany , $dataSet): SpyCompanyBusinessUnit
    {
        $companyBusinessUnitEntity = SpyCompanyBusinessUnitQuery::create()
            ->filterByKey($dataSet[static::KEY_BUSINESS_UNIT_KEY])
            ->findOneOrCreate();

        $companyBusinessUnitEntity = $this->mapDataSetToCompanyBusinessUnitEntity($idCompany ,$companyBusinessUnitEntity ,$dataSet);
        $companyBusinessUnitEntity->save();

        return $companyBusinessUnitEntity;
    }

    /**
     * @param int $companyId
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole
     */
    protected function saveCompanyRole(int $companyId,$dataSet): SpyCompanyRole
    {
        $companyRoleEntity = SpyCompanyRoleQuery::create()
            ->filterByKey($dataSet[self::KEY_COMPANY_ROLE_KEY])
            ->findOneOrCreate();
        $companyRoleEntity = $this->mapDataSetToCompanyRoleEntity($companyId ,$companyRoleEntity ,$dataSet);
        $companyRoleEntity->save();

        return $companyRoleEntity;
    }

    /**
     * @param int $idCompany
     * @param int $idCustomer
     * @param int $idBusinessUnit
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUser
     */
    protected function saveCompanyUser(int $idCustomer ,int $idCompany ,$idBusinessUnit ,$dataSet){
        
        $companyUserEntity = SpyCompanyUserQuery::create()
        ->filterByKey($dataSet[self::KEY_COMPANY_ROLE_KEY])
        ->findOneOrCreate();

        $companyUserEntity
            ->setFkCustomer($idCustomer)
            ->setFkCompany($idCompany)
            ->setFkCompanyBusinessUnit($idBusinessUnit)
            ->setIsDefault((bool)$dataSet[self::KEY_COMPANY_ROLE_IS_DEFAULT])
            ->save();

        return $companyUserEntity;
    }

    /**
     * @param int $idCompany
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $companyBusinessUnitEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit
     */
    private function mapDataSetToCompanyBusinessUnitEntity(int $idCompany ,SpyCompanyBusinessUnit $companyBusinessUnitEntity, DataSetInterface $dataSet): SpyCompanyBusinessUnit
    {
        $companyBusinessUnitEntity
            ->setName($dataSet[static::KEY_BUSINESS_UNIT_NAME])
            ->setEmail($dataSet[static::KEY_BUSINESS_UNIT_EMAIL])
            ->setPhone($dataSet[static::KEY_BUSINESS_UNIT_PHONE])
            ->setExternalUrl($dataSet[static::KEY_EXTERNAL_URL])
            ->setIban($dataSet[static::KEY_IBAN])
            ->setBic($dataSet[static::KEY_BIC])
            ->setFkCompany($idCompany);

        return $companyBusinessUnitEntity;
    }


    /**
     * @param \Orm\Zed\Company\Persistence\SpyCompany $companyEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Company\Persistence\SpyCompany
     */
    private function mapDataSetToCompanyEntity(SpyCompany $companyEntity, DataSetInterface $dataSet): SpyCompany
    {
        $companyEntity
            ->setName($dataSet[static::KEY_COMPANY_NAME])
            ->setIsActive($dataSet[static::KEY_COMPANY_IS_ACTIVE])
            ->setStatus($dataSet[static::KEY_COMPANY_STATUS]);

        return $companyEntity;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    private function mapDataSetToCustomerEntity(SpyCustomer $customerEntity, DataSetInterface $dataSet): SpyCustomer
    {
        $customerEntity
            ->setPhone($dataSet[static::KEY_CUSTOMER_PHONE])
            ->setSalutation($dataSet[static::KEY_CUSTOMER_SALUTATION])
            ->setFirstName($dataSet[static::KEY_CUSTOMER_FIRST_NAME])
            ->setLastName($dataSet[static::KEY_CUSTOMER_LAST_NAME])
            ->setGender($dataSet[static::KEY_CUSTOMER_GENDER])
            ->setDateOfBirth($dataSet[static::KEY_CUSTOMER_DOB])
            ->setPassword($dataSet[static::KEY_CUSTOMER_PASSWORD])
            ->setRegistered($dataSet[static::KEY_CUSTOMER_REGISTERED_ON]);

        return $customerEntity;
    }

    /**
     * @param int $idCompany
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole $companyRoleEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole
     */
    private function mapDataSetToCompanyRoleEntity(int $idCompany ,SpyCompanyRole $companyRoleEntity, DataSetInterface $dataSet): SpyCompanyRole
    {
        $companyRoleEntity
            ->setFkCompany($idCompany)
            ->setName($dataSet[static::KEY_COMPANY_ROLE_NAME])
            ->setIsDefault($dataSet[static::KEY_COMPANY_ROLE_IS_DEFAULT]);

        return $companyRoleEntity;
    }

}
