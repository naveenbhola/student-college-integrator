<?php 
	$grayCheck = "grayCheck.gif"; 
	$greenCheck = "greenCheck.gif";
	if(isset($productAlerts) && (count($productAlerts) > 0)): $i = 0;
						foreach($productAlerts as $temp): $i++; ?>
								<div class="lineSpace_20">
									<span class="float_L lineSpace_28 disBlock w25_per"><a href="#" title="<?php if($noOfAlerts >= $i) echo $temp['alert_name']; ?>"><?php if($noOfAlerts >= $i) echo $temp['alert_name']; ?></a></span>
									<span class="float_L disBlock w15_per">
										<div class="buttr3" style="padding-left:20px">
							<?php if(isset($temp['STATE']) && (strcmp($temp['STATE'],'on') != 0)): ?>		
								<button class="btn-submit17 w1" value="" type="button">
									<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
								</button>
							<?php else: ?>
								<button class="btn-submit15 w101">
									<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
								</button>	
								<?php endif; ?>
										</div>
										<div class="clear_L"></div>
									</span>
									<span class="float_L disBlock w30 mar_left_15p"><?php isset($temp['MAIL']) && ($temp['MAIL'] == true)?$mail = $greenCheck:$mail = $grayCheck; ?><img src="/public/images/<?php echo $mail; ?>" width="36" height="25" style="padding-left:10px" /><?php isset($temp['IM']) && ($temp['IM'] == true)?$im = $greenCheck:$im = $grayCheck; ?><img src="/public/images/<?php echo $im; ?>" width="36" height="25" /><?php isset($temp['SMS']) && ($temp['SMS'] == true)?$sms = $greenCheck:$sms = $grayCheck; ?><img src="/public/images/<?php echo $sms; ?>" width="36" height="25" /></span>
									<span class="float_L disBlock w15_per"><img src="/public/images/editPencil.gif" width="27" height="25" /></span>
									<span class="float_L disBlock w10_per"><?php $urlForDelete = site_url('alerts/Alerts/deleteAlert').'/12/'.$temp['alert_id'].'/1/'.$userId; echo $this->ajax->link_to_remote("<img src=\"/public/images/deleteIcon.gif\" width=\"27\" height=\"25\" />",array('url' => $urlForDelete, 'update' => 'first')) ?></span>
									<div class="clear_L"></div>								
								</div>
					<?php endforeach;
						else: ?>
					<div class="float_L mar_top_4p" style="width:70%">
					<span class="grayFont" style="font-size:13px;">No alerts</span>
					</div>
					<?php endif; ?>	
