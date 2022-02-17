<?php 

if(!empty($similarQuestionsData)){
   ?>
      <div class="qstn-heading">
              <h2>SIMILAR QUESTIONS</h2>
</div>
<!--card list-->  
<div class="qstn-cards-list">

<?php
foreach ($similarQuestionsData as $key => $value) {
   ?>   
      <a href="<?=$value['url'].'?showHam'?>" target="_blank" class="qstn-cards" onclick="sendGAWebAnA('QUESTION POSTING','SIMILARQUEST_QUESTIONPOSTING_WEBAnA')">
            <p class="ask-q-ana"><?=$value['questionTitle'];?></p>
            <p class="ans-count">
            <?php
               echo $value['answerCount']." Answer";
               if($value['answerCount'] > 1) echo "s";
            ?> </p>
      </a>
      
   <?php
}
?>

</div>
   <?php
}
?>



