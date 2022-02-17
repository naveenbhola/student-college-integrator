<?php
		$bannerProperties = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'HEADER');
		$headerComponents = array(
						'css'	=>	array('raised_all','mainStyle'),
						'jsFooter'=>  array('discussion','commonnetwork','common','ana_common','userQandA'),
						'title'	=>	'Shiksha.com – Ask & Answer – Education and Career Forum Community – Study Forum – Education & Career Counselors – Career Counselling',
						'tabName' =>	'Discussion',
						'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
						'metaDescription'	=>'',
						'metaKeywords'	=>'',
						'product'	=>'forums',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
						'bannerProperties' => $bannerProperties,
						'questionText'	=> $questionText,	
                                                'callShiksha'=>1,
						'postQuestionKey' => 'ASK_USERQNA_HEADER_POSTQUESTION'
					);
		$this->load->view('common/homepage', $headerComponents);
		$data = array(
				'successurl'=> '',
				'successfunction'=>'',
				'id'=>'',
				'redirect'=> 1,
				
			    );
		$this->load->view('network/mailOverlay',$data);	
		$userDisplayName = isset($userDetails['displayname'])?$userDetails['displayname']:'';
		$shortDisplayName = (strlen($userDisplayName)<=10)?$userDisplayName:substr($userDisplayName,0,10);
		$this->load->view('common/userCommonOverlay');

?>
<script>
//Added by Ankur to add VCard on all AnA pages: Start
var userVCardObject = new Array();
</script>
<?php
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:'';
	$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
	if($userId != '')
		$loggedIn = 1;
	else
		$loggedIn = 0;
	$loggedUserId = ($userId!='')?$userId:0;
	echo "<script language=\"javascript\"> ";
	echo "var COMPLETE_INFO = ".$quickSignUser.";";
	echo "var URLFORREDIRECT = '".base64_encode($_SERVER['REQUEST_URI'])."';";	
	echo "var BASE_URL = '".base_url().""."';";
	echo "var displayName = '".$userDisplayName."';";
	echo "var loggedInUserId = ".$loggedUserId.";";
	echo "</script> ";
	
?>
<script>
var parameterObj = eval(<?php echo $parameterObj; ?>);
</script>
<input type="hidden" id="pageKeyForReportAbuse" value="ASK_USERQNA_RIGHTPANEL_REPORTABUSE" />
<input type="hidden" id="pageKeyForSubmitComment" value="ASK_USERQNA_RIGHTPANEL_SUBMITANSWER" />
<!--[if IE 7]>
<style>
	.searchTP{padding:0px 2px;color:#acacac;position:relative;top:-4px;height:18px;border:solid 1px #acacac;}
	.selectedCategory{background-image:url(/public/images/bgTab.gif); border:1px solid #FFFFFF;  padding:7px 5px 4px 5px;position:relative;top:-1px; font-weight:bold; color:#FFFFFF;}
	.selectedCategory span{position:relative; top:-3px}
	.unSelectedCategory{font-size:12px; font-family:Arial; text-decoration:none;color:#0265DD; padding:8px 5px 6px 5px;position:relative;top:-3px }
</style>
<![endif]-->
<?php  $this->load->view('common/overlay'); ?>
<!--Pagination Related hidden fields Starts-->
	<input type="hidden" autocomplete="off" id="qaStartFrom" value="0"/>
	<input type="hidden" autocomplete="off" id="qaCountOffset" value="20"/>
	<input type="hidden" autocomplete="off" id="methodName" value="<?php if($tabSelected == 'question') echo 'insertUserQuestions'; else echo 'insertUserAnswers'; ?>"/>
<!--Pagination Related hidden fields Ends  -->

<div style="line-height:1px">&nbsp;</div>
<?php
$questionsTabSelected = '';
$answersTabSelected = '';
 if($tabSelected == 'question'){ 
$questionsTabSelected = 'selected';
?>

<div class="fontSize_14p bld OrgangeFont mar_full_10p" ><span id="forumHeading">
		 <?php 
			if($totalQuestionAsked <= 0){
				echo $userDisplayName." has not asked any question";
			}else{
				echo "Showing ".$userDisplayName."'s ".(($totalQuestionAsked > 1)?($totalQuestionAsked." Questions"):($totalQuestionAsked." Question")); 
			} ?></span></div>
<?php }else{
 $answersTabSelected = 'selected';
 ?>
<!-- Start of Report abuse confirmation overlay -->
<div style="display:none;" id="abuseFormText"></div>
<div style="display:none;" id="reportAbuseConfirmationDiv">
	<div>
		<div style="padding:10px 10px 10px 10px">
		<div class="lineSpace_5p">&nbsp;</div>
		<div align="center"><span id="reportAbuseConfirmation" style="font-size:14px;"></span></div>
		<div class="lineSpace_5p">&nbsp;</div>
		<div align="center"><input type="button" value="OK" class="spirit_header RegisterBtn" onClick="javascript:hideOverlay();" /></div>
		<div class="lineSpace_5p">&nbsp;</div>
		</div>
	</div>
</div>
<!-- End of Report abuse confirmation overlay -->	
<div class="fontSize_14p bld OrgangeFont mar_full_10p" ><span id="forumHeading"> 
			<?php 
				if($totalQuestionAnswered <= 0){
					echo $userDisplayName." has not answered any question";
				}else{
					echo "Showing ".$userDisplayName."'s ".(($totalQuestionAnswered > 1)?($totalQuestionAnswered." Answers"):($totalQuestionAnswered." Answer")); 
				}?></span></div>	
<?php } ?>
<div class="lineSpace_10p">&nbsp;</div>
<div class="mar_full_10p">
	<!--Start_Left_Panel-->
		<?php
			 $this->load->view('messageBoard/userQandALeftPanel',array('userDetails' => $userDetails,'totalQuestions' =>$totalQuestions,'totalAnswers' => $totalAnswers)); 
		?>
	<!--End_Left_Panel-->


<?php  if($_COOKIE['client']<=800): ?>
					<div class="mar_full_10p">
							<div  style="line-height:24px">
								<div class="normaltxt_11p_blk_arial" style="float:right;display:none;" align="right">
										<span id="pagLabTop"><?php if($tabSelected == 'question'){ echo "Questions Per Page:"; }else{echo "Answers Per Page:";} ?></span> 
								<select name="countOffset" id="countOffset_DD1" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'qaStartFrom','qaCountOffset');">
									<option value="10">10</option>
									<option value="20" selected>20</option>
									<option value="30">30</option>
								</select>
                                </div>
                                <div class="pagingID txt_align_r" id="paginataionPlace1" style="line-height:23px;"></div>                                								    </div>
                        	</div>
							<div class="lineSpace_5">&nbsp;</div>
		<?php endif; ?>	
<!--Start_Mid_Panel-->
<div style="margin-left:164px;">			
		<!--BlogNavigation-->
<div>
	<div id="blogTabContainer">
			<table width="99%" cellspacing="0" cellpadding="0" border="0" height="25">
				<tr>
					<td>
							<div id="blogNavigationTab">
								<ul>								
									<li container="forum" tabName="forumQuestionTab" class="<?php echo $questionsTabSelected; ?>"><?php    echo "<a href=\"javascript:void(0)\" onClick=\"showUserQuestions();\" title=\"".$userDisplayName."\">".$shortDisplayName."'s Questions</a>";  ?></li>
									<li container="forum" tabName="forumAnswerTab"  class="<?php echo $answersTabSelected; ?>"><?php    echo "<a href=\"javascript:void(0)\" onClick=\"showUserAnswers();\" title=\"".$userDisplayName."\">".$shortDisplayName."'s Answers</a>"; ?></li>
								</ul>
							</div>
					
					</td>
					<td align="right">
						<?php if($_COOKIE['client']>=970){ ?>
						<table cellspacing="0" cellpadding="0" border="0" height="25">
						<tr>
							<td><div class="pagingID" id="paginataionPlace1" style="line-height:23px;"></div></td>
							<td style="line-height:23px;display:none;"><span id="pagLabTop"><?php if($tabSelected == 'question'){ echo "Questions Per Page:"; }else{echo "Answers Per Page:";} ?></span>
								<select name="countOffset" id="countOffset_DD1" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'qaStartFrom','qaCountOffset');">
									<option value="10">10</option>
									<option value="20" selected>20</option>
									<option value="30">30</option>
								</select>					
							</td>
						</tr>
						</table>
						<?php } ?>	
					</td>
				</tr>
			</table>
	</div>
	<div style="float:left; width:100%;">
		<div class="raised_lgraynoBG">
				<b class="b1"></b><b class="b2"></b><!--<b class="b3"></b><b class="b4"></b>-->
				<!--className boxcontent_lgraynoBG-->
				<div class="" id="mainContainer">
						<div class="lineSpace_10">&nbsp;</div>
						<!-- User Questions Start -->
						<div id="userQuestions">
						<?php if($tabSelected == 'question'){
							$this->load->view('messageBoard/userQuestionListing',array('userQuestion' => $userQuestion,'userDisplayName' => $userDisplayName)); 
						}
						if(($totalQuestionAsked <= 0)&&($tabSelected == 'question')){
						?>
                                                <div class="lineSpace_10p">&nbsp;</div><div class="fontSize_12p" align="center"><?php echo $userDisplayName; ?> has not asked any question</div>
						<?php } ?>
						</div>
						<!-- Question Questions End -->		
						<!-- Question Answers Start -->
						<div id="userAnswers">
							<?php if($tabSelected == 'answer'){
								$this->load->view('messageBoard/userAnswerListing',array('userAnswer'=>$userAnswer,'userDisplayName'=>$userDisplayName,'viewedUserId'=>$viewedUserId)); 
								}
							if(($totalQuestionAnswered <= 0)&&($tabSelected == 'answer')){
							?>
                                                        <div class="lineSpace_10p">&nbsp;</div><div class="fontSize_12p" align="center"><?php echo $userDisplayName; ?> has not answered any question</div>
							<?php } ?>
						</div>
						<!-- Question Answers Start -->
						<div class="lineSpace_10">&nbsp;</div>
						<div class="clear_L"></div>
						<div class="lineSpace_11">&nbsp;</div>
						<!-- code for pagination start -->
						<div class="mar_full_10p">
							<div class="row" style="line-height:24px">
								<div class="normaltxt_11p_blk_arial" style="float:right;display:none;" align="right">
										<span id="pagLabBottom"><?php if($tabSelected == 'question'){ echo "Questions Per Page:"; }else{echo "Answers Per Page:";} ?></span>
										<select name="countOffset" id="countOffset_DD2" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'qaStartFrom','qaCountOffset');">
												<option value="10">10</option>
		                                        <option value="20" selected>20</option>
		                                        <option value="30">30</option>
										</select>
                                </div>
                                <div class="pagingID" id="paginataionPlace2" align="right"></div>                                								    </div>
                        	</div>
							<div class="lineSpace_10">&nbsp;</div>
							<!-- code for pagination ends -->
						</div>
			</div>
			<div class="lineSpace_11">&nbsp;</div>
            <?php
            $bannerProperties1 = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'FOOTER');
            $this->load->view('common/banner',$bannerProperties1); 
            ?>
		</div>
	</div>
	<!--BlogNavigation-->
</div>

<!--End_Mid_Panel-->
<br clear="all" />
</div>
<!--End_Center-->

<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1); 
	if($tabSelected == 'question'){
		echo "<script>
			setStartOffset(0,'qaStartFrom','qaCountOffset');
			doPagination(".$totalQuestionAsked.",'qaStartFrom','qaCountOffset','paginataionPlace1','paginataionPlace2','methodName',2);
			showDiv(new Array('userQuestions','userAnswers'),0);
			</script>";
	}else{
		echo "<script> 
			setStartOffset(0,'qaStartFrom','qaCountOffset');
		doPagination(".$totalQuestionAnswered.",'qaStartFrom','qaCountOffset','paginataionPlace1','paginataionPlace2','methodName',2);		
			showDiv(new Array('userQuestions','userAnswers'),1);
			</script>";
	}
?>


