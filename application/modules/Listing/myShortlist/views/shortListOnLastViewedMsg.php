<div style="margin:0px;
width: <?=$isRankingPage ? '758' : '743'?>px;
position: absolute;
z-index: 1;
right: <?=$isRankingPage ? '-19' : '-9'?>px;
top:  <?=$isRankingPage ? '42' : '58'?>px;
display:block
" 
	class="shortlist-widget cate-shrtlist-widget" isAnimated="false">
	<i class="common-sprite shrtlist-collge-pointer"></i>
	<div class="shortlist-widget-head">
		<div style="text-align: left" class="flLt">
			<strong>Shortlist to make an informed Decision About</strong>
			<p><?=$instituteName?></p>
		</div>
		<a class="flRt shortlist-remove-icon" onclick="animateToCloseShortlistWidget();" href="javascript:void(0);">×</a>
		<div class="clearFix"></div>
	</div>
	<div class="shortlist-list clear-width">
		<ul>
			<li>
				<div class="shrtlist-icon-box">
					<i class="common-sprite placmnt-sml-icn"></i>
				</div> <strong>Find Placement Data </strong>
				<p>See where students end up after graduating</p>
			</li>
			<li>
				<div class="shrtlist-icon-box">
					<i class="common-sprite readRvw-sml-icn"></i>
				</div> <strong>Read Reviews </strong>
				<p>See how alumni rate their college</p>
			</li>
			<li>
				<div class="shrtlist-icon-box">
					<i class="common-sprite askCurrent-sml-icn"></i>
				</div> <strong>Ask Current Students </strong>
				<p>Get Answers from current students</p>
			</li>
			<li class="last">
				<div class="shrtlist-icon-box">
					<i class="common-sprite getAlert-sml-icn"></i>
				</div> <strong>Get Alerts </strong>
				<p>Never miss a deadline of your target colleges</p>
			</li>
		</ul>
	</div>
	<div class="clearFix"></div>
</div>