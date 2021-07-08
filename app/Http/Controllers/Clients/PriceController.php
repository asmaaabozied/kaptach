<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Shuttle_price_list;
use App\Transfer_price_list;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PriceController extends BaseController
{
    public function index()
    {
        return view('clients.price_list.index');
    }

    public function shuttle(Request $request)
    {
        if ($request->all()) {
            $client_id = auth('admin')->user()->adminable->id;
            $shuttle_prices = Shuttle_price_list::with('airports')
                ->where('client_id', $client_id)
                ->whereNull('deleted_at')->get();

            return Datatables::of($shuttle_prices)->make(true);
        }
        return view('clients.price_list.shuttle_price');
    }

    public function transfer(Request $request)
    {
        if ($request->all()) {
            $client_id = auth('admin')->user()->adminable->id;
            $transfer_prices = Transfer_price_list::with( 'airport','carModel')
                ->where('client_id', $client_id)
                ->whereNull('deleted_at')->get();

            return Datatables::of($transfer_prices)
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
                })->rawColumns(['carModel.model_name'])
                ->make(true);
        }
        return view('clients.price_list.transfer_price');
    }

    public function tours()
    {
    }
}
