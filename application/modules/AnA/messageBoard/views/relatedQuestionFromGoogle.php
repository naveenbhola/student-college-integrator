<?php //$ACLStatus
$relatedQuestionCount = 10 - count($linkQuestionViewCount->link);
//echo "<pre>";print_r($googleRes['0']);echo "</pre>";
if(!empty($googleRes['title'])&&(isset($googleRes['title']['1'])) || count($linkQuestionViewCount->link)>0){
$result = $googleRes;
$countOfRelatedQuestions=0;
//echo "<pre>";print_r($result);echo "</pre>";
?>
<div class="related-ques-box">
<h5>Related Questions</h5>
<!--LoopStart-->
<ul>
<?php for($count1=0;$count1<count($linkQuestionViewCount->link);$count1++){
if((isset($linkQuestionViewCount->msgTitle[$count1]))&&(!empty($linkQuestionViewCount->msgTitle[$count1]))){ $countOfRelatedQuestions++;?>
<?php
$questionTitleTmp =  preg_replace('/(Ask Questions on various .*) | (Ask <br>  Questions on various .*) /','',$linkQuestionViewCount->msgTitle[$count1]);
$lenOfQuestionTitle = strlen(trim(preg_replace('(\s\s+)','',trim(strip_tags($questionTitleTmp)))));
$questionTitle = trim(preg_replace('(\s\s+)','',trim(strip_tags($questionTitleTmp))));
$tmpLink = explode('/',$linkQuestionViewCount->link[$count1]);
$linkId  = $tmpLink[4];?>

<li id="relatedQuestion<?php echo $countOfRelatedQuestions;?>" <?php if($countOfRelatedQuestions>4){?> style="display:none;" <?php } ?>>

<span id="partialRelatedQuestionId<?php echo $linkId;?>">
	<a href="<?php echo $linkQuestionViewCount->link[$count1]?>" target="_blank" >
	<?php if($lenOfQuestionTitle >140){ echo substr($questionTitle,0,137); ?>
    </a>
    <a href="javascript:void(0)" onClick="showFullRelatedQuestionAnA('<?php echo $linkId;?>');"><strong>...more</strong></a>
	<?php }else echo $questionTitle."</a>"; ?>
 &nbsp; <?php if($ACLStatus['DelinkQuestion']=='live' || $userGroup=='cms'):?>
    <a href="javascript:void(0);" onClick="unlinkedQuestion('<?php echo $topicId;?>','<?php echo $linkQuestionViewCount->linkedQuestionId[$count1];?>');">Unlink</a>
    <?php endif;?>
</span>
<span id="actualRelatedQuestionId<?php echo $linkId;?>" style="display:none;">
<a href="<?php echo $linkQuestionViewCount->link[$count1];?>#RelQues" target="_blank" ><?php echo $questionTitle; ?></a>
  &nbsp;<?php if($ACLStatus['DelinkQuestion']=='live' || $userGroup=='cms'):?>
    <a href="javascript:void(0);" onClick="unlinkedQuestion('<?php echo $topicId;?>','<?php echo $linkQuestionViewCount->linkedQuestionId[$count1];?>');">Unlink</a>
    <?php endif;?>
</span>

  



<div class="fcGya font-11">
    <!--<span id="relatedQuestionCreationDate<?php echo $countOfRelatedQuestions;?>" <?php if($countOfRelatedQuestions>4){?> style="display:none;" <?php } ?>><?php if(!empty($linkQuestionViewCount->creationDate[$count1]))echo $linkQuestionViewCount->creationDate[$count1]?> </span>
    <span  id="relatedQuestionCategoryCountry<?php echo $countOfRelatedQuestions;?>" <?php if($countOfRelatedQuestions>4){?> style="display:none;" <?php } ?>><?php if(!empty($linkQuestionViewCount->categoryCountry[$count1])) echo " in ".$linkQuestionViewCount->categoryCountry[$count1]?>  </span>-->
    <span id="relatedQuestionViewsAnsCommentCount<?php echo $countOfRelatedQuestions;?>"<?php if($countOfRelatedQuestions>4){?> style="display:none;" <?php } ?>>
<?php
                                                                        $str= array();
                                                                        /*if(!empty($linkQuestionViewCount->viewCount[$count1]))
                                                                        if($linkQuestionViewCount->viewCount[$count1]>0){
                                                                            if($linkQuestionViewCount->viewCount[$count1] != 1){
                                                                                    $caption = " Views";
                                                                            }else{
                                                                                $caption = " View";
                                                                            }
                                                                            $str[] = $linkQuestionViewCount->viewCount[$count1].$caption;
                                                                        }*/
                                                                        if(!empty($linkQuestionViewCount->answers[$count1])){
                                                                        if($linkQuestionViewCount->answers[$count1]>0){
                                                                            if($linkQuestionViewCountanswers[$count1] != 1){
                                                                                    $caption = " Answers";
                                                                            }else{
                                                                                $caption = " Answer";
                                                                            }
                                                                            $str[] = $linkQuestionViewCount->answers[$count1].$caption;
                                                                        }}
                                                                        if(!empty($linkQuestionViewCount->comments[$count1])){
                                                                        if($linkQuestionViewCount->comments[$count1]>0){
                                                                            if($linkQuestionViewCount->comments[$count1] != 1){
                                                                                    $caption = " Comments";
                                                                            }else{
                                                                                $caption = " Comment";
                                                                            }
                                                                            $str[] = $linkQuestionViewCount->comments[$count1].$caption;
                                                                        }}
                                                                        if(!empty($str))
                                                                        echo implode($str,' , ');
 ?>
    </span>
</div>
</li>
<?php
}
}
?>


<?php for($count=$count1;$count<$result['title'] && $count<=$relatedQuestionCount;$count++){
if((isset($result['title'][$count]))&&(!empty($result['title'][$count]))){
$countOfRelatedQuestions++;
$questionTitleTmp =  preg_replace('/(Ask Questions on various .*) | (Ask <br>  Questions on various .*) /','',$result['description'][$count]);

$lenOfQuestionTitle = strlen(trim(preg_replace('(\s\s+)','',trim(strip_tags($questionTitleTmp)))));
$questionTitle = trim(preg_replace('(\s\s+)','',trim(strip_tags($questionTitleTmp))));
$tmpLink = explode('/',$result['link'][$count]);
$linkId  = $tmpLink[4];?>
<li id="relatedQuestion<?php echo $countOfRelatedQuestions;?>" <?php if($countOfRelatedQuestions>4){?> style="display:none;" <?php } ?>>
<span id="partialRelatedQuestionId<?php echo $linkId;?>">
<a href="<?php echo $result['link'][$count];?>" target="_blank" ><?php if($lenOfQuestionTitle >140){ echo trim(substr($questionTitle,0,137)); ?></a>

<a href="javascript:void(0)" onClick="showFullRelatedQuestionAnA('<?php echo $linkId;?>');"><strong>...more</strong></a><?php }else echo $questionTitle."</a>"; ?>
</span>
<span id="actualRelatedQuestionId<?php echo $linkId;?>" style="display:none;">
<a href="<?php echo $result['link'][$count];?>#RelQues" target="_blank" ><?php echo $questionTitle; ?></a>
</span>

<div class="fcGya font-11">
    <!--<span id="relatedQuestionCreationDate<?php echo $countOfRelatedQuestions;?>" <?php if($countOfRelatedQuestions>4){?> style="display:none;" <?php } ?>><?php if(!empty($result['creationDate'][$count]))echo $result['creationDate'][$count]?> </span>
    <span id="relatedQuestionCategoryCountry<?php echo $countOfRelatedQuestions;?>" <?php if($countOfRelatedQuestions>4){?> style="display:none;" <?php } ?>><?php if(!empty($result['categoryCountry'][$count])) echo " in ".$result['categoryCountry'][$count]?>  </span>-->
    <span id="relatedQuestionViewsAnsCommentCount<?php echo $countOfRelatedQuestions;?>"<?php if($countOfRelatedQuestions>4){?> style="display:none;" <?php } ?>>
<?php
                                                                        $str= array();
                                                                        /*if(!empty($result['viewCount'][$count]))
                                                                        if($result['viewCount'][$count]>0){
                                                                            if($result['viewCount'][$count] != 1){
                                                                                    $caption = " Views";
                                                                            }else{
                                                                                $caption = " View";
                                                                            }
                                                                            $str[] = $result['viewCount'][$count].$caption;
                                                                        }*/
                                                                        if(!empty($result['answers'][$count])){
                                                                        if($result['answers'][$count]>0){
                                                                            if($result['answers'][$count] != 1){
                                                                                    $caption = " Answers";
                                                                            }else{
                                                                                $caption = " Answer";
                                                                            }
                                                                            $str[] = $result['answers'][$count].$caption;
                                                                        }}
                                                                        if(!empty($result['comments'][$count])){
                                                                        if($result['comments'][$count]>0){
                                                                            if($result['comments'][$count] != 1){
                                                                                    $caption = " Comments";
                                                                            }else{
                                                                                $caption = " Comment";
                                                                            }
                                                                            $str[] = $result['comments'][$count].$caption;
                                                                        }}
                                                                        if(!empty($str))
                                                                        echo implode($str,' , ');
                                                                        ?>
    </span>
</div>
</li>
<?php }}?>
</ul>
<!--LoopEnd-->

<span id="viewRelatedQLink">
<?php if($countOfRelatedQuestions>4){?> <a href="javascript:void(0);" onClick="showRelatedQUestion('<?php echo $countOfRelatedQuestions;?>');" class='view-related-link'>View more related questions</a> 
<?php }?>
    </span>
    </div><?php  }?>
    
    <script>
       window.onload=function(){trackEventByGA('SEARCH_RESULTS','SHIKSHA_RELATED_QUESTION_SOURCE_GOOGLE_SEARCH');};
    function showRelatedQUestion(numberOfRelatedQuestion){
    for(i=numberOfRelatedQuestion;i>4;i--){
$('relatedQuestion'+i).style.display='';
if($('relatedQuestionCreationDate'+i)){$('relatedQuestionCreationDate'+i).style.display=''};
//$('relatedQuestionCategoryCountry'+i).style.display='';
$('relatedQuestionViewsAnsCommentCount'+i).style.display='';
if($('relatedQuestionBestAnswer'+i) )$('relatedQuestionBestAnswer'+i).style.display='';
$('viewRelatedQLink').innerHTML='';
}
}
</script>
