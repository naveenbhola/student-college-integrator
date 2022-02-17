<?php
if($updated == "true")
{	$listingType = "Top_".$listingType; ?>
	<a href="#" uniqueattr="ListingPage/showLCDForm" onclick="showResponseForm('responseFormNew', '<?=$listingType?>', '<?=$listingId?>', 'listingPageTopLinks'); activatecustomplaceholder(); return false;"><span class="sprite-bg sms-icn"></span>Send Contact Details to Email/SMS</a>
	<?php
}
else
{?>
	<a href="#" onclick="emailThisListing(); return false;"><span class="sprite-bg email-icn"></span> Email Contact Details</a>
	<a href="#" onclick="smsThisListing(); return false;"><span class="sprite-bg sms-icn"></span> SMS Contact Details</a>
	<?php
}
?>
<a href="#" onclick="saveInfo(); return false;" class="save-info"><span class="sprite-bg save-icn"></span> Save Info </a>
<div id="ajax-div" style="display:none">
</div>
<?php
if(!$course){
	$course = $institute->getFlagshipCourse();
}
?>
<script>
        var getmeCurrentCity = $('getmeCurrentCity').value;
        var getmeCurrentLocaLity = $('getmeCurrentLocaLity').value;
	function emailThisListing(){
		loadShortRegistationForm("Email Contact Details",
								 "Send Mail",
								 "Email with institute's contact details is sent.",
								 true,false,
		function(){
			<?php
			if($pageType == "course"){
			?>
				$j('#ajax-div').load('/alerts/Alerts/emailListing/<?=$institute->getId()?>/<?=$course->getId()?>/<?=$currentLocation->getLocationId()?>');
			<?php
			}else{
			?>
				$j('#ajax-div').load('/alerts/Alerts/emailListing/<?=$institute->getId()?>/0/<?=$currentLocation->getLocationId()?>');
			<?php
			}
			?>
			displayMessage('/common/loadOverlayContent/common-commonThankYou',400,100);
			<?php if($paid && $course->isPaid()) { ?>
					makeAutoResponse(loggedUserFirstName,loggedUserLastName,loggedUserMobile,loggedUserEMail,<?=$institute->getId()?>, '<?=html_escape($institute->getName())?>',<?=$course->getId()?>,"&flag_check=1","'"+getmeCurrentCity+"'","'"+getmeCurrentLocaLity+"'");
                //changeFloatingRegistrationOnResponse();
			<?php } ?>
		},"emailThisListing");
	}
	
	function smsThisListing(){
		loadShortRegistationForm("SMS Contact Details",
								 "Send SMS",
								 "SMS with institute's contact details is sent.",
								 true,false,
		function(){
			<?php
			if($pageType == "course"){
			?>
				$j('#ajax-div').load('/alerts/Alerts/smsListing/<?=$institute->getId()?>/<?=$course->getId()?>/<?=$currentLocation->getLocationId()?>');
				$j('#ajax-div').load('/alerts/Alerts/emailListing/<?=$institute->getId()?>/<?=$course->getId()?>/<?=$currentLocation->getLocationId()?>');
			<?php
			}else{
			?>
				$j('#ajax-div').load('/alerts/Alerts/smsListing/<?=$institute->getId()?>/0/<?=$currentLocation->getLocationId()?>');
				$j('#ajax-div').load('/alerts/Alerts/emailListing/<?=$institute->getId()?>/0/<?=$currentLocation->getLocationId()?>');
			<?php
			}
			?>
			displayMessage('/common/loadOverlayContent/common-commonThankYou',400,100);
			<?php if($paid && $course->isPaid()) { ?>
			makeAutoResponse(loggedUserFirstName,loggedUserLastName,loggedUserMobile,loggedUserEMail,<?=$institute->getId()?>, '<?=html_escape($institute->getName())?>',<?=$course->getId()?>,"&flag_check=1","'"+getmeCurrentCity+"'","'"+getmeCurrentLocaLity+"'");
                //changeFloatingRegistrationOnResponse();
			<?php } ?>
		},"smsThisListing");
	}
	
	
	function saveInfo(){
		loadShortRegistationForm("Save Listing",
								 "Save Listing",
								 "Saved in Account & Settings",
								 true,false,
		function(){
			<?php
			if($pageType == "course"){
			?>
				$j('#ajax-div').load('/saveProduct/SaveProduct/save/',{type: 'course', id : '<?=$course->getId()?>'});
			<?php
			}else{
			?>
				$j('#ajax-div').load('/saveProduct/SaveProduct/save/',{type: 'institute', id : '<?=$institute->getId()?>'});
			<?php
			}
			?>
			changeSaveIcon();
			displayMessage('/common/loadOverlayContent/common-commonThankYou',400,100);
			<?php if($paid && $course->isPaid()) { ?>
			makeAutoResponse(loggedUserFirstName,loggedUserLastName,loggedUserMobile,loggedUserEMail,<?=$institute->getId()?>, '<?=html_escape($institute->getName())?>',<?=$course->getId()?>,"&flag_check=1","'"+getmeCurrentCity+"'","'"+getmeCurrentLocaLity+"'");
                //changeFloatingRegistrationOnResponse();
			<?php } ?>
		},"saveListingInfo");
	}
	
	function changeSaveIcon(){
		$j('.save-info').html('<span class="sprite-bg save-icn"></span> Saved in Account & Settings').attr('onclick','').css('color','#000').css('cursor','default').css('text-decoration','none');
	}
	<?php if($saved == "saved"){ ?>
		changeSaveIcon();
	<?php } ?>
	
	<?php
	if($makeAutoResponse){
	?>
		var loggedUserFirstName = escape("<?php echo addslashes($validateuser[0]['firstname']); ?>");
		var loggedUserLastName = escape("<?php echo addslashes($validateuser[0]['lastname']); ?>");
		var loggedUserMobile = '<?php echo $validateuser[0]['mobile']; ?>';
		var loggedUserEMail = '<?php if(!empty($validateuser[0]['cookiestr'])) { $a = $validateuser[0]['cookiestr']; $b = explode('|',$a); echo $b[0]; }else{ echo "Email";}?>';
		makeAutoResponse(loggedUserFirstName,loggedUserLastName,loggedUserMobile,loggedUserEMail,<?=$institute->getId()?>, '<?=html_escape($institute->getName())?>',<?=$course->getId()?>,'',"'"+getmeCurrentCity+"'","'"+getmeCurrentLocaLity+"'");
	<?php
	}
	?>
studyAbroad = <?=$studyAbroad?>;
</script>
