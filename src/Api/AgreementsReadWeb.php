<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug\Api;

use Przeslijmi\AgileDataRepplanPlug\Api\Agreement;

/**
 * Resource showing page with list of fieldses.
 */
class AgreementsReadWeb extends Agreement
{

    /**
     * Answers for GET list of fieldses on Api.
     *
     * @return void
     */
    public function get(): void
    {

        // Preparations.
        $this->prepareSwts('agreements', ( dirname(dirname(dirname(__FILE__))) . '/tpl/' ));

        // Show errors if there are new.
        if ($this->countErrors() > 0) {
            $this->showErrors('web');
            return;
        }

        // Print parsed contents.
        echo $this->swts->parse();
    }
}
