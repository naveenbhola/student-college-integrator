<?php
    $url_parts = parse_url("http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
    $constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
    $url = urlencode($constructed_url);
	$rankingPageId = $ranking_page->getId();
?>
<div class="flRt">
	<div class="flLt">
	  <iframe src="//www.facebook.com/plugins/like.php?href=<?php echo $current_page_url; ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font=tahoma&amp;colorscheme=light&amp;action=like&amp;height=21&amp;" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:25px;" allowTransparency="true"></iframe>
	</div>
	<div class="flLt">
	  <iframe allowtransparency="true" frameborder="0" scrolling="no" src="https://platform.twitter.com/widgets/tweet_button.html?url=<?php echo $current_page_url;?>&text=<?php echo $meta_details['title'];?>" style="width:85px; height:20px;"></iframe>
	</div>
	<div class="flLt" style="width:65px;">
		<div class="g-plusone" data-size="medium" data-href="<?php echo $current_page_url;?>"></div>
		<script type="text/javascript">
		  (function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script>
	</div>
	<div class="flLt">
	<?php
		$source = 'RANKING_PAGE_EMAIL_TIHS';
		if(!is_array($validateuser[0])) {
			?>
            <a href="javascript:void(0);" class="mailButton" onClick="emailRankingPage('<?php echo $urlIdentifier;?>'); return false;"  title="Email this"></a>
		<?php
		} else {
		?>
			<a href="javascript:void(0);" class="mailButton" onClick="showRankingEmailThisOverlay('<?php echo $urlIdentifier;?>'); return false;" title="Email this"></a>
        <?php
		}
		?>
	</div>
</div>

<script type="text/javascript">
	function emailRankingPage(urlIdentifier){
		loadShortRegistationForm("Registration",
								 "Submit",
								 "Successfully registered",
								 false,false,
		function(){
			showRankingEmailThisOverlay(urlIdentifier);
		}, "RANKING_PAGE_EMAIL_TIHS");
	}
</script>