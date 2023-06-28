<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug\Api;

use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlan;

/**
 * Resource showing page with list of fieldses.
 */
class RepaymentPlanCloneWeb extends RepaymentPlan
{

    /**
     * Answers for GET list of fieldses on Api.
     *
     * @return void
     */
    public function get(): void
    {

        // Preparations.
        $this->prepareSwts('repplanClone', ( dirname(dirname(dirname(__FILE__))) . '/tpl/' ));

        // Show errors if there are new.
        if ($this->countErrors() > 0) {
            $this->showErrors('web');
            return;
        }

        // Assign id.
        $this->swts->assign('id', $this->route->getParam('id'));
        $this->swts->assign('aggrId', $this->route->getParam('aggrId', false));

        // Print parsed contents.
        echo $this->swts->parse();
    }
}
