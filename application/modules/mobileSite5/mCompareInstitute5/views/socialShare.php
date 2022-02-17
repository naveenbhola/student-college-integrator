<?php
$url_parts = parse_url("http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
$constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
$constructed_url = preg_replace(array("/http:\/\//i") , "https://", $constructed_url);
$urlArticle = urlencode($constructed_url);
?>

 <section class="social-section">
    <ul class="social-links" style="margin: 0">
        <li style="width:33%">
            <a onclick="window.open('http://www.facebook.com/sharer.php?u=<?php echo $urlArticle; ?>', 'sharer', 'toolbar=0,status=0,width=620,height=280'); trackEventByGAMobile('HTML5_ARTICLE_DETAILS_FACEBOOK_SHARE');" href="javascript: void(0)">
            <i class="icon-facebook"></i>Share
            </a>
        </li>

        <li style="width:33%">
            <a onclick="window.open('https://twitter.com/intent/tweet?url=<?php echo $urlArticle; ?>', 'sharer', 'toolbar=0,status=0,width=620,height=280'); trackEventByGAMobile('HTML5_ARTICLE_DETAILS_TWITTER_SHARE');" href="javascript: void(0)">
            <i class="icon-tweet"></i>Tweet</a>
        </li>
	 <?php
	 $instStr = '';$count=0;
	  foreach($institutes as $institute)
	 {
	   $instStr .= ($count>0)?' vs '.html_escape($institute->getName()):html_escape($institute->getName());
	   $count++;
	 }
	 
	 
	 ?>
        <li style="width:33%">
           <!-- <a onclick="window.open('https://plus.google.com/share?url=<?php echo $urlArticle; ?>', 'sharer', 'toolbar=0,status=0,width=620,height=280');trackEventByGAMobile('HTML5_ARTICLE_DETAILS_GOOGLE_SHARE');" href="javascript: void(0)">
            <i class="icon-gplus"></i>+ 1</a>-->
		<a href="whatsapp://send?text=<?php echo preg_replace(array('/&amp;/i','/\+/i','/"/'),array('and','','\''),$instStr).' - '.$urlArticle; ?>" data-action="share/whatsapp/share" onclick="trackEventByGAMobile('HTML5_COMPARE_PAGE_WHATSAPP_SHARE');">
                <i class="sprite icon-wtapp"></i>WhatsApp</a>
        </li>
    </ul>
</section>
