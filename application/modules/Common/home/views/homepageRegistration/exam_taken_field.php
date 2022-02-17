<div id = "examTaken" style="display : none;" class="find-field-row">
    <label>Exams Taken:</label>
    <div class="formCont">
    	<div style="float:left;">
    	<input type="checkbox" name="compExam[]" value="TOEFL"/> TOEFL&nbsp; 
        <input type="checkbox" name="compExam[]" value="IELTS"/> IELTS &nbsp;
        </div>
        <div id="SAT_block" style="float:left;">
        	<input type="checkbox" name="compExam[]" id="SAT_score_chkbox" value="SAT" onclick="$('SAT_score').style.display = this.checked ?  '' : 'none'; "/> SAT &nbsp; 
            <input type="text" name="SAT_score" id="SAT_score" class="form-txt-field" style="display:none; width:70px" minscore="600" maxscore="2400" blurmethod="checkScore('SAT_score')" value="" size="3" maxlength="4"/>
        </div>
        <div class="spacer5 clearFix"></div>

        
        
        <div id="GMAT_block" style="display:none; float:left">
        	<input type="checkbox" name="compExam[]" id="GMAT_score_chkbox" value="GMAT" onclick="$('GMAT_score').style.display = this.checked ?  '' : 'none'; "/> GMAT &nbsp; 
            <input class="form-txt-field" type="text" name="GMAT_score" id="GMAT_score" style="display:none; width:70px" minscore="400" maxscore="800" blurmethod="checkScore('GMAT_score')" size="3" maxlength="3"/>
        </div>
        
        <div id="GRE_block" style="display:none; float:left">
        	<input type="checkbox" name="compExam[]" id="GRE_score_chkbox" value="GRE" onclick="$('GRE_score').style.display = this.checked ?  '' : 'none'; "/> GRE &nbsp; 
            <input class="form-txt-field" type="text" name="GRE_score" id="GRE_score" style="display:none; width:70px" minscore="500" maxscore="1600" blurmethod="checkScore('GRE_score')" size="3" maxlength="4"/>
        </div>
        <div class="clearFix"></div>
    	</div>
        <div>
            <div class="errorPlace" style="display:none;">
                <div class="errorMsg" id= "SAT_score_error"></div>
                <div class="errorMsg" id= "GMAT_score_error"></div>
                <div class="errorMsg" id= "GRE_score_error"></div>
            </div>
    	</div>
        <div class="clearFix"></div>
    </div>
