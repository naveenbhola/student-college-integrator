<div class="steps-main-block" style="padding-left:130px" id="<?php echo $sectionName;?>CustomField_<?php echo $countOfSection;?>">
                    <div class="steps-block" style="width:375px">
                    	<p class="custom-title">- Create Custom Field</p>
                        <ul>
                            <li>
                                <div class="career-fields">
                                    <input minlength="1" maxlength="250" caption="title" validate="validateStr" type="text" class="universal-txt-field" style="width:82%;color:#000;font-size:12px" value="Enter Title" id="wikkicontent_title_<?php echo $sectionName;?>_<?php echo $countOfSection;?>" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Enter Title"/> &nbsp;<a href="javascript:void(0);" onClick="careerObj.removeCustomFields('<?php echo $sectionName;?>','<?php echo $countOfSection;?>','','');" >Remove</a>
				<div style="display:none"><div class="errorMsg"  id="wikkicontent_title_<?php echo $sectionName;?>_<?php echo $countOfSection;?>_error"></div></div>
                                </div>
                            </li>
                            
                            <li>
                                <div class="career-fields">
                                    <textarea minlength="1" maxlength="10000"  caption="detail" class='mceEditor' caption="Description" id="wikkicontent_detail_<?php echo $sectionName;?>_<?php echo $countOfSection;?>" style="width:370px;height:100px;" ></textarea>
				<div><div id="wikkicontent_detail_<?php echo $sectionName;?>_<?php echo $countOfSection;?>_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
                                </div>
                            </li>
                            
                           
                        </ul>	
                        <div class="clearFix"></div>
                    </div>
</div>
                


