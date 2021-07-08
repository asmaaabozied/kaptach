<?php

namespace App\Http\Controllers;

use App\Car;
use App\Car_model;
use App\Helpers\BootForm;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Traits\SoftDeleteTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CarsController extends BaseController
{
    use SoftDeleteTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->all()) {

            $cars = Car::with('carModel')
                ->where('company_id', auth('admin')->user()->adminable->id)
                ->whereNull('deleted_at')->get();

            return Datatables::of($cars)
                ->editColumn('carModel', function ($model) {
                    $text = '';
                    foreach ($model->carModel as $car_model) {
                        $text .= $car_model->model_name.'<br><i class="fa fa-male"></i> x ' . $car_model->max_seats .
                            ' <i class="fa fa-suitcase"></i> x ' . $car_model->max_bags.'<br>';
                    }
                    return $text;
                })
                ->editColumn('color', function ($model) {
                    return "<div  style='background-color:" . $model->color . "; position: absolute;
    height: 10%;
    width: 2%;'></span>";
                })->editColumn('status', function ($model) {
                    return $model->status == 1 ? 'Active' : 'blocked';
                })
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;
                    return BootForm::linkOfEdit('cars.edit', $model->id, $trashed, !$trashed)
                        . BootForm::linkOfRestore('cars.restore', $model->id, $model->name, $trashed)
                        . BootForm::linkOfDelete('cars.soft_delete', $model->id, $model->name, 'link', true, '', !$trashed)
                        . BootForm::linkOfPermanentDelete('cars.destroy', $model->id, $model->name, 'link', true, '', $trashed)
                        . BootForm::routeLink('cars.changeStatus', $model->id, ['icon' => ($model->status == 1 ? 'fa-ban' : 'fa-check')]);
                })->rawColumns(['carModel', 'action', 'color', 'name'])
                ->make(true);
        }
        return view('companies.cars.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $carmodels = Car_model::whereNull('deleted_at')
            ->get()->pluck('ModelWithSeats', 'id');
        return view('companies.cars.create', compact('carmodels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'car_model_id' => 'required',
            'color' => 'required'
        ]);
        $inputs = $request->all();
        $inputs['company_id'] = auth('admin')->user()->adminable->id;
        $car = Car::create($inputs);
        if (!$car)
            flash()->error('failed to save data, please try again later');
        else {
            $car->carModel()->sync($request->car_model_id);
            flash()->success('Data was saved successfully');
        }
        return redirect(route('cars.index'));
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
        $car = Car::findOrFail($id);
        $carmodels = Car_model::whereNull('deleted_at')->get();
        return view('companies.cars.edit', compact('car', 'carmodels'));
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
        $car = Car::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'car_model_id' => 'required',
            'color' => 'required'
        ]);
        $update = $car->update($request->all());
        if (!$update)
            flash()->error('failed to save data, please try again later');
        else {
            $car->carModel()->detach();
            $car->carModel()->attach($request->car_model_id);
            flash()->success('Data was saved successfully');
        }

        return redirect(route('cars.index'));
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

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete($id)
    {
        $car = Car::findOrFail($id);
        if ($this->softDeleteModel($car)) {
            $car->save();
            flash()->success('Data was deleted successfully');
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('cars.index');
    }

    public function changeStatus($car_id)
    {
        $car = Car::whereNull('deleted_at')->findOrFail($car_id);
        if ($car) {
            if ($car->status == 1)
                $car->status = 0;
            else
                $car->status = 1;
            $inputs['status'] = $car->status;
            if (!$car->update($inputs)) {
                flash()->error('Failed to save data, please try again later');
            }
            flash()->success('Data was saved successfully');
        }
        return redirect()->route('cars.index');
    }
}
