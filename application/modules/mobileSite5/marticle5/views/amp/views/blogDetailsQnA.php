<h1 class="color-1 f22 font-w7 m-btm"><?=$blogObj->getTitle()?></h1>
<?php $this->load->view('articleDetailsOtherInfo');?>
<?php $this->load->view("mcommon5/socialShareThis",array('pageType'=>'ampPage')); ?>
<?php 
$blogQna = $blogObj->getDescription();
$noOfQnA=count($blogQna);
$i=0;
foreach($blogQna as $sn){ 
	if($sn->getQuestion()){
		if($i==1): ?>
			<div class="qna-section">

		<?php endif; if($i!=0): ?>
			<div class="qna-wrap">
				<div class="qna-box">
					<span class="qna-icon">Q</span>
					<div class="qna-details">
						<strong><?=$sn->getQuestion()?></strong>
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

		</div>
	<?php endif; ?> 
	<?php if($i==0){ ?>
		<p><?=addAltText($blogObj->getTitle(), $sn->getAnswer());?> </p>
		<?php  }
		$i++;
}
?>
