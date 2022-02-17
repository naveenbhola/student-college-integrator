<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}
?>
<div style="width: 600px;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
<div class="orangeColor fontSize_14p bld" style="width: 530px;"><span><b>Add new Widget</b></span><br/>
<div class="grayLine_1" style="margin-top:5px;">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
</div>
<form <?php if($edit == 0 || ($edit == 1) && empty($carousle_id)):?>onsubmit="if('<?php echo count($carousel_array)?>' == 4) {alert('All slots are full, please delete a live slot in order to create a new slide.'); return false;}if(!validateCarouselDesc()) {return false;}"<?php else:?> 
onsubmit="if(!validateCarouselDesc()) {return false;}" <?php endif;?>id="content_form" action="/homepage/HomepageSlideshowWidget/index/<?php echo $edit;?>/<?php echo $carousle_id;?>"  enctype="multipart/form-data" method="post">
   <div>
    <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Title : </div>
    </div>
    <div style="margin-left: 177px;padding-bottom:5px;">
        <div>
        <input caption="header_text_error" type="text" value="<?php echo preg_replace('/[\\\]/','',$carousel_title);?>" class="txt_1" name="carousel_title" style="width: 320px;" id="carousel_title" size="30" maxlength="30" onblur="validateField(this)" caption="carousel_title_error"/>
        </div>
        <div style="width:300; valign:top;font-size:10px;"><br>
			Max 30 characters</div>
        <div>
            <div style="" id="header_text_error" class="errorMsg"></div>
        </div>
    </div>
    <div style="width: 175px; line-height: 18px;" class="float_L">
	<div style="padding-right: 5px;" class="txt_align_l">Photo:</div>
	</div>
	<div style="margin-left: 177px;">
	<div>
	<input caption="banner_error"onblur="validateField(this)" type="file" value="<?php echo $carousel_photo_url;?>" class="txt_1" name="myImage[]" id="myImage" size="30" caption="banner_error" /></div>
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
    <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Description : </div>
    </div>
    <div style="margin-left: 150px;padding-bottom:5px;">
        <div>
       <textarea  caption="carousel_description_error" name="carousel_description" id="carousel_description" rows="20" cols="50" caption="carousel_description_error" onblur="if(trim(this.value).length>135) {$('carousel_description_error').innerHTML ='Please enter less than or equal to 135 characters'; return false;}validateField(this)" style="margin-left:3px;"><?php echo preg_replace('/[\\\]/','',$carousel_description); ?></textarea>
         </div>
         <div>
            <div style="padding-left:25px;font-size:10px;" id="carousel_description_error" class="errorMsg"></div>
        </div>
        <div style="padding-left:25px;font-size:10px;">
			   Max 135 characters</div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
    <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">URL : </div>
    </div>
    <div style="margin-left: 177px;padding-bottom:5px;">
        <div>
        <input caption="form_heading_error"type="text" value="<?php echo $carousel__destination_url;?>" class="txt_1" name="carousel__destination_url" style="width: 325px;" id="carousel__destination_url" size="30"  maxlength="255" caption="carousel__destination_url_error" onblur="validateField(this)"/>
        </div>
        <div style="font-size:10px;"><br>
			Max 255 characters</div>
		<div>
            <div style="" id="form_heading_error" class="errorMsg"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
    
	<div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;" class="txt_align_l">Open in new window : </div>
    </div>
<div style="margin-left: 177px;padding-bottom:5px;">
        <div>
        <input <?php if($carousel_open_new_window == 'YES') { echo "checked";}?> type="checkbox" 
value="<?php if(!empty($carousel_open_new_window)) {echo $carousel_open_new_window;} else {echo 'NO';}?>" onclick="if(this.checked == true) {this.value = 'YES'} else {this.value = 'NO'};"class="txt_1" name="carousel_open_new_window" id="carousel_open_new_window"/>
        </div>
    </div>
 <div class="clear_L withClear">&nbsp;</div>
  <div class="clear_L withClear">&nbsp;</div>
   <div class="clear_L withClear">&nbsp;</div>
<button type="submit" value=""class="btn-submit5 w12">
<div class="btn-submit5">
<p class="btn-submit6">Save</p>
</div>
</button>
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
<div id="Carousel_container">
<div><div class="orangeColor fontSize_14p bld" style="width: 530px;"><strong style="font-size:16px;">Previously Added Widget</strong></div></div>
<div class="grayLine_1" style="margin-top:5px;">&nbsp;</div>
<div class="clear_L withClear">&nbsp;</div>
<div class="clear_L withClear">&nbsp;</div>
<div class="clear_L withClear">&nbsp;</div>
		<table width="100%" cellpadding="2">
<?php $count= 0;foreach($carousel_array as $carousel_ele):
if(strpos($carousel_ele['carousel__destination_url'], "http://") === 0) {$url = $carousel_ele['carousel__destination_url'];} else {$url= "http://".$carousel_ele['carousel__destination_url'];}?>		
            <thead>
            <tr><td colspan="5" style="border:none;"><strong><?php echo "Slide ".++$count;?></strong></td></tr>
                <tr>
                        <td class="border" width="80" valign="top"><strong>Slide Position</strong></td>
                        <td class="border" width="150" valign="top"><strong>Title</strong></td>
                        <td class="border" width="180" valign="top"><strong>Photo</strong></td>
                        <td class="border" width="100" valign="top"><strong>Description</strong></td>
                        <td class="border" id='update_score' width="85" valign="top"><strong>URL</strong></td>
                    </tr>
                </thead>
                <tr>
                <td valign="top" class="border"><select onchange="reorderWidgets(this);" attr="<?php echo $carousel_ele['carousel_order'];?>"><option value="" selected>Select</option><?php echo $string_opt;?></select></td>
                    <td class="border" valign="top"><?php echo preg_replace("/[\\\]/",'',$carousel_ele['carousel_title']);?></td>
                    <td class="border" valign="top"><img width="300" height="250" src="<?php echo $carousel_ele['carousel_photo_url'];?>"/></td>
                    <td class="border" valign="top"><?php echo wordwrap(preg_replace("/[\\\]/",'',$carousel_ele['carousel_description']),50,"<br />\n");?></td>
                    <td class="border" valign="top" align="center"><a <?php if($carousel_ele['carousel_open_new_window'] == 'YES') {echo "target='blank'";}?>href="<?php echo $url;?>"><?php echo $url;?></a><br/>Open in new window:<strong><?php echo $carousel_ele['carousel_open_new_window'];?></strong></td>
                </tr>
                <tr><td><input type="button" value="Edit" onclick="window.location='/homepage/HomepageSlideshowWidget/index/1/<?php echo $carousel_ele['carousel_id'];?>'"/></td><td><input type="button" value="Delete" onclick="window.location='/homepage/HomepageSlideshowWidget/deleteCarousel/<?php echo $carousel_ele['carousel_id'];?>/<?php echo $carousel_ele['carousel_order'];?>'"/>
                <?php endforeach;?>
            </table>
     </div>
     <?php endif;?>       
</div>
<style>
.border {
	border:1px solid grey; 
	border-collapse:collapse;
}
table { 
border-collapse: collapse 

}
</style>
<?php $this->load->view('common/footer');?>
<script>
function validateField(element) {
	try {
	var value = element.value.replace(/^\s*|\s*$/g,'');
	if(!value) {
		if(element.name=='myImage[]') {
 		     document.getElementById(element.getAttribute('caption')).innerHTML = "Please "+element.name.replace('myImage[]','upload carousel image');
		} else {
			document.getElementById(element.getAttribute('caption')).innerHTML = "Please enter "+element.name.replace('_',' ');
		}
		return false; 
	} else {
		document.getElementById(element.getAttribute('caption')).innerHTML = "";
		return true;
	 
	}
	} catch(ex) {
		//
	}
}
function reorderWidgets(element) {
	try {
	var new_value = element.value;
	var org_attr = element.getAttribute('attr');
	if(new_value && new_value!=org_attr) {
		var obj = eval("eval("+carousle_order_array+")");
		window.location="/homepage/HomepageSlideshowWidget/reorderCarousel/"+org_attr+"/"+obj[org_attr]+"/"+new_value+"/"+obj[new_value];
	}
	} catch(ex) {
		//
	}
}
function validateCarouselDesc() {
var length1 = trim($('carousel_description').value);
if(length1.length>135) { return false} else {return true;}
}
</script>
