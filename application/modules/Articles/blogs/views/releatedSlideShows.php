<div class="more-option-box">
	<h3>More Slideshow</h3>
    <ul>
		<?php
			foreach($slideshows as $article){
		?>
    	<li>
        	<?php if($article['blogImageURL'] != ""){
					$article['blogImageURL'] = str_ireplace("_s","",$article['blogImageURL']);
				}else{
					$article['blogImageURL'] = "/public/images/articleDefault.jpg";
				}
			?>
			<div class="figure"><a href="<?=$article['url']?>" target="_blank"><img src="<?=$article['blogImageURL']?>" width="122" height="63"/></a></div>
            <p>
            	<a href="<?=$article['url']?>" target="_blank" title="<?=(html_escape($article['blogTitle']))?>"><?=html_escape(formatRelatedArticleTitle($article['blogTitle'],42))?></a>
			</p>
        </li>
		<?php
			}
		?>
    </ul>
    <div class="clearFix"></div>
</div>