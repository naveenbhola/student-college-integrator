 
	<input type="hidden" autocomplete="off" id="course_id" value="<?php echo $courseId;?>"/>
<?php if(!empty($qna)):?>
 <div class="discuss-comnt-section" id="questionListOnPage">
	<!-- <div class="cmnt-filter">
    Filter by: <a href="#">Eligibility,</a> <a href="#">Placements,</a> <a href="#">Cutoff,</a> <a href="#">Entrance Exam,</a> <a href="#">Fees</a>
    </div> -->
    
	<div style="margin-bottom: 18px;" id="paginataionPlace1" class="pagingID txt_align_r">
	
	<?php echo doPagination($totalEntry,$paginationUrl,$pageNo,$pageSize,$noPages);?>	

	</div>    
  <?php endif;?>
  	
    <div class="cmnt-title">
    <?php $unansweredText = ($questionType == 'Unanswered')?$questionType:'';?>
    	<span class="flLt"><?php echo $totalEntry;?> <?php echo ($totalEntry > 1)?$unansweredText.' Questions':$unansweredText.' Question'?>
    	<?php if(!empty($links)):?>
    	<a href="javascript:void(0);" onclick="setLinkCookie('All')"> (See All)</a>
    	<?php endif;?>
    	</span>
        <p class="flRt view-filter">
        View: 
<?php if($questionType == "All"){?>
<strong style="color:#666">All</strong> <span class="pipe">|</span> <a href="javascript:void(0)" style="font-weight:<?php echo ($questionType == "Unanswered")?'bold':'normal';?>" onclick="filterQuestions('Unanswered')">Unanswered</a>
<?php } ?>
<?php if($questionType == "Unanswered"){?>
<a href="javascript:void(0)" style="font-weight:<?php echo ($questionType == "All")?'bold':'normal';?>" class="" onclick="filterQuestions('All')">All</a> <span class="pipe">|</span> <strong style="color:#666">Unanswered</strong>
<?php } ?>
        </p>
    </div>    
    
	<?php if(!empty($qna)):?>
 	<ul class="comment-block ">    
 	<?php echo $this->load->view('CA/courseOverviewInner', $qna);?>
 	</ul>
            <div class="clearFix"></div>
	<div style="margin:12px 0 18px;" id="paginataionPlace1" class="pagingID txt_align_r">
	
	<?php echo doPagination($totalEntry,$paginationUrl,$pageNo,$pageSize,$noPages);?>	
	</div>    
                </div>

    <?php endif;?>
      
        <?php
                if($totalEntry<=0){ ?>
                        <script>
			if($('numberOfComments')) {
				if($('numberOfComments').parentNode) {
					$('numberOfComments').parentNode.childNodes[0].style.display = 'none';
		                        $('numberOfComments').style.display = 'none';
				}
			}
			</script>

                <?php }
                else if($totalEntry==1){ ?>
                        <script>
						if($('numberOfComments')) {
                        	$('numberOfComments').innerHTML = '<?=$totalEntry?> question';
						}
                        </script>
                        
                <?php }
                else{ ?>
                        <script>
						if($('numberOfComments')) {
                        	$('numberOfComments').innerHTML = '<?=$totalEntry?> questions';
						}
                        </script>
                <?php
                }
        ?>
      
