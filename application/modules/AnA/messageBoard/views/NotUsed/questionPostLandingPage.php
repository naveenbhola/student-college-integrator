<?php
    $userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
    $quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
    if($userId != 0){
        $loggedIn = 1;
    }else{
        $loggedIn = 0;
    }
    $topLeftSearchPanelFileData = array('infoWidgetData' => $infoWidgetData);
    $bannerProperties = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'HEADER');
    $headerComponents = array(
                    'css'   =>  array('mainStyle','raised_all'),
                    'js' => array('ajax-api','discussion_post','common'),
                    'jsFooter'=>    array('ana_common'),
                    'title' =>  'Ask and Answer - Education Career Forum Community – Study Forum – Education Career Counselors – Study Circle -Career Counseling',
                    'tabName' =>    'Ask and Answer',
                    'taburl' => site_url('messageBoard/MsgBoard/discussionHome'),
                    'metaDescription'   =>'',
                    'metaKeywords'  =>'',
                    'product'   =>'forums',
                    'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                    'bannerProperties' => $bannerProperties,
                    'questionText'  => $questionText,
                    'callShiksha'=>1
                );
    			$this->load->view('common/header', $headerComponents);
				$dataForHeaderSearchPanel = array('topLeftSearchPanelFileData' => $topLeftSearchPanelFileData);
				$this->load->view('messageBoard/headerSearchPanelForAnA',$dataForHeaderSearchPanel);
	$this->load->view('common/userCommonOverlay');
	$this->load->view('network/mailOverlay',$data);
	echo "<script language=\"javascript\"> ";
	echo "var BASE_URL = '';";
	echo "var COMPLETE_INFO = ".$quickSignUser.";";
	echo "var URLFORREDIRECT = '".base64_encode($_SERVER['REQUEST_URI'])."';";	
	echo "var loggedInUserId = ".$userId.";";
	echo "</script> ";

    ?>

<script>
var anotheraction = 1;
//Added by Ankur to add VCard on all AnA pages: Start
var userVCardObject = new Array();
</script>

    <!--Start_MidPanel-->
    <div class="marfull_LeftRight10">
        <div>
            <div class="float_R" style="width:245px">
                <div class="float_L" style="width:245px">
                    <div class="defaultAdd lineSpace_10">&nbsp;</div>
                    <div id="askQuestionRightPanelPlaceHolder">&nbsp;</div>
                    <div class="defaultAdd lineSpace_10">&nbsp;</div>
                    <div class="defaultAdd lineSpace_10">&nbsp;</div>
                    <div class="defaultAdd lineSpace_10">&nbsp;</div>
                </div>
            </div>
            <div style="margin-right:255px">
                <div class="float_L" style="width:100%">
                        <div class="defaultAdd lineSpace_10">&nbsp;</div>
                            <!--Start_ASK_NOW-->
                            <?php 
                                $data = array('questionText' => $questionText);
                                $this->load->view('messageBoard/askSearchWidget',$data); 
                            ?>
                            <!-- End_ASK_NOW -->                           
                            <div align="center" id="show_ajax_loader" style="width: 100%; line-height: 100px; height: 600px; position: relative;display:none;">
                            </div>
                        <!-- div to show similar question -->    
                        <div id="parent_div_id_similar_question" >    
                            <div id="similar_question_result_display_count" style="display:none"></div>
                            <div class="defaultAdd lineSpace_11">&nbsp;</div>
                            <div id="id_still_want_post_question_top" style="display:none">
                            <input type="button" onclick="ClickOnStillPostButton();" class="all_ShikshaBtn_spirit askShik_ISWTPMQButton" />
                            </div>
                            <div class="defaultAdd lineSpace_12">&nbsp;</div>
                            <input type="hidden" id="methodName" value="fetchdetailsimilarQuestionpagination" />
                            <input id = "countOffset" type = "hidden" value = "10"/>
                            <input id = "startOffSet" type = "hidden" value = "0"/>
                            <!--Start_Paging-->
                            <div id="pagination_show_upper_div" style="display:none">
								<div style="width:100%">
									<div class="float_L" style="width:50%">
                                        <div style="width:100%">
                                            <div style="height:28px;line-height:28px" id="similar_question_pagination_count_display_top">&nbsp;</div>
                                        </div>
                                    </div>
                                    <div class="float_L" style="width:49%">
                                        <div style="width:100%">
                                            <div class="txt_align_r" style="height:28px;line-height:28px">
												<div id="paginataionPlace1" class="pagingID"></div>
											</div>
                                        </div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
								</div>
                                <div class="grayLine_3">&nbsp;</div>
                            </div>
                            <!--End_Paging-->
                            <!-- Display content of Similar questions-->
                            <div style="width:100%" id="id_similar_question_content"></div>
                            <div  style="padding-top:15px">
                                <div id ="view_all_link_similar_question" style="display:none;" class="txt_align_r bld">Didn't find your answer <a href="#" onClick="AnA_PQ_searchAllQuestion();return false;"><b>View All &raquo;</b></a></div>
                            </div>
                            <div class="defaultAdd lineSpace_11">&nbsp;</div> 
                            <!--Start_Paging-->
                            <div id="pagination_show_bottom_div" style="display:none">
								<div style="width:100%">
                                   <div class="float_L" style="width:50%">
                                        <div style="width:100%">
                                            <div style="height:28px;line-height:28px" id="similar_question_pagination_count_display_bottom">&nbsp;</div>
                                        </div>
                                    </div>
                                    <div class="float_L" style="width:49%">
                                        <div style="width:100%">
                                            <div class="txt_align_r" style="height:28px;line-height:28px">
												<div id="paginataionPlace2" class="pagingID"></div>
                                        	</div>
										</div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
								</div>
                            </div>
                            <!--End_Paging-->
                            <div class="defaultAdd lineSpace_11">&nbsp;</div>
                            <div id="id_still_want_post_question_bottom" style="display:none;"><input onclick="ClickOnStillPostButton();" type="button" class="all_ShikshaBtn_spirit askShik_ISWTPMQButton" /></div>
                            <div class="defaultAdd lineSpace_12">&nbsp;</div>
                        </div>
                        <!-- // CLOSE SIMILAR QUESTION LANDING PAGE DIV -->
                        <div id="parent_div_id_category_page" style="display:none">
                        <?php
                        $data = array($categoryList); $this->load->view('messageBoard/questionPostCategoryForm',$data);
                        ?>
                        </div> <!-- // CLOSE CATEGORY SELECTOR PAGE DIV -->
                </div>    
            </div>
        </div>
        <div class="clear_B">&nbsp;</div>
    </div>
    <!--End_MidPanel-->
	<div id="askQuestionRightPanelContentHolder" style="display:none">
		<?php
		$data = array('questionText'=>$questionText);
		$this->load->view('messageBoard/loadHowAnAworksWidget',$data);
		?>
    </div>
<?php
    $bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
    $this->load->view('common/footer',$bannerProperties1);
?>
<script>
 	renderRightPanelOfPages('askQuestion');
    window.onload = function() {
        //expandCollapseAskSearchWidget(true);
        // check cookie here and load module
		try{
			var cookie_page_stat = loadCookie('globalLandingPagePostAna');
			document.getElementById('topicDesc1').value = document.getElementById('questionText').value;
/*			if (cookie_page_stat == 'SIMILAR_QUESTION_PAGE') {
				var Post_QuestionText = document.getElementById('questionText');
				globalquestionTextAnaPostQuestionLAndingPage = Post_QuestionText.value;
				getRelatedQuestion(Post_QuestionText.value,0,10);
			}else if (cookie_page_stat == 'CATEGORY_SELECTOR_PAGE') {
				// load category page
				ClickOnStillPostButton();
			} else {
				// let's start again
				var Post_QuestionText = document.getElementById('questionText');
				globalquestionTextAnaPostQuestionLAndingPage = Post_QuestionText.value;
				getRelatedQuestion(Post_QuestionText.value,0,10);
			}
			
			if(globalnumber_total_record_found == 0){
				AnA_PQ_ShowHide_Count_Similar_Question_Category_Page(false);
			}*/
				// load category page
			ClickOnStillPostButton();
			AnA_PQ_ShowHide_Count_Similar_Question_Category_Page(false);

		}catch (e) { ClickOnStillPostButton(); }
    };
</script>
