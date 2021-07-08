<?php

namespace App\Http\Controllers;

use App\Car;
use App\Car_model;
use App\Driver;
use App\Helpers\BootForm;
use App\Helpers\Files;
use App\Helpers\PushApi;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Traits\SoftDeleteTrait;
use App\Employer;
use App\Company;
use App\Http\Traits\TriggerTrait;
use App\Jobs\ResizeImage;
use App\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class DriversController extends BaseController
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

            $drivers = Driver::with('employer')
                ->where('company_id', auth('admin')->user()->adminable->id)
                ->whereNull('deleted_at')->get();
            return Datatables::of($drivers)
                ->editColumn('status', function ($model) {
                    $employer_status = $model->employer->status;
                    return $employer_status;
                })->editColumn('profile_pic', function ($model) {
                    return "<img class='profile-user-img img-responsive img-circle'
                         src='" . asset('uploads/drivers/' . $model->employer->profile_pic) . "' alt='profile picture'>";
                })
                ->editColumn('phone', function ($model) {
                    return '(+09)' . $model->phone;
                })
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;
                    $employer_status = $model->employer->status;
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
                        . '<li>' . BootForm::routeLink('drivers.change_status', $model->id, ['value' => $employer_status], true) . '<li>'
                        . '<li>' . BootForm::routeLink('drivers.edit', $model->id, ['value' => __('buttons.edit')]) . '<li>'
                        . '<li>' . BootForm::routeLink('drivers.reset_password', $model->id, ['value' => __('pages.reset_password')], true) . '<li>'
                        . '<li>' . BootForm::linkOfRestore('drivers.restore', $model->id, $model->name, $trashed) . '<li>'
                        . '<li>' . BootForm::linkOfDelete('drivers.soft_delete', $model->id, $model->name, 'link', true, 'Delete', !$trashed) . '<li>'
                        . '<li>' . BootForm::routeLink('drivers.schedule', $model->id, ['value' => __('pages.shifts')], true) . '<li>'
                        . '<li>' . BootForm::linkOfPermanentDelete('drivers.destroy', $model->id, $model->name, 'link', true, '', $trashed) . '<li>'
                        . '</ul>'
                        . '</div>';

                })->rawColumns(['profile_pic', 'action'])
                ->make(true);
        }
        return view('companies.drivers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('companies.drivers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Files $files
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Files $files)
    {
        $validator = $request->validate([
            'username' => 'required|unique:employers,username',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required:employers,password',
            'email' => 'required|unique:employers,email',
            'phone' => 'required|unique:employers,phone|regex:/[0-9]{9}/',
            'profile_pic' => 'required|max:3072',
        ]);

        $inputs = $request->all();

        //upload image
        if ($request->hasFile('profile_pic')) {
            $inputs['profile_pic'] = $files->uploadAndResizeImage($request->profile_pic, 'uploads/drivers', 200);
            $this->dispatch(new ResizeImage('drivers', $inputs['profile_pic']));
        }
        $inputs['type'] = 'driver';
        $inputs['password'] = bcrypt($inputs['password']);
        $inputs['api_token'] = Str::random(80);
        $inputs['company_id'] = auth('admin')->user()->adminable->id;
        $inputs['status'] = 'approved';
        $inputs['driver_type'] = 'commercial';
        if ($request->driver_type == 'personal') {
            //make company and admin for personal company
            $company = $this->curlApi($request);
            $inputs['company_id'] = $company->id;
            $inputs['driver_type'] = 'personal';
        }
        $employer = Employer::create($inputs);
        if ($employer) {
            $driver = Driver::create([
                'id' => $employer->id,
                'company_id' => $inputs['company_id'],
                'gender' => $request->gender,
                'phone' => $request->phone,
                'driver_type' => $inputs['driver_type']
            ]);
            if ($driver) {
                flash()->success('Data was saved successfully');
                if ($driver->driver_type == 'personal') {
                    $company = Company::with('admins')->findOrFail($company->id);
                    return view('companies.drivers.driver_info', compact('company'));
                }
            } else
                flash()->error('failed to update data, please try again later');
        } else
            flash()->error('failed to update data, please try again later');

        return redirect(route('drivers.index'));
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
        $driver = Employer::findOrFail($id);
        return view('companies.drivers.edit', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @param Files $files
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Files $files)
    {
        $validator = $request->validate([
            'username' => 'required|unique:employers,username,' . $id,
            'email' => 'required|unique:employers,email,' . $id,
            'phone' => 'required|regex:/[0-9]/|unique:employers,phone,' . $id,
        ]);
        $employer = Employer::find($id);
        $inputs = $request->all();
        //upload image
        if ($request->hasFile('profile_pic')) {
            $inputs['profile_pic'] = $files->uploadAndResizeImage($request->profile_pic, 'uploads/drivers', 200, $employer->getOriginal('profile_pic'));
            $this->dispatch(new ResizeImage('drivers', $inputs['profile_pic']));
        }

        if (!$employer->update($inputs)) {
            $driver = Driver::findOrFail($id);
            $driver->update($inputs);
            flash()->error('failed to update data, please try again later');
        } else {
            flash()->success('Data was saved successfully');
            $driver = Driver::findOrFail($id);
            $driver->update($inputs);
        }


        return redirect(route('drivers.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete($id)
    {
        $employer = Employer::findOrFail($id);
        if ($this->softDeleteModel($employer)) {
            $employer->save();
            $driver = Driver::findOrFail($id);
            $this->softDeleteModel($driver);
            $driver->save();
//            $this->onDeleteEmployer($driver);
            flash()->success('Data was deleted successfully');
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('drivers.index');
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

    /***
     * for schedule Driver
     */
    public function schedule($id)
    {
        $cars = Car::where('company_id', auth('admin')->user()->adminable->id)
            ->whereNull('deleted_at')->get()->pluck('plate_number', 'id');
        $employer = Employer::findOrFail($id);
        $shifts = Shift::where('company_id', auth('admin')->user()->adminable->id)
            ->where('driver_id', $id)->whereNull('deleted_at')->get();
        return view('companies.drivers.driver_schedule', compact('employer', 'cars', 'id', 'shifts'));
    }

    public function createSchedule($id, Request $request)
    {
        $validator = $request->validate([
            'date' => 'required',
            'car_id' => 'required',
            'shift_start_time' => 'required',
            'shift_end_time' => 'required',
        ]);
        $inputs = ['shift_start_time' => $request->date . ' ' . $request->shift_start_time,
            'shift_end_time' => $request->date . ' ' . $request->shift_end_time,
            'car_id' => $request->car_id,
            'company_id' => auth('admin')->user()->adminable->id,
            'driver_id' => $id,
        ];

        Shift::create($inputs);
        if ($request->repeat) {
            $dateArray = generateDate($request->date);
            foreach ($dateArray as $array) {
                $inputs = ['shift_start_time' => $array . ' ' . $request->shift_start_time,
                    'shift_end_time' => $array . ' ' . $request->shift_end_time,
                    'car_id' => $request->car_id,
                    'driver_id' => $id,
                    'company_id' => auth('admin')->user()->adminable->id,
                ];
                Shift::create($inputs);
            }
        }
        flash()->success('Data was saved successfully');
        return redirect(route('drivers.schedule', $id));
    }

    public function editSchedule($id)
    {
        $shift = Shift::findOrFail($id);
        if ($shift) {
            $cars = Car::where('company_id', auth('admin')->user()->adminable->id)
                ->whereNull('deleted_at')->get()->pluck('plate_number', 'id');

            return view('companies.drivers.edit_schedule', compact('shift', 'cars'));
        }
        return redirect(route('drivers.index'));

    }

    public function updateSchedule($id, Request $request)
    {
        $shift = Shift::findOrFail($id);
        if ($shift) {
            $validator = $request->validate([
                'car_id' => 'required',
                'shift_start_time' => 'required',
                'shift_end_time' => 'required',
            ]);
            $date = date('Y-m-d', strtotime($shift->shift_start_time));
            $inputs = [
                'shift_start_time' => $date . ' ' . $request->shift_start_time,
                'shift_end_time' => $date . ' ' . $request->shift_end_time,
                'car_id' => $request->car_id,
            ];
            if ($shift->update($inputs)) {
                flash()->success('Data was saved successfully');
                return redirect(route('drivers.schedule', $shift->driver_id));
            }


        }
        return redirect(route('drivers.index'));
    }

    public function softDeleteSchedule($id)
    {
        $shift = Shift::findOrFail($id);
        if ($this->softDeleteModel($shift)) {
            $shift->save();
//            $this->onDeleteEmployer($shift);
            flash()->success('Data was deleted successfully');
        }
        return redirect(route('drivers.schedule', $shift->driver_id));
    }

    public function changeDriver($id)
    {
        $shift = Shift::findOrFail($id);
        if ($shift) {
            $all_drivers = Driver::where('type', 'driver')
                ->where('company_id', auth('admin')->user()->adminable->id)
                ->whereNull('deleted_at')->get();
//            $all_drivers = Employer::where('type', 'driver')->whereHas('companies', function ($q) {
//                $q->where('corporate_id', auth('admin')->user()->corporate_id);
//            })->whereNull('deleted_at')->get()->pluck('username', 'id');
            $cars = Car::where('company_id', auth('admin')->user()->adminable->id)
                ->whereNull('deleted_at')->get()->pluck('name', 'id');

            return view('companies.drivers.change_driver', compact('driver', 'all_drivers', 'shifts', 'cars'));
        }
        return redirect(route('drivers.index'));
    }


    public function getDriverShift(Request $request)
    {
        $str = $this->driverShifts($request->all());
        return response()->json($str);
    }

    public function changeStatus($id)
    {
        $employer = Employer::findOrFail($id);
        if ($employer) {
//            $data = $employer->companies()
//                ->where('corporate_id', auth('admin')->user()->corporate_id)->first();
            if ($employer->status == "approved")
                $inputs['status'] = "pending";
            else
                $inputs['status'] = "approved";
            $row = $employer->update($inputs);
//            $row = $employer->companies()->updateExistingPivot(auth('admin')->user()->corporate_id, $inputs, false);
            if ($row)
                flash()->success('Data was saved successfully');
            else
                flash()->error('failed to update data, please try again later');
        }
        return redirect(route('drivers.index'));
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
        return redirect(route('drivers.index'));
    }

    private function curlApi(Request $request)
    {
        $baseUri = "http://localhost:8080/kaptan/public/api/v1/companies/create";
        $headers = array(
            'Content-Type: application/json'
        );
        $fields = array(
            'username' => $request->username,
            'contact_phone' => $request->phone,
            'contact_email' => $request->email
        );
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $baseUri);
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
        return json_decode($result);
    }
}
