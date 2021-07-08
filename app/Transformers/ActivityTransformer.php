<?php

namespace App\Transformers;

use App\Activity;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{
    public function transform(Activity $activity)
    {
        if ($activity->name == 'created_store')
            $id = $activity->subject->transfer->id;
        else if ($activity->name == 'deleted_store') {
            $desc = json_decode($activity->decription);
            $id = $desc->transfer_id;
        } else
            $id = $activity->subject->id;
        return [
            'name' => $activity->name,
            'id' => $id,
            "description" => call_user_func_array([$this, $activity->name], [$activity]),
            "lapse" => $activity->created_at->diffForHumans(),
        ];
    }

    protected function created_transfer(Activity $activity)
    {
        return "Admin created a transfer, " . $activity->subject->id;
    }

    protected function updated_transfer(Activity $activity)
    {
        return "Admin updated a transfer, " . $activity->subject->id;
    }

    protected function created_store(Activity $activity)
    {
        return "Admin offered for sale a transfer, " . $activity->subject->transfer->id;
    }
    protected function updated_store(Activity $activity)
    {
        return "Admin updated for sale a transfer, " .  $activity->subject->transfer->id;
    }
    protected function deleted_store(Activity $activity)
    {
        $desc = json_decode($activity->decription);
        $id = $desc->transfer_id;
        return "Admin undo offered for sale a transfer, " . $id;
    }
}