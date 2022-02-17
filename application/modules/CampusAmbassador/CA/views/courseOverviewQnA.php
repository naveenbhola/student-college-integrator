<div class="discuss-comnt-section" >
 <input id="institute_id" type="hidden" value="<?php echo $instituteId;?>">
 <input id="course_id" type="hidden" value="<?php echo $courseId;?>">
 <input id="js_enabled" type="hidden" value="<?php echo $js_enabled;?>">
 <input id="study_india" type="hidden" value="<?php echo $studyIndia;?>">
 
 <?php if($studyIndia && ($campusRepExists=='true' || (isset($_GET['link_id']) && $_GET['link_id']!=''))):?>    
     <div class="cmnt-title" id="questionListOnPage">
     	<?php $unansweredText = ($questionType == 'Unanswered')?$questionType:'';?>
    	<span class="flLt"><?php echo $total;?> <?php echo ($total > 1)?$unansweredText.' Questions':$unansweredText.' Question'?>
    	<?php if(!empty($links) && $campusRepExists=='true'):?>
    	<a href="javascript:void(0);" onclick="setLinkCookie('All')"> (See All)</a>
    	<?php endif;?>
    	</span>
        <p class="flRt view-filter">
        View: 
<?php if($questionType == "All"){ ?>
<strong style="color:#666">All</strong><span class="pipe">|</span> <a href="javascript:void(0)" style="font-weight:<?php echo ($questionType == "Unanswered")?'bold':'normal';?>" onclick="filterQuestions('Unanswered','<?php echo $_GET['link_id']?>')">Unanswered</a>

<?php } ?>
<?php if($questionType == "Unanswered"){?>
<a href="javascript:void(0)" style="font-weight:<?php echo ($questionType == "All")?'bold':'normal';?>" class="" onclick="filterQuestions('All','<?php echo $_GET['link_id']?>')">All</a> <span class="pipe">|</span> <strong style="color:#666">Unanswered</strong>
<?php } ?>
        </p>
    </div>  
<?php endif;?>    
 <ul class="comment-block " id="qna_div">    
 <?php echo $this->load->view('CA/courseOverviewInner')?>
  </ul>
  <?php if($js_enabled && $total > 10):?>
            <a href="javascript:void(0)" onclick="loadMoreQnAOverview()" class="load-more clear-width" id="load_more">Show more questions</a>
            <span class="load-more clear-width" id="load_more_span" style="display:none;">No more questions to show</span >
   <?php endif;?>         
            
 </div>

 <?php if($studyIndia):?>
         <?php
                if($total<=0){ ?>
			<script>
			if($('numberOfComments')) {
				if($('numberOfComments').parentNode) {
					$('numberOfComments').parentNode.childNodes[0].style.display = 'none';
		                        $('numberOfComments').style.display = 'none';
				}
			}
			</script>


                <?php }
                else if($total==1){ ?>
                        <script>
						if($('numberOfComments')) {
                        	$('numberOfComments').innerHTML = '<?=$total?> question';
						}
                        </script>
                        
                <?php }
                else{ ?>
                        <script>
						if($('numberOfComments')) {
                        	$('numberOfComments').innerHTML = '<?=$total?> questions';
						}
                        </script>
                <?php
                }
        ?>
        <?php endif?>
