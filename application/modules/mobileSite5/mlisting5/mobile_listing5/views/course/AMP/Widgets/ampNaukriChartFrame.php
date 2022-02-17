<html>
<head>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="<?php echo SHIKSHA_HOME; ?>/public/mobile5/js/ampNaukriChart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js" ></script>

<?php
   $this->load->view('mobile_listing5/course/AMP/css/iframeSpecCSS'); ?>
</head>
<body class="iframe-body">
<?php 
if(!empty($placementData['min'])){ 
?>
<script>var minSalary = <?php echo $placementData['min'];?>
</script>
<?php
}
?>
<?php
if(!empty($placementData['median'])){
?>
<script>var medianSalary = <?php echo $placementData['median'];?></script>
<?php
}
?>
<?php
if(!empty($placementData['avg'])){
?>
<script>var avgSalary = <?php echo $placementData['avg'];?></script>
<?php
}
?>
<?php
if(!empty($placementData['max'])){
?>
<script>var maxSalary = <?php echo $placementData['max'];?></script>
<?php
}
?>
<div id="placementGraphDiv">
<canvas id="placementsGraph"></canvas>
</div>
<script type="text/javascript">
document.onreadystatechange = function () {
    if (document.readyState === "complete") {
        createPlacementChart();
    }
}

</script>
</body>