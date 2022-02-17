<?php
$url_parts = parse_url("https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
$constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
$urlArticle = urlencode($constructed_url);
?>
        
<?php 
$articleStreamUrl = ($blogObj->getType() == 'news') ? SHIKSHA_HOME.'/news' : $this->config->item('articleUrl');
?>
<div class="slide-content">
<div class="article-keywords">
    <?php /*  if($blogObj->getTags() != ''): ?>
	<p>Keywords: <span><?php echo $blogObj->getTags();?></span></p>
	<?php endif;*/?>
	<?php  if($blogObj->getType() == 'kumkum'):?>
	<p>Updated on: <span class="articleDate"><?php echo date("M j, Y", strtotime($blogObj->getLastModifiedDate()))?> By </span><strong class="authorName">
		<a href="<?php echo SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/kumkumProfile';?>">Kumkum Tandon, Renowned Career Counsellor</a>
	</strong></p>
    <?php elseif($blogObj->getType() == 'ht'):?>
	<p>Updated on: <span class="articleDate"><?php echo date("M j, Y", strtotime($blogObj->getLastModifiedDate()))?> By </span><strong class="authorName">Powered by HT Horizons</strong></p>
    <?php else:?>
	<?php if($authorUrl!=''):?>
		<p>Updated on: <span class="articleDate"><?php echo date("M j, Y", strtotime($blogObj->getLastModifiedDate()))?> By </span><strong class="authorName">
		<a href="<?php echo $authorUrl;?>/"><?php echo $authoruserName;?></a>
		</strong></p>
	<?php elseif($externalUser=='true'):?>
		<p>Updated on: <span class="articleDate"><?php echo date("M j, Y", strtotime($blogObj->getLastModifiedDate()))?> By </span><strong class="authorName"><?php echo $authoruserName;?></strong></p>
	<?php else:?>
		<p>Updated on: <span class="articleDate"><?php echo date("M j, Y", strtotime($blogObj->getLastModifiedDate()))?> By </span><strong class="authorName">Shiksha</strong></p>
	<?php endif;?>	
    <?php endif;?>
   
   
</div>
 </div>
