<?php if(count($articles) > 2){ ?>
<div class="exam-right-wid" style="padding:0; width:245px; margin-bottom: 20px;">
            	<div class="popular-article-head">
                	<i class="exam-sprite popular-article-icon"></i><strong>Popular Articles on <?=$examName?></strong>
                </div>
                <div class="popular-article-detail">
                	<ul>
			<?php
			$class = ''; $i = 0;
			foreach($articles as $relatedAticleWidgetData) {
				$i++;
				if($i==6){
						$class = 'last';
				}
			?>
                        <li class=<?=$class?>>
                        	<a href="<?=$relatedAticleWidgetData['url']?>" target="_blank"><?php if(strlen($relatedAticleWidgetData['blogTitle']) > 60){$blogTitle = substr($relatedAticleWidgetData['blogTitle'], 0, 60).'..';} else $blogTitle = $relatedAticleWidgetData['blogTitle']; echo $blogTitle?></a>
                            <i class="exam-sprite article-comment-icon"></i><?php echo $relatedAticleWidgetData['msgCount']; ?> comments <span style="margin:0 4px;"> | </span><?php echo $relatedAticleWidgetData['blogView']; ?> views
                        </li>
			<?php } ?>
                    </ul>
                </div>
                <div class="clearFix"></div>
</div>
<?php } ?>