<?php

declare(strict_types=1);

use Przeslijmi\AgileDataRepplanPlug\Api\AgreementsReadApi as AggrsR;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlan as Rppl;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlanCreateApi as RpplC;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlanDeforceApi as RpplDef;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlanDeleteApi as RpplD;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlanEnforceApi as RpplEnf;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlansReadApi as RpplsR;
use Przeslijmi\AgileDataRepplanPlug\Api\RepaymentPlanUpdateApi as RpplU;
use Przeslijmi\Sirouter\Sirouter as R;

// Lvd.
$p   = '/api/v1/repplangen/repplans';
$ps  = '/api/v1/repplangen/repplans/([a-zA-Z0-9]{12})';
$pf  = '/api/v1/repplangen/form-fields/repplan';
$pfs = '/api/v1/repplangen/form-fields/repplan/([a-zA-Z0-9]{12})';
$a   = '/api/v1/repplangen/agreements';
$aps = '/api/v1/repplangen/agreements/([0-9abcdef]{9,11})/repplans';

// Forms.
R::register($pf, 'GET')->setCall(Rppl::class, 'getCreateUpdateFields');
R::register($pfs, 'GET')->setCall(Rppl::class, 'getCreateUpdateFields')->setParam(0, 'id');

// Repayments plans.
R::register($p, 'GET')->setCall(RpplsR::class, 'get');
R::register($p, 'POST')->setCall(RpplC::class, 'post');
R::register($ps, 'DELETE')->setCall(RpplD::class, 'delete')->setParam(0, 'id');
R::register($ps, 'PUT')->setCall(RpplU::class, 'put')->setParam(0, 'id');
R::register($ps . '/deforce', 'PUT')->setCall(RpplDef::class, 'put')->setParam(0, 'id');
R::register($ps . '/enforce', 'PUT')->setCall(RpplEnf::class, 'put')->setParam(0, 'id');

// Agreements.
R::register($a, 'GET')->setCall(AggrsR::class, 'get');
R::register($aps, 'GET')->setCall(RpplsR::class, 'get')->setParam(0, 'aggrId');
