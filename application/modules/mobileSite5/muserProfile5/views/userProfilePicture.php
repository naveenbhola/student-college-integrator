<div 
	
	<?php if(!$publicProfile){ ?> id='profilePic' <?php }?> width="60" height="60" class="user-img">

	<?php if(empty($personalInfo['AvatarImageURL']))  {

		$intials = substr($personalInfo['FirstName'], 0,1);	
		if(ctype_alpha($intials)){
			$intials = strtoupper($intials);
		}

		?>
		<div id ='imageUploadId'>
		 <p class='imageInitials'><i class="profile-sprite <?php if(!$publicProfile){ ?> ic-icon-edit<?php }?>"></i> <?php echo $intials;?></p>
		</div>  

	<?php } else {?>
    	<img <?php if(!$publicProfile){ ?> id='myProfilePic' <?php }?> class="lazy" data-original="<?php echo MEDIA_SERVER.$personalInfo['AvatarImageURL']; ?>"  width="60" height="60" > 
   	<?php }?>
</div>



<div id="upload-layer" class="upload-layer " style="display:none;">
	<div class="upload-btns">
		<a id = 'profilePicLayer' class="view-picbtn" href="#">Choose Picture</a>
	</div>
</div>



	<div class="upload-layer" id="upload-imageMob" style="display:none">
	   <div class="upload-btns">
	       <input id="uploadMyImage" type="file" name="myImage[]" />	
	       <input id='submitUploadImage' type="submit"  style ="display:none" >
	   </div>
	</div>
