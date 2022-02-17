<?php
				  $showListingTitleMessage = '';$showListingDescMessage='';
				  if($main_message['listingTitle'] != ""){
					  $showListingTitleMessage = $main_message['listingTitle']." | ";
					  $showListingDescMessage = " @ ".$main_message['listingTitle'];
				  }
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
				
				  $noIndexMetaTag = false;
			          if(isset($noIndexQuestion) && $noIndexQuestion) {
					$noIndexMetaTag = true;
				  }

				  $product = '';
				  if($campusConnectAvailable) {
				  	$cssArray = array('question_detail');
				  }else {
				  	$cssArray = array('header','raised_all','mainStyle');
				  	$product = 'forums';
				  }
				if($questionDescription != ""){
					$metaDescription = substr($questionDescription, 0, 150);
				}  else {
					$metaDescription = 'Read all answers to question: '.substr(seo_url($main_message['msgTxt']," "), 0, 120);

				} 
				   $headerComponents = array(
				      'css'	=>	$cssArray,
				      'js' 	=>	array('common','discussion','ana_common','myShiksha','discussion_post','facebook','ajax-api','national_listings','processForm'),
				      'title'	=>	seo_url($main_message['msgTxt']," "),
				      'tabName' =>	'Discussion',
				      'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
				      'metaDescription'	=> $metaDescription,	
				      'metaKeywords'	=>'Shiksha, Ask & Answer, Education, Career Forum Community, Study Forum, Education & Career Counselors, Career Counselling, study circle, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships',
				      'bannerProperties' => $bannerProperties,
				      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
				      'questionText'	=> $questionText,	
				      'callShiksha'=>1,
				   	  'product' => $product,
				      'notShowSearch' => true,
                                      'postQuestionKey' => 'ASK_ASKDETAIL_HEADER_POSTQUESTION',
                                      'showBottomMargin' => false,
                                      'isMasterList' => $isMasterList,
				      'canonicalURL' => $canonicalURLAdd,
                      'publishDate' => $publishDate,
                      'typeOfEntity' => ($fromOthersTopic=='user')?'question':$fromOthersTopic,
		      			'noIndexMetaTag'	=> $noIndexMetaTag,
		      			'alternate' => $alternate
				   );

   $this->load->view('common/header', $headerComponents);
   echo jsb9recordServerTime('SHIKSHA_CAFE_QUESTION_DETAIL',1);
    //$dataForHeaderSearchPanel = array('topLeftSearchPanelFileData' => $topLeftSearchPanelFileData);
    //$this->load->view('messageBoard/headerSearchPanelForAnA_quesDetail',$dataForHeaderSearchPanel);
   $dataForHeaderSearchPanel = array();
    $dataForHeaderSearchPanel = array('commonTabURL' => $tabURL,'tabselected' => 1);
    
    if(!$campusConnectAvailable) {
    	$this->load->view('messageBoard/headerPanelForAnA',$dataForHeaderSearchPanel);
    }
    //$this->load->view('user/getUserNameImage');
	$showDeleteMessage = false;
        //Hidden Div to be included for Google searching of MasterList questions Starts
 if($isMasterList == 'present'){?>
<div id="googleMasterListTag" style="display:none">
    MasterList Question
</div>
<?php

}
//Hidden Div to be included for Google searching of MasterList questions Ends
	if((count($main_message) <=0) || (isset($main_message['status'])) && (($main_message['status'] == 'deleted')||($main_message['status'] == 'abused')))
	{
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
			    <?php if($main_message['status'] != 'abused'){ ?>
			    This question no longer exists.
			    <?php }else{ ?>
			    This question has been removed on account of report abuse.
			    <?php } ?>
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
var national_listings_obj = new national_listings();

   overlayViewsArray.push(new Array('user/getUserNameImage','updateNameImageOverlay'));
   var img = document.getElementById('beacon_img');
   var randNumForBeacon = Math.floor(Math.random()*Math.pow(10,16));
   img.src = '<?php echo BEACON_URL; ?>/'+randNumForBeacon+'/0003003/<?php echo $topicId; ?>+<?php echo $userId; ?>';
   currentPageName = 'QUES DETAIL PAGE';
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
	<?php if(!empty($courseObj)):?>
		<input type="hidden" id="courseId" value="<?php echo $courseObj->getId();?>"/>
	<?php endif;?>
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
		<!--Start_Row_Float_Apply_into_Left_Panel-->
			<div class="ana-left-col">
	                <?php 
						//$this->load->view('common/askQuestionForm');
						if($ACLStatus['LinkQuestion']=='live'){
							$this->load->view('common/questionSearchOverlay_ForAnA',$displayData);
						}
						if($campusConnectAvailable) { 
					 		$this->load->view('messageBoard/topicDetails_centralPanel_new',array('subCatID'=>$CategoryList[1]));
						}else {
							$this->load->view('messageBoard/topicDetails_centralPanel',array('subCatID'=>$CategoryList[1]));
						}  
						
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
						if(!$campusConnectAvailable) {
							if($srcPage != 'askQuestion'){
                            					if($typeOfSearch == 'QER')
                                					$this->load->view('messageBoard/relatedQuestionFromShikshaSearch');
                                				else
							    		$this->load->view('messageBoard/relatedQuestionFromGoogle');
							    		//$this->load->view('messageBoard/similarQuestion');
								}
						}
						?>
                        
			<?php
			$defaultQuestionText = "Need expert guidance on education or career? Ask our experts.";
			?>
			
			<?php if(!$campusConnectAvailable):?>
						<!--Start_Row_Float_Apply_into_Left_Panel-->
                        <form id="askQuestionForm" name="askQuestionForm" method="get" action="" onsubmit="checkTextElementOnTransition($('questionTextQnADetails'),'focus');try{ var questionTextValue = document.getElementById('questionTextQnADetails').value; if(!validateQuestionForm_old(this,'<?php echo $headerComponents['postQuestionKey']; ?>','formsubmit','askQuestionForm')){return false;}else{ setCookieForFB('question');proceedToPostQuestion(this,'questionTextQnADetails'); return false;} }catch (e){ return false;}" style="margin:0;padding:0" novalidate="novalidate">
                        <div id="atab1_t" class="ask-ques-pannel">
                            <h4>Didn't find the right answer? <strong>Ask your own Question</strong></h4>
                            
			    <textarea class="universal-select" style="color:#959494" name="questionText" id="questionTextQnADetails" autocomplete="off"
                onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }" profanity="true"
                validate="validateStr" caption="Question" maxlength="140" minlength="2" required="true"
                onfocus="checkTextElementOnTransition(this,'focus');"
                onblur="checkTextElementOnTransition(this,'blur');"
                default="<?php echo $defaultQuestionText; ?>"  validateSingleChar='true'><?php echo $defaultQuestionText; ?></textarea>

                            <input name="referalUrlForAskQuestionFromHeader" id="referalUrlForAskQuestionFromHeader" type="hidden" value="<?php echo $base64url; ?>"/>
                            <input type = "hidden" id = "googleSearchCompleteArray" name= "googleSearchCompleteArray" value = ""/>
                            <div class="clearFix spacer5"></div>
                            <div class=" Fnt10 fcdGya"><span id="questionTextQnADetails_counter">0</span> out of 140 characters</div>
                            <div class="row errorPlace"><span id="questionTextQnADetails_error" class="errorMsg">&nbsp;</span></div>
                            <div class="clearFix spacer5"></div>
			    <?php
			    if($tab_required_course_page){ $crs_pg_prms =  $CategoryList[1].'_'.$CategoryList[0];
			    ?>
		            <input type="hidden" name="crs_pg_prms" id="crs_pg_prms" value="<?php echo $crs_pg_prms; ?>"/>
			    <?php
			    }
			    ?>
                            <input type="submit" value="Get an Answer" class="orange-button" onClick="trackEventByGA('ASK_NOW_BUTTON','QUESTION_DETAIL_PAGE_BOTTOM');"/>
                        <input type="hidden" name="tracking_keyid" id="tracking_keyid" value="104">
                        </div>
                        </form>
                       
                        
                        <div id='relatedDiscussionDiv'>
                        <!--<script>getRelatedDiscussions('<?php echo $CategoryList[0];?>','<?php echo $CategoryList[1];?>','<?php echo $catCountArray[0][countryId];?>');</script>
                        <div class="raised_all">
                        <b class="b2"></b><b class="b3"></b><b class="b4"></b>
                        <div class="boxcontent_all">
                        <div class="defaultAdd lineSpace_5">&nbsp;</div>
                        <div align="center"><img src="/public/images/loader.gif"/></div>
                        <div class="defaultAdd lineSpace_5">&nbsp;</div>
                        </div>
                        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
                        </div>
                        <div class="spacer20 clearFix"></div>-->
		        <?php echo Modules::run('RelatedDiscussion/RelatedDiscussion/index', $CategoryList[0], $CategoryList[1], $catCountArray[0][countryId]); ?>

                        </div>
			<!--      QUICK LINKS WIDGET      -->
                        <?php if(!empty($quickLinks)) echo $this->load->view("messageBoard/quickLinks",array('articleWidgetsData' => $quickLinks)); ?>
			<!--      QUICK LINKS WIDGET      -->
						<div class="spacer15 clearFix"></div>
						<!-- Start of banner -->
						<div style="font-style:italic;"><strong>Disclaimer:</strong> <a href="javascript:void(0);" style="color: rgb(112, 112, 112);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/termCondition');">Views expressed by the users above are their own, Info Edge (India) Limited does not endorse the same.</a></div>
						<div class="spacer10 clearFix"></div>
						
						<div>	
								<?php
									$bannerProperties1 = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'FOOTER');
									$this->load->view('common/banner',$bannerProperties1);  
								?>
						</div>
						
			<?php endif; ?>
			<!-- End of banne -->
			</div>
		<!--End_leftPanel-->
			<!--Start_Right_Panel-->
			<?php if(!$campusConnectAvailable):?>
				<div class="ana-right-col" id="ana-right-col">
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
			<?php endif;?>	
			<!--End_Right_Panel-->
	</div>
	<div class="clearFix"></div>
</div>
<!--End_MidContainer-->

<?php 
 	$dataForRankAndReputation = array('reputationPonits' => $reputationPoints,'rank' => $rank); 
 	$this->load->view('messageBoard/reputationOverlay',$dataForRankAndReputation); 
?> 

<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'','loadJQUERY'=>'YES');
	$this->load->view('common/footer',$bannerProperties1);

?>

                                <!-- Floating Registration Widget -->
                                <div id="floatingRegister">
                                        <script>
                                                var jsForWidget = new Array();
                                                jsForWidget.push('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("processForm"); ?>');
                                                addWidgetToAjaxList('/FloatingRegistration/FloatingRegistration/index/false/750/ana-right-col/\'\'/true/0/institute/\'\'/<?php echo $bottomregtrackingPageKeyId;?>','floatingRegister',jsForWidget);
                                        </script>
                                </div>

 
      <!-- Added for the JQuery Accordion widget in Cafe Stars --> 
      <?php if($fromOthersTopic!='announcement' && $fromOthersTopic!='discussion' && $fromOthersTopic!='review' && $fromOthersTopic!='eventAnA'){ ?> 
      <script src="/public/js/zebra_accordion.src.js"></script> 
      <!--Call_New_Function_for_3_Experts--> 
      <script> 
            //getExpertTopContributors(1,0, '<?php echo $categoryId; ?>'); 
      </script> 
      <?php }else{ ?> 
	    <script>getTopContributors(1,0);</script> 
      <?php } ?> 
<?php 
	$requestUriCleaned = $this->security->xss_clean($_SERVER['REQUEST_URI']);
	$questionUrl = SHIKSHA_ASK_HOME_URL.$requestUriCleaned;
	if(is_array($validateuser))
	  $this->load->view('messageBoard/shareThisQuestion',array('threadId'=>$topicId,'msgTxt' => $main_message['msgTxt'],'urlForQuestion' => $questionUrl,'display' => 'none'));
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
	<?php if(($arrayOfParameters['sameUserQuestion'] == true) && ($bestAnsFlagForThread != 1) && ($arrayOfParameters['answerUserId'] != -1) && ($actionDone === 'bestAnswerSelectPopUp')) { ?>
		window.onload= function(){showBestAnsOverLay("<?php echo $arrayOfParameters['answerId']; ?>","<?php echo $threadId; ?>","<?php echo $arrayOfParameters['answerUserId']; ?>");}
	<?php } ?>
	
        /**
         * For Institute Suggestor
         */
	if(typeof(initializeAutoSuggestorInstance) == "function") {
	        initializeAutoSuggestorInstance(); //For initiating AutoSuggestor Instance
	}
        //Event listener for hiding dropdown suggestions when user clicks outside the suggestion container
        if(typeof(handleClickForAutoSuggestor) == "function") {
            if(window.addEventListener){
                document.addEventListener('click', handleClickForAutoSuggestor, false);
            } else if (window.attachEvent){
                document.attachEvent('onclick', handleClickForAutoSuggestor);
            }
        }    
	
</script>
<?php
    echo "<script>var floatingRegistrationSource = 'ASK_FLOATINGWIDGETREGISTRATION';</script>";
    if(isset($catCountArray[0]['countryId']) && $catCountArray[0]['countryId']>2)
          echo "<script>var studyAbroad = 1;</script>";
    else
          echo "<script>var studyAbroad = 0;</script>";
?>

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
<script>function QnAQuestionWidget(ref,val){ 
	if(val=='focus'){
		if($('questionText').innerHTML=='Need expert guidance on education or career? Ask our experts.' || $('questionText').innerHTML=='')
			$('questionText').innerHTML='';
		else
			$('questionText').innerHTML="<?php echo $headerComponents['questionText']; ?>";
	}else{
		$('questionText').innerHTML = 'Need expert guidance on education or career? Ask our experts.';
	}
}

var tab_required_course_page = '<?php echo $tab_required_course_page;?>';
var subcat_id_course_page = '<?php echo $subcat_id_course_page;?>';
var cat_id_course_page = '<?php echo $cat_id_course_page;?>';
</script>
<!-- End: Code added for Google Code in case of Management as per Ticket# 180 -->


<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.Placeholders"); ?>"></script>


<!--added by akhter for campusRep widget, if rep is available-->
<?php if(isset($campusConnectAvailable) && $campusConnectAvailable){?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinycarousel.min"); ?>"></script>
<script>
$j(document).ready(function($j) {
    if($j('#inst-crWidgetSlider').length >0) {
		$j('#inst-crWidgetSlider').tinycarousel({ display: 3 });
    }
});

function stopClick(NavId,SliderID,event)
{
  if($j("#"+SliderID).find("#"+NavId).hasClass("disable"))
	{ 
		event.stopImmediatePropagation();
		return false;
	}
}
</script>
<?php }?>

