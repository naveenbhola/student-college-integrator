<div class="lineSpace_10" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%;display:block;padding-bottom:20px;font-weight:bold;font-size:12px;">Edit institute details, course details if required or else publish all.</div>
<?php if(count($listings) > 0 ) { ?>
<div align="center" style="width:100%;display:block"><b style="font-size:12px;">Publish listings under college <?php echo $institute_name; ?> </b>
</div>
<?php } ?>

<div class="lineSpace_10" style="width:100%">&nbsp;</div>
<div style="margin-left:50px">
<div style="overflow:auto; height:120px">
	<table width="90%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;background:url(/public/images/bgTable.gif) repeat-x left bottom;margin-left:1px" bordercolor="#cfcfcf">
    <tr>
        <td height="24" width="35%" align="left" valign="middle" style="padding-left:10px;"><strong>Listing Title</strong> </td>
        <td width="15%" align="left" valign="middle" style="padding-left:10px"><strong>Listing Type</strong> </td>
        <!-- <td width="25%" align="left" valign="middle" style="padding-left:10px"><strong>See Preview</strong> </td>  -->
        <td width="25%" align="left" valign="middle" style="padding-left:10px"><strong>Edit Listing</strong> </td>
        <td width="15%" align="left" valign="middle" style="padding-left:10px"><strong>Status</strong> </td>
    </tr>
	</table>
    <table width="90%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin-left:1px" bordercolor="#cfcfcf">
        <?php
            $i =1;
            foreach($listings as $key => $val){ 
            	if($val['type']=='institute'){
            		$editUrl = site_url("/enterprise/ShowForms/editInstituteForm/".$val['typeId']);
            	}elseif($val['type']=='course'){
            		$editUrl = site_url("/enterprise/ShowForms/showCourseEditForm/".$val['typeId']);
            	}
            	?>
            	
        <tr id="selectedListingId_<?php echo $i; ?>">
            <td height="24" width="35%" valign="top" style="padding:0 10px;border-left:1px solid #cfcfcf"><?php echo stripslashes($val['title']); ?></td>
            <td width="15%" valign="top" style="padding:0 10px"><?php echo $val['type']; ?></td>
            <!-- <td width="25%" valign="top" style="padding:0 10px"><a href="javascript:void(0);" onClick="fetchPreview('selectedListingId_<?php echo $i; ?>','<?php echo $val['type'];?>','<?php echo $val['typeId'];?>');" style="background:url(/public/images/sPreview.gif) no-repeat left top;padding:0 0 3px 20px">Click for Preview</a></td>  -->
            <td width="25%" valign="top" style="padding:0 10px"><a href="<?php echo $editUrl?>"  style="background:url(/public/images/sPreview.gif) no-repeat left top;padding:0 0 3px 20px">Edit</a></td>
            <td width="15%" valign="top" style="padding:0 10px"><span style="color:#e97600">To be Published</span></td>
        </tr>
        <?php $i++; 
        } ?>

           <?php 
            foreach($otherListings as $key => $val){ 
            	if($val['type']=='institute'){
            		$editUrl = site_url("/enterprise/ShowForms/editInstituteForm/".$val['typeId']);
            	}elseif($val['type']=='course'){
            		$editUrl = site_url("/enterprise/ShowForms/showCourseEditForm/".$val['typeId']);
            	}
            	?>
        <tr id="selectedListingId_<?php echo $i; ?>">
            <td height="24" width="35%" valign="top" style="padding:0 10px;border-left:1px solid #cfcfcf"><?php echo $val['title']; ?></td>
            <td width="15%" valign="top" style="padding:0 10px"><?php echo $val['type']; ?></td>
            <td width="25%" valign="top" style="padding:0 10px"><a href="<?php echo $editUrl?>" style="background:url(/public/images/sPreview.gif) no-repeat left top;padding:0 0 3px 20px">Edit</a></td>
            <td width="15%" valign="top" style="padding:0 10px"><span style="color:#1d8508">LIVE</span></td>
        </tr>
        <?php $i++; 
        } ?>

    </table>
</div>
</div>
<input type="hidden" id="totalListings" value="<?php echo $i; ?>" />
<?php if(count($listings) > 0) { ?>
<form action="<?php echo $formPostUrl; ?>" name="submitListings" id="submitListings" method="post" enctype="multipart/form-data">

<input type="hidden" name="listings" value="<?php echo base64_encode(serialize($listings)); ?>"/>
        <div style="line-height:9px">&nbsp;</div>
        <div align="center">
        	<div class="r1_1 bld">&nbsp;</div>
			<div class="r2_2">
				<button class="btn-submit19" type="button" value="" onclick="ENT_SubmitPublishLayer();" style="width:120px"><div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Publish All</p></div>
				</button>
				<button class="btn-submit39" type="button" value="" onClick=" try{ ListingOnBeforeUnload.prompt = true;location.replace('/enterprise/Enterprise/index/7'); } catch (err) { }"  style="width:100px"><div class="btn-submit39"><p style="padding: 15px 8px 15px 5px;color:#000; font-size:12px" class="btn-submit40">Cancel</p></div>
				</button>
			</div>
			<div class="clear_L"></div>
		</div>

</form>
<?php }else{ ?>
<div class="lineSpace_10" style="width:100%">&nbsp;</div>
<div align="center">
<b style="font-size:15px;">
You have not changed any of your listings.
</b>
</div>
<div class="lineSpace_10" style="width:100%">&nbsp;</div>
<?php } ?>
<div id="dblclick_error_msg" align="center" style="width:100%;display:none;padding-bottom:20px;font-weight:bold;font-size:12px;">
</div>
<script>
	var ENT_PublishLayerSubmit = false;
	function ENT_SubmitPublishLayer() {
		if(ENT_PublishLayerSubmit == false){
			ENT_PublishLayerSubmit = true;
			$('submitListings').submit();
			$('dblclick_error_msg').style.display = 'block';
			$('dblclick_error_msg').style.color = '#000';
			$('dblclick_error_msg').innerHTML = 'Publishing in progress';
		} else {
			$('dblclick_error_msg').style.display = 'block';
			$('dblclick_error_msg').style.color = 'red';
			$('dblclick_error_msg').innerHTML = 'Please click "Publish All" button only once. If you are stuck, please refresh this page and try again.';
		}
	}
</script>