<?php
namespace App\Http\Traits;
use DB;

trait TriggerTrait{

    public function onDeleteHost($host,$company_id){
        DB::table('company_host')
            ->where('host_id', $host->id)
            ->where('company_id', $company_id)
            ->delete();
    }
}