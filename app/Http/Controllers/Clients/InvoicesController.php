<?php

namespace App\Http\Controllers\Clients;

use App\Company;
use App\Helpers\BootForm;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Invoice;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use PDF;

class InvoicesController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->all()) {
            $client_id = auth('admin')->user()->adminable->id;
            $invoices = Invoice::where('client_id', $client_id)->whereNull('deleted_at');
            if ($request->input('code') != '') {
                $invoices->where('code', $request->input('code'));
            }
            if ($request->input('from') != '') {
                $invoices->where(DB::raw('concat(deducted_year ,"-0",deducted_month)'), '>=', date('Y-m', strtotime($request->input('from'))));
            }
            if ($request->input('to') != '') {
                $invoices->where(DB::raw('concat(deducted_year ,"-0",deducted_month)'), '<=', date('Y-m', strtotime($request->input('to'))));

            }
            $invoices->get();

            return Datatables::of($invoices)
                ->editColumn('code', function ($model) {
                    return BootForm::routeLink('clients.invoices.show', $model->id, ['value' => $model->code]);

                })
                ->editColumn('tax', function ($model) {
                    return $model->tax . ' %';

                })->editColumn('created_at', function ($model) {
                    return date('Y-m-d', strtotime($model->created_at));
                })
                ->editColumn('client.name', function ($model) {
                    return $model->client['name'];
                })->editColumn('deducted_month', function ($model) {
                    $dateObj = DateTime::createFromFormat('!m', $model->deducted_month);
                    $monthName = $dateObj->format('F');
                    return $monthName;
                })
                ->rawColumns(['code'])
                ->make(true);
        }
        return view('clients.invoices.index');
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        $to_company = Company::findOrFail(auth('admin')->user()->adminable->company_id);
        return view('clients.invoices.show', compact('to_company', 'invoice'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function downloadPDF($id)
    {
        $invoice = Invoice::findOrFail($id);
        $to_corporate = Company::findOrFail(auth('admin')->user()->adminable->company_id);
        $pdf = PDF::loadView('invoice.pdf', compact('invoice', 'to_corporate'));
        return $pdf->download('invoice.pdf');
    }
}
