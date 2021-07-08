<?php

namespace App\Http\Controllers\Companies;

use App\Car;
use App\Client;
use App\Driver;
use App\Employer;
use App\Events\TransferCreated;
use App\Guest;
use App\Helpers\PushApi;
use App\Host;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Shift;
use App\Status;
use App\Store;
use function foo\func;
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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->all()) {
            $transfers = Transfer::with('actionByAdmin', 'airport',
                'car_model', 'forSalesCompanies', 'transferable', 'exchange', 'forSaleDrivers')
                ->with(['driver' => function ($q) {
                    return $q->with('employer');
                }])
                ->whereNull('deleted_at')
                ->where('company_id', auth('admin')->user()->adminable->id);
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
            if ($request->input('hotel_id')) {
                $id = $request->input('hotel_id');
                $transfers->client($id);
            }
            $transfers->orderBy('transfer_start_time', 'ASC');
            $transfers->get();
//            $length = $request->input('length');
//            $transfers = $transfers->paginate($length);
//            return ['data' => $transfers, 'draw' => $request->input('draw')];
            // dd($transfers);
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

                    if (!$model->forSaleDrivers->isEmpty()) {
                        if ($model->forSaleDrivers->first()->buyable_type != "") {
                            $store_id = $model->forSaleDrivers->first()->id;
                            $str .= '<li>' . BootForm::routeLink('transfers.cancelOfferForSale', $store_id, ['value' => __('buttons.cancel') . ' ' . __('buttons.for_sale'), 'id' => 'btn_cancel', 'class' => 'sub-menu']) . '</li>';
                        }

                    }
                    $str .= '<li> <a href="#" class="sub-menu btn_transfer" id="' . $model->id . '">' . __('buttons.duplicate') . ' ' . __('pages.transfer') . '</a></li>';
                    $str .= '<li>' . BootForm::routeLink('transfers.showTicket', $model->id, ['value' => __('buttons.ticket'), 'class' => 'sub-menu'], true) . '</li>'
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
        $hotels = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->where('type', 'hotel')->whereNull('deleted_at')->get()->pluck('name', 'id');
        return view('companies.transfer.index', compact('search_date', 'airports', 'hotels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add($airport_id, $type)
    {
        $company = Company::findOrFail(auth('admin')->user()->adminable->id);
        $airport = Airport::findOrFail($airport_id);
        $host = Host::whereHas('companies', function ($q) {
            $q->where('company_id', auth('admin')->user()->adminable->id);
        })->where('airport_id', $airport_id)
            ->whereNull('deleted_at')->first();
        if ($company->cars->isEmpty()) {
            flash()->error('Please add a car!');
            return redirect(route('cars.create'));
        }

        if (!$host) {
            flash()->error('Please add a host for airport ' . $airport->name);
            return redirect(route('hosts.create'));
        }
        $countries = Country::all();
        $car_models = Car_model::whereNull('deleted_at')->get();
        $hotels = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->where('type', 'hotel')->whereNull('deleted_at')->get();
        $hosts = Host::whereHas('companies', function ($q) {
            $q->where('company_id', auth('admin')->user()->adminable->id);
        })->where('airport_id', $airport_id)->whereNull('deleted_at')->get();

        return view('companies.transfer.create', compact('car_models', 'countries', 'hotels', 'type', 'airport', 'hosts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param PushApi $pushApi
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PushApi $pushApi)
    {
        $validator = $request->validate([
            'datetimepicker' => 'required',
//            'shift' => 'required',
            'car_model_id' => 'required',
            'hotel_id' => 'required',
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
        $hotel = Client::find($request->hotel_id);
        $airport = Airport::find($request->airport_id);
        $company = Company::findOrFail(auth('admin')->user()->adminable->id);
        $data = [
            'airport_id' => $request->airport_id,
            'type' => $request->type,
            'transfer_start_time' => $request->datetimepicker,
            'flight_number' => $request->flight_number,
//            'flight_date' => date('y-m-d', strtotime($request->datetimepicker)),
//            'flight_departure_time' => date("H:i", strtotime($request->datetimepicker)),
//            'requested_by_admin' => auth('admin')->user()->id,
            'took_action_by_admin' => auth('admin')->user()->id,
            'payment_type_id' => 1,
            'company_id' => auth('admin')->user()->adminable->id,
            'car_model_id' => $request->car_model_id,
            'request_status' => 1,
            'price' => $request->price,
            'number_seats' => $car_model->max_seats,
            'number_of_booking' => $request->number_of_booking,
            'notes' => $request->notes

        ];

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

        for ($i = 0; $i < $request->number_of_booking; $i++) {
            $identity_number = $request->identity_number[$i];
            $str = Guest::makeIdentityNumber($request->identity_number[$i]);
            $d = [
                'company_id' => auth('admin')->user()->adminable->id,
                'client_id' => $hotel->id,
                'driver_id' => ((isset($shift)) ? $shift->driver->id : Null),
                'nationality' => $request->nationality[$i],
                'gender' => $request->gender[$i],
                'phone' => $request->phoneCode[$i] . $request->phone[$i],
                'first_name' => $request->first_name[$i],
                'last_name' => $request->last_name[$i],
            ];
            $guest = Guest::updateOrCreate(
                ['identity_number' => $str], $d);
            $transfer->guests()->attach($guest->id,
                ['host_id' => $data['host_id'], 'room_number' => $request->room_number[$i]]);

        }

//        event(new TransferCreated($res));
//        TransferCreated::dispatch($res);
        flash()->success('Data was saved successfully');
        return redirect(route('transfers.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transfer = Transfer::with('shift', 'guests', 'paymentType')->findOrFail($id);
        $statuses = $transfer->statuses()->pluck('status')->toArray();
        $transfer_statuses = $transfer->statuses()->pluck('status')->toArray();
        $lat = explode('-', $transfer->GPS_starting_point)[0];
        $lang = explode('-', $transfer->GPS_starting_point)[1];
        return view('companies.transfer.show', compact('transfer', 'transfer_statuses',
            'lang', 'lat', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transfer = Transfer::with('shift', 'guests', 'paymentType', 'car_model')
            ->findOrFail($id);
        $statuses = $transfer->statuses()->pluck('status')->toArray();
        $countries = Country::all();
        $car_models = Car_model::whereNull('deleted_at')->get();
        $hotels = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->where('type', 'hotel')->whereNull('deleted_at')->get();
        $hosts = Host::whereHas('companies', function ($q) {
            $q->where('company_id', auth('admin')->user()->adminable->id);
        })->where('airport_id', $transfer->airport_id)->whereNull('deleted_at')->get();
        $airport = Airport::findOrFail($transfer->airport_id);
        if (!$hosts) {
            flash()->error('Please add a host for airport ' . $airport->name);
            return redirect(route('hosts.create'));
        }
        $arr = [
            'car_model_id' => $transfer->car_model_id,
            'date' => $transfer->transfer_start_time,
            'shift' => $transfer->shift_id
        ];
        $shifts = $this->driverShifts($arr);
        return view('companies.transfer.edit', compact('transfer', 'car_models',
            'hotels', 'countries', 'hosts', 'shifts', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @param PushApi $pushApi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, PushApi $pushApi)
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
        $hotel = Client::find($transfer->transferable_id);
        $airport = Airport::find($transfer->airport_id);
        $data = [
            'transfer_start_time' => $request->datetimepicker,
            'took_action_by_admin' => auth('admin')->user()->id,
            'payment_type_id' => 1,
            'company_id' => auth('admin')->user()->adminable->id,
            'flight_number' => $request->flight_number,
            'car_model_id' => $request->car_model_id,
            'request_status' => $request->request_status,
            'price' => $request->price,
            'number_seats' => $car_model->max_seats,
            'number_of_booking' => $request->number_of_booking,
            'notes' => $request->notes,

        ];
        $old_shift_id = $transfer->shift_id;

        if ($request->shift) {
            $shift = Shift::findOrFail($request->shift);
            if ($transfer->shift != '') {
                if ($shift->driver_id != $transfer->shift->driver_id) {
                    $data['driver_acceptance'] = '0';
                }
            }
            $data['shift_id'] = $request->shift;
            $data['car_id'] = $shift->car->id;
            $data['driver_id'] = $shift->driver_id;
        }
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
        //cancelled inputs

        if ($request->cancelled) {
            $cancel_date = \Carbon\Carbon::now();
            $data['cancel_reason'] = $request->cancel_reason;
            $data['cancelled'] = 1;
            $data['cancellation_date'] = $cancel_date;

        }
        //update customers
        for ($i = 0; $i < $request->number_of_booking; $i++) {
            $identity_number = $request->identity_number[$i];
            $guest = Guest::updateOrCreate(
                ['identity_number' => $identity_number], [
                'driver_id' => ((isset($shift)) ? $shift->driver->id : Null),
                'nationality' => $request->nationality[$i],
                'gender' => $request->gender[$i],
                'phone' => $request->phone[$i],
                'first_name' => $request->first_name[$i],
                'last_name' => $request->last_name[$i],
                'room_number' => $request->room_number[$i],
            ]);
            // $transfer->employers()->updateExistingPivot($guest->id);

        }
        //host
        $host_id = $transfer->host_id;
        if ($request->type == 'arrival') {
            if (empty($host_id)) {
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

            }
        } else {
            $data['host_id'] = null;
        }

        //
        if (!$transfer->update($data))
            flash()->error('failed to update data, please try again later');
        else {
            if ($request->cancelled) {
                $id = Company::find(auth('admin')->user()->adminable->id);
                $transfer->cancellable()->associate($id)->save();
            }
            flash()->success('Data was saved successfully');
        }

        return redirect(route('transfers.index'));
    }

    public function softDelete($id)
    {
        $transfer = Transfer::findOrFail($id);
        //soft delete transfer

        if ($this->softDeleteModel($transfer)) {
            flash()->success('Data was deleted successfully');
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('transfers.index');
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
        $hotel_id = $request->hotel_id;
        $airport_id = $request->airport_id;
        $car_model_id = $request->car_model_id;
        $type = $request->type;
        $result = Transfer_price_list::where('car_model_id', $car_model_id)
            ->where('client_id', $hotel_id)
            ->where('airport_id', $airport_id)
            ->where('company_id', auth('admin')->user()->adminable->id)
            ->whereNull('deleted_at')->first();
        if (isset($result))
            $price = ($type == "arrival") ? $result->arrival_price : $result->departure_price;
        else
            $price = 0;
        $data = array('price' => $price);
        return response()->json($data);
    }

    public function approve($id)
    {
        $transfer = Transfer::find($id);
        if ($transfer->request_status == 0) {
            //host
            $host_id = $transfer->host_id;
            if ($transfer->type == 'arrival') {
                if (empty($host_id)) {
                    $host = Host::whereHas('companies', function ($q) {
                        $q->where('company_id', auth('admin')->user()->adminable->id);
                    })->where('airport_id', $transfer->airport_id)
                        ->whereNull('deleted_at')->first();

                    if ($host)
                        $inputs['host_id'] = $host->id;
                    else {
                        $airport = Airport::find($transfer->airport_id);
                        flash()->error('Please add host for airport ' . $airport->name);
                        return redirect(route('hosts.create'));
                    }

                }
            } else {
                $inputs['host_id'] = null;
            }

            //
            $inputs['request_status'] = 1;

        } else
            $inputs['request_status'] = 0;
        if (!$transfer->update($inputs))
            flash()->error('failed to update data, please try again later');
        else
            flash()->success('Data was saved successfully');
        return redirect(route('transfers.index'));
    }

    /**
     * Display the ticket of transfer.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showTicket($id)
    {
        $transfer = Transfer::with('shift', 'driver', 'guests', 'paymentType')->findOrFail($id);
        return view('companies.transfer.ticket', compact('transfer'));
    }

    public function downloadPDF($id)
    {
        $transfer = Transfer::with('shift', 'driver', 'guests', 'paymentType')->findOrFail($id);
//       return view('ticket.pdf',compact('transfer'));
        $pdf = \PDF::loadView('ticket.pdf', compact('transfer'));
        return $pdf->download('ticket.pdf');
    }

    public function showCancelTransfer($id)
    {
        $transfer = Transfer::findOrFail($id);
        if ($transfer->cancelled == 1) {
            $update = $transfer->update(['cancelled' => 0, 'cancel_reason' => null,
                'cancellation_date' => null, 'cancellable_id' => null, 'cancellable_type' => null]);
            if (!$update)
                flash()->error('failed to update data, please try again later');
            else
                flash()->success('Data was saved successfully');
            return redirect(route('transfers.index'));
        } else {
            return view('companies.transfer.cancel_transfer', compact('transfer'));
        }

    }

    public function cancelTransfer($id, Request $request)
    {
        $request->validate([
            'cancel_reason' => 'required'
        ]);
        $transfer = Transfer::findOrFail($id);
        $cancel_date = \Carbon\Carbon::now();
        $update = $transfer->update([
            'cancelled' => 1,
            'cancel_reason' => $request->cancel_reason,
            'cancellation_date' => $cancel_date
        ]);

        if (!$update)
            flash()->error('failed to update data, please try again later');
        else {
            $id = Company::find(auth('admin')->user()->adminable->id);
            $transfer->cancellable()->associate($id)->save();
            flash()->success('Data was saved successfully');
        }
        return redirect(route('transfers.index'));
    }

    public function start($t_id)
    {
        $transfer = Transfer::findOrFail($t_id);
        if ($transfer) {
            $id = Company::find(auth('admin')->user()->adminable->id);
            $explode = explode('-', $transfer->GPS_starting_point);
            $this->checkForDuplicatedStatus($transfer->id, 'Start');
            $status = Status::create([
                'status_time' => \Carbon\Carbon::now(),
                'status' => 'Start',
                'lat' => $explode[0],
                'lang' => $explode[1]
            ]);
            $status->statusable()->associate($transfer)->save();
            $status->actors()->associate($id)->save();
            $transfer->update(['status' => 'Start']);
            // delete from offer for sale
            Store::where('transfer_id', $t_id)->whereNull('buyable_id')->delete();
            //
            flash()->success('Data was saved successfully');
        }
        return redirect(route('transfers.show', $t_id));
    }

    private function checkForDuplicatedStatus($transfer_id, $status)
    {
        $transfer = Transfer::findOrFail($transfer_id);
        if (!$transfer->statuses->where('status', $status)->isEmpty()) {
            if ($status == 'Start')
                $transfer->statuses()->delete();
            else
                $transfer->statuses->where('status', $status)->first()->delete();
        }

    }

    public function end($tr_id, Request $request)
    {

        $transfer = Transfer::findOrFail($tr_id);
        $request->validate([
            'lat' => 'required',
            'lang' => 'required'
        ]);
        if ($transfer) {
            $id = Company::find(auth('admin')->user()->adminable->id);
            if ($request->use_default_end_point) {
                $lat = explode('-', $transfer->GPS_destination)[0];
                $lang = explode('-', $transfer->GPS_destination)[1];
            } else {
                $lat = $request->lat;
                $lang = $request->lang;
            }
            $this->checkForDuplicatedStatus($transfer->id, 'End');
            $status = Status::create([
                'status_time' => \Carbon\Carbon::now(),
                'status' => 'End',
                'lat' => $lat,
                'lang' => $lang
            ]);
            $status->statusable()->associate($transfer)->save();
            $status->actors()->associate($id)->save();
            $transfer->update(['status' => 'End']);
            flash()->success('Data was saved successfully');
        }
        return redirect(route('transfers.show', $tr_id));
    }

    public function reset($t_id)
    {
        $transfer = Transfer::findOrFail($t_id);
        if ($transfer) {
            $id = Company::find(auth('admin')->user()->adminable->id);
            $transfer->update(['status' => 'Pending']);
            $this->checkForDuplicatedStatus($transfer->id, 'Start');
            flash()->success('Data was saved successfully');
        }
        return redirect(route('transfers.show', $t_id));
    }

    public function getTransferById($transfer_id)
    {
        $company_id = auth('admin')->user()->adminable->id;
        $value = Transfer::with('actionByAdmin', 'airport', 'transferable',
            'car_model', 'forSalesCompanies', 'company')->find($transfer_id);
        $data = [
            'id' => $value->id,
            'company_id' => $value->company_id,
            'company' => $value->company,
            'transfer_start_time' => $value->transfer_start_time,
            'type' => $value->type,
            'airport' => $value->airport,
            'transferable' => $value->transferable,
            'car_model' => $value->car_model,
            'number_of_booking' => $value->number_of_booking,
            'deleted_at' => $value->deleted_at
        ];
        if ($value->company_id == $company_id) {
            if (!$value->forSalesCompanies->isEmpty()) {
                foreach ($value->forSalesCompanies as $forsale) {
                    if ($forsale->buyable_id == $company_id)
                        $data['purchased'] = 'purchased';
                    if ($forsale->company_id == $company_id && $forsale->buyable_id == "") {
                        $data['for_sale'] = 'For sale';
                    }
                    if ($forsale->company_id == $company_id && $forsale->buyable_id != "")
                        $data['sold'] = 'Sold';

                    $data['store_id'] = $forsale->id;
                }
            }
        } else {
            foreach ($value->forSalesCompanies as $forsale) {
                if ($forsale->buyable_id == $company_id)
                    $data['purchased'] = 'purchased';
                if ($forsale->company_id != $company_id && $forsale->buyable_id == "")
                    $data['for_sale'] = 'For sale';
                if ($forsale->company_id == $company_id && $forsale->buyable_id != "")
                    $data['sold'] = 'Sold';

                $data['store_id'] = $forsale->id;
            }
        }
        return $data;
    }

    public function viewModalForDuplicate($transfer_id)
    {
        $str = '';
        $airports = Airport::whereNull('deleted_at')->get();
        foreach ($airports as $airport) {
            $str .= '<a class="hvrbox" href="' . route('transfers.duplicate_transfer', ['id' => $transfer_id, 'airport_id' => $airport->id, 'type' => 'arrival']) . '">
                                    <img src="' . $airport->arrival_image['thumb'] . '" width="200" height="200"
                                         class="hvrbox-layer_bottom" alt="' . $airport->name . '">
                                    <div class="hvrbox-layer_top">
                                        <div class="hvrbox-text"> Arrival To ' . $airport->name . '</div>
                                    </div>
                                </a>
                                <a class="hvrbox"
                                   href="' . route('transfers.duplicate_transfer', ['id' => $transfer_id, 'airport_id' => $airport->id, 'type' => 'departure']) . '">
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

    public function duplicateTransfer($transfer_id, $airport_id, $type)
    {
        $transfer = Transfer::findOrFail($transfer_id);
        $countries = Country::all();
        $car_models = Car_model::whereNull('deleted_at')->get();
        $hotels = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->where('type', 'hotel')->whereNull('deleted_at')->get();
        $hosts = Host::whereHas('companies', function ($q) {
            $q->where('company_id', auth('admin')->user()->adminable->id);
        })->where('airport_id', $airport_id)->whereNull('deleted_at')->get();
        $airport = Airport::findOrFail($airport_id);
        $arr = [
            'car_model_id' => $transfer->car_model_id,
            'date' => $transfer->transfer_start_time,
            'shift' => $transfer->shift_id
        ];
        $shifts = $this->driverShifts($arr);
        return view('companies.transfer.duplicate', compact('transfer', 'countries', 'car_models', 'hosts', 'hotels', 'type', 'airport', 'shifts'));
    }

    public function storeDuplicateTransfer($transfer_id, $airport_id, $type, Request $request)
    {
        $validator = $request->validate([
            'datetimepicker' => 'required',
//            'shift' => 'required',
            'car_model_id' => 'required',
            'hotel_id' => 'required',
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
        $hotel = Client::find($request->hotel_id);
        $airport = Airport::find($request->airport_id);
        $company = Company::findOrFail(auth('admin')->user()->adminable->id);
        $data = [
            'airport_id' => $request->airport_id,
            'type' => $request->type,
            'transfer_start_time' => $request->datetimepicker,
            'flight_number' => $request->flight_number,
//            'flight_date' => date('y-m-d', strtotime($request->datetimepicker)),
//            'flight_departure_time' => date("H:i", strtotime($request->datetimepicker)),
//            'requested_by_admin' => auth('admin')->user()->id,
            'took_action_by_admin' => auth('admin')->user()->id,
            'payment_type_id' => 1,
            'company_id' => auth('admin')->user()->adminable->id,
            'car_model_id' => $request->car_model_id,
            'request_status' => 1,
            'price' => $request->price,
            'number_seats' => $car_model->max_seats,
            'number_of_booking' => $request->number_of_booking,
            'notes' => $request->notes

        ];

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

        for ($i = 0; $i < $request->number_of_booking; $i++) {
            $identity_number = $request->identity_number[$i];
            $str = Guest::makeIdentityNumber($request->identity_number[$i]);
            $d = [
                'company_id' => auth('admin')->user()->adminable->id,
                'client_id' => $hotel->id,
                'driver_id' => ((isset($shift)) ? $shift->driver->id : Null),
                'nationality' => $request->nationality[$i],
                'gender' => $request->gender[$i],
                'phone' => $request->phoneCode[$i] . $request->phone[$i],
                'first_name' => $request->first_name[$i],
                'last_name' => $request->last_name[$i],
            ];
            $guest = Guest::updateOrCreate(
                ['identity_number' => $str], $d);
            $transfer->guests()->attach($guest->id,
                ['host_id' => $data['host_id'], 'room_number' => $request->room_number[$i]]);

        }
        flash()->success('Data was saved successfully');
        return redirect(route('transfers.index'));
    }
}
