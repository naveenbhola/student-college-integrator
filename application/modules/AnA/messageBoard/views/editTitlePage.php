<?php if($status=='admin'){$url = '/enterprise/Enterprise/updateTitle';}else{ $url = '/messageBoard/MsgBoard/updateTitle';}?>
  <form id="askQuestionFormPost" autocomplete="off" method="post" action="" onsubmit="if(!validateFields(this)){ return false;}else{new Ajax.Request('<?php echo $url;?>' ,{onSuccess:function(request){if(request.responseText==1){thanksPageWithoutEdit();}else{questionThanksOverlay();} },evalScripts:true, parameters:Form.serialize(this)}); return false;}">
    <input type="hidden"value="<?php echo $msgId; ?>" name="msgId" />
    <input type="hidden"value="<?php echo $userId; ?>" name="userId"/>
    <input type="hidden"value="<?php echo $questionUserId; ?>" name="questionUserId"/>
    <input type="hidden"value="<?php echo $status; ?>" name="status"/>
    
    
<div style="width:632px;margin:0 auto">
            <div class="whtRound" style="padding:21px 18px 10px 18px;position:relative;">
            
            	<div style="position:absolute;top:-5px;right:35px;*right:57px; display:none" id="msgTitle_tips">
                    
            	</div>
                <div style="position:absolute;top:-5px;right:35px;*right:57px;" id="suggestion_tips" >
                    <div style="position:absolute;top:15px;right:-225px">
                        <div class="tooltipAnAm">
                            <div class="tooltipAnAt" style="height:130px">

                            	<div class="tar" style="padding:7px 14px 0 0"><img src="/public/images/cBtn.gif" class="pointer" onclick="hideSuggesstionTip()"/></div>
                                <div style="padding:0 20px 0 60px">
									<div class="mb8 bld">While adding the title, follow these guidelines:</div>
                                    <ol type="1" style="padding:0 0 0 15px;*padding:0 0 0 22px;margin:0">
                                    <li class="mb7">Title should be clear</li>
                                    <li class="mb7">Relavant</li>
                                    <li class="mb7">Title Gives value to your question</li>

                                    </ol>
                                </div>
                            </div>
                            <div><img src="/public/images/tooltipAnAb.gif" /></div>
                        </div>
                    </div>
                    </div>
               
            	<div class="wdh100">
                    <div class="mb10">

                        <strong>Enter Title</strong><br />
                        <input type="text" id="msgTitle" name="msgTitle" onblur="enterEnabled=false;" onfocus="try{ enterEnabled=true; }catch (e){}" profanity="true" validate="validateStr" caption="Question Title" required="true" maxlength="140" minlength="2" class="Fnt14 intxSty" style="width:90%" value="<?php if($msgTitleAdmin){echo htmlspecialchars($msgTitleAdmin);} ?>" onkeyup="hideSuggesstionTip();validateForTips('msgTitle');" />
                    </div>
                    <div class="row errorPlace"><span id="msgTitle_error" class="errorMsg">&nbsp;</span></div>
                    <div class="mb10">
                        <strong>Enter Description</strong><br />
                        <textarea class="intxSty" style="width:90%; height:100px" disabled validateSingleChar='true'><?php if($msgDesc){ echo $msgDesc; }else{echo $msgTitle; }?></textarea>
                        <input  type="hidden" name="msgDescription" value="<?php if(base64_encode($msgDesc)){ echo base64_encode($msgDesc); }else{echo $msgTitle; }?>">
                    </div>
                    </div>
                    <!--Start_Button-->

                    <div class="mtb10"><input type="submit" class="fbBtn" value="Update"  id="editButton"/> &nbsp; <a onclick="hideTitleOverlay();" href="javascript:void(0);">Cancel</a></div>
                    <!--End_Button-->
                </div>
            </div>
    </div>

  </form>