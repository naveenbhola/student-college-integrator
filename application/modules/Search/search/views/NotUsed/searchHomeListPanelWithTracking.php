<!--Start_Mid_Panel-->
<div style="text-align:left; width:790px; float:left; padding-left:10px;">
<div>
	<div id="searchListPlace">
    <?php
	if($results[0]['isSponsored'] == 1) { ?>
        <div class="bgBrown normaltxt_11p_blk txt_align_r" style="padding: 3px 10px 0px 0px; color: rgb(111, 153, 17);">Sponsored Results</div>
	<?php
	}
	?>
    <?php
    $j=0;
	$resultIds = '';
	for($i=0;$i<(sizeof($results)+sizeof($extenedResults));$i++) {
		if($i<sizeof($results)) {
			$searchItem = $results[$i];
		} else {
			$searchItem = $extenedResults[$j];
			$j++;
		}
		
		$searchItem['unique_row_id'] = $unique_search_log_id;
		$searchItem['result_row_no'] = $i+1;
		
		$resultIds .= $searchItem['typeId'] . "-";
		
		if($j==1) {
			echo "<div style=\"font-size: 15px;\"><span style=\"font-weight: normal;\" class=\"blackFont\">Showing other results that match any of the keywords <label class=\"OrgangeFont\">".$extKeyword."</label></span></div>";
		}
		//$title = (strlen($searchItem['title'])>100)?substr($searchItem['title'],0,100)."</b>...":$searchItem['title'];
		$title = $searchItem['title'];
		if($title=="") {
			$title=$searchItem['url'];
		}
		$fullTitle=$searchItem['title'];
		$content = $searchItem['shortContent']==''?'':$searchItem['shortContent'].'..';
		$content=strlen($content)>150?substr($content,0,150)."</b>":$content;
		$type = $searchItem['type'];
		$url = $searchItem['url'];
		$isSponsored = $searchItem['isSponsored'] == "1" ? $searchItem['isSponsored'] : '0';
		$imageUrl = trim($searchItem['imageUrl']) != "" ? $searchItem['imageUrl'] : "";
		$shortContent = $searchItem['shortContent'];
		if(array_key_exists('instituteName',$searchItem)) {
			$instituteName = $searchItem['instituteName'];	
		}
		$sponsoredClass = "";
		$sponsoredHtml = "";
		if($isSponsored == '1') {
			$sponsoredClass = 'bgBrown';
			//$sponsoredHtml  = '<div class="normaltxt_11p_blk txt_align_l" style="color:#6F9911; margin:0px 10px 0px 0px;">Sponsored Result</div>';
		} else {
			//$sponsoredHtml  = '<div class="lineSpace_10">&nbsp;</div>';
			$sponsoredClass = 'bgWhite';
		}
		$grayLine = '';
		if($i < (sizeof($results)+sizeof($extenedResults))-1) {
			$grayLine = '<div class="clear_L grayLine" style="border-bottom:solid 1px #EFEFEF;"></div>';
		}
		$contentLine;
		$icon;
		$caption;
		$requestInfoLine='<a href="'.$url.'">Request Info</a><br/>';
		$reportAbuseLine="";
		$joinGrpUrl="";
		$answerNowUrl="";
		$smsOverlayUrl="";
		$rowWidth="";
		$requestInfoUrl = '';
		$requestInfo = '';
		
		if($validateuser=="false") {
			$smsClickAction = "javascript:showuserLoginOverLay(this,'SEARCH_SEARCHLIST_MIDDLEPANEL_SMSCLICK','refresh');";
			$mailClickAction = "javascript:showuserLoginOverLay(this,'SEARCH_SEARCHLIST_MIDDLEPANEL_MAILCLICK','jsfunction','showSearchMailOverlay','".$type."','".$searchItem['typeId']."','".$url."');";
			$saveProductInfo= "javascript:showuserLoginOverLay(this,'SEARCH_SEARCHLIST_MIDDLEPANEL_SAVERESULTCLICK','jsfunction','saveProduct', '".$type."','".$searchItem['typeId']."');";
			$saveProductAction= "javascript:showuserLoginOverLay(this,'SEARCH_SEARCHLIST_MIDDLEPANEL_SAVERESULTCLICK','jsfunction','saveProduct', '".$type."','".$searchItem['typeId']."');";
			$inviteFriendAction = "javascript:showuserLoginOverLay(this,'SEARCH_SEARCHLIST_MIDDLEPANEL_INVITEFRIENDSCLICK','jsfunction','showInviteFriends');";
			$requestInfoUrl = "javascript:showuserLoginOverLay(this,'SEARCH_SEARCHLIST_MIDDLEPANEL_REQUESTINFOCLICK','refresh');";
		} else {
			if($validateuser[0]['quicksignuser'] == 1 && $validateuser[0]['requestinfouser'] == 1) {
				$base64url = base64_encode($_SERVER['REQUEST_URI']);
				$quickClickAction = "javascript:location.replace('/user/Userregistration/index/".$base64url."/1');return false;";
				$smsClickAction = $quickClickAction;
				$mailClickAction = $quickClickAction;
				$saveProductAction = $quickClickAction;
				$requestInfoUrl = $quickClickAction;
			} else {
				$smsClickAction="javascript:showSearchSmsOverlay('".$type."','".$searchItem['typeId']."','".$url."');";
				$mailClickAction="javascript:showSearchMailOverlay('".$type."','".$searchItem['typeId']."','".$url."');";
				$saveProductAction="javascript:saveProduct('".$type."','".$searchItem['typeId']."');";
				$requestInfoUrl = "javascript:setRequestInfoForSearchParams('".$type."','".$searchItem['typeId']."','". addcslashes($searchItem['title'], "'" )."','".$url."','".mencrypt($searchItem['contact_email'])."');";
			}
			$inviteFriendAction="javascript:showInviteFriends();return false;";
		}
		
		$target = ""; //Set this to _blank in case u want to link to open in a new window
		$questionMarkClass = ""; //This is a Q Mark class that comes in front of Question Snippet
		$snippetTitleClass = "text-decoration:underline"; //This is the color of the title in the snippet (Default href colour which is blue)
		$paddingBottom = "1"; // the padding below the last line and the border
		$WidthForRequestInfo = "";
		$rightMargin = "20px";
    
		switch($type)
		{
			case 'question':
				$caption = '';
				$icon = 'discussion_icon.gif';
				$requestInfo = '&nbsp;';
				$contentLine = getDiscussionHtml($searchItem);
				$snippetTitleClass = "color:rgb(0,0,0)";
				$questionMarkClass = "qmarked";
				$imageUrl = "";
				$title = $fullTitle;
				//echo $contentLine;
				if($searchItem['status']=="live") {
					$answerNowUrl="<img src=\"/public/images/report_icon.gif\" align=\"absmiddle\" /> &nbsp;  &nbsp;  &nbsp;<a href=\"/messageBoard/MsgBoard/topicDetails/".$searchItem['typeId']."/5#gRep\" title=\"Answer Now\">Answer Now</a>";
				}
				$requestInfoLine = '<a href="'.$url.'" title="Add Comment">Add Comment</a>&nbsp;&nbsp;&nbsp;';
				$saveProductInfo = '';
				break;
			
			case 'Event':
				$caption='Event Info';
				$icon = 'event_icon.gif';
				$contentLine = getEventHtml($searchItem);
				$requestInfoLine= '<a href="'.$url.'" title="View Details">View Details</a>&nbsp;&nbsp;&nbsp;';
				$saveProductInfo='';
				break;
			
			case 'scholarship':
				$caption = 'Scholarship Information';
				$icon = 'scholarship_icon.gif';
				$contentLine = getScholarshipHtml($searchItem);
				$smsOverlayUrl="<img src=\"/public/images/smsIcon.gif\" align=\"absmiddle\" />&nbsp;<span style=\"margin-right:22px\"><a href=\"javascript:void(0);\" onClick=\"".$smsClickAction."\" title=\"SMS result\">SMS result</a></span>";
				$saveProductInfo='<img src="/public/images/listing_save.gif" align="absmiddle" />&nbsp;<span id="'.$type.$searchItem['typeId'].'" style="margin-right:22px"><a href="javascript:void(0);" onClick="'.$saveProductAction.'" title="Save result">Save result</a></span>';
				break;
			
			case 'notification':
				$caption = 'Admission Information';
				$icon = 'admission_icon.gif';
				$contentLine = getNotificationHtml($searchItem);
				$smsOverlayUrl="<img src=\"/public/images/smsIcon.gif\" align=\"absmiddle\" />&nbsp;<span style=\"margin-right:22px\"><a href=\"javascript:void(0);\" onClick=\"".$smsClickAction."\" title=\"SMS result\">SMS result</a></span>";
				$saveProductInfo='<img src="/public/images/listing_save.gif" align="absmiddle" />&nbsp;<span id="'.$type.$searchItem['typeId'].'" style="margin-right:22px"><a href="javascript:void(0);" onClick="'.$saveProductAction.'" title="Save result">Save result</a></span>';
				break;
			
			case 'course':
				if($searchItem['packtype'] < 1 || $searchItem['packtype'] == 7) {
					$requestInfo = '&nbsp;';
				} else {
					/*
					$requestInfo= '<button onclick="'.$requestInfoUrl.'" type="button" class="btn-submit5" style="width:200px"><div class="btn-submit5"><p class="btn-submit6">Send Query to this Institute</p></div></button>&nbsp;&nbsp;&nbsp;';
					*/
					$requestInfo= '<div class="txt_align_r"><input type="button" onclick="return calloverlayInstitute(2,\'SEARCH_SEARCHHOME_CENTER_REQUEST_BROCHURE\');" class=" doneBtnReqGray" value="Request E-Brochure"/></div>';
				}
				$caption = 'Course Information &nbsp;&nbsp;&nbsp;';
				$icon = 'course_icon.gif';
				$contentLine = getCourseHtml($searchItem);
				$joinGrpSubUrl=getSeoUrl($searchItem['collegeId'],'collegegroup',strip_tags($searchItem['collegeName']))."/1";
				if($isSponsored!=1) {
					$joinGrpUrl="<img src=\"/public/images/joinGroup.gif\" align=\"absmiddle\" />&nbsp;<a href=\"".$joinGrpSubUrl."\" title=\"Join Group\">Join Group</a>";
				}
				$smsOverlayUrl="<img src=\"/public/images/smsIcon.gif\" align=\"absmiddle\" />&nbsp;<span style=\"margin-right:22px\"><a href=\"javascript:void(0);\" onClick=\"".$smsClickAction."\" title=\"SMS result\">SMS result</a></span>";
				$saveProductInfo='<img src="/public/images/listing_save.gif" align="absmiddle" />&nbsp;<span id="'.$type.$searchItem['typeId'].'" style="margin-right:22px"><a href="javascript:void(0);" onClick="'.$saveProductAction.'" title="Save result">Save result</a></span>';
				break;
			
			case 'institute':
				if($searchItem['packtype'] < 1 || $searchItem['packtype'] ==7 ) {
					$requestInfo = '';
				} else {
					/*
					$requestInfo= '<div class="txt_align_r"><input type="button" value="Send Query to this Institute" class="reqSearchBtn" onclick="'.$requestInfoUrl.'" /></div>';
					*/
					$requestInfo= '<div type="institute"          displayname="'.$searchItem['title'].'"
					locationname="'.$searchItem['location'].'"
					url="'.$searchItem['url'].'"
					title="'.$searchItem['title'].'"
					type="institute" id="reqEbr_'.$searchItem['typeId'].'"
					class="txt_align_r"><input type="button" onclick="return changeproductKey('.$searchItem['typeId'].',\'SEARCH_SEARCHHOME_CENTER_REQUEST_BROCHURE\');"
					class="doneBtnReqGray" value="Request E-Brochure"/></div>';
				}
				$imageUrl = $imageUrl==""?"/public/images/noImage.gif":$imageUrl;
				$content='';
				$WidthForRequestInfo = "width:200px";
				$rightMarginForRequestInfo = "200px";
				$caption = '&nbsp;&nbsp;&nbsp;';
				$icon = 'college_icon.gif';
				$paddingBottom = "10";
				$contentLine = getCollegeHtml($searchItem);
				
				if(!(preg_match('/<b>/',$title))) {
					if($searchItem['courseHighlight']!="" ) {
						if($searchItem['isSponsored'] == 0) {
							$snippetTitleClass = "color:#000;text-decoration:none;font-weight:bold;cursor:text;outline:none;";
							$url = "javascript:void(0)";
						}
					} else {
						if($searchItem['isSponsored'] == 0) {
							$snippetTitleClass = "color:#000;text-decoration:none;font-weight:bold;cursor:text;outline:none;";
							$url = "javascript:void(0)";
						}
						$content=strlen($searchItem['shortContent'])>200?substr($searchItem['shortContent'],0,200)."</b>":$searchItem['shortContent'];
						if(strlen($content) > 0) {
							$content = $content."...";
						}
						//$content=strlen($searchItem['shortContent'])>200?substr($searchItem['shortContent'],0,200)."</b>":$searchItem['shortContent'];
					}
				}
				$joinGrpSubUrl=getSeoUrl($searchItem['typeId'],'collegegroup',strip_tags($searchItem['title']))."/1";
				if($isSponsored!=1) {
					$joinGrpUrl="<img src=\"/public/images/joinGroup.gif\" align=\"absmiddle\" />&nbsp;<a href=\"".$joinGrpSubUrl."\" title=\"Join Group\">Join Group</a>";
				}
				$smsOverlayUrl="<img src=\"/public/images/smsIcon.gif\" align=\"absmiddle\" />&nbsp;<span style=\"margin-right:22px\"><a href=\"javascript:void(0);\" onClick=\"".$smsClickAction."\" title=\"SMS result\">SMS result</a></span>";
				$saveProductInfo='<img src="/public/images/listing_save.gif" align="absmiddle" />&nbsp;<span id="'.$type.$searchItem['typeId'].'" style="margin-right:22px"><a href="javascript:void(0);" onClick="'.$saveProductAction.'" title="Save result">Save result</a></span>';
				break;
			
			case 'blog':
				$numComments = isset($searchItem['noOfComments']) ? $searchItem['noOfComments'] : 0;
				switch($numComments) {
					case 0: $numCommentsText = ''; break;
					case 1: $numCommentsText = '&nbsp;<img src="/public/images/alminiBlog.gif" border="0" align="absmiddle" />'. '1 Comment'; break;
					default: $numCommentsText = '&nbsp;<img src="/public/images/alminiBlog.gif" border="0" align="absmiddle" />'. $numComments .' Comments'; break;
				}
				switch($searchItem['blogtype']) {
					case 'kumkum':
						$caption = "Kum Kum Tandon's Article";
						break;
					case 'exam':
						$caption = "Examination Article";
						break;
					case 'ht':
						$caption = "Powered by HT Horizons";
						break;
					default:
						$caption = "Shiksha Article";
						break;
				}
				$caption .=  $numCommentsText;
				//$caption = $searchItem['blogtype']=="kumkum"?"Article By Kum Kum Tandon":"Examination Article";
				$icon = '';
				$contentLine = '';
				$saveProductInfo='';
				$paddingBottom = "10"; // the padding below the last line and the border
				break;
			
			case 'schoolgroups':
				$caption = 'School Group';
				$icon = '';
				$contentLine = getGroupHtml($searchItem);
				$saveProductInfo='';
				$joinGrpSubUrl=getSeoUrl($searchItem['typeId'],'schoolgroups',strip_tags($searchItem['title']))."/1";
				$joinGrpUrl="<img src=\"/public/images/joinGroup.gif\" align=\"absmiddle\" />&nbsp;<a href=\"".$joinGrpSubUrl."\" title=\"Join Group\">Join Group</a>";
				$inviteFriendUrl="<img src=\"/public/images/inviteFrnd.gif\" align=\"absmiddle\" />&nbsp;<a href=\"javascript:void(0);\" onClick=\"".$inviteFriendAction."\" title=\"Invite Friends\">Invite Friends</a>";
				break;
			
			case 'collegegroup':
				$caption = 'College Group';
				$icon = '';
				$contentLine = getGroupHtml($searchItem);
				$saveProductInfo='';
				$joinGrpSubUrl=getSeoUrl($searchItem['typeId'],'collegegroup',strip_tags($searchItem['title']))."/1";
				$joinGrpUrl="<img src=\"/public/images/joinGroup.gif\" align=\"absmiddle\" />&nbsp;<a href=\"".$joinGrpSubUrl."\">Join Group</a>";
				$inviteFriendUrl="<img src=\"/public/images/inviteFrnd.gif\" align=\"absmiddle\" />&nbsp;<a href=\"javascript:void(0);\" onClick=\"".$inviteFriendAction."\" title=\"Invite Friends\">Invite Friends</a>";
				break;
			
			case 'examgroup':
				$caption = 'Test-Prep Group';
				$icon = '';
				$contentLine = getGroupHtml($searchItem);
				$saveProductInfo='';
				$joinGrpSubUrl=getSeoUrl($searchItem['typeId'],'collegegroup',strip_tags($searchItem['title']))."/1/TestPreparation";
				$joinGrpUrl="<img src=\"/public/images/joinGroup.gif\" align=\"absmiddle\" />&nbsp;<a href=\"".$joinGrpSubUrl."\">Join Group</a>";
				$inviteFriendUrl="<img src=\"/public/images/inviteFrnd.gif\" align=\"absmiddle\" />&nbsp;<a href=\"javascript:void(0);\" onClick=\"".$inviteFriendAction."\" title=\"Invite Friends\">Invite Friends</a>";
				break;
			
			case 'misc':
				$target="_blank";
				$caption = 'Web Search Result';
				$contentLine = getMiscHtml($searchItem);
				$saveProductInfo='';
				break;
		}
		$showImage="";
		if(trim(imageUrl) == "") {
			$showImage = 'none';
		} else {
			$showImage = 'inline';
		}
		$showImage = 'inline';
		if(imageUrl != ''){
			//imgParam =   getImgSize(imageUrl);
		}
		$imageUrl=getSmallImage($imageUrl);
		$imageFrame="";
		$marginLeft="";
    if(trim($imageUrl)!='')
    {
        //$imageFrame='<img src="'.$imageUrl.'" border="0" align="left" style="width:58px; padding-right:10px"/>';
		$imageFrame=<<<MARKUP
		<div style="width:58px; background-image:url($imageUrl); background-repeat:no-repeat; padding-right:10px;float:left;height:58px"></div>
MARKUP;
		//$marginLeft='margin-left:68px';
		//$imageFrame='<div style="padding-right:10px; float:left; display:'.$showImage.'" align="center" class="brd"><img src="'.$imageUrl.'" border="0" /></div>';
    }
    ?>
    <div class="<?php echo $sponsoredClass; ?>" id="listRow<?php echo $i;?>" onMouseOver="showRowPane(this.id);" onMouseOut="hideRowPane(this.id);" style="padding-top:0px; font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#000000; text-decoration:none">
        <div style="float:left;width:100%;line-height:16px;padding-top:10px" onMouseOver="showRowPane(this.parentNode.id);" onMouseOut="hideRowPane(this.parentNode.id);">
		<?php echo $imageFrame?>
			<div style="padding-bottom:3px;<?php echo $marginLeft ?>;">
				<div class="float_R" style="<?php echo $WidthForRequestInfo;?>"><?php echo $requestInfo; ?></div>
				<div class="<?php echo $questionMarkClass; ?>" style="margin-right:<?php echo $rightMarginForRequestInfo;?>">
                    <div>
                        <div class="">
							<!-- <a href="<?php //echo $url; ?>" class="fontSize_12p" style="<?php //echo $snippetTitleClass;?>;" target="<?php //echo $target;?>" title="<?php //echo strip_tags($fullTitle);?>" onClick="ctrClick('<?php //echo $i;?>','<?php //echo $type;?>','<?php //echo $searchItem['typeId'];?>');"><?php //echo $title; ?></a>&nbsp;&nbsp; -->
							<a class="fontSize_12p" style="<?php echo $snippetTitleClass;?>;" target="<?php echo $target;?>" title="<?php echo strip_tags($fullTitle);?>" onClick="ctrClick('<?php echo $i;?>','<?php echo $type;?>','<?php echo $searchItem['typeId'];?>'); trackSearchQueries('<?php echo $searchItem['result_row_no'];?>', '<?php echo $searchItem['typeId'];?>', '<?php echo $url;?>', '<?php echo $searchItem['isSponsored'];?>', 'primary');" href="javascript:void(0);"><?php echo $title; ?></a>&nbsp;&nbsp;
							<?php echo $caption;?>
                            <span style="<?php echo $marginLeft ?>;"><?php echo $sponsoredHtml;?></span>
                        </div>
                    </div>
					<?php
					if(array_key_exists('instituteName',$searchItem) && !empty($instituteName)) { ?>
						<div  style="<?php echo $marginLeft;?>" class="fontSize_12p"><?php echo "This question is about ".$instituteName; ?></div>
					<?php
					}
					?>
                    <?php
					if($contentLine != '') {
					?>
                        <div  style="<?php echo $marginLeft;?>" class="fontSize_12p"><?php echo $contentLine; ?></div>
					<?php
					}
					?>
				</div>
				<div class="clear_R withClear">&nbsp;</div>
			</div>
            <?php
			if($content != '') { ?>
			<!--
			FIX: search jason fix
			Hiding json content as of now
			-->
			<div class="fontSize_12p" style="<?php echo $marginLeft ?>;"><?php //echo $content;?></div>
			<?php
			}
			?>
		</div>
		<div style="line-height:<?php echo $paddingBottom;?>px">&nbsp;</div>
		<?php echo $grayLine; ?>
    </div>
<?php
}
?>
    </div>
</div>
</div>
<div class="clear_R"></div>
<!--End_Mid_Panel-->
<?php
function getDiscussionHtml($searchItem)
{
    $displayname=$searchItem['displayname'];
    $userlevel=$searchItem['userlevel'];
    $creationDate=makeRelativeTime($searchItem['creationDate']);
    $numViews=$searchItem['viewCount']==""?"N.A":$searchItem['viewCount'];
    $profession=$searchItem['profession'];
    $numComments=$searchItem['noOfComments']==""?"N.A":$searchItem['noOfComments'];;
    $views=$numViews==1?"1 View":$numViews." Views";
    $comments=$numComments==1?"1 Answer":$numComments." Answer";
    $userRow="";
    $dateRow="";
    if($displayname!="")
    {
		// Pankaj : change for search tracking
        $userRow = '<span class="grayFont">Asked by </span><a href="'.SHIKSHA_HOME.'/getUserProfile/'.$displayname.'">'.$displayname.'</a></span><span class="grayFont"> '.$creationDate.', '.$views.',</span> <a onclick="trackSearchQueries(\''.$searchItem['result_row_no'].'\', \''.$searchItem['typeId'].'\',\''.$searchItem["url"].'\', \''.$searchItem['isSponsored'].'\');" href="javascript:void(0);"><b>'.$comments.'</b></a>';
    }
    else
    {
        $userRow='<div class="dgreencolor">&nbsp;</div>';
    }
    if($creationDate!="")
    {
        // Pankaj : change for search tracking
		//$answerNowRow='<div style="padding-top:10px"><a href="'.$searchItem["url"].'#gRep">Answer Now &amp; Earn Points</a></div>';
		$answerNowRow = "<div style='padding-top:10px'><a onClick=\"trackSearchQueries('".$searchItem['result_row_no']."', '".$searchItem['typeId']."','".$searchItem["url"]."#gRep','".$searchItem['isSponsored']."');\" href='javascript:void(0);'>Answer Now &amp; Earn Points</a></div>";
	}
    else
    {
        $answerNowRow='<div>&nbsp;</div>';
    }
    $discussionHtml  =   '<div class="font_11p_black">'.$userRow.'<div class="lineSpace_10"></div>'.$answerNowRow.'</div>';
    return($discussionHtml);
}
function getEventHtml($searchItem)
{
    $location = $searchItem['location'];
    $startDate =$searchItem['startDate'];
    $endDate = $searchItem['endDate'];

    $locationRow="";
    $dateRow="";

    if($location != "")
    {
        $locationRow = '<div><span class=" dgreencolor">Location: </span>'.$location.'</div>';
    }
    else
    {
        $locationRow = '<div>&nbsp;</div>';
    }
    $dateRow = ' <div>';
    if($startDate != "")
    {
        $dateRow .= '<span class="dgreencolor">Start Date: </span>'.$startDate .' ';
    }
    if($endDate != '')
    {
        $dateRow .= '<span class="dgreencolor">End Date: </span>'.$endDate.' </div>';
    }
    $eventHtml   =   '<div>'.$locationRow.$dateRow.'</div>';
    return $eventHtml;
}
function getScholarshipHtml($searchItem)
{
    $applicableTo = strlen($searchItem['applicableTo'])>50?substr($searchItem['applicableTo'],0,50):$searchItem['applicableTo'];
    $eligibility = strlen($searchItem['eligibility'])>50?substr($searchItem['eligibility'],0,50):$searchItem['eligibility'];
    $number = strlen($searchItem['number'])>50?substr($searchItem['number'],0,50):$searchItem['number'];
    $value = strlen($searchItem['value'],0,50)?substr($searchItem['value'],0,50):$searchItem['value'];
    $eligibilityRow="";
    if($eligibility != "")
    {
        $eligibilityRow.='<span class="dgreencolor">Eligibility:&nbsp;</span>'.$eligibility.'  ';
    }
    if($applicableTo != '')
    {
        $eligibilityRow.='<span class="dgreencolor">Applicable to:&nbsp;</span>'.$applicableTo;
    }

    $numberRow = '';
    if($value != '')  {
        $numberRow .= '<span class="dgreencolor">Value:&nbsp;</span>'. $value.'  ';
    }
    if($number != '') {
        $numberRow .= ' <span class="dgreencolor">Number:&nbsp;</span>'. $number .'  ';
    }
    $numberRow = $numberRow == '' ? '&nbsp;' : $numberRow;
    $numberRow = '<div>'.$numberRow.'</div>';
    $scholarshipHtml =   '<div>'.$eligibilityRow.$numberRow.'</div>';
    return $scholarshipHtml;
}
function getNotificationHtml($searchItem)
{
    $collegeInfo = $searchItem['collegeInfo'];
    $endDate = $searchItem['endDate'];
    $endDate=($endDate!='1st Jan, 70, 05:30am') ? $endDate:"";
    $collegeInfoRow="";
    $dateRow="";
    if($collegeInfo != '')  {
        $collegeInfoRow = '  <div>'.$collegeInfo.'</div>';
    }
    else
    {
        $collegeInfoRow = '<div class="dgreencolor">&nbsp;</div>';
    }
    if($endDate != '')  {
        $dateRow = ' <div><span class="dgreencolor">Last Date:&nbsp;</span> '.$endDate.'</div>';
    } else {
        $dateRow = '<div>&nbsp;</div>';
    }
    $notificationHtml    =   '<div>'.$collegeInfoRow.$dateRow .'</div>';
    return $notificationHtml;
}
function getCourseHtml($searchItem)
{
    $fee = strlen($searchItem['fees'])>50?substr($searchItem['fees'],0,50):$searchItem['fees'];
    $duration = strlen($searchItem['duration'])>50?substr($searchItem['duration'],0,50):$searchItem['duration'];
    $eligibility = strlen($searchItem['eligibility'])>50?substr($searchItem['eligibility'],0,50):$searchItem['eligibility'];
    $college = $searchItem['college'];

    $collegeRow="";
    if($college != '')  {
        $collegeRow = '  <div class="dgreencolor"> Institute:&nbsp;<span style="color:#000000">'. $college .'</span></div>';
    } else {
        $collegeRow = '<div class="dgreencolor">&nbsp;</div>';
    }
    $feeSpan = '';
    $durationSpan = '';
    $eligibilitySpan = '';
    if($fee!= '') {
        $feeSpan = '<span class="dgreencolor">Fee:&nbsp</span><span style="color:#000000">'.$fee.'</span> &nbsp; &nbsp; &nbsp; &nbsp;';
    }
    if($duration != '') {
        $durationSpan = '<span class="dgreencolor">Duration:&nbsp</span><span style="color:#000000">'.$duration.'</span> &nbsp; &nbsp; &nbsp; &nbsp;';
    }
    if($eligibility!= '')
    {
        $eligibilitySpan = '<span class="dgreencolor">Eligibility:&nbsp</span><span style="color:#000000">'.$eligibility.'</span>' ;
    }
    $feeRow = '  <div>'.$feeSpan.$durationSpan.$eligibilitySpan.' </div>';
    $collegeHtml =   '<div>'.$collegeRow.$feeRow .'</div>';
    return $collegeHtml;
}
function getCollegeHtml($searchItem)
{
    $location = $searchItem['location'];
    $courses = $searchItem['courses'];
    $course_count = $searchItem['course_count'];

    $matchingCourseCount = 0;
    $hiddenMatchingCourseCount = 0;
    $optionalArgs['location'][0] = $searchItem['location'];
    $optionalArgs['institute'] = strip_tags($searchItem['title']);
    
    if($courses!='')
    {
        $courseList = json_decode(base64_decode($courses),true);
		$courseHighLightListTemp = explode(":::",$searchItem['courseHighlight']);
		$courseHighLightList = array();
		for($c=0; $c < count($courseList); $c++){
		/*
			$tagStripedCourseTitle = trim(strip_tags(htmlspecialchars_decode($courseHighLightListTemp[$c])));
			if($tagStripedCourseTitle == trim(strip_tags(htmlspecialchars_decode($courseList[$c]['courseTitle'])))){
				$courseHighLightList[] = $courseHighLightListTemp[$c];
			} else {
				$tempCourseHightLightArr = array_slice($courseHighLightListTemp, $c, strlen($courseHighLightListTemp));
				$courseHighLightList[] = $courseList[$c]['courseTitle'];
				$courseHighLightListTemp[$c] = "";
				$courseHighLightListTemp = array_merge($courseHighLightListTemp, $tempCourseHightLightArr);
			}
		*/
			$courseHighLightList[] = $courseList[$c]['courseTitle'];
		}
		
		$courses = '';
        $highlightCourse = '';
        $hiddenSnippets = 0;
        $CourseSnippetArray = array();
        for($i =0,$j=0; $i<sizeof($courseList); $i++)
        {
            $courseUrl = $courseList[$i]['url'];
            if($searchItem['isSponsored'] == 1)
            {
                if($searchItem['sponsorTypeId'] == $courseList[$i]['course_id'])
                {
                    if($courseHighLightList[$i] == "")
                    {
                        $courseHighLightList[$i] = $courseList[$i]['courseTitle'];
                    }
                    $sponsorTypeMatch=1;
                    $cTypeMatch = 0;
                    $cLevelMatch =0;
                }
                else
                {
                    $cTypeMatch = 0;
                    $cLevelMatch =0;
                    $sponsorTypeMatch=0;
                }
            }
            else
            {
                $cTypeMatch = 1;
                $cLevelMatch =1;
                $cTypeArray = array('full-time','part-time','correspondence','certification','e-learning');
                $cLevelArray = array('under-graduate-degree','post-graduate-degree','diploma','post-graduate-diploma','exam-preparation','vocational','certification');

                if(isset($_REQUEST['cType']) && $_REQUEST['cType']!='-1')
                {
                    if($_REQUEST['cType'] == "others" && (!in_array(str_replace(' ','-',$courseList[$i]['course_type_display']),$cTypeArray)))
                    {
                        $cTypeMatch = 2;
                    }
                    elseif(strtolower(str_replace(' ','-',$courseList[$i]['course_type_display'])) == $_REQUEST['cType'])
                    {
                        $cTypeMatch = 2;
                    }
                    else
                    {
                        $cTypeMatch = 0;
                    }
                }
                if(isset($_REQUEST['courseLevel']) && $_REQUEST['courseLevel']!='-1')
                {
                    if($_REQUEST['courseLevel'] == "others" && (!(in_array(str_replace(' ','-',$courseList[$i]['course_level_display']),$cLevelArray))))
                    {
                        $cLevelMatch =2;
                    }
                    elseif(strtolower(str_replace(' ','-',$courseList[$i]['course_level_display'])) == $_REQUEST['courseLevel'])
                    {
                        $cLevelMatch =2;
                    }
                    else
                    {
                        $cLevelMatch =0;
                    }
                }
                if(preg_match("@^[^<]*</b>@",$courseHighLightList[$i]))
                {
                    $courseHighLightList[$i] = "<b>".$courseHighLightList[$i];
                }
            }
            if(($cLevelMatch && $cTypeMatch)|| $sponsorTypeMatch)
            {
                if(preg_match("/<b>/",$courseHighLightList[$i]) || $sponsorTypeMatch)
                {
                    if(!(preg_match("@</b>@",$courseHighLightList[$i])))
                    {
                        $courseHighLightList[$i] = $courseHighLightList[$i]."</b>";
                    }
                    $courseList[$i]['duration'] = $courseList[$i]['duration_value'] == "" ? "":$courseList[$i]['duration_value']."-".$courseList[$i]['duration_unit']."&nbsp;|&nbsp;";
                    $courseList[$i]['courseType'] = $courseList[$i]['course_type'] == "" ? "":$courseList[$i]['course_type']."&nbsp;|&nbsp;";
                    $courseList[$i]['courselevel'] = $courseList[$i]['course_level'] == "" ? "":$courseList[$i]['course_level'];
					// PANKAJ: Changes for tracking
                    //$courseSnippet = "<div style='margin-top:3px'><a href='".$courseUrl."'>".$courseHighLightList[$i]."</b></a></div><div>".$courseList[$i]['duration'].$courseList[$i]['courseType'].$courseList[$i]['courselevel']."</div>";
					$courseSnippet = "<div style='margin-top:3px'><a href='javascript:void(0);' onClick=\"trackSearchQueries('".$searchItem['result_row_no']."', '".$searchItem['typeId']."','".$courseUrl."' ,'".$searchItem['isSponsored']."');\">".$courseHighLightList[$i]."</b></a></div><div>".$courseList[$i]['duration'].$courseList[$i]['courseType'].$courseList[$i]['courselevel']."</div>";
					if(preg_match_all("@<b>(?<name>[^<]*)</b>@",$courseHighLightList[$i],$matches))
                    {
                        preg_replace("\s+"," ",$courseHighLightList[$i]);
                    }
                    $CourseSnippetArray[] = $courseSnippet;
                    $matchCountArray[] = count($matches['name']);
                }
                else
                {
					if($highlightCourse == "")
                    {
						$temp_duration = $courseList[$i]['duration_value'] == "" ? "":$courseList[$i]['duration_value']."-".$courseList[$i]['duration_unit']."&nbsp;|&nbsp;";
						$temp_course_type = $courseList[$i]['course_type'] == "" ? "":$courseList[$i]['course_type']."&nbsp;|&nbsp;";
						$temp_course_level = $courseList[$i]['course_level'] == "" ? "":$courseList[$i]['course_level'];
					
                        if($j<5)
                        {
							// PANKAJ: Changes for tracking
                            //$courses = "<div><a href='".$courseUrl."'>".$courseList[$i]['courseTitle']."</a></div>". $courses;
							$courses.= "<div><a  href='javascript:void(0);' onClick=\"trackSearchQueries('".$searchItem['result_row_no']."', '".$searchItem['typeId']."','".$courseUrl."' ,'".$searchItem['isSponsored']."');\">".$courseList[$i]['courseTitle']."</a></div>";
							
							$courses .= "<div>".$temp_duration.$temp_course_type.$temp_course_level."</div>";
						}
                        if($j==5)
                        {
							// PANKAJ: Changes for tracking
                            //$courses .= "<div><a href=\"".$searchItem['url']."\">View all ".$course_count." courses of this Institute</a></div>";
							$courses .= "<div><a href='javascript:void(0);' onClick=\"trackSearchQueries('".$searchItem['result_row_no']."', '".$searchItem['typeId']."','".$courseUrl."' ,'".$searchItem['isSponsored']."');\">View all ".$course_count." courses of this Institute</a></div>";
						}
                        $j++;
                    }
                }
            }
            else
            {
				if ($highlightCourse == "")
                {
					$temp_duration = $courseList[$i]['duration_value'] == "" ? "":$courseList[$i]['duration_value']."-".$courseList[$i]['duration_unit']."&nbsp;|&nbsp;";
					$temp_course_type = $courseList[$i]['course_type'] == "" ? "":$courseList[$i]['course_type']."&nbsp;|&nbsp;";
					$temp_course_level = $courseList[$i]['course_level'] == "" ? "":$courseList[$i]['course_level'];
						
                    $courseUrl = $courseList[$i]['url'];
                    if($j<5)
                    {
						// PANKAJ: Changes for tracking
                        //$courses.= "<div><a href='".$courseUrl."'>".$courseList[$i]['courseTitle']."</a></div>";
						$courses.= "<div><a href='javascript:void(0);' onClick=\"trackSearchQueries('".$searchItem['result_row_no']."', '".$searchItem['typeId']."', '".$courseUrl."' , '".$searchItem['isSponsored']."');\">".$courseList[$i]['courseTitle']."</a></div>";
						$courses .= "<div>".$temp_duration.$temp_course_type.$temp_course_level."</div>";
                    }
                    if($j==5)
                    {
						// PANKAJ: Changes for tracking
                        //$courses.= "<div><a href=\"".$searchItem['url']."\">View all ".$course_count." courses of this Institute</a></div>";
						$courses.= "<div><a href='javascript:void(0);' onClick=\"trackSearchQueries('".$searchItem['result_row_no']."' , '".$searchItem['typeId']."' , '".$searchItem['url']."' , '".$searchItem['isSponsored']."');\">View all ".$course_count." courses of this Institute</a></div>";
					}
                    $j++;
                }
            }

        }
    array_multisort($matchCountArray,SORT_DESC,$CourseSnippetArray);
    for($i=0;$i<count($CourseSnippetArray);$i++)
    {
        if($i < 3)
        {
            $highlightCourse .=  $CourseSnippetArray[$i];
            $matchingCourseCount++;
        }
        else
        {
            $highlightCourseAdditional .= $CourseSnippetArray[$i];
            $hiddenMatchingCourseCount++;
        }
    }

    if($highlightCourseAdditional != "")
    {
		// PANKAJ: Changes for tracking
        //$highlightCourse .= "<div><a class='plusSign' href='javascript:void(0);' onClick='this.className=(this.className==\"plusSign\")?\"closedocument\":\"plusSign\";this.parentNode.lastChild.style.display = (this.parentNode.lastChild.style.display == \"none\")?\"\":\"none\";return false;'>View ".$hiddenMatchingCourseCount." more matching courses</a> <div style='display:none;'>".$highlightCourseAdditional."</div></div><div style=\"padding-top:5px\"><a href=\"".$searchItem['url']."\">View all ".$course_count." courses of this Institute</a></div>";
		$highlightCourse .= "<div><a class='plusSign' href='javascript:void(0);' onClick='this.className=(this.className==\"plusSign\")?\"closedocument\":\"plusSign\";this.parentNode.lastChild.style.display = (this.parentNode.lastChild.style.display == \"none\")?\"\":\"none\";return false;'>View ".$hiddenMatchingCourseCount." more matching courses</a> <div style='display:none;'>".$highlightCourseAdditional."</div></div><div style=\"padding-top:5px\"><a href='javascript:void(0)' onClick=\"trackSearchQueries('".$searchItem['result_row_no']."' , '".$searchItem['typeId']."' , '".$searchItem['url']."' , '".$searchItem['isSponsored']."');\">View all ".$course_count." courses of this Institute</a></div>";
    }
    else
    {
        if($highlightCourse !="" && count($courseList) > 1)
        {
            $highlightCourse .= "<div><a href='javascript:void(0);' onClick=\"trackSearchQueries('".$searchItem['result_row_no']."' , '".$searchItem['typeId']."' , '".$searchItem['url']."' , '".$searchItem['isSponsored']."');\">View all ".$course_count." courses of this Institute</a></div>";
        }
    }
    }
    $locationRow="";
    $courseRow="";
    if($location != '')  {
        $locationRow = '<div class="float_R" style="width:100px"><div class="float_L">';
        $locationHiddenRow ="";
        $locationCount=0;
        foreach(array_unique(explode(",",str_replace("-India","",$location))) as $location)
        {
            if($locationCount <3)
            {
                $locationRow .= $location."<br/>";
            }
            else
            {
                if($locationCount == 3)
                {
                    $locationRow .= "<a class='plusSign' href='javascript:void(0);' onClick='this.className=(this.className==\"plusSign\")?\"closedocument\":\"plusSign\";this.parentNode.lastChild.style.display = (this.parentNode.lastChild.style.display == \"none\")?\"\":\"none\";return false;'>More</a>";
                }
                $locationHiddenRow .= $location."<br/>";
            }
            $locationCount++;
        }
        $locationRow .= "<div style=\"display:none\">".$locationHiddenRow."</div>";
        $locationRow .= '</div><div class="clear_L withClear">&nbsp;</div></div>';
    } else {
        $locationRow = '<div class="float_R" style="width:100px">&nbsp;</div>';
    }

    if($highlightCourse!='')
    {
        $courseRow = '<div style="margin-right:120px;margin-left:68px;margin-top:3px"><div class="float_L">'.$highlightCourse.'</div><div class="clear_L withClear">&nbsp;</div></div>';
    }
    elseif($courses!='')
    {
        $courseRow = '<div style="margin-right:120px;margin-top:3px"><div class="float_L" style="width:55px">Courses:&nbsp;</div><div style="margin-left:125px">'.$courses.'</div><div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div></div>';
    }
    else
    {
        $courseRow = '<div style="margin-right:120px;margin-top:3px"><div class="float_L" style="width:55px">Courses:&nbsp;</div><div style="margin-left:125px">No Courses posted under this Institute&nbsp;</div><div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div></div>';
    }
    $collegeHtml =   '<div>'.$locationRow.$courseRow.'<div class="clear_B" style="font-size:1px;line-height:1px">&nbsp;</div></div>';
    return $collegeHtml;
}
function getGroupHtml($searchItem)
{
    $location = $searchItem['location'];
    $members = $searchItem['membercount'];
    if($members == '')
    $members = 'No';
    if($members == 1)
        $members .= " Member";
    else
        $members .= " Members";
    //when list of courses is more than three
    $locationRow="<div>";

    if($location != '')  {
        $locationRow .= ' <span class=" dgreencolor">Location: </span>'.$location.' <span class = "dgreencolor">Members: </span>'.$members.'</span>';
    } else {
        $locationRow .= '<span class=" dgreencolor">&nbsp;</span><span class = "dgreencolor">Members: </span>'.$members.'</span>';
    }

    $locationRow .= '</div>';
    $collegeHtml =   '<div>'.$locationRow.'</div>';
    return $collegeHtml;
}
function getMiscHtml($searchItem)
{
    $sourse = $searchItem['sourceUrl'];
    if($host != '')  {
        $locationRow = ' <div><span class=" dgreencolor">Source: </span>'.$sourse.'</div>';
    } else {
        $locationRow = ' <div><span class=" dgreencolor">Source: </span>'.$sourse.'</div>';
    }
    return $locationRow;
}
?>

<script type="text/javascript">

	var total_search_result_ids = "<?php echo $resultIds;?>";
	var total_search_rows = "<?php echo $numOfRecords;?>";
	var tsZeroResultCase = encodeURIComponent('<?php echo $searchList['zero_result_case'];?>');
	var tsInitialQerQuery = encodeURIComponent('<?php echo $searchList['initial_qer_query'];?>');
	var tsFinalQerQuery = encodeURIComponent('<?php echo $searchList['final_qer_query'];?>');
	var tsCallReturnFrom = encodeURIComponent('<?php echo $searchList['call_return_from'];?>');
	var search_unique_insert_id = -1;
	
	function initiateSearchTracking() {
		var urlParams = getCustomUrlVars();
		var keyword =  ((urlParams.keyword != undefined) ?  encodeURIComponent(urlParams.keyword) : "-1");
		var location = ((urlParams.location != undefined) ?  encodeURIComponent(urlParams.location) : "-1");
		var searchType = ((urlParams.searchType != undefined) ?  encodeURIComponent(urlParams.searchType) : "-1");
		
		if(keyword == "-1"){
			var keyw_ele = document.getElementById("tempkeyword");
			if(keyw_ele != undefined){
				keyword = keyw_ele.value;
			}
		}
		if(location == "-1"){
			var loc_ele = document.getElementById("templocation");
			if(loc_ele != undefined){
				location = loc_ele.value;
			}
		}
		if(searchType == "-1"){
			var searchtype_ele = document.getElementById("tempSearchType");
			if(searchtype_ele != undefined){
				searchType = searchtype_ele.value;
				if(searchType == "Institutes & Courses"){
					searchType = "course";
				} else if(searchType == "Ask & Answer"){
					searchType = "question";
				} else if(searchType == "Articles"){
					searchType = "blog";
				}
			}
		}
		
		var cType = ((urlParams.cType != undefined) ?  encodeURIComponent(urlParams.cType) : "-1");
		var cat_id = ((urlParams.subCategory != undefined) ?  encodeURIComponent(urlParams.subCategory) : "-1");
		var cityId = ((urlParams.cityId != undefined) ?  encodeURIComponent(urlParams.cityId) : "-1");
		var courseLevel = ((urlParams.courseLevel != undefined) ?  encodeURIComponent(urlParams.courseLevel) : "-1");
		
		var showCluster = ((urlParams.showCluster != undefined) ?  encodeURIComponent(urlParams.showCluster) : "-1");
		var countOffsetSearch = ((urlParams.countOffsetSearch != undefined) ?  encodeURIComponent(urlParams.countOffsetSearch) : "15");
		var startOffSetSearch = ((urlParams.startOffSetSearch != undefined) ?  encodeURIComponent(urlParams.startOffSetSearch) : "0");
		var countryId = ((urlParams.subLocation != undefined) ?  encodeURIComponent(urlParams.subLocation) : "-1");
		
		var autosuggestor_suggestion_shown = ((urlParams.autosuggestor_suggestion_shown != undefined) ?  encodeURIComponent(urlParams.autosuggestor_suggestion_shown) : "-1");
		// update the cookie value if needed
		writeTrackSearchQuery();
		// read the lastest track search cookie value
		var track_search_cookie_value = getCookie('tsr');
		var queryString = "keyword="+keyword+"&location="+location+"&cType="+cType+"&cat_id="+cat_id+"&cityId="+cityId+"&courseLevel="+courseLevel+"&searchType="+searchType+"&showCluster="+showCluster+"&countOffsetSearch="+countOffsetSearch+"&startOffSetSearch="+startOffSetSearch+"&countryId="+countryId+"&total_search_result_ids="+total_search_result_ids+"&total_search_rows="+total_search_rows+"&tsr="+track_search_cookie_value+"&tsZeroResultCase="+tsZeroResultCase+"&tsInitialQerQuery="+tsInitialQerQuery+"&tsFinalQerQuery="+tsFinalQerQuery+"&tsCallReturnFrom="+tsCallReturnFrom+"&autosuggestor_suggestion_shown="+autosuggestor_suggestion_shown;
		var trackURL = '/searchmatrix/SearchMatrix/trackSearchQuery';
		
		new Ajax.Request(trackURL,{method:'post', parameters: (queryString), onSuccess:function (response) {
			search_unique_insert_id = response.responseText;
		}});
		return false;
	}

	var redirectURLAfterTrackSearchQuery = '';
	function trackSearchQueries(uniqueRowId, uniqueSearchId, redirectURL, resultType, clickSection){
		if(redirectURL != 'javascript:void(0)'){
			clickedLinked = 'secondry';
			if(clickSection != undefined){
				clickedLinked = clickSection;
			}
			redirectURLAfterTrackSearchQuery = redirectURL;
			var trackURL = '/searchmatrix/SearchMatrix/updateLogResultClickedStatusById';
			var url = trackURL + '/' + uniqueRowId + '/' + uniqueSearchId + '/' + clickedLinked + '/' + search_unique_insert_id + '/' + resultType + '/';
			new Ajax.Request(url, { method:'get', onSuccess:handletrackSearchQueriesResponse});
			return false;	
		}
	}
	
	function handletrackSearchQueriesResponse(response){
		var url = redirectURLAfterTrackSearchQuery;
		redirectURLAfterTrackSearchQuery = undefined;
		setTimeout(function() {
			window.location = url;
		}, 0);
		return false;
	}
	
	function getCustomUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars;
	}
	
	function writeTrackSearchQuery(tsrValue) {
		var track_search_cookie_value = false;
		if(tsrValue != undefined){
			track_search_cookie_value = tsrValue;
		} else {
			var urlParams = getCustomUrlVars();
			var track_search_referrer =  ((urlParams.tsr != undefined) ?  (urlParams.tsr) : false);
			if(track_search_referrer){
				track_search_cookie_value = track_search_referrer;
			} else {
				//tsr param for tracking is not set, read the URL it might be static URL
				var window_url = window.location.href;
				var file_extension = getFileExtensionInUrl(window_url);
				if(file_extension == 'html'){
					// its a static page
					track_search_cookie_value = 'static';
				}
			}
		}
		
		if(track_search_cookie_value){
			setCookie('tsr', track_search_cookie_value);
		}
	}
	
	function getFileExtensionInUrl(url) {
		return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).substr(url.lastIndexOf(".")+1);
	}

</script>
