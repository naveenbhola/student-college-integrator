<?php if(!empty($data['linkedEntities']) && $type == 'question'){ ?>
<div class="related-col">
<div class="rel-card-heading"><h2>LINKED <?php echo strtoupper($type).'S';?></h2></div>
<?php foreach($data['linkedEntities'] as $key=>$linkedEntity){
        $viewCountText = ($linkedEntity['viewCount']== 1) ? 'View' : 'Views';
        $childCountText = ($linkedEntity['childCount']== 1) ? 'Answer' : 'Answers';
?>
        <a href="<?=$linkedEntity['url']?>" onclick="gaTrackEventCustom('QUESTION DETAIL PAGE','LINKED_QUEST_QUESTIONDETAIL_WEBAnA','<?=$GA_userLevel?>');"><?=$linkedEntity['title']?> <p><?php echo $linkedEntity['childCount'].' '.$childCountText;?>  <?php if($linkedEntity['viewCount'] > 0){ ?><span>|</span>
            <?php echo $linkedEntity['viewCount'].' '.$viewCountText; }?></p></a>
 <?php } ?>
</div>
<?php } ?>
<?php 
$relatedAlgoType = "RELATED_QUE_ALGO_1";
if(isset($data['algoType']) && $data['algoType'] == 2){
    $relatedAlgoType = "RELATED_QUE_ALGO_2";
}
?>
<input type="hidden" id="relatedAlgoType" value="<?php  //echo $relatedAlgoType; ?>"/>
<?php
if(!empty($data['related'])){ 

    ?>
    <div class="relatedqstn_v1">
    <div class="heading_v1"><h2>RELATED <?php echo strtoupper($type).'S';?></h2></div>
    <div class="related_contnt">
<?php foreach($data['related'] as $key=>$relatedEntity){
        $viewCountText = ($relatedEntity['viewCount']== 1) ? 'View' : 'Views';

        $positionClickTracking = "";
        if($type =='question' ){
            $GA_currentPage = 'QUESTION DETAIL PAGE';
            $GA_relatedEntity = 'RELATED_QUEST_QUESTIONDETAIL_WEBAnA';
            $positionClickTracking = "gaTrackEventCustom('".$relatedAlgoType."','Clicked','".($key+1)."');";
            if($relatedEntity['childCount'] > 1){
                $readMoreText = "Read All Answers";
            }else {
                $readMoreText = "Read Full Answer";
            }
        }else{
            $childCountText = ($relatedEntity['childCount']== 1) ? 'Comment' : 'Comments';
            $GA_currentPage = 'DISCUSSION DETAIL PAGE';
            $GA_relatedEntity = 'RELATED_DISC_DISCUSSIONDETAIL_WEBAnA';
        }
        
    ?>
    <div class="related_touples">
        <div class="rltd_heading">
            <a class="rltd_Q" href="<?=$relatedEntity['url']?>" onclick="gaTrackEventCustom('<?=$GA_currentPage?>','<?=$GA_relatedEntity?>','<?=$GA_userLevel?>');<?php //echo $positionClickTracking;?>"><?=$relatedEntity['title']?></a>
        </div>
        <?php
                if (array_key_exists('answer', $relatedEntity) && !empty($relatedEntity['answer'])){
        ?>
                    <div class="clearfix rltd_users">
                        <div class="user-i-card">S</div>
                        <div class="mt-inf-card">
                            <p class="rltd_label">Answered by</p>
                            <p class="u-name"><?=ucfirst($relatedEntity['answer']['firstName'])." ".ucfirst($relatedEntity['answer']['lastname'])?></p>
                            <p class="u-level"><?=ucfirst($relatedEntity['answer']['levelName'])?></p>
                        </div>
                    </div>
                    <div class="readAns">
                        <p class="readAns_Txt">
                            <?php
                            if (strlen($relatedEntity['answer']['msgTxt']) > 200) {
				    //echo htmlspecialchars(substr($relatedEntity['answer']['msgTxt'], 0, 197)."...");
				    echo getTextFromHtml($relatedEntity['answer']['msgTxt'], 197)."...";
                            }else {
                                echo htmlspecialchars($relatedEntity['answer']['msgTxt']);
                            }
                            ?>
                        </p>
                        <p class="readFullTxt"><a href="<?=$relatedEntity['url']."?referenceEntityId=".$relatedEntity['answer']['msgId']?>"><?=$readMoreText?></a>
                            <i class="rltd_i"></i>
                        </p>
                    </div>
        <?php   }
        ?>
    </div>
 <?php } ?>
    </div>
</div>
<?php } ?>   
