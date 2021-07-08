<?php

namespace App\Http\Controllers\Companies;

use App\Car_model;
use App\Client;
use App\Company;
use App\Country;
use App\Guest;
use App\Helpers\BootForm;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Traits\SoftDeleteTrait;
use Illuminate\Http\Request;
use App\Airport;
use App\Car;
use App\Shuttle;
use App\Employer;
use Yajra\DataTables\DataTables;

class ShuttlesController extends BaseController
{
    use SoftDeleteTrait;

    public function index()
    {
        $airports = Airport::whereNull('deleted_at')->get();
        return view('companies.shuttle.index', compact('airports'));
    }

    public function schedule($airport_id, $type, Request $request)
    {
        if ($request->all()) {
            $shuttles = Shuttle::with('shift')
                ->whereNull('deleted_at')
                ->where('airport_id', $airport_id)
                ->where('type', $type)
                ->where('company_id', auth('admin')->user()->adminable->id);
            if (!empty($request->search)) {
                $shuttles->whereDate('shuttle_start_time', $request->search);
            }
            $shuttles->get();
            return Datatables::of($shuttles)
                ->editColumn('id', function ($model) {
                    return BootForm::routeLink('shuttles.show', $model->id, ['value' => $model->id]);
                })
                ->addColumn('time', function ($model) {
                    $time = date('H:i', strtotime($model->shuttle_start_time));
                    $btn_style = "btn btn-danger btn-block";
                    if ($model->number_seats > $model->number_of_booking)
                        $btn_style = "btn btn-info btn-block";
                    return "<a class='" . $btn_style . "' href='" . route('shuttles.reservation', ['id' => $model->id]) . "'>" . $time . "</a>";
                })
                ->addColumn('driver', function ($model) {
                    if ($model->shift)
                        return $model->shift->employer->first_name . ' ' . $model->shift->employer->last_name;
                    else
                        return "";
                })
                ->editColumn('car_model', function ($model) {
                    return $model->car_model['ModelWithSeats'];
                })
                ->editColumn('seats', function ($model) {
                    return $model->number_seats;
                })
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;
                    return
                        BootForm::linkOfEdit('shuttles.edit', $model->id, $trashed, !$trashed)
//                        BootForm::routeLink('corporate.shuttles.reservation', $model->id, ['icon' => 'fa-ticket'], true, '', !$trashed)
                        . BootForm::linkOfDelete('shuttles.soft_delete', $model->id, $model->id, 'link', true, '', !$trashed);

                })
                ->rawColumns(['id', 'action', 'time'])
                ->make(true);
        }

        $search_date = strftime('%F');
        $airport = Airport::find($airport_id);
        return view('companies.shuttle.schedule', compact('type', 'airport', 'search_date'));
    }

    public function reservation($shuttleId)
    {
        $shuttle = Shuttle::with('station', 'airport')->findOrFail($shuttleId);
        $clients = Client::where('parent_id', auth('admin')->user()->adminable->id)
            ->where('station_id', $shuttle->station_id)
            ->whereNull('deleted_at')->get();
        if ($shuttle->number_seats == $shuttle->number_of_booking) {
            flash()->error("There are not enough seats!");
            return redirect(route('shuttles.schedule', ['id' => $shuttle->airport_id, 'type' => $shuttle->type]));
        }
        $countries = Country::all();
        return view('companies.shuttle.reservation', compact('shuttle', 'countries', 'clients'));
    }

    public function storeReservation(Request $request, $shuttleId)
    {
        $shuttle = Shuttle::findOrFail($shuttleId);
        for ($i = 0; $i < $request->number_of_booking; $i++) {
            $identity_number = $request->identity_number[$i];
            $guest = Guest::updateOrCreate(
                ['identity_number' => $identity_number], [
                'nationality' => $request->nationality[$i],
                'gender' => $request->gender[$i],
                'phone' => $request->phone[$i],
                'first_name' => $request->first_name[$i],
                'last_name' => $request->last_name[$i],
//                'room_number' => $request->room_number[$i],
            ]);
            $shuttle->guests()->attach($guest->id, ['host_id' => null]);
//        $shuttle->guests()->attach(0, ['guest_id' => $guest->id, 'client_id' => $request->hotel_id[$i]]);
            $shuttle->increment('number_of_booking');
        }
        //for clients price
        for ($i = 0; $i < count($request->client_id); $i++) {
            $shuttle->clients()->attach($request->client_id[$i], ['price' => $request->price[$i]]);
        }

        flash()->success('Data was saved successfully');
        return redirect(route('shuttles.schedule', ['id' => $shuttle->airport_id, 'type' => $shuttle->type]));

    }

    public function create($airport_id, $type)
    {
        $airport = Airport::whereNull('deleted_at')->find($airport_id);
        $car_models = Car_model::whereNull('deleted_at')->get();
        return view('companies.shuttle.create', compact('type', 'airport', 'car_models'));
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'datetimepicker' => 'required',
            'shift' => 'required',
            'car_model_id' => 'required',
        ]);
        $airport = Airport::find($request->airport_id);
        $data = [
            'airport_id' => $request->airport_id,
            'type' => $request->type,
            'shuttle_start_time' => $request->datetimepicker,
            'payment_type_id' => 1,
            'company_id' => auth('admin')->user()->adminable->id,
            'shift_id' => $request->shift,
            'car_model_id' => $request->car_model_id,
            'station_id' => $request->station_id,
            'number_seats' => $request->number_seats,
            'notes' => $request->notes

        ];
        if ($request->type == 'arrival') {
            $data['address_starting_point'] = $airport->address;
            $data['GPS_starting_point'] = $airport->lat . '-' . $airport->lng;
        } else {
            $data['address_destination'] = $airport->address;
            $data['GPS_destination'] = $airport->lat . '-' . $airport->lng;;
        }
        $shuttle = Shuttle::create($data);
        if ($shuttle)
            flash()->success('Data was saved successfully');
        else
            flash()->error('failed to update data, please try again later');
        return redirect(route('shuttles.schedule', ['id' => $airport->id, 'type' => $request->type]));


    }

    public function show($id)
    {
        $shuttle = Shuttle::with('shift', 'employers', 'guests', 'paymentType')->findOrFail($id);
        return view('companies.shuttle.show', compact('shuttle'));
    }

    public function edit($id)
    {
        $shuttle = Shuttle::FindOrFail($id);
        $car_models = Car_model::whereNull('deleted_at')->get();
        return view('companies.shuttle.edit', compact('shuttle', 'car_models'));
    }

    public function update(Request $request, $id)
    {
        $shuttle = Shuttle::FindOrFail($id);
        $validator = $request->validate([
            'datetimepicker' => 'required',
            'shift' => 'required',
            'car_model_id' => 'required',
        ]);
        $airport = Airport::find($shuttle->airport->id);
        $data = [
            'shuttle_start_time' => $request->datetimepicker,
            'payment_type_id' => 1,
            'company_id' => auth('admin')->user()->adminable_id,
            'shift_id' => $request->shift,
            'car_model_id' => $request->car_model_id,
            'number_seats' => $request->number_seats,
            'notes' => $request->notes

        ];
        if ($request->type == 'arrival') {
            $data['address_starting_point'] = $airport->address;
            $data['GPS_starting_point'] = $airport->lat . '-' . $airport->lng;
        } else {
            $data['address_destination'] = $airport->address;
            $data['GPS_destination'] = $airport->lat . '-' . $airport->lng;;
        }
        if ($shuttle->update($data))
            flash()->success('Data was saved successfully');
        else
            flash()->error('failed to update data, please try again later');
        return redirect(route('shuttles.schedule', ['id' => $request->airport_id, 'type' => $request->type]));
    }

    public function destroy($id)
    {
        //
    }

    public function softDelete($id)
    {
        $shuttle = Shuttle::findOrFail($id);
        //soft delete transfer
        if ($this->softDeleteModel($shuttle)) {
            $shuttle->save();
            flash()->success('Data was deleted successfully');
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect(route('shuttles.schedule', ['id' => $shuttle->airport_id, 'type' => $shuttle->type]));
    }
}
