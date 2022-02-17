<div  style="display:none;z-index:1;position:relative;" id="mailLayer" >
<div class="layerContent">
     
  <form name="mailerForm" id ="mailerForm"  method ="post" >
	      <div class="wdh100 mb10" >
                                    <div style="font-size: 14px";>
				       <input name="templateType" type="radio" checked="checked" style="position:relative;top:1px;" id="newtemplate" onclick="selecthide();" /> <strong>Create New Mail Template </strong>&nbsp;
				       <input name="templateType" type="radio" style="position:relative;top:1px;" id="savedtemplate" onclick="selectopen();" /><strong>Saved Mail Templates</strong>
                                      <span id="mailerTemplateSubject" >
					<select name="changeTemplate" onchange="setTemplate(value);" style="display: none ; width: 110px;" id="selectid" ></select>
					    <div style="padding-left:26px;font-size: 13px;" class="errorMsg" id="emptyselectId" ></div>
				      </span>
				  
                                    </div>
				<div class="clear_L" style="height: 12px;">&nbsp;</div>
              </div>
			
	      <div class="wdh100 mb10">
                                <div class="float_L tar" style="width: 90px; font-size: 14px"><label>Subject: </label> &nbsp; </div>
                                <div class="float_L">
                                    <div><input name ="subject" value="" type="text" class="olyTxtBx" style="width:398px; padding: 4px !important" id="subject" maxlength="100" onkeyup="setMailerSubjectAndBodyValues('subject');" /></div>
				    <div style="font-size: 13px;"class="errorMsg" id="emptysubjectId" ></div>                                
				</div>
				
                                <div class="clearFix"></div>
              </div>
	      
			    <div class="clearFix spacer5"></div>
	      <div class="wdh100 mb10">
                                <div class="float_L tar" style="width: 90px; font-size: 14px"><label>Body: </label> &nbsp; </div>
                                <div class="float_L">
                                    <div><textarea maxlength="1000" rows ="5" cols ="5" style="width:398px; height: 175px; overflow: auto; padding: 4px !important" name="body" value="" id="body" onkeyup="imposeMaxLengthOnBody(1000);setMailerSubjectAndBodyValues('body'); return false;" ></textarea></div>
				     <div style=" font-size: 13px;"class="errorMsg" id="emptybodyId"></div>
                                </div>
				
                                <div class="clear_L">&nbsp;</div>
              </div>
		
		<div class="errorMsg" id="showerrorId" style="font-size: 13px"></div>
		<div class="clearFix spacer5" style="height: 0;"></div>
                <div style="margin-left: 90px">
		<input type="submit" value="Send Mail" title="Send" class="orange-button" id="submitsend" name="btn1" onclick="imposeMaxLengthOnBody(1000);confirmCallback(1);return false;" style="padding:3px 8px !important" /> &nbsp;
		<input type="submit" value="Save and Send Email Text" title="SendNSave" class="orange-button" id="submitsavensend" name="btn2" onclick="imposeMaxLengthOnBody(1000);confirmCallback(2);return false;" style="padding:3px 8px !important" /> &nbsp;
		<a style="font-size: 16px" href="javascript:void(0);" title="Cancel" onclick="hideMailerOverlay();return false;">Cancel</a>
		</div>
		<div class="clearFix spacer10" style=" height: 28px;"></div>
       	</form>
     </div>
  </div>


