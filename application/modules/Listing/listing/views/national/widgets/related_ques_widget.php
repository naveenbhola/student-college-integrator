 <input id="institute_id" type="hidden" value="<?php echo $instituteId;?>">
 <input id="js_enabled" type="hidden" value="<?php echo $js_enabled;?>">

<div class="discuss-comnt-section" >

 <ul class="comment-block " id="rel_qna_div" style=" margin-left: 5px ;">    
 <?php if(!empty($data)):?>
 <?php echo $this->load->view('national/widgets/related_ques_widget_inner')?>
 <?php else :?>
 <?php echo "No Related Questions found!" ;?>
 
 <?php endif;?>
  </ul>
  <?php if($js_enabled && $total > 4):?>
            <a href="javascript:void(0)" onclick="loadMoreQues()" class="load-more clear-width" id="load_more">Load More Questions</a>
            <span class="load-more clear-width" id="load_more_span" style="display:none;">No more questions to show</span >
   <?php endif;?>        
</div>
