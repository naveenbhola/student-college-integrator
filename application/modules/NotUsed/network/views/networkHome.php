<?php
if($institute == 1)
{
$title = "College Network";
$js = "network";
$networktitle = 'Shiksha.com– Groups of College, University, Institute, Community, Forum, Discussion – Education & Career Options' ;
$networkdescription = 'Join and share in the group of your college, university, institute. Discuss career and education related queries in the group discussions. Share and gain now on Shiksha.com.';
$networkKeywords = 'Shiksha, college groups , Ask & Answer, Education, Career Forum Community, Study Forum, Education & Career Counselors, Career Counselling, study circle, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships';
}
else
{
$title = "School Network";
$js = "schoolNetwork";
$networktitle = 'Shiksha.com– Groups of School, High Schools, Community, Forum, Discussion – Education & Career Options';
$networkdescription = 'Join and share in the group of your school, high schools, institute. Discuss career and education related queries in the group discussions. Share and gain now on Shiksha.com.'; 
$networkKeywords = 'Shiksha, school groups , Ask & Answer, Education, Career Forum Community, Study Forum, Education & Career Counselors, Career Counselling, study circle, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships';
}

		$headerComponents = array(
								'css'	=>	array('header','raised_all','mainStyle','footer'),
								'js'	=>	array('commonnetwork','common',$js,'cityList','imageUpload'),
								'jsFooter'	=>	array('prototype','user'),
								'tabName'	=>	'College Network',
								'taburl' =>  site_url('network/Network/showAllGroups'),	
								'product' => 'Network',
								'title'	=>	$networktitle,
								'metaDescription' => $networkdescription,
								'metaKeywords'	=>$networkKeywords,
								'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
'bannerProperties' => array('pageId'=>'GROUP', 'pageZone'=>'HEADER'),
								'callShiksha'=>1
							);
$this->load->helper('form');		
$this->load->view('common/homepage', $headerComponents);
$this->load->view('network/mailOverlay');
?>
<style>
.raised_graynoBG {background: transparent; } 
.raised_graynoBG .b1, .raised_graynoBG .b2, .raised_graynoBG .b3, .raised_graynoBG .b4, .raised_graynoBG .b1b, .raised_graynoBG .b2b, .raised_graynoBG .b3b, .raised_graynoBG .b4b {display:block; overflow:hidden; font-size:1px;} 
.raised_graynoBG .b1, .raised_graynoBG .b2, .raised_graynoBG .b3, .raised_graynoBG .b1b, .raised_graynoBG .b2b, .raised_graynoBG .b3b {height:1px;} 
.raised_graynoBG .b2 {background:#DBE5E7; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_graynoBG .b3 {background:#FFFFFF; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_graynoBG .b4 {background:#FFFFFF; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_graynoBG .b4b {background:#FFFFFF; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_graynoBG .b3b {background:#FFFFFF; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_graynoBG .b2b {background:#FFFFFF; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_graynoBG .b1b {margin:0 5px; background:#DBE5E7;} 
.raised_graynoBG .b1 {margin:0 5px; background:#FFFFFF;} 
.raised_graynoBG .b2, .raised_graynoBG .b2b {margin:0 3px; border-width:0 2px;} 
.raised_graynoBG .b3, .raised_graynoBG .b3b {margin:0 2px;} 
.raised_graynoBG .b4, .raised_graynoBG .b4b {height:2px; margin:0 1px;} 
.raised_graynoBG .boxcontent_graynoBG {display:block; background-color:#FFFFFF; background-position:bottom; background-repeat:repeat-x; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
</style>
<!---   Centre Page--->

<!--Start_Center-->
<div class="mar_full_10p">
	<!--End_Right_Panel-->
	<div id="right_Panel_group">
		
			  		<div class="lineSpace_10">&nbsp;</div>
        <div class="raised_graynoBG"> 
			  <div class="mar_full_5p">
              <?php 
              if($institute == 1) { 
                  $txt = "Search In Institute Groups";
                  $grouptype = "collegegroup";
                  if($groupCategory == "TestPreparation")
                  {
                      $txt = "Search In Test Preparation Groups";
                      $grouptype = "examgroup";
                  }
              }
            else
            {   
                    $txt = "Search In School Groups";
                    $grouptype = "schoolgroups";
            }
        
        ?>
			  		<div class="normaltxt_11p_blk arial" ><span class="float_L">
                    <input id="searchinGroups" class="searchTP" style = "width:140px;position:relative;top:4px;" type="text" onkeyup="if(event.keyCode == 13) searchInSubProperty(document.getElementById('searchinGroups').value,'<?php echo $grouptype?>','');" onblur="enterEnabled=false;if(this.value =='') this.value='<?php echo $txt?>';" onfocus="enterEnabled=true;if(this.value =='<?php echo $txt?>') this.value = '';" value="<?php echo $txt?>" title = "<?php echo $txt?>"/></span>&nbsp;
                    <button class="btn-submit5" style="width:60px;"onclick="if(trim(document.getElementById('searchinGroups').value) != '<?php echo $txt?>'){ searchInSubProperty(document.getElementById('searchinGroups').value,'<?php echo $grouptype?>',''); }" type="button">
                    <div class="btn-submit5">
                    <p class="btn-submit6">Search</p>
                    </div>
                    </button>
                                                            <br clear="left" /></div>
			  </div>
		</div>
        <!-- Invite Friends -->
			  		<div class="lineSpace_10">&nbsp;</div>
        <div class="raised_greenGradient"> 
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_greenGradient">			  
			  <div class="mar_full_5p">
			  		<div class="lineSpace_5">&nbsp;</div>
			  		<div class="normaltxt_11p_blk arial"><span class="fontSize_13p bld float_L">Invite Friends From</span><br clear="left" /></div>
					<div class="lineSpace_12">&nbsp;</div>
					<div class="normaltxt_11p_blk_arial">
                      <div>
                    <?php


if($institute == 1)
{
    $institype = "college";
    $pageNm = 'GROUPSCAT';
}
else
{
    $institype = "school";	
    $pageNm = 'SCHOOLHOME';
}

if($groupCategory == "TestPreparation")
{
$pageNm = 'TESTHOME';
}
                         $base64url = base64_encode(SHIKSHA_GROUPS_HOME_URL.'/network/Network/index/'.$institype.'/2/'.$groupCategory);
			if(!(is_array($validateuser) && $validateuser != "false")) {
                        $onClick = "showuserLoginOverLay(this,'GROUPS_".$pageNm."_RIGHTPANEL_INVITEFRIEND','jsfunction','showInviteFriends');";
                    }else{
                        if($validateuser[0]['quicksignuser'] == 1)
                        {
                            $onClick = "window.location = '/user/Userregistration/index/$base64url/1'";
                        }
                        else
                        {
                            $onClick = "showInviteFriends();";
                        }} ?>
                        <div>
                            <a href="#" onClick = "<?php echo $onClick?>">
                            <img hspace="5" border="0" width = "212px" src="/public/images/invite.jpg"/>
                            </a>
                        </div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="fontSize_12p bld">&amp; Earn Shiksha Point</div>
                      </div>  
            <div class = "clear_L"></div>
                    </div>
			  </div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>	
		<div class="lineSpace_10">&nbsp;</div>
        <!-- Invite Friends Ends -->

        <?php 
        $arr = array(
				'pageNm'=> $pageNm,
			    );	

         $this->load->view('network/recentMembers',$arr);?>

		<div class="lineSpace_10">&nbsp;</div>
		
		<div class="raised_graynoBG"> 
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_graynoBG">			  
			  <div class="mar_full_5p">
			  		<div class="lineSpace_5">&nbsp;</div>
			  		<div class="normaltxt_11p_blk arial"><span class="fontSize_13p bld float_L">Who can join?</span><br clear="left" /></div>
					<div class="lineSpace_5">&nbsp;</div>
					<div class="normaltxt_11p_blk_arial">
						<ul style="margin-top:0;margin-bottom:0">
							<li style="list-style-image:url(/public/images/check.gif); margin-left:-12px" class = "fontSize_12p">Student <div class="lineSpace_10">&nbsp;</div></li>
							<li style="list-style-image:url(/public/images/check.gif); margin-left:-12px" class = "fontSize_12p">Alumni <div class="lineSpace_10">&nbsp;</div></li>
							<li style="list-style-image:url(/public/images/check.gif); margin-left:-12px" class = "fontSize_12p">Prospective Student <div class="lineSpace_10">&nbsp;</div></li>
							<li style="list-style-image:url(/public/images/check.gif); margin-left:-12px" class = "fontSize_12p">Faculty <div class="lineSpace_10">&nbsp;</div></li>							
													</ul>
					</div>
						<div class="normaltxt_11p_blk arial"><span class="fontSize_13p bld float_L">Benefits of Joining</span><br clear="left" /></div>
						<div class="lineSpace_5">&nbsp;</div>
						<div class="normaltxt_11p_blk_arial">
							<ul style="margin-top:0;margin-bottom:0">
								<li style="list-style-image:url(/public/images/eventArrow.gif); margin-left:-23px" class = "fontSize_12p">Find your connections <div class="lineSpace_10">&nbsp;</div></li>
								<li style="list-style-image:url(/public/images/eventArrow.gif); margin-left:-23px" class = "fontSize_12p">Meet old friends <div class="lineSpace_10">&nbsp;</div></li>
								<li style="list-style-image:url(/public/images/eventArrow.gif); margin-left:-23px" class = "fontSize_12p">Share your comments <div class="lineSpace_10">&nbsp;</div></li>							
							</ul>
						</div>					
			  </div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>	
		<div class="lineSpace_10">&nbsp;</div>
		
	</div>
	<!--End_Right_Panel-->

<input type="hidden" id="startOffsetNetwork" value="0"/>
<input type="hidden" id="countOffsetNetwork" value="20"/>
<input type="hidden" id="country" value='2'/>
<input type="hidden" id="alphabet" value='All'/>
<input type="hidden" id="methodName" value="showColleges"/>
<input type="hidden" id="countcity" value="15"/>
<input type="hidden" id="cityId" value="15"/>
<input type="hidden" id="Groupcategory" value="<?php echo $groupCategory?>"/>

<?php 
$this->load->view('network/UploadOverlay');
$arr = array(
				'institute'=> $institute,
			    );	

$this->load->view('network/networkCollege',$arr);
?>
</div>


<!--End_Center-->

<!-- Modified Neha Ends-->

<?php 
        if($institute == 1)
        $inst = "college";
        else
        $inst = "school";
 echo "<script language=\"javascript\"> ";
    if(isset($validateuser[0]['quicksignuser']) && $validateuser[0]['quicksignuser'] == 1)
    {
     echo "var URLFORREDIRECT = '".base64_encode(site_url('network/Network/index/'.$inst))."';";
     echo "var COMPLETE_INFO = 1;";
    }
    else
    echo "var COMPLETE_INFO = 0;";
if(!(is_array($validateuser) && $validateuser != "false"))  
{
echo "var VALIDATE_USER = false;";
}
else
{
echo "var VALIDATE_USER = true;";
}
 echo "var BASE_URL = '".site_url()."';";     
if($institute == 1)
{
echo "var countr = '".$country."';";
if($groupCategory == "Study Abroad")
echo "var countr = document.getElementById('countryforGroups').value;";
 echo "showColleges(countr);";
 }
else
 echo "showSchools();";
      echo "</script>";	  
     
?>
<!--Start_Footer-->
<?php 
$footer = array('pageId'=>'', 'pageZone'=>'');
$this->load->view('common/footer',$footer);
?>
<!--End_Footer-->
