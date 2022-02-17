<?php
if(isset($validateuser[0]['quicksignuser']) && $validateuser[0]['quicksignuser'] == 1 && $join == 1)
    {
     $URLFORREDIRECT = base64_encode(getSeoUrl($collegeId,'collegegroup',$listingDetails[0]['instituteName']));
      $URL = '/user/Userregistration/index/' . $URLFORREDIRECT . '/1';
     header('Location:' .$URL);
     exit;
    }

$detailtitle = 'Shiksha.com– ' .$listingDetails[0]['instituteName']. ' Group - Groups of College, University, Institute, Community, Forum, Discussion – Education & Career Options';
$detaildescription = 'Join and share in the group of '. $listingDetails[0]['instituteName'].' college, university, institute. Discuss career and education related queries in the group discussions. Share and gain now on Shiksha.com';
$detailKeywords = 'Shiksha, '.$listingDetails[0]['instituteName'].' , college groups , Ask & Answer, Education, Career Forum Community, Study Forum, Education & Career Counselors, Career Counselling, study circle, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships';
		$headerComponents = array(
								'css'	=>	array('header','raised_all','header','mainStyle','footer'),
								'js'	=>	array('user','common','commonnetwork','collegenetwork'),
								'jsFooter'	=>	array('prototype','discussion','myShiksha'),
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
$this->load->view('network/createTopicForm');
$this->load->view('network/mailOverlay');
$this->load->view('network/joinNetworkOverlay');

//$this->load->view('network/userComment',$comment);?>
<input type="hidden" id="startOffSet" value="0"/>
<input type="hidden" id="countOffset" value="12"/>
<input type="hidden" id="methodName" value="showEvents"/>
<input type ="hidden" name = "category" id = "category" value = ""/>
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
<input type="hidden" id="startAllEvent" value="0"/>
<input type="hidden" id="countAllEvent" value="<?php echo EVENTS_DETAIL_PAGE ?>"/>



<div class="mar_full_10p normaltxt_11p_blk_arial fontSize_12p">
		<div style="width:248px; float:right">

        <!-- File Sharing Module-->
<!--				<div class="raised_lgraynoBG">
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="h22 raisedbg_sky">
							<div class="normaltxt_11p_blk fontSize_12p arial bld mar_left_10p float_L" style="width:35%">File Sharing</div>
							<div class="txt_align_r"><button class="groubBtm"><img src="/public/images/folderGroup.gif" align="absmiddle" /> Upload your File</button> &nbsp; </div>
							<div class="clear_L"></div>					
						</div>																
						<div class="mar_full_10p">
							<div class="lineSpace_10">&nbsp;</div>
							<div class="fontSize_13p">Upload admission forms, test papers, sample papers</div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="OrgangeFont bld fontSize_12p">No files uploaded</div>
							<div class="lineSpace_10">&nbsp;</div>
						</div>
					</div>
					<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
				<div class="lineSpace_10">&nbsp;</div>-->
                <!-- File Sharing Module Ends -->
                <?php 
                $pageNm = 'GROUPSDETAIL';                
                $arr = array(
				'pageNm'=> $pageNm,
			    );	
                $this->load->view('network/membersofcollege',$arr);?>
                <!-- Related Groups Module-->
                <?php 
$listingDetailId = getListingTypeAndId($listingDetails[0]['detailurl']);
if(count($relatedListings) > 0) { ?>
				<div class="raised_lgraynoBG">
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="h22 raisedbg_sky">
							<div class="normaltxt_11p_blk fontSize_13p arial bld mar_left_10p">Related Groups</div>											
						</div>																						
						<div>
					<?php  $k = 0; while($k < count($relatedListings) && $k < GROUPS_DETAIL_PAGE) { ?> 
                            <div>
                                <div class="lineSpace_10">&nbsp;</div>							
                                    
<?php $listingId = getListingTypeAndId($relatedListings[$k]['url']) ;  
$urlForCollege = getSeoUrl($listingId['typeId'],'collegegroup',$relatedListings[$k]['title']);

if($relatedListings[$k]['imageUrl'] == "") $relatedListings[$k]['imageUrl'] = "/public/images/noPhoto.gif"; $relatedListings[$k]['imageUrl'] = getSmallImage($relatedListings[$k]['imageUrl']);?>
<?php 
if($listingId['typeId'] != $listingDetailId['typeId']) { ?>
			<img src="<?php echo $relatedListings[$k]['imageUrl']?>" hspace="10" align="left" /><a href="<?php echo $urlForCollege?>" title=""><?php echo $relatedListings[$k]['title']?></a><br /><?php echo $relatedListings[$k]['location']?>
<?php } ?>
								<div class="clear_L"></div>
							</div>
<?php $k++; } ?>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>
					<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>	
				<div class="lineSpace_10">&nbsp;</div>
                <?php } ?>
                <!--Related Groups End-->
   
   <!-- Subsrcibe Alert-->
<?php 
if($statusFlag == 'unsubscribed'){
$subscribetext = "Check to get email alerts and remain updated with the latest group activity";
$checked = "unchecked";
$subsstatus = "subscribed";
}
else
{
$subscribetext = "Uncheck if you don't want to receive email alerts for group activities";
$checked = "checked";
$subsstatus = "unsubscribed";
}
?>
<input type = "hidden" id = "subsstatus" value = "<?php echo $subsstatus?>"/>
<input type = "hidden" id = "groupname" value = "collegegroup"/>
<div id = "alertdiv" style = "display:none">

				<div class="raised_lgraynoBG">
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="h22 raisedbg_sky">
							<div class="normaltxt_11p_blk fontSize_13p arial bld mar_left_10p">Group Alert</div>		
						</div>					
						<div class="lineSpace_10">&nbsp;</div>
						<div id = "alertLabel">
							<div class="float_L"><input type = "checkbox" <?php echo $checked?> id = "subscribecheck" onClick = "subscribegroupalert(<?php echo $collegeId?>)"/></div>
							<div id = "subscribeText" class="mar_left_10"><?php echo $subscribetext?></div>
							<div class="clear_L"></div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>
					<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
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

    
                <!-- Poll Starts -->
<!--	    		<div class="raised_lgraynoBG">
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="h22 raisedbg_sky">
							<div class="normaltxt_11p_blk fontSize_12p arial bld mar_left_10p float_L" style="width:35%">Poll</div>
							<div class="txt_align_r"><button class="groubBtm"><img src="/public/images/groupPoll.gif" align="absmiddle" /> Create New Poll</button> &nbsp; </div>
							<div class="clear_L"></div>					
						</div>																
						<div class="mar_full_10p">
							<div class="lineSpace_10">&nbsp;</div>
							<div class="fontSize_12p">Which of the following do you think is the single most important factor in determining which university a student goes to?</div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="fontSize_12p">
								<input type="radio" /> GRE/GMAT score<br />
								<input type="radio" /> Cost<br />
								<input type="radio" /> Consultants<br />
								<input type="radio" /> Faculty<br />
								<input type="radio" /> Luck:)<br />
							</div>
							<div class="lineSpace_10">&nbsp;</div>
							<button class="btn-submit13" type="submit" onclick="" style="width:50px">
								<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Vote</p></div>
							</button> &nbsp; 
							<button onclick="" type="button" class="btn-submit5" style="width:90px">
								<div class="btn-submit5"><p class="btn-submit6">See Result</p></div>
							</button>
							<div class="lineSpace_10">&nbsp;</div>
						</div>
					</div>
					<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
				<div class="lineSpace_10">&nbsp;</div>-->
                <!-- Poll Ends-->
<!--				<div class="raised_lgraynoBG">
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="h22 raisedbg_sky">
							<div class="normaltxt_11p_blk fontSize_12p arial bld mar_left_10p">Privacy Setting</div>										
						</div>																
						<div class="mar_full_10p">
							<div class="lineSpace_10">&nbsp;</div>							
							<div class="fontSize_12p">
								<input type="checkbox" /> I wish to be contacted via Mobile<br />
								<input type="checkbox" /> I wish to be contacted via Email<br />
								<input type="checkbox" /> I wish to receive newsletters via Email<br />
							</div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="txt_align_c">
								<button onclick="" type="button" class="btn-submit5" style="width:100px">
									<div class="btn-submit5"><p class="btn-submit6">Save Setting</p></div>
								</button>
							</div>
							<div class="lineSpace_10">&nbsp;</div>
						</div>
					</div>
					<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>	-->
				<div class="lineSpace_10">&nbsp;</div>
		</div>
		<!--LeftPanel-->
		<div style="margin-right:258px">
			<div style="float:left; width:100%;" class="">
					<div>
						<div class="raised_group"> 
							<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
							<div class="boxcontent_group">
								<div class="mar_full_10p txt_align_c">
<?php
if($listingDetails[0]['thumbUrl'] != '') {?>
<div style="background:url(<?php echo getMediumImage($listingDetails[0]['thumbUrl'])?>) no-repeat left top; padding:0px 0px 25px 100px" />
<?php } ?>
										<h1>
											<span class="fontSize_16p"><?php echo $listingDetails[0]['instituteName']?></span> 
											<span class="fontSize_13p bld blackFont" id = "membercount">
                                           <?php  
            if($usergroup['totalCount'] == 0)
                $noofmembers = 'No members';
            if($usergroup['totalCount'] == 1)
                $noofmembers = "1 member";
            if($usergroup['totalCount'] > 1)
                $noofmembers = $usergroup['totalCount'] . " members";  echo "(". $noofmembers .")";?>
                                            
                                            
                                            
                                            </span>
                                            <div class="lineSpace_10">&nbsp;</div>
						<?php
                                    $base64url = base64_encode((SHIKSHA_GROUPS_HOME_URL.'/network/Network/collegeNetwork/'.$collegeId.'/'.seo_url($cityid).'/0'));
                                    if($member)
                                        $display = "none";
                                    else
                                        $display = '';
                        if(!(is_array($validateuser) && $validateuser != "false")) {
                        $jsfunc1 = "showNetworkOverlay(\'join\',\'\')";
                        $jsfunc2 = "showInviteFriends()";
							?>
								<button class="btnJoinNow" id = "join" name = "join" onClick = "javascript:showuserLoginOverLay(this,'GROUPS_GROUPSDETAIL_MIDDLEPANEL_JOINGROUP','jsfunction','showNetworkOverlay','join','');"><b>Join Now</b></button>&nbsp;<button class="btninviteFrnd" id = "join" name = "join" onClick = "javascript:showuserLoginOverLay(this,'GROUPS_GROUPSDETAIL_MIDDLEPANEL_INVITEFRIEND','jsfunction','showInviteFriends');"><b>Invite Friends</b></button>
								<?php }else{
                                    if($validateuser[0]['quicksignuser'] == 1)
                                    {?>
								<button class="btnJoinNow" id = "join" name = "join" onClick = "javascript:location.replace('/user/Userregistration/index/<?php echo $base64url?>/1');"><b>Join Now</b></button>&nbsp;<button class="btninviteFrnd" id = "join" name = "join" onClick = "javascript:location.replace('/user/Userregistration/index/<?php echo $base64url?>/1');"><b>Invite Friends</b></button>
                                    <?php }
                                    else
                                    {
									$userid = $validateuser[0]['userid'];
                                    $user = $userid;
									?>
										<button class="btnJoinNow" id = "join" name = "join" style = "display:<?php echo $display?>" onClick = "javascript:showNetworkOverlay('join',<?php echo $userid?>);"><b>Join Now</b></button>&nbsp;<button class="btninviteFrnd" id = "join" name = "join" onClick = "javascript:showInviteFriends();"><b>Invite Friends</b></button>
										<?php }} ?>
										</h1>
								<div class = "lineSpace_5">&nbsp;</div>	
                                    </div>
								</div>
							</div>
							<b class="b1b" style="margin:0;"></b>
						</div>
                        <?php 
                        $shortdesc = htmlspecialchars_decode($listingDetails[0]['shortDesc']);
                        if(strlen($listingDetails[0]['shortDesc']) > 500)
                        $shortdesc = strip_tags(substr($listingDetails[0]['shortDesc'],0,500).'...');?>
						<div class="raised_lgraynoBG"> 
							<div class="boxcontent_lgraynoBG fontSize_12p">
							<div class="lineSpace_10">&nbsp;</div>
							<p style="margin:0 10px">
                            <?php if($listingDetails[0]['url'] != '') {?>
								<img src="<?php echo getMediumImage($listingDetails[0]['url'])?>" align="left" style="margin-right:10px" />
                                <?php } ?>
                                <span style = "margin-right:10px"><?php echo $shortdesc?></span>
							</p>
							<div class="mar_full_10p txt_align_r"><a href="<?php echo $listingDetails[0]['detailurl']?>">View details</a></div>
							<div class="lineSpace_10">&nbsp;</div>
							</div>
							<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
						</div>										
					</div>
					<div class="lineSpace_10">&nbsp;</div>
                    <!-- Group News Start -->
                    <?php if($groupnews != "False" && count($groupnews) > 0) { ?>
					<div class="raised_lgraynoBG">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						<div class="boxcontent_lgraynoBG">

								<div class="h22 raisedbg_sky">
									<div class="normaltxt_11p_blk fontSize_13p arial bld mar_left_10p">Group News</div>								
								</div>
								<div class="lineSpace_10">&nbsp;</div>
								<div class="mar_full_10p">
									<?php for($k = 0;$k < 4;$k++) { ?>
									<div style="padding-bottom:7px" id = "<?php echo 'news'.$k?>"><?php echo $groupnews[$k]['newstext']?></div>
									<?php } ?>
									<span id = "morenewsdiv" style = "display:none">
									<?php for($k = 4;$k < count($groupnews);$k++) { ?>
									<div style="padding-bottom:7px;" id = "<?php echo 'news'.$k?>"><?php echo $groupnews[$k]['newstext']?></div>
									<?php } ?>
									</span>
									<?php if(count($groupnews) > 4) { ?>
									<div class="txt_align_r" id = "Seemorediv"><a href="#" onClick = "return Openmorenewsdiv();"><img src="/public/images/plusSign.gif" border="0"/> See more Network Updates</a></div>
									 <?php } ?>
								</div>
						</div>
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
                    <?php } ?>
                    <!-- Group News End-->

					<!-- College Discussion Board Start-->
                    <div class="raised_lgraynoBG">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						<div class="boxcontent_lgraynoBG">
							<div class="h22 raisedbg_sky">
								<div class="normaltxt_11p_blk fontSize_13p arial bld float_L" style = "width:45%"><span class="pd_left_10p">College Discussion Board</span></div>								
						<?php
							if(!(is_array($validateuser) && $validateuser != "false")) { 
								$onClick = 'showuserLoginOverLay(this,"GROUPS_GROUPSDETAIL_MIDDLEPANEL_ADDTOPIC","jsfunction","checkmember",this.form,"","collegeNetwork","'.$collegeId.'","group","Only members are allowed to add comment.Please join the group to participate in discussions","addTopic");return false;';
							}else {
								if($validateuser[0]['quicksignuser'] == 1) {
							        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                    $urlClick = '/user/Userregistration/index/'.$base64url.'/1';
                                $onClick = "window.location = '".$urlClick."'";
								} 
                                if($member)
                                $onClick = 'showNewTopicForm();';
                                else
                                {
                                $onClick = 'showNotaMemberMessage("Only members are allowed to start discussions.Please join the group to participate in discussions",0)';
								}
							}
						?>
								<div class="txt_align_r bld">
									<button class="groubBtm" onClick = '<?php echo $onClick?>'><span class="bld">Start new discussion</span></button> &nbsp; 
								</div>
								<div class="clear_L"></div>					
							</div>
							<div class="lineSpace_9">&nbsp;</div>
                            <?php if(count($messages) == 0){ ?>
							<div style="margin:0 2px" class="txt_align_c">
								<div style="padding:20px 0">Group members have not started any discussion yet, why not you start one?</div>
                            </div>
                            <?php } else { ?>
							<div style="margin:0 2px" class="bgGraySubHeader">
								<div class="float_L lineSpace_20 bld" style="width:44%; padding:0 10px">Forums</div>
								<div class="float_L lineSpace_20 bld" style="width:10%; padding-left:10px">Views</div>
								<div class="float_L lineSpace_20 bld" style="width:12%; padding-left:10px">Messages</div>
								<div class="float_L lineSpace_20 bld" style="width:21%; padding-left:10px">Last Message</div>
								<div class="clear_L"></div>
							</div>
                                <?php for($k = 0;$k<count($messages);$k++){?>
							<div style="margin:0 2px;">
								<div>
									<div class="lineSpace_9">&nbsp;</div>
                                    <?php 
                                    $msgText = $messages[$k]['msgTxt'];
                                    if(strlen($messages[$k]['msgTxt']) > 100)
                                    $msgText = substr($messages[$k]['msgTxt'],0,97) . '...';?>
									<div class="float_L" style="width:44%; padding:0 10px">
										<div class="forumsIcon"><a href="<?php echo $messages[$k]['url'].'/network'?>"><?php echo $msgText?></a><br /></div>
									</div>
									<div class="float_L" style="width:10%; padding-left:10px"><?php echo $messages[$k]['viewCount']?></div>
									<div class="float_L" style="width:12%; padding-left:10px"><?php echo $messages[$k]['msgCount']?></div>
                       <?php $username = $messages[$k]['username'];
                if(strlen($messages[$k]['username']) > 10)
                    {
                        $username = substr($messages[$k]['username'],0,7) . "...";
                    }?>
									<div class="float_L" style="width:22%; padding-left:10px"><?php echo $messages[$k]['lastdate']?><br />by <a href="/getUserProfile/<?php echo $messages[$k]['username']?>" title = "<?php echo $messages[$k]['username']?>"><?php echo $username?></a> <img src="/public/images/lastMessageIcon.gif" align="absmiddle" /></div>							
                                    <div class="clear_L"></div>
								</div>

								<div class="lineSpace_9">&nbsp;</div>
                                <?php if($k != count($messages) - 1) { ?>
								<div style="background:url(/public/images/dottedLine.gif); margin:0 5px"><img src="/public/images/dottedLine.gif" /></div>
                                <?php } ?>
								<div class="lineSpace_5">&nbsp;</div>
							</div>														
                                    <?php } ?>
                                <?php if($messages[0]['totalRows'] > TOPICS_DETAIL_PAGE) { ?>
                                <div class="mar_full_10p txt_align_r"><span class = "arrowBullets"><a href="/network/Network/discussionAll/<?php echo $collegeId?>/0/<?php echo TOPICS_VIEW_ALL_PAGE ?>/return/<?php echo $listingDetails[0]['instituteName']?>">View All Topics</a></span></div>
                                <?php } ?>
                        <?php } ?>
								<div class="lineSpace_5">&nbsp;</div>
						</div>
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
                    <!-- College Discussion Board Ends -->
					<div>
<!--						<div style="width:49.5%; float:right;">-->
                                    <!-- Video Upload Starts-->
                                    <!--<div class="raised_lgraynoBG">
									<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
									<div class="boxcontent_lgraynoBG">
										<div class="h22 raisedbg_sky">
											<div class="normaltxt_11p_blk fontSize_12p arial bld mar_left_10p; float_L" style="width:25%"> &nbsp; &nbsp;Videos</div>
											<div class="txt_align_r"><button class="groubBtm"><img src="/public/images/uploadVideo.gif" align="absmiddle" /> Upload your videos</button> &nbsp; </div>							
											<div class="clear_L"></div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p">
											<div>View and Share your Videos</div>
											<div class="OrgangeFont fontSize_14p bld">Videos</div>
											<div class="lineSpace_10">&nbsp;</div>
											<div class="bld">Showing 2 out 2 Videos</div>
											<div class="lineSpace_10">&nbsp;</div>
											<div>
													<div><img src="/public/images/albumPic.gif" border="0" /></div>
													<div class="lineSpace_5">&nbsp;</div>
													<div class="documentTxt"><a href="" title="" class="fontSize_12p">Institute Outing</a></div>													
													<div class="mar_top_5p documentTxt">date: 5 July,08</div>
													<div class="mar_top_5p documentTxt groupFilepadd_left20"><span class="documentTxt">everyone in my network can see this files</span></div>																								
													<div class="mar_top_5p">
														<button onclick="" type="button" class="btn-submit5 w1">
															<div class="btn-submit5"><p class="btn-submit6">Edit</p></div>
														</button>&nbsp;
														<button onclick="" type="button" class="btn-submit5" style="width:60px">
															<div class="btn-submit5"><p class="btn-submit6">Delete</p></div>
														</button>
													</div>
													<div class="lineSpace_20">&nbsp;</div>
											</div>
											<div>
													<div><img src="/public/images/albumPic.gif" border="0" /></div>
													<div class="lineSpace_5">&nbsp;</div>
													<div class="documentTxt"><a href="" title="" class="fontSize_12p">Institute Outing</a></div>													
													<div class="mar_top_5p documentTxt">date: 5 July,08</div>
													<div class="mar_top_5p documentTxt groupFilepadd_left20"><span class="documentTxt">everyone in my network can see this files</span></div>																								
													<div class="mar_top_5p">
														<button onclick="" type="button" class="btn-submit5 w1">
															<div class="btn-submit5"><p class="btn-submit6">Edit</p></div>
														</button>&nbsp;
														<button onclick="" type="button" class="btn-submit5" style="width:60px">
															<div class="btn-submit5"><p class="btn-submit6">Delete</p></div>
														</button>
													</div>
													<div class="lineSpace_10">&nbsp;</div>
											</div>
										</div>
									</div>
									<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
								</div>-->
                                <!-- Video Upload Ends-->
                                <!-- Photo Upload -->
					<!--	</div>-->
<!--						<div style="width:49.5%; float:left;">-->
							<!-- Photo Upload -->
                            <!--    <div class="raised_lgraynoBG">
                                <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
									<div class="boxcontent_lgraynoBG">
										<div class="h22 raisedbg_sky">
											<div class="normaltxt_11p_blk fontSize_12p arial bld mar_left_10p float_L" style="width:25%">Albums</div>
											<div class="txt_align_r"><button class="groubBtm"><img src="/public/images/uploadAlbum.gif" align="absmiddle" /> Upload your albums</button> &nbsp; </div>
											<div class="clear_L"></div>					
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p">
											<div>View and Share your Videos</div>
											<div class="OrgangeFont fontSize_14p bld">Albums</div>
											<div class="lineSpace_10">&nbsp;</div>
											<div class="bld">Showing 2 out 2 Videos</div>
											<div class="lineSpace_10">&nbsp;</div>
											<div>
													<div><img src="/public/images/albumPic.gif" border="0" /></div>
													<div class="lineSpace_5">&nbsp;</div>
													<div class="documentTxt"><a href="" title="" class="fontSize_12p">Institute Outing</a></div>													
													<div class="mar_top_5p"><a href="" class="fontSize_12p">2 Photos</a><span class="documentTxt"> | date: 5 July,08</span></div>
													<div class="mar_top_5p documentTxt groupFilepadd_left20"><span class="documentTxt">everyone in my network can see this files</span></div>																								
													<div class="mar_top_5p">
														<button onclick="" type="button" class="btn-submit5 w1">
															<div class="btn-submit5"><p class="btn-submit6">Edit</p></div>
														</button>&nbsp;
														<button onclick="" type="button" class="btn-submit5" style="width:60px">
															<div class="btn-submit5"><p class="btn-submit6">Delete</p></div>
														</button>
													</div>
													<div class="lineSpace_20">&nbsp;</div>
											</div>
											<div>
													<div><img src="/public/images/albumPic.gif" border="0" /></div>
													<div class="lineSpace_5">&nbsp;</div>
													<div class="documentTxt"><a href="" title="" class="fontSize_12p">Institute Outing</a></div>													
													<div class="mar_top_5p"><a href="" class="fontSize_12p">2 Photos</a><span class="documentTxt"> | date: 5 July,08</span></div>
													<div class="mar_top_5p documentTxt groupFilepadd_left20"><span class="documentTxt">everyone in my network can see this files</span></div>																								
													<div class="mar_top_5p">
														<button onclick="" type="button" class="btn-submit5 w1">
															<div class="btn-submit5"><p class="btn-submit6">Edit</p></div>
														</button>&nbsp;
														<button onclick="" type="button" class="btn-submit5" style="width:60px">
															<div class="btn-submit5"><p class="btn-submit6">Delete</p></div>
														</button>
													</div>
													<div class="lineSpace_10">&nbsp;</div>
											</div>
										</div>
									</div>
									<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
								</div>-->
<!--						</div>
						<div class="clear_B"></div>-->
					</div>
					<div class="raised_lgraynoBG">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						<div class="boxcontent_lgraynoBG">
							<div class="h22 raisedbg_sky">
								<div class="normaltxt_11p_blk fontSize_13p arial bld float_L" style="width:25%"><span class="pd_left_10p">Important Dates</span></div>
						<?php
                                        $params = $collegeId."/group/".seo_url($listingDetails[0]['instituteName']);
                                	$eventUrl = '/events/Events/showAddEvent/'.$params;
							if(!(is_array($validateuser) && $validateuser != "false")) { 
								$onClick = 'showuserLoginOverLay(this,"GROUPS_GROUPSDETAIL_MIDDLEPANEL_ADDEVENT","refresh");return false;';
								$onClick = 'showuserLoginOverLay(this,"GROUPS_GROUPSDETAIL_MIDDLEPANEL_ADDEVENT","jsfunction","checkmember",this.form,"","collegeNetwork","'.$collegeId.'","group","Only members are allowed to add an event.","event");return false;';
							}else {
								if($validateuser[0]['quicksignuser'] == 1) {
							        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                    $urlClick = '/user/Userregistration/index/'. $base64url .'/1';
                                $onClick = "window.location = '".$urlClick."'";
								} else {
                                if(!$member)
                                $onClick = 'showNotaMemberMessage("Only members are allowed to add an event.",0)';
                                else
                                {
                                $urlClick = '/events/Events/showAddEvent/'.$params;
                                $onClick = 'window.location = "'.$urlClick.'"';
								}
								}
							}
						?>
								<div class="txt_align_r"><button class="groubBtm" onClick = '<?php echo $onClick?>'><img src="/public/images/addEventGroup.gif" align="absmiddle" />&nbsp; <b>Add an Event</b> &nbsp; </button> &nbsp; </div>
								<div class="clear_L"></div>					
							</div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="mar_full_10p">
								<div>
									<div class="float_L txt_align_r" style="width:59%">
										<div style="line-height: 23px;" class="pagingID" id = "paginationPlace1">
										</div>
									</div>
									<div class="clear_L"></div>
								</div>
							</div>
							
							<div class="mar_full_10p">									
                                    <?php if(count($relatedEvents) > 0) { ?>
                                    <?php $k = 0; while($k < count($relatedEvents) && $k < EVENTS_DETAIL_PAGE) { ?>
									<div class="lineSpace_10">&nbsp;</div>
								<div>
									<div class="lineSpace_10">&nbsp;</div>
									<div class="quesAnsBullets">
										<div class="row"><a href="<?php echo $relatedEvents[$k]['url']?>" class="fontSize_12p"><?php echo $relatedEvents[$k]['title']?></a></div>
										<div class="lineSpace_5">&nbsp;</div>
										<div><?php echo $relatedEvents[$k]['location']?>, <?php echo $relatedEvents[$k]['startDate']?> - <?php echo $relatedEvents[$k]['endDate']?></div>
										<div class="row txt_align_r"><span class="arrowBullets"><a href="<?php echo $relatedEvents[$k]['url']?>">View Details</a></span></div>
										<div class="lineSpace_5">&nbsp;</div>
                                        <?php if($k < (count($relatedEvents) - 1) && $k < (EVENTS_DETAIL_PAGE -1 )) { ?>
										<div class="grayLine"></div>				
                                        <?php } ?>
									</div>
								</div>
                                    <?php $k++; }
                                    ?>
                                    <script>
//doPagination(11,'startAllEvent','countAllEvent','paginationPlace1');
</script>
<?php } else { ?>
		<div class="txt_align_c" style="padding:20px 0">No events found for this group. Why not you add an upcoming event for the group ? </div>
		<div class="lineSpace_10">&nbsp;</div>
<?php } ?>

							</div>
																
						</div>
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
					<div>

 						 <div style="width:49.5%; float:right;">
								<div class="raised_lgraynoBG">
									<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
									<div class="boxcontent_lgraynoBG" style="height:170px;overflow:hidden">
										<div class="h22 raisedbg_sky">
											<div class="normaltxt_11p_blk fontSize_13p arial bld mar_left_10p">Related Articles</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p">

<?php 
                $blogs = $blogs[0]['results'];
if(count($blogs) > 0) { ?>
                                        <?php $m = 0; while($m<count($blogs) && $m < ARTICLES_DETAIL_PAGE) { ?>
											<div class="quesAnsBullets">
                                                <div><a href="<?php echo $blogs[$m]['url']?>" title = "<?php echo $blogs[$m]['blogTitle']?>" class="fontSize_12p"><?php echo strlen($blogs[$m]['blogTitle']) > 50 ? substr($blogs[$m]['blogTitle'],0,50).'...' : $blogs[$m]['blogTitle']?></a></div>
												<div class="lineSpace_10">&nbsp;</div>
											</div>
                                        <?php $m++; }} else { ?>
                                        <div class="txt_align_c" style="line-height:130px">
											No related articles found
										</div>
                                        <?php } ?>
										</div>
									</div>
									<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
								</div>
						</div>


						<div style="width:49.5%; float:left;" id = "AskPanel">
								<div class="raised_lgraynoBG">
									<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
									<div class="boxcontent_lgraynoBG" style="height:170px;overflow:hidden">
										<div class="h22 raisedbg_sky">
											<div class="normaltxt_11p_blk fontSize_13p arial bld mar_left_10p">Related Questions</div>											
										</div>
										<div class="lineSpace_10">&nbsp;</div>
                                        <?php if(count($relatedQuestions) > 0) { ?>
										<div class="mar_full_10p">
                                            <div class="row" style="width:100%;" id = "relatedquestions"></div>
                            <?php    $l = 0;         while($l < count($relatedQuestions) && $l < QUESTIONS_DETAIL_PAGE)  { 
                                               
	$topicQues = $relatedQuestions[$l]['title'];
	if(strlen($topicQues) > 80 ){
		$topicQues = substr($topicQues,0, 77) .'...';
	}
                                               ?>
                                                <div>
												<!--<img src="/public/images/userImg.gif" align="left" hspace="5" />-->
                                                <a href="<?php echo $relatedQuestions[$l]['url']?>" class="fontSize_12p"><?php echo $topicQues?> </a>
												<div class="clear_L"></div>
											</div>
<!--											<div style="line-height:30px">
												<div class="lineSpace_3">&nbsp;</div>
												<img src="/public/images/inactive_user.gif"/> <img src="/public/images/mail.gif"/> <img src="/public/images/plus.gif"/> 
												Ask by <a href="">sush</a> <a style="text-decoration: none;" class="OrgangeFont bld pd_full brdGreen" href="">Bronze Level</a> 
												<span class="graycolor">16 hours ago, 3 view</span>
											</div>-->
											<div class="lineSpace_10">&nbsp;</div>
											<div><a href="<?php echo $relatedQuestions[$l]['url']?>" class="bld">Answer now and earn shiksha points</a></div>
											<div class="lineSpace_10">&nbsp;</div>
                                            <?php $l++ ; }?>
										</div>
                                        <?php } else { ?>
                                            <div class="txt_align_c" style="line-height:130px">
												No related questions found
                                            </div>
                                        <?php }
                                        ?>
									</div>
									<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
								</div>
						</div>
						<div class="clear_B"></div>
					</div>
			</div>
		</div>
		<!--End_LeftPanel-->
</div>
<!--Start_Footer-->
<?php 
$footer = array('pageId'=>'', 'pageZone'=>'');
$this->load->view('common/footer',$footer);?>
<!--End_Footer-->
<?php 
 echo "<script language=\"javascript\"> ";
echo "var BASE_URL = '".site_url()."';";
echo "var EVENT_URL = '".$eventUrl."';";
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
     echo "var URLFORREDIRECT = '".base64_encode(getSeoUrl($collegeId,'collegegroup',$listingDetails[0]['instituteName']))."';";
     echo "var COMPLETE_INFO = 1;";
    }
    else
    echo "var COMPLETE_INFO = 0;";
//echo "showrelatedquestions();";
if($join == 1 && !$member)
{
if($userid <> 0)
echo "showNetworkOverlay('join',".$userid.")";
else
echo "showuserLoginOverLay(document.getElementById('join'),'GROUPS_GROUPSDETAIL_MIDDLEPANEL_JOINGROUP','jsfunction','showNetworkOverlay','join','')";
}
/* echo "showuserCollegeNetwork(\"Student','Alumni','Prospective Student','Faculty\",1,document.getElementById('userId').value);";
 else
 echo "showuserCollegeNetwork(\"Student','Alumni','Prospective Student','Faculty\",0,0);";*/
echo "</script>";	  
?>
<script>
function Openmorenewsdiv()
{
document.getElementById('morenewsdiv').style.display = '';
document.getElementById('Seemorediv').innerHTML = '<a href="#" onClick = " return Closemorenewsdiv();"><img src="/public/images/closedocument.gif" border="0"/> Close Network Updates</a>';
return false;
}

function Closemorenewsdiv()
{
document.getElementById('morenewsdiv').style.display = 'none';
document.getElementById('Seemorediv').innerHTML = '<a href="#" onClick = " return Openmorenewsdiv();"><img src="/public/images/plusSign.gif" border="0"/> See more Network Updates</a>';
return false;
}
</script>
