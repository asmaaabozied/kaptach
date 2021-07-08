<?php

namespace App\Http\Controllers\Clients;

use App\Admin;
use App\Airport;
use App\Client;
use App\Company;
use App\Country;
use App\Guest;
use App\Helpers\BootForm;
use App\Http\Controllers\Controller;
use App\Shuttle;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ShuttlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $airports = Airport::whereNull('deleted_at')->get();
        return view('clients.shuttle.index', compact('airports'));
    }

    public function schedule($airport_id, $type, Request $request)
    {
        if ($request->all()) {
            $shuttles = Shuttle::with('shift')
                ->whereNull('deleted_at')
                ->where('airport_id', $airport_id)
                ->where('type', $type)
                ->where('company_id', (auth('admin')->user()->adminable->company_id));
            if (!empty($request->search)) {
                $shuttles->whereDate('shuttle_start_time', $request->search);
            }

            $shuttles->get();
            return Datatables::of($shuttles)
                ->editColumn('id', function ($model) {
                    return BootForm::routeLink('clients.shuttles.show', $model->id, ['value' => $model->id]);
                })
                ->addColumn('time', function ($model) {
                    $time = date('H:i', strtotime($model->shuttle_start_time));
                    $btn_style = "btn btn-danger btn-block";
                    if ($model->number_seats > $model->number_of_booking)
                        $btn_style = "btn btn-info btn-block";
                    return "<a class='" . $btn_style . "' href='" . route('clients.shuttles.reservation', ['id' => $model->id]) . "'>" . $time . "</a>";
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
                ->rawColumns(['id', 'time'])
                ->make(true);
        }

        $search_date = strftime('%F');
        $airport = Airport::find($airport_id);
        return view('hotels.shuttle.schedule', compact('type', 'airport', 'search_date'));
    }

    public function reservation($shuttleId)
    {
        $shuttle = Shuttle::with('station', 'airport')->findOrFail($shuttleId);
        if ($shuttle->number_seats == $shuttle->number_of_booking) {
            flash()->error("There are not enough seats!");
            return redirect(route('clients.shuttles.schedule', ['id' => $shuttle->airport_id, 'type' => $shuttle->type]));
        }
        $client = Client::findOrFail(auth('admin')->user()->adminable->id);
        $shuttles_of_hotel = $client->shuttles()->where('shuttle_id', $shuttle->id)->first();
        if ($shuttles_of_hotel) {
            $price = $client->shuttles()->where('shuttle_id', $shuttle->id)->first()->pivot->price;
        } else {
            $price = 0;
        }

        $countries = Country::all();
        return view('clients.shuttle.reservation', compact('shuttle', 'countries', 'price'));
    }

    public function storeReservation(Request $request, $shuttleId)
    {
        $shuttle = Shuttle::findOrFail($shuttleId);
        $client_id = auth('admin')->user()->adminable->id;
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
            $shuttle->guests()->attach($guest->id, ['host_id' => 0, 'client_id' => $client_id]);
            $shuttle->increment('number_of_booking');
        }

        flash()->success('Data was saved successfully');
        return redirect(route('clients.shuttles.schedule', ['id' => $shuttle->airport_id, 'type' => $shuttle->type]));

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shuttle = Shuttle::with('shift', 'employers', 'guests', 'paymentType')->findOrFail($id);
        $client = Client::findOrFail(auth('admin')->user()->adminable->id);
        return view('clients.shuttle.show', compact('shuttle', 'client'));
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
}
