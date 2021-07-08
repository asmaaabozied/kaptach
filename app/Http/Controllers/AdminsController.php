<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Client;
use App\Company;
use App\Helpers\BootForm;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use App\Helpers\Files;
use App\Jobs\ResizeImage;

class AdminsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->all()) {
            if (auth('admin')->user()->type == 'transfer_company') {
                $company = Company::findOrFail(auth('admin')->user()->adminable->id);
                $admins = $company->admins()->whereNull('deleted_at');
            } else {
                $client = Client::findOrFail(auth('admin')->user()->adminable->id);
                $admins = $client->admins()->whereNull('deleted_at');
            }
            $admins->get();

            return Datatables::of($admins)
                ->editColumn('status', function ($model) {
                    return $model->status == 1 ? 'Active' : 'blocked';
                })->editColumn('role', function ($model) {
                    return $model->role->name;
                })
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;
                    return BootForm::linkOfEdit('admins.edit', $model->id, $model->username, !$trashed)
                        . BootForm::linkOfRestore('admins.restore', $model->id, $model->username, $trashed)
                        . BootForm::linkOfDelete('admins.soft_delete', $model->id, $model->username, 'link', true, '', !$trashed)
                        . BootForm::routeLink('admins.changeStatus', $model->id, ['icon' => ($model->status == 1 ? 'fa-ban' : 'fa-check')])
                        . BootForm::linkOfPermanentDelete('admins.destroy', $model->id, $model->username, 'link', true, '', $trashed);

                })
                ->make(true);
        }
        return view('admins.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get()->pluck('name', 'id');
        return view('admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:admins,username',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required',
            'role_id' => 'required'
        ]);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['type'] = auth('admin')->user()->type;
        $input['role_id'] = $request->role_id;
        if (auth('admin')->user()->type == 'transfer_company') {
            $company = Company::findOrFail(auth('admin')->user()->adminable->id);
            $company->admins()->create($input);
        } else {
            $client = Client::findOrFail(auth('admin')->user()->adminable->id);
            $client->admins()->create($input);
        }
        flash()->success('Data was saved successfully');
        return redirect(route('admins.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::find($id);
        $roles = Role::get()->pluck('name', 'id');
        return view('admins.edit', compact('roles', 'admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'username' => 'required|unique:admins,username,' . $id,
            'email' => 'required|email|unique:admins,email,' . $id,
            'role_id' => 'required'
        ]);
        $input = $request->all();
        $admin = Admin::find($id);
        $role = Role::findOrFail($request->role_id);
        $admin->roles()->updateExistingPivot($role, ['role_id' => $request->role_id], false);
        $admin->update($input);
        flash()->success('Data was saved successfully');
        return redirect(route('admins.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function change_password()
    {
        $admin = Admin::find(auth('admin')->user()->id);
        return view('admins.change_password', compact('admin'));
    }

    public function update_password(Request $request)
    {

        $admin = Admin::find(auth('admin')->user()->id);

        $validator = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
            'new_password_confirmation' => 'required',
        ]);
        $input = $request->all();
        //check old password        
        if (Hash::check($input['old_password'], $admin->password)) {
            $check = true;
            $input['password'] = bcrypt($input['new_password']);
            if ($admin->update($input))
                flash()->success('Data was saved successfully');
            return redirect(route('change_password'));
        } else {
            // Wrong one
            $check = false;
            flash()->error('Password Doesn\'t Mach Try Again!');
            return redirect(route('change_password'));
        }

    }

    public function resetPassword($id)
    {
        $admin = Admin::findOrFail($id);
        if ($admin) {
            $update = $admin->update(['password' => bcrypt('123456')]);
            if ($update)
                flash()->success('Data was saved successfully');
            else
                flash()->error('failed to update data, please try again later');

            if (!$admin->adminable->is(auth('admin')->user()->adminable))
                return redirect(route('my-clients.admins', $admin->adminable->slug));
            else
                return redirect(route('admins.index'));
        } else {
            return redirect(route('my-clients.admins', auth('admin')->user()->adminable->slug));

        }

    }

    public function profile()
    {
        $admin = Admin::find(auth('admin')->user()->id);
        return view('admins.profile', compact('admin'));
    }

    public function profile_update(Request $request, Files $files)
    {
        $id = auth('admin')->user()->id;
        $this->validate($request, [
            'username' => 'required|unique:admins,username,' . $id,
            'email' => 'required|email|unique:admins,email,' . $id,
            'image' => 'image|max:3072',
        ]);
        $admin = Admin::findOrFail($id);
        $inputs = $request->all();
        //upload image
        if ($request->hasFile('image')) {
            $inputs['image'] = $files->uploadAndResizeImage($request->image, 'uploads/admins', 200, $admin->getOriginal('image'));
            $this->dispatch(new ResizeImage('admins', $inputs['image']));
        }
        if (!$admin->update($inputs))
            flash()->error('failed to update data, please try again later');
        else
            flash()->success('Data was saved successfully');

        return redirect(route('profile'));
    }

}
