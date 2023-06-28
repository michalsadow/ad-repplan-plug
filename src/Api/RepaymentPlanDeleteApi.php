<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug\Api;

use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlan;
use Przeslijmi\AgileDataRepplanPlug\Plan;

/**
 * Resource for API call for list of silos.
 */
class RepaymentPlanDeleteApi extends RepaymentPlan
{

    /**
     * Answers for GET list of siloses on Api.
     *
     * @return void
     */
    public function delete(): void
    {

        // Lvd.
        $id   = $this->route->getParam('id');
        $body = ( $this->route->getBody() ?? new stdClass() );

        // Check acceptance.
        if ($body->doDelete !== 'yes') {

            // Add error.
            $this->addError(
                $_ENV['LOCALE']->get('Przeslijmi.AgileData.delete.noAgreement')
            );

            // Send reponse immediately.
            $this->sendJson($this->response);
            return;
        }

        // Delete id.
        $handler = new Plan($id);
        $handler->delete();

        $this->sendJson($this->response);
    }
}
