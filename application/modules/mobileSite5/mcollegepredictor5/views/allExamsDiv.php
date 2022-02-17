<?php
			$this->load->config('CP/CollegePredictorConfig',TRUE);
			$settings =
			$this->config->item('settings','CollegePredictorConfig');
			$exams = $settings['CPEXAMS'];
?>
		<header id="page-header" class="clearfix">
		    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
			<a onClick="showEmailResultBar();" id="collegePredictorOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   	
			<div class="title-text" id="collegePredictorOverlayHeading">College Predictor</div>
		    </div>
		</header>
		
		<section class="layer-wrap fixed-wrap" style="height: 100%">
		    <ul class="layer-list">
			<?php foreach ($exams as $examName => $examData):?>
				<?php 
					$configExamName = strtoupper($examName);
					$configExamArray = $settings[$configExamName];
					$dbExamName = $configExamArray['examName'];
				
				?>
				<li><a href="javascript:void(0);" onclick="cpRedirect('<?php echo $examData['collegeUrl'];?>','<?php echo $dbExamName;?>')"><?php echo $examData['name'];?></a></li>
			<?php endforeach; ?>		
		    </ul>
		</section>
		<a href="javascript:void(0);" onclick="$('#collegePredictorOverlayClose').click();" class="cancel-btn">Cancel</a>
		
