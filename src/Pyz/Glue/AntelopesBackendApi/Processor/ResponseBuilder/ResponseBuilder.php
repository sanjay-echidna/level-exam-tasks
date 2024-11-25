<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\AntelopesBackendApi\Processor\ResponseBuilder;

use Generated\Shared\Transfer\AntelopeCollectionTransfer;
use Generated\Shared\Transfer\AntelopesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\AntelopeTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Pyz\Glue\AntelopesBackendApi\AntelopesBackendApiConfig;

class AntelopeResponseBuilder implements AntelopeResponseBuilderInterface
{
    public function createAntelopeResponse(AntelopeCollectionTransfer $antelopeCollectionTransfer): GlueResponseTransfer
    {
        $responseTransfer = new GlueResponseTransfer();
        foreach ($antelopeCollectionTransfer->getAntelopes() as $antelope) {
            $resource = $this->mapAntelopeDtoToGlueResourceTransfer($antelope);
            $responseTransfer->addResource($resource);
        }

        return $responseTransfer;
    }

    protected function mapAntelopeDtoToGlueResourceTransfer(AntelopeTransfer $antelopeTransfer): GlueResourceTransfer
    {
        $resource = new GlueResourceTransfer();
        $resource->setType(AntelopesBackendApiConfig::RESOURCE_ANTELOPES);
        $resource->setId('' . $antelopeTransfer->getIdAntelope());
        $attributes = new AntelopesBackendApiAttributesTransfer();
        $attributes->fromArray($antelopeTransfer->toArray(), true);

        $resource->setAttributes($attributes);

        return $resource;
    }
}