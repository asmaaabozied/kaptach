@extends('layouts.master')
@section('title',' Annual Report')
@section('styles')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datepicker/datepicker3.css')}}">
    <style>
    
    </style>
@endsection

@section('content')
    <div class="box col-lg-10">
        <div class="box-header with-border">
            <h2 class="page-header">
                <i class="fa fa-globe"></i> Kaptan
                <small class="pull-right">Date: 2/10/2014</small>
              </h2>      
        </div>
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col"><strong>Annual report for year 2019</strong><br><strong> Hotel:</strong>Hilton Bakir Koy</div>
            <div class="col-sm-5 invoice-col"></div>
            <div class="col-sm-3 invoice-col">                
                <address>
                  <strong>Kaptan co.</strong><br>
                  Phone: (804) 123-5432<br>
                  Email: info@almasaeedstudio.com
                </address>
            </div>
        </div>    
        <div class="box-body">
        <div class="row">
            <div class="col-xs-12 table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead style="background-color: darkgray">
                    <tr>
                        <th>Month</th>
                        <th>Sales</th>
                        <th>Payments</th>
                        <th>Tax</th>
                        <th>Invoice</th>
                        <th>Dept</th>
                        <th>Total</th>
                    </tr>
                    <tr style="background-color: bisque">
                        <th>Last Balance</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>1090</td>
                        <td></td>
                    </tr> 
                </thead>
                <tbody>
                <tr>
                  <td>January</td>
                  <td>2500</td>
                  <td style="color: red">-2590</td>
                  <td>90</td>
                  <td>590</td>
                  <td style="color: blue">0</td>
                  <td style="color: mediumvioletred">1090</td>
                </tr>
                <tr>
                  <td>February</td>
                  <td>2300</td>
                  <td style="color: red">-2330</td>
                  <td>30</td>
                  <td>330</td>
                  <td style="color: blue">0</td>
                  <td style="color: mediumvioletred">1090</td>
                </tr>
                <tr>
                  <td>March</td>
                  <td>4900</td>
                  <td style="color: red">-4940</td>
                  <td>40</td>
                  <td>440</td>
                  <td style="color: blue">0</td>
                  <td style="color: mediumvioletred">1090</td>
                </tr> 
                <tr>
                    <td>April</td>
                    <td>1000</td>
                    <td style="color: red">0</td>
                    <td>0</td>
                    <td>0</td>
                    <td style="color: blue">1000</td>
                    <td style="color: mediumvioletred">2090</td>
                  </tr>                
                </tbody>
                <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="background-color: lightskyblue">Balance: 2090</th>
                </tfoot>
              </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6"></div>    
        <div class="col-xs-6">
            <p class="lead">Amount Due 2019</p>
  
            <div class="table-responsive">
              <table class="table">
                <tr>
                  <th style="width:50%">Last Balance:</th>
                  <td>1090</td>
                </tr>
                <tr>
                  <th>Sales:</th>
                  <td>10.700</td>
                </tr>
                <tr>
                  <th>Payments</th>
                  <td>-9860</td>
                </tr>
                <tr>
                  <th>Dept:</th>
                  <td>1000</td>
                </tr>
                <tr>
                  <th>Total:</th>
                  <td>2090</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
    </div>        
@endsection
@section('scripts')
 
@endsection       