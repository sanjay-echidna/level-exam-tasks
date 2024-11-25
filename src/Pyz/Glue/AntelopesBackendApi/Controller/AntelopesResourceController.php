<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\AntelopesBackendApi\Controller;

use Generated\Shared\Transfer\AntelopeCriteriaTransfer;
use Generated\Shared\Transfer\AntelopesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController;

/**
 * @method \Pyz\Glue\AntelopesBackendApi\AntelopesBackendApiFactory getFactory()
 */
class AntelopesResourceController extends AbstractController
{
    public function getCollectionAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $antelopeCriteriaTransfer = new AntelopeCriteriaTransfer();
        $antelopes = $this->getFactory()->getAntelopeFacade()
            ->getAntelopeCollection($antelopeCriteriaTransfer)->getAntelopes();
        $responseTransfer = new GlueResponseTransfer();
        foreach ($antelopes as $antelope) {
            $resource = new GlueResourceTransfer();
            $resource->setType(AntelopesBackendApiConfig::RESOURCE_ANTELOPES);
            $resource->setId('' . $antelope->getIdAntelope());
            $attributes = new AntelopesBackendApiAttributesTransfer();
            $attributes->fromArray($antelope->toArray(), true);

            $resource->setAttributes($attributes);
            $responseTransfer->addResource($resource);
        }

        return $responseTransfer;
    }
}