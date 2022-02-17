<?php if($showCategorySelection) { ?> 
<script>overlayViewsArray.push(new Array('common/parentCategoryOverlay','commonParentCategoryPanel'));</script>
<?php } ?>
<?php
	global $categoryParentMap;
	foreach($categoryParentMap as $categoryName => $category) {
		$tempCategoryId = $category['id'];
		if($tempCategoryId == $categoryId){
			$selectedCategoryName = $categoryName;
		}
	}
	$showHeaderOfWidget = isset($showHeaderOfWidget)?$showHeaderOfWidget:true;
	$editorialBinQuestionsData = is_array($editorialBinQuestions['results'])?$editorialBinQuestions['results']:array();
    $currentShown = $editorialBinQuestions['currentShown'];
	$lowerLimit = $editorialBinQuestions['lowerLimit'];
	$upperLimit = $lowerLimit+count($editorialBinQuestionsData)-1;
	$userGroup = isset($validateuser[0]['usergroup'])?$validateuser[0]['usergroup']:'normal';
	$pageKeyForAskQuestion = $pageKeyInfo.'POSTQUESTION';
	$pageKeyForInlineAnswer = $pageKeyInfo.'InlineAnswer';

	$this->load->view('common/userCommonOverlay');
	$this->load->view('network/mailOverlay',$data);
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
	echo "<script language=\"javascript\"> ";
	echo "var BASE_URL = '';";
	echo "var COMPLETE_INFO = ".$quickSignUser.";";
	echo "var URLFORREDIRECT = '".base64_encode($_SERVER['REQUEST_URI'])."';";	
	echo "var loggedInUserId = ".$userId.";";
	echo "</script> ";
?>
<script>
//Added by Ankur to add VCard on all AnA pages: Start
var userVCardObject = new Array();
</script>
<input type="hidden" id="currentShownDivForGlobalAnAWidget" autocomplete="off" value="<?php echo $editorialBinQuestions['randNum']; ?>" />
<input type="hidden" id="widthForGlobalAnAWidget" autocomplete="off" value="0" />
<input type="hidden" id="selectedCategoryForGlobalAnAWidget" autocomplete="off" value="<?php echo $categoryId; ?>" />
<input type="hidden" id="lowerLimitForGlobalAnAWidget" autocomplete="off" value="<?php echo $lowerLimit; ?>" />
<input type="hidden" id="upperLimitForGlobalAnAWidget" autocomplete="off" value="<?php echo $upperLimit; ?>" />
<input type="hidden" id="totalRowsForGlobalAnAWidget" autocomplete="off" value="<?php echo $editorialBinQuestions['totalRows']; ?>" />
<div>
<div style="width:100%">
	<?php if($showHeaderOfWidget == true){ ?>
		<div class="Fnt15" style="padding:1px 0 1px 0"><b><?php echo $title ?></b></div>
		<div style="font-size:1px;border-bottom:2px solid #c5e3fd;margin-bottom:11px">&nbsp;</div>
	<?php } ?>
	<div>	
		<?php $this->load->view('home/globalAskSearchWidget',array('pageKeyForAskQuestion'=>$pageKeyForAskQuestion)); ?>
	</div>
	<div style="border-top:1px solid #f1f1f1">&nbsp;</div>
</div>

<!--Start_Featured_Answer-->
<div style="width:100%">
	<div style="padding:4px 0 12px 0">
		<div class="float_R" style="width:50px">
			<div class="arrowMove"><span href="javascript:void(0);" onClick="slideItGlobalAnAWidget(-1);" class="spirit_header homeshik_leftMoveTxtArrow">&nbsp;</span><span href="javascript:void(0);" onClick="slideItGlobalAnAWidget(1);" class="spirit_header homeshik_rightMoveTxtArrow" style="margin-left:3px">&nbsp;</span></div>
		</div>
		<div style="margin-right:60px">
			<div class="float_L" style="width:100%">
				<div class="homeShik_Icon home_FeaturedTab">
					<div><span class="blackColor">Featured Answers in</span> <span class="orangeColor" id="categoryPlaceForGlobalAnAWidget"><?php echo $selectedCategoryName; ?></span></div>
					<?php if($showCategorySelection) { ?>
						<div style="padding-left:150px" id="changeCategoryLinkContainer"><span style="font-size:12px;font-weight:400"><b style="color:#bdbdbd">[</b> <a href="javascript:void(0);" onClick="drpdwnOpen(this,'commonParentCategoryPanel');">Change Category <span class="orangeColor" style="font-size:9px">&#9660;</span></a> <b style="color:#bdbdbd">]</b></span></div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="clear_B">&nbsp;</div>
	</div>
	
	<div style="margin-left:15px">
		<div style="width:100%">
		<!-- start of rotating portion -->
		<div style="width:100%" class="shik_box1">
		<div class="shik_box2" style="width:100%"  id="containerForGlobalAnAWidget" currentShownDiv="<?php echo $editorialBinQuestions['randNum']; ?>">
		<?php
			$userProfile = SHIKSHA_HOME.'/getUserProfile/';
			$i = $editorialBinQuestions['lowerLimit']-1;
			foreach($editorialBinQuestionsData as $temp):
			$i++;
			$questionText = $temp['question']['msgTxt'];
			$answerText = $temp['answer']['msgTxt'];
			$questionText = (strlen($questionText) <= 400)?$questionText:(substr($questionText,0,400).'...');
			$answerText = (strlen($answerText) <= 400)?$answerText:(substr($answerText,0,400).'...');
			$questionUrl = $temp['question']['url'];
			$moreLink = '';
			if(strlen($answerText) > 400){
				$moreLink = '&nbsp;<a href="'.$questionUrl.'">more</a>';
			}
		?>
		<div style="width:100%" class="shik_box3" id="containerForGlobalAnAWidget<?php echo $i ?>" divNumber="<?php echo $i; ?>">
			<div>
			<div style="width:100%">
				<div class="shik_bgQuestionSign" style="padding-bottom:13px">
                <em>&nbsp;</em>
					<div style="line-height:17px"><a href="<?php echo $questionUrl; ?>"><?php echo $questionText; ?></a></div>
				</div>
				<?php if(count($temp['answer']) > 0){ ?>
				<div class="shik_bgAnswerSign">
                    <em>&nbsp;</em>
					<div style="color:#757272">By
					<?php if($temp['answer']['typeOfAnswer']!='institutebest'){ ?>
					<span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $temp['answer']['userid'];?>');" ><a href="<?php echo $userProfile.$temp['answer']['displayname']; ?>"><?php echo $temp['answer']['displayname']; ?></a></span> - <span class="orangeColor"><?php echo $temp['answer']['level']; ?></span></div>
					<?php }else{ ?>
					<b><?php echo $temp['answer']['listingTitle']; ?></b> - <span class="Fnt11 fcOrg" style='color:#FF8200;font-size:11px;'>Verified by </span>Shiksha</div>
					<?php } ?>
					<div style="line-height:17px"><a href="<?php echo $questionUrl; ?>" style="color:#000000;"><?php echo $answerText; ?></a><?php echo $moreLink; ?></div>
				</div>
				<?php } ?>
				<div>
					<div style="margin-left:34px">
						<div style="width:100%">
							<div>
								<div style="width:100%">
									<div style="width:100%">
										<?php			
											 if(($temp['answer']['digUp'] > 0) || ($temp['answer']['digDown'] > 0) || ($temp['answer']['typeOfAnswer'] == 'best')){
										?>
											<div style="float:left;height:25px;border:1px solid #e6e4e7;overflow:hidden">
												<?php
													if(count($temp['answer']) > 0){
														if($temp['answer']['digUp'] > 0){
												?>
															<span class="shik_RatingHandUpDotted" style="border-right:1px solid #e6e4e7"><?php echo $temp['answer']['digUp']; ?></span>
												<?php	 } ?>
												<?php 	 if($temp['answer']['digDown'] > 0){ ?>
															<span class="shik_RatingHandDownLine" style="border-right:1px solid #e6e4e7"><?php echo $temp['answer']['digDown']; ?></span>
												<?php 	 } ?>
												<?php 	 if($temp['answer']['typeOfAnswer'] == 'best'){ ?>
															<span class="shik_RatingStar">Voted Best Answer</span>
												<?php 	 }
													  }
												?>
											</div>
										<?php } ?>&nbsp;
										<div class="clear_L">&nbsp;</div>
									</div>
								</div>
							</div>
						</div>
						<?php if($temp['question']['noOfAnswer'] > 1){ ?>
							<div style="width:100%">
									<div style="width:100%;line-height:27px">
										<a href="<?php echo $temp['question']['url']; ?>">View all <?php echo $temp['question']['noOfAnswer']; ?> Answers</a>
									</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="defaultAdd lineSpace_11">&nbsp;</div>
				<div style="margin-left:34px;margin-top:5px;*margin-top:0;">
				<div style="width:100%;">
				<?php
					if(isset($temp['question']['flagForAnswer']) && ($temp['question']['flagForAnswer'] > 0)){
						echo '<div class="yelloBackForAlreadyAnswer"><img src="/public/images/greenChk.gif" align="absmiddle" />&nbsp;<a href="'.$questionUrl.'"  style="color:#000000;">You have already answered to this question</a></div>';
					}elseif($temp['question']['status'] == 'closed'){
						echo '<span class="normaltxt_11p_blk">This question has been closed for answering.</span>';
					}else{
						$threadId = $temp['question']['threadId'];
						$dataArray = array('userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => $temp['question']['noOfAnswer'],'detailPageUrl' =>$questionUrl,'callBackFunction' => 'try{ addMainComment('.$threadId.',request.responseText,\'-1\',true,true,\'-1\',\''.$pageKeyForInlineAnswer.'\'); } catch (e) {}');
						$this->load->view('messageBoard/InlineForm',$dataArray);
					}
				?>
				</div>
				</div>
			</div>
			<div class="defaultAdd lineSpace_10">&nbsp;</div>
			<div style="width:100%">
				<div>
					<div style="width:100%"><a href="javascript:void(0);" onClick="return goToHomeAnAHomePage(1);"><b>View All Questions &raquo;</b></a></div>
				</div>
				<div>
					<div style="width:100%">
						<?php if($temp['question']['status'] == 'closed'){ ?>
							<a href="javascript:void(0);" onClick="return goToHomeAnAHomePage(3);"><b>Answer Questions & Earn Shiksha Points</b></a>
						<?php } ?>&nbsp;
					</div>
				</div>
			</div>
		</div>&nbsp;</div>
		<?php endforeach; ?>
		</div>
		<div class="clear_L">&nbsp;</div>
		</div>
		<!--End of Rotation Portion-->
		</div>
	</div>
</div>
</div>
<!--End_Featured_Answer-->
<script>
var pageKeyForInlineAnswer = '<?php echo $pageKeyForInlineAnswer; ?>';
function setParametersForGlobalAnAWidget(Object1){
	document.getElementById('widthForGlobalAnAWidget').value = Object1.parentNode.offsetWidth;
	return;
}
setParametersForGlobalAnAWidget(document.getElementById('containerForGlobalAnAWidget'));
var gliderObject = new glider(document.getElementById('containerForGlobalAnAWidget'),'<?php echo $currentShown; ?>');
if(document.getElementById('changeCategoryLinkContainer') && (document.body.offsetWidth <= 800)){
	document.getElementById('changeCategoryLinkContainer').style.padding = '2px';
}
var categoryIdSelected = '<?php echo $categoryId ?>';
</script>
