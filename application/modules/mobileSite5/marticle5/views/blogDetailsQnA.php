<section class="content-wrap2">
	<div class='articleDetails'>
			<div class="article-content">
		   	 <?php 
		     $blogQna = $blogObj->getDescription();
			 $noOfQnA=count($blogQna);
			 $i=0;
			 foreach($blogQna as $sn){ 
			    if($sn->getQuestion()){?>
			    <section class="qna-section">
				<?php if($i==1): ?>	
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
	</div>
	<div class="article-readmore">
		<a class="link-blue-medium articleViewMore" href="javascript:void(0)" >Read Full Article</a>
	</div>
	<div class="social-wrapper-btm">
		<?php $this->load->view("mcommon5/socialSharingBand", array('widgetPosition' => 'ADP_Bottom')); ?>
	</div>
	<?php 
		$this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
	?>
</section>
