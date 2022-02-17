<?php
	
				   $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'HEADER');
				   $headerComponents = array(
				      'css'	=>	array('header','raised_all','mainStyle','footer','discussion'),
				      'js' 	=>	array('common','discussion'),
				      'jsFooter'=>      array('prototype','scriptaculous','commonnetwork','relatedContent'),	
								'title'	=>	'College Networks',
								'tabName'	=>	'College Network',
				      'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
				      'metaDescription'	=>'Ask and answer on topic - '.seo_url($main_message['msgTxt']," ").'. Ask Questions on various education & career topics or find answers to questions related to education and career options from our education counselors and users in this education and career forum community.',	
				      'metaKeywords'	=>'Shiksha, Ask & Answer, Education, Career Forum Community, Study Forum, Education & Career Counselors, Career Counselling, study circle, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships',
								'product' => 'Network',
				      'bannerProperties' => $bannerProperties,
				      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
				      'callShiksha'=>1
				   );
	
	   function  getStringwithWBR($str)
	   {
		$str = (strlen($str) <= 75)?$str:substr($str,0,75)."...";
		$newStr = '';
		for($i=0;$i<strlen($str);$i=($i+10))
		{
			$newStr .= substr($str,$i,10)."<wbr>";
		}	
		$newStr .= substr($str,strlen($newStr),strlen($str));
		return $newStr;
	   }

   $this->load->view('common/homepage', $headerComponents);
	if(count($main_message) <=0)
	{
		//echo "This question no longer exists.";
	?>
	<div class="raised_greenGradient mar_full_10p"> 
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_greenGradient">
			<div class="txt_align_c bld fontSize_18p" style="line-height:100px;">
			This discussion no longer exists.
			</div>
		</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
	<div style="line-height:300px;">&nbsp;</div>
	<?php
		$footerData = array();
   		$bannerProperties1 = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'FOOTER');
		$this->load->view('common/footer',$bannerProperties1);  
		exit;
	}
   $maildataForOverlay = array(
      'successurl'=> '',
      'redirect'=> 1

   );	
	$loggedIn = 0;
	if($userId != 0)	
		$loggedIn = 1;
?>
<img id = 'beacon_img' width=1 height=1 >
<script>
   var img = document.getElementById('beacon_img');
   var randNumForBeacon = Math.floor(Math.random()*Math.pow(10,16));
   img.src = '<?php echo BEACON_URL; ?>/'+randNumForBeacon+'/0003003/<?php echo $topicId; ?>+<?php echo $userId; ?>';
</script>

<?php 
      $quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;		
      $topicUrl = 'messageBoard/MsgBoard/topicDetails/'.$topicId; 	
      echo "<script language=\"javascript\"> "; 	
      echo "var BASE_URL = '".base_url().""."';";
      echo "var COMPLETE_INFO = ".$quickSignUser.";";	
      echo "var URLFORREDIRECT = '".base64_encode($topicUrl)."';";	
      echo "var jscategoryId = '".$categoryId."';";	
      echo "var loginRedirectUrl = '/messageBoard/MsgBoard/topicDetails';";			
      echo "var alertCount = '".$alertCount."';";	
      echo "var loggedIn = '".$loggedIn."';";	
      echo "var alertCountForCreateTopic = '".$alertCountForCreateTopic."';"; 	
      echo "var alertStatusForTopic = '';";	 	
      if(isset($WidgetStatus['result']) && ($WidgetStatus['result'] == 1))
      		echo "var alertStatusForTopic = '".$WidgetStatus['state']."';";	
      echo "</script> ";
?>
<!--Pagination Related hidden fields Starts-->
	<input type="hidden" id="startOffsetForQuestion" value="0"/>
	<input type="hidden" id="countOffsetForQuestion" value="30"/>
	<input type="hidden" id="topicId" value="<?php echo $topicId; ?>"/>
	<input type="hidden" id="methodName" value="showCommentsByPage"/>
	<input type="hidden" id="listingTypeId" value="<?php echo $main_message['listingTypeId']?>"/>
	<input type="hidden" id="listingType" value="<?php echo $main_message['listingType']?>"/>
<!--Pagination Related hidden fields Ends  -->
<div class="mar_full_10p">
   <!--Start_Right_Panel-->
   <?php 	
      $rightPanelArray = array();
      $rightPanelArray['topicId'] = $topicId;
      $rightPanelArray['topicName'] = $topicName;
      $rightPanelArray['alertNameValue'] = $alertNameValue;
      $rightPanelArray['alertId'] = $alertId;
      $rightPanelArray['categoryId'] = $categoryId;
      $rightPanelArray['userId'] = $userId;
    //  $this->load->view('messageBoard/topicDetails_right',$rightPanelArray); 
   ?>
   <!--End_Right_Panel-->

<?php
	$lnUserDisplayName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';   
        $urlForTopicPage = site_url('messageBoard/MsgBoard/topicDetails').'/'.$topicId;
        $maildataForOverlay = array('topicUrl'=> '/messageBoard/MsgBoard/topicDetails/'.$topicId,
        'displayName' => $lnUserDisplayName,
        'topicName' =>$main_message['msgTxt']);
        $maildata = json_encode($maildataForOverlay);
?>
   <!--Start_Mid_Panel-->
   <div style="margin-left:0px; margin-right:260px;">
	
            <!--Start_grayBorder-->
            <div style="float:left; width:100%;">
               <!--Start_Raised-->
               <div class="raised_lgraynoBG"> 
                  <!--Start_RoundBrd-->
                  <?php
			  $this->load->view('network/discussionDetails_central');  
				$threadId = $main_message['msgId'];
				echo "<script>"; 
				echo "var threadId = '".$threadId."'; \n";			
				echo "</script>";
				if(isset($_COOKIE['commentContent']) && (stripos($_COOKIE['commentContent'],'@$#@#$$') !== false)) {
					$cookieStuff1 = explode('@$#@#$$', $_COOKIE['commentContent']);
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
				echo "reply_form('".$parentId."'); \n";
				echo "document.getElementById('replyText". $parentId ."').value = '".escapeString($content)."'; \n"; 
				echo "reloadCaptcha('secimg','seccode'); \n";
				echo "if(".$questionId." != threadId) ";
				echo "{ ";
				echo "document.getElementById('replyText". $parentId ."').value = \"\"; \n";
				echo "} ";
				echo "} ";
				echo "</script>";
				}
		?>
            <!--Start_RoundBrd-->
         </div>
         <!--End_Raised-->			
      </div>
      <!--Start_grayBorder-->
      <div class="lineSpace_11">&nbsp;</div>
	<?php
		$bannerProperties1 = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'FOOTER');
		$this->load->view('common/banner',$bannerProperties1);  
	?>
   </div>
   <!--End_Mid_Panel-->	
   <br clear="all" />
</div>
<!--End_Center-->

<?php
   $bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
   $this->load->view('common/footer',$bannerProperties1);  

   $this->load->view('network/mailOverlay',$maildataForOverlay);		
   $this->load->view('common/mailOverlay',$maildataForOverlay); 			
   $callBackFunction = 'javascript:afterCreateTopic(request.responseText)';	
//   $this->load->view('messageBoard/createTopicForm',array('callBackFunction'=>$callBackFunction));			
   $questionText = escapeStr($main_message['msgTxt']);		
?> 
<script> 
	doPagination(commentCount,'startOffsetForQuestion','countOffsetForQuestion','paginataionPlace1','','methodName',10);
	var questionDetail =  '<?php echo $questionText; ?>';
	var mailContent = eval(<?php echo $maildata; ?>);	
	var tabSelected = '<?php echo $tabselected; ?>';
	document.getElementById("mailsubject").value = "I enjoyed this and you might like it too.";	
	document.getElementById("mailContent").value = 'Hey, I found this discussion topic that i really liked. I thought you might like it too. <br />\
	Click on <a href="'+mailContent.topicUrl+'">'+mailContent.topicName+'</a>to check it out!!!<br />\
	Have fun!<br />'+mailContent.displayName;	
	document.getElementById("mailsubject").disabled = true;
	document.getElementById("mailContent").disabled = true;		
</script>
<?php 
      echo "<script language=\"javascript\"> "; 	
      if($editTopic == 'edit'){
          if($userId == $main_message['userId']){
              if(count($topic_messages) == 0){
                  echo "setUpdateTopicOverlay($topicId);"; 
              }
          }
      }
      //echo "getRelatedContent('1',showRelatedQues);";
	echo "</script>";
?>
