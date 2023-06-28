<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug\Api;

use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlan;
use Przeslijmi\AgileDataRepplanPlug\Plan;

/**
 * Resource for API call for list of silos.
 */
class RepaymentPlanDeforceApi extends RepaymentPlan
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

        // Check acceptance.
        if ($body->doProceed !== 'yes') {

            // Add error.
            $this->addError(
                $_ENV['LOCALE']->get('Przeslijmi.AgileData.delete.noAgreement')
            );

            // Send reponse immediately.
            $this->sendJson($this->response);
            return;
        }

        // Deforce id.
        $handler = new Plan($id);
        $handler->setInForce(false);
        $handler->save();
        $handler->generate();

        $this->sendJson($this->response);
    }
}
