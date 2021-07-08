<?php

namespace App\Events;

use App\Activity;
use App\Company;
use App\Driver;
use App\Transfer;
use App\Transformers\ActivityTransformer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ActivityLogged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    public function broadcastAs()
    {
        return 'activity.created';
    }

    public function broadcastOn()
    {
        if ($this->activity->name == 'deleted_store') {
            $desc = json_decode($this->activity->description);
            $id = $desc->transfer_id;
            $transfer = Transfer::with('company')->findOrFail($id);
            $admins = $transfer->company->admins()->whereNull('deleted_at')->get()->toArray();
            if ($desc->store_for == 2 || $desc->store_for == 3) {
                $companies = Company::with('admins')->whereNull('deleted_at')->get();
                foreach ($companies as $company) {
                    $c_admins = $company->admins()->whereNull('deleted_at')->get()->toArray();
                    array_merge($admins->toArray(), $c_admins->toArray());
                }
            }
        } elseif ($this->activity->name == 'created_store') {
            $admins = $this->activity->subject->company->admins()->whereNull('deleted_at')->get();

            if ($this->activity->subject->store_for == 2 || $this->activity->subject->store_for == 3) {
                $companies = Company::with('admins')->whereNull('deleted_at')->get();
                foreach ($companies as $company) {
                    $c_admins = $company->admins()->whereNull('deleted_at')->get();
                    array_merge($admins->toArray(), $c_admins->toArray());
                }

            }

        } else {
            $admins = $this->activity->subject->company->admins()->whereNull('deleted_at')->get();
        }

        $channels = [];
        foreach ($admins as $admin) {
            $channels[] = new PrivateChannel('activity.admin.' . $admin->id);
        }

        if ($this->activity->name == 'created_transfer' || $this->activity->name == 'updated_transfer'
            || $this->activity->name == 'created_store') {
            if ($this->activity->subject->driver) {
                $driver = $this->activity->subject->driver;
                $channels[] = new PrivateChannel('activity.driver.' . $driver->id);
            }
        } elseif ($this->activity->name == 'deleted_store') {
            if ($transfer->driver) {
                $channels[] = new PrivateChannel('activity.driver.' . $transfer->driver->id);
            }
            if ($desc->store_for == 1 || $desc->store_for == 3) {
                $drivers = Driver::whereNull('deleted_at')->get();
                if (isset($drivers))
                    foreach ($drivers as $driver) {
                        $channels[] = new PrivateChannel('activity.driver.' . $driver->id);
                    }
            }
        } elseif ($this->activity->name == 'created_store') {
            if ($this->activity->subject->store_for == 1 || $this->activity->subject->store_for == 3) {
                $drivers = Driver::whereNull('deleted_at')->get();
                if (isset($drivers))
                    foreach ($drivers as $driver) {
                        $channels[] = new PrivateChannel('activity.driver.' . $driver->id);
                    }
            }
        } else {
            $drivers = $this->activity->subject->company->drivers()->whereNull('deleted_at')->get();

            if (isset($drivers))
                foreach ($drivers as $driver) {
                    $channels[] = new PrivateChannel('activity.driver.' . $driver->id);
                }
        }
//        dd($channels);
        return $channels;
    }

    public function broadcastWith()
    {
        return fractal($this->activity, new ActivityTransformer())->toArray();
    }
}