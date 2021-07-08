<?php

namespace App\Http\Controllers\Companies;

use App\Helpers\BootForm;
use App\Helpers\Files;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Traits\SoftDeleteTrait;
use App\Jobs\ResizeImage; 
use function foo\func; 
use App\Airport;
use App\Station;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AirportsController extends BaseController
{
    use SoftDeleteTrait;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->all()) {
            $airports = Airport::with('station');
            $airports->whereNull('deleted_at');

            return Datatables::of($airports)
                ->editColumn('station.name', function ($model) {
                    return $model->station['name'];
                })
                ->editColumn('arrival_image', function ($model) {
                    if ($model->arrival_image)
                        return  "<a href='" . $model->arrival_image['original'] . "' title='" . $model->name . "' class='cbox'><img src='" . $model->arrival_image['thumb'] . "' class='img-thumbnail img-responsive' id='img-preview'></a>";
                    else
                        return  "<img src='" . url('assets/img/no-image-available.jpg') . "' class='img-thumbnail img-responsive' id='img-preview'>";
                })
                ->editColumn('departure_image', function ($model) {
                    if ($model->departure_image)
                        return  "<a href='" . $model->departure_image['original'] . "' title='" . $model->name . "' class='cbox'><img src='" . $model->departure_image['thumb'] . "' class='img-thumbnail img-responsive' id='img-preview'></a>";
                    else
                        return "<img src='" . url('assets/img/no-image-available.jpg') . "' class='img-thumbnail img-responsive' id='img-preview'>";
                })
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;
                    return BootForm::linkOfEdit('airports.edit', $model->id, $trashed, !$trashed)
                        . BootForm::linkOfRestore('airports.restore', $model->id, $model->name, $trashed)
                        . BootForm::linkOfDelete('airports.soft_delete', $model->id, $model->name, 'link', true, '', !$trashed)
                        . BootForm::linkOfPermanentDelete('airports.destroy', $model->id, $model->name, 'link', true, '', $trashed);

                })->rawColumns(['arrival_image','departure_image', 'action'])
                ->make(true);
        }
        return view('companies.airports.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stations = Station::get()->pluck('name', 'id');
        return view('companies.airports.create', compact('stations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Files $files)
    {
        $validator = $request->validate([
            'name' => 'required|unique:airports,name',
            'station_id' => 'required',
            'arrival_image' => 'required|image|max:3072',
            'departure_image' => 'required|image|max:3072',
            'address' => 'required'
        ]);  
        $inputs = $request->all();      
        //upload image
        if ($request->hasFile('arrival_image')) {
            $inputs['arrival_image'] = $files->uploadAndResizeImage($request->arrival_image, 'uploads/airports', 200);
            $this->dispatch(new ResizeImage('airports', $inputs['arrival_image']));
        }
        if ($request->hasFile('departure_image')) {
            $inputs['departure_image'] = $files->uploadAndResizeImage($request->departure_image, 'uploads/airports', 200);
            $this->dispatch(new ResizeImage('airports', $inputs['departure_image']));
        }
        if (!Airport::create($inputs))
            flash()->error('failed to save data, please try again later');
        else
            flash()->success('Data was saved successfully');
       return redirect(route('airports.index'));

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
        $stations = Station::get()->pluck('name', 'id');
        $airport = Airport::findOrFail($id);
        return view('companies.airports.edit', compact('airport'), compact('stations'));
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
        $airport = Airport::find($id);
        $validator = $request->validate([
            'name' => 'required|unique:airports,name,' . $id,
            'station_id' => 'required',
            'address' => 'required',
            'arrival_image' => 'image|max:3072',
            'departure_image' => 'image|max:3072',
        ]);
        $inputs = $request->all();
        //upload image
        if ($request->hasFile('arrival_image')) {
            $inputs['arrival_image'] = $files->uploadAndResizeImage($request->arrival_image, 'uploads/airports', 200);
            $this->dispatch(new ResizeImage('airports', $inputs['arrival_image']));
        }
        if ($request->hasFile('departure_image')) {
            $inputs['departure_image'] = $files->uploadAndResizeImage($request->departure_image, 'uploads/airports', 200);
            $this->dispatch(new ResizeImage('airports', $inputs['departure_image']));
        }
        if (!$airport->update($inputs))
            flash()->error('failed to update data, please try again later');
        else
            flash()->success('Data was saved successfully');
        return redirect(route('airports.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete($id)
    {
        $airport = Airport::findOrFail($id);
        if ($this->softDeleteModel($airport)) {
            $airport->save();
            flash()->success('Data was deleted successfully');
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('airports.index');
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
