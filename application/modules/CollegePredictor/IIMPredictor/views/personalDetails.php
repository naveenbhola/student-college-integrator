<div id="iimpredictorInputStep1" name="personalDetails" class="<?php echo ($isOutputPage) ? 'initial_hide' : '';?>">
  <div class="prsnl-dtls">
     <section class="prsnl-cont clearfix">
         <aside class="prsnl-box">
             <div class="dtls">

                 <div class="choose-gender">
                  
                      <div class="full-width">
                        <section class="gender-btns">
                           <p class="sub-titls max-btm">Gender</p>
                          <!--  <label class="frst-label" for="1"><input type="radio" name="gender" id="1"><i class="x-l"></i><a href="javascript:void(0);" class="g-btn <?php if($userData['gender'] == 'male'){ echo 'active1'; }else if(empty($userData)){ echo 'active1'; } ?>" id="gender_m" display="male" >Male</a></label>
                           <label for="2"><input type="radio" name="gender" id="2"><i class="y-l"></i><a href="javascript:void(0);" class="g-btn <?php if($userData['gender'] == 'female'){ echo 'active1'; } ?>" id="gender_f" display="female" >Female</a></label>
                           <label for="3"><input type="radio" name="gender" id="3"><i class="y-l"></i><a href="javascript:void(0);" class="g-btn <?php if($userData['gender'] == 'transgender'){ echo 'active1'; } ?>" id="gender_tr" display="transgender" >Transgender</a></label>
                            -->

                           <p class="radio_p"><input type="radio" name="gender" id="1" data-no-use="1" <?php if($userData['gender'] == 'male'){ echo 'checked'; }else if(empty($userData)){ echo 'checked'; } ?> value="male"><label class="frst-label" for="1">Male</label></p>
                           <p class="radio_p"><input type="radio" name="gender" id="2" data-no-use="1" <?php if($userData['gender'] == 'female'){ echo 'checked'; }  ?> value="female"><label for="2">Female</label></p>
                           <p class="radio_p"><input type="radio" name="gender" id="3" data-no-use="1" <?php if($userData['gender'] == 'transgender'){ echo 'checked'; } ?> value="transgender"><label for="3">Transgender</label></p>
                        </section>
                        <p class="clr"></p>
                      </div>  
                       
                       <section class="prsnl-drpdwn">
                         <p class="sub-titls">Reservation Category</p>
                            <div class="predcit-drpdwn">
                                      <select class="custm-srch" name="category" default="reservation category" fieldtype="select" mandatory="1" id="predictor_userCategory"> 
                                        <option value="">Select</option>
                                        <option value="General" <?php if($userData['category'] == 'General') { echo 'selected'; } ?>>General </option>
                                        <option value="NC-OBC" <?php if($userData['category'] == 'NC-OBC') { echo 'selected'; } ?>>NC-OBC </option>
                                        <option value="SC" <?php if($userData['category'] == 'SC') { echo 'selected'; } ?>>SC </option>
                                        <option value="ST" <?php if($userData['category'] == 'ST') { echo 'selected'; } ?>>ST </option>
                                        <option value="DA" <?php if($userData['category'] == 'DA') { echo 'selected'; } ?>>Differently Abled (DA) </option>
                                      </select>
                                  <div class="errorMessage" id="predictor_userCategory_error"></div>
                            </div>
                       </section>
                       <p class="clr"></p>
                  </div>
                  
                <div class="prsn-nxt" ga-attr="PERSONAL_DETAILS_CONTINUE">
                 <input type="button" class="secondary button button--orange" value="Next" id="step1Button" />
                  <p class="clr"></p>
                </div>
                  
             </div>
              <p class="clr"></p>
         </aside>
     </section>
  </div>
<!--personal div ends--> 
<?php $this->load->view('ShowInTable');?>
</div>
