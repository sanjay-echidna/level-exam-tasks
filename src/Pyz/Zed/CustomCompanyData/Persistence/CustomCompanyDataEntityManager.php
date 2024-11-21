<?php

namespace Pyz\Zed\CustomCompanyData\Persistence;

use Orm\Zed\Company\Persistence\SpyCompany;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUser;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

class CustomCompanyDataEntityManager extends AbstractEntityManager implements CustomCompanyDataEntityManagerInterface
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
     * @param array $data
     *
     * @return void
     */
    public function saveCustomCompanyData(array $data): void
    {
        $entityCompanyCount= $this->insertCompanyChunk($data);
        $entityCustomerCount = $this->insertCustomerChunk($data);
        $entityCompanyBusinessCount = $this->insertCompanyBusinessUnitChunk($data);
        $entityCompanyUserCount = $this->insertCompanyUserChunk($data);
    }
    
    /**
     *
     * @param array $data
     * @return int
     */
    protected function insertCompanyChunk(array $data): int
    {
        $propelCollection = new ObjectCollection();
        $propelCollection->setModel(SpyCompany::class);

        foreach ($data as $record) {
            $entity = SpyCompanyQuery::create()
                ->filterByKey($record['company_key'])
                ->findOneOrCreate();

            $entity = $this->mapDataSetToCompanyEntity($entity, $record);
            $propelCollection->append($entity);
        }

        $propelCollection->save();

        return $propelCollection->count();
    }

    /**
     *
     * @param array $data
     * @return int
     */
    protected function insertCustomerChunk(array $data): int
    {
        $propelCollection = new ObjectCollection();
        $propelCollection->setModel(SpyCustomer::class);

        foreach ($data as $record) {
            $entity = SpyCustomerQuery::create()
                ->filterByCustomerReference($record[static::KEY_CUSTOMER_REFERENCE])
                ->findOneOrCreate();

            $entity = $this->mapDataSetToCustomerEntity($entity, $record);
            $propelCollection->append($entity);
        }

        $propelCollection->save();

        return $propelCollection->count();
    }

    /**
     *
     * @param array $data
     * @return int
     */
    protected function insertCompanyBusinessUnitChunk(array $data): int
    {
        $propelCollection = new ObjectCollection();
        $propelCollection->setModel(SpyCompanyBusinessUnit::class);

        foreach ($data as $record) {
            $entity = SpyCompanyBusinessUnitQuery::create()
                ->filterByKey($record[static::KEY_COMPANY_KEY])
                ->findOneOrCreate();
            $companyEntity = SpyCompanyQuery::create()
                ->filterByKey($record[static::KEY_COMPANY_KEY])
                ->findOneOrCreate();    

            $entity = $this->mapDataSetToCompanyBusinessUnitEntity($companyEntity->getIdCompany(), $entity, $record);
            $propelCollection->append($entity);
        }

        $propelCollection->save();

        return $propelCollection->count();
    }

    /**
     *
     * @param array $data
     * @return int
     */
    protected function insertCompanyUserChunk(array $data): int
    {
        $propelCollection = new ObjectCollection();
        $propelCollection->setModel(SpyCompanyUser::class);

        foreach ($data as $record) {
            $entity = SpyCompanyUserQuery::create()
                ->filterByKey($record[static::KEY_COMPANY_ROLE_KEY])
                ->findOneOrCreate();

            $companyEntity = SpyCompanyQuery::create()
                ->filterByKey($record[static::KEY_COMPANY_KEY])
                ->findOneOrCreate();

            $customerEntity = SpyCustomerQuery::create()
                ->filterByEmail($record[static::KEY_CUSTOMER_EMAIL])
                ->findOneOrCreate();
            $id = $companyEntity->getIdCompany();
            $companyBusinessUnitEntity = SpyCompanyBusinessUnitQuery::create()
                ->filterByKey($record[static::KEY_BUSINESS_UNIT_KEY])
                ->findOneOrCreate();

            $entity = $this->mapDataSetToCompanyUserEntity(
                $companyEntity->getIdCompany(),
                $companyEntity->getIdCompany(),
                $companyBusinessUnitEntity->getIdCompanyBusinessUnit(),
                $entity,
                $record
            );

            $propelCollection->append($entity);
        }

        $propelCollection->save();

        return $propelCollection->count();
    }

    /**
     * @param int $idCompany
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $companyBusinessUnitEntity
     * @param array $dataSet
     *
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit
     */
    private function mapDataSetToCompanyBusinessUnitEntity(int $idCompany ,SpyCompanyBusinessUnit $companyBusinessUnitEntity, array $dataSet): SpyCompanyBusinessUnit
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
     * @param array $dataSet
     *
     * @return \Orm\Zed\Company\Persistence\SpyCompany
     */
    private function mapDataSetToCompanyEntity(SpyCompany $companyEntity, array $dataSet): SpyCompany
    {
        $companyEntity
            ->setName($dataSet[static::KEY_COMPANY_NAME])
            ->setIsActive($dataSet[static::KEY_COMPANY_IS_ACTIVE])
            ->setStatus($dataSet[static::KEY_COMPANY_STATUS]);

        return $companyEntity;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     * @param array $dataSet
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    private function mapDataSetToCustomerEntity(SpyCustomer $customerEntity, array $dataSet): SpyCustomer
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
     * @param array $dataSet
     *
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole
     */
    private function mapDataSetToCompanyRoleEntity(int $idCompany ,SpyCompanyRole $companyRoleEntity, array $dataSet): SpyCompanyRole
    {
        $companyRoleEntity
            ->setFkCompany($idCompany)
            ->setName($dataSet[static::KEY_COMPANY_ROLE_NAME])
            ->setIsDefault($dataSet[static::KEY_COMPANY_ROLE_IS_DEFAULT]);

        return $companyRoleEntity;
    }

    /**
     *
     * @param int $idCompany
     * @param int $idBusinessUnit
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUser $companyUserEntity
     * @param array $dataSet
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUser
     */
    private function mapDataSetToCompanyUserEntity(
        int $idCustomer,
        int $idCompany,
        int $idBusinessUnit,
        SpyCompanyUser $companyUserEntity,
        array $dataSet
    ): SpyCompanyUser {
        $companyUserEntity
            ->setFkCustomer($idCustomer)
            ->setFkCompany($idCompany)
            ->setFkCompanyBusinessUnit($idBusinessUnit)
            ->setIsDefault((bool)$dataSet[static::KEY_COMPANY_ROLE_IS_DEFAULT]);

        return $companyUserEntity;
    }

}
