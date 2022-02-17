<?php if(isset($similarQuestions['resultList']) && (count($similarQuestions['resultList']) > 0)): ?>
<div>
    <div class="raised_pinkWhite ">
        <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
        <div class="boxcontent_pinkWhite">
            <div>


                <div class="normaltxt_11p_blk mar_full_10p">
                    <div class="OrgangeFont fontSize_13p bld" style="margin-right:110px"><h2><span class="cssSprite_Icons" style="background-position:-368px 0;padding-left:22px;font-size:16px">&nbsp;</span><span class="myHeadingControl">Related Questions on Shiksha</span></h2></div>
                   <!-- <div class="clear_R"></div>-->
                </div>
                <div class="lineSpace_10">&nbsp;</div>

<?php
			$i = 0;
			foreach($similarQuestions['resultList'] as $questionArray){
				$questionArray = $questionArray;
				$smallTitle = $questionArray['title'];
				if($topicId == $questionArray['typeId'])
					continue;
				if($i>=5)
					continue;
				$i++;
?>

                <div style="margin:0 5px; padding:10px 10px;">
                 <div class="qmarked" style="padding-bottom:20px;">
                    <div class="fontSize_12p" title="<?php echo $questionArray['title']; ?>">
                      <?php $quesLength = strlen($smallTitle);
                      if($quesLength<=300){
                             echo $smallTitle;
                            } else {
                             echo substr($smallTitle, 0, 297);
                             echo "<span id='relatedQuesDiv".$questionArray['typeId']."'>&nbsp;<FONT COLOR='#000000'>...</FONT> <a href='javascript:void(0);' id='relatedQuesHyperDiv".$questionArray['typeId']."' onClick='showCompleteAnswer(".$questionArray['typeId'].");'>more</a></span>";
                             echo "<span id='completeRelatedQuesDiv".$questionArray['typeId']."' style='display:none;'>".substr($smallTitle, 297, $quesLength)."</span>";
                             }
                       ?>
                   </div>
                   <div class="lineSpace_5">&nbsp;</div>
                   <?php if(isset($questionArray['category']) && $questionArray['category']!=''){ ?>
		      <div class="float_L">in <a href="/messageBoard/MsgBoard/discussionHome/<?php echo $questionArray['categoryId']; ?>/1/<?php echo $questionArray['countryId'];?>"><?php echo $questionArray['category']." - ".$questionArray['country']; ?></a></div>
                   <?php } ?>
                     <div class="float_R" valign="middle"><span align="absmiddle" class="vAns">&nbsp;<a href="<?php echo $questionArray['url']; ?>"><?php echo $questionArray['noOfComments']; ?>&nbsp;<?php if($questionArray['noOfComments']>1)echo "Answers"; else echo "Answer";?></a></span></div>

                  </div><div class="lineSpace_5">&nbsp;</div>
		  <div class="grayLine_1" style="width:100%;">&nbsp;</div>
                </div>
            <?php } ?>
            </div>
        </div>
        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
    </div>  
</div>
<?php endif; ?>