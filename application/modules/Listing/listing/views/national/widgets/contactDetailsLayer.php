<?php
	$widgetName = "Top_new";
?>

	<div class="layer-head">
		<a href="#" class="flRt close" onclick="hideListingsOverlay(); return false;" title="Close"></a>
		<div class="layer-title-txt">Get Contact Details on Email/SMS</div>
	</div>
	<div class="confirm-msg clear-width">
		<i class="sprite-bg confirm-icon"></i>
		<p>Institute contact details have been sent to you on your mobile &amp; email.</p>
	</div>
<?php 	
	$flag = 0;
	$locations = $course->getLocations();
	$location = $locations[$currentLocation->getLocationId()];
	$contactDetail = $location->getContactDetail();
	if(!($contactDetail->getContactNumbers()))
	{
		$locations = $institute->getLocations();
		$location = $locations[$currentLocation->getLocationId()];
		$contactDetail = $location->getContactDetail();
	}
?>
<p style="margin-bottom:10px; font-weight:bold">Course Contact Details:</p>
<?php if($contactDetail)
{ ?>
		<div id="contact-box" style="margin:0">
	<ul class="clear-width">
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
			$numbers = explode(", ", $contactDetail->getContactNumbers());
			$number_html_array = "";
			$size = sizeof($numbers);
			$count = 0;
			foreach ($numbers as $number)
			{
				if($count == 1 && $size > 2)
					$number_html_array[] = '<input type="checkbox" name="contact_numbers_'.$widgetName.'" value="'.html_escape($number).'">'.html_escape($number).'</br>';			
				else
					$number_html_array[] = '<input type="checkbox" name="contact_numbers_'.$widgetName.'" value="'.html_escape($number).'">'.html_escape($number);	
				$count++;
			} ?>
			<li>
				<form id="<?php echo $widgetName."_second";?>">
					<i class="sprite-bg contact-icon flLt"></i>
					<p>
					<strong class="flLt">Contact No.: </strong>	
					<span class="flLt" id="numbers" style="display:block; width:375px">
						<span><?=$contactDetail->getContactNumbers()?></span>
							<a href="javascript:void(0);" uniqueattr="LISTING_INSTITUTE_PAGES/REPORT_INVALID_UPDATED" class="manage-contact-btn orange-clr" onclick="$j('#numbers').hide(); $j('#reportIncorrect').show(); return false;">Report Incorrect Number</a>
					</span>
					
					<span class="flLt" id="reportIncorrect" style="display:none; width:375px">
						<span><?=implode(" ", $number_html_array)?></span>
						<br />
						<span>Select the number(s) to report as incorrect
							<a href="javascript:void(0);" uniqueattr="LISTING_INSTITUTE_PAGES/SUBMIT_REPORT_INVALID_UPDATED" class="manage-contact-btn" style="margin-left:3px; padding:1px 8px" onclick="reportInvalidNumbers('<?php echo $course->getId()?>','course','<?php echo $widgetName."_second";?>'); return false;">Submit</a>
						</span>
					</span>
					</p>
				</form>
			</li>
			<li id="showContactFax">
				<?php if($contactDetail->getContactFax())
				{ ?>
				<i class="sprite-bg fax-icon flLt"></i>
				<p>
					<strong>Fax No.:</strong> <?=$contactDetail->getContactFax()?>
				</p>
				<?php } ?>
			</li>
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
	</div>
<?php } ?>
<div class="clearFix"></div>
