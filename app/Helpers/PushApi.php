<?php

namespace App\Helpers;


use App\Device;

class PushApi
{
    protected $baseUri = 'https://fcm.googleapis.com/fcm/send';
    protected $fireBaseApiKey = 'AAAA6ybXaWQ:APA91bGYHR5nf0Izd71WO1PqpqAxewUFXKQ6aHxuFp5gs1ZF1wOb1kghmcrjyEv5bWAU0OdeI2Le6JulOnqzO1gJ3Es8_BVmocS1n9TsBACvpJ124KT6Zic4RhR_6-AFVNwKII8Qhvn7';

    function sendAndroidPush($device, $notification)
    {
        $headers = array(
            'Authorization: key=' . $this->fireBaseApiKey,
            'Content-Type: application/json'
        );
//        $action = ['action' => '/transfers', 'title' => 'View','action'=>'no','title'=>'Cancel'];
        $data = array(
            'message' => $notification['message'],
            'title' => $notification['title'],
//            "actions-data" =>json_encode(['actions'=>json_encode($action)])
//            'json-data'=>['actions'=>['action'=>'coffee-action','title'=>'Coffee']]
            "actions-data" => "{\"actions\": [{\"action\":\"transfers\", \"title\":\"view\"},{\"action\":\"no\",\"title\":\"cancel\"}]}"
        );
        if (isset($notification['id']))
            $data['id'] = $notification['id'];

        if (isset($notification['transfer_id']))
            $data['transfer_id'] = $notification['transfer_id'];

        if (isset($notification['transfer_start_time']))
            $data['transfer_start_time'] = $notification['transfer_start_time'];

        $fields = array('registration_ids' => [$device->token], 'data' => $data);
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $this->baseUri);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return $result;
    }

    function addDevice($model, $token, $platform, $locale = 'tr')
    {
        $inputs = [
            'token' => $token,
            'platform' => $platform,
            'local' => $locale
        ];
        $device = new Device($inputs);
        $model->device()->save($device);
        return true;
    }

    function deleteDevice($token)
    {
        $device = Device::where('token', $token)->first();
        if ($device)
            $device->delete();
        return true;
    }
}