<?php

namespace App\Http\Controllers;

use App\Car;
use App\Driver;
use App\Employer;
use App\Shift;
use Illuminate\Http\Request;

class ShiftsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Car::where('company_id', auth('admin')->user()->adminable->id)
            ->whereNull('deleted_at')->get()->pluck('plate_number', 'id');
        $drivers = Driver::with('employer')
            ->where('company_id', auth('admin')->user()->adminable->id)
            ->whereNull('deleted_at')->get()->pluck('employer.username', 'id');
        $shifts = Shift::where('company_id', auth('admin')->user()->adminable->id)
            ->whereNull('deleted_at')->get();
        return view('companies.drivers.shifts', compact('drivers', 'cars', 'shifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'date' => 'required',
            'driver_id' => 'required',
            'car_id' => 'required',
            'shift_start_time' => 'required',
            'shift_end_time' => 'required',
        ]);
        $inputs = [
            'shift_start_time' => $request->date . ' ' . $request->shift_start_time,
            'shift_end_time' => $request->date . ' ' . $request->shift_end_time,
            'car_id' => $request->car_id,
            'company_id' => auth('admin')->user()->adminable->id,
            'driver_id' => $request->driver_id,
        ];

        Shift::create($inputs);
        if ($request->repeat) {
            $dateArray = generateDate($request->date);
            foreach ($dateArray as $array) {
                $inputs = [
                    'shift_start_time' => $array . ' ' . $request->shift_start_time,
                    'shift_end_time' => $array . ' ' . $request->shift_end_time,
                    'car_id' => $request->car_id,
                    'driver_id' => $request->driver_id,
                    'company_id' => auth('admin')->user()->adminable->id,
                ];
                Shift::create($inputs);
            }
        }
        flash()->success('Data was saved successfully');
        return redirect(route('shifts.index'));
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
        //
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
        //
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
