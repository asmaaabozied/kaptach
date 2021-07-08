<?php

namespace App\Http\Controllers;

use App\Helpers\BootForm;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Traits\SoftDeleteTrait;
use App\Station;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StationsController extends BaseController
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

            $stations = Station::whereNull('deleted_at')->get();

            return Datatables::of($stations)
                ->editColumn('status', function ($model) {
                    return $model->status == 1 ? 'Active' : 'blocked';
                })
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;
                    return BootForm::linkOfEdit('stations.edit', $model->id, $trashed, !$trashed)
                        . BootForm::linkOfRestore('stations.restore', $model->id, $model->name, $trashed)
                        . BootForm::linkOfDelete('stations.soft_delete', $model->id, $model->name, 'link', true, '', !$trashed)
                        . BootForm::routeLink('stations.changeStatus', $model->id, ['icon' => ($model->status == 1 ? 'fa-ban' : 'fa-check')])
                        . BootForm::linkOfPermanentDelete('stations.destroy', $model->id, $model->name, 'link', true, '', $trashed);

                })
                ->make(true);
        }
        return view('companies.stations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('companies.stations.create');
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
            'name' => 'required|unique:stations,name',
        ]);
        Station::create($request->all());
        flash()->success('Data was saved successfully');
        return redirect(route('stations.index'));
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
        $station = Station::findOrFail($id);
        return view('companies.stations.edit', compact('station'));
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
        $validator = $request->validate([
            'name' => 'required|unique:stations,name,'.$id,
        ]);
        $station=Station::find($id);
        if (!$station->update($request->all()))
            flash()->error('failed to update data, please try again later');
        flash()->success('Data was saved successfully');
        return redirect(route('stations.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete($id)
    {
        $station = Station::findOrFail($id);
        if ($this->softDeleteModel($station)) {
            $station->save();
            flash()->success('Data was deleted successfully');
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('stations.index');
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
        $station = Station::whereNull('deleted_at')->findOrFail($id);
        if ($station) {
            if ($station->status == 1)
                $station->status = 0;
            else
                $station->status = 1;
            $inputs['status'] = $station->status;
            if (!$station->update($inputs)) {
                flash()->error('Failed to save data, please try again later');
            }
            flash()->success('Data was saved successfully');
        }
        return redirect()->route('stations.index');
    }
}
