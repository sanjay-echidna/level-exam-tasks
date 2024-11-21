<?php
use Spryker\Zed\Company\Business\CompanyBusinessFactory as SprykerCompanyBusinessFactory;
use Pyz\Zed\Company\Business\Writer\CompanyStorageWriter;

Class CompanyBusinessFactory extends SprykerCompanyBusinessFactory{
    
    /**
     * @return CustomCompanyDataStorageWriter
     */
    public function createCompanyDataWriter(){
        return (new CompanyStorageWriter());
    }
}
?>