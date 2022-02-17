<?php if(is_array($articles) && count($articles)>0){ ?>
            <section class="clearfix content-wrap">
            	<header class="content-inner content-header clearfix">
                	<h2 class="title-txt">Trending News &amp; Articles</h2>
                </header>

		<?php
		$i=0;
		foreach ($articles as $article){
		    $i++;
		    if(strlen($article['blogTitle'])>50){
			$article['blogTitle'] = substr($article['blogTitle'],0,47).'...';
		    }
		    if(strlen($article['summary'])>100){
			$article['summary'] = substr($article['summary'],0,97).'...';
		    }
		    if(!isset($article['blogImageURL']) || $article['blogImageURL']==''){
			$article['blogImageURL'] = '/public/mobile5/images/defaut-mobile-image-prep-tip.jpg';
		    }
		?>
		
		<?php if($i==2){ ?>
                <div class="clearfix" id="moreArticleLink">
                    	<a href="javascript:void(0);" onClick="showAllArticles();" class="btn btn-default btn-full" style="font-size:70%"><i class="msprite more-icn"></i> Top 5 NEWS &amp; ARTICLES</a>
                </div>		    
		<?php } ?>
		
                <article id="popularArticle<?=$i?>" style="cursor: pointer;<?php if($i>1){ echo 'display:none;';} ?>" class="clearfix content-inner" onClick="trackEventByGAMobile('MOBILE_ARTICLE_LINK_CLICK_FROM_HOMEPAGE'); window.location='<?=$article['url']?>'">
                    <figure class="article-thumb flLt"><img width=70 src="<?=$article['blogImageURL']?>" /></figure>
                    <div class="article-details">
                        <strong><?=$article['blogTitle']?></strong>
                        <p><?=$article['summary']?></p>
                    </div>
                </article>
		<?php } ?>

                <div class="clearfix" id="allArticleLink" style="display: none;">
                    	<a href="javascript:void(0);" onClick="trackEventByGAMobile('MOBILE_VIEW_ALL_ARTICLE_CLICK_FROM_HOMEPAGE'); redirectArticle('popular','<?=$articleURL?>' , '<?=$subcat?>');" class="btn btn-default btn-full" style="font-size:70%">View All Articles</a>
                </div>		    
		
            </section>
<?php } ?>
