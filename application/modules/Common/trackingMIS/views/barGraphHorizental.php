<div class="<?php echo $class?>"  id="BGraph<?php echo $number;?>" >
    <div class="x_panel tile " style="padding-right:5px !important">
        <div class="x_title " >
            <div  class= "pieHeadingSmallSA" style="width: 100%">
            	<table style="width: 100%;">
	            			<tr>
	            				<td colspan=2 heading="<?php echo $heading."-".$misMetric;?>" ><?php echo $heading;?>  <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/help.png" style="width:25px;height:25px" class="help"></td>
	            				<td style="text-align: left; width: 70px"> </td>
	            				<td  class="w_right showGrowth" style="text-align: left;width:10%;display:none;font-size:12px">MOM</td>
	            				<td  class="w_right showGrowth" style="text-align: left;width:10%;display:none;font-size:12px">YOY</td>
	            			</tr>
            			</table>


            </div>
            <div class="clearfix"></div>
        </div>
        <div class="loader_small_overlay" id="barGraphHorizental_<?php echo $number;?>"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
        <div class="x_content overflow_BR" id="barGraph<?php echo $number;?>" style="height: 320px;padding-right:0px !important;padding-left:0px !important">
        </div>
    </div>
</div>