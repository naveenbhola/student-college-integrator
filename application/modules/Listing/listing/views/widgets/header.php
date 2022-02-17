<?php 
	$this->load->view('common/overlay');
	if(isset($identifier)){ $listing_type = $identifier;}
?>

<div class="mlr10">
	<div class="wdh100 mb5">
		<div class="float_L" style="width:600px">
			<?php //$this->load->view('listing/widgets/breadcrumb');?>
			<div class="Fnt11 mb5" id="breadCrumbDiv"></div><script>fillBreadCrumb();</script>
			<h1>
				<div class="Fnt18 bld mb3"><?php echo $details['title']; ?></div>
				<div class="mb5"><span class="Fnt13 bld ddgClr"><?php if($details['listings'][0]['locality'] != '' || $details['listings'][0]['locality'] != '') echo $details['locations'][0]['locality'].", "; if($details['institute_id']!='33211' && $details['institute_id']!='32469' && $details['institute_id']!='32383' && $details['institute_id']!='32645') { echo $details['locations'][0]['city_name'];} ?></span>
				<!--<a href="#" class="sprt_nlt rArrow">Also present at 2 other location</a>-->
				</div>
			</h1>
		</div>
		<?php if(!empty($details['institute_logo'])){?>
			<div class="float_R" style="width:350px">				
				<?php if(!empty($details['institute_logo'])){?>
					<div style="height:70px;text-align:right">
						<div><a href="#"><img src="<?php echo $details['institute_logo'];?>" border="0" /></a></div>
					</div>
				<?php }?>
			</div>
		<?php }?>
		<div class="clear_B">&nbsp;</div>
	</div>
	<div class="wdh100">
		<div class="float_L" style="width:750px">
			<div class="Fnt11">
				 <?php if(($instituteType==1)&&(!empty($details['aima_rating']))){?>
				<span class="float_L">AIMA Rating:</span>
				<span class="aRating" onMouseOver = "showHideAimaToolTip('show')" onMouseOut = "showHideAimaToolTip('hide')"><img src ="/public/images/<?php echo $details['aima_rating']?>.gif"></span>
				<?php }?>
				<?php  if(!empty($details['establish_year'])){?><span class="float_L">Established in <?php echo $details['establish_year']?></span><?php }?>
				<span class="sprt_nlt cDetail"><a href="javascript:void(0)" onClick="showContactDetails();" >View Contact Details</a></span>
				<!--Start_Email-->
				<?php if(!isset($cmsAjaxFetch)){ ?>
				<span class="sprt_nlt eDetail" id="emailThisListing"><a href="#"  onClick="calloverlayListing('EMAIL','<?php echo $listing_type; ?>','<?php echo $type_id; ?>','<?php echo site_url($thisUrl); ?>',this,'<?php echo $source."_TOP_EMAIL"?>','<?php if(isset($subject)){ echo $subject; }else{ echo "Shiksha Info"; } ?>','<?php if(isset($extraParams)){ echo $extraParams; } ?>');return false;"  uniqueattr="ListingPageEmailLink">Email</a></span>
				<?php } ?>
				<!--End_Email-->
				
				<!--Start_SMS-->
				<?php if(!isset($cmsAjaxFetch)){
					if(($validateuser!='false')&&($validateuser[0]['requestinfouser'] == 1)){
					$base64url = base64_encode($thisUrl);
					$quickClickAction = "javascript:window.location = '/user/Userregistration/index/".$base64url."/1';";
				?>
					<span class="sprt_nlt sDetail" id="smsThisListing"><a href="#" onClick="<?php echo $quickClickAction; return false; ?>" title="SMS" uniqueattr="ListingPageSmsLink">SMS</a></span>
				<?php }	else { ?>
				<span class="sprt_nlt sDetail" id="smsThisListing"><a href="#" onClick="calloverlayListing('SMS','<?php echo $listing_type; ?>','<?php echo $type_id; ?>','<?php echo site_url($thisUrl); ?>',this,'<?php echo $source."_TOP_SMS"?>','<?php if(isset($subject)){ echo $subject; }else{ echo "Shiksha Info"; } ?>','<?php if(isset($extraParams)){ echo $extraParams; } ?>');return false;" uniqueattr="ListingPageSmsLink">SMS</a></span>
				<?php } } ?>
				<!--End_SMS-->
				
				<!--Start_SaveInfo-->
				<?php if(!isset($cmsAjaxFetch)){?>
				<span class="sprt_nlt siDetail" id="<?php echo 'institute'.$type_id;?>" style="display:none" uniqueattr="ListingPageSaveInfoLink"></span>
				<span class="sprt_nlt siDetail" id="<?php echo 'course'.$type_id;?>" style="display:none" uniqueattr="ListingPageSaveInfoLink"></span>
				<?php } ?>
				<!--End_SaveInfo-->

				<!--Start_ApplyNow-->
				<?php if($details['packType']=='1'||$details['packType']=='2'){?>
				<span class="sprt_nlt anDetail"><a href="javascript:void(0)" onClick="document.getElementById('reqInfoDispName_foralert_detail').focus()" >Apply Now</a></span>
				<?php } ?>
				<!--End_ApplyNow-->

				<!-- Online form button Start -->
				<?php if($details['courseDetails'][0]['displayOnlineFormButton']==='true'){
					$urlToRedirectWhenFormExpired    = '/studentFormsDashBoard/MyForms/Index/';
				?>
<?php $inst_id = $details['institute_id'];if(array_key_exists('seo_url', $online_form_institute_seo_url[$inst_id])) {$seo_url = SHIKSHA_HOME.$online_form_institute_seo_url[$inst_id]['seo_url'];} else {$seo_url = "/Online/OnlineForms/showOnlineForms/".$details['courseDetails'][0]['course_id'];}?>
				  <span class="onlineAppForm"><a onClick="setCookie('onlineCourseId','<?php echo $details['courseDetails'][0]['course_id'];?>',0);checkOnlineFormExpiredStatus('<?php echo $details['courseDetails'][0]['course_id'];?>','<?php echo $urlToRedirectWhenFormExpired;?>','<?php echo $seo_url?>');return false;" href="javascript:void(0);">Online Application Form</a></span>
				<?php }?>
				<!-- Online form button End -->
				<div class="clear_B">&nbsp;</div>
			</div>
		</div>
		<div class="float_R" style="width:200px">
			<div class="Fnt11 tar">
				<?php 
				$srcarr = array('source'=>'LISTING_INSTITUTEDETAIL');
				$this->load->view('listing_forms/listingActionBar',$srcarr);
				?>
			</div>
		</div>
		<div class="clear_B">&nbsp;</div>
	</div>
<?php if(($instituteType==1)&&(!empty($details['aima_rating']))){
if($details['aima_rating'] == 'SL'){
$rat = "Super League";
}else{
$rat = $details['aima_rating'];
}
?>
<div style="postion:relative;display:none" id="aima_tool_tip">
<div style="position:absolute;margin:-4px 0 0 63px">
<div style="position:relative;top:1px;left:10px"><img src="/publi
c/images/rArw.gif" /></div>
<div style="float:left;background:url(/public/images/rbg.gif) left top repeat-x;height:20px;line-height:20px;padding:0 5px;border:1px solid #abcaf6;font-size:11px">AIMA Rating - <?php echo $rat;?></div>
</div>
</div>

<script>
function showHideAimaToolTip(action){
if(action == 'show')
document.getElementById('aima_tool_tip').style.display = 'block';
if(action == 'hide')
document.getElementById('aima_tool_tip').style.display = 'none';
return false;
}
</script>
<?php }?>
</div>

<div id ="contact_details" style="display:none">
            <div class="whtRound">
            	<div class="wdh100">
                	<div class="lineSpace_20">
        <?php if(!empty($details['contact_name'])){?><strong>Name of the Person:</strong> <?php echo $details['contact_name'];?><br /><?php }?>
        <?php if((!empty($details['contact_main_phone']))||(!empty($details['contact_cell']))||(!empty($details['contact_alternate_phone']))){
        $contact = array();
        if(!empty($details['contact_main_phone'])){
            $contact[] = $details['contact_main_phone'];
        }
        if(!empty($details['contact_cell'])){
            $contact[] = $details['contact_cell'];
        }
        if(!empty($details['contact_alternate_phone'])){
            $contact[] = $details['contact_alternate_phone'];
        }
        ?>
         <p><strong>Contact No.: </strong><a href="#" onclick="showPhoneNumber(this,'contactInstituteTop'); return false;" uniqueattr="contactInstituteTop"><img align="absmiddle" src="/public/images/click-view-btn.gif" alt="Click to View"/></a><span id="contactInstituteTop" style="display:none"><?php echo implode(",",$contact);?></span></p>
        <?php }?>
        <?php if(!empty($details['contact_website'])){?><strong>Website:</strong> <?php echo $details['contact_website'];?><br /><?php }?>
        <?php if(!empty($details['locations'])){
            $location = array();
            if(!empty($details['locations']['0']['address1'])){
            $location[] =  trim($details['locations']['0']['address1'],",");}
            if(!empty($details['locations']['0']['address2'])){
            $location[] =  trim($details['locations']['0']['address2'],",");}
            if(!empty($details['locations']['0']['locality'])){
            $location[] =  trim($details['locations']['0']['locality'],",");}
	    if($details['institute_id']!='33211' && $details['institute_id']!='32469' && $details['institute_id']!='32383' && $details['institute_id']!='32645'){
            if(!empty($details['locations']['0']['city_name'])){
            $location[] =  trim($details['locations']['0']['city_name'],",");}
	    
            if(!empty($details['locations']['0']['country_name'])){
            $location[] =  trim($details['locations']['0']['country_name'],",");}
	    if(!empty($details['locations']['0']['pincode'])){
            $pincode =  trim($details['locations']['0']['pincode'],",");
	    $pincode = "-".$pincode;
	    }
	    }
            ?><p><strong>Address:</strong> <?php echo implode(",",$location);echo $pincode;?></p><?php }?>
	    <?php if(!empty($details['courseDetails']['0']['contact_name'])||(!empty($details['courseDetails']['0']['contact_email']))||(!empty($details['courseDetails']['0']['contact_cell']))||(!empty($details['courseDetails']['0']['contact_main_phone']))||(!empty($details['courseDetails']['0']['contact_alternate_phone']))){?>
	    <div class="ln">&nbsp;</div>
	    <?php if(!empty($details['courseDetails']['0']['title'])){?>
	    <p><strong>For Course:</strong> <?php echo $details['courseDetails']['0']['title'];?></p>
	    <?php }?>
	    <?php if(!empty($details['courseDetails']['0']['contact_name'])){?>
	    <p><strong>Contact Name:</strong> <?php echo $details['courseDetails']['0']['contact_name'];?></p>
	    <?php }?>
	    <?php if((!empty($details['courseDetails']['0']['contact_main_phone']))||(!empty($details['courseDetails']['0']['contact_cell']))||(!empty($details['courseDetails']['0']['contact_alternate_phone']))){
        $contact = array();
        if(!empty($details['courseDetails']['0']['contact_main_phone'])){
            $contact[] = $details['courseDetails']['0']['contact_main_phone'];
        }
        if(!empty($details['courseDetails']['0']['contact_cell'])){
            $contact[] = $details['courseDetails']['0']['contact_cell'];
        }
        if(!empty($details['courseDetails']['0']['contact_alternate_phone'])){
            $contact[] = $details['courseDetails']['0']['contact_alternate_phone'];
        }
        ?>
        <p><strong>Contact No.: </strong><a href="#" onclick="showPhoneNumber(this,'contactCourseTop'); return false;"><img align="absmiddle" src="/public/images/click-view-btn.gif" alt="Click to View"/></a><span id="contactCourseTop" style="display:none"><?php echo implode(",",$contact);?></span></p>
	<?php }?>
	<?php if(!empty($details['courseDetails']['0']['contact_email'])){?>
	    <p><strong>Email:</strong> <?php echo $details['courseDetails']['0']['contact_email'];?></p>
	<?php }?>
	<?php }?>

                    </div>
                </div>
            </div>
    </div>

<script>
function showContactDetails(){
     var content = $('contact_details').innerHTML;
     overlayParentAnA = $('contact_details');
     overlayParentAnA.innerHTML = '';
     showOverlayAnA(367,400,'Contact Detail',content);
}


function hideContactDetails(){
    document.getElementById('contact_main_div').style.display = 'none';
}
</script>
