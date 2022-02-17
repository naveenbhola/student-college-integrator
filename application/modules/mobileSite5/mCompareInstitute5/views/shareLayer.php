 <?php
	$instStr = '';$count=0;
	foreach($institutes as $institute)
	{
		$instStr .= ($count>0)?' vs '.html_escape($institute->getName()):html_escape($institute->getName());
		$count++;
	} 
	$url_parts = parse_url("https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
	$constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
  $constructed_url = preg_replace(array("/https:\/\//i") , "https://", $constructed_url);
	$urlArticle = urlencode($constructed_url);
	 ?>

<div id = "socialShareLayer" class="social-links1" onclick="freePage();$('#socialShareLayer').hide(); var html = $('html');
      var scrollPosition = html.data('scroll-position');
      html.css('overflow', html.data('previous-overflow'));
      window.scrollTo(scrollPosition[0], scrollPosition[1]);
      $('#page-header-container .header').show()">
  <div class="scl">
    <p class="">Share</p>
    <ul class="foote-scl">
     <li><a onclick="window.open('https://www.facebook.com/sharer.php?u=<?php echo $urlArticle; ?>', 'sharer', 'toolbar=0,status=0,width=620,height=280'); trackEventByGAMobile('HTML5_ARTICLE_DETAILS_FACEBOOK_SHARE');" href="javascript: void(0)"><i class="ic-fb spriteSocialIcons"></i><p>Facebook</p></a></li>
      <li><a onclick="window.open('https://twitter.com/intent/tweet?url=<?php echo $urlArticle; ?>', 'sharer', 'toolbar=0,status=0,width=620,height=280'); trackEventByGAMobile('HTML5_ARTICLE_DETAILS_TWITTER_SHARE');" href="javascript: void(0)"><i class="ic-tweet spriteSocialIcons"></i><p>Twitter</p></a></li>
       <li><a href="whatsapp://send?text=<?php echo preg_replace(array('/&amp;/i','/\+/i','/"/'),array('and','','\''),$instStr).' - '.$urlArticle; ?>" data-action="share/whatsapp/share" onclick="trackEventByGAMobile('HTML5_COMPARE_PAGE_WHATSAPP_SHARE');"><i class="ic-wa spriteSocialIcons"></i><p>Whats App</p></a></li>
    </ul>
  </div>
</div>

<script type="text/javascript">
  function freePage(){
    var browserType = window.navigator.userAgent;
    if((browserType.match(/Windows/g) != null || browserType.match(/Microsoft/g) != null)){
       $('.ui-content').height('auto');
       $('.ui-panel-dismiss').height('');
    }
  }
</script>