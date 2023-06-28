<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug\Api;

use Przeslijmi\AgileDataClient\StandardResource;

/**
 * Company definitions to use in multiple api calls.
 */
class Agreement extends StandardResource
{

    /**
     * List of all possible fields.
     *
     * @var array
     */
    protected $fields = [
        'id' => [
            'sortable' => true,
        ],
        'fullName' => [
            'sortable' => true,
            'defaultSort' => true,
        ],
        'name' => [
            'sortable' => true,
        ],
        'agreement' => [
            'sortable' => true,
        ],
        'count' => [
            'sortable' => true,
        ],
        'latest' => [
            'sortable' => true,
        ],
        'latestInForce' => [
            'sortable' => true,
        ],
        'countInForce' => [
            'sortable' => true,
        ],
    ];
}
