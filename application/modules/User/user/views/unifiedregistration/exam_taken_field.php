<div id="examtakenblock_unifiedregistration" style="display:none;">
<div class="float_L" style="width:165px;line-height:18px">
    <div class="txt_align_r" style="padding-right:5px">
        Exams Taken:
    </div>
</div>
<div style="margin-left:163px">
    <div class="float_L">
        <div>
            <input type="checkbox" name="compExam[]" value="TOEFL"/> TOEFL&nbsp; 
            <input type="checkbox" name="compExam[]" value="IELTS"/> IELTS&nbsp;<br/> 
            <span id="SAT_block_unifiedregistration">
                <input type="checkbox" name="compExam[]" id="SAT_score_unifiedregistration_chkbox" value="SAT" onclick="$('SAT_score_unifiedregistration').style.display = this.checked ?  '' : 'none'; "/> SAT &nbsp; 
                <input type="text" name="SAT_score" id="SAT_score_unifiedregistration" style="display:none;" minscore="600" maxscore="2400" blurmethod="ShikshaUnifiedRegistarion.checkScore('SAT_score_unifiedregistration')" value="" size="3" maxlength="4"/>
            </span>
            <span id="GMAT_block_unifiedregistration" style="display:none">
                <input type="checkbox" name="compExam[]" id="GMAT_score_unifiedregistration_chkbox" value="GMAT" onclick="$('GMAT_score_unifiedregistration').style.display = this.checked ?  '' : 'none'; "/> GMAT &nbsp; 
                <input type="text" name="GMAT_score" id="GMAT_score_unifiedregistration" style="display:none;" minscore="400" maxscore="800" blurmethod="ShikshaUnifiedRegistarion.checkScore('GMAT_score_unifiedregistration')" size="3" maxlength="3"/>
            </span>
            <span id="GRE_block_unifiedregistration" style="display:none">
                <input type="checkbox" name="compExam[]" id="GRE_score_unifiedregistration_chkbox" value="GRE" onclick="$('GRE_score_unifiedregistration').style.display = this.checked ?  '' : 'none'; "/> GRE &nbsp; 
                <input type="text" name="GRE_score" id="GRE_score_unifiedregistration" style="display:none;" minscore="500" maxscore="1600" blurmethod="ShikshaUnifiedRegistarion.checkScore('GRE_score_unifiedregistration')" size="3" maxlength="4"/>
            </span>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
    <div>
        <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px;display:inline">
            <div class="errorMsg" id= "SAT_score_unifiedregistration_error"></div>
            <div class="errorMsg" id= "GMAT_score_unifiedregistration_error"></div>
            <div class="errorMsg" id= "GRE_score_unifiedregistration_error"></div>
        </div>
    </div>
</div>
<div class="clear_L withClear">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
</div>
