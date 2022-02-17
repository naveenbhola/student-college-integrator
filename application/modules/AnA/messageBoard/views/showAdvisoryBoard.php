<?php
		$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
		$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
		if($userId != 0){
			$tempJsArray = array('commonnetwork','alerts','ana_common','myShiksha');
			$loggedIn = 1;
		}else{
			$tempJsArray = array('commonnetwork','ana_common','myShiksha');
			$loggedIn = 0;
		}
		$bannerProperties = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'HEADER');
		$headerComponents = array(
						'css'	=>	array('raised_all','mainStyle','header'),
                        'js' => array('common','discussion', 'ajax-api'),
						'jsFooter'=>    $tempJsArray,
						'title'	=>	$seoTitle,
						'tabName' =>	'Discussion',
						'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
						'metaDescription'	=>$seoDescription,
						'metaKeywords'	=>'Ask and Answer, Education, Career Forum Community, Study Forum, Education Career Counselors, Career Counseling, study circle, Education Events, Admissions, Scholarships, Examination Results, Career Fairs, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships, shiksha',
						'product'	=>'forums',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
						'bannerProperties' => $bannerProperties,
						'canonicalURL' => $canonicalURL,
						'previousURL' => $previousURL,
						'nextURL' => $nextURL,
						'questionText'	=> $questionText,
						'callShiksha'=>1,
						'notShowSearch' => true,
						'postQuestionKey' => 'ASK_ASKHOME_HEADER_POSTQUESTION'
					);
		$this->load->view('common/header', $headerComponents);

		$isCmsUser = 0;
		if($userGroup === 'cms')$isCmsUser = 1;
		echo "<script language=\"javascript\"> ";
		echo "var BASE_URL = '';";
		echo "var COMPLETE_INFO = ".$quickSignUser.";";
		echo "var URLFORREDIRECT = '".$pageUrl."';";	
		echo "var loginRedirectUrl = '/messageBoard/MsgBoard/userProfile';";
		echo "var loggedIn = '".$loggedIn."';";		
		echo "var loggedInUserId = '".$userId."';";
		echo "var isCmsUser = '".$isCmsUser."';";
		echo "</script> ";

?>
<script>
overlayViewsArray.push(new Array('network/mailOverlay','mailOverlay'));
</script>
<style>
#levelLink a.selected {font-weight: bold; text-decoration: underline;}
</style>
<div class="wrapperFxd">
    	<div class="lh10">&nbsp;</div>
        <div class="bg_head">
        	<div class="float_L" style="width:205px"><img src="/public/images/img_27.gif" border="0" /></div>
            <div style="margin-left:206px">
		<h1 class="fcOrg mb5" style="font-size:27px; font-weight: normal; display: block; line-height: normal;">Ask &amp; Answer Panel of Experts</h1>
                <div class="lineSpace_18" style="margin-top:5px;"><b>At the core of the Shiksha Ask & Answer community you'll find some self-motivated, accomplished and exceptional people who make an extra effort to help students with answer to their critical career related queries and  thus enable them to make a better decision. In an endeavor to  recognize these people for their noble contribution we showcase them in our "Expert Panel".</b></div>
            </div>
            <div class="clear_L">&nbsp;</div>
        </div>
		<?php
		$levelCountMap = array();
		foreach($allVcardAllUser as $value)
		{
			$levelCountMap[$value['Level']] = $value['count'];
		}
		$paginationURL = '/messageBoard/MsgBoard/advisoryBoard/'.$linkSel.'/@start@/@count@';
		$paginationHTML = str_replace('/All/0/50','/',doPagination($totalCount,$paginationURL,$start,$rows,3));
?>
        <div class="bg_head2" id="levelLink"><b>Our Expert Panel:</b>		
			<?php
			if($linkSel=='All'){
				$strLink=' &nbsp; &nbsp; <a href="/messageBoard/MsgBoard/advisoryBoard/All/" class="selected">All</a>';
			} else {
				$strLink=' &nbsp; &nbsp; <a href="/messageBoard/MsgBoard/advisoryBoard/All/">All</a>';
			}
			foreach($allVcardAllUser as $key=>$levelDetails)
			{
				if($levelDetails['Level'] == $linkSel)
				{
					if($levelDetails['count']!=0){$strLink .=',&nbsp;&nbsp;<a href="/messageBoard/MsgBoard/advisoryBoard/'.$levelDetails['Level'].'-community-members/" class="selected">'.$levelDetails['Level'].' ('.$levelDetails['count'].')</a>';}
				}
				else
				{
					if($levelDetails['count']!=0){$strLink .=',&nbsp;&nbsp;<a href="/messageBoard/MsgBoard/advisoryBoard/'.$levelDetails['Level'].'-community-members/">'.$levelDetails['Level'].' ('.$levelDetails['count'].')</a>';}
				}
			}
			echo $strLink;
			?>
		</div>
        <!--Start_ListingPanel-->
        <div class="mlr10">
            <div class="wdh100">
				<?php $showPaging=0;$flag=0;$preValue='';$subRow=1;
					foreach($allVcardFilterUser as $row => $value) {
						$userProfileLink = SHIKSHA_HOME."/getUserProfile/".$VCardDetails[$row][0]['displayname']."/Answer#userActivities";
						$userPersonalProfilePage = SHIKSHA_HOME."/getUserProfile/".$VCardDetails[$row][0]['displayname'];
						$expertizeString = '';
						for($i=0;$i<count($userExpertize[$row]);$i++){
						  $expertizeString .= ($expertizeString=='')?$userExpertize[$row][$i]['name']:" , ".$userExpertize[$row][$i]['name'];
						}
						$weeklyPointsValue = 0;
						for($z=0;$z<count($weeklyPoints);$z++){
						  if($VCardDetails[$row][0]['userId']==$weeklyPoints[$z]['userid'])
							$weeklyPointsValue = isset($weeklyPoints[$z]['weeklyPoints'])?$weeklyPoints[$z]['weeklyPoints']:0;
						}
						$answerString = ($VCardDetails[$row][0]['totalAnswers']>1)?$VCardDetails[$row][0]['totalAnswers']." Answers":$VCardDetails[$row][0]['totalAnswers']." Answer";
						$likeString = ($otherUserDetails[$row][0]['likes']>1)?$otherUserDetails[$row][0]['likes']." Upvotes":$otherUserDetails[$row][0]['likes']." Upvotes";
						if($preValue!=$value['Level']){
							$preValue = $value['Level'];
						?>
							<div class="wdh100  mtb10">
								<div class="float_L Fnt16">
									<b><?php echo '<h2 style="font-size: 16px;">'.$preValue.' ('.$levelCountMap[$preValue].')</h2>'; ?>
									</b></div>
								<div class="float_R">
								<?php if($showPaging<=0){ ?>
									<div class="pagingID txt_align_r" id="paginataionPlace1" style="line-height:23px"><?php echo $paginationHTML;?></div>
								<?php } else { echo "&nbsp;"; } ?>
								</div>
								<div class="clear_B mb5">&nbsp;</div>
								<div class="brdbottom"></div>
							</div>
							<div class="wdh100 mtb5">
								<div class="float_L" style="width:58px"><img src="<?php $defaultImg='/public/images/photoNotAvailable.gif'; echo ($VCardDetails[$row][0]['imageURL']!='')?$VCardDetails[$row][0]['imageURL']:$defaultImg; ?>" width="58" height="52" /></div>
								<div style="margin-left:68px">
									<div class="float_L wdh100">
										<div class="float_L"><a href="<?php echo $userPersonalProfilePage;?>" class="Fnt14"><b><?php echo ($VCardDetails[$row][0]['firstname']!='')?$VCardDetails[$row][0]['firstname'].'&nbsp;'.$VCardDetails[$row][0]['lastname']:$VCardDetails[$row][0]['displayname']; ?></b></a></div>
										<div class="float_R grayColor">
											<a href="javascript:void(0);" class="Fnt11" onclick="javascript: try{ showMailOverlay('<?php echo $userId;?>','','<?php echo $VCardDetails[$row][0]['userId'];?>','<?php echo $VCardDetails[$row][0]['displayname'];?>','ASK_EXPERT_MIDDLEPANEL_MAILUSER',this,'<?php echo $emailTrackingPageKeyId;?>') } catch (e) {} return false;">Send Mail</a>  <span style="color:#8D8D8D">|</span>  
											<?php if($value['isFriend']=='false'){ ?><?php } ?>
											<?php if(isset($followUserArr[$VCardDetails[$row][0]['userId']])){ ?>
											<span id="followUserLink<?php echo $VCardDetails[$row][0]['userId'];?>" class="Fnt11">Following</span>
											<?php }else{ ?>
											<span id="followUserLink<?php echo $VCardDetails[$row][0]['userId'];?>"><a href="javascript:void(0);" class="Fnt11" onclick="followUser('<?php echo $VCardDetails[$row][0]['userId'];?>','ASK_EXPERT_MIDDLEPANEL_ADDUSER',this,true,'','<?php echo $followTrackingPageKeyId;?>');">Follow</a></span>&nbsp;&nbsp;<span id="followUserImage<?php echo $VCardDetails[$row][0]['userId'];?>" style="display:none;"><img src='/public/images/working.gif' align='absmiddle'/></span>
											<?php } ?>
										</div>
										<div class="clear_B">&nbsp;</div>
										<div class="lineSpace_19"> <?php echo "<b>".$preValue ."</b> (".$otherUserDetails[$row][0]['totalPoints']." Points, ".$weeklyPointsValue." Points this week)</div>"; ?>
										<div class="lineSpace_19"><b>Expertise:</b> <?php echo $expertizeString; ?></div>
										<div class="lineSpace_19"><b>Contribution:</b> <?php echo "<a href='".$userProfileLink."'>".$answerString."</a> (  ".$likeString." )";?></div>
										<?php if(isset($VCardDetails[$row][0]['aboutMe'])){ ?>
										<div style="margin-top:10px;"><b><?php echo ($VCardDetails[$row][0]['firstname']!='')?$VCardDetails[$row][0]['firstname'].'&nbsp;'.$VCardDetails[$row][0]['lastname']:$VCardDetails[$row][0]['displayname']; ?>'s Profile</b></div>
										<div class="lineSpace_16"><span class="ffV"><?php echo $VCardDetails[$row][0]['highestQualification']; ?></span>, <?php echo $VCardDetails[$row][0]['designation'].", ".$VCardDetails[$row][0]['instituteName']; ?></div>
										<?php if(($VCardDetails[$row][0]['aboutMe']!='')){ ?><div class="lineSpace_16 mb10" style="color:#7b7b7b;margin-top:5px;"><?php echo $VCardDetails[$row][0]['aboutMe']; ?></div><?php } ?>
										<?php if(($VCardDetails[$row][0]['aboutCompany']!='')){ ?><div class="lineSpace_16 mb10" style="margin-top:5px;">About my Company: <?php echo $VCardDetails[$row][0]['aboutCompany']; ?></div><?php } ?>
										<?php if(isset($VCardDetails[$row][0]['blogURL'])){ 
										if($VCardDetails[$row][0]['blogURL']!='' || $VCardDetails[$row][0]['facebookURL']!='' || $VCardDetails[$row][0]['linkedInURL']!='' || $VCardDetails[$row][0]['twitterURL']!='' || $VCardDetails[$row][0]['youtubeURL']!=''){
										?>
											<div style="margin-top:7px"><span class="float_L" style="position:relative;top:2px">Catch <?php echo ($VCardDetails[$row][0]['firstname']!='')?$VCardDetails[$row][0]['firstname'].'&nbsp;'.$VCardDetails[$row][0]['lastname']:$VCardDetails[$row][0]['displayname']; ?> on:</span> 
											  <?php if(isset($VCardDetails[$row][0]['blogURL']) && $VCardDetails[$row][0]['blogURL']!=''){ ?><a href="<?php echo $VCardDetails[$row][0]['blogURL'];?>"><img src="/public/images/blog.gif" hspace="5" border="0" title="<?php echo $VCardDetails[$row][0]['blogURL'];?>"/></a><?php } ?>
											  <?php if(isset($VCardDetails[$row][0]['facebookURL']) && $VCardDetails[$row][0]['facebookURL']!=''){ ?><a href="<?php echo $VCardDetails[$row][0]['facebookURL'];?>"><img src="/public/images/facebookwhite.gif" hspace="5" border="0" title="<?php echo $VCardDetails[$row][0]['facebookURL'];?>"/></a><?php } ?>
											  <?php if(isset($VCardDetails[$row][0]['linkedInURL']) && $VCardDetails[$row][0]['linkedInURL']!=''){ ?><a href="<?php echo $VCardDetails[$row][0]['linkedInURL'];?>"><img src="/public/images/linkedIn.gif" hspace="5" border="0" title="<?php echo $VCardDetails[$row][0]['linkedInURL'];?>"/></a><?php } ?>
											  <?php if(isset($VCardDetails[$row][0]['twitterURL']) && $VCardDetails[$row][0]['twitterURL']!=''){ ?><a href="<?php echo $VCardDetails[$row][0]['twitterURL'];?>"><img src="/public/images/twitter.gif" hspace="5" border="0" title="<?php echo $VCardDetails[$row][0]['twitterURL'];?>"/></a><?php } ?>
											  <?php if(isset($VCardDetails[$row][0]['youtubeURL']) && $VCardDetails[$row][0]['youtubeURL']!=''){ ?><a href="<?php echo $VCardDetails[$row][0]['youtubeURL'];?>"><img src="/public/images/youtube.gif" hspace="5" border="0" title="<?php echo $VCardDetails[$row][0]['youtubeURL'];?>"/></a><?php } ?>
											</div>
										<?php }} ?>
										<?php } ?>
										<div class="clear_B">&nbsp;</div>
									</div>                        
								</div>
								<div class="clear_B">&nbsp;</div>
								<div class="dottedLine">&nbsp;</div>
							</div>
						<?php
						} else {
						?>
							<div class="wdh100 mtb5">
								<div class="float_L" style="width:58px"><img src="<?php $defaultImg='/public/images/photoNotAvailable.gif'; echo ($VCardDetails[$row][0]['imageURL']!='')?$VCardDetails[$row][0]['imageURL']:$defaultImg; ?>" width="58" height="52" /></div>
								<div style="margin-left:68px">
									<div class="float_L wdh100">
										<div class="float_L"><a href="<?php echo $userPersonalProfilePage;?>" class="Fnt14"><b><?php echo ($VCardDetails[$row][0]['firstname']!='')?$VCardDetails[$row][0]['firstname'].'&nbsp;'.$VCardDetails[$row][0]['lastname']:$VCardDetails[$row][0]['displayname']; ?></b></a></div>
										<div class="float_R grayColor">
											<a href="javascript:void(0);" class="Fnt11" onclick="javascript: try{ showMailOverlay('<?php echo $userId;?>','','<?php echo $VCardDetails[$row][0]['userId'];?>','<?php echo $VCardDetails[$row][0]['displayname'];?>','ASK_EXPERT_MIDDLEPANEL_MAILUSER',this,'<?php echo $emailTrackingPageKeyId;?>') } catch (e) {} return false;">Send Mail</a>  <span style="color:#8D8D8D">|</span>  
											<?php if($value['isFriend']=='false'){ ?> <?php } ?>
											<?php if(isset($followUserArr[$VCardDetails[$row][0]['userId']])){ ?>
											<span id="followUserLink<?php echo $VCardDetails[$row][0]['userId'];?>" class="Fnt11">Following</span>
											<?php }else{ ?>
											<span id="followUserLink<?php echo $VCardDetails[$row][0]['userId'];?>"><a href="javascript:void(0);" class="Fnt11" onclick="followUser('<?php echo $VCardDetails[$row][0]['userId'];?>','ASK_EXPERT_MIDDLEPANEL_ADDUSER',this,true,'','<?php echo $followTrackingPageKeyId;?>');">Follow</a></span>&nbsp;&nbsp;<span id="followUserImage<?php echo $VCardDetails[$row][0]['userId'];?>" style="display:none;"><img src='/public/images/working.gif' align='absmiddle'/></span>
											<?php } ?>
										</div>
										<div class="clear_B">&nbsp;</div>
										<div class="lineSpace_19"> <?php echo "<b>".$preValue ."</b> (".$otherUserDetails[$row][0]['totalPoints']." Points, ".$weeklyPointsValue." Points this week)</div>"; ?>
										<div class="lineSpace_19"><b>Expertise:</b> <?php echo $expertizeString; ?></div>
										<div class="lineSpace_19"><b>Contribution:</b> <?php echo "<a href='".$userProfileLink."'>".$answerString."</a> ( ".$likeString." )";?></div>
										<?php if(isset($VCardDetails[$row][0]['aboutMe'])){ ?>
										<div style="margin-top:10px;"><b><?php echo ($VCardDetails[$row][0]['firstname']!='')?$VCardDetails[$row][0]['firstname'].'&nbsp;'.$VCardDetails[$row][0]['lastname']:$VCardDetails[$row][0]['displayname']; ?>'s Profile</b></div>
										<div class="lineSpace_16"><span class="ffV"><?php echo $VCardDetails[$row][0]['highestQualification']; ?></span>, <?php echo $VCardDetails[$row][0]['designation'].", ".$VCardDetails[$row][0]['instituteName']; ?></div>
										<?php if(($VCardDetails[$row][0]['aboutMe']!='')){ ?>											
											<div class="lineSpace_16 mb10" style="color:#7b7b7b;margin-top:5px;"><?php echo $VCardDetails[$row][0]['aboutMe']; ?></div>
											<?php } ?>

										<?php if(($VCardDetails[$row][0]['aboutCompany']!='')){ ?><div class="lineSpace_16 mb10" style="margin-top:5px;">About my Company: <?php echo $VCardDetails[$row][0]['aboutCompany']; ?></div><?php } ?>

										<?php if(isset($VCardDetails[$row][0]['blogURL'])){				
										if($VCardDetails[$row][0]['blogURL']!='' || $VCardDetails[$row][0]['facebookURL']!='' || $VCardDetails[$row][0]['linkedInURL']!='' || $VCardDetails[$row][0]['twitterURL']!='' || $VCardDetails[$row][0]['youtubeURL']!=''){
										?>
											<div style="margin-top:7px"><span class="float_L" style="position:relative;top:2px">Catch <?php echo ($VCardDetails[$row][0]['firstname']!='')?$VCardDetails[$row][0]['firstname'].'&nbsp;'.$VCardDetails[$row][0]['lastname']:$VCardDetails[$row][0]['displayname']; ?> on:</span> 
											  <?php if(isset($VCardDetails[$row][0]['blogURL']) && $VCardDetails[$row][0]['blogURL']!=''){ ?><a href="<?php echo $VCardDetails[$row][0]['blogURL'];?>"><img src="/public/images/blog.gif" hspace="5" border="0" title="<?php echo $VCardDetails[$row][0]['blogURL'];?>"/></a><?php } ?>
											  <?php if(isset($VCardDetails[$row][0]['facebookURL']) && $VCardDetails[$row][0]['facebookURL']!=''){ ?><a href="<?php echo $VCardDetails[$row][0]['facebookURL'];?>"><img src="/public/images/facebookwhite.gif" hspace="5" border="0" title="<?php echo $VCardDetails[$row][0]['facebookURL'];?>"/></a><?php } ?>
											  <?php if(isset($VCardDetails[$row][0]['linkedInURL']) && $VCardDetails[$row][0]['linkedInURL']!=''){ ?><a href="<?php echo $VCardDetails[$row][0]['linkedInURL'];?>"><img src="/public/images/linkedIn.gif" hspace="5" border="0" title="<?php echo $VCardDetails[$row][0]['linkedInURL'];?>"/></a><?php } ?>
											  <?php if(isset($VCardDetails[$row][0]['twitterURL']) && $VCardDetails[$row][0]['twitterURL']!=''){ ?><a href="<?php echo $VCardDetails[$row][0]['twitterURL'];?>"><img src="/public/images/twitter.gif" hspace="5" border="0" title="<?php echo $VCardDetails[$row][0]['twitterURL'];?>"/></a><?php } ?>
											  <?php if(isset($VCardDetails[$row][0]['youtubeURL']) && $VCardDetails[$row][0]['youtubeURL']!=''){ ?><a href="<?php echo $VCardDetails[$row][0]['youtubeURL'];?>"><img src="/public/images/youtube.gif" hspace="5" border="0" title="<?php echo $VCardDetails[$row][0]['youtubeURL'];?>"/></a><?php } ?>
											</div>
										<?php }} ?>
										<?php } ?>
										<div class="clear_B">&nbsp;</div>
									</div>                        
								</div>
								<div class="clear_B">&nbsp;</div>
								<div class="dottedLine">&nbsp;</div>
							</div>
						<?php
							continue;
						}						
				?>
				<?php $showPaging=1;$flag=1; } ?>				
                <!--Start_BottomPagination-->
                <div class="wdh100  mtb10">
                    <div class="txt_align_r">
					<div class="pagingID txt_align_r" id="paginataionPlace1" style="line-height:23px"><?php echo $paginationHTML;?></div>
					</div>
                </div>
                <!--Start_BottomPagination-->               
            </div>
        </div>
        <!--End_ListingPanel-->
	</div>

<?php 	
	$this->load->view('common/footer',$bannerProperties1);
?>
<script>

function callPaginationMethod()
{
		$('countSelect1').innerHTML = '<?php echo $selectBoxHTML;?>';
		$('countSelect2').innerHTML = '<?php echo $selectBoxHTML;?>';
		<?php if($categoryId != 1){  ?>
		//createWidgetForDiscussionHome('<?php echo $alertWidget; ?>','byCategory',jscategoryId);
		<?php }?>
}
<?php
	echo "var myQnACountPool = new Array();";
	if($tabselected ==4){
		echo "myQnACountPool['myQnA_". $categoryId ."_". $countryId ."'] = new Array();";
		echo "myQnACountPool['myQnA_". $categoryId ."_". $countryId ."']['totalCount'] =  '". $topicListings['totalQuestionsAnswered'] .'_'. $topicListings['totalQuestions'] .'_'. $topicListings['totalBestAnswers']. "'; myQnACountPool['myQnA_". $categoryId ."_". $countryId ."']['newRepliesCount'] =  '". $topicListings['newRepliesCount']."';";
	}
?>
</script>




