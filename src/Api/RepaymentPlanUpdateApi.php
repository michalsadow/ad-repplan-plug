<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug\Api;

use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlan;
use Przeslijmi\AgileDataRepplanPlug\Plan;

/**
 * Resource for API call for list of silos.
 */
class RepaymentPlanUpdateApi extends RepaymentPlan
{

    /**
     * Answers for GET list of siloses on Api.
     *
     * @return void
     */
    public function put(): void
    {

        // Lvd.
        $id   = $this->route->getParam('id');
        $body = ( $this->route->getBody() ?? new stdClass() );

        // Full validation.
        $this->validate($body);

        // Create if no errors have been found.
        if ($this->response['status'] === 'success') {
            $plan = new Plan($id);
            $plan->setDef($body);
            $plan->save();
            $plan->generate();
        }

        // Send answer.
        $this->sendJson($this->response);
    }
}
