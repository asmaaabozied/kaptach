<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Payment;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PaymentsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->all()) {
            $client_id = auth('admin')->user()->adminable->id;
            $payments = Payment::where('client_id', $client_id)->whereNull('deleted_at');
            if ($request->input('from') != '') {
                $payments->where(DB::raw('concat(deducted_year ,"-0",deducted_month)'), '>=', date('Y-m', strtotime($request->input('from'))));
            }
            if ($request->input('to') != '') {
                $payments->where(DB::raw('concat(deducted_year ,"-0",deducted_month)'), '<=', date('Y-m', strtotime($request->input('to'))));

            }
            $payments->get();

            return Datatables::of($payments)
                ->editColumn('created_at', function ($model) {
                    return date('Y-m-d', strtotime($model->created_at));
                })
               ->editColumn('deducted_month', function ($model) {
                    $dateObj = DateTime::createFromFormat('!m', $model->deducted_month);
                    $monthName = $dateObj->format('F');
                    return $monthName;
                })
                ->make(true);
        }
        return view('clients.payments.index');
    }
}
