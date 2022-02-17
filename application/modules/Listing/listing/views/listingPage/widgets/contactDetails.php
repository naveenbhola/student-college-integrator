<?php
$locations = $institute->getLocations();
$location = $locations[$currentLocation->getLocationId()];
$title = "Contact Details of <span itemprop=\"name\">".html_escape($institute->getName())."</span>, ";
if($location->getLocality() && $location->getLocality()->getName()){
	$title .= $location->getLocality()->getName().", ";
}
$title .= $location->getCity()->getName();
$widgetName = "Top";

if($overlay == "no"){
	$widgetName = "Bottom";
?>
<div  itemscope itemtype="http://data-vocabulary.org/Organization">
	<h2 class="section-title"><?=$title?></h2>
<?php }else{
?>
<script>
function showContactDetails(obj){
    var content = $('contactDetails').innerHTML;
    overlayParentAnA = $('contactDetails');
    overlayParentAnA = ''	;
    showOverlayAnA(500,400,'<?=str_replace("'", "\'", $title)?>',content);
}
</script>
<a href="#" onclick="showContactDetails(); return false;"><span class="sprite-bg contact-icn"></span> View Contact Details</a>
<div id="contactDetails" style="display:none">
	<div class="contact-detail-cont" style="width:93%">
<?php
}

if($contactDetail = $location->getContactDetail()){
?>
<ul>
	<?php if($contactDetail->getContactPerson()){ ?>
	<li>
		<strong>Name of the Person: </strong>
		<span><?=$contactDetail->getContactPerson()?></span>
	</li>
	<?php } ?>
	<?php if($contactDetail->getContactNumbers()){ 
		  $numbers = explode(",", $contactDetail->getContactNumbers());
		  $number_html_array = "";
		  foreach ($numbers as $number) {
		  	$number_html_array[]=  $number_html .'<input type="checkbox" name="contact_numbers_'.$widgetName.'" value="'.html_escape($number).'">'.html_escape($number);
		  }
	?>
	<li>
		
		<?php
		if($buttons){
		?>
		<form id="<?php echo $widgetName."_first";?>" novalidate="" onsubmit="processContactCount('<?=$widgetName."_first"?>'); return false">
		<strong>Contact No.: </strong>	
		<input type="hidden" id="listing_id_first" value="<?=$institute->getId()?>">
		<input type="hidden" id="listing_type_first" value="institute">
		<a uniqueattr="contactInstitute<?=$widgetName?>" onclick="$j(this).hide(); $j(this).next().show(); return false;" href="#" style="text-decoration:none">
			<button class="orange-button" style="font-size:11px !important;height:23px !important" onclick="$j(this).parent().parent().trigger('submit'); return false;">Click here to View</Button>
		</a>
		<span style="display:none"><span itemprop="tel"><?=$contactDetail->getContactNumbers()?></span>
                <?php
                //if(count($numbers)>1) {echo "<br/>";} 
                ?>
		<button uniqueattr="listing_report_contact_button_clicked" class="orange-button" style="font-size:11px !important;height:23px !important;<?php if(count($numbers)>1):?>margin-top:5px;margin-bottom:5px;<?php endif;?>" onclick="$j(this).parent().hide(); $j(this).parent().next().show(); return false;">Report Incorrect Number</Button>
		<br/>
                <?php if($contactDetail->getContactFax()){ ?><strong>Fax No.:</strong> <?=$contactDetail->getContactFax()?><?php } ?>
		</span>
		<span style="display:none"><span itemprop="tel"><?=implode(" ", $number_html_array)?></span>
		<br/>
		<span style="position: relative;<?php if($widgetName =='Top'):?>left: 79px;<?php else:?>left:87px;<?php endif;?>" id="span_<?php echo $widgetName."_first";?>">
		<span>
		Select the number(s) to report as incorrect
		</span>
		<span>
		<button uniqueattr="listing_report_contact_submitbutton_clicked" class="gray-button" style="font-size:11px !important;margin-left:3px;" onclick="reportInvalidNumbers('<?php echo $institute->getId()?>','institute','<?php echo $widgetName."_first";?>'); return false;">Submit</button>
		</span>
		</span>
		</br>
		<span>
		<?php if($contactDetail->getContactFax()){ ?><strong>Fax No.:</strong> <?=$contactDetail->getContactFax()?><?php } ?>
                </span>
		</span>
		</form>
		<?php
		}else{
		?>
		<span><span itemprop="tel"><?=$contactDetail->getContactNumbers()?></span><?php if($contactDetail->getContactFax()){ ?>. <strong>Fax No.:</strong> <?=$contactDetail->getContactFax()?><?php } ?></span>
		<?php
		}
		?>
	</li>
	<?php } ?>
	<?php if($contactDetail->getContactEmail()){ ?>
	<li>
		<strong>Email: </strong>
		<span><?=$contactDetail->getContactEmail()?></span>
	</li>
	<?php } ?>
	<?php if($contactDetail->getContactWebsite()){ ?>
	<li>
		<strong>Website: </strong>
		<span itemprop="url"><?=$contactDetail->getContactWebsite()?></span>
	</li>
	<?php } ?>
	<?php if($location->getAddress()){ ?>
	<li>
		<strong>Address: </strong>
		<span itemprop="address"><?=$location->getAddress()?></span>
	</li>
	<?php } ?>

</ul>
<?php
}

if($course){
	$locations = $course->getLocations();
	$location = $locations[$currentLocation->getLocationId()];
	if($contactDetail = $location->getContactDetail()){
?>
<div class="gray-rule"></div>
<ul>
	<li>
		<strong>For Course: </strong>
		<span><?=html_escape($course->getName())?></span>
	</li>
	<?php if($contactDetail->getContactPerson()){ ?>
	<li>
		<strong>Name of the Person: </strong>
		<span><?=$contactDetail->getContactPerson()?></span>
	</li>
	<?php } ?>
	<?php if($contactDetail->getContactNumbers()){ 
		$numbers = explode(",", $contactDetail->getContactNumbers());
		  $number_html_array = "";
		  foreach ($numbers as $number) {
		  	$number_html_array[]=  $number_html .'<input type="checkbox" name="contact_numbers_'.$widgetName.'" value="'.html_escape($number).'">'.html_escape($number);
                  }
	?>
	<li>
		<?php
		if($buttons){
		?>
		<form id="<?php echo $widgetName."_second";?>" novalidate="" onsubmit="processContactCount('<?=$widgetName."_second"?>'); return false">
		<strong>Contact No.: </strong>	
		<input type="hidden" id="listing_id_second" value="<?=$course->getId()?>">
		<input type="hidden" id="listing_type_second" value="course">
		
		<a uniqueattr="contactCourse<?=$widgetName?>" onclick="$j(this).hide(); $j(this).next().show(); return false;" href="#" style="text-decoration:none">
			<button class="orange-button" style="font-size:11px !important;height:23px !important" onclick="$j(this).parent().parent().trigger('submit'); return false;">Click here to View</Button>
		</a>
		<span style="display:none"><?=$contactDetail->getContactNumbers()?>
                 <?php
			//if(count($numbers)>1) {echo "<br/>";}
                ?>
		<button uniqueattr="listing_report_contact_button_clicked" class="orange-button" style="font-size:11px !important;height:23px !important;<?php if(count($numbers)>1):?>margin-top:5px;margin-bottom:5px;<?php endif;?>" onclick="$j(this).parent().hide(); $j(this).parent().next().show(); return false;">Report Incorrect Number</Button>
		<br/>
		<?php if($contactDetail->getContactFax()){ ?><strong>Fax No.:</strong> <?=$contactDetail->getContactFax()?><?php } ?>
		</span>
		<span style="display:none"><span itemprop="tel"><?=implode(" ", $number_html_array)?></span>
		<br/>
		<span style="position: relative;<?php if($widgetName =='Top'):?>left: 79px;<?php else:?>left:87px;<?php endif;?>" id="span_<?php echo $widgetName."_second";?>">
		<span>
		Select the number(s) to report as incorrect
		</span>
		<span>
		<button uniqueattr="listing_report_contact_submitbutton_clicked" class="gray-button" style="font-size:11px !important;margin-left:3px;" onclick="reportInvalidNumbers('<?php echo $course->getId()?>','course','<?php echo $widgetName."_second";?>'); return false;">Submit</button>
		</span>
		</span>
		<br/>
		<span>
		<?php if($contactDetail->getContactFax()){ ?><strong>Fax No.:</strong> <?=$contactDetail->getContactFax()?><?php } ?>
		</span>
		</span>
		</form>
		<?php
		}else{
		?>
		<span><?=$contactDetail->getContactNumbers()?><?php if($contactDetail->getContactFax()){ ?>. <strong>Fax No.:</strong> <?=$contactDetail->getContactFax()?><?php } ?></span>
		<?php
		}
		?>
	</li>
	<?php } ?>
	<?php if($contactDetail->getContactEmail()){ ?>
	<li>
		<strong>Email: </strong>
		<span><?=$contactDetail->getContactEmail()?></span>
	</li>
	<?php } ?>
	<?php if($contactDetail->getContactWebsite()){ ?>
	<li>
		<strong>Website: </strong>
		<span><?=$contactDetail->getContactWebsite()?></span>
	</li>
	<?php } ?>
</ul>
<?php
	}
}
?>
<?php
if($overlay == "no"){
?>
</div>
<?php }else{
?>
	</div>
</div>
<?php
}
?>
