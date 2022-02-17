<?php
$url_parts = parse_url("http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
$constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
$urlArticle = urlencode($constructed_url);
?> 
 
 <!-- <section class="social-section">	
    <ul class="social-links">	
	<li>
	    <a onclick="window.open('http://www.facebook.com/sharer.php?u=<?php echo $urlArticle; ?>', 'sharer', 'toolbar=0,status=0,width=620,height=280'); trackEventByGAMobile('HTML5_ARTICLE_DETAILS_FACEBOOK_SHARE');" href="javascript: void(0)"> 
	    <i class="icon-facebook"></i>Share
	    </a>
	</li>

	<li>
	    <a onclick="window.open('https://twitter.com/intent/tweet?url=<?php echo $urlArticle; ?>', 'sharer', 'toolbar=0,status=0,width=620,height=280'); trackEventByGAMobile('HTML5_ARTICLE_DETAILS_TWITTER_SHARE');" href="javascript: void(0)"> 
	    <i class="icon-tweet"></i>Tweet</a>
	</li>

	<li>
	<a href="whatsapp://send?text=<?php echo preg_replace(array('/&amp;/i','/\+/i','/"/'),array('and','','\''),$blogInfo[0]['blogTitle']).' - '.$urlArticle; ?>" data-action="share/whatsapp/share" onclick="trackEventByGAMobile('HTML5_ARTICLE_DETAILS_WHATSAPP_SHARE');">
		<i class="sprite icon-wtapp"></i>WhatsApp</a>
	</li>
    </ul>
</section> -->
        
<?php 
$articleStreamUrl = ($blogObj->getType() == 'news') ? SHIKSHA_HOME.'/news' : $this->config->item('articleUrl');
?>
<div class="slide-content">
<div class="article-keywords">
    <?php  if($blogObj->getTags() != ''): ?>
	<p>Keywords: <span><?php echo $blogObj->getTags();?></span></p>
	<?php endif;?>
	<?php  if($blogObj->getType() == 'kumkum'):?>
	<p>Posted by: <span>Kumkum Tandon, Renowned Career Counsellor |  <?php echo date("M j, Y, h.iA", strtotime($blogObj->getLastModifiedDate()))?> IST</span></p>
    <?php elseif($blogObj->getType() == 'ht'):?>
	<p>Posted by: <span>Powered by HT Horizons | <?php echo date("M j, Y, h.iA", strtotime($blogObj->getLastModifiedDate()))?> IST</span></p>
    <?php else:?>
    <?php if($blogId != 13149):?>
	<?php if($authorUrl!=''):?>
		<p>Posted by: <span><?php echo $authoruserName;?> | <?php echo date("M j, Y, h.iA", strtotime($blogObj->getLastModifiedDate()))?> IST</span></p>
	<?php elseif($externalUser=='true'):?>
		<p>Posted by: <span><?php echo $authoruserName;?> | <?php echo date("M j, Y, h.iA", strtotime($blogObj->getLastModifiedDate()))?> IST</span></p>
	<?php else:?>
		<p>Posted by: <span>Shiksha | <?php echo date("M j, Y, h.iA", strtotime($blogObj->getLastModifiedDate()))?> IST</span></p>
	<?php endif;?>
	<?php endif;?>	
    <?php endif;?>
   
   
</div>
<!--- display course name -->
<!--div class="footer-links" style="font-size:inherit">
	    <label>Course:</label>
	    <a href="<?php //echo $articleStreamUrl.'?subcat='.$blogInfo[0]['boardId'];?>" class="ui-link"><?php //echo $subCategoryName;?>Subcat name</a>
</div-->
 </div>
