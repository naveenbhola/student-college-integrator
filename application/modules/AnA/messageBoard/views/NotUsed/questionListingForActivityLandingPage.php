<?php
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	$userImageURL = isset($validateuser[0]['avtarurl'])?$validateuser[0]['avtarurl']:'/public/images/photoNotAvailable.gif';
	$questionListArray = (isset($questionList[0]) && is_array($questionList[0]['results']))?$questionList[0]['results']:array();
	$totalCountForQuestions = isset($questionList[0]['totalCount'])?$questionList[0]['totalCount']:0;
	$userProfile = site_url('getUserProfile').'/'; 
	$this->load->view('common/userCommonOverlay');
	$this->load->view('network/mailOverlay',$data);
	$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
	echo "<script language=\"javascript\"> ";
	echo "var BASE_URL = '';";
	echo "var COMPLETE_INFO = ".$quickSignUser.";";
	echo "var URLFORREDIRECT = '".base64_encode($_SERVER['REQUEST_URI'])."';";	
	echo "var loggedInUserId = ".$userId.";";
	echo "</script> ";

?>
<script>
var userVCardObject = new Array();
</script>
<input type="hidden" autocomplete="off" id="startOffsetForQuestion" value="0"/>
<input type="hidden" autocomplete="off" id="countOffsetForQuestion" value="10"/>
<input type="hidden" autocomplete="off" id="threadIdOfReccentActivity" value="<?php echo $recentActivityThreadId; ?>"/>
<input type="hidden" autocomplete="off" id="methodName" value="showListingForActivityLandingPage"/>
<input type="hidden" autocomplete="off" id="totalCountForQuestions" value="<?php echo $totalCountForQuestions; ?>"/>


<!--Start_Question_And_Answer-->
<div style="width:100%">
	<div class="shik_outerBorder">
		<div class="spirit_middle shik_TopBottomGradient" style="line-height:30px">
			<?php if($typeOfPage == 'answer'){ ?>
				<b>Earn More Points by Answering These Questions</b>
			<?php }else{ ?>
				<b>Earn Back Points by Answering These Questions</b>
			<?php } ?>
		</div>
		<!--Pagination Place start here -->
			<div class="pagingID mar_full_10p lineSpace_18 txt_align_r" id="paginataionPlace1" style="margin:10px 0px;display:none;"></div>
		<!-- Pagination Place ends here -->
		<div class="marfull_LeftRight10">
			<div id="questionListContainer">
			<?php
				$i = 0;
				foreach($questionListArray as $temp):
					$creationDateForQuestion = makeRelativeTime($temp['creationDate']);
					$viewText = ($temp['viewCount'] <= 1)?$temp['viewCount'].' View':$temp['viewCount'].' Views';
					if($temp['ansCount'] == 0){
						$answerText = 'No answer';
					}else{
						$answerText = ($temp['ansCount'] == 1)?$temp['ansCount'].' answer':$temp['ansCount'].' answers';	
					}
					$questionUrl = $temp['url'];
			?>
			<!--Start_QuestionListing-->
			<?php
				if($i == 5){ echo '<div id="otherFiveQuestionContainer" style="display:none;">'; }
				$i++;
			?>
   			<div style="width:100%">
				<div class="shik_bgQuestionSign">
					<div style="line-height:17px"><?php echo $temp['msgTxt']; ?></div>
					<div class="grayColorFnt11" style="line-height:20px">Asked by <span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $temp['userId'];?>');" ><a href="<?php echo $userProfile.$temp['displayName'] ?>" class="Fnt12"><?php echo $temp['displayName']; ?></a></span> <?php echo $creationDateForQuestion; ?>, <?php echo $viewText; ?>, <a href="<?php echo $temp['url']; ?>" class="Fnt12"><?php echo $answerText; ?></a></div>
				</div>
				<div style="width:98%;">
					<?php
					if(isset($temp['flagForAnswer']) && ($temp['flagForAnswer'] > 0)){
						echo '<div class="yelloBackForAlreadyAnswer"><img src="/public/images/greenChk.gif" align="absmiddle" />&nbsp;<a href="'.$questionUrl.'"  style="color:#000000;">You have already answered to this question</a></div>';
					}elseif($temp['status'] == 'closed'){
						echo '<span class="normaltxt_11p_blk">This question has been closed for answering.</span>';
					}else{
					?>
					<div style="padding-left:34px;">
						<div class="pl33"><img src="/public/images/upArw.png" /></div>
						<div class="fbkBx">
							<div>
								<div class="float_L wdh100">
									<?php 
										  $dataArray = array('userId'=>$userId,'userImageURL'=>$userImageURL,'userGroup' =>$userGroup,'threadId' =>$temp['threadId'],'ansCount' => $temp['ansCount'],'detailPageUrl' =>$questionUrl,'callBackFunction' => 'try{ addMainComment('.$temp['threadId'].',request.responseText,\'-1\',true); } catch (e) {}');
										  $this->load->view('messageBoard/InlineForm_Homepage',$dataArray);
									  ?>
								</div>
								<s>&nbsp;</s>
							</div>
						</div>
					</div>
					<?php }
					?>
				</div>
				<div class="defaultAdd lineSpace_6">&nbsp;</div>
				<div class="grayLine_2">&nbsp;</div>
				<div class="defaultAdd lineSpace_10">&nbsp;</div>
			</div>
			<!--End_QuestionListing-->
			<?php if(($i == 10) || ($i == count($questionListArray))){ echo '</div>'; } ?>
			<?php endforeach; ?>
			</div>
			<!--Pagination Place start here -->
			<div class="pagingID mar_full_10p lineSpace_18 txt_align_r" id="paginataionPlace2" style="margin:10px 0px;display:none;"></div>
			<!-- Pagination Place ends here -->
			<?php if(count($questionListArray) > 5){ ?>
			<div class="txt_align_r" style="padding:8px 0 6px 0" id="viewAllLinkContainer"><a href="javascript:void(0);" onClick="javascript:openAllAquestion();"><b>View More Questions</b></a></div>
			<?php } ?>
		</div>
	</div>
</div>
<!--End_Question_And_Answer-->
<script>
doPagination(<?php echo $totalCountForQuestions; ?>,'startOffsetForQuestion','countOffsetForQuestion','paginataionPlace1','paginataionPlace2','methodName',3);
</script>