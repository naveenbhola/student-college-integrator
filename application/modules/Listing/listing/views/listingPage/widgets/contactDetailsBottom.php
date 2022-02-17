<?php
$locations = $institute->getLocations();
$location = $locations[$currentLocation->getLocationId()];
$title = "Contact Details of <span itemprop=\"name\">".html_escape($institute->getName())."</span>, ";
if($location->getLocality() && $location->getLocality()->getName()){
	$title .= $location->getLocality()->getName().", ";
}
$title .= $location->getCity()->getName();
$widgetName = 'bottom';
$flag = 0;
?>


<div style="position: relative" itemscope itemtype="http://data-vocabulary.org/Organization">
	<h2 class="section-title"><?=$title?></h2>
	<div class="confirm-msg" id="message" style="display:none; margin:20px 0 15px "><i class="icon-tick"></i> <p>Institute contact details have been sent to you on your mobile & email.</p></div>
	
	<?php if($course)
	{
		$listingType = "Bottom_".$pageType;
		$locations = $course->getLocations();
		$location = $locations[$currentLocation->getLocationId()];
		if($contactDetail = $location->getContactDetail())
		{ ?>
			<ul>
				<li>
					<strong>For Course: </strong><span><?=html_escape($course->getName())?></span>
				</li>
				<?php if(!($contactDetail->getContactNumbers()))
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
						<?php if($buttons)
						{ ?>
							<strong>Contact No.: </strong>
							<a id="Button_2" uniqueattr="contactInstitute<?=$widgetName?>" onclick="" href="#" style="text-decoration:none">
								<button class="orange-button" style="font-size:11px !important;height:23px !important" onclick="showResponseForm('responseFormBottomNew','<?=$listingType?>','<?=$typeId?>', 'listingPageBottomNew'); activatecustomplaceholder(); return false;">Send Contact Details to Email/SMS</Button>
							</a>
							<span id="showContactNumber_course" style="display:none">
								<span itemprop="tel"><?=$contactDetail->getContactNumbers()?></span>
								<button uniqueattr="listing_report_contact_button_clicked" class="orange-button" style="font-size:11px !important;height:23px !important;<?php if(count($numbers)>1):?>margin-top:5px;margin-bottom:5px;<?php endif;?>" onclick="$j(this).parent().hide(); $j(this).parent().next().show(); return false;">Report Incorrect Number</Button>
								<br/>
								<?php if($contactDetail->getContactFax())
								{ ?>
									<strong>Fax No.:</strong> <?=$contactDetail->getContactFax()?>
								<?php } ?>
							</span>
							
							<span style="display:none">
								<span itemprop="tel"><?=implode(" ", $number_html_array)?></span>
								<br/>
								<span style="position: relative; left:87px;" id="span_<?php echo $widgetName."_second";?>">
									<span>Select the number(s) to report as incorrect</span>
									<span>
										<button uniqueattr="listing_report_contact_submitbutton_clicked" class="gray-button" style="font-size:11px !important;margin-left:3px;" onclick="reportInvalidNumbers('<?php echo $course->getId()?>','course','<?php echo $widgetName."_second";?>'); return false;">Submit</button>
									</span>
								</span>
								<br/>
								<span id="showContactFax_course" style="display:none">
									<?php if($contactDetail->getContactFax())
									{ ?>
										<strong>Fax No.:</strong><?=$contactDetail->getContactFax()?>
									<?php } ?>
								</span>
							</span>
						<?php }
						else
						{ ?>
							<span><?=$contactDetail->getContactNumbers()?>
								<?php if($contactDetail->getContactFax())
								{ ?>. <strong>Fax No.:</strong> <?=$contactDetail->getContactFax()?>
								<?php } ?>
							</span>
						<?php }	?>
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
				} 
				
				if($location->getAddress())
				{ ?>
					<li>
						<strong>Address: </strong><span><?=$location->getAddress()?></span>
					</li>
				<?php } ?>
			</ul>
		<?php }
	} ?>
	<div id="responseFormBottomNew" style="display: none;">	
	</div>
</div>