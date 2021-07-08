<?php

namespace App\Http\Controllers\Clients;

use App\Client;
use App\Employer;
use App\Guest;
use App\Host;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\BootForm;
use App\Http\Traits\SoftDeleteTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Company;
use App\Airport;
use App\Transfer;
use App\Car_model;
use App\Country;
use App\Transfer_price_list;

class TransfersController extends BaseController
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
            $client = Client::whereNull('deleted_at')->find(auth('admin')->user()->adminable->id);
            $transfers = $client->transfers();
            $transfers->with('shift', 'requestedAdmin', 'airport', 'car_model')->whereNull('deleted_at');
            if (!empty($_GET["selected_date"])) {
                $transfers->whereDate('transfer_start_time', $_GET["selected_date"]);
            }
            //requested search
            if ($request->input('request_status') != '') {
                $transfers->where('request_status', $request->input('request_status'));
            }
            if ($request->input('from') != '') {
                $transfers->where('transfer_start_time', '>=', date('Y-m-d', strtotime($request->input('from'))));
            }
            if ($request->input('to') != '') {
                $transfers->whereDate('transfer_start_time', '<=', $request->input('to'));
            }
            $transfers->orderBy('transfer_start_time', 'ASC');
            $transfers->get();
            return Datatables::of($transfers)
                ->editColumn('id', function ($model) {
                    $text = '';
                    if ($model->cancelled == 1)
                        $text .= '<div class="verticaltext_content" style="top: 80px;
    color: red;
    text-transform: uppercase;
    font-weight:bold;">cancelled</div>';
                    $text .= BootForm::routeLink('clients.transfers.show', $model->id, ['value' => $model->id]);
                    return $text;
                })
                ->editColumn('transfer_start_time', function ($model) use ($request) {
                    if ($request->input('from') || $request->input('to')) {
                        return "<p>" . date('Y-m-d H:i', strtotime($model->transfer_start_time)) . "</p>";
                    } else {
                        return "<p style='font-size: 22px;'>" . date('H:i', strtotime($model->transfer_start_time)) . "</p>";
                    }
                })->addColumn('tid', function ($model) {
                    return $model->id;
                })
                ->editColumn('request_status', function ($model) {
                    if ($model->request_status == 0)
                        return "<span class=\"label label-danger\">Pending</span>";
                    else
                        return "<span class=\"label label-success\">Approved</span>";
                })
                ->addColumn('driver', function ($model) {
                    if ($model->driver)
                        return $model->driver->employer->first_name . ' ' . $model->driver->employer->last_name;
                    else
                        return "";
                })
                ->editColumn('car_model', function ($model) {
                    return $model->car_model['ModelWithSeats'];
                })
                ->editColumn('airport', function ($model) {
                    return $model->airport['name'];
                })
                ->editColumn('hotel_name', function ($model) {
                    return $model->transferable['name'];
                })
                ->editColumn('admin', function ($model) {
                    if ($model->requestedAdmin)
                        return $model->requestedAdmin['username'];
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
                        . '<li>' . BootForm::routeLink('clients.transfers.edit', $model->id, ['value' => __('buttons.edit')], !$trashed) . '</li>'
                        . '<li>' . BootForm::linkOfDelete('clients.transfers.soft_delete', $model->id, $model->id, 'link', true, 'Delete', !$trashed) . '</li>'
                        . '<li>' . BootForm::routeLink('clients.transfers.show_cancel', $model->id, ['value' => ($model->cancelled == 1 ? __('buttons.open') : __('buttons.cancel')), 'id' => 'btn_cancel']) . '</li>'
                        . '</ul>'
                        . '</div>';

                })
                ->with('sum_price', $transfers->sum('price'))
                ->rawColumns(['id', 'action', 'transfer_start_time', 'request_status'])
                ->make(true);
        }
        $search_date = strftime('%F');
        $airports = Airport::whereNull('deleted_at')->get();

        return view('clients.transfer.index', compact('search_date', 'airports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add($airport_id, $type)
    {
        $countries = Country::all();
        $car_models = Car_model::whereNull('deleted_at')->get();
        $airport = Airport::findOrFail($airport_id);
        $client = Client::find(auth('admin')->user()->adminable->id);
        return view('clients.transfer.create', compact('car_models', 'countries', 'client', 'type', 'airport'));
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
            'datetimepicker' => 'required',
            'car_model_id' => 'required',
            'identity_number' => 'required',
            'gender' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'nationality' => 'required',
            'phone' => 'required',
            'room_number' => 'required_if:type,departure',
            'price' => 'required'
        ]);
        $car_model = Car_model::find($request->car_model_id);
        $client = Client::find(auth('admin')->user()->adminable->id);
        $airport = Airport::find($request->airport_id);
        $data = [
            'airport_id' => $request->airport_id,
            'type' => $request->type,
            'transfer_start_time' => $request->datetimepicker,
            'requested_by_admin' => auth('admin')->user()->id,
            'payment_type_id' => 1,
            'company_id' => $client->company_id,
            'car_model_id' => $request->car_model_id,
            'request_status' => 0,
            'price' => $request->price,
            'number_seats' => $car_model->max_seats,
            'number_of_booking' => $request->number_of_booking,
            'notes' => $request->notes,
            'flight_number' => $request->flight_number

        ];

        if ($request->type == 'arrival') {
            $data['address_starting_point'] = $airport->address;
            $data['GPS_starting_point'] = $airport->lat . '-' . $airport->lang;
            $data['address_destination'] = $client->address;
            $data['GPS_destination'] = $client->lat . '-' . $client->lang;
        } else {
            $data['address_starting_point'] = $client->address;
            $data['GPS_starting_point'] = $client->lat . '-' . $client->lang;;
            $data['address_destination'] = $airport->address;
            $data['GPS_destination'] = $airport->lat . '-' . $airport->lang;;
        }

        $transfer = new Transfer($data);
        $transfer->transferable()->associate($client)->save();

        for ($i = 0; $i < $request->number_of_booking; $i++) {
            $identity_number = $request->identity_number[$i];
            $str = Guest::makeIdentityNumber($request->identity_number[$i]);
            $guest = Guest::updateOrCreate(
                ['identity_number' => $str], [
                'company_id' => $client->company_id,
                'client_id' => $client->id,
                'nationality' => $request->nationality[$i],
                'gender' => $request->gender[$i],
                'phone' => $request->phone[$i],
                'first_name' => $request->first_name[$i],
                'last_name' => $request->last_name[$i],
            ]);
            $transfer->guests()->attach($guest->id, ['host_id' => null, 'room_number' => $request->room_number[$i]]);

        }

        flash()->success('Data was saved successfully');
        return redirect(route('clients.transfers.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transfer = Transfer::with('shift', 'driver', 'guests', 'paymentType')->findOrFail($id);
        return view('clients.transfer.show', compact('transfer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transfer = Transfer::with('shift', 'driver', 'guests', 'paymentType', 'car_model')->findOrFail($id);
        $countries = Country::all();
        $car_models = Car_model::whereNull('deleted_at')->get();
        $hosts = Host::whereHas('companies', function ($q) {
            $q->where('company_id', auth('admin')->user()->adminable->company_id);
        })->where('airport_id', $transfer->airport_id)
            ->whereNull('deleted_at')->get();

        return view('clients.transfer.edit', compact('transfer',
            'car_models', 'countries', 'hosts'));
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
            'datetimepicker' => 'required',
            'car_model_id' => 'required',
            'identity_number' => 'required',
            'gender' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'nationality' => 'required',
            'phone' => 'required',
            'room_number' => 'required_if:type,departure',
            'price' => 'required'
        ]);
        $transfer = Transfer::find($id);
        $car_model = Car_model::find($request->car_model_id);
        $client = Client::find($transfer->transferable_id);
        $airport = Airport::find($transfer->airport_id);

        $data = [
            'transfer_start_time' => $request->datetimepicker,
            'took_action_by_admin' => auth('admin')->user()->id,
            'payment_type_id' => 1,
            'flight_number' => $request->flight_number,
            'car_model_id' => $request->car_model_id,
            'price' => $request->price,
            'number_seats' => $car_model->max_seats,
            'number_of_booking' => $request->number_of_booking,
            'notes' => $request->notes,
        ];
        //change status

        if ($request->datetimepicker != $transfer->transfer_start_time || $request->car_model_id != $transfer->car_model_id) {
            $data['request_status'] = 0;
            $data['shift_id'] = $request->shift;
        }

        if ($request->type == 'arrival') {
            $data['address_starting_point'] = $airport->address;
            $data['GPS_starting_point'] = $airport->lat . '-' . $airport->lang;
            $data['address_destination'] = $client->address;
            $data['GPS_destination'] = $client->lat . '-' . $client->lang;
        } else {
            $data['address_starting_point'] = $client->address;
            $data['GPS_starting_point'] = $client->lat . '-' . $client->lang;;
            $data['address_destination'] = $airport->address;
            $data['GPS_destination'] = $airport->lat . '-' . $airport->lang;;
        }
        //update customers
        for ($i = 0; $i < $request->number_of_booking; $i++) {
            $identity_number = $request->identity_number[$i];
            $guest = Guest::updateOrCreate(
                ['identity_number' => $identity_number], [
                'nationality' => $request->nationality[$i],
                'gender' => $request->gender[$i],
                'phone' => $request->phone[$i],
                'first_name' => $request->first_name[$i],
                'last_name' => $request->last_name[$i],
                'room_number' => $request->room_number[$i],
            ]);
            // $transfer->employers()->updateExistingPivot($guest->id);

        }
        if (!$transfer->update($data))
            flash()->error('failed to update data, please try again later');
        else
            flash()->success('Data was saved successfully');
        return redirect(route('clients.transfers.index'));
    }

    public function softDelete($id)
    {
        $transfer = Transfer::findOrFail($id);
        //soft delete customers
        DB::table('guest_transfer')
            ->where('transfer_id', $id)
            ->update(array('deleted_at' => DB::raw('NOW()')));
        //soft delete transfer
        if ($this->softDeleteModel($transfer)) {
            $transfer->save();
            flash()->success('Data was deleted successfully');
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('clients.transfers.index');
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

    public function transfer_price(Request $request)
    {
        $client_id = auth('admin')->user()->adminable->id;
        $airport_id = $request->airport_id;
        $car_model_id = $request->car_model_id;
        $type = $request->type;
        $result = Transfer_price_list::where('car_model_id', $car_model_id)
            ->where('client_id', $client_id)->
            where('airport_id', $airport_id)->whereNull('deleted_at')->first();
        if (isset($result))
            $price = ($type == "arrival") ? $result->arrival_price : $result->departure_price;
        else
            $price = 0;
        $data = array('price' => $price);
        return response()->json($data);
    }

    public function showCancelTransfer($id)
    {
        $transfer = Transfer::findOrFail($id);
        $date = strtotime($transfer->transfer_start_time);
        if ($date > time() - 7200) {
            if ($transfer->cancelled == 1) {
                $update = $transfer->update(['cancelled' => 0, 'cancel_reason' => null,
                    'cancellation_date' => null, 'cancellable_id' => null, 'cancellable_type' => null]);
                if (!$update)
                    flash()->error('failed to update data, please try again later');
                else
                    flash()->success('Data was saved successfully');
                return redirect()->route('clients.transfers.index');
            } else {
                return view('clients.transfer.cancel_transfer', compact('transfer'));
            }
        } else {
            flash()->error('Not allowed to cancel transfer ');
            return redirect()->route('clients.transfers.index');
        }


    }

    public function cancelTransfer($id, Request $request)
    {
        $request->validate([
            'cancel_reason' => 'required'
        ]);
        $transfer = Transfer::findOrFail($id);
        $cancel_date = \Carbon\Carbon::now();
        $update = $transfer->update(['cancelled' => 1, 'cancel_reason' => $request->cancel_reason, 'cancellation_date' => $cancel_date]);

        if (!$update)
            flash()->error('failed to update data, please try again later');
        else {
            $client = Client::find(auth('admin')->user()->adminable->id);
            $transfer->cancellable()->associate($client)->save();
            flash()->success('Data was saved successfully');
        }
        return redirect()->route('clients.transfers.index');
    }
}
