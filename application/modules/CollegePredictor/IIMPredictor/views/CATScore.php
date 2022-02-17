 <div class="prsnl-dtls initial_hide" id="catScoreStep" >
                               <section class="prsnl-cont clearfix">
                                   <article class="prsnl-box">
                                       <div class="dtls">
                                          <div class="ph">
                                             
                                             <div class="academic-dtls" id="catScorefields">
                                              <form class="cat-form" href="#" name="">
                                                <ul class="aa">
                                                 <li>
                                                   <div class="cat-flds">
                                                   <p class="score-head">Verbal and Reading Comprehension</p>
                                                      <!-- <input type="text" name="text" class="cat-txt-fld" placeholder="Marks"> -->
                                                       <input type="text" class="cat-txt-fld" id="VRC_Percentile" maxlimit="100" value="<?php if($userData['VRC_Percentile']){ echo $userData['VRC_Percentile']; } ?>" placeholder="VRC %ile" name="VRC_Percentile" default="VRC percentile" mandatory="1" fieldtype="enter" autocomplete="off">
                                                        <div class="errorMessage" id="VRC_Percentile_error">

                                                   </div>
                                                 </li>
                                                
                                                 <li>
                                                   <div class="cat-flds">
                                                    <p class="score-head">Data Interpretation and Logical Reasoning </p>
                                                      <!-- <input type="text" name="text" class="cat-txt-fld" placeholder="Marks"> -->
                                                      <input type="text" class="cat-txt-fld" id="DILR_Percentile"  maxlimit="100" value="<?php if($userData['DILR_Percentile']){ echo $userData['DILR_Percentile']; } ?>" placeholder="DILR %ile" name="DILR_Percentile" default="DILR percentile" mandatory="1" fieldtype="enter" autocomplete="off">
                                                      <div class="errorMessage" id="DILR_Percentile_error">
                                                      
                                                   </div>
                                                 </li>
                                                 <li>
                                                   <div class="cat-flds">
                                                    <p class="score-head">Quantitative Aptitude</p>
                                                      <!-- <input type="text" name="text" class="cat-txt-fld" placeholder="Marks"> -->
                                                       <input type="text" class="cat-txt-fld" id="QA_Percentile" class="txt-fld" maxlimit="100" value="<?php if($userData['QA_Percentile']){ echo $userData['QA_Percentile']; } ?>" placeholder="QA %ile" name="QA_Percentile" default="QA percentile" mandatory="1" fieldtype="enter" autocomplete="off">
                                                        <div class="errorMessage" id="QA_Percentile_error">

                                                   </div>
                                                 </li>
                                                  <li>
                                                   <div class="cat-flds">
                                                     <p class="score-head">Overall CAT</p>
                                                      
                                                      <div class="l-t">
                                                         <input type="text" id="cat_total" class="cat-txt-fld lft" maxlimit="300" value="<?php if($userData['cat_total']){ echo $userData['cat_total']; } ?>" placeholder="Total Marks" name="cat_total" default="marks" mandatory="1" fieldtype="enter" autocomplete="off">
                                                         <div class="errorMessage" id="cat_total_error"></div>
                                                      </div>

                                                      <div class="r-t">
                                                        <input type="text" class="cat-txt-fld lft" id="cat_percentile" maxlimit="100" value="<?php if($userData['cat_percentile']){ echo $userData['cat_percentile']; } ?>" placeholder="Total %ile" name="cat_percentile" default="total %ile" mandatory="1" fieldtype="enter" autocomplete="off">
                                                         <div class="errorMessage" id="cat_percentile_error"></div>
                                                       </div>  

                                                   </div>
                                                 </li>
                                                 <li>
                                                        <div class="cat-btn-col">
                                                           <input type="button" class="btn-bck button button--secondary" value="Previous" id="step3Back" ga-attr = "STEP3_PREV"/>
                                                           <input type="hidden" id="isCatdataSkipped" value="1" />
                                                         
                                                          <input type="button" class="secondary button button--orange" name="next" value="Predict Now" id="catScoreStepSubmit" ga-attr = "PREDICT_NOW" >
                                                           <!-- <input type="button" class="secondary nxt-btn getEligibilityResult" name="next" value="Skip this step" id="catScoreStepSubmit" > -->
                                                            <p class="clr"></p>
                                                      </div> 
                                                 	  </li>
                                                 
                                                  </ul>
                                             
                                             </form>
                                              
                                             </div>
                                             
                                             
                                                   
                                          </div>
                                          
                                         
                                          
                                          
                                       </div>
                                   </article>
                               </section>
                              
                              </div>
