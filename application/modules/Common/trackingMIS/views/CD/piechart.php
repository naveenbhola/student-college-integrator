
<div class="col-md-6 col-sm-6 col-xs-12" id="<?php echo $key.$number.'-maindiv';?>">
    <div class="x_panel tile fixed_height_320 overflow_hidden">
        <div class="x_title">
            <!-- id="<?php echo $splitType.'-heading';?>" -->
            <h2 id="<?php echo $key.$number.'-heading';?>"><?php echo $value['heading'];?></h2>
            <div id="dateRange_<?php echo $key.$number;?>" class="dateSA"></div>
            <?php if($key == 'Traffic-SourceWise' || $key == 'Traffic-DeviceWise') {?>
            <div id="">
                <img class="helperText" id="<?php echo $key.$number.'-help';?>" data-toggle="tooltip" src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/help.png" width="20px" style="padding-right:0px" title="<?php echo $helperText;?>"  data-placement="bottom"/>
                <!-- <p id="<?php echo $key.$number.'-msg';?>" style="visibility:hidden;opacity:1;">Here Traffic means Direct Landing on this Page</p> -->
            </div>
            <?php } ?>
           <!--  <ul class="nav navbar-right panel_toolbox">
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
            </ul> -->
            <div class="clearfix"></div>
            <div class="loader_small_overlay cdLoader" style="" id="<?php echo $key.$number;?>-loader">
                                                 <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif">
                                            </div>
        </div>

        <div class="x_content">

            <table class="" style="width:100%">
                <tr>
                   
                    <th style="width:37%;">
                        <!-- <p class = "" id="<?php echo $key.$number.'-count';?>"></p> -->
                        <p>Split View</p>
                    </th>
                    <th>
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                            <p class=""><?php echo $value['typeHeading'];?></p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <p class="">%</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <p class="">Count</p>
                        </div>
                    </th>
                </tr>
                <tr>
                    <td id="<?php echo $key.$number.'-div';?>">
                        <!-- <div > -->
                        <canvas id="<?php echo $key.$number.'-graph';?>" class ="pieChart-Graph" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
                        <!-- </div> -->
                    </td>
                    <td>
                        <div style="height: 160px;" class="overflow_y">
                        <table class="tile_info pieChart-Info" id="<?php echo $key.$number.'-info';?>">
                            
                        </table>
                    </div>
                    </td>
                </tr>
                <tr>
                    <td>
                      </td>
                      <td>  
                        <div>
                        <table style="width: 100%; text-align: right;">
                            <tbody>
                                <tr>
                                        <td id="">
                                    <h4 class="totalDataCount" id="<?php echo $key.$number.'-count';?>"></h4>
                                </td>
                            </tr>
                        </tbody>
                </table>
            </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>


<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>