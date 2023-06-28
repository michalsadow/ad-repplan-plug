<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug;

use stdClass;
use Przeslijmi\AgileData\Tools\JsonSettings as Json;

/**
 * Keeps index up to date.
 *
 * Index keeps:
 *   - id
 *   - agreement
 *   - name
 *   - repStyle
 *   - repPeriod
 *   - sumOfPayments
 *   - minRate
 *   - maxRate
 *   - generationDate
 */
class IndexHandler
{

    /**
     * Updates/deletes information in index for given unique ID number.
     *
     * @param string        $id      Tvelwe-chars unique ID number.
     * @param null|stdClass $details Empty for deletion. Details to be updated.
     *
     * @return void
     */
    public function update(string $id, ?stdClass $details): void
    {

        $loc    = $_ENV['LOCALE'];
        $locSta = 'Przeslijmi.AgileDataRepplanPlug.repplans.fields.';

        // Prepare details - delete unneede fields, and add needed ones.
        if ($details !== null) {
            $properDetails                 = new stdClass();
            $properDetails->id             = $id;
            $properDetails->agreement      = ( $details->agreement ?? null );
            $properDetails->name           = ( $details->name ?? null );
            $properDetails->repStyle       = $loc->get($locSta . 'repStyle.types.' . ( $details->repStyle ?? 'nn' ));
            $properDetails->repPeriod      = $loc->get($locSta . 'repPeriod.types.' . ( $details->repPeriod ?? 'nn' ));
            $properDetails->sumOfPayments  = array_sum(array_column(( $details->payments ?? [] ), 'amount'));
            $properDetails->minRate        = min(array_column(( $details->rates ?? [] ), 'amount'));
            $properDetails->maxRate        = max(array_column(( $details->rates ?? [] ), 'amount'));
            $properDetails->generationDate = date('Y-m-d H:i:s');
            $properDetails->inForce        = ( ( isset($details->inForce) === true ) ? $details->inForce : 'no' );
        }

        // Get index and add current id.
        $index = $this->getIndex();

        // Add/change or delete.
        if ($details === null && isset($index->{$id}) === true) {
            unset($index->{$id});
        } else {
            $index->{$id} = $properDetails;
        }

        // Save index.
        $this->save($index);
    }

    /**
     * Delivers whole index.
     *
     * @return stdClass
     */
    public function getIndex(): stdClass
    {

        // Lvd.
        $uri = $this->getUri();

        // Deliver real index if it exists.
        if (file_exists($uri) === true) {
            return json_decode(file_get_contents($this->getUri()));
        }

        // Deliver empty otherwise.
        return new stdClass();
    }

    /**
     * Saves file.
     *
     * @param stdClass $index Index contents to be saved.
     *
     * @return void
     */
    private function save(stdClass $index): void
    {

        // Save file.
        file_put_contents($this->getUri(), json_encode($index, Json::stdWrite()));
    }

    /**
     * Delivers index file uri (see config `PRZESLIJMI_ADREPPLANPLUG_INDEX_URI`).
     *
     * @return string
     */
    private function getUri(): string
    {

        return $_ENV['PRZESLIJMI_ADREPPLANPLUG_INDEX_URI'];
    }
}
