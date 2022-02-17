<?php
$this->load->view('enterprise/subtabsStudyAbroadWidgets');
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}
if(!empty($carousle_id)) $edit=1;
?>
<div style="width: 600px; padding: 10px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;"><?php echo $main_suc_message;?></div>
<div
	style="width: 600px; padding: 10px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
<div class="orangeColor fontSize_14p bld" style="width: 530px;"><span><b>Registration Banner:</b></span><br />
<div class="grayLine_1" style="margin-top: 5px;">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
</div>
</div>
<form onsubmit="return validateAllFields();" id="content_form"
	action="/enterprise/Enterprise/index/53/5/<?php echo $edit;?>"
	enctype="multipart/form-data" method="post">
<input type="hidden" value="<?php echo $carousle_id;?>" name="carousle_id"/>	
<div style="margin-left: 10px;">
<div style="width: 175px; line-height: 18px;" class="float_L">
<div style="padding-right: 5px;" class="txt_align_l">Upload banner for
registration:</div>
</div>
<div style="margin-left: 177px;">
<div><input title="the Banner" caption="banner_error"
	onblur="validateField(this)" type="file"
	value="<?php echo $carousel_photo_url;?>" class="txt_1"
	name="myImage[]" id="myImage" size="30" caption="banner_error" /></div>
<div>
<div>
<div style="" id="banner_error" class="errorMsg"></div>
</div>
<div style="width: 300; valign: top; font-size: 10px;"><br>
Supported image formats for banner: JPG, GIF, PNG</div>
</div>
<div><?php if(!empty($error_message)):?>
<div id="upload_error" class="errorMsg"><?php echo $error_message?></div>
	<?php else:?>
<div id="upload_error" class="errorMsg" style="color: green !important;"><?php echo $success_message?></div>
	<?php endif;?></div>
</div>
<div class="clear_L withClear">&nbsp;</div>
<div style="width: 175px; line-height: 18px;" class="float_L">
<div style="padding-right: 5px;" class="txt_align_l">Enter title for
registration layer:</div>
</div>
<div style="margin-left: 177px; padding-bottom: 5px;">
<div><input title="the Title" caption="header_text_error" type="text"
	value="<?php echo preg_replace('/[\\\]/','',$carousel_title);?>"
	class="txt_1" name="carousel_title" style="width: 320px;"
	id="carousel_title" size="30" maxlength="50"
	onblur="validateField(this)" caption="carousel_title_error" /></div>
<div style="width: 300; valign: top; font-size: 10px;"><br>
Max 50 characters</div>
<div>
<div style="" id="header_text_error" class="errorMsg"></div>
</div>
</div>
<div class="clear_L withClear">&nbsp;</div>
<div style="width: 175px; line-height: 18px;" class="float_L">
<div style="padding-right: 5px;" class="txt_align_l">Enter message for
registration layer :</div>
</div>
<div style="margin-left: 150px; padding-bottom: 5px;">
<div><input title="the Message" caption="carousel_description_error"
	name="carousel_description" id="carousel_description"
	caption="carousel_description_error"
	 maxlength="160"
	value="<?php echo preg_replace('/[\\\]/','',$carousel_description); ?>"
	style="margin-left: 3px;" size="30"/></div>
<div>
<div style="padding-left: 25px; font-size: 10px;"
	id="carousel_description_error" class="errorMsg"></div>
</div>
<div style="padding-left: 25px; font-size: 10px;">Max 160 characters</div>
</div>
<div class="clear_L withClear">&nbsp;</div>
<button type="submit" value="" class="btn-submit5 w12">
<div class="btn-submit5">
<p class="btn-submit6">Save</p>
</div>
</button>
</div>
</form>
<div class="clear_L withClear">&nbsp;</div>
<div class="clear_L withClear">&nbsp;</div>
<div class="clear_L withClear">&nbsp;</div>
<div class="clear_L withClear">&nbsp;</div>
<div class="clear_L withClear">&nbsp;</div>
	<?php if(is_array($carousel_array) && count($carousel_array)>0):
	?>
	<?php $string_opt= "";foreach($carousel_array as $carousel_ob){
		$string_opt =$string_opt.'<option value="'.$carousel_ob[carousel_order].'">'.$carousel_ob[carousel_order]."</option>";
		$carousle_order[$carousel_ob['carousel_order']] = $carousel_ob['carousel_id'];
	}
	$carousle_order  = json_encode($carousle_order);
	?>
<script>
var carousle_order_array = '<?php echo $carousle_order;?>';
</script>
	<?php endif;?>
<style>
.border {
	border: 1px solid grey;
	border-collapse: collapse;
}

table {
	border-collapse: collapse
}
</style>
	<?php //$this->load->view('common/footer');?>
<script>
function validateAllFields() {
	var flag1= validateField($("myImage"));
	var flag2 = validateField($("carousel_title"));
	if(flag2 === false || flag1 === false) {
		return false;
	}
	return true;
}
function validateField(element) {
	try {
	var value = element.value.replace(/^\s*|\s*$/g,'');
	if(!value) {
		var title = element.getAttribute('title');
		document.getElementById(element.getAttribute('caption')).innerHTML = "Please specify "+title;
		return false; 
	} else {
		document.getElementById(element.getAttribute('caption')).innerHTML = "";
		return true;
	 
	}
	} catch(ex) {
		//
	}
}
</script>
