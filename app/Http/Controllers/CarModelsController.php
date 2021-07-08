<?php

namespace App\Http\Controllers;

use App\Car;
use App\Car_model;
use App\Helpers\BootForm;
use App\Helpers\Files;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Traits\SoftDeleteTrait;
use App\Jobs\ResizeImage;
use function foo\func;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CarModelsController extends BaseController
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

            $carmodels = Car_model::whereNull('deleted_at')->get();

            return Datatables::of($carmodels)
                ->editColumn('model_name', function ($model) {
                    if ($model->image)
                        return $model->model_name . "<a href='" . $model->image['original'] . "' title='" . $model->model_name . "' class='cbox'><img src='" . $model->image['thumb'] . "' class='img-thumbnail img-responsive' id='img-preview'></a>";
                    else
                        return $model->model_name . "<img src='" . url('assets/img/no-image-available.jpg') . "' class='img-thumbnail img-responsive' id='img-preview'>";
                })
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;
                    return BootForm::linkOfEdit('carmodels.edit', $model->id, $trashed, !$trashed)
                        . BootForm::linkOfRestore('carmodels.restore', $model->id, $model->name, $trashed)
                        . BootForm::linkOfDelete('carmodels.soft_delete', $model->id, $model->name, 'link', true, '', !$trashed)
                        . BootForm::linkOfPermanentDelete('carmodels.destroy', $model->id, $model->name, 'link', true, '', $trashed);

                })->rawColumns(['model_name', 'action'])
                ->make(true);
        }
        return view('companies.cars.car_models.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('companies.cars.car_models.create');
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
        $request->validate([
            'model_name' => 'required',
            'max_bags' => 'required',
            'max_seats' => 'required',
            'image' => 'required|image|max:3072',
        ]);
        $inputs = $request->all();
        //upload image
        if ($request->hasFile('image')) {
            $inputs['image'] = $files->uploadAndResizeImage($request->image, 'uploads/car_models', 200);
            $this->dispatch(new ResizeImage('car_models', $inputs['image']));
        }
        if (!Car_model::create($inputs))
            flash()->error('failed to save data, please try again later');
        else
            flash()->success('Data was saved successfully');
        return redirect(route('carmodels.index'));
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
        $carmodel = Car_model::findOrFail($id);
        return view('companies.cars.car_models.edit', compact('carmodel'));
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
        $carmodel = Car_model::findOrFail($id);
        $request->validate([
            'model_name' => 'required',
            'max_bags' => 'required',
            'max_seats' => 'required',
            'image' => 'image|max:3072',
        ]);
        $inputs = $request->all();

        //upload image
        if ($request->hasFile('image')) {
            $inputs['image'] = $files->uploadAndResizeImage($request->image, 'uploads/car_models', 200, $carmodel->getOriginal('image'));
            $this->dispatch(new ResizeImage('car_models', $inputs['image']));
        }
        if (!$carmodel->update($inputs))
            flash()->error('failed to update data, please try again later');
        else
            flash()->success('Data was saved successfully');
        return redirect(route('carmodels.index'));
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
        $carmodel = Car_model::findOrFail($id);
        if ($this->softDeleteModel($carmodel)) {
            if ($carmodel->save()) {
                $this->onDeleteCarModel($carmodel);
            }
            flash()->success('Data was deleted successfully');
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('carmodels.index');
    }

    private function onDeleteCarModel($carmodel)
    {
        Car::where('car_model_id', $carmodel->id)
            ->update(['deleted_at' => \Carbon\Carbon::now()]);
        return true;
    }

    public function getCarModelByID(Request $request)
    {
        $carmodel = Car_model::findOrFail($request->id);

        return $carmodel->max_seats;
    }
}
