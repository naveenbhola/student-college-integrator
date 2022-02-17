<?php
//This condition is put because of new listing revamp changes by Aakash
if(isset($identifier)){
    $listing_type = $identifier;
}
?>
<?php
$discussionUrl = "/messageBoard/MsgBoard/topicDetails/".$details['categoryArr'][0]['category_id']."/".$details['threadId']; 

if(!isset($cmsAjaxFetch)){ 
    $this->load->view("search/searchOverlay");
    $this->load->view("listing/reportChange");
}
?>
<div class="txt_align_r">
	<?php if(!isset($cmsAjaxFetch)){ ?>
		<!-- Code start for Report abuse link -->
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
   else
   {
	 showuserOverlay(this,'add');
   }
}
</script>
