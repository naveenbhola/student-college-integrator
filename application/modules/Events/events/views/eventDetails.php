<?php
$topLeftSearchPanelFileData = array('infoWidgetData' => $infoWidgetData);
$eventTitleForSEO .= isset($event_title) ? $event_title : '';
if($categoryCsv !== '1'){
        $selectedCatName = '';
        $categoryArray = explode(",",$categoryCsv);
        foreach($categoryArray as $temp)
        {
                if($temp != 1)
                $selectedCatName .= $categoryForLeftPanel[$temp][0].' - ';
        }
        $selectedCatName = trim(substr($selectedCatName,0,strlen($selectedCatName)-2));
        $category= $selectedCatName;
}else if($mainCategoryCsv == '1'){
        $category="All categories";
}
// get assoc_country name :P
foreach ($country_list as $array)
{
    if ($array['countryID'] == $assoc_country) {
        $assoc_country_name =  $array['countryName'];
    }
}

if($fromOthers==0){
		$eventType="Application Submission Deadline";
		$Title = $event_title."-". $countryName."-".$cityName."-".$eventType." for ". $selectedCatName.", Start Date-".date('Y-m-d',strtotime($start_date));
		$description = "$Title .Find details on education events - Application Submission Deadlines, Course Commencement in different universities / colleges, Result Declaration, Examination Dates, Form Issuance, Admissions, Entrance Exams Updates, Exam Schedules & New Courses.";
		$keywords = "events in $cityName $countryName,  education events, deadlines, Application Submission Deadlines, Course Commencement, Result Declaration, Examination Dates, Form Issuance, Admissions, entrance exams updates, exam schedules, new courses";
}
elseif($fromOthers==1){
		$eventType="Course Commencement";
		$Title = $event_title."-". $countryName."-".$cityName."-".$eventType." for ". $selectedCatName.", Start Date-".date('Y-m-d',strtotime($start_date));
		$description = "$Title .Find details on education events - Application Submission Deadlines, Course Commencement in different universities / colleges, Result Declaration, Examination Dates, Form Issuance, Admissions, Entrance Exams Updates, Exam Schedules & New Courses.";
		$keywords = "events in $cityName $countryName,  education events, deadlines, Application Submission Deadlines, Course Commencement, Result Declaration, Examination Dates, Form Issuance, Admissions, entrance exams updates, exam schedules, new courses";
}
elseif($fromOthers==2){
		$eventType="Result Declaration";
  $Title = $event_title."-". $countryName."-".$cityName."-".$eventType." for ". $selectedCatName.", Start Date-".date('Y-m-d',strtotime($start_date));
		$description = "$Title .Find details on education events - Application Submission Deadlines, Course Commencement in different universities / colleges, Result Declaration, Examination Dates, Form Issuance, Admissions, Entrance Exams Updates, Exam Schedules & New Courses.";
		$keywords = "events in $cityName $countryName,  education events, deadlines, Application Submission Deadlines, Course Commencement, Result Declaration, Examination Dates, Form Issuance, Admissions, entrance exams updates, exam schedules, new courses";
}
elseif($fromOthers==3){
		$eventType="Examination Date";
        $Title = $event_title."-". $countryName."-".$cityName."-".$eventType." for ". $selectedCatName.", Start Date-".date('Y-m-d',strtotime($start_date));
		$description = "$Title .Find details on education events - Application Submission Deadlines, Course Commencement in different universities / colleges, Result Declaration, Examination Dates, Form Issuance, Admissions, Entrance Exams Updates, Exam Schedules & New Courses.";
		$keywords = "events in $cityName $countryName,  education events, deadlines, Application Submission Deadlines, Course Commencement, Result Declaration, Examination Dates, Form Issuance, Admissions, entrance exams updates, exam schedules, new courses";
}
elseif($fromOthers==4){
		$eventType="Form Issuance";
		$Title = $event_title."-". $assoc_country_name ."-".$eventType." Update -". $selectedCatName.", Start Date-".date('Y-m-d',strtotime($start_date));
		$description = $event_title."-". $assoc_country_name ."-".
        $cityName."-".$eventType." Update- Start Date for ".$selectedCatName ."-". date('Y-m-d',strtotime($start_date)) ." ,$description .Find details on education events - Application Submission Deadlines, Course Commencement in different Universities / Colleges, Result Declaration, Examination Dates, Form Issuance, Admissions, Entrance Exams Updates, Exam Schedules & New Courses.";
		$keywords = "events in $cityName $countryName,  education events, deadlines, Application Submission Deadlines, Course Commencement, Result Declaration, Examination Dates, Form Issuance, Admissions, entrance exams updates, exam schedules, new courses";
}
elseif($fromOthers==5){
		$eventType="General";
		$Title = $event_title."-". $assoc_country_name ."-".$eventType." Update -". $selectedCatName.", Start Date-".date('Y-m-d',strtotime($start_date));
		$description = $event_title."-". $assoc_country_name ."-".
        $cityName."-".$eventType." Update- Start Date for ".$selectedCatName ."-". date('Y-m-d',strtotime($start_date)) ." ,$description .Find details on education events - Application Submission Deadlines, Course Commencement in different Universities / Colleges, Result Declaration, Examination Dates, Form Issuance, Admissions, Entrance Exams Updates, Exam Schedules & New Courses.";
		$keywords = "events in $cityName $countryName,  education events, deadlines, Application Submission Deadlines, Course Commencement, Result Declaration, Examination Dates, Form Issuance, Admissions, entrance exams updates, exam schedules, new courses";
}
$eventTitleForSEO .= isset($eventType) ? ' -'.$eventType : '';
$eventTitleForSEO .= isset($category) ? ' for '. $category : '';
$eventStartDate .= isset($start_date) ? $start_date : '';
if($eventStartDate != '') {
		$dateArray = explode("-",$eventStartDate);
		$year = $dateArray[0];
		$month = $dateArray[1];
		$day = $dateArray[2];
		$eventTitleForSEO .= ', Start Date  - '. date("l, F d, Y", mktime(0, 0, 0, $month, $day, $year));
}


		$headerComponents = array(
								'css'	=>	array(
											'raised_all',
											'mainStyle',
											'events',
											'modal-message',
											'header'
										),
								'js'	=>	array(
											'common',
											'prototype',
											'user',
											'catTree',
											'scriptaculous',
											'events',
											'multipleapply',
											'lazyload',
											'ana_common'
										),
								'title'	=> $Title,
								'tabName'	=>	'Event Calendar',
								'bannerProperties' => array('pageId'=>'EVENTS_DETAILS', 'pageZone'=>'HEADER'),
								'taburl'	=>	site_url('events/Events/index'),
								'metaDescription'	=> $description,
								'metaKeywords'	=>	$keywords,
								'product' => 'events',
								'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'callShiksha'=>1
							);
		$this->load->view('common/header', $headerComponents);
		$dataForHeaderSearchPanel = array('topLeftSearchPanelFileData' => $topLeftSearchPanelFileData);
                $this->load->view('events/headerSearchPanelForEvents',$dataForHeaderSearchPanel);
		$mainTextBoxWidth = "width:450px;";
?>
<script>
//Added by Ankur to add VCard on all AnA pages: Start
var userVCardObject = new Array();
LazyLoad.loadOnce([
     '/public/js/<?php echo getJSWithVersion("tooltip"); ?>',
     '/public/js/<?php echo getJSWithVersion("ajax-api"); ?>'
      ],callbackfn);
</script>
<!--Start_Mid_Panel-->
<div class="">
<div class="mar_full_10p">
	<?php $this->load->view('events/rightPanelEventDetail'); ?>
	<?php
		$url = array(
		'successurl'=> '',
		'successfunction'=>'showAddEvents',
		'id'=>'add',
		);
	?>
	<!--Start_Mid_Panel-->
		<div style="float:left; width:750px">
			<?php $this->load->view('events/eventDetailPanel'); ?>
			<div class="spacer10 clearFix"></div>
			<?php if(is_numeric($topicId)) { ?>
			<div class="raised_lgraynoBG" style="width:100%; float:left">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_lgraynoBG">
					<div class="h22 raisedbg_sky">
						<div class="Fnt13 bgEnt" id = "">
						<div class="lineSpace_5">&nbsp;</div>
							<div class="mar_left_10p bld"><img src="/public/images/comnt.gif" align="absmiddle"> Event Discussion Board</div>
						<div class="lineSpace_5">&nbsp;</div>
						</div>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
					<div class="mar_right_10p" align="left">
						<div class="pagingID" id="paginataionPlace3"></div>
					</div>
					<!--<div class="lineSpace_20">&nbsp;</div>
					<div class="grayLine"></div>-->
					<div class="lineSpace_20">&nbsp;</div>
					<?php
						$topicUrl = site_url('messageBoard/MsgBoard/topicDetails').'/'.$categoryId.'/'.$topicId;
						$url = site_url("messageBoard/MsgBoard/replyMsg");
						$isCmsUser =0;
						$userId = 0;
						if(is_array($validateuser))
						{
							if(strcmp($validateuser[0]['usergroup'],'cms') == 0)
								$isCmsUser = 1;
								$userId = $validateuser[0]['userid'];
						}
						$this->load->view('common/userCommonOverlay');
						$this->load->view('network/mailOverlay',$data);
						$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;

						echo "<script language=\"javascript\"> ";
						echo "var BASE_URL = '';";
						echo "var COMPLETE_INFO = ".$quickSignUser.";";
						echo "var URLFORREDIRECT = '".base64_encode($_SERVER['REQUEST_URI'])."';";
						echo "var loggedInUserId = '".$userId."';";
						echo "</script> ";
					?>
					<div style="float:left;width:100%;display:inline">
						<div id = "userCollegeComments" class="mar_full_10p">
							<div id = "topicContainer">
								<?php
									$commentData['url'] = $url;
									$commentData['threadId'] = $threadId;
									$commentData['isCmsUser'] = $isCmsUser;
									$commentData['topicUrl'] = $topicUrl;
									$commentData['userProfile'] = site_url('getUserProfile').'/';
									$commentData['userId'] = $userId;
									$commentData['fromOthers'] = 'event';
									$commentData['maximumCommentAllowed'] = 4;
									$commentData['pageKeySuffixForDetail'] = 'EVENTS_EVENTSDETAIL_MIDDLEPANEL_';
									$commentData['entityTypeShown'] = "Event Comment";
									$commentData['eventId'] = $event_id;
									$this->load->view('messageBoard/topicPage',$commentData);
								?>
								<?php
									//if(!(isset($topic_messages) && is_array($topic_messages))) {
								?>
								<!--<div id = "nomessagemsg" class = "fontSize_12p Organge" align = "center"></div>-->
								<?php //} ?>
							</div>
						</div>
					</div>
					<?php
						$comment['flag'] = 'event';
					?>
					<div id="globalReplyFormContainer">
						<?php $temp = 1; if(($isCmsUser == 0) && ($closeDiscussion == 0)):
						echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$threadId ,'before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$threadId.')}','success' => 'javascript:addMainComment('.$temp.',request.responseText,\'after\',false,false);')); ?>
						<div class="normaltxt_11p_blk">
							<div class="searchShowing1 mar_left_10p" style="font-size:12px;font-family:Arial">Your Comment</div>
							<div><textarea name="replyText" onkeyup="textKey(this)" class="textboxBorder mar_left_10p" id="replyText" style="<?php echo $mainTextBoxWidth; ?>" validate="validateStr" caption="Answer" maxlength="1000" minlength="2" required="true" rows="5"></textarea></div>
							<div class="mar_left_10p">
								<span id="replyText_counter" class="grayFont Fnt10">0</span>&nbsp;out of 1000 characters
							</div>
							<div class="row errorPlace">
								<div id="replyText_error" class="mar_left_10p errorMsg"></div>
							</div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="lineSpace_18 fontSize_12p mar_left_10p" style="font-family:Arial" >Type in the characters you see in the picture below:</div>
							<div class="mar_left_10p">
								<img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&secvariable=seccode&junk=<?php echo rand(0,100000000); ?>" id="secimg" onabort="reloadCaptcha(this.id,'seccode')" onClick="reloadCaptcha(this.id,'seccode')" />
								<input type="text" name="seccode" id="seccode" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="6" style="position:relative; top:-10px" />
								<input type="hidden" name="fromOthers" value="event" />
							</div>
							<div class="row errorPlace"><div id="seccode_error" class="mar_left_10p errorMsg"></div></div>
							<div><input type="hidden" name="threadid" value="<?php echo $threadId; ?>" /></div>
							<div><input type="hidden" name="secCodeIndex" value="seccode" /></div>
							<div><input type="hidden" name="appendThread" value="false" /></div>
							<div><input type="hidden" name="fromOthers" value="event" /></div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="mar_left_10p">
								<div>
									<button class="btn-submit13 w4" type="submit" id="submitButton">
									<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Submit</p></div>
									</button>
								</div>
							</div>
							<div class="lineSpace_10">&nbsp;</div>
						</div>
						</form>
						<?php endif; ?>
						<div class="clear_L">&nbsp;</div>
						<div class="lineSpace_28">&nbsp;</div>
					</div>
					<div class="lineSpace_20">&nbsp;</div>
					<div class="mar_right_10p" align="left"><div class="pagingID" id="paginataionPlace4"></div></div>
					<div class="lineSpace_10">&nbsp;</div>
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
			<?php } ?>
			<div class="lineSpace_20">&nbsp;</div>
			<div>
				<?php
					$bannerProperties = array('pageId'=>'EVENTS_DETAILS', 'pageZone'=>'FOOTER');
					$this->load->view('common/banner', $bannerProperties);
				?>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
		</div>
</div>
</div>
<!--End_Mid_Panel-->

<?php
	$bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties);
?>
<?php
$this->load->view('network/mailOverlay');
$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
$eventUrl = $_SERVER['REQUEST_URI'];
echo "<script language=\"javascript\"> ";
echo "var COMPLETE_INFO = ".$quickSignUser.";";
echo "var BASE_URL = '".base_url().""."';";
echo "var URLFORREDIRECT = '".base64_encode($eventUrl)."';";
echo "</script>";
?>
