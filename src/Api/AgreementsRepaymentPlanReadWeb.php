<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug\Api;

use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlan;

/**
 * Resource showing page with list of fieldses.
 */
class AgreementsRepaymentPlanReadWeb extends RepaymentPlan
{

    /**
     * Answers for GET list of fieldses on Api.
     *
     * @return void
     */
    public function get(): void
    {

        // Preparations.
        $this->prepareSwts('agreementRepplans', ( dirname(dirname(dirname(__FILE__))) . '/tpl/' ));

        // Show errors if there are new.
        if ($this->countErrors() > 0) {
            $this->showErrors('web');
            return;
        }

        // Assign silo.
        $this->swts->assign('aggrId', $this->route->getParam('aggrId'));

        // Print parsed contents.
        echo $this->swts->parse();
    }
}
