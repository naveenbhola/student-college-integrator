 <section class="content-wrap clearfix" style="border-radius:0; box-shadow:none; margin:15px 0; background: #fff;">
        	  <div  id="quesTab">

		<header class="content-inner content-header clearfix">
            	<div style="z-index:9;" class="custome-dropdown" id="intermediateQuestionDropDownList">
		  <a href="javascript:void(0)" onClick="toggleCustomDropDown('intermediateQuestionDropDown');">
		    <div class="arrow"><i class="caret"></i></div>
                      <span class="display-area" id="display-area-inter"><?php echo empty($order)? 'Recent' : $order;?></span>
                  </a>
                        <div style="display:none;width: 96px;" id="intermediateQuestionDropDown" class="drop-layer" >
			<ul>
	    <li><a id="RecentQuestionsTab" href="javascript:void(0)" ques-type="Recent" onClick="showQuestions(this,'<?=$instituteId?>')">Recent</a></li>
	    <li><a id="FeaturedQuestionsTab" href="javascript:void(0)" ques-type="Featured" onClick="showQuestions(this,'<?=$instituteId?>')">Featured</a></li>
        <li><a id="MostViewedQuestionsTab" href="javascript:void(0)" ques-type="MostViewed" onClick="showQuestions(this,'<?=$instituteId?>')">Most Viewed</a></li>
			</ul>
		      </div>
                      </div>
                <h2 style="margin-right:98px; padding:7px 0 0; font-size:14px;" class="title-txt">
		  <?php if(empty($order) || $order == "Recent"){
			echo "Recent";?>
		  <?php
		    }
		    else if($order=='MostViewed'){
			  echo "Most Viewed"; }
		    else {
			  echo "Featured";
		  }?> Questions</h2>
            </header>
            
            
            <div class="campus-college-sub-container" id="questionTab">
<?php
  $noResultFound = 0;
    if(count($displayData) > 0){ ?>
	<?php
	$count = 0;
	foreach($displayData['msgData'] as $quesId=>$resultData){
?>
		      <?php if(!empty($resultData['answer']) && array_key_exists('answer', $resultData)) { $count++; ?>
			<ul>
              <li>
                  <p>
  
        <a href="<?php echo getSeoUrl($quesId, 'question', $resultData['question']);?>"><?php echo $resultData['question'][0]; ?></a><br>
                          <span><?php echo $resultData['ansCount'][0]; ?> answer(s), <?php echo $resultData['viewCount'][0] ; ?> View(s)</span><br>
			  <strong class="campus-answer" id="span1-<?=$quesId?>"><?php echo substr($resultData['answer'],0,80) ;  ?> </strong>
                          <strong class="campus-answer" id="span2-<?=$quesId?>" style="display:none;"><?php echo $resultData['answer'] ;  ?> </strong>
    <?php
         if(strlen($resultData['answer'])>80){ ?>
            <a class="sml" onclick="$('#span1-'+<?=$quesId?>).hide();$('#span2-'+<?=$quesId?>).show();$(this).hide();"  href="javascript:void(0)">
            Read more</a>
    <?php } ?>
  
		    </p>
                </li>
          </ul>
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
   echo "No Most Viewed Questions available";
   else if($order == 'Featured')
   echo "No Featured Questions available";
   else 
     echo "No Recent Questions available";
   
   
} ?>
    </p>     
 
</div>
		  </div>
        </section>
        
        