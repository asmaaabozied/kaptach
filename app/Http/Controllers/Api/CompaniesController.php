<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\Company;
use App\Driver;
use App\Host;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Auth;

class CompaniesController extends Controller
{
    public $successStatus = 200;

    public function index()
    {
        $data['companies'] = Company::select('id', 'name', 'type')
            ->whereNull('deleted_at')
            ->where('type', 'Commercial')
            ->where('receive_request_from_drivers', 1)->get()->toArray();

        return response()->json($data, $this->successStatus);
    }

    public function generateCompany(Request $request)
    {
        $companyData = [
            'name' => $request->username,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'type' => 'personal',
            'parent_id' => 1,
            'status' => 1
        ];
        $companyData['slug'] = Company::makeSlug($request->username);
        $company = Company::create($companyData);
        if ($company)
            if ($this->generateAdmin($company))
                return $company;
            else
                return false;
        else
            return false;
    }

    private function generateAdmin(Company $company)
    {
        $adminData = [
            'username' => Admin::generateUsername($company->name),
            'email' => $company->contact_email,
            'password' => Hash::make('123456'),
            'role_id' => 1,
            'status' => 0
        ];
        $admin = $company->admins()->create($adminData);
        if ($admin)
            return true;
        else
            return false;
    }

    public function FindHosts($airport_id)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $driver = Driver::findOrFail($auth_id);
            $hosts = Host::whereHas('companies', function ($q) use ($driver) {
                $q->where('company_id', $driver->company_id);
            })->where('airport_id',$airport_id)
                ->whereNull('deleted_at')->first();

            if ($hosts) {
                return response()->json(['found' => true]);
            } else {
                return response()->json(['found' => false]);
            }

    }
}
