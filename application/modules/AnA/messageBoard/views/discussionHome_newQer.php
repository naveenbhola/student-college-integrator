<?php
		$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
		$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
		if($userId != 0){
			$tempJsArray = array('commonnetwork','alerts','ana_common','myShiksha', 'discussion_post');
			$loggedIn = 1;
		}else{
			$tempJsArray = array('commonnetwork','ana_common','myShiksha', 'discussion_post');
			$loggedIn = 0;
		}
		$noIndexNoFollowCheck = false;
		if($tabselected == 3 || ($tabselected==1 && $countryId>=3)){
			$noIndexNoFollowCheck = true;
		}
		
		if(!$tab_required_course_page) {
			$metaTitle = 'Ask and Answer - Education Career Forum Community – Study Forum – Education Career Counselors – Study Circle -Career Counseling';
			$metKeywords = 'Ask and Answer, Education, Career Forum Community, Study Forum, Education Career Counselors, Career Counseling, study circle, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships, shiksha';	
			$metaDescription = 'Ask Questions on various education and career topics or find answers to questions related to education and career options from our education counselors and users in this education and career forum community.';
			$myCanonical = $canonicalurl;
		}
		
		$bannerProperties = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'HEADER');
		$headerComponents = array(
						'css'	=>	array('raised_all','mainStyle','header'),
                        			'js' => array('common','discussion','facebook','ajax-api'),
						'jsFooter'=>    $tempJsArray,
						'title'	=> $metaTitle,
						'tabName' =>	'Discussion',
						'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
						'metaDescription' => $metaDescription,
						'metaKeywords'	=> $metKeywords,
						'product'	=>'forums',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
						'bannerProperties' => $bannerProperties,
						'questionText'	=> $questionText,
						'callShiksha'=>1,
						'notShowSearch' => true,
						'postQuestionKey' => 'ASK_ASKHOME_HEADER_POSTQUESTION',
						'showBottomMargin' => false,
						'noIndexNoFollow' => $noIndexNoFollowCheck,
						//'nextURL'=>$nexturl,
						//'previousURL'=>$previousurl,
						'canonicalURL'=> $myCanonical,
						'nextURL'=>$nexturl,
						'previousURL'=>$previousurl
					);
		$this->load->view('common/header', $headerComponents);
		if($tabselected == 0){
		echo jsb9recordServerTime('SHIKSHA_CAFE_BUZZ_TAB',1);
		}elseif($tabselected == 1){
                echo jsb9recordServerTime('SHIKSHA_CAFE_QNA_TAB',1);
		}
		//$topLeftSearchPanelFileData = array('infoWidgetData' => $infoWidgetData);
		//$dataForHeaderSearchPanel = array('topLeftSearchPanelFileData' => $topLeftSearchPanelFileData);
		$commonTabURL = str_replace('@qnaTab@',$myqnaTab,$tabURL);
		$headerTabURL = site_url('messageBoard/MsgBoard/discussionHome')."/1/@tab@/1/".$myqnaTab."/";
		$dataForHeaderSearchPanel = array('commonTabURL' => $headerTabURL);
		$this->load->view('messageBoard/headerPanelForAnA',$dataForHeaderSearchPanel);
		$data = array(
				'successurl'=> '',
				'successfunction'=>'',
				'id'=>'',
				'redirect'=> 1,
				
			    );
		//$CategoryArray = array('catCountURL'=>$catCountURL,'selectedCategory'=>$categoryId,'selectedCountry'=>$countryId);
		//$this->load->view('network/mailOverlay',$data);
		//$this->load->view('common/commonOverlay',$CategoryArray);
		//$this->load->view('user/getUserNameImage');
		if($tabselected==4)
		{
			if($myqnaTab=='question')
				$totalCount = $topicListings['totalQuestions'];
			elseif($myqnaTab=='answer')
				$totalCount = $topicListings['totalQuestionsAnswered'];	
			elseif($myqnaTab=='bestanswer')
				$totalCount = $topicListings['totalBestAnswers'];
                        elseif($myqnaTab=='untitledQuestion'){
                               
                                $totalCount = $topicListings['totaluntitledQuestions'];
                               
                        }
		}else if($tabselected==5){
                    if($topicListings['totaluntitledQuestions'])
                                $totalCount = $topicListings['totaluntitledQuestions'];
                                else
                                $totalCount = $tmp['totaluntitledQuestions'];

                }
        elseif($tabselected == 6 && $categoryId ==0)
        {
        	$totalCount = (is_array($topicListings))?$topicListings[0]['totalTopicCount']:0;
        }
		else if(($tabselected == 6 || $tabselected == 7) && ($categoryId>1)){
			$totalCount = (is_array($topicListings))?$topicListings[0]['totalTopicCount']:0;
		}else if($tabselected==4){
                                if($myqnaTab=='untitledQuestion')
                                $totalCount = $topicListings['totaluntitledQuestions'];
                }
		else{
			$totalCount = $topicListings['totalCount'];
		}
		if($tabselected==6 || $tabselected==7){	
			if($tab_required_course_page && in_array($tabselected,array(1,6))) {		
		    	$paginationHTML = doCoursepagePaginationCafe($totalCount,$paginationURL,$start,$rows,4);
			} else {
				$paginationHTML = doPaginationCafe($totalCount,$paginationURL,$start,$rows,4);
			}
		    $classHomepage = "";
		    $showCategory = false;
		    $showRightPanel = false;
		}
		else{						
			if($tab_required_course_page && in_array($tabselected,array(1,6))) {				
		    	$paginationHTML = doCoursepagePagination($totalCount,$paginationURL,$start,$rows,3);
			} else {
				$paginationHTML = str_replace('default/0/20','',doPagination($totalCount,$paginationURL,$start,$rows,3));
			}
		    $classHomepage = "ana-left-col";
		    $showCategory = true;
		    $showRightPanel = true;
		}

                
		
		
?>
<script>
var facebook_channel_path = "<?php echo FB_CHANNEL_PATH; ?>";
overlayViewsArray.push(new Array('network/mailOverlay','mailOverlay'));
overlayViewsArray.push(new Array('user/getUserNameImage','updateNameImageOverlay'));
overlayViewsArray.push(new Array('common/userCommonOverlay','userCommonOverlayForVCard'));
</script>

<?php
	$isCmsUser = 0;
	if($userGroup === 'cms')$isCmsUser = 1;
	echo "<script language=\"javascript\"> ";
	echo "var BASE_URL = '';";
	echo "var COMPLETE_INFO = ".$quickSignUser.";";
	echo "var URLFORREDIRECT = '".$pageUrl."';";	
	echo "var loginRedirectUrl = '/messageBoard/MsgBoard/discussionHome';";
	echo "var jscategoryId = '".$categoryId."';";
	echo "var jscountryId = '".$countryId."';";
	echo "var jscatCountURL = '".$catCountURL."';";
	echo "var loggedIn = '".$loggedIn."';";		
	echo "var loggedInUserId = '".$userId."';";
	echo "var alertCount = '".$alertCount."';";
	echo "var isCmsUser = '".$isCmsUser."';";
	echo "</script> ";
	
?>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("search"); ?>" type="text/css" rel="stylesheet" />
<input type="hidden" id="pageKeyForSubmitComment" value="ASK_ASKHOME_MIDDLEPANEL_SUBMITANSWER" />
<!--Pagination Related hidden fields Starts-->
<input type="hidden" autocomplete="off" id="categoryForQuestion" value="<?php echo $categoryId ?>"/>
<input type="hidden" autocomplete="off" id="countryForQuestion" value="<?php echo $countryId ?>"/>
<!--Pagination Related hidden fields Ends  -->
<input type="hidden" autocomplete="off" id="showUpdateUserNameImage" value="" name="showUpdateUserNameImage"/>
<input type="hidden" name="questionDetailPage" id="questionDetailPage" value="" />
<div id="userNameImageDiv" style="display:none"></div>
<!-- Start of Dig up and YOU image tooltip overlay -->
<div id = "youTooltip" class="blur" style="width:192px;position:absolute;left:0px;z-index:1000;display:none;top:0px;">
  <div class="shadow">
    <div class="content">&nbsp;click to change your display name</div>
  </div>
</div>
<div id = "digUpDownTooltip" class="blur" style="position:absolute;left:0px;z-index:1000;display:none;top:0px;">
  <div class="shadow">
    <div class="content" id="digTooltipContent"></div>
  </div>
</div>
<!-- End of Dig up and YOU image tooltip overlay -->

<script>
var userVCardObject = new Array();
//var completeCategoryTree = eval(<?php echo $categoryForLeftPanel; ?>);
//var parameterObj = eval(<?php echo $parameterObj; ?>);
//var completeCountryTree = eval(<?php echo $countryList; ?>);
</script>
	
<!-- Block Start to show the action done message -->
		<?php if(($actionDone == 'deleteQuestion') && ($userId > 0)){ ?>
		<div class="mar_full_10p showMessages" id="confirmMsgForDelete">
			<div class="float_R">
				<img src="/public/images/crossImg.gif" style="position:relative;top:5px;cursor:pointer;" onClick="javascript:document.getElementById('confirmMsgForDelete').style.display='none';"/>
			</div>
			<div>
				Your question has been successfully deleted.
			</div>
			<div class="clear_B" style="line-height:1px">&nbsp;</div>
		</div>
		<?php } ?>
		<!-- Block End to show the action done message -->

		<?php
			$userProfile = site_url('getUserProfile').'/';
			$selectedCountryName = ($selectedCountryName == 'All')?'All countries':$selectedCountryName;
			$selectedCategoryName = ($selectedCategoryName == 'All')?'All categories':$selectedCategoryName;

			if($categoryId == 0)
			{
				$selectedCategoryName = "Miscellaneous";
			}		
			$newRepliesCount = $topicListings['newRepliesCount'];
			$recentTabNumbs = array(1,3);
		?>
		<div style="display:none;">
		      <?php $this->load->view('messageBoard/autoSuggestorForInstitute',array('movable'=>'true'));  ?>
		</div>

		<div class="mar_full_10p">
			  
			  <!--Start_Mid_Panel-->
			  <div class="<?php echo $classHomepage;?>">

				  <!-- Block start for displaying the question post success message -->
                  <!-- The Message below has been commented since now we are redirecting the user to the Detail pages -->
				  <?php
				  if( (isset($_COOKIE['questionPosted'])) && ($_COOKIE['questionPosted']=="true") ){ 
					setcookie  ('questionPosted','',time()-3600,'/',COOKIEDOMAIN);
				  ?>
				  <!--<div style="line-height:1px">&nbsp;</div>
				  <div class="showMessages Fnt12" style="margin-bottom:5px;" id="postMsgSuccess">Your question has been successfully posted.</div>
				  <script>
				    window.setTimeout(function(){document.getElementById('postMsgSuccess').style.display = 'none';}, 5000);
				  </script>-->
				  <?php  } 

				  if( (isset($_COOKIE['discussionPosted'])) && ($_COOKIE['discussionPosted']=="true") ){ 
					setcookie  ('discussionPosted','',time()-3600,'/',COOKIEDOMAIN);
				  ?>
				  <!--<div style="line-height:1px">&nbsp;</div>
				  <div class="showMessages Fnt12" style="margin-bottom:5px;" id="postMsgSuccess">Your discussion has been successfully posted.</div>
				  <script>
				    window.setTimeout(function(){document.getElementById('postMsgSuccess').style.display = 'none';}, 5000);
				  </script>-->
				  <?php  } 

				  if( (isset($_COOKIE['announcementPosted'])) && ($_COOKIE['announcementPosted']=="true") ){ 
					setcookie  ('announcementPosted','',time()-3600,'/',COOKIEDOMAIN);
				  ?>
				  <!--<div style="line-height:1px">&nbsp;</div>
				  <div class="showMessages Fnt12" style="margin-bottom:5px;" id="postMsgSuccess">Your announcement has been successfully posted.</div>
				  <script>
				    window.setTimeout(function(){document.getElementById('postMsgSuccess').style.display = 'none';}, 5000);
				  </script>-->
				  <?php  } 
				  ?>
				  <!-- Block end for displaying the question post success message -->
      
				  <!-- Block start for Google search results display -->
				  <?php /*if(isset($_REQUEST) && isset($_REQUEST['cx']) && isset($_REQUEST['cof'])){ ?>
				  <div id="cse-search-results"></div>
				  <script type="text/javascript">
				    var googleSearchIframeName = "cse-search-results";
				    var googleSearchFormName = "cse-search-box";
				    var googleSearchFrameWidth = 600;
				    var googleSearchDomain = "www.google.com";
				    var googleSearchPath = "/cse";
				  </script>
				  <script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>
				  <style type="text/css"> 
				    #cse-search-results iframe {width: 705px; }
				  </style>
				  <?php }*/
                  if(is_array($googleRes) && !empty($googleRes) && $googleRes['general']['numfound']>=1)
                  {
                    
                    ?>
                  
                            
		<?php		$userProfile = site_url('getUserProfile').'/';
			?>	 

					<!--Start_SimilarQuestion-->				
						<div class="result-section" >
                                <h5 class="search-result-title" style="display:block;float:left;">
                                <span>
								<?php echo $googleRes['general']['numfound'];?><?php if(count($googleRes['general']['numfound'])==1)
                                {echo " Question ";}else{echo " Questions ";}?>
                                </span> found				
                                </h5>
                                 <div class="pagingID txt_align_r" id="paginataionPlace1" style="line-height:23px;">&nbsp;<?php echo $paginationHTMLForGoogle;?></div>
                                 <div class="spacer5 clearFix"></div>
                                <div style="background: #EEE;height:1px; overflow:hidden; width:100%"></div>
                                <div class="spacer10 clearFix"></div>
                                    <ul>
                                  <?php  $this->load->view('qNaResult.php',$googleRes); ?>
                                  </ul>

																
					</div>					
					<div class="pagingID txt_align_r" id="paginataionPlace2" style="line-height:23px">&nbsp;<?php echo $paginationHTMLForGoogle;?></div>

				<?php  }else if(is_array($googleRes) && $googleRes['general']['numfound']==0) {
                ?>
                                
                                <div class="Fnt16">No standard web pages containing all your search terms were found.</div>
                                <?php $keyword = $this->security->xss_clean($_REQUEST['keyword']); ?>
                                <div class="Fnt16" style="margin-top:25px;">Your Search - <b><?php echo urldecode($keyword);?></b> - did not match any documents.</div>
                                <p class="Fnt16">Suggestions</p>
                                <div>
                                        <ul type="disc">
                                                <li class="Fnt16">Make sure all words are spelled correctly.</li>
                                                <li class="Fnt16">Try different keywords.</li>
                                                <li class="Fnt16">Try more general keywords.</li>
                                        </ul>
                                </div>
                                <?php
                                }else{ ?>
				  <!-- Block End for Google search results display -->

				  <!-- Block Start for Ask Question widget -->
				  <div style="line-height:1px">&nbsp;</div>
				  <?php
					if($tabselected == 0){ 
				  ?>
					    <!--<div class="Fnt16 bld pt5 mb10">At Shiksha Cafe, interact with experts or simply hang out with fellow students!</div>-->
                        <div class="qna-widget">
                        <h1 class="qna-header">
                            <div class="anaCup">Ask and Answer: Interact with experts or simply hang out with fellow students!</div>
                        </h1>
				  <?php
					    $entity = (isset($_COOKIE['entitytype']))?$_COOKIE['entitytype']:'question';
					    $displayData['entity'] = $entity;
					    $displayData['displayHeading'] = "true";
					   //below line is used to pass the conversion tracking page key id to hidden field
				 	 	$displayData['trackingPageKeyId']=$qtrackingPageKeyId;
				 	 	$displayData['atrackingPageKeyId']=$atrackingPageKeyId;
				 	 	$displayData['dtrackingPageKeyId']=$dtrackingPageKeyId;
				 	 	$displayData['qtrackingPageKeyId']=$qtrackingPageKeyId;
						
					    $this->load->view('common/askCafeForm',$displayData);
						
						?></div>
                        <div class="spacer10 clearFix"></div>
                        <?php 
					    //Added by Ankur for Homepage Rehash :Showing discussion/announcement when asked from Shiksha Homepage
					    if(isset($_COOKIE['homepageDiscussionTitle']) && $_COOKIE['homepageDiscussionTitle']!=''){
						
						$homepageDiscussionTitle = $this->security->xss_clean($_COOKIE['homepageDiscussionTitle']); ?>
						
						<script>
						openTab($('atab2'), 'tab2');
						$('questionTextD').value = base64_decode('<?php echo $homepageDiscussionTitle; ?>');
						//$('questionTextD').style.color = '';
						$('questionTextD').focus();
						setCookie('homepageDiscussionTitle', '',-1);
						</script>
						<?php
					    }
					    else if(isset($_COOKIE['homepageAnnouncementTitle']) && $_COOKIE['homepageAnnouncementTitle']!=''){

						$homepageAnnouncementTitle = $this->security->xss_clean($_COOKIE['homepageAnnouncementTitle']); ?>

						<script>
						openTab($('atab4'), 'tab4');
						$('questionTextA').value = base64_decode('<?php echo $homepageAnnouncementTitle; ?>');
						//$('questionTextA').style.color = '';
						$('questionTextA').focus();
						setCookie('homepageAnnouncementTitle', '',-1);
						</script>
						<?php
					    }

					}
				  ?>
				  <div style="line-height:1px">&nbsp;</div>
				  <!-- Block End for Ask Question widget -->
				  
				  <!-- Block Start for displaying Ask a Answer on QnA tab -->
				  <?php if($tabselected==1 || $tabselected==3){
					echo "<div class='fontSize_12p float_L'>";
					//below line is used to pass the conversion tracking page key id to hidden field
				 	 $displayData['trackingPageKeyId']=$qtrackingPageKeyId;
				 	 $displayData['atrackingPageKeyId']=$atrackingPageKeyId;
				 	 $displayData['dtrackingPageKeyId']=$dtrackingPageKeyId;
				 	 $displayData['qtrackingPageKeyId']=$qtrackingPageKeyId;
					 
					//In case the Cookies are set, open the Post entity widget
					if(isset($_COOKIE['entitytype']) && isset($_COOKIE['posttitle'])){
						$entity = (isset($_COOKIE['entitytype']))?$_COOKIE['entitytype']:'question';
						$displayData['entity'] = $entity;
						$displayData['displayHeading'] = "false";
						$this->load->view('common/askCafeForm',$displayData);
					}
					else
					{
						echo "<h2 class='ana_q flLt' style='font-family:Tahoma; font-size:13px; font-weight:normal;'><a id='postingWidgetLink' href='javascript:void(0);' onClick='showPostingWidget(\"question\",$qtrackingPageKeyId,$dtrackingPageKeyId,$atrackingPageKeyId);'>Ask a Question</a><span id='postingWidgetNonLink' style='display:none;color: #000'>Ask a Question</span></h2><div class='clearFix'></div>";
					}
					echo "<div style='margin-top:5px;margin-bottom:10px;display:none' id='postingWidget'></div>";
					echo "</div>";
				  } ?>
				  <!-- Block End for displaying Ask a Answer on QnA tab -->

				  <!-- Block Start for Management and Category widget -->
				  <?php if($showCategory){ ?>
				  <?php if(!$tab_required_course_page):?>
				  <div class="fontSize_12p mar_full_10p float_R" id="homepageTabs" style="display:block">
							  <span id="categoryHeading" class="fontSize_12p "><a class="ana_ddwn pl10" onClick="showcommonOverlay(this,'parentcategory',true);return false;" href="javascript:void(0);"><?php echo $selectedCategoryName; if($selectedCategoryName == 'All'){ echo " categories";} ?></a></span>
							  <!-- 
							  <span class="fontSize_12p">&nbsp;&nbsp;-</span>
							  <span id="countryHeading" class="fontSize_12p "><a class="ana_ddwn pl10" onClick="showcommonOverlay(this,'country',true);return false;"  href="javascript:void(0);"><?php echo $selectedCountryName; if($selectedCountryName == 'All'){ echo " countries";} ?></a></span>
							  -->
				  </div>
				  <?php endif;?>
				  <div class="clear_B"></div>
				  <div style="line-height:10px">&nbsp;</div>
				  <?php } ?>
				  <!-- Block End for Management and Category widget -->

				  <div class="normaltxt_11p_blk" id="breadCrumbs"></div>
				  <?php
					  $widthOfTD ='220';
					  $widthOfFirstTD = '485';
				  ?>

				  <!--BlogNavigation-->
				  <div id="discussionTabs" style="display:block">
					  <!-- Start of Displaying Tabs for Popular, My QnA, Recently posted + Pagination -->
					  <?php if(($tabselected > 0 && $tabselected < 6) ){ ?>
					  <div id="blogTabContainer">
							  <table width="99%" cellspacing="0" cellpadding="0" border="0" height="25">
								  <tr>
									  <td width="<?php echo $widthOfFirstTD;?>">
											  <!--<div id="blogNavigationTab" style="width:100%">
												  <ul>
													  <li container="forum" tabName="forumWallTab" class="<?php if($tabselected == 0) { echo 'selected'; } ?>"><?php    if($tabselected==0) echo "<a href=\"#\">Ask & Answer <b style=\"font-style:normal;color:#FF0000;font-size:9px;position:relative;top:-4px\">Live</b></a>"; else  echo  "<a href=\"".str_replace("@tab@",0,$commonTabURL)."\">Ask & Answer <b style=\"font-style:normal;color:#FF0000;font-size:9px;position:relative;top:-4px\">Live</b></a>";  ?></li>
													  <li container="forum" tabName="forumRecentTab" class="<?php if(in_array($tabselected,$recentTabNumbs)) { echo 'selected'; } ?>"><?php    if(in_array($tabselected,$recentTabNumbs)) echo "<a href=\"#\">Recent Questions</a>"; else  echo  "<a href=\"".str_replace("@tab@",1,$commonTabURL)."\">Recent Questions</a>";  ?></li>
													  <li container="forum" tabName="forumPopularTab" class="<?php if($tabselected == 2) { echo 'selected'; } ?>"><?php    if($tabselected == 2) echo "<a href=\"#\">Popular Questions</a>"; else echo "<a href=\"".str_replace("@tab@",2,$commonTabURL)."\">Popular Questions</a>"; ?></li>
													  <?php if(is_array($validateuser) && ($validateuser != "false") && ($userGroup !== 'cms')) { ?>
														  <li container="forum" tabName="forumMyTab" presentKey="<?php echo $myKey; ?>" class="<?php if($tabselected == 4) { echo 'selected'; } ?>"><?php  if($tabselected == 4) echo "<a href=\"#\">My Q &amp; A</a>"; else echo "<a href=\"".str_replace("@tab@",4,$commonTabURL)."\" >My Q &amp; A</a>";   ?></li>
													  <?php } if($userGroup == 'cms'){ ?>
														  <li container="forum" tabName="forumEditorTab" class="<?php if($tabselected == 5) { echo 'selected'; } ?>"><?php    if($tabselected == 5) echo "<a href=\"#\">Editor's Pick</a>"; else echo "<a href=\"".str_replace("@tab@",5,$commonTabURL)."\" >Editor's Pick</a>"; ?></li>
													  <?php } ?>
													  <li container="forum" tabName="forumDiscussionTab" class="<?php if($tabselected == 6) { echo 'selected'; } ?>"><?php    if($tabselected == 6) echo "<a href=\"#\">D</a>"; else echo "<a href=\"".str_replace("@tab@",6,$commonTabURL)."\">D</a>"; ?></li>
													  <li container="forum" tabName="forumAnnouncementTab" class="<?php if($tabselected == 7) { echo 'selected'; } ?>"><?php    if($tabselected == 7) echo "<a href=\"#\">A</a>"; else echo "<a href=\"".str_replace("@tab@",7,$commonTabURL)."\">A</a>"; ?></li>
												  </ul>
											  </div>-->
									  </td>
									  <td align="right" width="<?php echo $widthOfTD; ?>">
										  <table cellspacing="0" cellpadding="0" border="0" height="25">
										  <tr>
											  <td>
												<div class="pagingID txt_align_r" id="paginataionPlace1" style="line-height:23px"><?php echo $paginationHTML;?></div>
											  </td>
											  <td>
												  <div class="normaltxt_11p_blk_arial" style="float:right;" align="right" id="countSelect1"></div>
											  </td>
										  </tr>
										  </table>
									  </td>
								  </tr>
							  </table>
					  </div>
					  <?php } ?>
					  <!-- End of Displaying Tabs for Popular, My QnA, Recently posted + Pagination -->

					  <!-- Start of Displaying question/answers block -->
					  <div style="float:left; width:100%;">
						  <div class="raised_lgraynoBG">
								  <?php if($tabselected!=6 && $tabselected!=7 && $tabselected!=0){ ?>
								  <b class="b1"></b><b class="b2"></b>
								  <?php } ?>
								  <div class="" id="mainContainer">
										  <div class="lineSpace_10">&nbsp;</div>

										  <!-- Link for all/unswered start -->
										  <?php  if(!$tab_required_course_page) { ?>
										  <div style="padding:0 10px;height:18px;<?php if(!in_array($tabselected,$recentTabNumbs)){ echo 'display:none;'; } ?>" id="recentTabs">
											  <div style="height:18px" class="float_R">
											  <?php
												  $recentClass = '';
												  $unAnsClass = '';
												  switch($tabselected) {
													  case 1 : $recentClass = 'OrgangeFont bld doNotShowLink'; break;
													  case 3 : $unAnsClass = 'OrgangeFont bld doNotShowLink'; break;
												  }
											  ?>
											  <a href="<?php echo SHIKSHA_ASK_HOME_URL.'/questions';?>" class="<?php echo $recentClass; ?>" id="recentAll">All</a> |
											  <a href="<?php echo SHIKSHA_ASK_HOME_URL.'/unanswers';?>" class="<?php echo $unAnsClass; ?>" id="recentUnanswered">Unanswered</a>
											  </div>
											  <div style="line-height:1px;clear:both">&nbsp;</div>
										  </div>
										<?php	} ?>
										  <!-- Link for all/unswered ends -->
										  <!-- start of Wall topics -->
										  <div id="wall" class="<?php if($tabselected != 0) { echo 'displayNone'; } ?>" presentKey="<?php echo $wallKey; ?>" >
											  <?php 
												  if($tabselected == 0):
													  //$this->load->view('messageBoard/hallOfFame_Wall',$topLeftSearchPanelFileData);
													  $this->load->view('messageBoard/questionHomePageWall');	
													  $paginationParam = 'ForWallQuestion';
												  endif; 
											  ?>
										  </div>
										  <!-- end of Wall topics -->
										  <!-- start of recent topics -->
										  <div id="recent" class="<?php if($tabselected != 1) { echo 'displayNone'; } ?>" presentKey="<?php echo $recentKey; ?>" >
											  <?php 
												  if($tabselected == 1):
													  $this->load->view('messageBoard/questionHomePageListing');	
													  $paginationParam = 'ForRecentQuestion';
												  endif; 
											  ?>
										  </div>
										  <!-- end of recent topics -->
										  <!-- start of popular topics -->
										  <div id="popular" class="<?php if($tabselected != 2) { echo 'displayNone'; } ?>" presentKey="<?php echo $popularKey; ?>">
											  <?php if($tabselected == 2):
												  $this->load->view('messageBoard/questionHomePageListing');
												  $paginationParam = 'ForPopularQuestion';
											  endif; ?>
										  </div>
										  <!-- end of popular topics -->
										  <!-- start of unanswered topics -->
										  <div id="unAnswered" class="<?php if($tabselected != 3) { echo 'displayNone'; } ?>" presentKey="<?php echo $unAnsweredKey; ?>">
											  <?php if($tabselected == 3): 
												  $this->load->view('messageBoard/questionHomePageListing');
												  $paginationParam = 'ForUnAnsweredQuestion';
											  endif; ?>
										  </div>
										  <!-- end of unanswered topics  -->
										  <!-- start of My topics -->
										  <div id="my" class="<?php if($tabselected != 4) { echo 'displayNone'; } ?>" presentKey<?php echo ucwords($myqnaTab);  ?>s="<?php echo $myKey; ?>">

										  <!--Start_pagination_and_Answer_question_Lnk-->
										  <div style="padding:0 10px;height:35px">
											  <div style="height:35px" class="float_R" id="myBlockHeader"><b>Showing:</b>
											  <?php
												  $answersClass = '';
												  $questionsClass = '';
												  $bestAnswersClass = '';
												  $answerJS = ''; $questionJS = ''; $bestAnswerJS = '';
												  $tabReplacedURL = str_replace('@tab@',4,$tabURL); 
												  $answersOnClick =str_replace('@qnaTab@','answer',$tabReplacedURL);
												  $questionsOnClick =str_replace('@qnaTab@','question',$tabReplacedURL);
												  $bestAnswersOnClick =str_replace('@qnaTab@','bestanswer',$tabReplacedURL);
                                                                                                  $untitledQuestionOnClick =str_replace('@qnaTab@','untitledQuestion',$tabReplacedURL);
												  switch($myqnaTab) {
													  case 'answer' : $answersClass = 'OrgangeFont bld doNotShowLink'; $answersOnClick = '#'; $answerJS = "onClick='javascript:void(0);return false;'"; break;
													  case 'question' : $questionsClass = 'OrgangeFont bld doNotShowLink'; $questionsOnClick = '#'; $questionJS = "onClick='javascript:void(0);return false;'"; break;
													  case 'bestanswer' : $bestAnswersClass = 'OrgangeFont bld doNotShowLink'; $bestAnswersOnClick = '#'; $bestAnswerJS = "onClick='javascript:void(0);return false;'"; break;
                                                                                                          case 'untitledQuestion' : $untitledQuestionClass = 'OrgangeFont bld doNotShowLink'; $untitledQuestionOnClick = '#'; $untitledQuestionJS = "onClick='javascript:void(0);return false;'"; break;
												  }
											  ?>
											  <a href="<?php echo $answersOnClick; ?>" <?php echo $answerJS; ?> class="<?php echo $answersClass; ?>" id="myAnswersLink">Answers (<?php echo $topicListings['totalQuestionsAnswered']; ?>)</a> |
											  <a href="<?php echo $questionsOnClick; ?>" <?php echo $questionJS; ?> class="<?php echo $questionsClass; ?>" id="myQuestionsLink">Questions (<?php echo $topicListings['totalQuestions']; ?>)</a> |
											                                              <a href="<?php echo $untitledQuestionOnClick; ?>" <?php echo $untitledQuestionJS; ?> class="<?php echo $untitledQuestionClass; ?>" id="myuntitledQuestionLink">Untitled Questions</a>
											  </div>
											  <div style="line-height:1px;clear:both">&nbsp;</div>
										  </div>
										  <?php if($myqnaTab=='untitledQuestion'){ ?>
										  <div style="padding-bottom:15px;"><b>Add relevant title to the following questions and earn extra reputation points!</b></div>
										  <?php } ?>
										  <!--Start_pagination_and_Answer_question_Lnk-->
											  <?php if($tabselected == 4):
												  if($myqnaTab=='question'){
													  $this->load->view('messageBoard/myQuestionsHomePageListing');
													  $paginationParam = 'ForMyQuestions';
													  $paginationMethodName = 'getMyQuestions';	
													  $showPaginationNumber = 3;
												  }elseif($myqnaTab=='answer'){
													  $this->load->view('messageBoard/myAnswersHomePageListing');
													  $paginationParam = 'ForMyAnswers';
													  $paginationMethodName = 'getMyAnswers';
													  $showPaginationNumber = 4;    
												  }elseif($myqnaTab=='bestanswer'){
													  $this->load->view('messageBoard/myAnswersHomePageListing');
													  $paginationParam = 'ForMyBestAnswers';
													  $paginationMethodName = 'getMyBestAnswers';
													  $showPaginationNumber = 5;     
												  }elseif($myqnaTab=='untitledQuestion'){
													  $this->load->view('messageBoard/myuntitledQuestionHomePageListing');
													  $paginationParam = 'ForMyuntitledQuestions';
													  $paginationMethodName = 'getMyuntitledQuestions';
													  $showPaginationNumber = 6;
												  }
											  endif; ?>
										  </div>
										  <!-- end of My topics -->
										  <!-- start of editorial bin questions -->
										  <div id="eidtorialBin" class="<?php if($tabselected != 5) { echo 'displayNone'; } ?>" presentKey="<?php echo $editorKey; ?>">
                                                                                        

                                                                                          <?php if($tabselected == 5):
                                                                                                  ?>
                                                                                      <div style="padding:0 10px;height:35px">
											  <div style="height:35px" class="float_R" id="myBlockHeader"><b>Showing:</b>
                                                                                        <?php
                                                                                        $answersClass = '';

												  $answerJS = '';
												  $tabReplacedURL = str_replace('@tab@',5,$tabURL);
		   									          $answersOnClick =str_replace('@qnaTab@','answer',$tabReplacedURL);
		                                                                                  $untitledQuestionOnClick =str_replace('@qnaTab@','untitledQuestion',$tabReplacedURL);
												  switch($myqnaTab) {
													  case 'answer' : $answersClass = 'OrgangeFont bld doNotShowLink'; $answersOnClick = '#'; $answerJS = "onClick='javascript:void(0);return false;'"; break;
													  case 'untitledQuestion' : $untitledQuestionClass = 'OrgangeFont bld doNotShowLink'; $untitledQuestionOnClick = '#'; $untitledQuestionJS = "onClick='javascript:void(0);return false;'"; break;
												  }
                                                                                                  ?>
                                                                                                  <a href="<?php echo $answersOnClick; ?>" <?php echo $answerJS; ?> class="<?php echo $answersClass; ?>" id="myAnswersLink">Answers</a> |
                                                                                                  <a href="<?php echo $untitledQuestionOnClick; ?>" <?php echo $untitledQuestionJS; ?> class="<?php echo $untitledQuestionClass; ?>" id="myuntitledQuestionLink">Untitled Questions</a>
                                                                                        </div>
											  <div style="line-height:1px;clear:both">&nbsp;</div>
										  </div>
											<?php if($myqnaTab=='untitledQuestion'){ ?>
											<div style="padding-bottom:15px;"><b>Add relevant title to the following questions and earn extra reputation points!</b></div>
											<?php } ?>
                                                                                               <?php
												  if($myqnaTab=='untitledQuestion'){
													  $this->load->view('messageBoard/myuntitledQuestionHomePageListing');
													  $paginationParam = 'ForMyuntitledQuestions';
													  $paginationMethodName = 'getMyuntitledQuestions';
													  $showPaginationNumber = 6;
												  }else{
                                                                                                          $this->load->view('messageBoard/questionHomePageListing');
												          $paginationParam = 'ForEditorBin';
                                                                                                  }
											  endif; ?>
										  </div>
										  <!-- end of editorial bin questions  -->

										  <!-- start of discussion topics -->
										  <div id="discussion" class="<?php if($tabselected != 6) { echo 'displayNone'; } ?>" presentKey="<?php echo $recentKey; ?>" >
											  <?php 
												  if($tabselected == 6):
													  
												  	//below line is used to pass the conversion tracking page key id to hidden field
				  										$displayData['trackingPageKeyId']=$dtrackingPageKeyId;

				 	 									$displayData['atrackingPageKeyId']=$atrackingPageKeyId;
													 	 $displayData['dtrackingPageKeyId']=$dtrackingPageKeyId;
													 	 $displayData['qtrackingPageKeyId']=$qtrackingPageKeyId;

														//In case the Cookies are set, open the Post entity widget
													  if(isset($_COOKIE['entitytype']) && isset($_COOKIE['posttitle'])){
														  $entity = (isset($_COOKIE['entitytype']))?$_COOKIE['entitytype']:'question';
														  $displayData['entity'] = $entity;
														  $displayData['displayHeading'] = "false";
														  $this->load->view('common/askCafeForm',$displayData);
													  }
													  else
													  {
														  echo "<h2 class='mb10 ana_sprt ana_blg2' style='font-weight:normal;display:inline-block;height:20px;background-position:0 -40px'><a id='postingWidgetLink' href='javascript:void(0);' onClick='showPostingWidget(\"discussion\",$qtrackingPageKeyId,$dtrackingPageKeyId,$atrackingPageKeyId);'>Start a new discussion</a><span id='postingWidgetNonLink' style='display:none'>Start a new discussion</span></h2>";
													  }
													  echo "<div style='margin-bottom:10px;display:none' id='postingWidget'></div>";
													  $hoempageData = array('selectedCategoryName'=>$selectedCategoryName,'selectedCountryName'=>$selectedCountryName, 'paginationHTML'=>$paginationHTML);
													  $this->load->view('messageBoard/commonHomepageView',$hoempageData);	
													  $paginationParam = 'ForDiscussionTopic';
												  endif; 
											  ?>
										  </div>
										  <!-- end of discussion topics -->
										  <!-- start of announcement topics -->
										  <div id="recent" class="<?php if($tabselected != 7) { echo 'displayNone'; } ?>" presentKey="<?php echo $recentKey; ?>" >
											  <?php 
												  if($tabselected == 7):
												  	//below line is used to pass the conversion tracking page key id to hidden field
				  										$displayData['trackingPageKeyId']=$atrackingPageKeyId;
				  										$displayData['atrackingPageKeyId']=$atrackingPageKeyId;
													 	 $displayData['dtrackingPageKeyId']=$dtrackingPageKeyId;
													 	 $displayData['qtrackingPageKeyId']=$qtrackingPageKeyId;
													  //In case the Cookies are set, open the Post entity widget
													  if(isset($_COOKIE['entitytype']) && isset($_COOKIE['posttitle'])){
													  	  $entitytypeCleaned = $this->security->xss_clean($_COOKIE['entitytype']);
														  $entity = (isset($_COOKIE['entitytype']))?$entitytypeCleaned:'question';
														  $displayData['entity'] = $entity;
														  $displayData['displayHeading'] = "false";
														  $this->load->view('common/askCafeForm',$displayData);
													  }
													  else
													  {
														  echo "<h2 class='mb10 ana_mike h20 lineSpace_20 flLt'><a href='javascript:void(0);' onClick='showPostingWidget(\"announcement\",$qtrackingPageKeyId,$dtrackingPageKeyId,$atrackingPageKeyId);' id='postingWidgetLink'>Got something interesting? Announce it!</a><span id='postingWidgetNonLink' style='display:none'>Got something interesting? Announce it!</span></h2><div class='clearFix'></div>";
													  }
													  echo "<div style='margin-bottom:10px;display:none' id='postingWidget'></div>";
													  $hoempageData = array('selectedCategoryName'=>$selectedCategoryName,'selectedCountryName'=>$selectedCountryName, 'paginationHTML'=>$paginationHTML);
													  $this->load->view('messageBoard/commonHomepageView',$hoempageData);	
													  $paginationParam = 'ForAnnouncementTopic';
												  endif; 
											  ?>
										  </div>
										  <!-- end of announcement topics -->
										  <div class="spacer10 clearFix">&nbsp;</div>

										  <!-- code for pagination start -->
										  <?php if(($tabselected > 0 && $tabselected < 6) ){ ?>
										  <div class="mar_full_10p">
											  <div class="row" style="line-height:24px">
												  <div class="normaltxt_11p_blk_arial" style="float:right;" align="right" id="countSelect2"></div>
												  <div class="pagingID txt_align_r" id="paginataionPlace2" style="line-height:23px"><?php echo $paginationHTML;?></div>
											  </div>
											  <div class="spacer10 clearFix">&nbsp;</div>
										  </div>
										  <?php } ?>
										  <!-- code for pagination ends -->
								  
							  </div>
							  <div><b style="width:72px; float:left; display:block">Disclaimer:</b> <span style="margin-left:72px; display:block"><a href="javascript:void(0);" style="color: rgb(112, 112, 112);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/termCondition');">Views expressed by the users above are their own, Info Edge (India) Limited does not endorse the same.</a></span></div>
							  <div class="spacer10 clearFix">&nbsp;</div>
							  <?php
							  //$this->load->view('common/userCommonOverlay');
							  $bannerProperties1 = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'FOOTER');
							  $this->load->view('common/banner',$bannerProperties1); 
							  ?>
						</div>
					  </div>
					  <!--End of Displaying question/answers block-->
				</div>
				<!--End BlogNavigation-->

				<!-- Block start for Google search results display -->
				<?php } ?>
				<!-- Block end for Google search results display -->

			</div>
			<!-- End of Mid Panel -->

			<!--start_Right_Panel-->
			<?php if($showRightPanel){ ?>
			<div class="ana-right-col">
				<?php 	
				  $userProfile = site_url('getUserProfile').'/';
				  if($tabselected < 6){
				      $rightPanelArray = array();
				      $rightPanelArray['categoryId'] = $categoryId;
				      $rightPanelArray['validateuser'] = $validateuser;
				      $rightPanelArray['mcUsers'] = $mcUsers;
				      $rightPanelArray['userProfile'] = $userProfile;
				      $rightPanelArray['userId'] = $userId;
				      $rightPanelArray['friendArray'] = $friendArray;
				      $rightPanelArray['cardStatus'] = $cardStatus;
                      //$rightPanelArray['reputationPoints'] = $reputationPoints;
					  //$dataForRankAndReputation = array('reputationPonits' => $reputationPoints,'rank' => $rank);
					  if(isset($reputationPoints)){
						$rightPanelArray['reputationPoints'] = $reputationPoints;
						$rightPanelArray['rank'] = $rank;
					  }
				      $this->load->view('messageBoard/discussionHome_right',$rightPanelArray);
				  }
				  $newRepliesCount = $topicListings['newRepliesCount'];
				  $recentTabNumbs = array(1,3);
				?>
				</div>
			
			<?php } ?>
			<!--End_Right_Panel-->

		<br clear="all" />
		</div>
	<?php $this->load->view('messageBoard/reputationOverlay',$dataForRankAndReputation); ?>
<!--End_Center-->
<div id="categoryCountryWidgetDiv" style="display:none;"></div>

<script>
window.onload= function(){
		getCategoryCountryWidget();
		callPaginationMethod();
		for(var i in ajaxWidgets){
				executeAjaxWidgetRequest(ajaxWidgets[i]);
		}
}
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
	if(!($tab_required_course_page && in_array($tabselected,array(1,6)))) {
		$displayArr = array(10,20,30);
		$selectBoxHTML = getPaginationSelectBox($totalCount,$paginationURL,$start,$rows,$displayArr,"");
	}
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);

?>
 <script>
$j(document).ready(function() {
	setScrollbarForMsNotificationLayer();
 });            
</script>

<script>
//Call this function to load the top contributor widget with an Ajax call
// $categroyId is a category Id
getTopContributors(1,0,<?php echo $categoryId; ?>);

function callPaginationMethod()
{
		if($('countSelect1')) $('countSelect1').innerHTML = '<?php echo $selectBoxHTML;?>';
		if($('countSelect2')) $('countSelect2').innerHTML = '<?php echo $selectBoxHTML;?>';
}
<?php
	echo "var myQnACountPool = new Array();";
	if($tabselected ==4){
		echo "myQnACountPool['myQnA_". $categoryId ."_". $countryId ."'] = new Array();";
		echo "myQnACountPool['myQnA_". $categoryId ."_". $countryId ."']['totalCount'] =  '". $topicListings['totalQuestionsAnswered'] .'_'. $topicListings['totalQuestions'] .'_'. $topicListings['totalBestAnswers']. "'; myQnACountPool['myQnA_". $categoryId ."_". $countryId ."']['newRepliesCount'] =  '". $topicListings['newRepliesCount']."';";
	}
?>
</script>

<script>
var tab_required_course_page = '<?php echo $tab_required_course_page;?>';
var subcat_id_course_page = '<?php echo $subcat_id_course_page?>';
var cat_id_course_page = '<?php echo $cat_id_course_page?>';
var isShowAppBanner = 1; // using onload
</script>

<!-- Code Start to show the Category-Country overlay when the user has logged in -->
<?php
      if(isset($_COOKIE['entitytype']))
      {
?>
	  <script>
	    var entityType = "<?php echo $entity;?>";
	    if(entityType=="discussion"){
		var questionTextValue = $('questionTextD').value;
		showCatCounOverlay(questionTextValue,'discussion'); 
	    }
	    else if(entityType=="announcement"){
		var questionTextValue = $('questionTextA').value; 
		showCatCounOverlay(questionTextValue,'announcement'); 
	    }
	    /*else if(entityType=="question"){
		var questionTextValue = $('questionText').value; 
		showCatCounOverlay(questionTextValue,'question'); 
	    }*/
	  </script>
<?php }
      setcookie  ('posttitle','',time()-3600,'/',COOKIEDOMAIN);
      setcookie  ('postdescription','',time()-3600,'/',COOKIEDOMAIN);
      setcookie  ('entitytype','',time()-3600,'/',COOKIEDOMAIN);

      //Code start In case the question is asked from the Shiksha homepage
      if(isset($_COOKIE['homepageQuestion']) && $_COOKIE['homepageQuestion']!=''){
?>
      <script>
      var entityType = "<?php echo $entity;?>";
      if(entityType!="question"){
      var questionTextValue = $('questionText').value; 
      if(validateQuestionForm_old($('askQuestionForm'),'ASK_ASKHOME_HEADER_POSTQUESTION','formsubmit','askQuestionForm')){
	showCatCounOverlay(questionTextValue,'question'); 
      }
      }
      setCookie('homepageQuestion', '',-1);
      </script>
<?php
      }
      //Code End In case the question is asked from the Shiksha homepage

?>
<!-- Code End to show the Category-Country overlay when the user has logged in -->

<!-- Start: Code added for Google Code in case of Management as per Ticket# 180 -->
<?php if($categoryId==3){ ?>
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


