<?php
		$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
		$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
		$topLeftSearchPanelFileData = array('infoWidgetData' => $infoWidgetData);
		$bannerProperties = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'HEADER');
		$headerComponents = array(
						'css'	=>	array('raised_all','mainStyle'),
                        'js' => array('common','myShiksha'),
						'jsFooter'=> array('discussion','ana_common'),
						'title'	=>	'Ask and Answer - Education Career Forum Community – Study Forum – Education Career Counselors – Study Circle -Career Counseling',
						'tabName' =>	'Ask and Answer',
						'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
						'metaDescription'	=>'',
						'metaKeywords'	=>'',
						'product'	=>'forums',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
						'bannerProperties' => $bannerProperties,
						'questionText'	=> $questionText,
						'notShowSearch' => true,
						'callShiksha'=>1
					);
					$this->load->view('common/header', $headerComponents);
					$dataForHeaderSearchPanel = array('topLeftSearchPanelFileData' => $topLeftSearchPanelFileData);
					$this->load->view('messageBoard/headerSearchPanelForAnA_quesDetail',$dataForHeaderSearchPanel);
?>
<script>
overlayViewsArray.push(new Array('user/getUserNameImage','updateNameImageOverlay'));
</script>
    <!--Start_MidPanel-->
    <div class="marfull_LeftRight10">
    	<div>

            <div class="float_R" style="width:239px">
            	<div class="float_L" style="width:239px">
                	<!--Start_UserInfo-->
						<?php $this->load->view('messageBoard/userLeaderBoard'); ?>
                    <!--End_UserInfo-->

					<!--Start_VCard widget-->
					<?php if(isset($cardStatus)) { ?>
					<div class="lineSpace_10">&nbsp;</div>
					<div>
						<div class="raised_all">
							<b class="b2"></b><b class="b3"></b><b class="b4"></b>
							<div class="boxcontent_all" style="padding: 3px 3px 3px 3px;">
							  <div class="widgetVcard">
								  <?php if($cardStatus=='0'){ ?>
								  <span><a href="/messageBoard/MsgBoard/vcardForm">Create Your Exclusive Shiksha V-Card here</a></span><br><span style="color:#696969;font-size:11px">and increase your fan following</span>
								  <?php }else if($cardStatus=='1'){ ?>
								  <span><a href="/messageBoard/MsgBoard/vcardForm">Update Your Exclusive Shiksha V-Card here</a></span><br><span style="color:#696969;font-size:11px">and increase your fan following</span>
								  <?php } ?>
							  </div>
							</div>
							<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
						</div>
					</div>
					<?php } ?>
					<!--End_VCard widget-->

                    <div class="defaultAdd lineSpace_10">&nbsp;</div>
					<!--Start_Top Contributors-->
					<div id='topContributorsDiv'>
						<div class="raised_all">
							<b class="b2"></b><b class="b3"></b><b class="b4"></b>
							<div class="boxcontent_all">
								<div class="defaultAdd lineSpace_5">&nbsp;</div>
								<div align="center"><img src="/public/images/loader.gif"/></div>
								<div class="defaultAdd lineSpace_5">&nbsp;</div>
							</div>
							<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
						</div>
					</div>
					<!--End_Top Contributors-->
                    <div class="defaultAdd lineSpace_10">&nbsp;</div>
                    <!--Start_ExpertProfile-->
						<?php $this->load->view('messageBoard/ExpertPanel'); ?>
                    <!--End_ExpertProfile-->
                </div>
            </div>

            <div style="margin-right:249px">
            	<div class="float_L" style="width:100%">
                	<!--Start_ASK_NOW-->
						<?php $this->load->view('common/askQuestionForm'); ?>
                    <!-- End_ASK_NOW -->
                    <div class="defaultAdd lineSpace_10">&nbsp;</div>

					<!--Start_ShowConfimationMessage-->
                    <div style="width:100%" id="confirmationMessageContainerForAnswer">
                    	<div class="shik_confirmationMsg">
                        	<div style="width:100%">
                            	<div class="float_L" style="width:96%">
                                	<div style="width:100%">
                                    	<div><b>Your answer has been successfully posted</b></div>
                                        <div style="padding-top:8px">Points Earned: <b><?php if($answerCount == 1){ echo "15";}else{ echo "10";} ?></b>
										<?php if($userActivityAcknowledge['pointsNeedToReachNextLevel'] != 0): ?>
											<span style="padding-left:35px">Points needed to reach next level (<?php echo $userActivityAcknowledge['nextLevel']; ?>): <b><?php echo $userActivityAcknowledge['pointsNeedToReachNextLevel']; ?></b></span>
										<?php endif; ?>
										</div>
                                    </div>
                                </div>
                                <div class="float_L" style="width:4%">
                                	<div><a href="javascript:void(0);" class="spirit_header shik_crossButton" style="text-decoration:none" onClick="javascript:showDiv(new Array('confirmationMessageContainerForAnswer'),-1);">&nbsp;</a></div>
                                </div>
                                <div class="clear_L">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                    <!--End_ShowConfimationMessage-->
                    <div class="defaultAdd lineSpace_10">&nbsp;</div>
                    
                    <!--Start_Question_And_Answer-->
						<?php
							$data = array('typeOfPage' => 'answer');
							$this->load->view('messageBoard/questionListingForActivityLandingPage',$data);
						?>
                    <!--End_Question_And_Answer-->
                    <div class="defaultAdd lineSpace_10">&nbsp;</div>
                    <!--Start_Recently_answered_question-->
                    	<?php $this->load->view('messageBoard/recentlyAnswered'); ?>
                    <!--End_Recently_Answer_Question-->
					<div class="defaultAdd lineSpace_10">&nbsp;</div>
					<?php
						$bannerProperties1 = array('pageId'=>'ANSWER_POST_SUCCESS', 'pageZone'=>'FOOTER');
						$this->load->view('common/banner',$bannerProperties1); 
        			?>
                    <div style="height:50px">&nbsp;</div>
                </div>
            </div>


        </div>
        <div class="clear_B">&nbsp;</div>
    </div>
    <!--End_MidPanel-->
<input type="hidden" name="questionDetailPage" id="questionDetailPage" value="" />
<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?>
<script>
window.onload= function(){getTopContributors();}
</script>
