<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Company;
use App\Helpers\BootForm;
use App\Helpers\Files;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Traits\SoftDeleteTrait;
use App\Jobs\ResizeImage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;

/*use App\Helpers\Files;
use App\Jobs\ResizeImage;*/

class CompaniesController extends BaseController
{
    use SoftDeleteTrait;

    public function index(Request $request)
    {
        if ($request->all()) {

            $companies = Company::where('parent_id', auth('admin')->user()->adminable->id);
            $companies->whereNull('deleted_at')->get();

            return Datatables::of($companies)
                ->editColumn('status', function ($model) {
                    return $model->status == 1 ? 'Active' : 'Blocked';
                })->editColumn('logo', function ($model) {
                    return "<img class='profile-user-img img-responsive img-circle'
                         src='" . asset('uploads/companies/' . $model->logo) . "' alt='company logo'>";
                })->editColumn('type', function ($model) {
                    if ($model->type == 'commercial')
                        return __('pages.commercial');
                    else
                        return __('pages.personal');
                })
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;
                    return '<div class="btn-group">'
                        . '<button type="button" class="btn btn-default">Action</button>'
                        . '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">'
                        . '<span class="caret"></span>'
                        . '<span class="sr-only">Toggle Dropdown</span>'
                        . '</button>'
                        . '<ul class="dropdown-menu" role="menu">'
                        . '<li>' . BootForm::routeLink('companies.drivers', $model->slug, ['value' => __('pages.drivers')], !$trashed) . '</li>'
                        . '<li>' . BootForm::routeLink('companies.edit', $model->slug, ['value' => __('buttons.edit')], !$trashed) . '</li>'
                        . '<li>' . BootForm::linkOfRestore('companies.restore', $model->id, $model->name, $trashed) . '</li>'
                        . '<li>' . BootForm::linkOfDelete('companies.soft_delete', $model->id, $model->name, 'link', true, 'Delete', !$trashed) . '</li>'
                        . '<li>' . BootForm::routeLink('companies.changeStatus', $model->id, ['value' => ($model->status == 1 ? 'BLOCK' : 'ACTIVE')]) . '</li>'
//                        . '<li>' . BootForm::linkOfPermanentDelete('companies.destroy', $model->id, $model->name, 'link', true, '', $trashed) . '</li>'
                        . '</ul>'
                        . '</div>';

                })->rawColumns(['action', 'logo'])
                ->make(true);
        }
        return view('companies.index');
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|unique:companies,name',
            'username' => 'required|unique:admins,username',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required',
            'type' => 'required'
        ]);
        $companyData = [
            'name' => $request->name,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'type' => $request->type,
            'parent_id' => auth('admin')->user()->adminable->id
        ];
        $companyData['slug'] = Company::makeSlug($request->name);
        $company = Company::create($companyData);
        if (!$company) {
            flash()->error('failed to save data, please try again later');
        } else {

            $adminData = [
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 1
            ];
            $admin = $company->admins()->create($adminData);
//            $admin = Admin::create($adminData);
            $admin->assignRole([$request->role_id]);
            flash()->success('Data was saved successfully');
        }
        return redirect(route('companies.index'));

    }

    public function show($id)
    {
        //
    }

    public function edit($slug)
    {
        $company = Company::where('slug', $slug)->first();
        return view('companies.edit', compact('company'));
    }


    public function update(Request $request, Files $files, $id)
    {
        $company = Company::findOrFail($id);
        $validator = $request->validate([
            'name' => 'required|unique:companies,name,' . $id,
            'type' => 'required'
        ]);
        $inputs = $request->all();
        if ($request->hasFile('logo')) {
            $inputs['logo'] = $files->uploadAndResizeImage($request->logo, 'uploads/companies', 200);
            $this->dispatch(new ResizeImage('companies', $inputs['logo']));
        }
        if ($request->name != $company->name) {
            $inputs['slug'] = Company::makeSlug($request->name);
        }
        $update = $company->update($inputs);
        if ($update)
            flash()->success('Data was saved successfully');
        else
            flash()->error('failed to update data, please try again later');

        return redirect(route('companies.index'));
    }

    public function destroy($id)
    {
        //
    }

    public function softDelete($id)
    {
        $company = Company::findOrFail($id);
        if ($this->softDeleteModel($company)) {
            if ($company->save()) {
                $company->Admins()->update(['deleted_at' => \Carbon\Carbon::now()]);
                flash()->success('Data was deleted successfully');
            }
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('companies.index');
    }

    public function changeStatus($company_id)
    {
        $company = Company::whereNull('deleted_at')->findOrFail($company_id);
        if ($company) {
            if ($company->status == 1)
                $company->status = 0;
            else
                $company->status = 1;
            $inputs['status'] = $company->status;
            if (!$company->update($inputs)) {
                $company->admins()->update($inputs);
                flash()->error('Failed to save data, please try again later');
            }
            flash()->success('Data was saved successfully');
        }
        return redirect()->route('companies.index');
    }

    public function settings()
    {
        $update = Company::find(auth('admin')->user()->adminable->id);
        return view('companies.settings', compact('update'));
    }

    public function settings_update()
    {
        //return view('companies.settings');
    }

    public function getAllCompanies()
    {
        $companies = Company::
        where('id', '!=', auth('admin')->user()->adminable->id)
            ->whereNull('deleted_at')->get()->pluck('id', 'name');
        return $companies;
    }

    public function drivers($slug)
    {
        $company = Company::with('drivers')->where('slug', $slug)->first();
        return view('companies.drivers', compact('company'));
    }
}
