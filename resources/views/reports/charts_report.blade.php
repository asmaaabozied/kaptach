@extends('layouts.master')
@section('title',' Charts Report')
@section('styles')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datepicker/datepicker3.css')}}">
    <style>
    
    </style>
@endsection

@section('content')
    <div class="box col-xs-10">
        <div class="box-header with-border">
            <h3>Charts Report</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                <!-- AREA CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                    <h3 class="box-title">Payments Chart</h3>
                    </div>
                    <div class="box-body">
                    <div class="chart">
                        <canvas id="areaChart" style="height:250px"></canvas>
                    </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
        
                <!-- DONUT CHART -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                    <h3 class="box-title">Clients  Chart</h3>
                    </div>
                    <div class="box-body">
                      <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
        
                </div>
                <!-- /.col (LEFT) -->
                <div class="col-md-6">
                <!-- LINE CHART -->
                <div class="box box-info">
                    <div class="box-header with-border">
                    <h3 class="box-title">Total Payments</h3>
                    </div>
                    <div class="box-body">
                    <div class="chart">
                        <canvas id="lineChart" style="height:250px"></canvas>
                    </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
        
                <!-- BAR CHART -->
                <div class="box box-success">
                    <div class="box-header with-border">
                    <h3 class="box-title">Monthly Payments</h3>
                    </div>
                    <div class="box-body">
                    <div class="chart">
                        <canvas id="barChart" style="height:230px"></canvas>
                    </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
        
                </div>
                <!-- /.col (RIGHT) -->
            </div>
        </div>
    </div>        
@endsection
@section('scripts')
<script src="{{asset('assets/plugins/chartjs/Chart.min.js')}}"></script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
<script>
  $(function () {
    
    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var areaChart = new Chart(areaChartCanvas);

    var paymentsData = {
      labels: ["January", "February", "March", "April", "May", "June", "July","August","November","Octuber","December"],
      datasets: [
        {
          label: "2020",
          fillColor: "rgba(210, 214, 222, 1)",
          strokeColor: "rgba(210, 214, 222, 1)",
          pointColor: "rgba(210, 214, 222, 1)",
          pointStrokeColor: "#c1c7d1",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(220,220,220,1)",
          data: [200, 100, 300, 150, 0, 500, 200,300,400,500,200]
        },
        {
          label: "2019",
          fillColor: "rgba(60,141,188,0.9)",
          strokeColor: "rgba(60,141,188,0.8)",
          pointColor: "#3b8bba",
          pointStrokeColor: "rgba(60,141,188,1)",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(60,141,188,1)",
          data: [100, 200, 500, 350, 100, 400, 100,100,200,100,800]
        }
      ]
    };
    var monthly_paymentsData = {
      labels: ["January", "February", "March", "April", "May", "June", "July","August","November","Octuber","December"],
      datasets: [
        {
          label: "2020",
          fillColor: "rgba(210, 214, 222, 1)",
          strokeColor: "rgba(210, 214, 222, 1)",
          pointColor: "rgba(210, 214, 222, 1)",
          pointStrokeColor: "#c1c7d1",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(220,220,220,1)",
          data: [200, 100, 300, 150, 0, 500, 200,300,400,500,200]
        },
        {
          label: "2019",
          fillColor: "rgba(60,141,188,0.9)",
          strokeColor: "rgba(60,141,188,0.8)",
          pointColor: "#3b8bba",
          pointStrokeColor: "rgba(60,141,188,1)",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(60,141,188,1)",
          data: [100, 200, 500, 350, 100, 400, 100,100,200,100,800]
        }
      ]
    };
    var total_paymentsData = {
      labels: ["January", "February", "March", "April", "May", "June", "July","August","November","Octuber","December"],
      datasets: [
        {
          label: "2020",
          fillColor: "rgba(210, 214, 222, 1)",
          strokeColor: "rgba(210, 214, 222, 1)",
          pointColor: "rgba(210, 214, 222, 1)",
          pointStrokeColor: "#c1c7d1",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(220,220,220,1)",
          data: [200, 100, 300, 150, 0, 500, 200,300,400,500,200]
        },
        {
          label: "2019",
          fillColor: "rgba(60,141,188,0.9)",
          strokeColor: "rgba(60,141,188,0.8)",
          pointColor: "#3b8bba",
          pointStrokeColor: "rgba(60,141,188,1)",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(60,141,188,1)",
          data: [100, 200, 500, 350, 100, 400, 100,100,200,100,800]
        }
      ]
    };

    var ChartOptions = {
      //Boolean - If we should show the scale at all
      showScale: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: false,
      //String - Colour of the grid lines
      scaleGridLineColor: "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - Whether the line is curved between points
      bezierCurve: true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension: 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot: false,
      //Number - Radius of each point dot in pixels
      pointDotRadius: 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth: 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius: 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke: true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth: 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill: true,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true
    };

    //Create the line chart
    areaChart.Line(paymentsData, ChartOptions);

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
    var lineChart = new Chart(lineChartCanvas);
    var lineChartOptions = ChartOptions;
    lineChartOptions.datasetFill = false;
    lineChart.Line(total_paymentsData, lineChartOptions);

    //-------------
    //- PIE CHART -
    //-------------

  var data_points =  [
			{ y: 2000, name: "Hilton" },
			{ y: 1500, name: "Lion" },
			{ y: 500, name: "Serkeji" },
			{ y: 100, name: "Tourism co" },
		]; 
  var options = {
	exportEnabled: true,
	animationEnabled: true,
	legend:{
		horizontalAlign: "right",
		verticalAlign: "center"
	},
	data: [{
		type: "pie",
		showInLegend: true,
		toolTipContent: "<b>{name}</b>: ${y} (#percent%)",
		indexLabel: "{name}",
		legendText: "{name} (#percent%)",
		indexLabelPlacement: "inside",
		dataPoints: data_points
	}]
};
$("#chartContainer").CanvasJSChart(options);


    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    var barChart = new Chart(barChartCanvas);
    var barChartData = monthly_paymentsData;
    barChartData.datasets[1].fillColor = "#00a65a";
    barChartData.datasets[1].strokeColor = "#00a65a";
    barChartData.datasets[1].pointColor = "#00a65a";
    var barChartOptions = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: true,
      //String - Colour of the grid lines
      scaleGridLineColor: "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - If there is a stroke on each bar
      barShowStroke: true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth: 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing: 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing: 1,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to make the chart responsive
      responsive: true,
      maintainAspectRatio: true
    };

    barChartOptions.datasetFill = false;
    barChart.Bar(monthly_paymentsData, barChartOptions);
  });
</script>
@endsection       