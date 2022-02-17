<div class="cmp-share-box" style="<?php if(!($institutes && count($institutes)>0)){echo 'display:none;';}?>">
	<p class="">Share this comparison
		<span class="share-icons">
			<?php 
			$pageurl = "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			?>
			<a href="javascript:;" onclick="trackEventByGA('CmpShare','COMPARE_DESK_SHARE_FB');window.open('http://www.facebook.com/sharer.php?u=<?=$pageurl?>', 'sharer', 'toolbar=0,status=0,width=620,height=280');"><i class="cmpre-sprite ic-fb"></i></a>
			<a href="javascript:;" onclick="trackEventByGA('CmpShare','COMPARE_DESK_SHARE_TWEET');window.open('https://twitter.com/intent/tweet?url=<?=$pageurl?>', 'sharer', 'toolbar=0,status=0,width=620,height=280'); trackEventByGAMobile('HTML5_ARTICLE_DETAILS_TWITTER_SHARE');"><i class="cmpre-sprite ic-tweet"></i></a>
			<a href="javascript:;" onclick="trackEventByGA('CmpShare','COMPARE_DESK_SHARE_GPLUS');window.open('https://plus.google.com/share?&hl=en&url=<?=$pageurl?>', 'sharer', 'toolbar=0,status=0,width=620,height=280');"><i class="cmpre-sprite ic-gplus"></i></a>
		</span>
	</p>
</div>