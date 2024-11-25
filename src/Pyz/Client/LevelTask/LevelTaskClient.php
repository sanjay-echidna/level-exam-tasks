<?php

namespace Pyz\Client\LevelTask;

use Generated\Shared\Transfer\AntelopeCriteriaTransfer;
use Generated\Shared\Transfer\AntelopeResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\LevelTask\LevelTaskFactory getFactory()
 */
class LevelTaskClient extends AbstractClient implements LevelTaskClientInterface
{

    public function getAntelope(
        AntelopeCriteriaTransfer $antelopeCriteriaTransfer
    ): AntelopeResponseTransfer {
        return $this->getFactory()->createLevelTaskStub()->getAntelope($antelopeCriteriaTransfer);
    }
}