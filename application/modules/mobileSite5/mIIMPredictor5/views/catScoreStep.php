  <div class="prsnl-dtls initial_hide" id="catScoreStep">
   <section class="prsnl-cont clearfix">
       <article class="prsnl-box">
           <div class="dtls">
              <div class="ph">
              <!--    <h3><?php if($this->input->get('modify') == 'yes'){ echo 'Modify'; }else{ echo'Enter'; }?> CAT Score / Expected Score</h3>	
                 <p>Did you know: Majority of top IIMs consider "total marks" and not "total %ile" for their calculations?</p> -->
             
                 <div class="academic-dtls">
                  <div class="cat-form" name="catScorefields" id="catScorefields">
                    <ul class="aa">
                     <!-- <li>
                       <div class="cat-flds">
                          <input type="text" name="text" class="txt-fld" placeholder="Verbal and Reading Comprehension Marks"/>
                       </div>
                     </li> -->
                     <li>
                       <div class="cat-flds">
                          <input type="number" step="any" class="txt-fld" id="VRC_Percentile" maxlimit="100" value="<?php if($userData['VRC_Percentile']){ echo $userData['VRC_Percentile']; } ?>" placeholder="VRC %ile" name="VRC_Percentile" default="VRC percentile" mandatory="1" fieldtype="enter" />
                          <div class="errorMessage" id="VRC_Percentile_error">
                          </div>
                       </div>
                     </li>
                     <li>
                       <div class="cat-flds">
                          <input type="number" step="any" id="DILR_Percentile" class="txt-fld" maxlimit="100" value="<?php if($userData['DILR_Percentile']){ echo $userData['DILR_Percentile']; } ?>" placeholder="DILR %ile" name="DILR_Percentile" default="DILR percentile" mandatory="1" fieldtype="enter" />
                          <div class="errorMessage" id="DILR_Percentile_error">
                          </div>
                       </div>
                     </li>
                    <!--  <li>
                       <div class="cat-flds">
                          <input type="text" name="text" class="txt-fld" placeholder="Quantitative Aptitude Marks"/>
                       </div>
                     </li> -->
                      <li>
                       <div class="cat-flds">
                          <input type="number" step="any" id="QA_Percentile" class="txt-fld" maxlimit="100" value="<?php if($userData['QA_Percentile']){ echo $userData['QA_Percentile']; } ?>" placeholder="QA %ile" name="QA_Percentile" default="QA percentile" mandatory="1" fieldtype="enter" />
                          <div class="errorMessage" id="QA_Percentile_error">
                          </div>
                       </div>
                     </li>
                      <li>
                       <div class="cat-flds">
                          <input type="number" step="any" id="cat_total" class="txt-fld" maxlimit="300" value="<?php if($userData['cat_total']){ echo $userData['cat_total']; } ?>" placeholder="Total Marks" name="cat_total" default="marks" mandatory="1" fieldtype="enter" />
                          <div class="errorMessage" id="cat_total_error">
                          </div>
                       </div>
                     </li>
                     <li>
                       <div class="cat-flds">
                          <input type="number" step="any" id="cat_percentile" class="txt-fld" maxlimit="100" value="<?php if($userData['cat_percentile']){ echo $userData['cat_percentile']; } ?>" placeholder="Total %ile" name="cat_percentile" default="total %ile" mandatory="1" fieldtype="enter" />
                          <div class="errorMessage" id="cat_percentile_error">
                          </div>
                       </div>
                     </li>
                     <li>
                        <!-- <section class="cat-nxt">
                          <input type="submit" name="next" value="View Results" ga-attr="VIEW_RESULTS" class="nxt-btn" id="catScoreStepSubmit" >
                        </section> -->
                        <div class="clear_s">
                        <input type="hidden" id="isCatdataSkipped" value="1"/>
                          <a class="button button--secondary" id="catScoreStepPrvs" ga-attr="CAT_SCORE_PAGE_PREVIOUS">Previous</a>
                          <a class="button button--orange" id="catScoreStepSubmit" ga-attr="VIEW_RESULTS">Predict Now</a>
                       </div>
                     </li>                    
                    </ul>
                 
                 </div>
                  
                 </div>
                 
              </div>
           </div>
       </article>
   </section>
  
  </div>