<title>Upload attachment on pageID=<?php echo $page_id;?></title>
<style>
.rowWDFcms {
  width: 988px!important;
}
.main {
  _margin-left: 150px!important;
}
.homeShik_footerBg {
   _position: relative;
   _left: 150px!important;
   _width: 1013px!important;
}
</style>
<div class="main">
<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        ); 
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs',$headerTabs);
?>
<div style="width: 900px;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">

<div class="orangeColor fontSize_14p bld" style="width: 530px; font-size:18px;"><span><b>Attachment</b></span><br/><span style="color:black;font-size:13px;">Page URL: <?php echo $details['0']['page_url']; ?></span>
<div class="grayLine_1" style="margin-top:5px;">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
</div>

<form id="content_form" action="/enterprise/MultipleMarketingPage/saveMarketingPageMailer"  enctype="multipart/form-data" method="post" onsubmit="return validateFields()">
   <input type="hidden" name="page_id" value="<?php if(!empty($details['0']['page_id'])) : echo $details['0']['page_id']; else: echo $page_id; endif;?>"/>
<?php
/*
	<div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Subject : </div>
    </div>
    <div style="margin-left: 177px;padding-bottom:5px;">
        <div>
        <input type="text" value="<?php if(!empty($details['0']['subject'])) :echo htmlspecialchars($details['0']['subject']); endif; ?>" class="txt_1" name="subject" style="width: 600px; border:1px solid #ccc" id="subject" size="30" maxlength="250" onblur="validateField(this)" caption="subject_error"/>
        </div>
        <div style="" id="subject_error" class="errorMsg"></div>
    </div>
    <div class="clearFix">&nbsp;</div>
	
	<div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Content : </div>
    </div>
    <div style="margin-left: 150px;padding-bottom:5px;">
        <div>
       <textarea  name="content" id="content" caption="content_error" onblur="validateField(this)" style="margin-left:3px; width:600px; height:300px;"><?php if(!empty($details['0']['content'])):echo $details['0']['content'];endif; ?></textarea>
         </div>
         <div>
            <div style="padding-left:25px;font-size:10px;" id="content_error" class="errorMsg"></div>
        </div>
	</div>	 
    <div class="clearFix">&nbsp;</div>

*/	
?>
	<br />
	<div style="width: 250px; line-height: 18px;" class="float_L">
	  <div style="padding-right: 5px; padding-top:4px; font: bold 12px arial;" class="txt_align_r">Upload Document:</div>
	</div>
	<div style="margin-left: 255px;">
	  <div>
		<input onblur="validateField(this)" type="file" value="" class="txt_1" name="myImage[]" style="width: 150px;" id="myImage" size="30" caption="banner_error" />
	  </div>
	  <div>
		<div style="" id="banner_error" class="errorMsg"></div>
	  </div>
	</div>
    <div class="clearFix">&nbsp;</div>
	
	<?php if($details[0]['attachment_url'] && $details[0]['attachment_name']) { ?>
	<div style="width: 175px; line-height: 18px;" class="float_L">
	  <div style="padding-right: 5px;" class="txt_align_l">&nbsp;</div>
	</div>
	<div style="margin-left: 255px; margin-top:10px; background: #eee; width:580px; padding:10px;">
	  <div>
		<div style='float:left; width:500px;'>
		<b>Document currently uploaded: 
		<a href='<?php echo MEDIA_SERVER.$details[0]['attachment_url']; ?>'><?php echo $details[0]['attachment_name']; ?></a>
		</b>	
		</div>
		<div style='float:right; width:80px;'>
		  <input type='checkbox' name="remove_attachment" id="remove_attachment" /> Remove
		</div>
		<div class="clearFix"></div
	  </div>
	</div>
	  <div>
		<div style="" id="banner_error" class="errorMsg"></div>
	  </div>
	</div>
    <div class="clearFix">&nbsp;</div>
   <?php } ?>
   
   <br />
   <div style="width: 250px;" class="float_L">
        <div style="padding-right: 5px; padding-top:0px; font: bold 12px arial;" class="txt_align_r">Download Confirmation Message : </div>
    </div>
    <div style="margin-left: 255px;padding-bottom:5px; padding-top:2px;">
        <div>
        <textarea  name="downloadConfirmationMessage" id="downloadConfirmationMessage" style="margin-left:3px; width:400px; height:100px;"><?php if(!empty($details['0']['download_confirmation_message'])):echo $details['0']['download_confirmation_message'];endif; ?></textarea>
        </div>
        <div style="" id="downloadConfirmationMessage_error" class="errorMsg"></div>
    </div>
    <div class="clearFix">&nbsp;</div>
	
	
  <div class="clear_L withClear">&nbsp;</div>
  <div class="clear_L withClear">&nbsp;</div>
  <div class="clear_L withClear">&nbsp;</div>
<div class="spacer10"></div>

<div style="margin-left:255px;">
<button type="submit" value=""class="btn-submit5 w12">
<div class="btn-submit5">
<p class="btn-submit6">Save</p>
</div>
</button>
<button type="button"  onclick="location.replace('/enterprise/MultipleMarketingPage/marketingPageDetails')" value=""class="btn-submit5 w12">
<div class="btn-submit5">
<p class="btn-submit6">Cancel</p>
</div>
</button>
</div>

</form>
<div class="clear_L withClear">&nbsp;</div>
<div class="clear_L withClear">&nbsp;</div>
<div class="clear_L withClear">&nbsp;</div>
<div class="clear_L withClear">&nbsp;</div>
<div class="clear_L withClear">&nbsp;</div>
</div>
</div>
<?php $this->load->view('common/footer');?>
<script type="text/javascript"> 
function validateFields() {
  
//  var validateSubject = validateField(document.getElementById('subject'));
//  var validateContent = validateField(document.getElementById('content'));
//  
//  if (validateSubject && validateContent) {
//	return true;
//  }
//  else {
//	return false;
//  }

  var removeAttachment = document.getElementById('remove_attachment');
  if (removeAttachment) {
	return true;
  }

  var validateAttachment = validateField(document.getElementById('myImage'));
  if (validateAttachment) {
	return true;
  }
  else {
	return false;
  }
}

function validateField(element) {
	var value = element.value.replace(/^\s*|\s*$/g,'');
	if(!value) {
		if(element.name=='myImage[]') {
 		     document.getElementById(element.getAttribute('caption')).innerHTML = "Please upload the document";
		} else {
			document.getElementById(element.getAttribute('caption')).innerHTML = "Please enter "+element.name.replace('_',' ');
		}
		return false; 
	} else {
		document.getElementById(element.getAttribute('caption')).innerHTML = "";
		return true;
	}
}
</script>