<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Payment;
use DateTime;
use Illuminate\Http\Request;
use App\Company;
use App\Transfer;
use App\Shuttle;

class ReportsController extends BaseController
{

    public function index()
    {
        # code...
    }

    public function annual()
    {
        $id = auth('admin')->user()->adminable->id;
        $type = auth('admin')->user()->type;
        if ($type == 'transfer_company') {
            $clients = Client::where('company_id', $id)
                ->whereNull('deleted_at')->get();
            return view('reports.annual_report', compact('clients'));
        } else {
            return view('reports.annual_report');
        }

    }

    public function annual_print()
    {
        return view('reports.annual_report_print');
    }
    public function loadData(Request $request)
    {
        if ($request->all()) {
            if (isset($request->item))
                $item = $request->item;
            else
                $item = auth('admin')->user()->adminable->id;
            $year = $request->year;
            return $this->currentBalance($item, $year);

        }
    }

    public function currentBalance($item, $year)
    {
        $data = [];
        $total = 0;
        $sales = 0;
        $total_payments = 0;
        $total_tax = 0;
        $client = Client::whereNull('deleted_at')->find($item);
        $last_year = $year - 1;
        $last_balance = 0;
        for ($j = 2019; $j <= $last_year; $j++) {
            $last_balance = $last_balance + $this->lastBalance($item, $j);
        }
        for ($month = 1; $month <= 12; $month++) {
            if ($year == date('Y'))
                $transfers = $client->transfers()
                    ->whereYear('transfer_start_time', '=', $year)
                    ->whereMonth('transfer_start_time', '=', $month)
                    ->whereDate('transfer_start_time', '<=', date('Y-m-d'))
                    ->whereNull('deleted_at');
            else
                $transfers = $client->transfers()
                    ->whereYear('transfer_start_time', '=', $year)
                    ->whereMonth('transfer_start_time', '=', $month)
                    ->whereNull('deleted_at');

            $invoices = Invoice::where('client_id', $item)->where('deducted_year', $year)
                ->where('deducted_month', $month)
                ->whereNull('deleted_at')
                ->get();
            $payments = Payment::where('client_id', $item)->where('deducted_year', $year)
                ->where('deducted_month', $month)
                ->whereNull('deleted_at')
                ->get();
            if ($transfers) {
                $total_transfers = $transfers->sum('price');
            } else
                $total_transfers = 0;

            if ($invoices) {
                $tax = 0;
                $total_invoices = 0;
                foreach ($invoices as $invoice) {
                    $tax_d = ($invoice->tax / 100) * $invoice->price;
                    $tax = $tax + $tax_d;
                    $total_invoices = $total_invoices + $invoice->price + $tax_d;
                }
            }

            if ($payments)
                $t_payments = $payments->sum('amount');
            else
                $t_payments = 0;

            $dept = $total_transfers + $tax - $t_payments;
            $total = $total + $dept;
            if ($month == 1) {
                $total = $total + $last_balance;
            }
            $sales = $sales + $total_transfers;
            $total_payments = $total_payments + $t_payments;
            $total_tax = $total_tax + $tax;
            $dateObj = DateTime::createFromFormat('!m', $month);
            $monthName = $dateObj->format('F');
            $data['month'][$month] = [
                'month' => $monthName,
                'month_num' => $month,
                'sales' => $total_transfers,
                'payments' => $t_payments,
                'invoices' => $total_invoices,
                'tax' => $tax,
                'dept' => $dept,
                'total' => $total
            ];

        }
        $data['total'] = $total;
        $data['total_sales'] = $sales;
        $data['total_payments'] = $total_payments;
        $data['total_tax'] = $total_tax;
        $data['year'] = $year;
        $data['item'] = $item;

        return view('reports.ajax_load_data', compact('last_balance', 'data', 'last_year'));
    }

    public function lastBalance($item, $last_year)
    {
        $client = Client::whereNull('deleted_at')->find($item);
        $transfers = $client->transfers()
            ->whereYear('transfer_start_time', '=', $last_year)
            ->whereNull('deleted_at');
        if ($transfers)
            $total_transfers = $transfers->sum('price');
        else
            $total_transfers = 0;
        $invoices = Invoice::where('client_id', $item)->where('deducted_year', $last_year)
            ->whereNull('deleted_at')
            ->get();
        if ($invoices) {
            $tax = 0;
            foreach ($invoices as $invoice) {
                $tax_d = ($invoice->tax / 100) * $invoice->price;
                $tax = $tax + $tax_d;
            }
        } else
            $tax = 0;

        $payments = Payment::where('client_id', $item)->where('deducted_year', $last_year)
            ->whereNull('deleted_at')
            ->get();
        if ($payments)
            $total_payments = $payments->sum('amount');
        else
            $total_payments = 0;
        return $total_transfers + $tax - $total_payments;
    }

    public function payments($client_id, $year, $month)
    {
        $client = Company::findOrFail($client_id);
        $payments = Payment::where('client_id', $client_id)->where('deducted_year', $year)
            ->where('deducted_month', $month)
            ->get();
        return view('reports.payments_report', compact('payments', 'month', 'year', 'client'));
    }

    public function invoices($client_id, $year, $month)
    {
        $client = Company::findOrFail($client_id);
        $invoices = Invoice::where('client_id', $client_id)->where('deducted_year', $year)
            ->where('deducted_month', $month)
            ->get();
        return view('reports.invoices_report', compact('invoices', 'month', 'year', 'client'));
    }

    public function transportation($client_id, $year, $month)
    {
        $client = Client::findOrFail($client_id);
        $transfers = $client->transfers()
            ->whereYear('transfer_start_time', '=', $year)
            ->whereMonth('transfer_start_time', '=', $month)
            ->whereNull('deleted_at')->orderBy('transfer_start_time', 'ASC')->get();
        return view('reports.transportation_report', compact('client', 'month', 'year', 'transfers'));

    }


    public function clients_balance()
    {
        $company_id = auth('admin')->user()->adminable->id;
        $type = auth('admin')->user()->type;
        if ($type == 'transfer_company') {
            $clients = Client::where('company_id', $company_id)
                ->whereNull('deleted_at')->get();
            return view('reports.clients_balance_report', compact('clients'));
        } else {
            return view('reports.clients_balance_report');
        }

    }

    public function loadClientBalance(Request $request)
    {
        if ($request->all()) {
            $type = auth('admin')->user()->type;
            if ($type == "transfer_company") {
                $company_id = auth('admin')->user()->adminable->id;
                if (isset($request->item))
                    $item = $request->item;
                else
                    $item = 'all';

            } else {
                $item = auth('admin')->user()->adminable->id;
            }

            $date = $request->date;
            $month = explode('/', $date)[0];
            $year = explode('/', $date)[1];

            $total = 0;
            $total_tax = 0;
            $data = [];
            if ($item == 'all') {
                $clients = Client::where('company_id', $company_id)
                    ->whereNull('deleted_at')->get();
                foreach ($clients as $client) {
                    $returned_data = $this->getForeachClientBalance($client->id, $year, $month);
                    $data[$client->id] = $returned_data;
                }
            } else {
                $returned_data = $this->getForeachClientBalance($item, $year, $month);

                $data[$item] = $returned_data;

            }

            return view('reports.ajax_client_balance', compact('data'));

        }
    }

    private function getForeachClientBalance($item, $year, $month = null)
    {
        $total = 0;
        $total_tax = 0;
        $data = [];
        $client = Client::whereNull('deleted_at')->find($item);
        $transfers = $client->transfers()->whereYear('transfer_start_time', '=', $year)
            ->whereMonth('transfer_start_time', '=', $month)
            ->whereNull('deleted_at');

        $payments = Payment::where('client_id', $item)->where('deducted_year', $year)
            ->where('deducted_month', $month)
            ->get();

        $invoices = Invoice::where('client_id', $item)->where('deducted_year', $year)
            ->where('deducted_month', $month)
            ->get();
        if ($invoices) {
            $tax = 0;
            $total_invoices = 0;
            foreach ($invoices as $invoice) {
                $tax_d = ($invoice->tax / 100) * $invoice->price;
                $tax = $tax + $tax_d;
                $total_invoices = $total_invoices + $invoice->price + $tax_d;
            }
        }

        if ($payments)
            $t_payments = $payments->sum('amount');
        else
            $t_payments = 0;

        if ($transfers)
            $total_transfers = $transfers->sum('price');
        else
            $total_transfers = 0;

        $dept = $total_transfers + $tax - $t_payments;
        $total = $total + $dept;
        $total_tax = $total_tax + $tax;

        $data = [
            'name' => $client->name,
            'total' => $total,
            'transfers' => $total_transfers,
            'total_tax' => $total_tax,
            'total_payments' => $t_payments
        ];
        return $data;

    }

    public function charts()
    {
        return view('reports.charts_report');
    }

}
