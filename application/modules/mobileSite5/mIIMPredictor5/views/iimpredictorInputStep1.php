<div data-role="content" id="iimpredictorInputStep1" name="personalDetails" data-enhance="false" class="<?php echo ($isOutputPage) ? 'initial_hide' : '';?>">

  <div class="drp-dwn-layer" id="userCategory">
  <div class="drp-cont">
   <ul class="drpdwn-layer-ul">
      <li><a class="userCategory" href="javascript:void(0);" id="predictor_userCategory_General" >General</a></li>    
      <li><a class="userCategory" href="javascript:void(0);" id="predictor_userCategory_NC-OBC" >NC-OBC</a></li>    
      <li><a class="userCategory" href="javascript:void(0);" id="predictor_userCategory_SC"  >SC</a></li>    
      <li><a class="userCategory" href="javascript:void(0);" id="predictor_userCategory_ST"  >ST</a></li>    
      <li><a class="userCategory" href="javascript:void(0);" id="predictor_userCategory_DA"  >Differently Abled (DA)</a></li>    
   </ul>
 </div>
</div>

  <div class="prsnl-dtls">
   <section class="prsnl-cont clearfix">
       <article class="prsnl-box">
           <div class="dtls">
              <!-- <span class="ph">
                 <h3>Personal Details</h3>	
                 <p>Did you know: IIM Rohtak gives 12.5% extra marks to females for gender diversity.</p>
              </span> -->
              
              <div class="choose-gender">
                <p>Gender</p>
                 
              <!--    <span class="gender-btns">
                   <label><i class="x-l"></i><a href="javascript:void(0);" class="g-btn <?php if($userData['gender'] == 'male'){ echo 'active'; }else if(empty($userData)){ echo 'active'; } ?>" id="gender_m" display="male" >Male</a></label>
                   <label><a href="javascript:void(0);" class="g-btn <?php if($userData['gender'] == 'female'){ echo 'active'; } ?>" id="gender_f" display="female" >Female</a></label>
                   <label><i class="y-l"></i><a href="javascript:void(0);" class="g-btn <?php if($userData['gender'] == 'transgender'){ echo 'active'; } ?>" id="gender_tr" display="transgender" >Transgender</a></label>
                 </span> -->
                      <span class="gender-btns">
                            <span class="radio_p">
                                  <input type="radio" name="gender" id="1" data-no-use="1" <?php if($userData['gender'] == 'male'){ echo 'checked'; }else if(empty($userData)){ echo 'checked'; } ?> value="male">
                                  <label class="frst-label" for="1">Male</label>
                            </span>
                            <span class="radio_p">
                                  <input type="radio" name="gender" id="2" data-no-use="1" <?php if($userData['gender'] == 'female'){ echo 'checked'; } ?> value="female">
                                  <label for="2">Female</label>
                            </span>
                            <span class="radio_p">
                                  <input type="radio" name="gender" id="3" data-no-use="1" <?php if($userData['gender'] == 'transgender'){ echo 'checked'; } ?> value="transgender">
                                  <label for="3">Transgender</label>
                            </span>
                      </span>

                 
                 <section class="prsnl-drpdwn">
                   <p>Reservation Category</p>

                      <div class="custom-drp">
                          <p class="custm-srch" id="userCategoryholder"><?php if(!empty($userData['category'])){ echo $userData['category']; }else{ echo 'Select'; } ?> <i class="pointerDown"></i></p>
                      </div>
                      <div class="errorMessage" id="predictor_userCategory_error"></div>
                 </section>
                 
                 <!-- <section class="prsn-nxt">
                   <input type="button" name="next" ga-attr="PERSONAL_DETAILS_CONTINUE" value="Continue" class="nxt-btn" id="step1Button" />
                 </section> -->
                  <div class="clear_s">
                    <a class="predict button button--orange" href="javascript:void(0);" ga-attr="PERSONAL_DETAILS_CONTINUE" id="step1Button">Next</a>
                 </div>
                
              </div>
              
           </div>
       </article>
   </section>
      
<!-- <input type="hidden" name="gender" default="gender" id="predictor_gender" mandatory="1" fieldtype="select" value="<?php if(!empty($userData['gender'])){ echo $userData['gender']; }else{ echo 'male'; } ?>"/> -->
<input type="hidden" name="category" default="reservation category" fieldtype="select" mandatory="1" id="predictor_userCategory" value="<?php if(!empty($userData['category'])){ echo $userData['category']; }?>"/>

</div>
<?php $this->load->view('IIMPredictor/ShowInTable');?>
</div>
