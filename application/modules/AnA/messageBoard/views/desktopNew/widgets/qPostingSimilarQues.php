<?php

if(!empty($similarQuestionsData)){
?>
<div class="similar-qstns" id="similar-qstns">
<div class="similar-col">
 <p class="txt-p">Check out similar Questions</p>
            <div class="similar-col-inner">
                <ul>
                    <?php
                    foreach ($similarQuestionsData as $key => $value) {
                    ?>   
                    <li>
                        <a href="<?=$value['url'];?>" target="_blank" onclick="gaTrackEventCustom('QUESTION POSTING','SIMILARQUEST_QUESTIONPOSTING_DESKAnA','<?php echo $GA_userLevel;?>');"><?=$value['questionTitle'];?></a><span><?php echo $value['answerCount']." Answer"; 
                        if($value['answerCount'] > 1) echo "s";
                        ?></span>
                    </li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
</div>
</div>
<?php
}
?>