<?php
$discussionUrl = "/messageBoard/MsgBoard/topicDetails/".$details['categoryArr'][0]['category_id']."/".$details['threadId']; 

if(!isset($cmsAjaxFetch)){ 
    $this->load->view("search/searchOverlay");
    $this->load->view("listing/reportChange");
}

$trackEvent1 = ''; 
$trackEvent2 = '';
$trackEvent3 = '';
	if(isset($tab)){ 
	        if($tab=='overview'){
	                $trackEvent1 = 'LISTING_OVERVIEWTAB_SAVE_LISTING_CLICK'; 
                        $trackEvent2 = 'LISTING_OVERVIEWTAB_EMAIL_CLICK';
                        $trackEvent3 = 'LISTING_OVERVIEWTAB_SMS_CLICK';
		}
	        else if($tab=='ana'){
                        $trackEvent1 = 'LISTING_QNATAB_SAVE_LISTING_CLICK';
                        $trackEvent2 = 'LISTING_QNATAB_EMAIL_CLICK';
                        $trackEvent3 = 'LISTING_QNATAB_SMS_CLICK';
		}
	        else if($tab=='alumni'){
                        $trackEvent1 = 'LISTING_ALUMNITAB_SAVE_LISTING_CLICK';
                        $trackEvent2 = 'LISTING_ALUMNITAB_EMAIL_CLICK';
                        $trackEvent3 = 'LISTING_ALUMNITAB_SMS_CLICK';
		}
	        else if($tab=='media'){
                        $trackEvent1 = 'LISTING_MEDIATAB_SAVE_LISTING_CLICK';
                        $trackEvent2 = 'LISTING_MEDIATAB_EMAIL_CLICK';
                        $trackEvent3 = 'LISTING_MEDIATAB_SMS_CLICK';
		}
                else if($tab=='course'){
                        $trackEvent1 = 'LISTING_COURSETAB_SAVE_LISTING_CLICK';
                        $trackEvent2 = 'LISTING_COURSETAB_EMAIL_CLICK';
                        $trackEvent3 = 'LISTING_COURSETAB_SMS_CLICK';
		}

	} 
?>
<div class="">
	<div>			
		<div class="float_R">
        <?php 	 if(!isset($validateuser[0])){ ?>
<a href="#" class="fontSize_11p" onClick="trackEventByGA('LinkClick','<?php echo $trackEvent2;?>'); showuserLoginOverLay(this,'<?php echo $source."_TOP_EMAIL"?>','jsfunction','showSearchMailOverlay','<?php echo $listing_type ?>','<?php echo $type_id?>','<?php echo site_url($thisUrl)?>'); return false;"  title="Email"> Email</a><span style="color:#CCC"> |</span>
<?php 

        $quickClickAction = "javascript:showuserLoginOverLay(this,'".$source."'_TOP_SMS','refresh');";

}else { ?>
<?php if(!isset($cmsAjaxFetch)){ ?>

			<span id ="emailThisListing" > <a href="#"  onClick="trackEventByGA('LinkClick','<?php echo $trackEvent2;?>'); showSearchMailOverlay('<?php echo $listing_type; ?>','<?php echo $type_id; ?>','<?php echo site_url($thisUrl); ?>');return false;" title="Email">Email</a> </span><span style="color:#CCC">| </span>
            <?php  } } ?>

        <?php 	 if(!isset($validateuser[0])){ ?>
<a href="#" class="fontSize_11p" onClick="trackEventByGA('LinkClick','<?php echo $trackEvent3;?>'); showuserLoginOverLay(this,'<?php echo $source."_TOP_SMS"?>','refresh'); return false;" title="SMS"> SMS</a> <span style="color:#CCC">|</span>
<?php }else { ?>
<?php if(!isset($cmsAjaxFetch)){ 
    if($validateuser[0]['requestinfouser'] == 1)
    {
        $base64url = base64_encode($thisUrl);
        $quickClickAction = "javascript:window.location = '/user/Userregistration/index/".$base64url."/1';";
	?>
    <span id ="smsThisListing" > <a href="#"  onClick="trackEventByGA('LinkClick','<?php echo $trackEvent3;?>'); <?php echo $quickClickAction; ?> return false;"  title="SMS">SMS</a> </span><span style="color:#CCC">| </span>
    <?php 
    }
    else{ ?>

			<span id ="smsThisListing" > <a href="#"  onClick="trackEventByGA('LinkClick','<?php echo $trackEvent3;?>'); showSearchSmsOverlay('<?php echo $listing_type; ?>','<?php echo $type_id; ?>','<?php echo site_url($thisUrl); ?>');return false;" title="SMS">SMS</a> </span><span style="color:#CCC">| </span>
            <?php } } } ?>


            <?php if(!isset($cmsAjaxFetch)){ ?>
                <?php if ($saved!="saved") {
if(!isset($validateuser[0])){
?>
                    <span id="<?php echo $listing_type.$type_id;?>"><a href="#" onclick="trackEventByGA('LinkClick','<?php echo $trackEvent1;?>'); showuserLoginOverLay(this,'<?php echo $source."_TOP_SAVEINFO"?>','jsfunction','saveProduct','<?php echo $listing_type ?>','<?php echo $type_id?>');" title="Save Info">Save Info</a></span> <span style="color:#CCC">|</span>
                    <?php 
}else{
                    if($validateuser[0]['requestinfouser'] == 1) {
                        $base64url = base64_encode($thisUrl);
                        $quickClickAction = "javascript:window.location = '/user/Userregistration/index/".$base64url."/1';";
                ?>
                    <span id="<?php echo $listing_type.$type_id;?>"><a href="#" onclick="trackEventByGA('LinkClick','<?php echo $trackEvent1;?>'); <?php echo $quickClickAction; ?>" title="Save Info">Save Info</a></span> <span style="color:#CCC">|</span>
                <?php } else { ?>
                    <span id="<?php echo $listing_type.$type_id;?>"><a href="#" onclick="trackEventByGA('LinkClick','<?php echo $trackEvent1;?>'); saveProduct('<?php echo $listing_type; ?>','<?php echo $type_id; ?>');" title="Save Info">Save Info</a></span> <span style="color:#CCC">|</span>
                <?php } } ?>
			<?php } else { ?>
			Saved In Account & Settings<span style="color:#CCC"> |</span>
			<?php } ?>
           <?php 
            if($registerText['paid'] !="yes"){
                if(!isset($validateuser[0])){ ?>
                    <span id = "reportAbuseListing"><a href="#" class="fontSize_11p" onClick="showuserLoginOverLay(this,'<?php echo $source."_TOP_REPORTABUSE"?>','jsfunction','reportAbuseListing','<?php echo $listing_type?>','<?php echo $type_id?>'); return false;" title="Report Abuse"> Report Abuse</a></span>
                <?php }else { ?>
                    <?php if(!isset($cmsAjaxFetch)){ ?>
                        <span id ="reportAbuseListing" > <a href="javascript:void(0);"  onClick="reportAbuseListing('<?php echo $listing_type; ?>','<?php echo $type_id; ?>');" title="Report Abuse">Report Abuse</a></span>
                    <?php } ?>
                <?php } ?>
                <span style="color:#CCC"> |</span>
                <span id ="reportChange" > <a href="javascript:void(0);"  onClick="showReportChangeOverlay();" title="Report Changes">Report Changes</a></span>
           <?php } ?>

<?php } ?>
		</div>
		<div class="bld">Posted under</div>
		<div class="clear_R"></div>
	</div>




	<div class="lineSpace_17">
	   <?php $count=1;$totalCats = count($details['categoryArr']);
       $details['categoryArr'] = reorderArray($details['categoryArr'],$refCategory,$refCategoryParent,'category_id');
	      foreach ($details['categoryArr'] as $cats){ 
		 if ($totalCats>2 && $count==3) { ?>
		 <div id="morecats" style="display:none;">
					<?php  }   
				 if($cats['parent_cat_id'] >1){?>
				<?php echo $cats['parent_cat_name']?>
			
				>&nbsp;<?php echo $cats['cat_name']?><br/>
				<?php }else{ ?>
				 &nbsp; <?php echo $cats['cat_name']?><br/>
				<?php } ?>

				<?php $count++; } ?> 	 
				 <?php  if ($count>2 && $totalCats>2 ) { ?>
		</div>

	      <div id="expandcats"><a href="javascript:void(0);" onclick="$('morecats').style.display = '';$('expandcats').style.display='none';$('collapsecats').style.display=''" title="Sell all Categories">See all Categories</a></div> 	 
	               <div id="collapsecats" style="display:none"><a href="javascript:void(0);" onclick="$('morecats').style.display = 'none';$('expandcats').style.display='';$('collapsecats').style.display='none'" title="Close this list">Close this list</a></div> 	 
		        <?php } ?>
	</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<script>
function saveProduct(type,id)
{
    var url = "/saveProduct/SaveProduct/save";
    new Ajax.Request(url, { method:'post', parameters: ('type='+type+'&id='+id), onSuccess:showResponseForSave });
}

function showResponseForSave(response)
{
    if (isNaN(response.responseText))
    {
        document.getElementById(response.responseText).innerHTML = "Saved In Account & Settings";
        if(!isUserLoggedIn && getCookie('user') != '')
        {
            window.setTimeout(function(){window.location.reload();}, 5000);
        }
    }
}
</script>
