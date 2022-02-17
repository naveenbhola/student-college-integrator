<?php
foreach($articleList as $article) {
	$formattedTitle = '';
	$blogTitle = $article->getBlogTitle();
	$blogType = ($article->getBlogType() == 'news') ? 'News' : 'Articles';
	$blogURL = SHIKSHA_HOME.$article->getBlogUrl();
	$summary = strip_tags($article->getSummary());
	$imageURL  = $article->getBlogImageURL();
	$params = array();
	
	if(strlen($summary) > 200){
		$summary = preg_replace('/\s+?(\S+)?$/', '',substr($summary, 0,200))."...";
	}
	if(empty($imageURL)){
		$imageURL = SHIKSHA_HOME.'/public/images/articlesDefault_s.gif';
	}else{
		$params['url'] = $imageURL;
		$params['domainName'] = MEDIA_SERVER;
		$imageURL = addingDomainNameToUrl($params);
	}
	if(strlen($blogTitle)>=65 && strlen($blogTitle)<=80){
		$arrTitle = explode(" ",$blogTitle);
		$titleCount = count($arrTitle);
		$titleLastword = $arrTitle[$titleCount-1];
		array_pop($arrTitle);
		$titleStr = implode(" ",$arrTitle);
		$formattedTitle = $titleStr .'<br>'. $titleLastword;
	}
	$numberOfComments = $commentCount[$article->getDiscussionTopicId()];
?>
	<div class="" style="padding:10px; line-height:25px;font-size:16px; color:#000000; text-decoration:none; border-bottom:1px solid #E1D7D7; margin-bottom:5px">
	    <div class="float_L"><img  class="lazy" data-original="<?php echo $imageURL; ?>" alt="<?php echo $blogTitle; ?>"/></div>
	    <div style="margin-left:68px">
		    <div style="font-size:18px;padding-bottom:3px;"><a href="<?php echo $blogURL; ?>" target="_blank" title="<?php echo strip_tags($blogTitle);?>"><u><?php if($formattedTitle !=''){ echo strip_tags($formattedTitle);}else{echo strip_tags($blogTitle);} ?></u></a>&nbsp;&nbsp;<label class="graycolor"><?php echo $articleType;?></label>
				<?php if($blogType == 'News'){?>
				    <img class="lazy" data-original="<?php echo SHIKSHA_HOME;?>/public/images/news-badge.png" style="position:relative; top:4px;"/>
				<?php }else{?>
				    <img class="lazy" data-original="<?php echo SHIKSHA_HOME;?>/public/images/article-badge.png" style="position:relative; top:4px;"/>
				<?php }?>
				<?php if(!empty($numberOfComments)){?>
				<span style="font-size:14px;"><span style="margin: 0px 10px; color:#ccc; font-size:16px"> | </span><img class="lazy" data-original="<?php echo SHIKSHA_HOME;?>/public/images/alminiBlog.gif" align="absmiddle"/><a href="<?php echo $blogURL;?>#blogCommentSection"><?php echo $numberOfComments;?> Comment<?php if($numberOfComments > 1){ echo 's';}?></a></span><?php }?>
				<?php if(!empty($summary)) { ?>
					<div><?php echo $summary;?></div>
		        <?php } ?>
		    </div>
	    	<div class="clearFix"></div>
	    </div>
	</div>
<?php } ?>