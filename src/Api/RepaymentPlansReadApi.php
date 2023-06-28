<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug\Api;

use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlan;
use Przeslijmi\AgileDataRepplanPlug\IndexHandler;
use stdClass;

/**
 * Resource for API call for list of silos.
 */
class RepaymentPlansReadApi extends RepaymentPlan
{

    /**
     * Answers for GET list of siloses on Api.
     *
     * @return void
     */
    public function get(): void
    {

        // Lvd.
        $loc    = $_ENV['LOCALE'];
        $locSta = 'Przeslijmi.AgileDataRepplanPlug.inForce.';

        // Limit to only one agreement.
        $agrrIdParam = $this->route->getParam('aggrId', false);

        // Find index.
        $index = new IndexHandler();

        // Define list contents.
        foreach ($index->getIndex() as $id => $details) {

            // Lvd.
            $row    = (array) $details;
            $aggrId = 'a' . crc32($details->name . ' - ' . $details->agreement);

            if (empty($agrrIdParam) === false && $aggrId !== $agrrIdParam) {
                continue;
            }

            // Add other.
            $row['defaultSort']            = implode(', ', [ $row['name'], $row['agreement'], $row['generationDate'] ]);
            $row['agreementSort']          = implode(', ', [ $row['agreement'], $row['generationDate'] ]);
            $row['inForceLoc']             = $loc->get($locSta . $details->inForce);
            $row['xlsxUri']                = '/' . $_ENV['PRZESLIJMI_ADREPPLANPLUG_PLANS_DIR_URI'] . $id . '.xlsx';
            $row['possibleReaction']       = ( ( $details->inForce === 'yes' ) ? 'deforce' : 'enforce' );
            $row['possibleReactionLoc']    = $loc->get($locSta . $row['possibleReaction']);
            $row['sumOfPaymentsFormatted'] = number_format($row['sumOfPayments'], 2, '.', '&thinsp;') . ' PLN';
            $row['minRateFormatted']       = number_format(( $row['minRate'] * 100 ), 2, '.', '&thinsp;') . ' %';
            $row['maxRateFormatted']       = number_format(( $row['maxRate'] * 100 ), 2, '.', '&thinsp;') . ' %';

            // Add this one.
            $this->rows[] = $row;
        }//end foreach

        // If this is a call for one agreement only - add extra stuff to response.
        if (empty($agrrIdParam) === false) {

            // Only one row.
            $oneRow = ( array_values(array_slice($this->rows, 0, 1))[0] ?? [] );

            // Add extra to response.
            $this->response['extra']            = new stdClass();
            $this->response['extra']->aggrId    = $agrrIdParam;
            $this->response['extra']->name      = ( $oneRow['name'] ?? '' );
            $this->response['extra']->agreement = ( $oneRow['agreement'] ?? '' );

            // Add link safe variants.
            $this->response['extra']->nameLinkSafe      = urlencode(( $oneRow['name'] ?? '' ));
            $this->response['extra']->agreementLinkSafe = urlencode(( $oneRow['agreement'] ?? '' ));
        }

        // Pack and send.
        $this->composeList();
        $this->sendJson($this->response);
    }
}
