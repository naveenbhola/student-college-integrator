<?php
$locations = $institute->getLocations();
$location = $locations[$currentLocation->getLocationId()];
$title = $institute->getName().", ";
if($location->getLocality() && $location->getLocality()->getName()){
	$title .= $location->getLocality()->getName().", ";
}
$title .= $location->getCity()->getName();
$widgetName = "Top";
?>

<script>
function showContactDetails(obj){
	var content = $('contactDetailsNotUpdated').innerHTML;
	overlayParentListings = $('contactDetailsNotUpdated');
	overlayParentListings = ''	;
	showListingsOverlay(500,'auto','',content);
	if(typeof $('iframe_div') != "undefined")
	{
	    $j('#iframe_div').css({backgroundColor: "#000", opacity: "0.4" });
	}
}
</script>

<div id="contactDetailsNotUpdated" style="display:none">
	<div class="layer-head">
		<a href="#" class="flRt sprite-bg cross-icon" onclick="hideListingsOverlay();" title="Close"></a>
		<div class="layer-title-txt">Contact Details of <?=$title?></div>
	</div>
	
	<div id="contact-box" style="margin:0">
		<?php if($contactDetail = $location->getContactDetail())
		{ ?>
			<ul class="clear-width">
				<?php if($contactDetail->getContactPerson()){ ?>
				<li>
						<i class="sprite-bg person-icon flLt"></i>
						<p>
						<strong class="flLt">Name of the Person: </strong>
						<span class="flLt add-details" style="width:318px"><?=$contactDetail->getContactPerson()?></span>
						</p>
				</li>
				<?php } ?>
				
				<?php if($contactDetail->getContactNumbers())
				{ 
					$numbers = explode(",", $contactDetail->getContactNumbers());
					$number_html_array = "";
					foreach ($numbers as $number) {
					      $number_html_array[]=  $number_html .'<input type="checkbox" name="contact_numbers_'.$widgetName.'" value="'.html_escape($number).'">'.html_escape($number);
					} ?>
					<li>
						
						<form id="<?php echo $widgetName."_first";?>" novalidate="" onsubmit="processContactCount('<?=$widgetName."_first"?>'); return false">
						<i class="sprite-bg contact-icon flLt"></i>
							<p>
							<strong class="flLt">Contact No.: </strong>	
							<input type="hidden" id="listing_id_first" value="<?=$institute->getId()?>">
							<input type="hidden" id="listing_type_first" value="institute">
							<a href="javascript:void(0);" id="button_notUpdated" class="manage-contact-btn orange-clr" uniqueattr="LISTING_INSTITUTE/CONTACT_DETAILS_NOT_UPDATED" onclick="$j('<?="#".$widgetName."_first"?>').trigger('submit'); $j('#button_notUpdated').hide(); $j('#numbers_notUpdated').show(); $j('#fax').show(); return false;" href="#">
									Click here to View
							</a>
							
							<span id='numbers_notUpdated' class="flLt" style="display:none; width:375px">
								<span><?=$contactDetail->getContactNumbers()?></span>
								<a href="javascript:void(0);" class="manage-contact-btn orange-clr" style="margin-left:10px" uniqueattr="LISTING_INSTITUTE/REPORT_INVALID_NOT_UPDATED" onclick="$j('#numbers_notUpdated').hide(); $j('#numbers_checkbox').show(); return false;">Report Incorrect Number</a>
							</span>
							
							<span class="flLt" id="numbers_checkbox" style="display:none; width:375px">
								<span><?=implode(" ", $number_html_array)?></span>
								<br />
								<span id="span_<?php echo $widgetName."_first";?>">Select the number(s) to report as incorrect</span>
								<a href="javascript:void(0);" class="manage-contact-btn" style="margin-left:3px; padding:1px 8px" uniqueattr="LISTING_INSTITUTE/SUBMIT_REPORT_INVALID_NOT_UPDATED" onclick="reportInvalidNumbers('<?php echo $institute->getId()?>','institute','<?php echo $widgetName."_first";?>'); return false;">Submit</a>
							</span>
							</p>
							
							
						</form>
					</li>
					
					
					<?php if($contactDetail->getContactFax())
					{ ?>
						<li id="fax" style="display:none">
								<i class="sprite-bg fax-icon flLt"></i>
								<p>
							<strong>Fax No.:</strong>
							<?=$contactDetail->getContactFax()?>
							</p>
						</li>
					<?php } ?>
					
				<?php } ?>
				
				<?php if($contactDetail->getContactEmail())
				{ ?>
					<li>
						<i class="sprite-bg email-icon flLt"></i>
						<p>
						<strong class="flLt">Email: </strong>
						<span class="add-details" style="width:475px"><?=$contactDetail->getContactEmail()?></span>
						</p>
					</li>
				<?php } ?>
				
				<?php if($contactDetail->getContactWebsite())
				{ ?>
					<li>
						<i class="sprite-bg web-icon flLt" style="top:6px"></i>
						<p>
						<strong class="flLt">Website: </strong>
						<span class="add-details" style="width:475px"><?=$contactDetail->getContactWebsite()?></span>
						
						</p>
					</li>
				<?php } ?>
				
				<?php if($location->getAddress())
				{ ?>
					<li>
						<i class="sprite-bg loc-icon flLt"></i>
                        <p>		
						<strong class="flLt">Address: </strong>
						<span class="add-details flLt" style="width:400px"><?=$location->getAddress()?></span>
						</p>
					</li>	
				<?php } ?>
			
			</ul>
		<?php }
		
		if($course && $pageType != 'institute')
		{
			$locations = $course->getLocations();
			$location = $locations[$currentLocation->getLocationId()];
			if($contactDetail = $location->getContactDetail())
			{ ?>
				<div class="gray-divider clear-width"></div>
				<ul class="clear-width">
					<li>
						<i class="sprite-bg talk-icon flLt" style="top:4px"></i>
						<p>
						<strong class="flLt">For Course: </strong>
						<span class="add-details flLt" style="width:380px"><?=html_escape($course->getName())?></span>
						</p>
					</li>
					
					<?php if($contactDetail->getContactPerson())
					{ ?>
						<li>
						<i class="sprite-bg person-icon flLt"></i>
						<p>
						<strong class="flLt">Name of the Person: </strong>
						<span class="flLt add-details" style="width:318px"><?=$contactDetail->getContactPerson()?></span>
						</p>
						</li>
						
					<?php } ?>
					
					<?php if($contactDetail->getContactNumbers())
					{ 
						$numbers = explode(",", $contactDetail->getContactNumbers());
						$number_html_array = "";
						foreach ($numbers as $number) {
						      $number_html_array[]=  $number_html .'<input type="checkbox" name="contact_numbers_'.$widgetName.'" value="'.html_escape($number).'">'.html_escape($number);
						} ?>
						<li>
							<form id="<?php echo $widgetName."_second";?>" novalidate="" onsubmit="processContactCount('<?=$widgetName."_second"?>'); return false">
								<i class="sprite-bg contact-icon flLt"></i>
								<p>
								<strong class="flLt">Contact No.: </strong>	
								<input type="hidden" id="listing_id_second" value="<?=$course->getId()?>">
								<input type="hidden" id="listing_type_second" value="course">
								
									<a href="javascript:void(0);" id="button_notUpdatedBottom" class="manage-contact-btn orange-clr" uniqueattr="LISTING_COURSE/CONTACT_DETAILS_NOT_UPDATED" onclick="$j('<?="#".$widgetName."_second"?>').trigger('submit'); $j('#button_notUpdatedBottom').hide(); $j('#numbers_bottom').show(); return false;" href="#">
										Click here to View
									</a>
								
								<span class="flLt" id="numbers_bottom" style="display:none; width:375px">
									<span><?=$contactDetail->getContactNumbers()?></span>
									<a href="javascript:void(0);" class="manage-contact-btn orange-clr" style="margin-left:10px" uniqueattr="LISTING_COURSE/REPORT_INVALID_NOT_UPDATED" onclick="$j('#numbers_bottom').hide(); $j('#numbers_checkbox_bottom').show(); return false;">Report Incorrect Number</a>
								</span>
								
								<span class="flLt" id="numbers_checkbox_bottom" style="display:none; width:375px">
									<span><?=implode(" ", $number_html_array)?></span>
									<br />
									<span id="span_<?php echo $widgetName."_first";?>">Select the number(s) to report as incorrect</span>
										<a href="javascript:void(0);" class="manage-contact-btn" style="margin-left:3px; padding:1px 8px" uniqueattr="LISTING_COURSE/SUBMIT_REPORT_INVALID_NOT_UPDATED" onclick="reportInvalidNumbers('<?php echo $institute->getId()?>','institute','<?php echo $widgetName."_first";?>'); return false;">Submit</a>
								</span>
								</p>
							</form>
						</li>
						<?php if($contactDetail->getContactFax())
						{ ?>
							<li id="fax" style="display:none">
								<i class="sprite-bg fax-icon flLt"></i>
								<p>
								<strong>Fax No.:</strong>
								<?=$contactDetail->getContactFax()?>
								</p>
							</li>
						<?php } ?>
					<?php } ?>
					
					<?php if($contactDetail->getContactEmail())
					{ ?>
						<li>
						<i class="sprite-bg email-icon flLt"></i>
						<p>
						<strong class="flLt">Email: </strong>
						<span class="add-details" style="width:475px"><?=$contactDetail->getContactEmail()?></span>
						</p>
					</li>
						
						
					<?php } ?>
					
					<?php if($contactDetail->getContactWebsite())
					{ ?>
						<li>
						<i class="sprite-bg web-icon flLt" style="top:6px"></i>
						<p>
						<strong class="flLt">Website: </strong>
						<span class="add-details" style="width:475px"><?=$contactDetail->getContactWebsite()?></span>
						
						</p>
					</li>
						
						
					<?php } ?>
				</ul>
			<?php }
		} ?>
	</div>
</div>
