<?php

namespace Pyz\Zed\CustomCompanyData\Persistence;


interface CustomCompanyDataEntityManagerInterface
{
    /**
     * @param array $data
     *
     * @return void
     */
    public function saveCustomCompanyData(array $data):void;

    // /**
    //  * 
    //  *
    //  * @param \Generated\Shared\Transfer\CustomCompanyDataTransfer $CustomCompanyDataTransfer
    //  *
    //  * @return void
    //  */
    // public function publishCustomCompanyData(CustomCompanyDataTransfer $CustomCompanyDataTransfer): void;

}
