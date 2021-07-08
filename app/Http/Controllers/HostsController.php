<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Helpers\BootForm;
use App\Helpers\Files;
use App\Host;
use App\Http\Controllers\Controller;
use App\Http\Traits\SoftDeleteTrait;
use App\Employer;
use App\Company;
use App\Http\Traits\TriggerTrait;
use App\Jobs\ResizeImage;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class HostsController extends BaseController
{
    use SoftDeleteTrait;
    use TriggerTrait;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->all()) {

            $hosts = Host::with('employer', 'companies');
            if ($request->phone == "") {
                $hosts->whereHas('companies', function ($q) {
                    $q->where('company_id', auth('admin')->user()->adminable->id);
                });
            } else {
                $hosts->where('phone', $request->phone);
            }

            $hosts->whereNull('deleted_at')->get();
            return Datatables::of($hosts)
                ->editColumn('airport', function ($model) {
                    return $model->airport['name'];
                })
                ->editColumn('status', function ($model) use ($request) {
                    if ($request->phone == "") {
                        $employer_status = $model->companies()
                            ->first()->pivot->status;
                        return $employer_status;
                    } else
                        return '';

                })
                ->editColumn('profile_pic', function ($model) {
                    return "<img class='profile-user-img img-responsive img-circle'
                         src='" . asset('uploads/hosts/' . $model->employer->profile_pic) . "' alt='profile picture'>";
                })
                ->editColumn('phone', function ($model) {
                    return '(+09)' . $model->phone;
                })
                ->addColumn('action', function ($model) use ($request) {
                    if ($request->phone == "") {
                        $trashed = (bool)$request->trashed;
                        $employer_status = $model->companies()
                            ->first()->pivot->status;
                        if ($employer_status == 'approved')
                            $employer_status = 'Pending';
                        else
                            $employer_status = 'Approved';
                        return '<div class="btn-group">'
                            . '<button type="button" class="btn btn-default">Action</button>'
                            . '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">'
                            . '<span class="caret"></span>'
                            . '<span class="sr-only">Toggle Dropdown</span>'
                            . '</button>'
                            . '<ul class="dropdown-menu" role="menu">'
                            . '<li>' . BootForm::routeLink('hosts.change_status', $model->id, ['value' => $employer_status], true) . '<li>'
                            . '<li>' . BootForm::routeLink('hosts.edit', $model->id, ['value' => __('buttons.edit')]) . '<li>'
                            . '<li>' . BootForm::routeLink('hosts.reset_password', $model->id, ['value' => __('pages.reset_password')], true) . '<li>'
                            . '<li>' . BootForm::linkOfRestore('hosts.restore', $model->id, $model->name, $trashed) . '<li>'
                            . '<li>' . BootForm::linkOfDelete('hosts.soft_delete', $model->id, $model->name, 'link', true, 'Delete', !$trashed) . '<li>'
                            . '<li>' . BootForm::linkOfPermanentDelete('hosts.destroy', $model->id, $model->name, 'link', true, '', $trashed) . '<li>'
                            . '</ul>'
                            . '</div>';
                    } else {
                        return BootForm::routeLink('hosts.add_host', $model->id, ['value' => 'Add Host'], true);
                    }

                })->rawColumns(['profile_pic', 'action'])
                ->make(true);
        }
        return view('companies.hosts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $airports = Airport::whereNull('deleted_at')->get()->pluck('name', 'id');
        return view('companies.hosts.create', compact('airports'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @internal param Files $files
     */
    public function store(Request $request, Files $files)
    {
        $validator = $request->validate([
            'username' => 'required|unique:employers,username',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required:employers,password',
            'phone' => 'required|unique:employers,phone|regex:/[0-9]/',
            'email' => 'required|unique:employers,email',
            'airport_id' => 'required',
            'profile_pic' => 'image|max:3072',
        ]);
        $inputs = $request->all();
        //upload image
        if ($request->hasFile('profile_pic')) {
            $inputs['profile_pic'] = $files->uploadAndResizeImage($request->profile_pic, 'uploads/hosts', 200);
            $this->dispatch(new ResizeImage('hosts', $inputs['profile_pic']));
        }
        $inputs['type'] = 'host';
        $inputs['password'] = bcrypt($inputs['password']);
        $inputs['api_token'] = Str::random(80);
        $inputs['status'] = 'approved';
        $employer = Employer::create($inputs);
        if ($employer) {
            Host::create([
                'id' => $employer->id,
                'airport_id' => $request->airport_id,
                'gender' => $request->gender,
                'phone' => $request->phone
            ]);
            $host = Host::findOrFail($employer->id);
            $host->companies()->attach(auth('admin')->user()->adminable->id, ['status' => 'approved']);

            if ($host)
                flash()->success('Data was saved successfully');
            else
                flash()->error('failed to update data, please try again later');
        } else {
            flash()->error('failed to update data, please try again later');
        }
        return redirect(route('hosts.index'));
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
        $host = Employer::findOrFail($id);
        $airports = Airport::whereNull('deleted_at')->get()->pluck('name', 'id');
        return view('companies.hosts.edit', compact('host', 'airports'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Files $files)
    {
        $validator = $request->validate([
            'username' => 'required|unique:employers,username,' . $id,
            'phone' => 'required|regex:/[0-9]/|unique:employers,phone,' . $id,
            'email' => 'required|unique:employers,email,' . $id,
            'airport_id' => 'required'
        ]);

        $employer = Employer::find($id);

        $inputs = $request->all();
        //upload image
        if ($request->hasFile('profile_pic')) {
            $inputs['profile_pic'] = $files->uploadAndResizeImage($request->profile_pic, 'uploads/hosts', 200, $employer->getOriginal('profile_pic'));
            $this->dispatch(new ResizeImage('hosts', $inputs['profile_pic']));
        }
        if ($employer->update($inputs)) {
            $host = Host::find($id);
            $host->update($inputs);
            flash()->success('Data was saved successfully');
        } else {
            flash()->error('failed to update data, please try again later');
        }


        return redirect(route('hosts.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete($id)
    {
        $host = Host::with('companies')->findOrFail($id);
        $company_count = $host->companies()->count();
        $this->onDeleteHost($host, auth('admin')->user()->adminable->id);
        if ($company_count == 1) {
            if ($this->softDeleteModel($host)) {
                $host->save();
                $employer = Employer::findOrFail($id);
                $employer->update(['deleted_at' => \Carbon\Carbon::now()]);

            }
        }
        flash()->success('Data was deleted successfully');

        return redirect()->route('hosts.index');
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

    public function changeStatus($id)
    {
        $host = Host::with('companies')->findOrFail($id);
        if ($host) {
            $data = $host->companies()
                ->where('company_id', auth('admin')->user()->adminable->id)->first();

            if ($data->pivot->status == "approved")
                $inputs['status'] = "pending";
            else
                $inputs['status'] = "approved";

            $row = $host->companies()->updateExistingPivot(auth('admin')->user()->adminable->id, $inputs, false);
            if ($row)
                flash()->success('Data was saved successfully');
            else
                flash()->error('failed to update data, please try again later');
        }
        return redirect(route('hosts.index'));
    }

    public function ResetPassword($id)
    {
        $employer = Employer::findOrFail($id);
        return view('companies.reset_password', compact('employer'));
    }

    public function updateResetPassword(Request $request, $id, PushApi $pushApi)
    {
        $employer = Employer::findOrFail($id);
        $validator = $request->validate([
            'password' => 'required_with:password_confirmation|string|confirmed',
        ]);
        $inputs = $request->all();
        $inputs['password'] = bcrypt($inputs['password']);


        if ($employer->update($inputs)) {
            //send push notification
            if ($employer->device)
                $pushApi->sendAndroidPush($employer->device, ['message' => 'your password was updated']);
            flash()->success('Data was saved successfully');
        } else
            flash()->error('failed to update data, please try again later');
        return redirect(route('hosts.index'));
    }

    public function addHost($host_id)
    {
        $host = Host::findOrFail($host_id);
        $company = $host->companies()->where('company_id', auth('admin')->user()->adminable->id)->first();
        if (!$company) {
            $host->companies()->attach(auth('admin')->user()->adminable->id, ['status' => 'pending']);

            if ($host)
                flash()->success('Host was added to your company');
            else
                flash()->error('failed to update data, please try again later');
        } else
            flash()->error('Host already added');


        return redirect(route('hosts.index'));
    }
}
