<!--START: 2nd Layer-->
<?php
$widgetName = "Top_new";
?>

<div class="contact-layer">
	<div class="layer-head">
        <p class="contact-title flLt">Get Contact Details on Email/SMS</p>
        <span class="icon-close flRt" onclick="hideOverlayAnA();" title="Close"></span>
        
        <div class="confirm-msg"><i class="icon-tick"></i> <p>Institute contact details have been sent to you on your mobile & email.</p></div>
    </div>
<?php 	
	$flag = 0;
	$locations = $course->getLocations();
	$location = $locations[$currentLocation->getLocationId()];
?>
	
	<p class="inst-title">Course Contact Details:</p>
	
	<?php if($contactDetail = $location->getContactDetail())
	{?>
		<ul class="contact-form-details">
			<?php
			if(!($contactDetail->getContactNumbers()))
			{
				$locations = $institute->getLocations();
				$location = $locations[$currentLocation->getLocationId()];
				$contactDetail = $location->getContactDetail();
			}
			if($contactDetail->getContactPerson())
			{ ?>
				<li>
					<strong>Name of the Person: </strong><span><?=$contactDetail->getContactPerson()?></span>
				</li>
				<?php
			} ?>
			
			<?php if($contactDetail->getContactNumbers())
			{
				$numbers = explode(",", $contactDetail->getContactNumbers());
				$number_html_array = "";
				foreach ($numbers as $number)
				{
					$number_html_array[]=  $number_html .'<input type="checkbox" name="contact_numbers_'.$widgetName.'" value="'.html_escape($number).'">'.html_escape($number);
				} ?>
				<li>
					<form id="<?php echo $widgetName."_second";?>">
						<strong>Contact No.: </strong>
						<span>
							<span itemprop="tel"><?=$contactDetail->getContactNumbers()?></span>
							<p class="report-num">
								<button uniqueattr="listing_report_contact_button_clicked" class="orange-button" style="font-size:11px !important;height:23px !important;<?php if(count($numbers)>1):?>margin-top:5px;margin-bottom:5px;<?php endif;?>" onclick="$j(this).parent().parent().hide(); $j(this).parent().parent().next().show(); return false;">Report Incorrect Number</Button>
							</p>
							<?php if($contactDetail->getContactFax())
							{ ?>
								<strong>Fax No.:</strong><?=$contactDetail->getContactFax()?>
							<?php } ?>
						</span>
						<span style="display:none">
							<span itemprop="tel"><?=implode(" ", $number_html_array)?></span>
							</br>
							<span style="position: relative; left:79px;" id="span_<?php echo $widgetName."_second";?>">
								<span>Select the number(s) to report as incorrect</span>
								<span>
									<button uniqueattr="listing_report_contact_submitbutton_clicked" class="gray-button" style="font-size:11px !important;margin-left:3px;" onclick="reportInvalidNumbers('<?php echo $course->getId()?>','course','<?php echo $widgetName."_second";?>'); return false;">Submit</button>
								</span>
							</span>
							</br>
							<span id="showContactFax" style="display:none">
								<?php if($contactDetail->getContactFax())
								{ ?>
									<strong>Fax No.:</strong> <?=$contactDetail->getContactFax()?>
								<?php } ?>
							</span>
						</span>
						</br>
					</form>
				</li>
			<?php
			}
			
			if($contactDetail->getContactEmail())
			{ ?>
				<li>
					<strong>Email: </strong><span><?=$contactDetail->getContactEmail()?></span>
				</li>
				<?php
			} ?>
			
			<?php if($contactDetail->getContactWebsite())
			{ ?>
				<li>
					<strong>Website: </strong><span><?=$contactDetail->getContactWebsite()?></span>
				</li>
				<?php
			} ?>
			
			<?php if($location->getAddress())
			{ ?>
				<li>
					<strong>Address: </strong><span><?=$location->getAddress()?></span>
				</li>
			<?php } ?>
		</ul>
		<div class="clearFix"></div>
	<?php } ?>
</div>
