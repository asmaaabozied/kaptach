<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Airport;
use App\Car_model;
use App\Client;
use App\Helpers\BootForm;
use App\Helpers\Files;
use App\Http\Traits\SoftDeleteTrait;
use App\Invoice;
use App\Jobs\ResizeImage;
use App\Payment;
use App\Shuttle_price_list;
use App\Station;
use App\Transfer_price_list;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClientsController extends BaseController
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

            $hotels = Client::with('station')
                ->where('company_id', auth('admin')->user()->adminable->id)
                ->whereNull('deleted_at')->get();
            return Datatables::of($hotels)
                ->editColumn('name', function ($model) {
                    return BootForm::routeLink('my-clients.show', $model->slug, ['value' => $model->name]);
                })
                ->editColumn('station', function ($model) {
                    if ($model->station)
                        return $model->station->name;
                    else
                        return '';
                })
                ->editColumn('type', function ($model) {
                    if ($model->type == 'hotel')
                        return 'Hotel';
                    elseif ($model->type == 'tourism_company')
                        return 'Tourism Company';
                })
                ->editColumn('logo', function ($model) {
                    return "<img class='profile-user-img img-responsive img-circle' src='" . $model->logo['original'] . "' alt='User profile picture'>";
                })
                ->editColumn('status', function ($model) {
                    return $model->status == 1 ? 'Active' : 'blocked';
                })
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;

                    return '<div class="btn-group">'
                        . '<button type="button" class="btn btn-default">Action</button>'
                        . '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">'
                        . '<span class="caret"></span>'
                        . '<span class="sr-only">Toggle Dropdown</span>'
                        . '</button>'
                        . '<ul class="dropdown-menu" role="menu">'
                        . '<li>' . BootForm::routeLink('my-clients.edit', $model->slug, ['value' => __('buttons.edit')], !$trashed) . '</li>'
                        . '<li>' . BootForm::routeLink('my-clients.admins', $model->slug, ['value' => __('pages.admins')], !$trashed) . '</li>'
                        . '<li>' . BootForm::linkOfRestore('my-clients.restore', $model->id, $model->name, $trashed) . '</li>'
                        . '<li>' . BootForm::linkOfDelete('my-clients.soft_delete', $model->id, $model->name, 'link', true, 'Delete', !$trashed) . '</li>'
                        . '<li>' . BootForm::routeLink('my-clients.changeStatus', $model->id, ['value' => ($model->status == 1 ? 'BLOCK' : 'ACTIVE')]) . '</li>'
                        . '<li>' . BootForm::routeLink('my-clients.add_price', $model->id, ['value' => 'Add Price']) . '</li>'
                        . '</ul>'
                        . '</div>';

                })
                ->rawColumns(['name', 'action', 'logo'])
                ->make(true);
        }
        return view('companies.clients.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $stations = Station::whereNull('deleted_at')->get()->pluck('name', 'id');
        return view('companies.clients.create', compact('stations'));
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
            'name' => 'required',
            'type' => 'required',
            'station_id' => 'required_if:type,hotel',
            'logo' => 'required|image|max:3072',
//            'address' => 'required',
//            'lat' => 'required',
//            'lang' => 'required',
        ]);

        $inputs = $request->all();
        $inputs['company_id'] = auth('admin')->user()->adminable->id;
        $inputs['lat'] = '41.005322';
        $inputs['lang'] = '29.012179';
        $inputs['address'] = 'İstanbul Turkey';
        //upload image
        if ($request->hasFile('logo')) {
            $inputs['logo'] = $files->uploadAndResizeImage($request->logo, 'uploads/clients', 200);
            $this->dispatch(new ResizeImage('clients', $inputs['logo']));
        }
        $inputs['slug'] = Client::makeSlug($request->name);
        $slug = $inputs['slug'];
        $client = Client::create($inputs);
        $username = Admin::generateUsername($request->name);
        $client->admins()->create(
            [
                'role_id' => 1,
                'username' => $username,
                'password' => bcrypt('123456'),
                'email' => $username . '@kaptan.com',
                'type' => $request->type,
            ]
        );
        if ($client) {
            flash()->success('Data was saved successfully');
            return redirect(route('my-clients.info', [$slug]));
        } else {
            flash()->error('failed to update data, please try again later');
            return redirect(route('my-clients.index'));
        }


    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $client = Client::where('slug', $slug)->first();
        $requested_transfers = $client->transfers()->where('request_status', 0)
            ->whereNull('deleted_at')->get();
        $airports = Airport::whereNull('deleted_at')->get();
        $shuttles_price = Shuttle_price_list::whereNull('deleted_at')
            ->where('client_id', $client->id)->get();
        $car_models = Car_model::whereNull('deleted_at')->get()->pluck('ModelWithSeats', 'id');
        $transfer_prices = $this->getAllTransferPriceByHotel($client->id);
        return view('companies.clients.hotels.show', compact('client', 'requested_transfers',
            'shuttles_price', 'airports', 'transfer_prices', 'car_models'));
    }

    private function getAllTransferPriceByHotel($hotelId)
    {

        $airports = Airport::whereNull('deleted_at')->get();
        $car_models = Car_model::whereNull('deleted_at')->get();

        $price_arr = [];
        $k = 0;
        foreach ($airports as $airport) {
            $k++;
            $price_arr[$k] = ['airport_id' => $airport->id,
                'airport_name' => $airport->name,
            ];
            $i = 0;
            foreach ($car_models as $car_model) {
                $i++;
                $transfer_price = Transfer_price_list::whereNull('deleted_at')
                    ->where('airport_id', $airport->id)
                    ->where('car_model_id', $car_model->id)
                    ->where('client_id', $hotelId)->first();
                if ($transfer_price) {
                    $price_arr[$k]['car_model'][$i] = ['id' => $car_model->id, 'car_model_name' => $car_model->ModelWithSeats,
                        'departure_price' => $transfer_price->departure_price,
                        'arrival_price' => $transfer_price->arrival_price
                    ];

                } else {
                    $price_arr[$k]['car_model'][$i] = ['id' => $car_model->id, 'car_model_name' => $car_model->ModelWithSeats,
                        'departure_price' => '0.00',
                        'arrival_price' => '0.00'
                    ];
                }


            }

        }
        return $price_arr;
    }

    public function info($slug)
    {
        $client = Client::where('slug', $slug)->first();
        return view('companies.clients.client_info', compact('client'));
    }

    public function addPrice($hotelId)
    {
        $shuttles_price = Shuttle_price_list::whereNull('deleted_at')
            ->where('client_id', $hotelId)->get();
        $airports = Airport::whereNull('deleted_at')->get();
        $car_models = Car_model::whereNull('deleted_at')->get()->pluck('ModelWithSeats', 'id');
        $transfer_prices = $this->getAllTransferPriceByHotel($hotelId);
        return view('companies.clients.hotels.myhotel_price_create',
            compact('shuttles_price', 'airports', 'car_models', 'hotelId', 'transfer_prices'));
    }

    public function storePrice($hotelId, Request $request)
    {
        //make validation
        $validator = $request->validate([
            'transfer' => 'required',
            'shuttle' => 'required',
        ]);

        $transfer_requrest = $request->transfer;
        $shuttle_requrest = $request->shuttle;
        // add shuttle price request
        foreach ($shuttle_requrest as $key => $value) {
            $departure_price = $value['departure_price'];
            $arrival_price = $value['arrival_price'];
            $row_shuttle_price = Shuttle_price_list::updateOrCreate(
                [
                    'client_id' => $hotelId,
                    'company_id' => auth('admin')->user()->adminable->id,
                    'airport_id' => $key,
                    'deleted_at' => Null
                ]
                , ['departure_price' => $departure_price, 'arrival_price' => $arrival_price]);
        }
        // add transfer price request
        foreach ($transfer_requrest as $item => $val) {
            $airport = $item;
            foreach ($val as $model => $price) {
                $model_id = $model;
                $departure_price = $price['departure_price'];
                $arrival_price = $price['arrival_price'];
                $row_transfer_price = Transfer_price_list::updateOrCreate(
                    [
                        'client_id' => $hotelId,
                        'company_id' => auth('admin')->user()->adminable->id,
                        'airport_id' => $airport,
                        'car_model_id' => $model_id,
                        'deleted_at' => Null
                    ]
                    , ['departure_price' => $departure_price, 'arrival_price' => $arrival_price]);
            }
        }
        flash()->success('Data was saved successfully');
        return redirect(route('my-clients.add_price', $hotelId));

    }

    public function addPayment($client_id)
    {
        return view('companies.clients.hotels.add_payment', compact('client_id'));
    }

    public function storePayment($client_id, Request $request)
    {
        $inputs = $request->all();
        $inputs['client_id'] = $client_id;
        $inputs['deducted_year'] = date('y', strtotime($request->deducted_date));
        $inputs['deducted_month'] = date('m', strtotime($request->deducted_date));
        Payment::create($inputs);
        $client = Client::findorFail($client_id);
        flash()->success('Data was saved successfully');
        return redirect(route('my-clients.show', $client->slug));
    }

    public function addInvoice($client_id)
    {
        return view('companies.invoices.create', compact('client_id'));
    }

    public function storeInvoice($client_id, Request $request)
    {
        $inputs = $request->all();
        $inputs['client_id'] = $client_id;
        $inputs['deducted_year'] = date('y', strtotime($request->deducted_date));
        $inputs['deducted_month'] = date('m', strtotime($request->deducted_date));
        $inputs['code'] = mt_rand(100000, 999999);
        $invoice = Invoice::create($inputs);
        flash()->success('Data was saved successfully');
        return redirect(route('invoices.show', $invoice->id));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $stations = Station::whereNull('deleted_at')->get()->pluck('name', 'id');
        $client = Client::where('slug', $slug)->first();
        return view('companies.clients.edit', compact('client', 'stations'));
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
        $client = Client::findOrFail($id);
        $validator = $request->validate([
            'name' => 'required',
            'type' => 'required',
            'station_id' => 'required_if:type,hotel',
            'logo' => 'required|image|max:3072',
            'address' => 'required',
            'lat' => 'required',
            'lang' => 'required',
            'address' => 'required'
        ]);
        $inputs = $request->all();
        if ($request->hasFile('logo')) {
            $inputs['logo'] = $files->uploadAndResizeImage($request->logo, 'uploads/clients', 200);
            $this->dispatch(new ResizeImage('clients', $inputs['logo']));
        }
        if ($request->name != $client->name) {
            $inputs['slug'] = Client::makeSlug($request->name);
        }
        $update = $client->update($inputs);
        if ($update) {
            flash()->success('Data was saved successfully');
            return redirect(route('my-clients.show', [$client->slug]));
        } else {
            flash()->error('failed to update data, please try again later');
            return redirect(route('my-clients.index'));
        }

    }

    public function admins($slug)
    {
        $client = Client::where('slug', $slug)->first();
        if ($client) {
            $admins = $client->admins()->whereNull('deleted_at')->get();
            return view('companies.clients.admins', compact('admins'));
        } else
            flash()->error('failed to update data, please try again later');

        return redirect(route('my-clients.index'));
    }

    public function addAdmins($slug)
    {
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

    public function softDelete($id)
    {
        $client = Client::findOrFail($id);
        if ($this->softDeleteModel($client)) {
            if ($client->save()) {
                $client->admins()->update(['deleted_at' => \Carbon\Carbon::now()]);
                flash()->success('Data was deleted successfully');
            }
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('my-clients.index');
    }

    public function changeStatus($id)
    {
        $client = Client::whereNull('deleted_at')->findOrFail(auth('admin')->user()->adminable->id);
        if ($client) {
            if ($client->status == 1)
                $client->status = 0;
            else
                $client->status = 1;
            $inputs['status'] = $client->status;
            if ($client->update($inputs)) {
                $client->Admins()->update($inputs);
//                $admin = Admin::where('corporate_id', auth('admin')->user()->adminable->id);
//                $admin->update($inputs);
                flash()->success('Data was saved successfully');
            } else
                flash()->error('Failed to save data, please try again later');

        }
        return redirect()->route('my-clients.index');
    }

}
