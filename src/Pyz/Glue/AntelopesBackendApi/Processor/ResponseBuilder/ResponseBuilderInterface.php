<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\AntelopesBackendApi\Processor\ResponseBuilder;

use Generated\Shared\Transfer\AntelopeCollectionTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface AntelopeResponseBuilderInterface
{
    public function createAntelopeResponse(AntelopeCollectionTransfer $antelopeCollectionTransfer): GlueResponseTransfer;
}