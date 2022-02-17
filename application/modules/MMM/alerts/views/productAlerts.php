<?php 
	$alertsHomeUrl = site_url('alerts/Alerts/alertsHome').'/12';
	$grayCheck = "grayCheck.gif"; 
	$greenCheck = "greenCheck.gif";
	$i = 0;
	if(isset($productAlerts) && (count($productAlerts) > 0)): $i = 0;
						foreach($productAlerts as $record): $i++; if($noOfAlerts < $i) break;  
			$alertName = escapeString($record['alert_name']); 
			$alertName = str_replace('courseAndCollege','courseAndInstitute',$alertName);	
			?>
								<div class="lineSpace_20">
			<span class="float_L lineSpace_28 disBlock W68_per">
			<a href="javascript:void(0);" onClick="javascript:updateOverlay('<?php echo $productName; ?>',<?php echo $formNumber; ?>,'<?php echo $record['alert_id'];  ?>','<?php echo $alertName;  ?>','<?php echo $productId; ?>')" title="<?php echo $alertName; ?>" style="text-transform:capitalize;"><?php if(strlen($alertName)>32) { echo substr($alertName,0,32)."...";} else { echo $alertName; } ?>&nbsp;<img src="/public/images/editPencil.gif" border="0" /></a>
		 <?php if(isset($record['SMS']) && (strcmp($record['SMS'],'on')==0)): ?><img src="/public/images/mobileicon.jpg" /> <?php endif; ?>	
			</span>
									<span class="float_L disBlock w20_per">
										<div class="buttr3" style="padding-left:20px" id="status<?php echo $record['alert_id']; ?>">
							<?php if(isset($record['STATE']) && (strcmp($record['STATE'],'on') != 0)): ?>		
								<button class="btn-submit17 w1" onClick="updateStatus(<?php echo $appId ?>,<?php echo $record['alert_id']; ?>,'on')">
									<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
								</button>
							<?php else: ?>
								<button class="btn-submit15 w101" style="padding-left:-5px" onClick="updateStatus(<?php echo $appId ?>,<?php echo $record['alert_id']; ?>,'off')">
									<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
								</button>	
								<?php endif; ?>
										</div>
										<div class="clear_L"></div>
									</span>
									<span class="float_L disBlock w10_per"><?php $urlForDelete = site_url('alerts/Alerts/deleteAlert').'/12/'.$record['alert_id'].'/'.$productId.'/'.$productName.'/'.$noOfAlerts;  echo $this->ajax->link_to_remote("<img src=\"/public/images/deleteIcon.gif\" width=\"27\" height=\"25\" border=\"0\"/>",array('url' => $urlForDelete, 'update' => $productContainer,'success' => 'javascript:updateAlertCount(\''.$productName.'\');','confirm' => 'Do you really want to delete this alert?')) ?></span>
									<div class="clear_L"></div>								
								</div>
					<?php endforeach; 
					      if(($noOfAlerts == 1) && ($i > 1)):	?>
						<div class="float_L mar_right_10p">
						<img src="/public/images/eventArrow.gif" width="6" height="6" /> 
						<a href="<?php echo $alertsHomeUrl; ?>#courseAndCollege">View all</a></div>
					<?php endif;
				  	      else: ?>
					<div class="float_L mar_top_4p" style="width:70%">
					<span class="grayFont" style="font-size:13px;">No alerts</span>
					</div>
					<?php endif; ?>	
