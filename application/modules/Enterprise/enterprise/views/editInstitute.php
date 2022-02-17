<!--Edit_college_course_form_start-->
<?php  
    $attribute = array('name' => 'instiListing','id'=> 'instiListing', 'method' => 'post');
    echo form_open_multipart('enterprise/Enterprise/updateCollegeCMS',$attribute); 
?>
<?php 
    $style = "display:block";
    global $formFlow;
    global $maxVideos;
    global $maxPhotos;
    global $maxDocs;
    global $featuredLogo;
    global $featuredPanel;

    $formFlow = 'edit';
    $maxVideos =3;
    $maxPhotos =3;
    $maxDocs =3;
    $featuredLogo = "Yes";
    $featuredPanel ="Yes";

?>
<script>
   var completeCategoryTree = eval(<?php echo $completeCategoryTree; ?>);
   var cal = new CalendarPopup("calendardiv");
   cal.offsetX = 20;
   cal.offsetY = 0;
</script>

<div style="display:none;">
<?php 
        $this->load->view('listing/packSelection');
    ?>
</div>

<input type="hidden" name="old_institute_id" value="<?php echo $institute_id; ?>" />
<input type="hidden" name="add_type" value="new" />
<input type="hidden" id="listingProdId" name="listingProdId" value="<?php echo $packType; ?>" />

<div class="lineSpace_20">&nbsp;</div>

<div id="college_details_main">
   <div id="college_details" style="<?php echo $style;?>">
      <?php $this->load->view('enterprise/cmsCollegeDetails'); ?>
   </div>
</div>

   <input type='hidden' name='c_media_content' value='1'/>
   <?php    $this->load->view('enterprise/cmsMediaContent'); ?>


<div class="lineSpace_13">&nbsp;</div>
<?php if ($usergroup != "cms"): ?>
<div class="row">
	 <div>
             <div>
                 <div class="r1 bld">Type the characters you see in picture:<span class="redcolor">*</span></div>
                 <div class="r2">
                     <img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&secvariable=seccode&randomkey=<?php echo rand(); ?>" id="topicCaptcha"/><br />
                     <input type="text" name="captcha_text" id="captcha_text" caption="Security Code" tip="secCode" >
                 </div>
                 <div class="clear_L"></div>
             </div>
	    <div class="row errorPlace">
	       <div class="r1">&nbsp;</div>
	       <div class="r2 errorMsg" id="captcha_text_error"></div>
	       <div class="clear_L"></div>
	    </div>
	 </div>
</div>
<div class="lineSpace_13">&nbsp;</div>
<?php endif; ?>

<input type="hidden" name="editCollege" value="1" />
   <div class="lineSpace_15">&nbsp;</div>
   <div style="display: inline; float:left; width:100%">
      <div class="buttr3">
         <button class="btn-submit7 w9" value="addCourse" type="button" onClick="validateCourseListing(this.form);">
            <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Update Institute</p></div>
         </button>
      </div>
<?php $redirectLocation = "/";
	if ( isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']!="") )
		$redirectLocation = $_SERVER['HTTP_REFERER'];
?>
      <div class="buttr2">
      <button class="btn-submit11 w4" value="cancel" type="button" onClick="location.replace('<?php echo $redirectLocation;?>');" >
            <div class="btn-submit11"><p class="btn-submit12">Cancel</p></div>
         </button>
      </div>
      <div class="clear_L"></div>
   </div>
   <div class="clear_L"></div>
</form>
<!--End_Course_Listing_Form-->
<script>
   addOnBlurValidate(document.getElementById('instiListing'));
   addOnFocusToopTip(document.getElementById('instiListing'));
   var formName = "instiListing";

   function validateCourseListing(objForm)
   {
         var flag = validateFields(objForm);
         if(flag==true)
         <?php if ($usergroup != "cms"){ ?>
         validateCaptchaForListing();
         <?php }else{ ?>
         objForm.submit();
         <?php } ?>

   }

   tinyMCE.init({ mode : "textareas", theme : "simple",editor_selector : "mceEditor", editor_deselector : "mceNoEditor"});

   <?php if($packType > 0){ ?>
   editPackSpecificChanges("<?php echo $listingType; ?>");
   <?php  }else{ ?>
   editPackSpecificChangesCMS("<?php echo $listingType; ?>");
   <?php  } ?>

   fillProfaneWordsBag();
</script>
