<?php //print_r($linkDiscussionViewCount);
if((count($linkDiscussionViewCount->msgTitle)>0)) { ?>
<!--Start_SimilarQuestion-->
<div class="lineSpace_10 clear_B">&nbsp;</div>
<div style="border-bottom:1px solid #eaeeed;"></div>
<div class="Fnt14 bld mt10"><?php echo count($linkDiscussionViewCount->msgTitle);?> <?php if(count($linkDiscussionViewCount->msgTitle)==1) {echo "discussion";}else {echo "discussions";}?></div>
         <div style="width:650px; height:150px;overflow:auto" class="shik_box3 mt10" id="containerForLatestUpdateWidget0" divnumber="0">
            <div style="width:100%; width:620px">
                    <?php for($count=0;$count<count($linkDiscussionViewCount->msgTitle);$count++) {	?>
                       <div id="id<?php echo $count;?>" onClick="selectedDiscussionId('<?php echo $linkDiscussionViewCount->tmplink[$count];?>','<?php echo $count;?>','<?php echo count($linkDiscussionViewCount->msgTitle);?>');" class="shikIcons home_dot" style="height:30px;overflow:hidden;margin:3px;"><a onClick="trackEventByGA('SEARCH_RESULTS','SHIKSHA_CAFE_INTERMEDIATE');" href="<?php echo $linkDiscussionViewCount->link[$count];?>" target= "_blank" class="bld" class="bld" title=""><?php echo $linkDiscussionViewCount->msgTitle[$count] ?></a><br/>
                            <span class="grayFont Fnt11">
                                        <?php
                                        $str= array();
                                        if($linkDiscussionViewCount->viewCount[$count]>0) {
                                            if($linkDiscussionViewCount->viewCount[$count] != 1) {
                                                $caption = "Views";
                                            }else {
                                                $caption = "View";
                                            }
                                            $str[] = $linkDiscussionViewCount->viewCount[$count]." ".$caption;
                                        }
                                        if($linkDiscussionViewCount->answers[$count]>0) {
                                            if($linkDiscussionViewCount->answers[$count] != 1) {
                                                $caption = "Answers";
                                            }else {
                                                $caption = "Answer";
                                            }
                                            $str[] = $linkDiscussionViewCount->answers[$count]." ".$caption;
                                        }
                                        if($linkDiscussionViewCount->comments[$count]>0) {
                                            if($linkDiscussionViewCount->comments[$count] != 1) {
                                                $caption = "Comments";
                                            }else {
                                                $caption = "Comment";
                                            }
                                            $str[] = $linkDiscussionViewCount->comments[$count]." ".$caption;
                                        }
                                        echo implode($str,' ,');
                                        ?>
                            </span>
                       </div>
                    
    <?php
    }	?>
                
            </div>
            &nbsp;</div>

                                <div class="lineSpace_10 clear_B">&nbsp;</div>

                                <div style="padding-top:10px"><input type="Submit" value="Redirect Discussion" onclick="addDiscussionToRelatedQuestionList(questionLinkedGoogleSearchId,document.getElementById('mainDiscussionIdPU').innerHTML,document.getElementById('userIdPU').innerHTML,document.getElementById('owernIdPU').innerHTML,document.getElementById('entityType').innerHTML)" class="fbBtn" id="submitButtonreportAbuse"> &nbsp; <a href="javascript:void(0);" onclick="hideOverlay();">Cancel</a></div>

                                <div class="lineSpace_10 clear_B">&nbsp;</div>
    
<?php
}else{
	echo '<div class="bld mt10">No Result found.</div>';
}
?>

