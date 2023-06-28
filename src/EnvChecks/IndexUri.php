<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug\EnvChecks;

use Przeslijmi\AgileData\Configure\EnvChecks\EnvChecksParent;

/**
 * Checks if given ENV value is proper.
 */
class IndexUri extends EnvChecksParent
{

    /**
     * Standard actions to be performed on ENV.
     *
     * @var array
     */
    protected static $actions = [
        [ 'convertSlashesTo', '/' ],
        [ 'trim', ' ' ],
    ];

    /**
     * Standard rules to be checked.
     *
     * @var array
     */
    protected static $rules = [
        'dataType' => 'string',
        'canBeEmpty' => false,
        'secureUri' => true,
        'jsonFileContents' => true,
    ];
}
