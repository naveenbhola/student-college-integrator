<?php
function getDisplayNameText($userId, $entityUserId, $displayName, $vcardStatus, $userName) {
    $userProfile = site_url('getUserProfile').'/';
    if($entityUserId==$userId) {
        $userDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$entityUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$displayName.'">'.$userName.'</a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>';
    }else {
        $userDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$entityUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$displayName.'">'.$userName.' </a></span>';
    }
    return $userDisplayText;
}

$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
$userImageURLDisplay = isset($validateuser[0]['avtarurl'])?$validateuser[0]['avtarurl']:'/public/images/photoNotAvailable.gif';
$iconCssClass = '';
if($tabselected==6) {
    $entityType = 'discussion';
    $displayStr = 'Discussion Topics';
    $iconCssClass = 'ana_disc_homepage';
}
else if($tabselected==7) {
        $entityType = 'announcement';
        $displayStr = 'Announcement Topics';
        $iconCssClass = 'ana_anno_homepage';
    }

$ajaxCall = "false";
if(isset($isAjax) && $isAjax=="1")
    $ajaxCall = "true";

//Code Start to show the Heading, Category-Country
if(($ajaxCall=="false")) {
    ?>
<div class="wdh100 h28 ana_DbgClr">
    <div class="float_L w49_per lh28">
        <span class="Fnt14 bld pl10"><?php echo $displayStr;?></span> &nbsp;
        <?php if(!$tab_required_course_page):?>
        <span id="categoryHeading" class="fontSize_12p "><a class="ana_ddwn pl10" onClick="showcommonOverlay(this,'parentcategory',true);return false;" href="javascript:void(0);"><?php echo $selectedCategoryName; if($selectedCategoryName == 'All') { echo " categories";} ?></a></span>
       <!---- <span class="fontSize_12p">&nbsp;&nbsp;-</span>-->
        <!---<span id="countryHeading" class="fontSize_12p "><a class="ana_ddwn pl10" onClick="showcommonOverlay(this,'country',true);return false;" href="javascript:void(0);"><?php //echo $selectedCountryName; if($selectedCountryName == 'All') { echo " countries";} ?></a></span>-->
        <?php endif;?>
    </div>
    <div class="float_R w49_per lh28">
        <span class="Fnt14 bld">Latest Post</span>
    </div>
</div>
<div class="clear_B">&nbsp;</div>
<?php
} //echo count($topicListings[0]['stickyArray']);
//Code end to show the Heading, Category-Country

$stickyDisplayArray=array();//print_r($topicListings[0]['stickyArray']);
for($i=0;$i<count($topicListings[0]['stickyArray']);$i++) {
    $stickyDisplayArray['stickythreadId'][$i]=$topicListings[0]['stickyArray'][$i]['stickythreadId'];
    $stickyDisplayArray[$topicListings[0]['stickyArray'][$i]['stickythreadId']]=$topicListings[0]['stickyArray'][$i]['status'];
}
//print_r($stickyDisplayArray);
if(is_array($topicListings)) {
    $levelVCard = isset($topicListings[0]['levelVCard'])?$topicListings[0]['levelVCard']:array();
    //Convert the Level array as per the User Id
    $levelVCardArray = array();
    if(is_array($levelVCard)) {
        for($i=0;$i<count($levelVCard);$i++) {
            $userIdTemp = $levelVCard[$i]['userid'];
            $levelVCardArray[$userIdTemp] = $levelVCard[$i];
        }
    }
    $msgData = $topicListings[0]['msgData'];
    //Convert the commentData array as per the mainAnswerId
    $commentData = $topicListings[0]['commentData'];
    $commentDataArray = array();
    if(is_array($commentData)) {
        for($i=0;$i<count($commentData);$i++) {
            $mainAnswerId = $commentData[$i]['mainAnswerId'];
            $commentDataArray[$mainAnswerId] = $commentData[$i];
        }
    }


    //In case of All categories, display the categories also
    $parentCategoryName = '';
    $parentCategoryId=0;
    //Show the total Count of topics in a category
    if($categoryId != '1' && ($ajaxCall=="false")) {
        echo "<div class='float_L' style='line-height:20px;margin-top:10px;'>";
        echo "<span class='Fnt14 bld' style='margin-top:5px;margin-left:10px;'>".$selectedCategoryName." : ".$selectedCountryName."</span>";
        $topicDisplayText = ($topicListings[0]['totalTopicCount']>1)?'Topics':'Topic';
        echo "&nbsp;<span class='Fnt11 fcdGya'>(<b>".$topicListings[0]['totalTopicCount']." $topicDisplayText</b>)</span>";
        echo "</div>";
        echo "<div id='topIdPage' class='float_R' style='margin-right:15px;margin-top:15px;'>".preg_replace('/default\/0\/60/','',$paginationHTML)."</div>";
        echo "<div class='clear_B'></div>";
        echo "<div class='ln_2'>&nbsp;</div>";
    }

    //Start the for loop
    for($i=0;$i<count($msgData);$i++) {

    //SHow the More link at the end of Category Posts
        if($categoryId=='1' && $parentCategoryName!=$msgData[$i]['name'] && $parentCategoryName!='' && ($ajaxCall=="false")) {
            $categoryURL1 = str_replace('@cat@',$parentCategoryId,$categoryURL);
            if($msgData[$i-1]['totalCount']>5) {
            //echo "<div><a href='".$categoryURL1."'>More</a></div>";
                echo "<div class='ln_2'>&nbsp;</div>";
                echo "<div id='displayMoreTopics".$parentCategoryId."' style='display:none'></div>";
                echo "<div id='moreLink".$parentCategoryId."' class='mt10'>
		  <a class='ana_sprt ana_ar' href='javascript:void(0);' onClick='showMoreTopicsHomepage(\"".$entityType."\",\"".$parentCategoryId."\",\"".$countryId."\",\"".$msgData[$i-1]['totalCount']."\",\"".$categoryURL1."\")'>More</a>
		  &nbsp;<span style='display:none' id='moreTopicWait".$parentCategoryId."'><img src='/public/images/working.gif' align='absmiddle'/></span>
		  </div>";
            }
            echo "<div class='ana_disSep'>&nbsp;</div>";
            echo "</div>";	//Close the main div of the Category
        }else if($i>0) {
                echo "<div class='ln_2'>&nbsp;</div>";
            }
        //Show the Category Name and no of topics at the start of Category Posts
        if($categoryId=='1' && $parentCategoryName!=$msgData[$i]['name'] && ($ajaxCall=="false")) {
            $categoryURL1 = str_replace('@cat@',$msgData[$i]['categoryId'],$categoryURL);
            $topicDisplay = ($msgData[$i]['totalCount']>1)?'Topics':'Topic';
            if($selectedCountryName == 'All') { $showCat = " countries";}
            echo "<div class='lh28 pl10'>
		<img align='absmiddle' style='cursor: pointer; margin-top: -8px;' onclick='showHideTopicContainer(".$msgData[$i]['categoryId'].")' src='/public/images/closedocument.gif' id='3Toggler".$msgData[$i]['categoryId']."'> 
		<h3 class='Fnt14 bld' style='color:#000;text-decoration:none;padding-left:5px'>".$msgData[$i]['name']." : $selectedCountryName $showCat</h3> 
		<a href='".$categoryURL1."' class='Fnt11'>(".$msgData[$i]['totalCount']." ".$topicDisplay.")</a>
		</div>";
            echo "<div id='topicsContainer".$msgData[$i]['categoryId']."' style='display:block'>";	//Start the main div of the Category
            echo "<div class='ln_2' style='margin-top:0'>&nbsp;</div>";
        }
        $parentCategoryName = $msgData[$i]['name'];
        $parentCategoryId = $msgData[$i]['categoryId'];
        $commentDisplay = ($msgData[$i]['commentCount']>1)?'Comments':'Comment';
        //$postUserLevel = getTheRatingStar($levelVCardArray[$msgData[$i]['userId']]['ownerLevelP']);
        $postUserLevel = '';
        $postUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$postUserLevel.'</a></span>';
        $postUserDisplayText = getDisplayNameText($userId, $msgData[$i]['userId'], $msgData[$i]['displayname'], $levelVCardArray[$msgData[$i]['userId']]['vcardStatus'], $msgData[$i]['firstname'].' '.$msgData[$i]['lastname']);
        ?>

<!-- Code Start for displaying the Post details -->
<div class="float_L w49_per">
    <div class="ml10 <?php echo $iconCssClass;?> lineSpace_16">
        <a href="<?php echo $msgData[$i]['url'];?>" class="bld">
        <?php
                    $discussionTopicText = html_entity_decode(html_entity_decode($msgData[$i]['msgTxt'],ENT_NOQUOTES,'UTF-8'));
                    echo formatDiscussionforList($discussionTopicText,300);?>
        </a>
        <?php if($msgData[$i]['commentCount']>0) { ?>
        <span class="ana_sprt ana_sblg ml10">&nbsp;</span><span><?php echo $msgData[$i]['commentCount']." ".$commentDisplay;?></span>
        <?php } ?>
        <br />
        <span class="fcdGya" style="margin-top:10px;">Started by <?php echo $postUserDisplayText;?><?php echo $postUserLevelText;?>&nbsp;
            <span class="Fnt11 fcdGya">on <?php echo date('M j, Y, h:i A',strtotime($msgData[$i]['creationDate']));?></span>
            <br />
            <!-- Code Start for displaying the Page links -->
        <?php if($msgData[$i]['commentCount']>20) {
                        $x=1;
                        $shown = false;
                        ?>
            <span class="fcdGya">Page
            <?php for($j=0;$j<$msgData[$i]['commentCount'];$j+=20) {
                                if($x>=4 && $msgData[$i]['commentCount']>80 && ($msgData[$i]['commentCount']-$j>=20)) {
                                    if($shown===false) {
                                        echo "...";
                                    }
                                    $shown = true;
                                }else {
                                    ?>
                <a href="<?php echo $msgData[$i]['url'];?>-all-askHome-all-all-<?php echo $j;?>-20"><?php echo $x;?></a>
                <?php }
                                $x++;} ?>
            </span>
        <?php } ?>
            <!-- Code End for displaying the Page links -->
	</span>
    </div>
</div>
<!-- Code End for displaying the Post details -->


        <?php
        //Code Start for displaying the Comment details
        if(is_array($commentDataArray[$msgData[$i]['msgId']]) && (count($commentDataArray[$msgData[$i]['msgId']])>0)) {
            $userIdTemp = $commentDataArray[$msgData[$i]['msgId']]['userId'];
            //$commentUserLevel = getTheRatingStar($levelVCardArray[$userIdTemp]['ownerLevelP']);
            $commentUserLevel = '';
            $commentUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$commentUserLevel.'</a></span>';
            $commentUserDisplayText = getDisplayNameText($userId, $commentDataArray[$msgData[$i]['msgId']]['userId'], $commentDataArray[$msgData[$i]['msgId']]['displayname'], $levelVCardArray[$userIdTemp]['vcardStatus'], $commentDataArray[$msgData[$i]['msgId']]['firstname'].' '.$commentDataArray[$msgData[$i]['msgId']]['lastname']);
            ?>
<div class="float_R w49_per">
    <div> <?php if($ACLStatus['MakeStickyDiscussion']=='live' || $ACLStatus['RemoveStickyDiscussion']=='live' || $ACLStatus['MakeStickyAnnouncement']=='live' || $ACLStatus['RemoveStickyAnnouncement']=='live'):?>
            <?php if(in_array($commentDataArray[$msgData[$i]['msgId']]['threadId'],$stickyDisplayArray['stickythreadId']) && $stickyDisplayArray[$commentDataArray[$msgData[$i]['msgId']]['threadId']]=='live') {?>
        <div id="sticky<?php echo $commentDataArray[$msgData[$i]['msgId']]['msgId'];?>"><div class="float_L w48"><a href="javascript:void(0);" onclick="makeStickyDiscussionAnnouncement('<?php echo $userId;?>','<?php echo $commentDataArray[$msgData[$i]['msgId']]['msgId'];?>','<?php echo $commentDataArray[$msgData[$i]['msgId']]['threadId'];?>','<?php if($parentCategoryId=='') echo $categoryId; else echo $parentCategoryId;?>','<?php echo $entityType;?>','unsticky');"><img border="0" src="/public/images/green_sticky.gif" title="Sticky"/></a></div></div>
            <?php  }else if(in_array($commentDataArray[$msgData[$i]['msgId']]['threadId'],$stickyDisplayArray['stickythreadId']) && $stickyDisplayArray[$commentDataArray[$msgData[$i]['msgId']]['threadId']]=='deleted') { ?>
        <div id="unsticky<?php echo $commentDataArray[$msgData[$i]['msgId']]['msgId'];?>"><div class="float_L w48"><a href="javascript:void(0);" onclick="makeStickyDiscussionAnnouncement('<?php echo $userId;?>','<?php echo $commentDataArray[$msgData[$i]['msgId']]['msgId'];?>','<?php echo $commentDataArray[$msgData[$i]['msgId']]['threadId'];?>','<?php if($parentCategoryId=='') echo $categoryId; else echo $parentCategoryId;?>','<?php echo $entityType;?>','sticky');"><img border="0" src="/public/images/grey_sticky.gif" title="UnSticky"/></a></div></div>
                <?php }else { ?>
        <div id="unsticky<?php echo $commentDataArray[$msgData[$i]['msgId']]['msgId'];?>"><div class="float_L w48"><a href="javascript:void(0);" onclick="makeStickyDiscussionAnnouncement('<?php echo $userId;?>','<?php echo $commentDataArray[$msgData[$i]['msgId']]['msgId'];?>','<?php echo $commentDataArray[$msgData[$i]['msgId']]['threadId'];?>','<?php if($parentCategoryId=='') echo $categoryId; else echo $parentCategoryId;?>','<?php echo $entityType;?>','sticky');"><img border="0" src="/public/images/grey_sticky.gif" title="UnSticky"/></a></div></div>
                <?php }?>
        <?php endif;?>
        <div class="float_L w48"><img src="<?php if($commentDataArray[$msgData[$i]['msgId']]['avtarimageurl']=='') echo getTinyImage('/public/images/photoNotAvailable.gif'); else  echo getTinyImage($commentDataArray[$msgData[$i]['msgId']]['avtarimageurl']);?>" /></div>
        <div class="ml49">
            <span class="Fnt11">
            <?php
                            $discussionText = html_entity_decode(html_entity_decode($commentDataArray[$msgData[$i]['msgId']]['msgTxt'],ENT_NOQUOTES,'UTF-8'));
                            if(strlen($commentDataArray[$msgData[$i]['msgId']]['msgTxt'])<=125) { ?>
                <div><?php echo $commentUserDisplayText;?><?php echo $commentUserLevelText;?> <?php echo formatQNAforQuestionDetailPage($discussionText,300);?></div>
            <?php }else { ?>
                <div>
                <?php echo $commentUserDisplayText;?>
                                    <?php echo $commentUserLevelText;?>
                                    <?php echo formatQNAforQuestionDetailPage(substr($discussionText, 0, 122),300);?>
				  ...<a href="<?php echo $msgData[$i]['url'];?>-all-askHome-all-all-<?php echo (ceil($msgData[$i]['commentCount']/20)-1)*20;?>-20">view</a>
                </div>
            <?php } ?>
            </span>
            <br />
            <div class="fcdGya Fnt11 pt3"><?php echo makeRelativeTime($commentDataArray[$msgData[$i]['msgId']]['creationDate']);?></div>
        </div>
        <div class="clear_B">&nbsp;</div>
    </div>
</div>
        <?php
        }
        //Code End for displaying the Comment details
        //Code Start for displaying the Discussion description in case no comments are available on the post
        else {
            ?>
<div class="float_R w49_per">
    <div> <?php if($ACLStatus['MakeStickyDiscussion']=='live' || $ACLStatus['RemoveStickyDiscussion']=='live' || $ACLStatus['MakeStickyAnnouncement']=='live' || $ACLStatus['RemoveStickyAnnouncement']=='live'):?>
            <?php if(in_array($msgData[$i]['threadId'],$stickyDisplayArray['stickythreadId']) && $stickyDisplayArray[$msgData[$i]['threadId']]=='live') {?>
        <div id="sticky<?php echo $msgData[$i]['msgId'];?>"><div class="float_L w48"><a href="javascript:void(0);" onclick="makeStickyDiscussionAnnouncement('<?php echo $userId;?>','<?php echo $msgData[$i]['msgId'];?>','<?php echo $msgData[$i]['threadId'];?>','<?php if($parentCategoryId=='') echo $categoryId; else echo $parentCategoryId;?>','<?php echo $entityType;?>','unsticky');"><img border="0" src="/public/images/green_sticky.gif" title="Sticky"/></a></div></div>
            <?php }else if(in_array($msgData[$i]['threadId'],$stickyDisplayArray['stickythreadId']) && $stickyDisplayArray[$msgData[$i]['threadId']]=='deleted') { ?>
        <div id="unsticky<?php echo $msgData[$i]['msgId'];?>"><div class="float_L w48"><a href="javascript:void(0);" onclick="makeStickyDiscussionAnnouncement('<?php echo $userId;?>','<?php echo $msgData[$i]['msgId'];?>','<?php echo $msgData[$i]['threadId'];?>','<?php if($parentCategoryId=='') echo $categoryId; else echo $parentCategoryId;?>','<?php echo $entityType;?>','sticky');"><img border="0" src="/public/images/grey_sticky.gif" title="UnSticky"/></a></div></div>
                <?php }else { ?>
        <div id="unsticky<?php echo $msgData[$i]['msgId'];?>"><div class="float_L w48"><a href="javascript:void(0);" onclick="makeStickyDiscussionAnnouncement('<?php echo $userId;?>','<?php echo $msgData[$i]['msgId'];?>','<?php echo $msgData[$i]['threadId'];?>','<?php if($parentCategoryId=='') echo $categoryId; else echo $parentCategoryId;?>','<?php echo $entityType;?>','sticky');"><img border="0" src="/public/images/grey_sticky.gif" title="UnSticky"/></a></div></div>
                <?php } ?>
        <?php endif;?>
        <div class="float_L w48"><img src="<?php if($msgData[$i]['avtarimageurl']=='') echo getTinyImage('/public/images/photoNotAvailable.gif'); else echo getTinyImage($msgData[$i]['avtarimageurl']);?>" /></div>
        <div class="ml49">
            <span class="Fnt11">
            <?php
                            $discussionDescText = html_entity_decode(html_entity_decode($msgData[$i]['description'],ENT_NOQUOTES,'UTF-8'));
                            if(strlen($msgData[$i]['description'])<=125) { ?>
                <div><?php echo $postUserDisplayText;?><?php echo $postUserLevelText;?> <?php echo formatQNAforQuestionDetailPage($discussionDescText,300);?></div>
            <?php }else { ?>
                <div>
                <?php echo $postUserDisplayText;?><?php echo $postUserLevelText;?>
                                    <?php echo formatQNAforQuestionDetailPage(substr($discussionDescText, 0, 122),300);?>
				  ...<a href="<?php echo $msgData[$i]['url'];?>">view</a>
                </div>
            <?php } ?>
            </span>
            <br />
            <div class="fcdGya Fnt11 pt3"><?php echo makeRelativeTime($msgData[$i]['creationDate']);?></div>
        </div>
        <div class="clear_B">&nbsp;</div>
    </div>
</div>
        <?php
        }
        //Code End for displaying the Discussion description in case no comments are available on the post
        ?>
<div class="clear_B">&nbsp;</div>

    <?php
    }
    //End the for loop

    if($categoryId=='1' && $parentCategoryName!=$msgData[$i]['name'] && $parentCategoryName!='' && ($ajaxCall=="false")) {
        $categoryURL1 = str_replace('@cat@',$parentCategoryId,$categoryURL);
        if($msgData[$i-1]['totalCount']>5) {
        //echo "<div><a href='".$categoryURL1."'>More</a></div>";
            echo "<div class='ln_2'>&nbsp;</div>";
            echo "<div id='displayMoreTopics".$parentCategoryId."' style='display:none'></div>";
            echo "<div id='moreLink".$parentCategoryId."' class='mt10'>
		  <a class='ana_sprt ana_ar' href='javascript:void(0);' onClick='showMoreTopicsHomepage(\"".$entityType."\",\"".$parentCategoryId."\",\"".$countryId."\",\"".$msgData[$i-1]['totalCount']."\",\"".$categoryURL1."\")'>More</a>
		  &nbsp;<span style='display:none' id='moreTopicWait".$parentCategoryId."'><img src='/public/images/working.gif' align='absmiddle'/></span>
		  </div>";
        }
        echo "<div class='ana_disSep'>&nbsp;</div>";
        echo "</div>";	//Close the main div of the Category
    }

    if($categoryId!='1' && ($ajaxCall=="false")) {
        echo "<div class='ana_disSep'>&nbsp;</div>";
        echo "<div id='topIdPage' class='float_R' style='margin-right:15px;margin-top:15px;'>".preg_replace('/default\/0\/60/','',$paginationHTML)."</div>";
        echo "<div class='clear_B'></div>";
    }

}
?>
