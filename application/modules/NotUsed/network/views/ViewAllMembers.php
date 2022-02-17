<?php
$detailtitle = 'Shiksha.com– ' .$collegeName. ' Group - Groups of College, University, Institute, Community, Forum, Discussion – Education & Career Options';
$detaildescription = 'Join and share in the group of '. $collegeName.' college, university, institute. Discuss career and education related queries in the group discussions. Share and gain now on Shiksha.com';
$detailKeywords = 'Shiksha, '.$collegeName.' , college groups , Ask & Answer, Education, Career Forum Community, Study Forum, Education & Career Counselors, Career Counselling, study circle, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships';
		$headerComponents = array(
								'css'	=>	array('header','raised_all','header','mainStyle','footer'),
								'js'	=>	array('common','network','commonnetwork','collegenetwork'),
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
$this->load->view('network/mailOverlay');
?>

<input type ="hidden" id="startAllMembers" value="0"/>
<input type ="hidden" id="countAllMembers" value="<?php echo USERS_VIEW_ALL_PAGE ?>"/>
<input type="hidden" id="methodName" value="ViewAllMembers"/>
<input type="hidden" id="collegeId" value="<?php echo $collegeId?>"/>
<input type="hidden" id="loggeduserId" value="<?php echo $userId?>"/>
<input type="hidden" id="grouptype" value="<?php echo $grouptype?>"/>

<div class="mar_full_10p">
 <?php $SeoUrl = getSeoUrl($collegeId,"collegegroup",$collegeName);
 if($grouptype == "TestPreparation")
 $SeoUrl .= "/0/TestPreparation";
 ?>
	<div><a href="<?php echo SHIKSHA_GROUPS_HOME; ?>">Groups</a> &gt;&nbsp;<a href = "<?php echo $SeoUrl?>"><?php echo $collegeName?></a> &gt;&nbsp;<a href = "#" style = "cursor:text">Members</a></div>

	<div class="lineSpace_5">&nbsp;</div>
	<div class="OrgangeFont bld fontSize_14p">Showing all Members for <?php echo $collegeName?></div>
	<div class="lineSpace_5">&nbsp;</div>
</div>
<div class="mar_full_10p normaltxt_11p_blk_arial">		
		<!--LeftPanel-->
		<div>			
					<div class="raised_lgraynoBG">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						<div class="boxcontent_lgraynoBG">							
							<!--Start_Top_Pagination-->
							<div class="lineSpace_5">&nbsp;</div>							
							<div class="mar_left_10p">
                            <?php  if(count($usergroup) < 3)
                            $last = count($usergroup);
                            else
                            $last = 3;
                            ?>
									<div class="float_L bld" id = "countstr1" style="width:40%; line-height:23px">Showing 1 - <?php echo $last ?> documents out of <?php echo $usergroup['totalCount']?></div>
									<div class="float_L txt_align_r" style="width:59%">
										<div style="line-height: 23px;" class="pagingID" id = "paginationPlace2">
										</div>
									</div>
									<div class="clear_L"></div>
							</div>
							<div class="lineSpace_9">&nbsp;</div>
							<!--End_Top_Pagination-->		
							<div class="mar_full_10p">
									<!--Start_MemberBox-->
									<div id = "userAll">										
									</div>
									<!--End_MemberBox-->																		
									<div class="clear_L"></div>
							</div>					
							<!--Start_Bottom_Pagination-->
							<div class="lineSpace_9">&nbsp;</div>
							<div class="mar_left_10p">
									<div class="float_L bld" id = "countstr2" style="width:40%; line-height:23px">Showing 1 - <?php echo $last?> documents out of <?php echo $usergroup['totalCount']?></div>
									<div class="float_L txt_align_r" style="width:59%">
										<div style="line-height: 23px;" class="pagingID" id = "paginationPlace1">
										</div>
									</div>
									<div class="clear_L"></div>
							</div>
							<div class="lineSpace_9">&nbsp;</div>
							<!--End_Bottom_Pagination-->
						</div>						
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
		</div>
		<!--End_LeftPanel-->
</div>
<?php 
$footer = array('pageId'=>'', 'pageZone'=>'');
$this->load->view('common/footer',$footer);?>
<!--End_Footer-->
<?php echo "<script language=\"javascript\"> ";
echo "var BASE_URL = '".site_url()."';";
if($grouptype == "TestPreparation")
echo "var GROUPTYPE = 'TESTMEMBERS';";
else
echo "var GROUPTYPE = 'GROUPMEMBERS';";
if(isset($validateuser[0]['quicksignuser']) && $validateuser[0]['quicksignuser'] == 1)
    {
echo "var URLFORREDIRECT = '".base64_encode("/network/Network/MembersAll/".$collegeId."/0/".USERS_VIEW_ALL_PAGE ."/".$collegeName)."';";

     echo "var COMPLETE_INFO = 1;";
    }
    else
    echo "var COMPLETE_INFO = 0;";
                                    echo "createAllMembers(".json_encode($usergroup).",".$usergroup['totalCount']."," .$userId .")";
                    echo "</script>";
            ?>
