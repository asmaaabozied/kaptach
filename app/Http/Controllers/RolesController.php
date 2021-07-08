<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Helpers\BootForm;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RolesController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->all()) {

            $roles = Role::get();
            return Datatables::of($roles)
                ->addColumn('action', function ($model) use ($request) {
                    $admin = Admin::findOrFail(auth('admin')->user()->id);
                    $trashed = (bool)$request->trashed;
                    return BootForm::linkOfEdit('roles.edit', $model->id, $model->name, $admin->can('role-edit'))
                        . BootForm::linkOfShow('roles.show', $model->id, $model->name);
                })
                ->make(true);
        }
        return view('roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        return view('roles.create', compact('permissions'));
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
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);


        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        flash()->success('Data was saved successfully');
        return redirect(route('roles.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::with('permissions')->find($id);
        return view('roles.show',compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::get();
        foreach($role->permissions as $permission) {
            $rolePermissions[$permission->pivot->permission_id] = $permission->pivot->permission_id;
        }
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
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
            'name' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();


        $role->syncPermissions($request->input('permission'));
        flash()->success('Data was saved successfully');
        return redirect(route('roles.index'));
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
}
