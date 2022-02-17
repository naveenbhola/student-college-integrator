<?php
$detailtitle = 'Shiksha.com– ' .$collegeName. ' Group - Groups of College, University, Institute, Community, Forum, Discussion – Education & Career Options';
$detaildescription = 'Join and share in the group of '. $collegeName.' college, university, institute. Discuss career and education related queries in the group discussions. Share and gain now on Shiksha.com';
$detailKeywords = 'Shiksha, '.$collegeName.' , college groups , Ask & Answer, Education, Career Forum Community, Study Forum, Education & Career Counselors, Career Counselling, study circle, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships';
		$headerComponents = array(
								'css'	=>	array('header','raised_all','header','mainStyle','footer'),
								'js'	=>	array('common','network','commonnetwork'),
								'jsFooter'	=>	array('user','prototype','discussion','myShiksha'),
								'title'	=>	'College Networks',
								'tabName'	=>	'College Network',
								'taburl' =>  site_url('network/Network/collegeNetwork/'.$collegeId.'/'.seo_url($cityid).'/0'),	
								'title'	=>	$detailtitle,
								'metaDescription' => $detaildescription,
								'metaKeywords'	=>$detailKeywords,
								'product' => 'Network',
								'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
'bannerProperties' => array('pageId'=>'GROUP', 'pageZone'=>'HEADER'),
								'callShiksha'=>1
							);
$this->load->helper('form');
$this->load->view('common/homepage', $headerComponents);
?>
<style>
.raised_pinkbg {background: transparent; } 
.raised_pinkbg .b1, .raised_pinkbg .b2, .raised_pinkbg .b3, .raised_pinkbg .b4, .raised_pinkbg .b1b, .raised_pinkbg .b2b, .raised_pinkbg .b3b, .raised_pinkbg .b4b {display:block; overflow:hidden; font-size:3px;} 
.raised_pinkbg .b1, .raised_pinkbg .b2, .raised_pinkbg .b3, .raised_pinkbg .b1b, .raised_pinkbg .b2b, .raised_pinkbg .b3b {height:1px;} 
.raised_pinkbg .b2 {background:#FFE9D4; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
.raised_pinkbg .b3 {background:#FFF8F2; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
.raised_pinkbg .b4 {background:#FFF8F2; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
.raised_pinkbg .b4b {background:#FFE9D4; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
.raised_pinkbg .b3b {background:#FFE9D4; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
.raised_pinkbg .b2b {background:#FFE9D4; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;} 
.raised_pinkbg .b1b {margin:0px 5px; background:#FFE9D4;} 
.raised_pinkbg .b1 {margin:0px 5px; background:#ffffff;} 
.raised_pinkbg .b2, .raised_pinkbg .b2b {margin:0 3px; border-width:0 2px;} 
.raised_pinkbg .b3, .raised_pinkbg .b3b {margin:0 2px; padding:0px;} 
.raised_pinkbg .b4, .raised_pinkbg .b4b {height:2px; margin:0 1px;} 
.raised_pinkbg .boxcontent_pinkbg { display:block; background-image:url(/public/images/bgforignEdu.gif); background-position:bottom; background-repeat:repeat-x; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;}
.raised_pinkbg .boxcontent_pinkbg_c { display:block; background-image:url(/public/images/bgcountry.gif); background-position:bottom; background-repeat:repeat-x; border-left:1px solid #FFE9D4; border-right:1px solid #FFE9D4;}

</style>
<!--Country_Details-->
<div class="mar_full_10p">
		<div class="raised_pinkbg">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_pinkbg_c">
				<div class="row">
					<div class="mar_left_10p float_L" id = "collegeLogo"></div>
					<div>
						<div style="margin-left:140px" class="txt_align_c normaltxt_12p_blk_arial OrgangeFont fontSize_18p bld" id = "collegeName"></div>
						<div class="lineSpace_10">&nbsp;</div>
<!--						<div class="normaltxt_11p_blk bld" align="center" id = "collegeMembers"></div>


						<div class="lineSpace_10">&nbsp;</div>-->
						<div style="margin-left:140px" class="txt_align_c">
						<?php
                                    $base64url = base64_encode((SHIKSHA_GROUPS_HOME_URL.'/network/Network/collegeNetwork/'.$collegeId.'/'.seo_url($cityid).'/0'));
                                    if($member)
                                        $display = "none";
                                    else
                                        $display = '';

                        if(!(is_array($validateuser) && $validateuser != "false")) {
							?>
								<button class="btnJoinNow" id = "join" name = "join" onClick = "javascript:showuserOverlay(this,'join');">Join Now</button>&nbsp;<button class="btninviteFrnd" id = "join" name = "join" onClick = "javascript:showuserOverlay(this,'join');">Invite Friends</button>
								<?php }else{
                                    if($validateuser[0]['quicksignuser'] == 1)
                                    {?>
								<button class="btnJoinNow" id = "join" name = "join" onClick = "javascript:location.replace('/user/Userregistration/index/<?php echo $base64url?>/1');">Join Now</button>&nbsp;<button class="btninviteFrnd" id = "join" name = "join" onClick = "javascript:location.replace('/user/Userregistration/index/<?php echo $base64url?>/1');">Invite Friends</button>
                                    <?php }
                                    else
                                    {
									$userid = $validateuser[0]['userid'];
                                    $user = $userid;
									?>
										<button class="btnJoinNow" id = "join" name = "join" style = "display:<?php echo $display?>" onClick = "javascript:showNetworkOverlay('join',<?php echo $userid?>);">Join Now</button>&nbsp;<button class="btninviteFrnd" id = "join" name = "join" onClick = "javascript:showInviteFriends();">Invite Friends</button>
										<?php }} ?>
						</div>
<!--						<div style="margin-left:140px" class="txt_align_c">
						<?php if(!(is_array($validateuser) && $validateuser != "false")) {
							?>
								<button class="btnJoinNow" id = "join" name = "join" onClick = "javascript:showuserOverlay(this,'join');">Invite Friends</button>
								<?php }else{
									$userid = $validateuser[0]['userid'];
                                    $user = $userid;
									?>
										<button class="btnJoinNow" id = "join" name = "join" onClick = "javascript:showNetworkOverlay('join',<?php echo $userid?>);">Invite Friends</button>
										<?php } ?>
						</div>-->
					</div>


<div class="clear_L"></div>


				</div>

			</div>

			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
</div>
<!--End_of_Country_Details-->
<div class="lineSpace_10">&nbsp;</div>
<!--Start_Mid_Container-->

<!--Start_Center-->
<div class="mar_full_10p">
	<!--End_Right_Panel-->
<div id="right_Panelnetwork">
<!--		<div class="raised_lgraynoBG">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_lbluenoBG">

/*if(!isset($validateuser[0]['userid']))
$user = 0;
else
$user = $validateuser[0]['userid'];
$arr = array(
				'collegeId'=> $collegeId,
				'userId'=>$user,
			    );	
	 $this->load->view('network/userComment',$arr)*/?>
<div class="mar_right_10p" align="right">
<div class="pagingID" id="paginataionPlace5"></div>
<div class="lineSpace_15">&nbsp;</div>
</div>
<div id = "RecentComments">
</div>
<div class="mar_right_10p" align="right">
							
				<div class="pagingID" id="paginataionPlace6"></div>

							</div>
				<div class="lineSpace_10">&nbsp;</div>

			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
		<div class="lineSpace_10">&nbsp;</div>-->
      
	</div>
	<!--End_Right_Panel-->

	<!--Start_Left_Panel-->
	<div id="left_Panel_n1">
		<div class="raised_skyn">
<!--			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>-->
                <div class="boxcontent_skyn">
			<!--		<div class="row deactiveselectyear" style="width:100%">-->
                  <div class="row normaltxt_12p_blk bld deactiveselectyear" style="background:url(/public/images/bgHeaderGroup.gif); background-repeat:repeat-x; background-position:left bottom;width:100%;border-top:1px solid #C8ECFC;  border-bottom:1px solid #C8ECFC;height:30px;">
                  			<div class="lineSpace_5 deactivequestion">&nbsp;</div>
			  		<div class = "deactivequestion" style="">&nbsp;<img src="/public/images/graducationIcon.gif" align="absmiddle" /> &nbsp;<span class="fontSize_12p bld" style="margin-left:7px">Graduation Year</span></div>
					</div>
					<div class="lineSpace_10 deactivequestion">&nbsp;</div>
			  		<div align = "center" class="normaltxt_12p_blk arial  deactivequestion"><a href = "#" class="fontSize_12p bld"  onClick = "return showByGraduationYear(0,0,0,'All')">All Members</a></div>
					<div class="lineSpace_10 deactivequestion">&nbsp;</div>
					<div align="center" class = "deactivequestion"><img src="/public/images/upslider.gif"  style = "cursor:pointer;" onmouseover = "this.src = '/public/images/highlight2.gif'" onmouseout = "this.src = '/public/images/upslider.gif'" onClick = "YearScroll('up',4)"/></div>
					<div class="lineSpace_10 deactivequestion">&nbsp;</div>

				  <div class="row">
		                  <div class="normaltxt_11p_blk lineSpace_20 w9 mar_left_11px">
				  <div id = "Graduation" name = "Graduation"></div>
		                 </div>
                		  </div>
		
			  <div class="row deactiveselectyear" style="width:100%">
  			  <div class="lineSpace_10">&nbsp;</div>
			  <div align="center"><img src="/public/images/downslider.gif" style = "cursor:pointer;" onmouseover = "this.src ='/public/images/highlight1.gif'" onmouseout = "this.src = '/public/images/downslider.gif'"  onClick = "YearScroll('down',4)"/></div>
						  </div>
		</div>
			  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
			<div class="lineSpace_10">&nbsp;</div>

            <!-- Related Questions-->
			<div class="raised_skyn" id = "AskPanel" style ="display:none">				
                <div class="boxcontent_skyn">
                  <div class="row normaltxt_12p_blk bld deactiveselectyear" style="width:100%;border-top:1px solid #C8ECFC;  border-bottom:1px solid #C8ECFC; height:33px;background:url(/public/images/bgHeaderGroup.gif); background-repeat:repeat-x; background-position:left bottom">
                              <div class="quesAnsleftIcon">Answer Questions &amp; Earn Shiksha Points</div>
                  </div>

                  <div class="row" style="width:100%;" id = "relatedquestions">
                  </div>
                  <div class = "fontSize_12p deactivequestion" id = "noquesmsg" align = "center"></div>

					<div class="lineSpace_11 deactivequestion">&nbsp;</div>
				</div>

				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
            <!-- Related Questions End--->

<!-- Subsrcibe Alert-->
<?php 
if($statusFlag == 'unsubscribed'){
$subscribetext = "Check to get email alerts and remain updated with the latest group activity";
$checked = "unchecked";
$subsstatus = "subscribed";
}
else
{
$subscribetext = "Uncheck if you don't want to receive any email alert regarding group activities";
$checked = "checked";
$subsstatus = "unsubscribed";
}
?>
<input type = "hidden" id = "subsstatus" value = "<?php echo $subsstatus?>"/>
<input type = "hidden" id = "groupname" value = "collegegroup"/>
<div id = "alertdiv" style = "display:none">
		<div class="raised_sky">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b> 
				<div class="boxcontent_skyCollegeGrp" style="background:url(/public/images/bgUncheck.gif); background-repeat:repeat-x; background-position:left bottom">	
            <div class="lineSpace_11">&nbsp;</div>
            <div class="normaltxt_11p_blk fontSize_12p bld pd_left_10p" id="alertLabel">
            <input type = "checkbox" <?php echo $checked?> id = "subscribecheck" onChange = "subscribegroupalert(<?php echo $collegeId?>)"/><span id = "subscribeText"><?php echo $subscribetext?></span>
            </div>
           <div class="lineSpace_11">&nbsp;</div>
				  <div class="lineSpace_1">&nbsp;</div>
				</div>			
				<b class="b4b" style="background:#DFECFF"></b><b class="b3b" style="background:#DFECFF"></b><b class="b2b" style="background:#DFECFF"></b><b class="b1b"></b>		
	  </div>			
			<div class="lineSpace_10">&nbsp;</div>
            </div>
            <?php echo "<script>";
                if(isset($validateuser[0]['displayname']) && $member){
                    echo "document.getElementById('alertdiv').style.display = '';";
                    }
                    echo "</script>";
            ?>

<!-- Alert Ends -->


<!-- Subsribe Alert-->

	</div>
    <div id = "commentholder" style = "display:none"></div>
	<!--End_Left_Panel-->
<input type="hidden" id="startOffSet" value="0"/>
<input type="hidden" id="countOffset" value="12"/>
<input type="hidden" id="methodName" value="showuserCollegeNetwork"/>
<!--<input type ="hidden" id ="graduationYear" value = "<?php echo date('Y'); ?>"/>-->
<input type ="hidden" id ="graduationYear" value = "0"/>
<input type ="hidden" id ="collegeId" value = "<?php echo $collegeId ?>"/>
<input type ="hidden" id ="userId" value = "<?php echo $user?>"/>
<input type = "hidden" id = "successfunction" value  = ""/>
<input type = "hidden" id = "startComment" value = "0"/>
<input type = "hidden" id = "commentOffset" value = "5"/>
<input type = "hidden" id="typeofuser1" value=""/>
<!--<input type = "hidden" id = "commentholder" value = ""/>-->
<input type="hidden" id="collegetitle" value=""/>
<?php

$this->load->view('network/userCollegeNetwork');
/*$data = array(
				'successurl'=> site_url('network/Network/collegeNetwork/'.$collegeId),
				'successfunction'=>'',
				'id'=>'',
				'redirect'=> 1,
				
			    );	

$this->load->view('user/userlogin',$data);*/
$this->load->view('network/mailOverlay',$data);


$url = array(
				'successurl'=> '',
				'collegeId'=> $collegeId,
                'cityid'=>$cityid,
                'loggeduser'=> $user,
				'successfunction'=>'',
				'id'=>'add',
				'redirect'=> 0,
				
			    );	
$this->load->view('network/joinNetworkOverlay',$url);


?>
	
<br clear="all" />
</div>
<!--End_Center-->
<!--End_Mid_Container-->


<div class="lineSpace_20">&nbsp;</div>
<!--Start_Footer-->
<?php 
$footer = array('pageId'=>'', 'pageZone'=>'');
$this->load->view('common/footer',$footer);?>
<!--End_Footer-->
 <?php
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
     echo '<script> if(document.getElementById("replyText'. $parentId .'")){ ';
         echo 'reply_form("'.$parentId.'");document.getElementById("replyText'. $parentId .'").value = "'.$content.'"; 
         if('.$questionId.' != threadId)
         {
             document.getElementById("replyText'. $parentId .'").value = "";
         }
     }
     </script>';
 }
?>
<?php 
	$commentCountForTopic = isset($main_message['msgCount'])?$main_message['msgCount']:0;
 echo "<script language=\"javascript\"> ";
if(!isset($validateuser[0]['userid']))
{
echo "userid = 0;";
echo "Flag = 0;";
}
else
{
echo "userid =".$validateuser[0]['userid'].";";
echo "Flag = 1;";
}
    if(isset($validateuser[0]['quicksignuser']) && $validateuser[0]['quicksignuser'] == 1)
    {
     echo "var URLFORREDIRECT = '".base64_encode(getSeoUrl($collegeId,'collegegroup',$collegeName))."';";
     echo "var COMPLETE_INFO = 1;";
    }
    else
    echo "var COMPLETE_INFO = 0;";
 echo "var BASE_URL = '".site_url()."';";     
 echo "showtotalMembers();";
echo "setGraduationYear();";
echo "showrelatedquestions();";
echo "var year = ".date('Y').";";
echo "var maxyear = year + 2;" ;
echo "var minyear = year - 1;";
echo "var total = 4;";
echo "showYearCalendar(maxyear,minyear,total);";
echo "var SITE_URL = '".base_url()."/';";
echo "var commentCount  = ".$commentCountForTopic.";";
if($join == 1 && !$member)
 echo "showuserCollegeNetwork(\"Student','Alumni','Prospective Student','Faculty\",1,document.getElementById('userId').value);";
 else
 echo "showuserCollegeNetwork(\"Student','Alumni','Prospective Student','Faculty\",0,0);";
      echo "</script>";	  
     
?>
