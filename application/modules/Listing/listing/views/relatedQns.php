<style>
.raised_pinkWhite {background: transparent; } 
.raised_pinkWhite  .b1, .raised_pinkWhite  .b2, .raised_pinkWhite  .b3, .raised_pinkWhite  .b4, .raised_pinkWhite  .b1b, .raised_pinkWhite  .b2b, .raised_pinkWhite  .b3b, .raised_pinkWhite  .b4b {display:block; overflow:hidden; font-size:1px;} 
.raised_pinkWhite  .b1, .raised_pinkWhite  .b2, .raised_pinkWhite  .b3, .raised_pinkWhite  .b1b, .raised_pinkWhite  .b2b, .raised_pinkWhite  .b3b {height:1px;} 
.raised_pinkWhite  .b2 {background:#FFE9D4; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
.raised_pinkWhite  .b3 {background:#FFFFFF; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
.raised_pinkWhite  .b4 {background:#FFFFFF; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
.raised_pinkWhite  .b4b {background:#FFFFFF; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
.raised_pinkWhite  .b3b {background:#FFFFFF; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
.raised_pinkWhite  .b2b {background:#FFFFFF; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
.raised_pinkWhite  .b1b {margin:0 5px; background:#FFE9D4;} 
.raised_pinkWhite  .b1 {margin:0 5px; background:#ffffff;} 
.raised_pinkWhite  .b2, .raised_pinkWhite  .b2b {margin:0 3px; border-width:0 2px;} 
.raised_pinkWhite  .b3, .raised_pinkWhite  .b3b {margin:0 2px;} 
.raised_pinkWhite  .b4, .raised_pinkWhite  .b4b {height:2px; margin:0 1px;} 
.raised_pinkWhite  .boxcontent_pinkWhite {display:block; background-color:#FFFFFF; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
</style>
<?php
if(count($relatedQuestions) >= 1){
$userGroup = isset($validateuser[0]['usergroup'])?$validateuser[0]['usergroup']:'normal';
$pageKeyForAskQuestion = $pageKeyInfo.'POSTQUESTION';
$pageKeyForInlineAnswer = $pageKeyInfo.'InlineAnswer';
$titleOfInstitute = isset($details['institute_name'])?$details['institute_name']:((isset($details['instituteArr'][0]['institute_name']))?$details['instituteArr'][0]['institute_name']:$details['title']);
$this->load->view('common/userCommonOverlay');
$this->load->view('network/mailOverlay',$data);
$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
echo "<script language=\"javascript\"> ";
echo "var BASE_URL = '';";
echo "var COMPLETE_INFO = ".$quickSignUser.";";
echo "var URLFORREDIRECT = '".base64_encode($_SERVER['REQUEST_URI'])."';";	
echo "var loggedInUserId = ".$userId.";";
echo "</script> ";

?>
<script>
var userVCardObject = new Array();
function searchAllQuestion(){
    var url = '/relatedData/relatedData/getRelatedSearchQuery/<?php echo $type_id; ?>/<?php echo $listing_type; ?>/ask'; 
    new Ajax.Request(url, { method:'get', onSuccess:redirectUrlForRelatedSearch });
    return false;
}
function redirectUrlForRelatedSearch(res){
    var urlForSearch = '/search/index?keyword='+encodeURIComponent(decodeURIComponent(res.responseText))+'&searchType=relatedData&cat_id=-1&countOffsetSearch=25&startOffSetSearch=0&subCategory=-1&subLocation=-1&cityId=-1&cType=-1&durationMin=-1&durationMax=-1&courseLevel=-1&subType=0&showCluster=-1&channelId=home_page&listingDetails=<?php echo $listing_type; ?>+<?php echo $type_id; ?>';
    window.location.href = urlForSearch;
    return false;
}
</script>
<div class="lineSpace_18">&nbsp;</div>
<div style="position:relative">
  	     <div style="position:absolute;top:-17px;margin-left:85%"><img border="0" src="/public/images/shikshaExclusive.gif"/></div>
  	 </div>
<div>
    <div class="raised_pinkWhite">
        <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
        <div class="boxcontent_pinkWhite">
			<div style="margin-bottom:10px;">
				<div class="float_L fontSize_14p bld" style="width:80%">
					<div style="margin-right:100px;">
						&nbsp; <img src="/public/images/relatedQuestionHeaderIcon.gif" align="absmiddle"/><span class="blackColor">Questions related to </span><span class="orangeColor"><?php echo $titleOfInstitute; ?></span>
					</div>
				</div>
		<div class="clear_B">&nbsp;</div>
		</div>
		<div class="defaultAdd clear_B"></div>
		<div class="defaultAdd lineSpace_8">&nbsp;</div>
		<div class="grayLine_3 mar_full_10p">&nbsp;</div>
		<div class="defaultAdd lineSpace_8">&nbsp;</div>
		<div style="width:100%">
			<div style="margin:0 10px;">
				<div style="width:100%">
	<?php
		$i=0;
		$userProfile = site_url('getUserProfile').'/';
		foreach($relatedQuestions as $temp){
			$i++;
			if($i > 2){
				break;
			}
			$questionUrl = $temp['url'];
			$questionText = $temp['title'];
			$questionText = (strlen($questionText) <= 250)?$questionText:(substr($questionText,0,250).'...');
			$popularAnswerDetails = $temp['popularAnswerDetails'];
			$flagForAnswer = array_key_exists('flagForAnswer',$popularAnswerDetails)?$popularAnswerDetails['flagForAnswer']:0;
			$questionUserDisplayName = $temp['questionUserInfo']['displayname'];
			$viewMsg = ($temp['viewCount'] > 1)?($temp['viewCount'].' Views'):($temp['viewCount'].' View');
			$arrVal = split(" ",$temp['creationDate']);
			$day = ereg_replace("[a-z]","",$arrVal[0]);
			$month = ereg_replace("[,]","",$arrVal[1]);
			for($j=1;$j<=12;$j++){
				if(date("M", mktime(0, 0, 0, $j, 1, 0)) == $month){
					$month_number = $j;
					break;
				}
			}
			$timeForCreation = split(":",$arrVal[3]);
			$hour = $timeForCreation[0];
			$min = ereg_replace('[a-zA-Z]','',$timeForCreation[1]);
			$creationDateFinal =  makeRelativeTime(date("Y-m-d H:i:s",mktime("$hour","$min",'0',$month_number,$day,"20".$arrVal[2])));
	?>
		<div style="width:100%">
			<div style="width:100%">
				<div class="shik_bgQuestionSign" style="padding-bottom:10px">
					<div style="line-height:17px"><a href="<?php echo $questionUrl; ?>" target="_blank"><?php echo $questionText; ?></a></div>
				</div>
				<!-- Start of Question Info -->
					<div style="margin-left:30px;">
                    <span class="grayFont">Asked by</span>
                    <span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $temp['questionUserInfo']['id'];?>');" ><a href="<?php echo SHIKSHA_HOME;?>/getUserProfile/<?php echo $questionUserDisplayName; ?>"><?php echo $questionUserDisplayName;  ?></a></span>
                    <span class="grayFont">&nbsp;<?php echo $creationDateFinal.", ".$viewMsg?></span>
                    </div>
					<div class="lineSpace_10">&nbsp;</div>
				<!-- End Of Question Info -->	
				<?php
					if((count($popularAnswerDetails) > 0) && ($temp['answerCount'] > 0)){
						$answerText = $popularAnswerDetails['msgTxt'];
						$moreLink = '';
						if(strlen($answerText) > 250){
							$answerText = substr($answerText,0,250).'...';
							$moreLink = '&nbsp;<a href="'.$questionUrl.'" target="_blank">more</a>';
						}
						$bestAnswer = (strcmp($popularAnswerDetails['typeOfAnswer'],'best')===0)?1:0;
				?>
				<div style="padding-left:34px;">
					<div style="color:#757272">By 
					<?php if($popularAnswerDetails['typeOfAnswer']!='institutebest'){ ?>
					<span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $popularAnswerDetails['userid'];?>');" ><a href="<?php echo $userProfile.$popularAnswerDetails['displayname']; ?>"><?php echo $popularAnswerDetails['displayname']; ?></a></span> - <span class="forA"><a href="/shikshaHelp/ShikshaHelp/upsInfo" ><?php echo $popularAnswerDetails['level']; ?></a></span></div>
					<?php }else{ ?>
					<b><?php echo $popularAnswerDetails['listingTitle']; ?></b> - <span class="Fnt11 fcOrg">Verified by </span>Shiksha</div>
					<?php } ?>
					<div style="line-height:17px"><a href="<?php echo $questionUrl; ?>" style="color:#000000;" target="_blank"><?php echo $answerText; ?></a><?php echo $moreLink; ?></div>
				</div>
				<?php if(($popularAnswerDetails['digUp'] > 0) || ($popularAnswerDetails['digDown'] > 0) || ($bestAnswer == 1) || ($temp['answerCount'] > 1)){ ?>
				<div>
					<div style="margin-left:34px">
						<div style="width:100%">
							<div style="width:130px" class="float_R">
								<div style="width:100%;line-height:27px" class="txt_align_r">
									<?php if($temp['answerCount'] > 1){ ?>
									<a href="<?php echo $questionUrl; ?>" target="_blank">View all <?php echo $temp['answerCount']; ?> Answers</a>
									<?php } ?>&nbsp;
								</div>
							</div>
							<div style="margin-right:130px">
								<div class="float_L" style="width:100%">
									<div style="width:100%">
										<?php if(($popularAnswerDetails['digUp'] > 0) || ($popularAnswerDetails['digDown'] > 0) || ($bestAnswer == 1)){ ?>
											<div style="float:left;height:25px;border:1px solid #e6e4e7;overflow:hidden">
												<?php if($popularAnswerDetails['digUp'] > 0){	?>
															<span class="shik_RatingHandUpDotted" style="border-right:1px solid #e6e4e7"><?php echo $popularAnswerDetails['digUp']; ?></span>
												<?php } if($popularAnswerDetails['digDown'] > 0){ ?>
															<span class="shik_RatingHandDownLine" style="border-right:1px solid #e6e4e7"><?php echo $popularAnswerDetails['digDown']; ?></span>
												<?php } if($bestAnswer == 1){ ?>
															<span class="shik_RatingStar">Voted Best Answer</span>
												<?php 	 } ?>
											</div>
										<?php } ?>&nbsp;
									</div>
								</div>
							</div>
						</div>
						<div class="defaultAdd clear_B">&nbsp;</div>
					</div>
				</div>
				<?php	}
						}
				?>
					<div style="margin-left:34px;margin-top:5px;">
					<div style="width:97%;">
					<?php
						if($temp['answerCount'] == 0){
							if($flagForAnswer > 0){
							echo '<div class="yelloBackForAlreadyAnswer"><img src="/public/images/greenChk.gif" align="absmiddle" />&nbsp;<a href="'.$questionUrl.'"  style="color:#000000;">You have already answered to this question</a></div>';
							}elseif($temp['status'] == 'closed'){
								echo '<span class="normaltxt_11p_blk">This question has been closed for answering.</span>';
							}else{
								$threadId = $temp['typeId'];
								$dataArray = array('userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => $temp['answerCount'],'detailPageUrl' =>$questionUrl,'callBackFunction' => 'try{ addMainComment('.$threadId.',request.responseText,\'-1\',true,true,\'-1\',\''.$pageKeyForInlineAnswer.'\'); } catch (e) {}');
								$this->load->view('messageBoard/InlineForm',$dataArray);
							}
						}
					?>
					</div>
					</div>
			</div>
			<div class="defaultAdd lineSpace_8">&nbsp;</div>
			<?php if($i != 5){ ?>
				<div class="grayLine_3">&nbsp;</div>
				<div class="defaultAdd lineSpace_8">&nbsp;</div>
			<?php } ?>
		</div>
	<?php } ?>
		<div class="txt_align_r"><a href="javascript:void(0);" onClick="return searchAllQuestion();">View All <b><?php echo $temp['noOfResults']; ?> Questions</b> of <?php echo $titleOfInstitute; ?></a></div>
		<div class="grayLine_3" style="margin:8px 0px;">&nbsp;</div>
		</div>
		</div>
		</div>
		<div class="defaultAdd lineSpace_10">&nbsp;</div>	
        </div>
        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
    </div>  
</div>
<div class="lineSpace_10">&nbsp;</div>
<?php } ?>
