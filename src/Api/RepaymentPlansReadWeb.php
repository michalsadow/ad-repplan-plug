<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug\Api;

use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlan;

/**
 * Resource showing page with list of fieldses.
 */
class RepaymentPlansReadWeb extends RepaymentPlan
{

    /**
     * Answers for GET list of fieldses on Api.
     *
     * @return void
     */
    public function get(): void
    {

        // Preparations.
        $this->prepareSwts('repplans', ( dirname(dirname(dirname(__FILE__))) . '/tpl/' ));

        // Show errors if there are new.
        if ($this->countErrors() > 0) {
            $this->showErrors('web');
            return;
        }

        // Print parsed contents.
        echo $this->swts->parse();
    }
}
