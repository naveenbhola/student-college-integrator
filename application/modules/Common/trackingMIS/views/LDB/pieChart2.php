<script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("fusioncharts","nationalMIS"); ?>"></script>



<script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("fusioncharts.widgets","nationalMIS"); ?>"></script>

<div class="col-md-4 col-sm-4 col-xs-12">
    <div class="x_panel tile fixed_height_320 overflow_hidden" id="pieChartTwo">
        <div class="x_title">
            <h2>Actionwies Usage</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                    </ul>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <table class="" style="width:100%">
                <tr>
                    <th style="width:37%;">
                       
                    </th>
                    <th>
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                            <p class="">Action</p>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                            <p class="">Percentage</p>
                        </div>
                    </th>
                </tr>
                <tr>
                    <td>
                        <canvas id="canvas2" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
                    </td>
                    <td>
                        <table class="tile_info1">
                       
                            
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>


<!--
<?php
    $colorArray = array("blue","green", "purple" , "aero");
    $i = 0; 
    //_p($totalResponses);
    foreach ($responses['totalResponses'] as $key => $value) {
        $doughnutData[$i]['value'] = $value;
        $doughnutData[$i]['label'] = $key;
        $doughnutData[$i]['color'] = $colorArray[$i];
        $i++;
    }
    
?>
-->
<script type="text/javascript">
    
//var colorArray = ["#F0F8FF", "#FAEBD7" , "#000000" , "#0000FF" , "#8A2BE2" , "#A52A2A" , "#DEB887"];
         /*var doughnutData = <?php echo json_encode($doughnutData); ?>
        


        var myDoughnut = new Chart(document.getElementById("canvas2").getContext("2d")).Doughnut(doughnutData);*/

</script>

