<?php

namespace App\Repositories\Interface;

interface InstallmentPlanInterface
{
    public function storePlan($request);
    public function plansIndex($request);
    public function planStatus($id);
    public function myNegativeBalance();
    public function negativeBalanceStore($request);
    public function balanceRequests($request);
    public function balanceRequest($id);
    public function balanceRequestsDatatable($request);
    public function requestUpdate($request,$id);

}
