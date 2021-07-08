<?php

namespace App\Http\Controllers\Companies;

use App\Car_model;
use App\Helpers\BootForm;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Traits\SoftDeleteTrait;
use App\Tour_price_list;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ToursPriceController extends BaseController
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

            $tours_prices = Tour_price_list::with('carModel')
                ->where('company_id', auth('admin')->user()->adminable->id)
                ->whereNull('deleted_at')->get();

            return Datatables::of($tours_prices)
                ->editColumn('with_food', function ($model) {
                    if ($model->with_food == 1)
                        return "<i class='fa fa-check'></i>";
                    else
                        return "<i class='fa fa-ban'></i>";
                })
                ->editColumn('carModel.model_name', function ($model) {
                    $text = '';
                    $text .= $model->carModel['model_name'];
                    if ($model->carModel['image'])
                        $text .= "<a href='" . $model->carModel['image']['original'] . "' title='" . $model->carModel['model_name'] . "' class='cbox'><img src='" . $model->carModel['image']['thumb'] . "' class='img-thumbnail img-responsive' id='img-preview'></a>";
                    else
                        $text .= "<img src='" . url('assets/img/no-image-available.jpg') . "' class='img-thumbnail img-responsive' id='img-preview'>";
                    $text .= '<br><i class="fa fa-male"></i> x ' . $model->carModel['max_seats'] .
                        ' <i class="fa fa-suitcase"></i> x ' . $model->carModel['max_bags'];
                    return $text;
                })
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;
                    return BootForm::linkOfEdit('tours-price.edit', $model->id, $trashed, !$trashed)
                        . BootForm::linkOfRestore('tours-price.restore', $model->id, 'price', $trashed)
                        . BootForm::linkOfDelete('tours-price.soft_delete', $model->id, 'price', 'link', true, '', !$trashed)
                        . BootForm::linkOfPermanentDelete('tours-price.destroy', $model->id, 'price', 'link', true, '', $trashed);

                })
                ->rawColumns(['carModel.model_name', 'action', 'with_food'])
                ->make(true);
        }
        return view('companies.tours.price_list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $car_models = Car_model::whereNull('deleted_at')->get()->pluck('ModelWithSeats', 'id');
        return view('companies.tours.price_create', compact('car_models'));

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
            'price' => 'required|numeric',
            'car_model_id' => 'required',
            'tourism_place' => 'required',
            'number_hours' => 'required',
            'tour_time_range' => 'required',
        ]);
        $times = explode('-', $request->tour_time_range);
        $inputs = $request->all();
        $inputs['company_id'] = auth('admin')->user()->adminable->id;
        $inputs['tours_start_time'] = date('Y-m-d H:i:s', strtotime($times[0]));
        $inputs['tours_end_time'] = date('Y-m-d H:i:s', strtotime($times[1]));
        if (!Tour_price_list::create($inputs)) {
            flash()->error('failed to update data, please try again later');
        } else {
            flash()->success('Data was saved successfully');
        }
        return redirect(route('prices-list'));
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
        $tour_price = Tour_price_list::find($id);
        $car_models = Car_model::whereNull('deleted_at')->get()->pluck('ModelWithSeats', 'id');
        return view('companies.tours.price_edit', compact('car_models', 'tour_price'));
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
        $request->validate([
            'price' => 'required|numeric',
            'car_model_id' => 'required',
            'tourism_place' => 'required',
            'number_hours' => 'required',
            'tour_time_range' => 'required',
        ]);
        $tour_price = Tour_price_list::find($id);
        $times = explode('-', $request->tour_time_range);
        $inputs = $request->all();
        $inputs['tours_start_time'] = date('Y-m-d H:i:s', strtotime($times[0]));
        $inputs['tours_end_time'] = date('Y-m-d H:i:s', strtotime($times[1]));
        if (!$tour_price->update($inputs)) {
            flash()->error('failed to update data, please try again later');
        } else {
            flash()->success('Data was saved successfully');
        }
        return redirect(route('prices-list'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete($id)
    {
        $tour_price = Tour_price_list::findOrFail($id);
        if ($this->softDeleteModel($tour_price)) {
            $tour_price->save();
            flash()->success('Data was deleted successfully');
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('prices-list');
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
