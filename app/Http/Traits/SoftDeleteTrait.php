<?php
namespace App\Http\Traits;

trait SoftDeleteTrait
{
    protected function softDeleteModel($model){
        $model->deleted_at = \Carbon\Carbon::now();
        if($model->save()){
            return true;
        }
        return false;
    }

    protected function restoreModel($model){
        $model->deleted_at = null;
        if($model->save()){
            return true;
        }
        return false;
    }

}