	
	<?php $this->load->view('resetWidget');?>
	
	<div class="predictor-rank-box" style="margin-top: 11px;" <?php if(count($getCookei)>0 && isset($validateuser[0]['userid']) && $validateuser[0]['userid']>0){?> style="display:none;"<?php }?> id="predictorRank-header">
		<div class="flLt predictor-title-box " style="margin-top:10px;">
		<h1 class="font-24"><?=$seoName?> Rank Predictor</h1>
		<p><?php echo $rpConfig[$examName]['heading'];?> <span class="predictor-info" onmouseover="$j('#tooltipText').show();" onmouseout="$j('#tooltipText').hide();">i</span></p>
		<div class="predict-tooltip" style="display:none" id="tooltipText">
			<?php echo $rpConfig[$examName]['helpText'];?>
		</div>
	    </div>
	<?php if($rpConfig[$examName]['isShowAakashLogo'] == 'YES'){?>
	    <div class="flRt">
			<p class="powered-title">Powered by:</p>
			<a style="text-decoration: none;" target="_blank" href="http://www.aakash.ac.in">
			<img src="/public/images/akash-logo.png" width="82" height="47" alt="akash logo" /></a>
	    </div>
	    <div class="clearFix"></div>
	<?php }?>
	</div>