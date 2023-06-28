<?php

declare(strict_types=1);

use Przeslijmi\AgileDataRepplanPlug\Api\AgreementsReadWeb as AggrsR;
use Przeslijmi\AgileDataRepplanPlug\Api\AgreementsRepaymentPlanReadWeb as AgrpsR;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlanCloneWeb as RpplClo;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlanCreateWeb as RpplC;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlanDeforceWeb as RpplDef;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlanDeleteWeb as RpplD;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlanEnforceWeb as RpplEnf;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlansReadWeb as RpplsR;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlanUpdateWeb as RpplU;
use Przeslijmi\Sirouter\Sirouter as R;


// Lvd.
$h   = '/dane/harmonogramy';
$hs  = '/dane/harmonogramy/([0-9a-zA-Z]{12})';
$u   = '/dane/harmonogramy-po-umowach';
$us  = '/dane/harmonogramy-po-umowach/([0-9abcdef]{9,11})';
$uhs = '/dane/harmonogramy-po-umowach/([0-9abcdef]{9,11})/([0-9a-zA-Z]{12})';

// Main page.
R::register($h, 'GET')->setCall(RpplsR::class, 'get');
R::register($h . '/dodaj', 'GET')->setCall(RpplC::class, 'get');
R::register($hs . '/edytuj', 'GET')->setCall(RpplU::class, 'get')->setParam(0, 'id');
R::register($hs . '/kasuj', 'GET')->setCall(RpplD::class, 'get')->setParam(0, 'id');
R::register($hs . '/powiel', 'GET')->setCall(RpplClo::class, 'get')->setParam(0, 'id');
R::register($hs . '/niech-obowiazuje', 'GET')->setCall(RpplEnf::class, 'get')->setParam(0, 'id');
R::register($hs . '/niech-nieobowiazuje', 'GET')->setCall(RpplDef::class, 'get')->setParam(0, 'id');

// Plans per agreement.
R::register($u, 'GET')->setCall(AggrsR::class, 'get');
R::register($us, 'GET')->setCall(AgrpsR::class, 'get')->setParam(0, 'aggrId');
R::register($uhs . '/edytuj', 'GET')->setCall(RpplU::class, 'get')->setParam(0, 'aggrId')->setParam(1, 'id');
R::register($uhs . '/kasuj', 'GET')->setCall(RpplD::class, 'get')->setParam(0, 'aggrId')->setParam(1, 'id');
R::register($uhs . '/powiel', 'GET')->setCall(RpplClo::class, 'get')->setParam(0, 'aggrId')->setParam(1, 'id');
R::register($uhs . '/niech-obowiazuje', 'GET')->setCall(RpplEnf::class, 'get')->setParam(0, 'aggrId')->setParam(1, 'id');
R::register($uhs . '/niech-nieobowiazuje', 'GET')->setCall(RpplDef::class, 'get')->setParam(0, 'aggrId')->setParam(1, 'id');
