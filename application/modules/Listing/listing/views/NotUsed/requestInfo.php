<!--Right_Form-->
<?php
/*echo "<pre>";
print_r($validateuser);
echo "</pre>";
*/

?>

     <div class="w255">
         <div class="raised_pink"> 
             <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
             <div class="boxcontent_pink">
             <?php

             $url = site_url('listing/Listing/requestInfo');
             echo $this->ajax->form_remote_tag( array('url'=> $url,'success' => 'javascript:updateRequestInfo(request.responseText);','name' => 'reqInfo','id' => 'reqInfo'));  
             ?>
               <div class="h36 bgRequestInfo normaltxt_11p_blk bld fontSize_14p"><img src="/public/images/request_Free_Info.gif" width="57" height="36" align="absmiddle" />Request Free Info</div>
               <div class="mar_left_10p">
                   <div class="lineSpace_12">&nbsp;</div>
                   <?php 
                     if(!isset($validateuser[0])){ 
                     ?>

                   <div class="normaltxt_11p_blk fontSize_11p">
                   Enter your information below and our representative will contact you:<br/>
                   (Already have Shiksha account then <a href="#" class="fontSize_11p" onClick="showuserLoginOverLay('myShiksha'); return false;"> Sign In</a> )

                   </div>
                   <?php }elseif(!isset($validateuser[0]['mobile']) || $validateuser[0]['mobile'] ==0 ) { ?>

                   <div class="normaltxt_11p_blk fontSize_11p">
                   Enter your information below and our representative will contact you:
                   </div>
<?php  } ?>

                      <input type="hidden" name="listing_type" id="listing_type"  value="<?php echo $listing_type; ?>"/>
                      <input type="hidden" name="listing_type_id" id="listing_type_id"  value="<?php echo $type_id; ?>"/>
                   <?php
                     if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ 
                     ?>

<?php 
}else{ 
 ?>
                   <div class="lineSpace_12">&nbsp;</div>
                   <div class="normaltxt_11p_blk">Name:<span class="redcolor">*</span></div>
                   <div class="lineSpace_2">&nbsp;</div>
<?php } ?>
                   <div class="normaltxt_11p_blk">
                   <?php
                     if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ 
                     ?>

<!--                      <input type="text" name="reqInfoDispName" id="reqInfoDispName" disabled value="<?php echo $validateuser[0]['displayname']; ?>"/>-->

                     <?php 
                     }else{
                     ?>

                      <input type="text" name="reqInfoDispName" id="reqInfoDispName"  value="" maxlength = "25" minlength = "3" validate="validateDisplayName"  required="true" />

                     <?php } ?>
                   </div>
        			<div class="row errorPlace">
        				<div class="r2 errorMsg" id="reqInfoDispName_error" ></div>
        				<div class="clear_L"></div>
        			</div>

                   <?php
                     if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ 
                     ?>

<?php 
}else{ 
 ?>

                   <div class="lineSpace_13">&nbsp;</div>
                   <div class="normaltxt_11p_blk">Email Address:<span class="redcolor">*</span></div>
                   <?php } ?>
                   <div class="lineSpace_2">&nbsp;</div>
                   <div class="normaltxt_11p_blk">
                   <?php
                   if(isset($validateuser[0]) && isset($validateuser[0]['cookiestr'])){ ?>

<!--                      <input type="text" name="reqInfoEmail" id="reqInfoEmail" disabled value="<?php $emailArr = explode("|",$validateuser[0]['cookiestr']); echo $emailArr[0]; ?>" required="true" />-->

                     <?php 
                     }else{
                     ?>

                      <input type="text" name="reqInfoEmail" id="reqInfoEmail"  value=""  maxlength = "125" validate = "validateEmail" required="true" />

                     <?php } ?>

                   </div>
        			<div class="row errorPlace">
        				<div class="r2 errorMsg" id="reqInfoEmail_error" ></div>
        				<div class="clear_L"></div>
        			</div>


                   <div class="lineSpace_13">&nbsp;</div>
                   <div class="normaltxt_11p_blk">Contact Number:<span class="redcolor">*</span></div>
                   <div class="lineSpace_2">&nbsp;</div>
                   <div class="normaltxt_11p_blk">
                    <?php
                     if(isset($validateuser[0]) && isset($validateuser[0]['mobile']) && $validateuser[0]['mobile'] != 0){ ?>

                      <input type="text" name="reqInfoPhNumber" id="reqInfoPhNumber" value="<?php echo $validateuser[0]['mobile']; ?>" maxlength = "13" minlength = "5" validate = "validateInteger" required="true" />


                     <?php 
                     }else{
                     ?>

                      <input type="text" name="reqInfoPhNumber" id="reqInfoPhNumber"  value=""  maxlength = "13" minlength = "5" validate = "validateInteger" required="true" />

                     <?php } ?>
                   </div>
        			<div class="row errorPlace">
        				<div class="r2 errorMsg" id="reqInfoPhNumber_error" ></div>
        				<div class="clear_L"></div>
        			</div>
                   <div class="lineSpace_13">&nbsp;</div>
                   <div class="normaltxt_11p_blk fontSize_10p lineSpace_15"><input type="checkbox" name="cAgree" id="cAgree" /> I agree to the Shiksha.com <a href="#">terms of<br />services</a>, <a href="#">privacy policy</a> and to receive email and phone support from Shiksha.</div>
                   <div class="clear_L"></div>
        			<div class="row errorPlace">
        				<div class="r2 errorMsg" id="cAgree_error" ></div>
        				<div class="clear_L"></div>
        			</div>

                   <div class="lineSpace_13">&nbsp;</div>
                   <div class="lineSpace_5">&nbsp;</div>
                   
				   <div>
					   <button value="" type="submit" onClick="return sendReqInfo(this.form);" >
							<div class="shikshaEnabledBtn_L">
								<span class="shikshaEnabledBtn_R">Request&nbsp;Info</span>
							</div>
					   </button>
                   </div>
                   <div class="lineSpace_10">&nbsp;</div>

               </div>
               </form>
            </div>
            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
        <div class="lineSpace_10">&nbsp;</div>
         <div class="raised_pink">
              <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
              <div class="boxcontent_pink">
                <div class="mar_full_10p">
                   <div class="normaltxt_11p_blk"> You can subscribe to alerts.</div>
                   <div class="lineSpace_10">&nbsp;</div>
                   <div class="row">
                   <div class="buttr3">
                        <button class="btn-submit7 w3" value="" type="button" onclick="javascript:<?php echo $subscribeAction; ?>" >
                        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Subscribe</p></div>
                        </button>
                   </div>                                                                                                                                                       <div class="clear_L"></div>
                   </div>
                </div>
              </div>
              <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
         </div>
        <div class="lineSpace_10">&nbsp;</div>

    </div>

    <script>

    function sendReqInfo(objForm){
        var flag = validateFields(objForm);  
        if(flag != true){
            return false;
        }
        else{
            var checkboxAgree = document.getElementById('cAgree');
            if(checkboxAgree.checked != true)
            { 
				document.getElementById('cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('cAgree_error').parentNode.style.display = 'inline';
                    return false;
            }
            else {
				document.getElementById('cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('cAgree_error').parentNode.style.display = 'none';
                    return true;
            }
        }
    }

function updateRequestInfo(responseText)
{ 
    if((trim(responseText) == 'both') || (trim(responseText) == 'email') || (trim(responseText) == 'false')){
				document.getElementById('reqInfoEmail_error').innerHTML = 'Email Already exists !!';
                document.getElementById('reqInfoEmail_error').parentNode.style.display = 'inline';
    }
    else{
        document.getElementById('right_Form').innerHTML = responseText;
    }
}



    </script>
<?php 
$bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'SIDE');
	                $this->load->view('common/banner',$bannerProperties);
                    ?>

<!--End_Right_From-->
