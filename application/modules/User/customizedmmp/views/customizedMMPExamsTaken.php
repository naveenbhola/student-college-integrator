<div class="float_L" style="width:35%;line-height:18px">
    <div class="txt_align_r" style="padding-right:5px">
        Exams Taken:
    </div>
</div>
<div style="width:63%;float:right;text-align:left;">
    <div class="float_L">
        <div>
            <div style="display:block;">
                <input style="margin-left:0px;" type="checkbox" name="compExam[]" value="TOEFL"/> TOEFL&nbsp; 
                <input type="checkbox" name="compExam[]" value="IELTS"/> IELTS&nbsp;<br/>
            </div>
            <div style="display:block;">
                <span id="SAT_block">
                    <input style="margin-left:0px;" type="checkbox" name="compExam[]" id="SAT_score_chkbox" value="SAT" onclick="$('SAT_score').style.display = this.checked ?  '' : 'none'; "/> SAT &nbsp; 
                    <input type="text" name="SAT_score" id="SAT_score" style="display:none;" minscore="600" maxscore="2400" blurmethod="checkScore('SAT_score')" value="" size="3" maxlength="4"/>
                </span>
                <span id="GMAT_block" style="display:none;">
                    <input style="margin-left:0px;" type="checkbox" name="compExam[]" id="GMAT_score_chkbox" value="GMAT" onclick="$('GMAT_score').style.display = this.checked ?  '' : 'none'; "/> GMAT &nbsp; 
                    <input type="text" name="GMAT_score" id="GMAT_score" style="display:none;" minscore="400" maxscore="800" blurmethod="checkScore('GMAT_score')" size="3" maxlength="3"/>
                </span>
                <span id="GRE_block" style="display:none;">
                    <input type="checkbox" name="compExam[]" id="GRE_score_chkbox" value="GRE" onclick="$('GRE_score').style.display = this.checked ?  '' : 'none'; "/> GRE &nbsp; 
                    <input type="text" name="GRE_score" id="GRE_score" style="display:none;" minscore="500" maxscore="1600" blurmethod="checkScore('GRE_score')" size="3" maxlength="4"/>
                </span>
            </div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
    <div>
        <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px;display:inline">
            <div class="errorMsg" id= "SAT_score_error"></div>
            <div class="errorMsg" id= "GMAT_score_error"></div>
            <div class="errorMsg" id= "GRE_score_error"></div>
        </div>
    </div>
</div>
<div class="clear_L withClear" style="clear:both;">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
