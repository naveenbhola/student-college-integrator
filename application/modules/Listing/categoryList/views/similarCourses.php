	<?php $this->load->view("categoryList/similarCourseHeader");?>
		
	<div class="similar-inst-wrapper">
		<div class="similar-inst-cont">
                            <div class="cont-title">Find Institutes Similar to</div>
					<?php $this->load->view('categoryList/similarCourseSearch'); ?>
		</div>
	</div>
	<?php $this->load->view('categoryList/similarCourseLeftPane'); ?>

	<div id="cateRightCol" style="margin:10px 0;width:280px;">
		<div class="similar-inst-widget">
		
		<div class="widget-title">Don't Miss Out Options As Good As Your Dream Institute 
		<i class="similar-pointer"></i>
		</div>
		<div class="widget-content">
		<strong>How will the similar institute <br />
		feature on Shiksha help you?</strong>
		
		<i class="similar-sprite help-image"></i>
		<p>It's simple really. While you might know of a few great institutes you aspire to study in, we will help you find other institutes that are the similar or the closest MATCH to your dream college. Go ahead and enter the name of your favourite institute and we will wave our wand to show you more options. </p>
		</div>
		</div>
	</div>
	<?php $this->load->view("categoryList/similarCourseFooter"); ?>		
