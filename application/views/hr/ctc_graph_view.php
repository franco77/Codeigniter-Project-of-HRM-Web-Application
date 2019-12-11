<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<!--<script src="<?=  base_url()?>assets/dist/frontend/amcharts.js" type="text/javascript"></script>
  <script src="<?=  base_url()?>assets/dist/frontend/serial.js" type="text/javascript"></script>-->
  
<!-- Resources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script>
    AmCharts.loadJSON = function(url) {
      if (window.XMLHttpRequest) {
        var request = new XMLHttpRequest();
      } else {
        var request = new ActiveXObject('Microsoft.XMLHTTP');
      }
	  request.open('GET', url, false);
      request.send();

      return eval(request.responseText);
    };
  </script>
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9"> 
					<legend class="pkheader">Generate Graph for Employee</legend> 
					<div class="row well"> 
						<div> <h4><?php echo $empInfo[0]['full_name'].' ( '.$empInfo[0]['loginhandle'] .' )'; ?></h4></div>
						<div class="row pkdsearch" style="max-width:790px">
							 <div id="chartdiv" style="width: 100%; height: 300px;"></div>
						</div>
					
					
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>
<script>
var chart;
AmCharts.ready(function() {
 var loginID = '<?php echo $_GET['id']; ?>';
 var base_url = '<?=  base_url(); ?>';
  var chartData = AmCharts.loadJSON(base_url+'en/hr/graphdata?id='+loginID);
console.log(chartData);
  var chart = AmCharts.makeChart( "chartdiv", {
		  "type": "serial",
		  "theme": "light",
		  "dataProvider": chartData,
		  "valueAxes": [ {
			"gridColor": "#FFFFFF",
			"gridAlpha": 0.2,
			"dashLength": 0
		  } ],
		  "gridAboveGraphs": true,
		  "startDuration": 1,
		  "graphs": [ {
			"balloonText": "FY: <b>[[category]]</b> <br/> Salary: <b>[[value]]</b> <br/> Percentage: <b>[[percentage]]%</b>  <br/> Cumulative: <b>[[cumulative]]%</b> <br/> Desg: <b>[[designation]]</b>",
			"fillAlphas": 0.8,
			"lineAlpha": 0.2,
			"type": "column",
			"valueField": "visits"
		  } ],
		  "chartCursor": {
			"categoryBalloonEnabled": false,
			"cursorAlpha": 0,
			"zoomable": false
		  },
		  "categoryField": "country",
		  "categoryAxis": {
			"gridPosition": "start",
			"gridAlpha": 0,
			"tickPosition": "start",
			"tickLength": 20
		  },
		  "export": {
			"enabled": true
		  }

		} );
});

  </script>