<div class="scrollbar1">
   <div class="scrollbar">
       <div class="track">
           <div id="thumbbranch" class="thumb"></div>
       </div>
   </div>
   <div class="viewport" style="height: 500px; width: 99%">
       <div class="overview">
         
<?php
$noResultFound = 0;
if(count($displayData) > 0){ ?>
      <?php
      $count = 0;
      foreach($displayData['msgData'] as $quesId=>$resultData){
      ?>
   <p>
   <?php if(!empty($resultData['answer']) && array_key_exists('answer', $resultData)) { $count++; ?>
   <a href="<?php echo getSeoUrl($quesId, 'question', $resultData['question']);?>"><?php echo $resultData['question'][0]; ?></a><br>
   <span><?php echo $resultData['ansCount'][0]; ?> answer(s), <?php echo $resultData['viewCount'][0] ; ?> View(s)</span><br>
   <span class="campus-answer" id="span1-<?=$quesId?>"><?php echo substr($resultData['answer'],0,140) ;  ?></span>
   <span class="campus-answer" id="span2-<?=$quesId?>" style="display:none;"><?php echo $resultData['answer'] ;  ?></span>
         <?php
         if(strlen($resultData['answer'])>140){ ?>
            <a class="sml" onclick="$j('#span1-<?=$quesId?>').hide();$j('#span2-<?=$quesId?>').show();$j(this).hide();  var scrollbar1 = $j('.scrollbar1').data('plugin_tinyscrollbar'); scrollbar1.update(scrollbar1.contentPosition);" href="javascript:void(0)">
            Read more</a>
         <?php } ?>
   </p>
      <?php
      if($count>=24){
            break;
      } ?>
    <?php }  ?>
   <?php }
   
   if($count == 0)
      $noResultFound  = 1;
   
   }
   else{
      $noResultFound = 1;
   }
   
   
if($noResultFound == 1) { ?>
  <p>
   <?php

   if($order == 'MostViewed')
   echo "No MostViewed Questions available";
   else if($order == 'Featured')
   echo "No Featured Questions available";
   else 
     echo "No Recent Questions available";
   
   
} ?>
    </p>     
         
       </div>
   </div>
</div>