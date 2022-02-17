<?php
   $headerComponents = array(
      //'css'   =>  array('header','raised_all','mainStyle'),
      'css' => array('static'),
	  'js'    =>  array('common'),
      'title' =>  'Call Counselor',
      'tabname' => 'all',
      'taburl' =>  site_url(),
      'metaKeywords'  =>'Some Meta Keywords',
      'product' => 'inbox',
      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
      'callShiksha'=>1
   );
   $this->load->view('common/homepage', $headerComponents);
?>
<!--End_GreenGradient-->
<div style="margin:0 20px" class="normaltxt_11p_blk">
   <div style="line-height:16px">&nbsp;</div>
   <div class="OrgangeFont fontSize_18p bld">Have Question For Higher Education in India or Abroad? </div>
   <div style="line-height:16px">&nbsp;</div>
   <div class="fontSize_13p bld">We have a team of experts to answer your queries</div>
   <div class="lineSpace_10">&nbsp;</div>
   <div class="fontSize_13p bld">Call us for <span class="OrgangeFont">free</span> education counselling now <span class="OrgangeFont">+91-0120-3082000</span> or Let our shiksha counselor contact you</div>
   <div class="lineSpace_10">&nbsp;</div>
   <div style="line-height:33px">&nbsp;</div>
   <div style="width:720px">
      <div class="raised_pink"> 
	 <b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#FFF3E7"></b><b class="b4" style="background-color:#FFF3E7"></b>
	 <div class="boxcontent_pink" style="height:230px">
	    <div style="margin-left:220px" id="contactcallcenter">
	       <form id="callform" onsubmit="new Ajax.Updater('contactcallcenter','callSubmit',{ parameters:Form.serialize(this)}); return false;" method="post" >
	       <div class="lineSpace_5">&nbsp;</div>
	       <div class="OrgangeFont fontSize_13p bld">Please ensure that you add correct contact details</div>
	       <div class="lineSpace_10">&nbsp;</div>
	       <div class="row">
		  <div style="float:left; width:225px"><span class="bld">First Name</span><br />
		     <input type="text" size="27" name="firstname" id="firstname" validate="validateStr" minlength="3" maxlength="25" required="true" caption="First Name" value="<?php if (isset($validateuser[0]['firstname'])) echo $validateuser[0]['firstname'];?>" /><br/>
		     <div>
		        <span id="firstname_error" class="errorMsg"></span>
		     </div>
		  </div>

		  <div style="float:left; width:225px"><span class="bld">Last Name</span><br />
		     <input type="text" size="27" name="lastname" id="lastname" validate="validateStr" maxlength="25" caption="Last Name" value="<?php if(isset($validateuser[0]['lastname'])) echo $validateuser[0]['lastname'];?>"/><br/>
		     <div style="float:left; width:225px;">
			<span id="lastname_error" class="errorMsg"></span>
		     </div>
		  </div>
		  <div class="clear_L"></div>
	       </div>
	       <div class="lineSpace_15">&nbsp;</div>
	       <div class="row">
		  <div style="float:left; width:225px"><span class="bld">Contact Number:</span><br />
		     <input type="text" size="27" name="contactno" id="contactno" validate="validateInteger" maxlength="13" minlength="5" required="true" caption="Contact Number" value="<?php if(isset($validateuser[0]['mobile'])) echo $validateuser[0]['mobile'];?>"/><br/>
		     <div>
			<span id="contactno_error" class="errorMsg"></span>
		     </div>
		  </div>
		  <div style="float:left; width:225px"><span class="bld">E-mail Address:</span><br />
		     <input type="text" size="27" name="email" id="email" validate="validateEmail" required="true" caption="Email" value="<?php if(isset($email)) echo $email; ?>"/><br/>
		     <div >
			<span id="email_error" class="errorMsg"></span>
		     </div>
		  </div>
		  <div class="clear_L"></div>
	       </div>
	       <div class="lineSpace_15">&nbsp;</div>
	       <div class="row">
		  <div style="float:left;"><input type="checkbox"  style="position:relative; top:-3px" name="terms" id="terms" /></div>
		  <div style="float:left;">I agree to the Shiksha.com <a href="#">terms of service, privacy policy</a> and to receive<br /> email and phone support from shiksha.</div>
		  <div class="clear_L"></div>
	       </div>
	       <div class="row">
		  <div style="float:left">
		     <span id="terms_error" class="errorMsg"></span>
		  </div>
	       </div>

	       <div class="clear_L lineSpace_15">&nbsp;</div>
	       <div class="row">
		  <div style="width:140px; float:left">&nbsp;</div>
		  <div class="buttr3">
		     <button class="btn-submit7 w9" type="submit" onClick="return validateCallForm(this.form);">
			<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Submit</p></div>
		     </button>
		  </div>
		  <div class="clear_L"></div>
	       </div>
		</form>

	    </div>
	 </div>
	 <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
      </div>
      <div style="position: relative; top: -251px; left:20px;width:151px"><img src="/public/images/girl.gif" /></div>
   </div>


</div>
<?php $this->load->view('common/footer'); ?>
<script>
   fillProfaneWordsBag();

   function validateCallForm(objForm)
   {
	 var f_flag =  validateFields(objForm);
	 if ($('terms').checked == false) 
	 {
	       $('terms_error').innerHTML = "Accept Terms of Service";
	       $('terms_error').parentNode.style.display = "";
	       f_flag = false;
	 }
	 else
	 {
	       $('terms_error').innerHTML = "";
	       $('terms_error').parentNode.style.display = "none";
	 }
	 <?php if(!is_array($validateuser)): ?>
	 if (f_flag)
	 {
	       var url = "/user/Userregistration/checkAvailability/"+$('email').value+"/email";
	       new Ajax.Request (url, { 
		     		onSuccess : function(res) 
				{ 
				      if (res.responseText == "1") 
				      {
					    $('email_error').innerHTML= "Email Already Exists!!!";
					    $('email_error').parentNode.style.display = "";
				      }
				      else
				      {
					    new Ajax.Updater('contactcallcenter','callSubmit',{ parameters:Form.serialize($('callform'))});
					    return false;
				      }

			       	} 
			  });
	 }
	 return false;
	 <?php else: ?>
	 return f_flag;
	 <?php endif; ?>


   }

</script>
