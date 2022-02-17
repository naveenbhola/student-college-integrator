<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td colspan="2" style="padding-left:30px;font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#231E19;">
			<p><strong>Dear <?php echo $userName; ?></strong></p>
			<p>We have found <?php echo $noOfSubscriptions; ?> important dates awaiting your attention in the coming week.</p>
			<table cellpadding="0" cellspacing="0" border="0" width="500" style="font-size:12px;font-family:Arial">
			<?php 
			foreach($eventDetailsArray as $temp){
                                        $event_id=$temp['event_id'];
                                        $titleEvent=$temp['event_title'];
                                        $startEvent=$temp['start_date'];
                                        $endEvent=$temp['end_date'];
					$city_name=$temp['city_name'];
					$country_name=$temp['country_name'];
                                        ?>
                                                 <?php                          if(date("jS M,y",strtotime($startEvent))!=date("jS M,y",strtotime($endEvent))){
                                                                                $currentDate=date("Y-m-d");
                                                                                if($startEvent>=$currentDate){ ?>
                                                                        <tr>				
										<td valign="top" style="font-size:10px">starts on</td>
										<td valign="top" style="padding:0 10px" width="440"></td>
									</tr>
									<tr>				
										<td style="width:39px" valign="top">
<div style="height:37px;overflow:hidden">
<table cellpadding="0" cellspacing="0" border="0">
<tr><td background="https://www.shiksha.com/public/images/sdtBg.gif" width="39">
<div align="center" style="color:#fff;font-size:11px;font-weight:700;padding-top:4px;line-height:15px"><?php echo date("M",strtotime($startEvent));?></div>
<div align="center" style="font-size:11px;font-weight:700;line-height:16px"><?php echo date("j",strtotime($startEvent));?></div>
</td></tr>
</table>
</div>
										</td>
                                                                                <?php }else{  ?>
                                                                        <tr>				
										<td valign="top" style="font-size:10px">Upto</td>
										<td valign="top" style="padding:0 10px" width="440"></td>
									</tr>
									<tr>
                                                                                <td style="width:39px" valign="top">
<div style="height:37px;overflow:hidden">
<table cellpadding="0" cellspacing="0" border="0">													
<tr><td background="https://www.shiksha.com/public/images/sdtBg.gif" width="39">
<div align="center" style="color:#fff;font-size:11px;font-weight:700;padding-top:4px;line-height:15px"><?php echo date("M",strtotime($endEvent));?></div>
<div align="center" style="font-size:11px;font-weight:700;line-height:16px"><?php echo date("j",strtotime($endEvent));?></div>
</td></tr>
</table>
</div>
										</td>
                                                                                <?php }
                                                                                }else{
                                                                                ?>
										<tr>
                                                                                <td style="width:39px" valign="top">
<div style="height:37px;overflow:hidden">
<table cellpadding="0" cellspacing="0" border="0">
<tr><td background="https://www.shiksha.com/public/images/sdtBg.gif" width="39">
<div align="center" style="color:#fff;font-size:11px;font-weight:700;padding-top:4px;line-height:15px"><?php echo date("M",strtotime($startEvent));?></div>
<div align="center" style="font-size:11px;font-weight:700;line-height:16px"><?php echo date("j",strtotime($startEvent));?></div>
</td></tr>
</table>
</div>
										</td>
                                                                        <?php } ?>
										<td valign="top" style="padding:0 10px" width="440">
						<div><?php echo $titleEvent; ?></div>
						<div><?php echo $city_name; ?> , <?php echo $country_name; ?></div>
					</td>
				</tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                        <?php   } ?></table>
			<p><strong>Best regards<br />Shiksha</strong></p>
		</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>  
</table>
