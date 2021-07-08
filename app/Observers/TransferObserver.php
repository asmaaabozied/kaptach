<?php

namespace App\Observers;

use App\Employer;
use App\Helpers\PushApi;
use App\Shift;
use App\Transfer;
use Log;

class TransferObserver
{
    /**
     * Handle the transfer "created" event.
     *
     * @param  \App\Transfer $transfer
     * @return void
     */
    public function created(Transfer $transfer)
    {

        $pushApi = new PushApi;
        $time_transfer = date('H:i', strtotime($transfer->transfer_start_time));
        $notification = [
            'message' => 'Transfer was created in time ' . $time_transfer,
            'title' => 'New Transfer',
            'id' => 1,
            'transfer_id' => $transfer->id,
            'transfer_start_time' => $transfer->transfer_start_time
        ];
        try {
            if ($transfer->host_id) {
                $host = Employer::findOrFail($transfer->host_id);
                if ($host->device->token != '')
                    $pushApi->sendAndroidPush($host->device, $notification);
            }
            if ($transfer->shift_id) {
                $driver = Employer::findOrFail($transfer->shift->employer->id);
                if ($driver->device->token != '')
                    $pushApi->sendAndroidPush($driver->device, $notification);
            }
        } catch (\Exception $ex) {
            Log::debug($ex->getMessage());
        }


    }

    /**
     * Handle the transfer "updated" event.
     *
     * @param  \App\Transfer $transfer
     * @return void
     */
    public function updated(Transfer $transfer)
    {

    }

    /**
     * Handle the transfer "deleted" event.
     *
     * @param  \App\Transfer $transfer
     * @return void
     */
    public function deleted(Transfer $transfer)
    {

        $pushApi = new PushApi;
        $notification = [
            'message' => 'Transfer was deleted',
            'title' => 'deleted',
            'id' => 3,
            'transfer_id' => $transfer->id,
            'transfer_start_time' => $transfer->transfer_start_time
        ];
        try {
            if ($transfer->host_id) {
                $host = Employer::findOrFail($transfer->host_id);
                if ($host->device->token)
                    $pushApi->sendAndroidPush($host->device, $notification);
            }
            if ($transfer->shift_id) {
                $driver = Employer::findOrFail($transfer->shift->employer->id);
                if ($driver->device->token)
                    $pushApi->sendAndroidPush($driver->device, $notification);
            }
        } catch (\Exception $ex) {
            Log::debug($ex->getMessage());
        }
    }

    /**
     * Handle the transfer "restored" event.
     *
     * @param  \App\Transfer $transfer
     * @return void
     */
    public function restored(Transfer $transfer)
    {
        //
    }

    /**
     * Handle the transfer "force deleted" event.
     *
     * @param  \App\Transfer $transfer
     * @return void
     */
    public function forceDeleted(Transfer $transfer)
    {
        //
    }
}
