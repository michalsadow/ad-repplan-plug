<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug\Api;

use Przeslijmi\AgileData\Exceptions\Steps\StandardNodeValidationException;
use Przeslijmi\AgileData\Exceptions\Steps\ValidatePlug\NodeContentsMalformedException;
use Przeslijmi\AgileData\Exceptions\Steps\ValidatePlug\NodeIsEmptyException;
use Przeslijmi\AgileData\Exceptions\Steps\ValidatePlug\NodeIsMissingException;
use Przeslijmi\AgileData\Operations\CommonMethodsForOperations;
use Przeslijmi\AgileData\Steps\Helpers\StandardTestOfNodes;
use Przeslijmi\AgileDataClient\StandardResource;
use Przeslijmi\AgileDataRepplanPlug\Plan;
use stdClass;

/**
 * Company definitions to use in multiple api calls.
 */
class RepaymentPlan extends StandardResource
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
        'defaultSort' => [
            'sortable' => true,
            'defaultSort' => true,
        ],
        'name' => [
            'sortable' => true,
        ],
        'agreement' => [
            'sortable' => true,
        ],
        'agreementSort' => [
            'sortable' => true,
        ],
        'generationDate' => [
            'sortable' => true,
        ],
        'sumOfPayments' => [
            'sortable' => true,
        ],
        'minRate' => [
            'sortable' => true,
        ],
        'maxRate' => [
            'sortable' => true,
        ],
        'inForceLoc' => [
            'sortable' => true,
        ],
    ];

    /**
     * Delivers fields to prepare form of creating/editing silo.
     *
     * @return void
     */
    public function getCreateUpdateFields(): void
    {

        // Lvd.
        $loc     = $_ENV['LOCALE'];
        $locSta  = 'Przeslijmi.AgileDataRepplanPlug.repplans.fields.';
        $fields  = [];
        $planId  = $this->route->getParam('id', false);
        $planDef = [];

        // Lvd.
        $payments = [
            [
                'payments_date' => date('Y-m-d'),
                'payments_amount' => '0.00',
            ],
        ];
        $rates    = [
            [
                'rates_date' => date('Y-m-d'),
                'rates_amount' => '0.03',
            ],
        ];

        // Get definition of plan.
        if (empty($planId) === false) {

            // Define handler and get plan definition.
            $handler = new Plan($planId);
            $planDef = $handler->getDef();

            // Prepare payments.
            $payments = [];
            foreach ($planDef->payments as $payment) {
                $payments[] = [
                    'payments_date' => $payment->date,
                    'payments_amount' => $payment->amount,
                ];
            }

            // Prepare rates.
            $rates = [];
            foreach ($planDef->rates as $rate) {
                $rates[] = [
                    'rates_date' => $rate->date,
                    'rates_amount' => $rate->amount,
                ];
            }
        }//end if

        // Create fields.
        $fields[] = [
            'type' => 'select',
            'id' => 'repStyle',
            'value' => ( $planDef->repStyle ?? 'linear' ),
            'name' => $loc->get($locSta . 'repStyle.name'),
            'desc' => $loc->get($locSta . 'repStyle.desc'),
            'options' => [
                'linear' => $loc->get($locSta . 'repStyle.types.linear'),
                'annuit' => $loc->get($locSta . 'repStyle.types.annuit'),
                'annuitZero' => $loc->get($locSta . 'repStyle.types.annuitZero'),
                'baloon' => $loc->get($locSta . 'repStyle.types.baloon'),
            ],
            'group' => $loc->get('Przeslijmi.AgileData.tabs.general'),
        ];
        $fields[] = [
            'type' => 'switch',
            'id' => 'daily',
            'value' => 'no',
            'checked' => ( $this->yn[( $planDef->daily ?? 'no' )] ?? false ),
            'name' => $loc->get($locSta . 'daily.name'),
            'desc' => $loc->get($locSta . 'daily.desc'),
            'group' => $loc->get('Przeslijmi.AgileData.tabs.general'),
        ];
        $fields[] = [
            'type' => 'select',
            'id' => 'repPeriod',
            'value' => ( $planDef->repPeriod ?? 'monthly' ),
            'name' => $loc->get($locSta . 'repPeriod.name'),
            'desc' => $loc->get($locSta . 'repPeriod.desc'),
            'options' => [
                'monthly' => $loc->get($locSta . 'repPeriod.types.monthly'),
                'quarterly' => $loc->get($locSta . 'repPeriod.types.quarterly'),
                'halfYearly' => $loc->get($locSta . 'repPeriod.types.halfYearly'),
                'yearly' => $loc->get($locSta . 'repPeriod.types.yearly'),
                'baloon' => $loc->get($locSta . 'repPeriod.types.baloon'),
            ],
            'group' => $loc->get('Przeslijmi.AgileData.tabs.general'),
        ];
        $fields[] = [
            'type' => 'date',
            'id' => 'firstRep',
            'value' => ( $planDef->firstRep ?? null ),
            'name' => $loc->get($locSta . 'firstRep.name'),
            'desc' => $loc->get($locSta . 'firstRep.desc'),
            'group' => $loc->get('Przeslijmi.AgileData.tabs.general'),
        ];
        $fields[] = [
            'type' => 'date',
            'id' => 'lastRep',
            'value' => ( $planDef->lastRep ?? null ),
            'name' => $loc->get($locSta . 'lastRep.name'),
            'desc' => $loc->get($locSta . 'lastRep.desc'),
            'group' => $loc->get('Przeslijmi.AgileData.tabs.general'),
        ];
        $fields[] = [
            'type' => 'multi',
            'id' => 'payments',
            'allowAdding' => true,
            'allowDeleting' => true,
            'allowReorder' => true,
            'name' => $loc->get($locSta . 'payments.name'),
            'desc' => $loc->get($locSta . 'payments.desc'),
            'subFields' => [
                [
                    'type' => 'date',
                    'id' => 'payments_date',
                    'name' => $loc->get($locSta . 'payments.type.date'),
                ],
                [
                    'type' => 'text',
                    'id' => 'payments_amount',
                    'name' => $loc->get($locSta . 'payments.type.amount'),
                ],
            ],
            'values' => $payments,
            'group' => $loc->get('Przeslijmi.AgileData.tabs.general'),
        ];
        $fields[] = [
            'type' => 'multi',
            'id' => 'rates',
            'allowAdding' => true,
            'allowDeleting' => true,
            'allowReorder' => true,
            'name' => $loc->get($locSta . 'rates.name'),
            'desc' => $loc->get($locSta . 'rates.desc'),
            'subFields' => [
                [
                    'type' => 'date',
                    'id' => 'rates_date',
                    'name' => $loc->get($locSta . 'rates.type.date'),
                ],
                [
                    'type' => 'text',
                    'id' => 'rates_amount',
                    'name' => $loc->get($locSta . 'rates.type.amount'),
                ],
            ],
            'values' => $rates,
            'group' => $loc->get('Przeslijmi.AgileData.tabs.general'),
        ];
        $fields[] = [
            'type' => 'text',
            'id' => 'name',
            'value' => ( $planDef->name ?? $this->route->getAttributeIfExists('name') ?? '' ),
            'name' => $loc->get($locSta . 'name.name'),
            'desc' => $loc->get($locSta . 'name.desc'),
            'maxlength' => 255,
            'group' => $loc->get('Przeslijmi.AgileDataRepplanPlug.tabs.id'),
        ];
        $fields[] = [
            'type' => 'text',
            'id' => 'agreement',
            'value' => ( $planDef->agreement ?? $this->route->getAttributeIfExists('agreement') ?? '' ),
            'name' => $loc->get($locSta . 'agreement.name'),
            'desc' => $loc->get($locSta . 'agreement.desc'),
            'maxlength' => 255,
            'group' => $loc->get('Przeslijmi.AgileDataRepplanPlug.tabs.id'),
        ];
        $fields[] = [
            'type' => 'textarea',
            'id' => 'info',
            'value' => ( $planDef->info ?? '' ),
            'rows' => 5,
            'name' => $loc->get($locSta . 'info.name'),
            'desc' => $loc->get($locSta . 'info.desc'),
            'maxlength' => 255,
            'group' => $loc->get('Przeslijmi.AgileDataRepplanPlug.tabs.id'),
        ];

        // Create response.
        $this->sendJson([
            'status' => 'success',
            'data' => [
                'fields' => $fields,
            ],
        ]);
    }

    /**
     * Validates body of a plan.
     *
     * @param stdClass $body Plan body to validate.
     *
     * @return void
     */
    protected function validate(stdClass $body): void
    {

        // Lvd.
        $loc    = $_ENV['LOCALE'];
        $test   = new CommonMethodsForOperations();
        $locSta = 'Przeslijmi.AgileDataRepplanPlug.exc.add.';

        // Prevalidate.
        // Unpack fields.
        $body = $test::unpackMultiFieldsToRecords($body, 'payments');
        $body = $test::unpackMultiFieldsToRecords($body, 'rates');

        // Convert string to numbers.
        foreach ($body->payments as $no => $payment) {
            $body->payments[$no]->amount = (float) str_replace(',', '.', $payment->amount);
        }
        foreach ($body->rates as $no => $rate) {
            $body->rates[$no]->amount = (float) str_replace(',', '.', $rate->amount);
        }

        // Sort payments and rates.
        foreach ([ 'payments', 'rates' ] as $what) {
            array_multisort(array_column($body->{$what}, 'date'), SORT_ASC, $body->{$what});
        }

        // Make all tests.
        try {

            // Main tests.
            $test->testNodes('repplan', $body, [
                'repStyle' => [ '!stringEnum', [ 'linear', 'annuit', 'annuitZero', 'baloon' ] ],
                'daily' => [ '!stringEnum', [ 'yes', 'no' ] ],
                'repPeriod' => [ '!stringEnum', [ 'monthly', 'quarterly', 'halfYearly', 'yearly', 'baloon' ] ],
                'firstRep' => '!dateYmd',
                'lastRep' => '!dateYmd',
                'payments' => '!array',
                'rates' => '!array',
            ]);

            // Test payments.
            foreach ($body->payments as $no => $payment) {
                $test->testNodes('repplan.payments', $payment, [
                    'date' => '!dateYmd',
                    'amount' => '!positiveFloat',
                ]);
            }

            // Test rates.
            foreach ($body->rates as $no => $rate) {
                $test->testNodes('repplan.rates', $rate, [
                    'date' => '!dateYmd',
                    'amount' => '!positiveOrZeroRate',
                ]);
            }

            // Baloon repayment type and non-baloon frequency.
            if (
                ( $body->repStyle === 'baloon' && $body->repPeriod !== 'baloon' )
                || ( $body->repPeriod === 'baloon' && $body->repStyle !== 'baloon' )
            ) {
                $this->addError($loc->get('Przeslijmi.AgileDataRepplanPlug.exc.add.BaloonIsBaloon'));
            }

            // Unknown rate in the beginning.
            if ($body->payments[0]->date < $body->rates[0]->date) {
                $this->addError($loc->get('Przeslijmi.AgileDataRepplanPlug.exc.add.UnknownRateOnStart'));
            }

            // First repayment have to be later then first payment.
            if ($body->firstRep <= $body->payments[0]->date) {
                $this->addError($loc->get('Przeslijmi.AgileDataRepplanPlug.exc.add.FirstRepaymentTooEarly'));
            }

            // First repayment have to be later then first payment.
            if ($body->lastRep <= $body->firstRep) {
                $this->addError($loc->get('Przeslijmi.AgileDataRepplanPlug.exc.add.LastRepaymentTooEarly'));
            }

            // Last payment is ater last repayment.
            if ($body->payments[( count($body->payments) - 1 )]->date >= $body->lastRep) {
                $this->addError($loc->get('Przeslijmi.AgileDataRepplanPlug.exc.add.LastPaymentTooLate'));
            }

        } catch (NodeIsEmptyException | NodeIsMissingException | NodeContentsMalformedException $exc) {

            // Change status and remember error.
            $this->addError($loc->get(
                $locSta . $exc->getCodeName() . '.' . $exc->getInfos()['path'] . '.' . $exc->getInfos()['nodeName']
            ));

        } catch (PlugValidationException $exc) {

            // Change status and remember error.
            $this->addError($loc->get(
                $locSta . $exc->getCodeName()
            ));

        }//end try
    }
}
