<title>change contents of the page pageID=<?php echo $page_id;?></title>
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
<div style="width: 600px;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
<div class="orangeColor fontSize_14p bld" style="width: 530px;"><span><b>Edit Content</b></span><br/><span style="color:black;font-size:13px;">Page URL: <?php echo $details['0']['page_url'];?></span>
<div class="grayLine_1" style="margin-top:5px;">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
</div>
<form id="content_form" action="/enterprise/MultipleMarketingPage/savemarketingPageContents"  enctype="multipart/form-data" method="post" onsubmit="return validateFields()">
   <input type="hidden" name="page_id" value="<?php if(!empty($details['0']['page_id'])) : echo $details['0']['page_id']; else: echo $page_id; endif;?>"/>
    <input type="hidden" name="display_on_page" value="<?php if(!empty($details['0']['display_on_page'])) : echo $details['0']['display_on_page']; else: echo $display_on_page; endif; ?> "/>
    <?php $display_on_page = trim($display_on_page);?>
    
    <?php if($details['0']['display_on_page'] == 'newmmp' || $display_on_page == 'newmmp'){ ?>
   <div>
    <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Enter Form Heading : </div>
    </div>
    <div style="margin-left: 177px;padding-bottom:5px;">
        <div>
        <input type="text" value="<?php if(!empty($details['0']['form_heading'])):echo htmlspecialchars($details['0']['form_heading']);else: echo htmlspecialchars($form_heading); endif;?>" class="txt_1" name="form_heading" style="width: 325px;" id="form_heading" size="30"  maxlength="200" caption="form_heading_error" onblur="validateField(this)"/>
        </div>
        <div style="font-size:10px;"><br>
			Max 200 characters</div>
		<div>
            <div style="" id="form_heading_error" class="errorMsg"></div>
        </div>
       </div>
    </div>


<div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Enter Sub-heading : </div>
    </div>
    <div style="margin-left: 177px;padding-bottom:5px;">
        <div>
        <textarea maxlength="500" value="<?php if(!empty($details['0']['subheading'])):echo htmlspecialchars($details['0']['subheading']);else: echo htmlspecialchars($subheading);endif;?>" class="txt_1" name="subheading" style="width: 325px;" id="subheading" size="30" caption="subheading _error" onblur="validateField(this)"><?php if(!empty($details['0']['subheading'])):echo htmlspecialchars($details['0']['subheading']);else: echo htmlspecialchars($subheading);endif; ?></textarea>
        </div>
    <div style="font-size:10px;"><br>
      Max 500 characters</div>
        <div>
            <div style="" id="subheading _error" class="errorMsg"></div>
        </div>
    </div>

    <div style="width: 175px; line-height: 18px;" class="float_L">
	     <div style="padding-right: 5px;" class="txt_align_l">Upload Background Image:</div>
	 </div>
    <div style="margin-left: 177px;padding-bottom: 5px">
	<div>
	<input onblur="validateField(this)" type="file" value="" class="txt_1" name="myImage[]" style="width: 252px;" id="myImage" size="30" caption="background_error" /></div>
	<?php if(!empty($details['0']['background_image'])){ ?>
	<a href="<?php echo MEDIA_SERVER.htmlspecialchars($details['0']['background_image']); ?>" target="_blank">View Previously Uploaded Image</a>
	<?php } ?>
  <input type="hidden" name="background_image" value="<?php echo htmlspecialchars($details['0']['background_image']); ?>" />
	<div>
	<div>
            <div style="" id="background_error" class="errorMsg"></div>
     </div>
	<?php if(!empty($error_message)):?>
	<div id="upload_error" class="errorMsg"><?php echo $error_message?></div>
	<?php else:?>
	<div id="upload_error" class="errorMsg" style="color:green!important;"><?php echo $success_message?></div>
	<?php endif;?>
	</div>
	</div>

  
  <div style="width: 175px; line-height: 18px;" class="float_L">
       <div style="padding-right: 5px;" class="txt_align_l">Upload Header Image (Mobile Only):</div>
  </div>

  <div style="margin-left: 177px;">
    <div>
    <input onblur="validateField(this)" type="file" value="" class="txt_1" name="myHeaderImage[]" style="width: 200px;" id="myHeaderImage" size="30" caption="header_error" /></div>
    
    <input type="hidden" name="header_image" value="<?php echo htmlspecialchars($details['0']['header_image']); ?>" />
    
    <div><b>Dimension: 230 * 100 Only</b></div>
    <?php if(!empty($details['0']['header_image'])){ ?>
      <a href="<?php echo MEDIA_SERVER.htmlspecialchars($details['0']['header_image']); ?>" target="_blank">View Previously Uploaded Image</a>
    <?php } ?>

    <div>
    <div>
              <div style="" id="header_error" class="errorMsg"></div>
       </div>
    <?php if(!empty($header_error_message)):?>
    <div id="header_upload_error" class="errorMsg"><?php echo $header_error_message?></div>
    <?php else:?>
    <div id="header_upload_error" class="errorMsg" style="color:green!important;"><?php echo $header_success_message?></div>
    <?php endif;?>
    </div>
  </div>


    <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Enter Background URL : </div>
    </div>
    <div style="margin-left: 177px;padding-bottom:5px;">
        <div>
        <input type="text" value="<?php if(!empty($details['0']['background_url'])):echo htmlspecialchars($details['0']['background_url']);else: echo htmlspecialchars($background_url);endif;?>" class="txt_1" name="background_url" style="width: 325px;" id="background_url" size="30" maxlength = "500" caption="background_url _error" onblur="validateField(this)"/>
        </div>
        <div style="font-size:10px;"><br>
			Max 500 characters</div>
		<div>
            <div style="" id="background_url _error" class="errorMsg"></div>
        </div>
    </div>
        <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Enter Pixel Codes : </div>
    </div>
    <div style="margin-left: 177px;padding-bottom:5px;">
        <div>
        <textarea value="<?php if(!empty($details['0']['pixel_codes'])):echo htmlspecialchars($details['0']['pixel_codes']);else: echo htmlspecialchars($pixel_codes);endif;?>" class="txt_1" name="pixel_codes" style="width: 325px;" id="pixel_codes" size="30" caption="pixel_codes _error" onblur="validateField(this)"><?php if(!empty($details['0']['pixel_codes'])):echo htmlspecialchars($details['0']['pixel_codes']);else: echo htmlspecialchars($pixel_codes);endif; ?></textarea>
        </div>
        <div style="font-size:10px;"><br>
			Enter in base64 encoded format</div>
		<div>
            <div style="" id="pixel_codes _error" class="errorMsg"></div>
        </div>
    </div>

    </div>
    
    <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Enter Submit Button Text : </div>
    </div>
    <div style="margin-left: 177px;padding-bottom:5px;">
      <div>
        <input type="text" value="<?php if(!empty($details['0']['submitButtonText'])):echo htmlspecialchars($details['0']['submitButtonText']);else: echo htmlspecialchars($submitButtonText);endif;?>" class="txt_1" name="submitButtonText" style="width: 325px;" id="submitButtonText" size="30" maxlength = "40" caption="submitButtonText _error"/>
      </div>
      <div style="font-size:10px;"><br>Max 40 characters</div>
      <div>
          <div style="" id="pixel_codes _error" class="errorMsg"></div>
      </div>
    </div>

   </div>
   <?php } else{ ?>
   <div>
    <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Enter Heading : </div>
    </div>
    <div style="margin-left: 177px;padding-bottom:5px;">
        <div>
        <input type="text" value="<?php if(!empty($details['0']['header_text'])) :echo htmlspecialchars($details['0']['header_text']); else: echo htmlspecialchars($header_text);endif; ?>" class="txt_1" name="header_text" style="width: 320px;" id="header_text" size="30" maxlength="60" onblur="validateField(this)" caption="header_text_error"/>
        </div>
        <div style="width:300; valign:top;font-size:10px;"><br>
			Max 60 characters</div>
        <div>
            <div style="" id="header_text_error" class="errorMsg"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
    <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Enter Banner Text : </div>
    </div>
    <div style="margin-left: 150px;padding-bottom:5px;">
        <div>
       <textarea  name="banner_text" id="banner_text" rows="20" cols="50" caption="banner_text_error" onblur="validateField(this)" style="margin-left:3px;"><?php if(!empty($details['0']['banner_text'])):echo $details['0']['banner_text'];else: echo $banner_text;endif; ?></textarea>
         </div>
         <div>
            <div style="padding-left:25px;font-size:10px;" id="banner_text_error" class="errorMsg"></div>
        </div>
        <div style="padding-left:25px;font-size:10px;">
			   HTML allowed, Max 500 characters</div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
    <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Enter Form Heading : </div>
    </div>
    <div style="margin-left: 177px;padding-bottom:5px;">
        <div>
        <input type="text" value="<?php if(!empty($details['0']['form_heading'])):echo htmlspecialchars($details['0']['form_heading']);else: echo htmlspecialchars($form_heading);endif;?>" class="txt_1" name="form_heading" style="width: 325px;" id="form_heading" size="30"  maxlength="50" caption="form_heading_error" onblur="validateField(this)"/>
        </div>
        <div style="font-size:10px;"><br>
			Max 50 characters</div>
		<div>
            <div style="" id="form_heading_error" class="errorMsg"></div>
        </div>
    </div>
  	<div style="width: 175px; line-height: 18px;" class="float_L">
  <div style="padding-right: 5px;" class="txt_align_l">Upload Banner Image:</div>
  </div>


  </div>

  
	<div style="margin-left: 177px;">
	<div>
	<input onblur="validateField(this)" type="file" value="" class="txt_1" name="myImage[]" style="width: 150px;" id="myImage" size="30" caption="banner_error" /></div>
	<div>
	<div>
            <div style="" id="banner_error" class="errorMsg"></div>
     </div>
	<?php if(!empty($error_message)):?>
	<div id="upload_error" class="errorMsg"><?php echo $error_message?></div>
	<?php else:?>
	<div id="upload_error" class="errorMsg" style="color:green!important;"><?php echo $success_message?></div>
	<?php endif;?>
	</div>
	</div>
    
    <div class="clear_L withClear">&nbsp;</div>
</div>

  <div class="clear_L withClear">&nbsp;</div>
  <div class="clear_L withClear">&nbsp;</div>
  <div class="clear_L withClear">&nbsp;</div>
  <div class="clear_L withClear">&nbsp;</div>
  
  <div style="width: 175px; line-height: 18px;" class="float_L">
      <div style="padding-right: 5px;" class="txt_align_l">Enter Submit Button Text : </div>
  </div>
  <div style="margin-left: 177px;padding-bottom:5px;">
    <div>
      <input type="text" value="<?php if(!empty($details['0']['submitButtonText'])):echo htmlspecialchars($details['0']['submitButtonText']);else: echo htmlspecialchars($submitButtonText);endif;?>" class="txt_1" name="submitButtonText" style="width: 325px;" id="submitButtonText" size="30" maxlength = "50" caption="submitButtonText _error"/>
    </div>
    <div style="font-size:10px;"><br>Max 50 characters</div>
    <div>
        <div style="" id="pixel_codes _error" class="errorMsg"></div>
    </div>
  </div>
  <?php } ?>

   <div class="clear_L withClear">&nbsp;</div>
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
String.prototype.count = function(character){  
return this.split(character).length-1;  
}   
function validateFields() {

if ($j('#banner_text').length) {
  var string = document.getElementById('banner_text').value.replace(/^\s*|\s*$/g,'');
  count1 = string.count('</div>');
  count2 = string.count('<div');

  if(string.length>500) {

      document.getElementById('banner_text_error').innerHTML = "Please don\'t enter more than 500 characters for banner text";
      return false;

  } 

  if((count1>0 || count2>0) && count1!=count2) {

  	alert('Please close the mismatched div so page will appear correctly');
  	return false;

  }

}

if (document.getElementById('background_url').value != '') {

  if(!validateURLNew(document.getElementById('background_url').value)){
    alert("Please enter a valid URL for background URL");
    return false;
  }
}


	if((document.getElementById('header_text').value.replace(/^\s*|\s*$/g,'')) && (document.getElementById('banner_text').value.replace(/^\s*|\s*$/g,'')) && (document.getElementById('form_heading').value.replace(/^\s*|\s*$/g,''))) {
		if(string.length>500) {
	         document.getElementById('banner_text_error').innerHTML = "Please don\'t enter more than 500 characters for banner text";
	         return false;
		} 
		return true;
	} else {
		var where_to= confirm('Form is not complete. It can still be saved by clicking Okay. Click Cancel to stay on this form and complete it.');
		if(where_to==true) {
			return true;
		}
		return false;
		}
}

function validateField(element) {
	var value = element.value.replace(/^\s*|\s*$/g,'');

	if(!value) {
		if(element.name == 'myImage[]') {
 		     document.getElementById(element.getAttribute('caption')).innerHTML = "Please upload image"+element.name.replace('myImage[]','')+" so the page will appear correctly";
		}else if(element.name=='myHeaderImage[]') {
         document.getElementById(element.getAttribute('caption')).innerHTML = "Please upload header image so the page will appear correctly";
    } else {
			document.getElementById(element.getAttribute('caption')).innerHTML = "Please enter "+element.name.replace('_',' ')+" so the page will appear correctly";
		}
		return false; 
	} else {
		document.getElementById(element.getAttribute('caption')).innerHTML = "";
		return true;
	 
	}
}

function validateURLNew(value) {
	var result = false;
	if(value) {
		var regx = new RegExp();
		regx.compile("^[A-Za-z]+://[A-Za-z0-9-]+\.[A-Za-z0-9]+");
		result = regx.test(value);
	}
	return result;
}

</script>
