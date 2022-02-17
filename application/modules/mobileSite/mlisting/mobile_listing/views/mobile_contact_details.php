<?php 

		$locations = $course->getLocations();
		$location = $locations[$currentLocation->getLocationId()];
		if($location){
			$contactDetail = $location->getContactDetail();
		}
		if(!$contactDetail){
		    $locations = $institute->getLocations();
	    	$location = $locations[$currentLocation->getLocationId()];
		    $contactDetail = $location->getContactDetail();
	    }
	if($contactDetail){ ?>
	<div class="round-box" >View Contact Details
	<?php	if($contactDetail->getContactPerson()){ ?>
			<p><strong>Name of the Person:</strong> <?php echo $contactDetail->getContactPerson();?></p>
		<?php
		}
		if($contactDetail->getContactNumbers()){ ?>
			<?php $contactNumbers = explode(',',$contactDetail->getContactNumbers());
			?>
			<p id="listing_contact_widget"><strong>Contact No.:</strong><?php $count=count($contactNumbers);
													$i=0;
													foreach($contactNumbers as $no){
														$i++;?>
														<a href="tel:<?=$no;?>"> 
															<?php echo $no; 
															if($i!=$count)echo ",";?> 
															<?php }?>
														 </a>
 
	<?php }
	$email = $contactDetail->getContactEmail();
	$website = $contactDetail->getContactWebsite();
	$address = $currentLocation->getAddress();
	if(!empty($email)){?><p><strong>Email:</strong> <?php echo $email;?></p><?php }
    if(!empty($website)){?><p><strong>Website:</strong> <?php echo $website;?></p><?php }
	if($address){ 
		$city= $currentLocation->getCity()->getName()!=''? $currentLocation->getCity()->getName():"";
		$state=$currentLocation->getState()->getName()!=''?$currentLocation->getState()->getName():"";
		$country=$currentLocation->getCountry()->getName()!=''?$currentLocation->getCountry()->getName():"";
		$geoParams1=$institute->getName()." ".$currentLocation->getCity()->getName()." ".$currentLocation->getCountry()->getName();			
		$geoParams1=urlencode($geoParams1);
	?>
		<p><strong>Address:</strong><a href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=<?= $geoParams1;?>"><?php echo $address;?></a></p>
	
	<?php } ?> 
	</div> 
	<?php } 
	
?>

<script>
$(document).ready(function(){
    $("#listing_contact_widget").click(function(){
    	<?php
    	if($institute_id)
    	{
    		?>
    		var listing_id  = "<?php echo $institute_id; ?>";
    		<?php
    	}
    	else
    	{
    		?>
    		var listing_id  = "<?php echo $course_id; ?>";
    		<?php
    	}
    	?>
        _gaq.push(['_trackEvent', 'click_on_contact_listing_detail', 'click', listing_id]);
    });
});

</script>
