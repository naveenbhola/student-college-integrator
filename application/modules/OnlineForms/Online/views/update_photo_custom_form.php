<h3 class="upperCase"><?php if(!empty($profileImage)):?>Update Image <?php else:?>Add Image<?php endif;?></h3>
<div id="update_photo_from_custom_form">
<ul>
<?php
$showImage = true; 
if(!empty($profileImage)) {
$img  = getimagesize($profileImage);
	if(!empty($img)) {
		$showImage = true;
	}
}	
?>
	<?php if(!empty($profileImage) && $showImage):?>
    <li id="already_section_image">    
        <div style="padding-left:312px; float:left;margin-top: 21px;">
            <img width="195" height="192" src="<?php echo MEDIA_SERVER.$profileImage;?>" alt="Profile Pic" />
        </div>
        
        <div style="width:200px; float:left; padding:164px 0 0 10px">
            <input type="button" class="saveContButton" style="font-size:12px" value="Change Profile Picture" onclick="$('update_photo_from_custom_form_widget').style.display='block';$('profileImageValidAdditional').value='validate';$('already_section_image').style.display = 'none';">
        </div>
    </li>

<?php endif;?>	
<li <?php if(!empty($profileImage)  && $showImage) {echo "style=display:none;";}?> id="update_photo_from_custom_form_widget">
	<div class="additionalInfoLeftCol" style="width:100%;margin-top: 21px;">
	    <label style="float: left;padding: 3px 5px 0 0; text-align: right; width: 300px; font-size:14px"><?php if(empty($profileImage)){?>Upload Photo<span style="color: red">*</span> <?php }else{?>Update Photo<?php } ?>:</label>
        <div class="fieldBoxLarge" style="width:420px">
	    <input type='file' name='userApplicationfile[]' id='profileImage' caption="Image"  onmouseout="hidetip();"onmouseover="showTipOnline('Post your recent color photograph on white background here. The maximum image size allowed is 2 MB.',this);" size="30"/>
	    <input type='hidden' name='profileImageValid' value='extn:jpg,jpeg,png|size:5'>
	    &nbsp;<a id="imageGuideline" href="javascript:void(0)" class="imgGuideline" onmouseover="showTipOnline('- Image specifications: Passport size colour photograph (4.5 X 3.5 cm). <br>- The image size should not be more than 2 MB.',this);" onmouseout="hidetip();">View image guidelines</a><br />
	    <span class="imageSizeInfo">(Image dimention :4.5 X 3.5 cm, Image Size : Maximum 2 MB)</span>
	    <div style='display:none'><div class='errorMsg' id= 'profileImage_error'></div></div>
	    <input type='hidden' name='profileImageValidAdditional' value='<?php if(empty($profileImage)) {echo "validate";} else {echo "novalidate";}?>' id="profileImageValidAdditional">
        </div>
     </div>   
</li>
</ul>	
</div>	
<!-- <h3>&nbsp;</h3> -->
