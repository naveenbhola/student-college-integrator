<div id="breadcrumb" style="width:60%; padding-left:10px">
        <div id="articleBreadCrumb">
        	<ul>
		<li><a href="<?php echo SHIKSHA_HOME;?>">Home</a><span></span></li>
		<?php if($isNews!='1'){ ?>
                <li><a href="<?php echo SHIKSHA_HOME;?>/blogs/shikshaBlog/showArticlesList">All Articles</a><span></span></li>
                <?php }  if($isFeature=='1'){ ?>
                <li><a href="<?php echo SHIKSHA_HOME;?>/blogs/shikshaBlog/getFlavorArticles">Features</a><span></span></li>
                <?php }else if($isNews=='1'){ ?>
                <li><a href="<?php echo SHIKSHA_HOME;?>/news">News</a><span></span></li>
                <?php } ?>
                <?php echo formatRelatedArticleTitle($blogInfo[0]['blogTitle'],80);?>
           </ul>
      </div>
</div>
