<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use App\Company;
use App\Helpers\Files;
use App\Jobs\ResizeImage;

class SettingsController extends BaseController
{

    public function company_settings()
    {
        $id = auth('admin')->user()->adminable->id;
        $update = Company::find($id);
        return view('companies.settings', compact('update'));
    }


    public function settings_update(Request $request, Files $files)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'image|max:3072'
        ]);
        $inputs = $request->all();
        if (auth('admin')->user()->type == 'transfer_company') {
            $company = Company::findOrFail(auth('admin')->user()->adminable->id);
            //upload image
            if ($request->hasFile('logo')) {
                $inputs['logo'] = $files->uploadAndResizeImage($request->logo, 'uploads/companies', 200, $company->getOriginal('image'));
                $this->dispatch(new ResizeImage('companies', $inputs['logo']));
            }
            if(!$request->receive_request_from_drivers){
                $inputs['receive_request_from_drivers']=0;
            }
            if (!$company->update($inputs))
                flash()->error('failed to update data, please try again later');
            else
                flash()->success('Data was saved successfully');
            return redirect(route('settings'));
        } else {
            $client = Client::findOrFail(auth('admin')->user()->adminable->id);
            //upload image
            if ($request->hasFile('logo')) {
                $inputs['logo'] = $files->uploadAndResizeImage($request->logo, 'uploads/clients', 200, $client->getOriginal('image'));
                $this->dispatch(new ResizeImage('clients', $inputs['logo']));
            }
            if (!$client->update($inputs))
                flash()->error('failed to update data, please try again later');
            else
                flash()->success('Data was saved successfully');
            return redirect(route('client_settings'));
        }

    }

    public function client_settings()
    {
        $id = auth('admin')->user()->adminable->id;
        $update = Client::find($id);
        return view('clients.settings', compact('update'));
    }

}
