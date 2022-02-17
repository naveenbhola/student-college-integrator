<form action="" accept-charset="utf-8" method="post" novalidate="novalidate" name="rankPredictorForm" id="rankPredictorForm_<?php echo $regFormId; ?>" onsubmit="return false;">
                <div class="predictor-rank-box">
                    <div class="predictor-rank-form">
                        <h2><?php echo $rpConfig[$examName]['formHeading'];?></h2>
                        <ul>
                    		<li>
                            	<div class="predictor-column flLt">
                                	<label><?php echo $rpConfig[$examName]['inputField']['score']['label'];?> </label><br />
                                	<input type="text" class="predictor-textfield" id="rankPredictor_Score<?php echo $regFormId; ?>" name="rankPredictor_Score" maxlength="<?php echo $rpConfig[$examName]['inputField']['score']['maxLength'];?>" minlength="<?php echo $rpConfig[$examName]['inputField']['score']['minLength'];?>" <?php if($rpConfig[$examName]['inputField']['score']['isAllowedDecimal'] == 'YES'){?> validate="validateFloat" onkeyup="manageFloat($j(this));" <?php }else{?> validate="validateInteger" <?php }?> caption="valid score" required = "true" value="<?php echo $getCookei[0];?>"/>
				    <div style="display:none;" id="scrError"><div class="predictor-errmsg" id="rankPredictor_Score<?php echo $regFormId; ?>_error"></div></div>
                                </div>
                             <!--    <div class="predictor-column flRt">
                                	<label><?php echo $rpConfig[$examName]['inputField']['marks']['label'];?> </label><br />
                                	<input type="text" class="predictor-textfield" id="rankPredictor_BoardMarks<?php echo $regFormId; ?>" name="rankPredictor_BoardMarks" maxlength="<?php echo $rpConfig[$examName]['inputField']['marks']['maxLength'];?>" minlength="<?php echo $rpConfig[$examName]['inputField']['marks']['minLength'];?>" validate="validateFloat" caption="board marks" required = "true" value="<?php echo $getCookei[1];?>"/>
				    <div style="display:none;" id="pcmError"><div class="predictor-errmsg" id="rankPredictor_BoardMarks<?php echo $regFormId; ?>_error"></div></div></div> -->

                        <div class="predictor-column flRt">
                                    <label><?php echo $rpConfig[$examName]['inputField']['rollNo']['label'];?> </label><br />
                                    <input type="text" class="predictor-textfield" id="rankPredictor_RollNo<?php echo $regFormId; ?>" name="rankPredictor_RollNo" maxlength="<?php echo $rpConfig[$examName]['inputField']['rollNo']['maxLength'];?>" minlength="<?php echo $rpConfig[$examName]['inputField']['rollNo']['minLength'];?>" validate="validateStr" caption="roll no" required = "true" value="<?php echo $getCookei[2];?>"/>
                    <div style="display:none;"><div class="predictor-errmsg" id="rankPredictor_RollNo<?php echo $regFormId; ?>_error"></div></div>
                                </div>
                            
                                <div class="clearFix"></div>
                            </li>
                            
                            <li style="text-align: left">
                            	<a href="javascript:void(0);" style="margin-left:135px;" class="predict-rank-btn" onclick="if(validateFields($('rankPredictorForm_<?php echo $regFormId; ?>')) != true){ return false;}else{if(validateScore('<?php echo $regFormId;?>') !=true){return false;}else{resetRankPredictor('<?php echo $regFormId;?>');}}" id="predictBtn_<?php echo $regFormId;?>">Predict <?php echo $rpConfig[$examName]['examName'];?> Rank</a><span id="resetLoading" style="display: none; margin-top:5px;"> Please wait..</span>
                            </li>
                    	</ul>
                    </div>
                    <div class="clearFix"></div>
                </div>
                
</form>
<script>
		
    try{
	 addOnBlurValidate(document.getElementById('rankPredictorForm_<?php echo $regFormId; ?>'));
	} catch (ex) {
	}
</script>
