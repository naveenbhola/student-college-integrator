                <!--Start_Content-->
<?php 
    if(($ListingMode == 'view' && 
    (
        (strlen(trim($details['contact_name'])) > 0) || 
        (strlen(trim($details['contact_main_phone'])) > 0 || 
        strlen(trim($details['contact_cell'])) > 0 || 
        strlen(trim($details['contact_alternate_phone'])) > 0 || 
        strlen(trim($details['contact_fax'])) > 0) || 
        (strlen(trim($details['contact_email'])) > 0) || 
        (
            (strlen(trim($details['locations'][0]['address']))> 0) || 
            (strlen(trim($details['locations'][0]['city_name'])) > 0)  || 
            (strlen(trim($details['locations'][0]['pincode'])) > 0) || 
            (strlen(trim($details['locations'][0]['country_name'])) > 0) 
        )
    )) || $ListingMode != 'view')
    {
?>
<div class="float_R contactBT" style="padding-top:5px;padding-bottom:17px" id="resolutionfor800ContactDetails">
<script>
if(document.body.offsetWidth<900){
	document.getElementById('resolutionfor800ContactDetails').style.width='255px';
} else {
	document.getElementById('resolutionfor800ContactDetails').style.width='360px';
}
function gotoBtmForm(val,googleTrackKey){	
	var hht = eval(document.getElementById('gotoBottom').offsetTop)-400;
	window.scrollTo(0, hht);
	var objFormDiv = document.getElementById('before_get_free_alerts_detail');	
	if(objFormDiv.style.display!="none"){		
		document.getElementById('reqInfoDispName_foralert_detail').focus();
		if(val==1){		
			trackEventByGA(googleTrackKey,'TopBottomClick');
		} else {		
			trackEventByGA(googleTrackKey,'MiddleClick');
		}
	}
}
</script>                
                    <div class="fontSize_14p"><b>Contact Details:</b> <span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>" style="padding-left:119px">[<a href="<?php echo $instituteEditUrlContact; ?>" class="fontSize_12p">Edit</a>]</span></div>
                    <div class="grayLine" style="line-height:3px;margin-bottom:5px">&nbsp;</div>
                    <div style="line-height:18px">
                        <?php if(strlen($details['contact_name']) > 0){ ?><b>Name of the Person :</b><?php echo nl2br_Shiksha(insertWbr($details['contact_name'],22)); ?><br /><?php } ?>
                        <?php if(strlen($details['contact_main_phone']) > 0 || strlen($details['contact_cell']) > 0 || strlen($details['contact_alternate_phone']) > 0){ ?><b>Contact No. :</b>
<?php 
$contactNos = array();
if(strlen($details['contact_main_phone'])> 0)
	$contactNos[] = nl2br_Shiksha(insertWbr($details['contact_main_phone'],22));
if(strlen($details['contact_cell'])> 0) 
	$contactNos[] = $details['contact_cell'];
if(strlen($details['contact_alternate_phone'])> 0)
	$contactNos[] = $details['contact_alternate_phone'];
    echo implode(', ',$contactNos);
?><br />
<?php       
if(strlen($details['contact_fax'])> 0) {
?>
<b>Fax number:</b>
<?php
	echo $details['contact_fax'] . "<br />";
 }   
?>
                        <?php } ?>
                         <?php if(strlen($details['contact_email']) > 0){ ?><b>Email :</b> <?php echo insertWbr($details['contact_email'],15); ?><br /> <?php } ?>
                        <?php if(strlen($details['url']) > 0){ ?><b>Website :</b> <?php echo insertWbr($details['url'],15); ?><br /> <?php } ?>
                        <b>Address :</b>                        <span style="line-height:14px"><?php echo nl2br_Shiksha(insertWbr($details['locations'][0]['address'],22));echo " ";
			if($details['institute_id']!='32469'){
                        if (preg_match("/\others\b/i", $details['locations'][0]['city_name'])) {
                            echo "<br/>";
                        }else if (preg_match("/\other\b/i", $details['locations'][0]['city_name'])) {
                            echo "<br/>";
                        } else {
                            echo $details['locations'][0]['city_name'];
                        }
                        echo ",".$details['locations'][0]['country_name']; } 
			?>
                        </span>
                        <?php
                        if ((isset($details['locations'][0]['pincode'])) && (!empty($details['locations'][0]['pincode']))) {
                            echo "<br/> <b>Pincode : </b>";
                            echo $details['locations'][0]['pincode'];
                        }
                        ?>
                    </div>
					<?php
						$googleTrackKey = 'ContactDetailsAnchor';
						if($flagForAB == 'true') {
						  $googleTrackKey = 'ContactDetailsAnchor_'.$details['institute_id'].'_case1';
						}
						if(!isset($cmsData)) {
						if(($registerText['paid'] == "yes" ) && ($flagForAB != 'false')){ 
							$googleTrackKey = 'ContactDetailsAnchor';
							if($flagForAB == 'true') {
							  $googleTrackKey = 'ContactDetailsAnchor_'.$details['institute_id'];
							}
					?>
					  <div class="listingBtn" style="margin-top:10px"><a href="javascript:void(0)" onclick="gotoBtmForm(1,'<?php echo $googleTrackKey; ?>');" style="color:#000;text-decoration:none">I want this institute to counsel me</a></div>
					<?php } } ?>
                </div>
<?php
}
?>
                <!--End_Content-->
