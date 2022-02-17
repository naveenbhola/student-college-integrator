<div class="predictor-rank-box" <?php if(count($getCookei) <=0 && isset($validateuser[0]['userid']) && $validateuser[0]['userid']>0){?> style="display:none;margin-top: 11px;" <?php }else if(count($getCookei) >0 && isset($validateuser[0]['userid']) && $validateuser[0]['userid']>0){?> style="display:block;margin-top: 11px;" <?php }else{?> style="display:none;margin-top: 11px;" <?php }?>id="predictorRrank-rest">
	<div class="flLt predictor-title-box" style="margin-top:10px;width:auto;">
	<p class="font-24"><?php echo $rpConfig[$examName]['examName'];?> Rank Predictor</p>
	<?php if($examName !='comedk' && $examName !='jee-advanced'){?>
	<ul class="board-score-sec">
		<li>Your <?php echo $rpConfig[$examName]['inputField']['score']['label'];?>: <strong id="_score"><?php echo $getCookei[0];?></strong></li>
		<!-- <li><span class="score-sep"> | </span></li>
		<li>Your <?php echo $rpConfig[$examName]['inputField']['marks']['label'];?>: <strong id="_boardScore"><?php echo $getCookei[1];?></strong></li> -->
		<li style="float: right;"><a href="javascript:void(0);" onclick="$j('#restForm-box').slideDown(400);$j(this).hide();" class="predcit-reset-btn" id="predcit-reset-btn" style="display: none;">Reset</a></li>
	</ul>
	<?php }else{?>
	<ul class="board-score-sec">
		<li>Your <?php echo $rpConfig[$examName]['inputField']['score']['label'];?>: <strong id="_score"><?php echo $getCookei[0];?></strong></li>
		<li style="margin:0; float:right"><a href="javascript:void(0);" onclick="$j('#restForm-box').slideDown(400);$j(this).hide();" class="predcit-reset-btn" id="predcit-reset-btn" style="display: none;">Reset</a></li>
        </ul>
	<?php }?>
	</div>
    <?php if($rpConfig[$examName]['isShowAakashLogo'] == 'YES'){?>	
    <div class="flRt">
		<p class="powered-title">Powered by:</p>
		<a style="text-decoration: none;" target="_blank" href="http://www.aakash.ac.in"><img src="/public/images/akash-logo.png" width="82" height="47" alt="akash logo" /></a>
    </div>
    <div class="clearFix"></div>
    <?php }?>
</div>
