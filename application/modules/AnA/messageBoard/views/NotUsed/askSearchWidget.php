<?php
	$questionText = isset($questionText)?$questionText:'';
	$editStyle = 'display:none;';
	$askQuestionStyle = '';
	if(isset($_COOKIE['commentContent']) && ($questionText == '')){
		$questionText = $_COOKIE['commentContent'];
		if((stripos($questionText,'@$#@#$$') !== false) || (stripos($questionText,'@#@!@%@') !== false)){
			$questionText = '';
		}
	}
    if($questionText != ''){
        $editStyle = '';
        $askQuestionStyle = 'display:none;';
		$showExpanded = true;
    }
	$questionTab = 'Ask&nbsp;a&nbsp;Question';
	if($questionText != ''){
		$questionTab = 'Your&nbsp;Question';
	}
	$showExpanded = isset($showExpanded)?$showExpanded:0;
	$pageViewed = isset($pageViewed)?$pageViewed:'other';
	$askPageShown = ($pageViewed == 'ask')?'true':'false';
	$questionText = htmlspecialchars($questionText);

?>
<!--Start_LongTabing-->
<div style="z-index:10000">
	<div class="float_L" style="width:80%;height:32px">
		<div class="globalAnATab">
			<a href="javascript:void(0);" class="selectedGlobalTab" style="margin-left:15px" id="askQuestionTabForGlobalWidget" onClick="swapTabs(this);"><span><?php echo $questionTab; ?></span></a>
			<a href="javascript:void(0);" id="searchAnswerTabForGlobalWidget" onClick="swapTabs(this);"><span>Search&nbsp;for&nbsp;Answer</span></a>
		</div>
	</div>
	<div class="float_L" style="width:20%;height:33px">
		<div align="right" style="margin-right:7px;margin-top:16px"><a href="javascript:void(0);" class="<?php if($showExpanded == 1){ echo 'spirit_header collapseTab';}else{ echo 'spirit_header expandTab'; } ?>" id="expandCollapseLink" onClick="javascript:expandCollapseAskSearchWidget();" style="text-decoration:none">&nbsp;</a></div>
	</div>
	<div class="defaultAdd clear_L">&nbsp;</div>
</div>
<!--End_LongTabing-->
<!--Start_LongTabing_DataContainer-->
<div style="position:relative;top:-3px;z-index:98">
	<div>
		<div class="shik_roundCornerTopRight"><span class="shik_roundCornerTopLeft">&nbsp;</span></div>
		<div style="width:100%">
			<div class="leftRightBorderBox">
				<div class="bgExpendCollapse" id="globalAskSearchWidgetContainer" style="<?php if($showExpanded == 1){ echo 'display:block';}else{ echo 'display:none'; } ?>">
					<div class="defaultAdd lineSpace_6">&nbsp;</div>
					<div style="margin:0 16px">
						<div id="askEditContainerForAnAGlobalWidget">
						<!-- Start of edit section -->
							<div style="width:100%;<?php echo $editStyle; ?>" id="editQuestionGlobalContainer">
								<div class="float_L" style="width:80%">
									<div style="width:100%">
										<div>
											<div style="width:98%;height:46px;font-weight:normal;overflow-x:hidden;overflow-y:auto" class="askShik_seachtextArea"><div style="padding-left:5px;" id="editQuestionTextContainerForGlobalWidget"><?php echo shiksha_formatIt(insertWbr($questionText,32)); ?></div></div>
										</div>
										<div class="defaultAdd lineSpace_10">&nbsp;</div>
									</div>
								</div>
								<div class="float_L" style="width:20%">
									<div style="width:100%">
										<div style="margin-top:26px"><input type="button" value="" class="all_ShikshaBtn_spirit askShik_editButton" onClick="showAskHideEditForm(true);" /></div>
									</div>
									<!--<div class="defaultAdd lineSpace_10">&nbsp;</div>-->
								</div>
								<div style="padding-top:1px">
									<table width="100%" cellpadding="0" border="0">
									<tr>
									<td><div align='left'><span align="right">Make sure your question follows <a href="javascript:void(0);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline');">Community Guidelines</a>&nbsp;</span></div></td>
									</tr></table>
								</div>
								<div class="defaultAdd clear_L">&nbsp;</div>
							</div>
						<!-- End of edit sction -->
						<!-- Start of ask question -->
						<form id="askQuestionForm" name="askQuestionForm" method="get" action="<?php echo SHIKSHA_ASK_HOME_URL; ?>/messageBoard/MsgBoard/questionPostLandingPage" onsubmit="try{return validateQuestionForm(this,'<?php echo $postQuestionKey; ?>','formsubmit','askQuestionForm','<?php echo $askPageShown; ?>');}catch (e){ return false;}">
						<div style="width:100%;<?php echo $askQuestionStyle; ?>" id="askQuestionForAnAGlobalWidget">
							<div class="float_L" style="width:80%">
								<div style="width:100%">
									<div><textarea name="questionText" id="questionText" autocomplete="off" value="<?php echo $questionText; ?>" type="text" onkeyup="try{ textKey(this); } catch (e){}" profanity="true" validate="validateStr" caption="Question" maxlength="300" minlength="2" required="true" style="width:98%;height:46px;color:#565656;" default="Type your education related question in this box. Your questions will be answered by Shiksha Counselors, Experts, College Alumni and Students." onfocus="checkTextElementOnTransition(this,'focus');" onblur="checkTextElementOnTransition(this,'blur');"><?php if ($questionText != '') { echo $questionText;} else { echo "Type your education related question in this box. Your questions will be answered by Shiksha Counselors, Experts, College Alumni and Students.";} ?></textarea></div>
									<div class="row errorPlace">
										<div class="errorMsg" id="questionText_error"> </div>
									</div>
									<input name="referalUrlForAskQuestionFromHeader" id="referalUrlForAskQuestionFromHeader" type="hidden" value="hackParameter"/>
									<div class="defaultAdd lineSpace_4">&nbsp;</div>
									<div>
										<div class="float_L" style="width:38%">
											<div class="grayColorFnt10" style="height:21px"><span id="questionText_counter">0</span> out of 300 characters</div>
										</div>
										<div class="float_L" style="width:60%">
											<div style="height:21px;line-height:21px" class="spirit_header shik_ShowAskMins">
												<div class="float_R" style="width:89px">
													<div class="txt_align_c"><?php echo $averageTimeToAnswer; ?>:00 Mins&nbsp;</div>
												</div>
												<div class="float_R">Average time to get an answer is&nbsp;</div>
											</div>
										</div>
										<div class="float_L" style="padding-top:1px">
											<table width="100%" cellpadding="0" border="0">
											<tr>
											<td><div align='left'><span align="right">Make sure your question follows <a href="javascript:void(0);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline');">Community Guidelines</a>&nbsp;</span></div></td>
											</tr></table>
										</div>

									</div>
								</div>
							</div>
							<div class="float_L" style="width:20%">
								<div style="margin-top:26px"><input type="<?php if($pageViewed == 'other'){ echo 'submit'; }else{ echo 'button'; } ?>" onClick="<?php if($pageViewed == 'ask'){ echo 'return validateAskQuestion();';}?>" value="" class="spirit_header shik_AskNowBtn" /></div>
								<?php if($pageViewed == 'ask'){ ?>	
								<div class="defaultAdd lineSpace_1">&nbsp;</div>
								<div>
									<div style="height:12px">
										<a href="javascript:void(0);" onClick="showAskHideEditForm(false);">Cancel</a>
									</div>
								</div>
								<?php } ?>
							</div>

							<div class="defaultAdd clear_L">&nbsp;</div>
						</div>
						</form>
						<!-- End of ask question -->
						</div>
						<!-- Start of search answer -->
						<div style="width:100%;display:none;" id="searchAnswerForAnAGlobalWidhet">
							<div class="float_L" style="width:80%">
								<div style="width:100%">
									<div><textarea name="keywordForGlobalAnAWidget" id="keywordForGlobalAnAWidget" style="width:98%;height:46px;color:#565656;" onfocus="checkTextElementOnTransition(this,'focus');" onblur="checkTextElementOnTransition(this,'blur');" default="Type your question or keywords in this box and click Search to find your answer from similar question previously answered.">Type your question or keywords in this box and click Search to find your answer from similar question previously answered.</textarea></div>
									<div class="row errorPlace">
										<div id="keywordForGlobalAnAWidget_error" class="errorMsg">&nbsp;</div>
									</div>
									<input type="hidden" name="searchTypeForGlobalAnAWidget" id="searchTypeForGlobalAnAWidget" value="question" />
									<div class="defaultAdd lineSpace_4">&nbsp;</div>
								</div>
							</div>
							<div class="float_L" style="width:20%">
								<div style="margin-top:26px"><input type="button" value="" class="spirit_header shik_searchNowBtn" onClick="searchForGlobalAnAWidget();" /></div>
							</div>
							<div class="defaultAdd clear_L">&nbsp;</div>
						</div>
						<!-- End` of search answer -->
						
					</div>
				</div>
			</div>
		</div>
		<div class="shik_roundCornerBottomRight"><span class="shik_roundCornerBottomLeft">&nbsp;</span></div>
	</div>
</div>
<!--End_LongTabing_DataContainer-->
