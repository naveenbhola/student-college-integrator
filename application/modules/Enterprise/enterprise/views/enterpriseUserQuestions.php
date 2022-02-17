<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Enterprise User Questions</title>
<?php
$headerComponents = array(
							'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
							'js'	=>	array('common','enterprise','header','discussion','ana_common'),
							'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
							'tabName'	=>	'',
							'taburl' => site_url('enterprise/Enterprise'),
							'metaKeywords'	=>''
							);
                                                        $this->load->view('enterprise/headerCMS', $headerComponents);
?>
<?php
	$this->load->view('enterprise/cmsTabs');
	$userProfile = site_url('getUserProfile').'/';
	$listingOwnerName = is_array($validateuser[0])?$validateuser[0]['displayname']:'';
?>
</head>
<body>
<!--Pagination Related hidden fields Starts-->
	<input type="hidden" autocomplete="off" id="startOffsetForQuestion" value="<?php echo $startOffset; ?>"/>
	<input type="hidden" autocomplete="off" id="countOffsetForQuestion" value="<?php echo $countOffset; ?>"/>
	<input type="hidden" autocomplete="off" id="totalNumOfRows" value="<?php echo $totalCount; ?>"/>
	<input type="hidden" autocomplete="off" id="methodName" value="redirectToNextPage"/>
<!--Pagination Related hidden fields Ends  -->
<!-- start of page -->
<div class="mar_full_10p">
	<div class="fontSize_18p">Questions Posted for <span class="orangeColor"><?php echo $listingOwnerName; ?></span></div>
    <div class="gntLine">&nbsp;</div>
    <div class="wdh100">
        <div class="raised_greenGradient_ww">
	        <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
    	    <div class="boxcontent_greenGradient_ww">
            	<div class="mar_full_10p">
                    <div class="clear_B lineSpace_5">&nbsp;</div>
                    <!--Start_Pagination_Code-->
						<div>
                    		<div class="pagingID mar_full_10p lineSpace_22 float_R" id="paginataionPlace1"></div>
							<div class="float_L bld">
							<?php
									if($totalCount > 0){
										echo "Showing ";
										if($totalCount > 10){
											echo ($startOffset+1)." - ".($startOffset+$countOffset)." of ".$totalCount;
										}else {
											echo ($startOffset+1)." - ".$totalCount." of ".$totalCount;
										}
									}else{
										echo "No question is posted currently for you.";
									}
							?>
							</div>
						</div>
                    <!--End_Pagination_Code-->
                    <div class="clear_B lineSpace_5">&nbsp;</div>
                    
                    <div>
					<?php
						$url = site_url("messageBoard/MsgBoard/replyMsg");
						foreach($questionsArr as $temp):
						$threadId = $temp['threadId'];
						$creationDate = makeRelativeTime($temp['creationDate']);
						$viewText = ($temp['viewCount'] <= 1)?$temp['viewCount'].' View':$temp['viewCount'].' Views';
						if($temp['ansCount'] == 0){
							$answerText = 'No answer';
						}else{
							$answerText = ($temp['ansCount'] == 1)?('<span id="answerCountHolder'.$threadId.'">'.$temp['ansCount'].'</span> answer'):$temp['ansCount'].' answers';
						}
						$NameOfUser = (trim($temp['firstname']) != "")?$temp['firstname']:$temp['displayname'];
						$listingTitle = $temp['listing_title'];
					?>
                    	<!--Start_Question_Reating_Data-->    
                        <div class="shik_bgQuestionSign quesBtmLine">
                            <div>
								<?php echo $temp['msgTxt']; ?>
							</div>
                            <div class="font_size_11">
								Asked by <a href="<?php echo $userProfile.$temp['displayname']; ?>">
											<b><?php echo $NameOfUser; ?></b>
								</a>
								<?php if(isset($answerArr[$temp['threadId']])): ?>
									<b>(<?php echo $temp['email'];  if($temp['mobile'] != ""){ echo ",".$temp['mobile']; } ?>)</b>
								<?php endif; ?>
								<?php echo $creationDate; ?>, <?php echo $viewText; ?>, <a href="<?php echo $temp['url']; ?>"><span id="answerTextHolder<?php echo $threadId; ?>"><?php echo $answerText; ?></span></a>
							</div>
                            <div class="clear_B lineSpace_5">&nbsp;</div>

                            <?php if(!empty($listingTitle)){?>
							<div class="font_size_11 graycolor">Question posted for 
							<?php
								echo $listingTitle;
								if(!empty($temp['instituteCityName'])){
									echo ', '.$temp['instituteCityName'].'</br>';
								}
								if($temp['course_title'] != ''){
									echo 'Course: '.$temp['course_title'];
								}
								
							?>	
							</div>
							<?php } ?>
							<div class="clear_B lineSpace_5">&nbsp;</div>
                            <!--Start_yourFormInclude-->
							<div style="width:100%;">
								<?php
									if(isset($answerArr[$temp['threadId']])){
									$msgId = $answerArr[$threadId]['msgId'];
								?>
								<div id="answerContainer<?php echo $msgId; ?>">
									<div class="font_size_11">Your Answer</div>
									<div style="background:#e2e2e2;padding:10px">
										<div class="txt_align_r"><a href="javascript:void(0);" onClick="javascript:showEditAnswerForm(<?php echo $msgId; ?>);return false;" class="bld">Edit</a></div>
										<div id="msgTxtContent<?php echo $msgId; ?>">
											<?php echo $answerArr[$threadId]['msgTxt']; ?>
										</div>
									</div>
								</div>
								<div class="clear_B" style="height:5px">&nbsp;</div>
								<!--Start_ReplyForm-->
								<div style="width:100%;float:left">
										<div style="display:none;" class="formReplyBrder" id="replyForm<?php echo $msgId;?>">
											<?php
											echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$msgId,'before' => 'if(validateFields(this) != true){return false;} else {
disableReplyFormButton('.$msgId.')}','success' => 'javascript:addSubComment('.$msgId.',request.responseText,1,\'showAnswerForCMS('.$msgId.')\');'));
											?>
											<div>
												<div class="bld" style="padding-bottom:5px" id="messageForReply<?php echo $msgId; ?>"><span class="OrgangeFont">Reply to</span> <?php echo $temp['displayname']; ?></div>
												<div style="padding-bottom:5px;display:none;" id="messageForEdit<?php echo $msgId; ?>"><span class="fontSize_12p">Your Answer </span></div>
											</div>
											<div>
												<textarea name="replyText<?php echo $msgId; ?>" onkeyup="textKey(this)" class="textboxBorder mar_left_10p" id="replyText<?php echo $msgId; ?>" validate="validateStr" caption="Answer" maxlength="2500" minlength="15" required="true" rows="5" style="width:98%;"></textarea>
											</div>
											<div>
												<table width="100%" cellpadding="0" border="0">
												<tr>
												<td><span id="replyText<?php echo $msgId; ?>_counter">0</span> out of 2500 character</td>
												<td><div align='right'><span align="right">Make sure your reply follows <a href="javascript:void(0);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline');">Community Guidelines</a>&nbsp;</span></div></td>
												</tr></table>
											</div>
											<div class="errorPlace" style="display:block"><div class="errorMsg" id="replyText<?php echo $msgId; ?>_error"></div></div>
											<div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode<?php echo $msgId; ?>_error"></div></div>
											<input type="hidden" name="threadid<?php echo $msgId; ?>" value="<?php echo $msgId; ?>" />
											<input type="hidden" name="fromOthers<?php echo $msgId; ?>" value="user" />
											<input type="hidden" name="mainAnsId<?php echo $msgId; ?>" value="-1" />
											<input type="hidden" name="actionPerformed<?php echo $msgId; ?>" id="actionPerformed<?php echo $msgId; ?>" value="editAnswer" />
											<div style="padding-top:10px"><input type="Submit" value="Submit" class="submitGlobal" id="submitButton<?php echo $msgId; ?>" /> &nbsp; <input type="button" value="Cancel" class="cancelGlobal" onClick="hidereply_form('<?php echo $msgId; ?>','ForHiding');showAnswerForCMS('<?php echo $msgId; ?>')" /></div>
											</form>
										</div>									
								</div>
								<!--End_ReplyForm-->
								<?php }elseif($temp['status'] == 'closed'){
										echo '<span class="normaltxt_11p_blk">This question has been closed for answering.</span>';
									}else{
										//Check if Campus Rep is available on this Course. if yes, do not show the Answer Box.
										$campusRepExists  = 'false';
										if(is_array($courseIds[$temp['threadId']]) && $courseIds[$temp['threadId']]['courseId']>0){
											$courseid = $courseIds[$temp['threadId']]['courseId'];
								                	$campusRepExists = $caDiscussionHelper->checkIfCampusRepExistsForCourse(array($courseid));
								                	$campusRepExists  = $campusRepExists[$courseid];     
										}
										if($campusRepExists!='true'){
											$dataArray = array('userGroup' =>'enterprise','threadId' =>$temp['threadId'],'ansCount' => $temp['ansCount'],'detailPageUrl' =>$temp['url'],'callBackFunction' => 'try{ answerPostedSuccessfully('.$temp['threadId'].',request.responseText); } catch (e) {}','enterpriseView' => 'true');
											$this->load->view('messageBoard/InlineForm',$dataArray);
										}
									}
								?>		
							</div>
                            <!--End_yourFormInclude-->
                            <div class="clear_B lineSpace_1">&nbsp;</div>
                        </div>
                        <!--End_Question_Reating_Data-->
						<?php endforeach; ?>
                    </div>
                    <div class="clear_B lineSpace_5">&nbsp;</div>
                    <!--Start_Pagination_Code-->
						<div class="pagingID mar_full_10p lineSpace_18 txt_align_r" id="paginataionPlace2"></div>
                    <!--End_Pagination_Code-->
                    <div class="clear_B lineSpace_5">&nbsp;</div>
                </div>
        	</div>
        	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
    </div>
</div>
<!-- End of Page -->
<div class="lineSpace_10">&nbsp;</div>
<?php
	$totalCount = (isset($totalCount) && (trim($totalCount) != '') && (is_numeric($totalCount)))?$totalCount:0;
?>
<script>
doPagination(<?php echo $totalCount; ?>,'startOffsetForQuestion','countOffsetForQuestion','paginataionPlace1','paginataionPlace2','methodName',10);
var prodId = '<?php echo $prodId; ?>';
</script>
<?php $this->load->view('enterprise/footer'); ?>
