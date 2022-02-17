<script>
function showDiv(nDivID){
	obj = document.getElementById(nDivID);
	obj.style.display = "block";
	if(nDivID=="PG"){
		document.getElementById('PG2').style.display = "none";
		document.getElementById('PG3').style.display = "none";
	}
	if(nDivID=="PG2"){
		document.getElementById('PG').style.display = "none";
		document.getElementById('PG3').style.display = "none";
	}
	if(nDivID=="PG3"){
		document.getElementById('PG').style.display = "none";
		document.getElementById('PG2').style.display = "none";
	}
}
</script>
<?php
//if($userDetailsArray[0][0][0][reputationPoints] || $reputationPoints){?>
<div id="Rank" style="display:none;position:absolute;left:0;z-index:1000;top:0">
	<div style="position:relative;top:1px;left:10px"><img src="/public/images/rArw.gif" /></div>
	<div class="rank" nowrap="nowrap">Current Rank :<span id="cRankTC">&nbsp;<?php echo $rank; ?></span></div>
</div>
<?php
 //}
  ?>
<!--Start_UserWidge-->
<div class="wdh100">
	<div id="RankAndReputationPonits" style="position:absolute;left:0px;z-index:1000;display:none;top:0px;">
		<div style="width:475px;position:relative">
			<div id="leftArrowImageRep" style="position:absolute;top:24px;left:1px;"><img src="/public/images/myprofileArw.gif" /></div>
			<div id='rightArrowImageRep' style="display:none;"><img border="0" src="/public/images/arrowRightOverlay.gif" class="vCardRArw" id="rightArrowRepImage"/></div>
			<div class="raised_skyWithBGW" style="margin-left:23px">
				<b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_skyWithBGW" style="background:#fff url(/public/images/bgNewAnAW.gif) left bottom repeat-x">
					<div class="mlr10">
							<div class="lineSpace_10">&nbsp;</div>
							<div class="clear_B">&nbsp;</div>
							<div class="wdh100">
								<div class="mPflebg">									
									<span class="mpSpan1">Reputation: <b><span id="repNumTC"><?php echo $reputationPoints; ?>&nbsp;</span></b></span>
									<span class="mpSpan2">Current Rank: <b><span id="rankNumTC"><?php  echo $rank; ?>&nbsp;</span></b></span>									
								</div>
								<div class="float_R"><a href="javascript:void(0);" onclick="hideRepuataionAndRank();">X</a></div>
							</div>
					</div>
					<div class="clear_B">&nbsp;</div>
					<div class="lineSpace_10">&nbsp;</div>
					<div class="wdh100">
							<!--Start_FirstPage-->
							<div class="raised_skyWithBGW" style="padding-right:10px;padding-left:10px;">
								<b class="b2"></b><b class="b3"></b><b class="b4"></b>
								<div class="boxcontent_skyWithBGW">
									<div style="padding:5px 10px">
										<div class="bld">What is Reputation Index and Rank?</div>
										<div>Reputation index is a measure of your influence and reputation amongst the community. Higher the reputation, higher the rank. Your reputation index has a direct impact on your Q&amp;A points.</div>
									</div>
								</div>
								<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
							</div>
							<!--End_FirstPage-->
							<div class="lineSpace_10">&nbsp;</div>
							<!--Start_FirstPage-->
							<div class="raised_skyWithBGW" style="padding-right:10px;padding-left:10px;">
								<b class="b2"></b><b class="b3"></b><b class="b4"></b>
								<div class="boxcontent_skyWithBGW">
									<div style="padding:5px 10px">
										<div class="bld">How is Reputation Index calculated?</div>
										<div>Reputation Index is based on the quality of your answers. For every answer of yours liked by the community, you get an increase in reputation. The Shiksha community can like your answers in many ways: by giving thumbs up, by selecting your answers as the best answer, by following you.</div>
									</div>
								</div>
								<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
							</div>
							<!--End_FirstPage-->
							<div class="lineSpace_10">&nbsp;</div>
							<!--Start_FirstPage-->
							<div class="raised_skyWithBGW" style="padding-right:10px;padding-left:10px;">
								<b class="b2"></b><b class="b3"></b><b class="b4"></b>
								<div class="boxcontent_skyWithBGW">
									<div style="padding:5px 10px">
										<div class="bld">Can the Reputation Index also decrease?</div>
										<div>Yes. As stated earlier, Reputation Index is based on the quality of your answers. If the community feels that the quality is dropping, they can express their opinion by giving thumbs down to your answers. To keep your Reputation Index high, try giving well researched and clear answers.</div>
									</div>
								</div>
								<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
							</div>
							<!--End_FirstPage-->
							<div class="lineSpace_10">&nbsp;</div>
					</div>
				</div>
				<b style="background: none repeat scroll 0% 0% rgb(100, 146, 206);" class="b4b"></b><b style="background: none repeat scroll 0% 0% rgb(100, 146, 206);" class="b3b"></b><b style="background: none repeat scroll 0% 0% rgb(100, 146, 206);" class="b2b"></b><b class="b1b"></b>
				<div class="clear_B">&nbsp;</div>
			</div>
		</div>
	</div>
</div>        
<!--End_UserWidge-->