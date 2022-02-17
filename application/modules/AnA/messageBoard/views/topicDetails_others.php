<?php
				  $showListingTitleMessage = '';$showListingDescMessage='';
				$categoryIdForBanner = isset($CategoryList[array_rand($CategoryList)])?$CategoryList[array_rand($CategoryList)]:1;
				$topLeftSearchPanelFileData = array('infoWidgetData' => $infoWidgetData);
				$criteriaArray = array(
                                    'category' => $categoryIdForBanner,
                                     'country' => '',
                                     'city' => '',
                                     'keyword'=>'');
				   $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'HEADER','shikshaCriteria' => $criteriaArray);
				    $canonicalURLAdd = '';
				    if( isset($canonicalURL) && $canonicalURL!=''){
					$canonicalURLAdd = $canonicalURL;
				    }

				   $headerComponents = array(
				      'css'	=>	array('header','raised_all','mainStyle'),
				      'js' 	=>	array('common','discussion','ana_common','myShiksha','discussion_post','facebook'),
				      'title'	=>	$showListingTitleMessage.seo_url($topic_messages[0][0]['msgTxt']," "),
				      'tabName' =>	'Discussion',
				      'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
				      'metaDescription'	=>seo_url($topic_messages[0][0]['msgTxt']," ").'. Ask Questions on various education & career topics or find answers to questions related to education and career options from our education counselors and users in this education and career forum community.'.$showListingDescMessage,
				      'metaKeywords'	=>'Shiksha, Ask & Answer, Education, Career Forum Community, Study Forum, Education & Career Counselors, Career Counselling, study circle, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships',
				      'product'	=>'forums',
				      'bannerProperties' => $bannerProperties,
				      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
				      'questionText'	=> $questionText,	
				      'callShiksha'=>1,
				      'notShowSearch' => true,
				      'postQuestionKey' => 'ASK_ASKDETAIL_HEADER_POSTQUESTION',
				      'showBottomMargin' => false,
				      'canonicalURL' => $canonicalURLAdd,
                      'typeOfEntity' => ($fromOthersTopic=='user')?'question':$fromOthersTopic
				   );
if((count($main_message) <=0) || (isset($main_message['status'])) && (($main_message['status'] == 'deleted')||($main_message['status'] == 'abused')))
       {
                                 //header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
                                 //header("Status: 404 Not Found");
                                 //$_SERVER['REDIRECT_STATUS'] = 404;
		                //In case a question is deleted or abused, redirect the user to Ask homepage
				if($main_message['fromOthers']=='discussion'){
			                $url = SHIKSHA_ASK_HOME.'/messageBoard/MsgBoard/discussionHome/1/6/1/answer/';
				}
				else{
					$url = SHIKSHA_ASK_HOME.'/messageBoard/MsgBoard/discussionHome/1/7/1/answer/';
				}
		                header("Location: $url",TRUE,301);
                		exit;
       }

   $this->load->view('common/header', $headerComponents); ?>
<script>
var tab_required_course_page = '<?php echo $tab_required_course_page;?>';
var subcat_id_course_page = '<?php echo $subcat_id_course_page;?>';
var cat_id_course_page = '<?php echo $cat_id_course_page;?>';
var isShowAppBanner = 1; // using onload
</script>   
<?php   
    //$dataForHeaderSearchPanel = array('topLeftSearchPanelFileData' => $topLeftSearchPanelFileData);
    //$this->load->view('messageBoard/headerSearchPanelForAnA_quesDetail',$dataForHeaderSearchPanel);
    //$this->load->view('user/getUserNameImage');
	if($fromOthersTopic == 'discussion')
	  $tabselected = 6;
	else if($fromOthersTopic == 'announcement')
	  $tabselected = 7;
	$dataForHeaderSearchPanel = array('commonTabURL' => $tabURL,'tabselected' => $tabselected);
	$this->load->view('messageBoard/headerPanelForAnA',$dataForHeaderSearchPanel);

	$showDeleteMessage = false;
	if((count($main_message) <=0) || (isset($main_message['status'])) && (($main_message['status'] == 'deleted')||($main_message['status'] == 'abused')))
	{
	  $displayDeleteString = array('user'=>'This question no longer exists.','discussion'=>'This discussion no longer exists');
	  $displayAbuseString = array('user'=>'This question has been removed on account of report abuse.','discussion'=>'This discussion has been removed on account of report abuse.');
	  if(!(is_array($validateuser)))
	    $showDeleteMessage = true;
	  else if(strcmp($validateuser[0]['usergroup'],'cms') != 0)
	    $showDeleteMessage = true;
	  if($showDeleteMessage){
		//echo "This question no longer exists.";
	?>
	<div id="cse" style="display:none;" class="wrapperFxd"></div>
	<div id="topicDetails">
	    <div class="raised_greenGradient mar_full_10p"> 
		    <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		    <div class="boxcontent_greenGradient">
			    <div class="txt_align_c bld fontSize_18p" style="line-height:100px;">
			    <?php if($main_message['status'] != 'abused'){
				    if(count($main_message) > 0)
				      echo $displayDeleteString[$main_message['fromOthers']]; 
				    else
				      echo "This entity no longer exists.";
				  } 
				  else { 
				    echo $displayAbuseString[$main_message['fromOthers']];
				  } ?>
			    </div>
		    </div>
		    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	    </div>
	    <div style="line-height:300px;">&nbsp;</div>
	</div>
	<?php
		$footerData = array();
   		$bannerProperties1 = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'FOOTER');
		$this->load->view('common/footer',$bannerProperties1);  
		exit;
	  }
	}
	$loggedIn = 0;
	if($userId != 0)	
		$loggedIn = 1;
?>
<img id = 'beacon_img' width=1 height=1 >
<script>
   overlayViewsArray.push(new Array('user/getUserNameImage','updateNameImageOverlay'));
   var img = document.getElementById('beacon_img');
   var randNumForBeacon = Math.floor(Math.random()*Math.pow(10,16));
   img.src = '<?php echo BEACON_URL; ?>/'+randNumForBeacon+'/0003003/<?php echo $topicId; ?>+<?php echo $userId; ?>';
</script>

<?php 
	$quickSignUser = 0;
	$displayName = 0;
	if(is_array($validateuser))
	{
		$quickSignUser = $validateuser[0]['quicksignuser'];
		$displayName = $validateuser[0]['displayname'];	
	}
	$topicUrl = 'messageBoard/MsgBoard/topicDetails/'.$topicId; 	
	echo "<script language=\"javascript\"> "; 	
	echo "var BASE_URL = '".base_url().""."';";
	echo "var COMPLETE_INFO = ".$quickSignUser.";";	
	echo "var URLFORREDIRECT = '".base64_encode($topicUrl)."';";	
	echo "var jscategoryId = '".$categoryId."';";	
	echo "var loginRedirectUrl = '/messageBoard/MsgBoard/topicDetails';";			
	echo "var alertCount = '".$alertCount."';";	
	echo "var loggedIn = '".$loggedIn."';";	
	echo "var displayName = '".$displayName."';";
	echo "var pageViewed = 'detail';";
	echo "var alertCountForCreateTopic = '".$alertCountForCreateTopic."';"; 	
	echo "var alertStatusForTopic = '';";	 	
	if(isset($WidgetStatus['result']) && ($WidgetStatus['result'] == 1))
			echo "var alertStatusForTopic = '".$WidgetStatus['state']."';";	
	echo "</script> ";
?>
<!--Pagination Related hidden fields Starts-->
	<input type="hidden" autocomplete="off" id="startOffsetForQuestion" value="0"/>
	<input type="hidden" autocomplete="off" id="countOffsetForQuestion" value="10"/>
	<input type="hidden" autocomplete="off" id="topicId" value="<?php echo $topicId; ?>"/>
	<input type="hidden" autocomplete="off" id="totalNumOfRows" value="<?php echo $totalNumOfRows; ?>"/>
	<input type="hidden" autocomplete="off" id="methodName" value="showCommentsByPage"/>
<!--Pagination Related hidden fields Ends  -->
	<input type="hidden" autocomplete="off" id="showUpdateUserNameImage" value="" name="showUpdateUserNameImage"/>
	<div id="userNameImageDiv" style="display:none"></div>
	<div id="cse" style="display:none;" class="wrapperFxd"></div>
<?php
	if(($srcPage == 'askQuestion') && ($userId == $main_message['userId'])){
		if($actionDone == 'postQuestion'){
			$msgForActionComplete = 'Your question has been successfully posted.';
		}elseif($actionDone == 'editQuestion'){
			$msgForActionComplete = 'Your question has been successfully edited.';
		}elseif($actionDone == 'editdiscussion'){
			$msgForActionComplete = 'Your discussion has been successfully edited.';
		}elseif($actionDone == 'editannouncement'){
			$msgForActionComplete = 'Your announcement has been successfully edited.';
		}
?>
<div id="topicDetails">
  <div class="lineSpace_5">&nbsp;</div>
  <div class="wrapperFxd" id="confirmMsgForDelete" >
    <div class="mar_full_10p showMessages" >
	    <div class="float_R">
		    <img src="/public/images/crossImg.gif" style="position:relative;top:5px;cursor:pointer;" onClick="javascript:document.getElementById('confirmMsgForDelete').style.display='none';"/>
	    </div>
	    <div>
		    <?php echo $msgForActionComplete;  ?>
	    </div>
	    <div class="clear_B" style="line-height:1px">&nbsp;</div>
    </div>
  </div>
  <div class="lineSpace_5">&nbsp;</div>
</div>
<?php } ?>

<!--Start_MidContainer-->
<div class="wrapperFxd" id="topicDetailsPage">
    <!--Start_leftPanel-->
    <div class="mlr10">
		<div>
			<!--Start_Row_Float_Apply_into_Left_Panel-->
			<div class="float_L w705">
	                <?php 
						//$this->load->view('common/askQuestionForm');
						if($ACLStatus['LinkQuestion']=='live'){
							$this->load->view('common/discussionSearchOverlay_ForAnA',$displayData);
						}
					 	$this->load->view('messageBoard/topicDetails_centralPanel_others');  
						$threadId = $main_message['msgId'];
						if($srcPage != 'askQuestion'){				
						if(isset($_COOKIE['commentContent']) && (stripos($_COOKIE['commentContent'],'@$#@#$$') !== false)) {
							$commentContent = $this->security->xss_clean($_COOKIE['commentContent']);
							$cookieStuff1 = explode('@$#@#$$', $commentContent);
							$questionId = $cookieStuff1[0];
							$cookieStuff = explode('@#@!@%@', $cookieStuff1[1]);
							$parentId = $cookieStuff[0];
							$cookieStuff[0] = '';
							$content = '';
							foreach($cookieStuff as $stuff) {
								if($stuff != '') {
									$content .= ($content == '') ? $stuff : '@#@!@%@' .$stuff;
								}
							} 
						echo "<script language=\"Javascript\" type=\"text/javascript\">"; 
						echo "if(document.getElementById('replyText". $parentId ."')) ";
						echo "{";
						echo "reply_formForQues('".$parentId."'); \n";
						echo "document.getElementById('replyText". $parentId ."').value = '".escapeString($content)."'; \n"; 
						echo "if(".$questionId." != threadId) ";
						echo "{ ";
						echo "document.getElementById('replyText". $parentId ."').value = \"\"; \n";
						echo "} ";
						echo "} ";
						echo "</script>";
						}
						}
						?>
						<?php
							//if($srcPage != 'askQuestion'){
							//	$this->load->view('messageBoard/similarQuestion');
							//}
						?>
						<!--Start_Row_Float_Apply_into_Left_Panel-->
						<div style="line-height:1px;clear:both">&nbsp;</div>
						<!-- Start of banner -->
						<div class="float_R"><b>Disclaimer:</b> <a href="javascript:void(0);" style="color: rgb(112, 112, 112);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/termCondition');">Views expressed by the users above are their own, Info Edge (India) Limited does not endorse the same.</a></div>
						<div class="clear_B"></div>
						<div class="lineSpace_11">&nbsp;</div>
						<div>	
								<?php
									$bannerProperties1 = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'FOOTER');
									$this->load->view('common/banner',$bannerProperties1);  
								?>
						</div>
			<!-- End of banne -->
			</div>

			<!--Start_Right_Panel-->
			<div>
				<div class="float_R w250">
				<?php 	
					  $rightPanelArray = array();
					  $rightPanelArray['topicId'] = $topicId;
					  $rightPanelArray['topicName'] = $topicName;
					  $rightPanelArray['alertNameValue'] = $alertNameValue;
					  $rightPanelArray['alertId'] = $alertId;
					  $rightPanelArray['categoryId'] = $categoryId;
					  $rightPanelArray['userId'] = $userId;
					  $this->load->view('messageBoard/topicDetails_right',$rightPanelArray);
				?>
				</div>
				<div style="line-height:1px;clear:both">&nbsp;</div>
			</div>
			<!--End_Right_Panel-->
		</div>
	</div>
	<!--End_leftPanel-->
	<div style="line-height:1px;clear:both">&nbsp;</div>
</div>
<!--End_MidContainer-->

<script>getTopContributors(1,0);</script>

<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
	$requestUri = $this->security->xss_clean($_SERVER['REQUEST_URI']);
	$questionUrl = SHIKSHA_ASK_HOME_URL.$requestUri;
	if(is_array($validateuser))
	  $this->load->view('messageBoard/shareThisQuestion',array('threadId'=>$topicId,'msgTxt' => $topic_messages[0][0]['msgTxt'],'urlForQuestion' => $questionUrl,'display' => 'none', 'entityType' => $fromOthersTopic));
	$NameOFUser = '';
	$email='';
	if(is_array($validateuser)){
		$NameOFUser = (trim($validateuser[0]['firstname']) != '')?$validateuser[0]['firstname']:$validateuser[0]['displayname'];
		$userInfoArray = explode('|',$validateuser[0]['cookiestr']);
		$email = $userInfoArray[0];
	}
	$extraParams = base64_encode(serialize(array('questionUrl' => $questionUrl,'NameOFUser' => $NameOFUser)));
	$defaultSubject = 'Shiksha Q&A: '.$NameOFUser.' sent you a question';
	$bodyOfMail = " Hi ,\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I think you will really find following question interesting on Shiksha Question and Answers.\n\nThank you\n".$NameOFUser."";
	$maildataForOverlay = array('defaultSubject'=> $defaultSubject,
								'defaultEmailId' => $email,
								'defaultEmailContent' =>$bodyOfMail,
								'extraParams' => $extraParams,
								'validateFunction' => 'validateFields(this)',
								'beforeFunction' => '',
								'callBackFunction' => 'afterMailSend()',
								'type' => 'mailThisQuestion');
	//$this->load->view('common/mailOverlay',$maildataForOverlay);			
	$questionText = escapeStr($main_message['msgTxt']);
?> 
<script>
	//doPagination(<?php echo $totalNumOfRows; ?>,'startOffsetForQuestion','countOffsetForQuestion','paginataionPlace1','paginataionPlace2','methodName',10);
	var questionDetail =  '<?php echo $questionText; ?>';
	var tabSelected = '<?php echo $tabselected; ?>';
	var linkForQuestion = '<a href="<?php echo $questionUrl; ?>"><?php echo $questionUrl; ?></a>';
	<?php //if(($arrayOfParameters['sameUserQuestion'] == true) && ($bestAnsFlagForThread != 1) && ($arrayOfParameters['answerUserId'] != -1) && ($actionDone === 'bestAnswerSelectPopUp')) { ?>
		//window.onload= function(){showBestAnsOverLay("<?php echo $arrayOfParameters['answerId']; ?>","<?php echo $threadId; ?>","<?php echo $arrayOfParameters['answerUserId']; ?>");}
	<?php //} ?>
</script>

<!-- Start: Code added for Google Code in case of Management as per Ticket# 180 -->
<?php if(isset($catCountArray[0]['categoryId']) && $catCountArray[0]['categoryId']==3){ ?>
<!-- Google Code for MBA-General Remarketing List -->
<!--
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1053765138;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "666666";
var google_conversion_label = "bdFfCNrN6gEQkty89gM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1053765138/?label=bdFfCNrN6gEQkty89gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
-->
<?php } ?>
<!-- End: Code added for Google Code in case of Management as per Ticket# 180 -->

<!-- Code to bring the focus to Comment box in case this page is opened from Listing detail page -->
<script>
(function($j) {
  if(window.location.hash) {
      var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
      if(hash=='answerFormDetailPage1'){
        	showAnswerCommentForm('<?php echo $topic_messages[0][0]['msgId']; ?>');
      }
  }
})($j);  
</script>
<!-- Code End to bring the focus to Comment box in case this page is opened from Listing detail page -->
