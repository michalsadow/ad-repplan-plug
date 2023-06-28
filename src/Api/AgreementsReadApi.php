<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug\Api;

use Przeslijmi\AgileDataRepplanPlug\Api\Agreement;
use Przeslijmi\AgileDataRepplanPlug\IndexHandler;

/**
 * Resource for API call for list of silos.
 */
class AgreementsReadApi extends Agreement
{

    /**
     * Answers for GET list of siloses on Api.
     *
     * @return void
     */
    public function get(): void
    {

        // Lvd.
        $loc = $_ENV['LOCALE'];

        // Find index.
        $index = new IndexHandler();

        // Define list contents.
        foreach ($index->getIndex() as $id => $details) {

            // Ignore repayments created without defining client or agreement number.
            if (empty($details->name) === true || empty($details->agreement) === true) {
                continue;
            }

            // Lvd.
            $aggrId = 'a' . crc32($details->name . ' - ' . $details->agreement);

            // Create parent row.
            if (isset($this->rows[$aggrId]) === false) {
                $this->rows[$aggrId] = [
                    'id' => $aggrId,
                    'fullName' => ( $details->name . ', ' . $details->agreement ),
                    'name' => $details->name,
                    'agreement' => $details->agreement,
                    'count' => 0,
                    'countInForce' => 0,
                    'latest' => '---',
                    'latestInForce' => '---',
                    'plans' => [],
                ];
            }

            // Add this plan.
            $this->rows[$aggrId]['plans'][] = (array) $details;
        }//end foreach

        // Recalc stats.
        foreach ($this->rows as $aggrId => $agrrement) {
            foreach ($agrrement['plans'] as $plan) {

                // Count how many plans there are and which one is latest.
                ++$this->rows[$aggrId]['count'];
                $this->rows[$aggrId]['latest'] = max($this->rows[$aggrId]['latest'], $plan['generationDate']);

                // Count how many in-force plans there are and which one is latest.
                if ($plan->inForce === 'yes') {
                    ++$this->rows[$aggrId]['countInForce'];
                    $this->rows[$aggrId]['latestInForce'] = max(
                        $plan['generationDate'],
                        $this->rows[$aggrId]['latestInForce']
                    );
                }
            }
        }

        // Delete keys from rows.
        $this->rows = array_values($this->rows);

        // Pack and send.
        $this->composeList();
        $this->sendJson($this->response);
    }
}
