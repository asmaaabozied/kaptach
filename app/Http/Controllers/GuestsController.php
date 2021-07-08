<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Car_model;
use App\Client;
use App\Company;
use App\Country;
use App\Guest;
use App\Helpers\BootForm;
use App\Host;
use App\Shift;
use App\Transfer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class GuestsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->all()) {
            $company_id = auth('admin')->user()->adminable->id;
            $guests = Guest::where('company_id', $company_id)->whereNull('deleted_at');
            if ($request->key_search != "") {
                $guests->Where('first_name', 'like', $request->key_search . '%')
                    ->orWhere('identity_number', 'like', $request->key_search . '%');
            }
            $guests = $guests->get();

            return Datatables::of($guests)
                ->addColumn('add_transfer', function ($model) {
                    return $model->id;
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
                        . '<li>' . BootForm::routeLink('guests.edit', $model->id, ['value' => __('buttons.edit')]) . '<li>'
                        . '<li>' . BootForm::routeLink('guests.transfers', $model->id, ['value' => __('pages.transfers')]) . '<li>'
                        . '<li><a href="#" class="btn_transfer" id="' . $model->id . '">' . __('pages.add') . ' ' . __('pages.transfers') . '</a></li>'
//                        . '<li>' . BootForm::routeLink('guests.add_transfer', $model->id, ['value' => __('pages.add') . ' ' . __('pages.transfers')]) . '<li>'
                        . '<li>' . BootForm::linkOfRestore('guests.restore', $model->id, $model->first_name, $trashed) . '<li>'
//                        . '<li>' . BootForm::linkOfDelete('guests.soft_delete', $model->id, $model->name, 'link', true, 'Delete', !$trashed) . '<li>'
                        . '<li>' . BootForm::linkOfPermanentDelete('guests.destroy', $model->id, $model->first_name, 'link', true, '', $trashed) . '<li>'
                        . '</ul>'
                        . '</div>';
                })
                ->make(true);
        }

        return view('guests.index');
    }

    public function viewModalWithData($guest_id)
    {

        $str = '';
        $airports = Airport::whereNull('deleted_at')->get();
        foreach ($airports as $airport) {
            $str .= '<a class="hvrbox" href="' . route('guests.add_transfer', ['id' => $guest_id, 'airport_id' => $airport->id, 'type' => 'arrival']) . '">
                                    <img src="' . $airport->arrival_image['thumb'] . '" width="200" height="200"
                                         class="hvrbox-layer_bottom" alt="' . $airport->name . '">
                                    <div class="hvrbox-layer_top">
                                        <div class="hvrbox-text"> Arrival To ' . $airport->name . '</div>
                                    </div>
                                </a>
                                <a class="hvrbox"
                                   href="' . route('guests.add_transfer', ['id' => $guest_id, 'airport_id' => $airport->id, 'type' => 'departure']) . '">
                                    <img src="' . $airport->departure_image['thumb'] . '" width="200" height="200"
                                         class="hvrbox-layer_bottom" alt="' . $airport->name . '">
                                    <div class="hvrbox-layer_top">
                                        <div class="hvrbox-text"> Departure From ' . $airport->name . '</div>
                                    </div>
                                </a>
                                <div>
                                    <hr size="30">
                                </div>';
        }
        echo $str;
        exit;
    }

    public function GetGuestsAjax(Request $request)
    {
        $company_id = auth('admin')->user()->adminable->id;
        $countries = Country::all();
        $guests = Guest::where('company_id', $company_id)->whereNull('deleted_at');
        if ($request->key_search != "") {
            $guests->Where('first_name', 'like', $request->key_search . '%')
                ->orWhere('identity_number', 'like', $request->key_search . '%');
        }
        $guests = $guests->get();
        $required = '';
        if ($request->type == 'departure')
            $required = 'required';

        $str = '';
        foreach ($guests as $guest) {
            $str .= '<tr>';
            $str .= '<td><input type="checkbox" name="record" class="filled-in"></td>';
            $str .= '<td><input type="text" class=" form-control" minlength="4" name="identity_number[]"
                        value="' . $guest->identity_number . '" required></td>';
            $str .= '<td><input type="text" class="form-control" name="first_name[]" required value="' . $guest->first_name . '"> </td>';
            $str .= '<td><input type="text" class="form-control" name="last_name[]" required value="' . $guest->last_name . '"></td>';
            $str .= '<td><select name="gender[]" required class="form-control">';
            $str .= '<option value="female" ' . ($guest->gender == 'female' ? 'selected' : '') . '>' . __('inputs.female') . '</option>';
            $str .= '<option value="male" ' . ($guest->gender == 'male' ? 'selected' : '') . '>' . __('inputs.male') . '</option></select>';
            $str .= '</td>';
            $str .= '<td>';
            $str .= '<select name="nationality[]" required class="form-control">';
            $str .= '<option value="">>' . __('pages.select') . __('pages.nationality') . '</option>';
            foreach ($countries as $country) {
                $str .= '<option value="' . $country->nationality . '" ' .($guest->nationality == $country->nationality ? 'selected' : '').'>' . $country->nationality . '</option>';
            }

            $str .= '</select>';
            $str .= '</td>';
            $str .= '<td><div class="input"> 
                    <input type="text" name="phone[]" class="form-control phone" value="'.$guest->phone.'" required/>
                   </div><br/></td>';
            $str .= '<td><input type="text" class="form-control" min="1" name="room_number[]" ' . $required . '>
            </td>';
            $str .= '</tr>';
        }
        return $str;
    }

    public function addTransfer($id, $airport_id, $type)
    {
        $guest = Guest::findOrFail($id);
        $company = Company::findOrFail(auth('admin')->user()->adminable->id);
        $airport = Airport::findOrFail($airport_id);
        $hosts = Host::whereHas('companies', function ($q) {
            $q->where('company_id', auth('admin')->user()->adminable->id);
        })->where('airport_id', $airport_id)
            ->whereNull('deleted_at')->get();
        if ($company->cars->isEmpty()) {
            flash()->error('Please add a car!');
            return redirect(route('cars.create'));
        }

        if (!$hosts) {
            flash()->error('Please add a host for airport ' . $airport->name);
            return redirect(route('hosts.create'));
        }
        $countries = Country::all();
        $car_models = Car_model::whereNull('deleted_at')->get();
        $hotels = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->where('type', 'hotel')->whereNull('deleted_at')->get();
        return view('guests.add_transfer', compact('guest', 'countries', 'airport',
            'car_models', 'hotels', 'type', 'hosts'));
    }

    public function transferStore($guest_id, Request $request)
    {
        $validator = $request->validate([
            'datetimepicker' => 'required',
            'car_model_id' => 'required',
            'hotel_id' => 'required',
            'room_number' => 'required_if:type,departure',
            'price' => 'required'
        ]);
        $car_model = Car_model::find($request->car_model_id);
        $hotel = Client::find($request->hotel_id);
        $airport = Airport::find($request->airport_id);
        $company = Company::findOrFail(auth('admin')->user()->adminable->id);
        $guest = Guest::findOrFail($guest_id);
        $data = [
            'airport_id' => $request->airport_id,
            'type' => $request->type,
            'transfer_start_time' => $request->datetimepicker,
            'flight_number' => $request->flight_number,
            'took_action_by_admin' => auth('admin')->user()->id,
            'payment_type_id' => 1,
            'company_id' => auth('admin')->user()->adminable->id,
            'car_model_id' => $request->car_model_id,
            'request_status' => 1,
            'price' => $request->price,
            'number_seats' => $car_model->max_seats,
            'number_of_booking' => 1,
            'notes' => $request->notes
        ];
        if ($request->type == 'arrival') {
            $data['address_starting_point'] = $airport->address;
            $data['GPS_starting_point'] = $airport->lat . '-' . $airport->lang;
            $data['address_destination'] = $hotel->address;
            $data['GPS_destination'] = $hotel->lat . '-' . $hotel->lang;
        } else {
            $data['address_starting_point'] = $hotel->address;
            $data['GPS_starting_point'] = $hotel->lat . '-' . $hotel->lang;;
            $data['address_destination'] = $airport->address;
            $data['GPS_destination'] = $airport->lat . '-' . $airport->lang;;
        }
        if ($request->shift) {
            $shift = Shift::findOrFail($request->shift);
            $data['driver_id'] = $shift->driver_id;
            $data['shift_id'] = $request->shift;
            $data['car_id'] = $shift->car->id;
        } else {
            if ($company->type == 'personal') {
                $driver = Driver::where('company_id', $company->id)->whereNull('deleted_at')->first();
                $data['driver_id'] = $driver->id;
                $car_model_id = $request->car_model_id;
                $date = $request->datetimepicker;
                $car = Car::whereHas('carModel', function ($query) use ($car_model_id) {
                    $query->where('car_model_id', $car_model_id);
                })->whereNull('deleted_at')
                    ->where('company_id', auth('admin')->user()->adminable->id)
                    ->first();
                if (!$car) {
                    flash()->error('Please add car for this model!');
                    return redirect(route('cars.create'));
                } else {
                    $shift_inputs = ['shift_start_time' => $date,
                        'shift_end_time' => $date,
                        'car_id' => $car->id,
                        'driver_id' => $driver->id,
                        'company_id' => auth('admin')->user()->adminable->id,
                    ];
                    $shift = Shift::create($shift_inputs);
                    $data['shift_id'] = $shift->id;
                    $data['car_id'] = $car->id;
                }
            }
        }

        $host_id = $request->host_id;
        if ($request->type == 'arrival') {
            if (empty($request->host_id)) {
                $host = Host::whereHas('companies', function ($q) {
                    $q->where('company_id', auth('admin')->user()->adminable->id);
                })->where('airport_id', $request->airport_id)
                    ->whereNull('deleted_at')->first();

                if ($host)
                    $data['host_id'] = $host->id;
                else {
                    flash()->error('Please add host for airport ' . $airport->name);
                    return redirect(route('hosts.create'));
                }

            } else {
                $data['host_id'] = $host_id;
            }
        } else {
            $data['host_id'] = null;
        }

        $transfer = new Transfer($data);
        $transfer->transferable()->associate($hotel)->save();

        $transfer->guests()->attach($guest->id,
            ['host_id' => $data['host_id'], 'room_number' => $request->room_number]);

        flash()->success('Data was saved successfully');
        return redirect(route('guests.index'));
    }

    public function transfers($guest_id, Request $request)
    {
        $guest = Guest::findOrFail($guest_id);
        if ($request->all()) {
            $transfers = $guest->transfers();
            if (!empty($_GET["selected_date"])) {
                $transfers->whereDate('transfer_start_time', $_GET["selected_date"]);
            }
            $transfers = $transfers->get();
            $transfers = $transfers->whereNull('deleted_at');

            return Datatables::of($transfers)
                ->addColumn('checkbox', function ($model) {
                    return '<input type="checkbox" class="sub-menu" name="record" value="' . $model->id . '">';
                })
                ->editColumn('id', function ($model) {
                    $text = '';
                    if ($model->cancelled == 1)
                        $text .= '<div class="verticaltext_content" style="top: 80px;
    color: red;
    text-transform: uppercase;
    font-weight:bold;">cancelled</div>';
                    $text .= BootForm::routeLink('transfers.show', $model->id, ['value' => $model->id, 'class' => 'sub-menu']);
                    return $text;
                })
                ->editColumn('transfer_start_time', function ($model) use ($request) {
                    if ($request->input('from') || $request->input('to')) {
                        return "<p>" . date('Y-m-d H:i', strtotime($model->transfer_start_time)) . "</p>";
                    } else {
                        return "<p style='font-size: 22px;'>" . date('H:i', strtotime($model->transfer_start_time)) . "</p>";
                    }
                })
                ->addColumn('tid', function ($model) {
                    return $model->id;
                })
                ->editColumn('request_status', function ($model) {
                    if ($model->request_status == 0)
                        return "<span class=\"label label-danger\">Pending</span>";
                    else
                        return "<span class=\"label label-success\">Approved</span>";

                })
                ->addColumn('driver', function ($model) {
                    if ($model->driver_id) {
                        $text = '';
                        $text .= $model->driver->employer->first_name . ' ' . $model->driver->employer->last_name;
                        if ($model->status == 2) {
                            $text .= '<div class="verticaltext_content" style="top: 80px;
    color: red;
    text-transform: uppercase;
    font-weight:bold;">Ended</div>';
                        } else {
                            if ($model->driver_acceptance == 0) {
                                $text .= '<div class="verticaltext_content" style="top: 80px;
    color: #00c0ef;
    text-transform: uppercase;
    font-weight:bold;">Pending</div>';
                            } elseif ($model->driver_acceptance == 1) {
                                $text .= '<div class="verticaltext_content" style="top: 80px;
    color: #00a65a;
    text-transform: uppercase;
    font-weight:bold;">Accepted</div>';
                            } elseif ($model->driver_acceptance == 2) {
                                $text .= '<div class="verticaltext_content" style="top: 80px;
    color: red;
    text-transform: uppercase;
    font-weight:bold;">Rejected</div>';
                            }
                        }
                        return $text;
                    } else
                        return "";
                })
                ->editColumn('car_model', function ($model) {
                    return $model->car_model['ModelWithSeats'];
                })
                ->editColumn('airport', function ($model) {
                    return $model->airport['name'];
                })
                ->editColumn('transferable.name', function ($model) {
                    return $model->transferable['name'];
                })
                ->editColumn('admin', function ($model) {
                    return $model->actionByAdmin['username'];
                })
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;
                    $statuses = $model->statuses()->pluck('status')->toArray();

                    $str = '<div class="btn-group">'
                        . '<button type="button" class="btn btn-default sub-menu">Action</button>'
                        . '<button type="button" class="btn btn-default dropdown-toggle sub-menu" data-toggle="dropdown">'
                        . '<span class="caret"></span>'
                        . '<span class="sr-only">Toggle Dropdown</span>'
                        . '</button>'
                        . '<ul class="dropdown-menu sub-menu" role="menu">'
                        . '<li>' . BootForm::routeLink('transfers.edit', $model->id, ['value' => __('buttons.edit'), 'class' => 'sub-menu'], !$trashed) . '</li>'
                        . '<li>' . BootForm::routeLink('transfers.approve', $model->id, ['value' => ($model->request_status == 1 ? __('buttons.pending') : __('buttons.approve'))]) . '</li>';
                    if (in_array('Start', $statuses))
                        $view = false;
                    else {
                        $str .= '<li>' . BootForm::routeLink('transfers.show_cancel', $model->id, ['value' => ($model->cancelled == 1 ? __('buttons.open') : __('buttons.cancel')), 'id' => 'btn_cancel', 'class' => 'sub-menu']) . '</li>'
                            . '<li>' . BootForm::linkOfDelete('transfers.soft_delete', $model->id, $model->id, 'link', true, 'Delete', !$trashed) . '</li>';
                    }
                    $str .= '<li>' . BootForm::linkOfRestore('transfers.restore', $model->id, $model->name, $trashed) . '</li>';
                    if ($model->exchange) {
                        $str .= '<li>' . BootForm::routeLink('exchanges.show', $model->exchange->id, ['value' => 'View offer']) . '</li>';
                    } else {
                        $str .= '<li>' . BootForm::routeLink('transfers.search_for_exchange', $model->id, ['value' => 'Exchange']) . '</li>';
                    }
                    $str .= '<li>' . BootForm::routeLink('transfers.showTicket', $model->id, ['value' => __('buttons.ticket'), 'target' => '_blank', 'class' => 'sub-menu'], true) . '</li>'
                        . '</ul>'
                        . '</div>';
                    return $str;
                })
                ->with('sum_price', $transfers->sum('price'))
                ->rawColumns(['checkbox', 'id', 'action', 'transfer_start_time', 'request_status', 'cancelled', 'driver'])
                ->make(true);
        }
        $search_date = strftime('%F');
        $airports = Airport::whereNull('deleted_at')->get();
        return view('guests.transfers', compact('guest', 'airports', 'search_date'));

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

    public function checkIdentityNumber(Request $request)
    {
        $str = Guest::makeIdentityNumber($request->identity_number);
        return $str;
    }
}
