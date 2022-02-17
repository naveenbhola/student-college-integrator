<html>
<head>
<script type="text/javascript" src="<?php echo SHIKSHA_HOME; ?>/public/mobile5/js/ampAlumniChart.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
     google.charts.load('current', {packages: ['corechart']});     
</script>
<script type="text/javascript">
<?php //_p($chartData);die; ?>
var data = '<?php echo $chartData;?>';
var chart_data = eval('(' + data +')');
var _formplaceholderSupport = false;
document.onreadystatechange = function () {
    if (document.readyState === "complete") {
        drawChart();
    }
}
</script>
<script>
  //  var selected_specialization = '<?php echo $naukriData['selected_naukri_splzn'];?>';
var max_value = 0;	
</script>


</head>
<body class="iframe-body">
<section>
<div id="naukri_widget_data"></div>
<div id="salary-data-chart"></div>
</section>
</body>