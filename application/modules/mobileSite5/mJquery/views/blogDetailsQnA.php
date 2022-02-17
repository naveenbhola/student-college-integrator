<section class="content-wrap2">
	<div class="article-content">
    <?php 
     $blogQna = $blogObj->getDescription();
	 $noOfQnA=count($blogQna);
	 $i=0;
	 foreach($blogQna as $sn){ 
	    if($sn->getQuestion()){
		if($i==1): ?>
		<section class="qna-section">
		
		<?php endif; if($i!=0): ?>
	       <div class="qna-wrap">
		      <div class="qna-box">
			  <span class="qna-icon">Q</span>
			  <div class="qna-details">
			      <strong><?=html_escape($sn->getQuestion())?></strong>
			  </div>
		      </div>
		      <div class="qna-box">
			  <span class="qna-icon">A</span>
			  <div class="qna-details">
			      <?=addAltTextMobile($blogObj->getTitle(), $sn->getAnswer());?>
			  </div>
		      </div>
	      </div>      
		     
		<?php
                 endif; }  if($i==$noOfQnA-1): ?> 
	    
	    </section>
	    <?php endif; ?> 
	    <?php if($i==0){ ?>
		       <p><?=addAltText($blogObj->getTitle(), $sn->getAnswer());?> </p>
	    <?php  }
		$i++;
	 }
	 ?>
	</div>
	<?php $this->load->view('articleDetailsOtherInfo');?>
</section>
