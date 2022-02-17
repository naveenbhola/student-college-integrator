<?php $marketingArr = array(1563); ?>
<div class="featured-article-tab">
	<ul>
	<?php if(!in_array($userId, $marketingArr)){?>
	    <a href="/home/HomePageCMS/index/banner"><li class="<?=($pageType == 'banner' ? 'active' : '')?>">Cover Banner</li></a>
	    <a href="/home/HomePageCMS/index/featured"><li class="<?=($pageType == 'featured' ? 'active' : '')?>">Text Ads</li></a>
	    <a href="/home/HomePageFeaturedCollegeCMS/featuredCollegeIndex/featuredCollege"><li class="<?=($pageType == 'featuredCollege' ? 'active' : '')?>">Featured Colleges</li></a>
	    <a href="/home/HomePageCMS/index/article"><li class="<?=($pageType == 'article' ? 'active' : '')?>">Articles</li></a>
	    <?php } ?>
	    <a href="/home/HomePageMarketingCMS/marketingContentIndex/marketingContent"><li class="<?=($pageType == 'marketingContent' ? 'active' : '')?>">Marketing Content</li></a>
	    <a href="/home/HomePageCMS/index/testimonials"><li class="<?=($pageType == 'testimonials' ? 'active' : '')?>">Testimonials</li></a>
	</ul>
</div>
