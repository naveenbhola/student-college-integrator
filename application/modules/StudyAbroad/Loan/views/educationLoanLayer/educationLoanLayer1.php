<div class="layer-comn">
   <div class="loan-form">
      <?php $this->load->view('educationLoanLayer/educationLoanLayerHeader'); ?>
      <div class="loan-formDet">
         <?php $this->load->view('educationLoanLayer/educationLoanLayerStepNav'); ?>
         <div class="loan-formFields" id="layer1">
            <div class="loan-fld">
               <label>Amount of Loan</label>
               <div class="loan-input">
                  <span class="inr-vlu">INR</span>
                  <input type="text" placeholder ="Enter Loan Amount" class="l1-q1" id="l1-q1" data-validation="Mandatory|Min|Max" inputType="number"  min="100000" max="20000000" caption="your Amount">
               </div>
               <div class="err-msg" id="err-msg-l1-q1"></div>
            </div>
            <div class="loan-fldGrp loan-fld-l1-q2" >
               <div class="loan-fld">
                  <label>Are you working?</label>
                  <div class="Lform-radio">
                     <p>
                        <input type="radio" id="l1-q2" name="l1-q2" value="yes" data-validation="Mandatory">
                        <label for="l1-q2">Yes</label>
                     </p>
                     <p>
                        <input type="radio" id="l1-q2-1" name="l1-q2" value="no">
                        <label for="l1-q2-1">No</label>
                     </p>
                  </div>
                  <div class="err-msg" id="err-msg-l1-q2"></div>
               </div>               
            </div>
            <div class="Ldummy-txt infoTipMsg" style="display:none;">
               <strong>What to enter as your annual income?</strong>
               <p>Make sure the income you are disclosing can be verified with proper document proofs like salary slips, income tax returns, bank statements, and form16 etc.</p>
            </div>
            <div class="loan-formBtn2">
               <a href="javascript:void(0)" class="nextStep">Next</a>
            </div>
         </div>
      </div>
   </div>
</div>