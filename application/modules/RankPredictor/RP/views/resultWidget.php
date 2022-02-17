<div class="predictor-rank-box predicted-rank" <?php if(count($getCookei) <=0 && isset($validateuser[0]['userid']) && $validateuser[0]['userid']>0){?> style="display:none;" <?php }else if(count($getCookei) >0 && isset($validateuser[0]['userid']) && $validateuser[0]['userid']>0){?> style="display:block;" <?php }else{?> style="display:none;" <?php }?> id="predicted-Layer">
	<p class="font-24 flLt" id="_showResult">Your Predicted Rank:<strong class="font-30" id="predicted-rank" style="vertical-align: middle;color:#1b1b1b;">&nbsp;<img style='padding-top: 2px;' src='/public/images/loader_hpg.gif' border=0></img></strong></p>
    <?php if($_COOKIE[$rpFeedBackCookieName] == "" && $rpConfig[$examName]['ishelpfulWidget'] == 'YES'){?>
    <div class="flLt" style="width:100%; margin-top:3px;">
	<span class="flLt helpful-text">Is this information helpful?</span>
	<div class="vote-section flLt" style="margin-top:0;">
		<a class="rankhelpful" style="margin-right:10px; text-decoration: none;" href="javascript:void(0);" onclick="sendRankFeedback('1','rate','rankPredictor')"><i class="predictor-sprite upVote-icon" id="yes"></i> Yes</a>
		<a class="rankhelpful" style="text-decoration: none;" href="javascript:void(0);" onclick="sendRankFeedback('0','rate','rankPredictor')"><i class="predictor-sprite dwnVote-icon" id="no"></i> No</a>
	</div>
    </div>
    <div id="thankMsg" style="display: none;" class="helpful-text thnku-msg clearFix flLt"></div>
    <?php }?>
    <div class="clearFix"></div>
    <div class="predict-disclaimer-txt" id="disclaimer-onresult">Disclaimer: <?php echo substr($rpConfig[$examName]['disclaimer'],0,162).'..';?>
	  <a href="javascript:void(0);" onclick="openDisclaimer();" style="text-decoration: none;">View More</a>
    <span style="display: none;" id="disc_more">Disclaimer: <?php echo $rpConfig[$examName]['disclaimer'];?></span>
    </div>
</div>
