<?php	
	$newsImage = isset($newsImage) && $newsImage != '' ? $newsImage : '/public/images/foreign-edu-calender.jpg';	
	$newsCaption = isset($newsCaption) && $newsCaption != '' ? $newsCaption : 'Featured Scholarships';		
	$newsPosition = isset($newsPosition) &&  $newsPosition!= '' ?  $newsPosition : 'left';
	$class = $newsPosition == 'left' ? 'float_L' : 'float_R';
    $newsCaption = 'Articles';
    if(isset($blogs['total']) && count($blogs['results']) > 0 ) {
?>
<div>
	<div class="careerOptionPanelBrd">
		<div class="careerOptionPanelHeaderBg">
			<h5><span class="blackFont fontSize_13p">Articles</span></h5>
		</div>
		<div style="line-height:5px">&nbsp;</div>		
		<div class="mar_full_10p" style="padding:10px 0px;display:block;<?php echo  isset($articlesPanelHeight)? 'height:'.$articlesPanelHeight .'px;' : '';?>" id="blogsPlace">
			<div id="articlesBlock">
					<?php 
						$CI_Instance = & get_instance();
						$clientWidth =  $CI_Instance->checkClientData();
						$characterLength = 250;
						foreach($blogs['results'] as $blog) {
							$blogId = isset($blog['blogId']) ? $blog['blogId'] : '';
							$blogTitle = isset($blog['blogTitle']) ? $blog['blogTitle'] : '';
							$blogUrl = isset($blog['url']) ? $blog['url'] : '';
					?>
                    <div class="normaltxt_11p_blk arial">
                        <div style="margin-bottom:2px" class="quesAnsBullets">
	                        <a class="fontSize_12p" href="<?php echo $blogUrl; ?>" title="<?php echo $blogTitle; ?>"><?php echo (strlen($blogTitle)>$characterLength)?substr($blogTitle,0,$characterLength-3)."...":$blogTitle; ?></a>
                        </div>
                        <div style="line-height:10px">&nbsp;</div>
                    </div>
					<?php
						}
                        if(count($blogs['results']) < $blogs['total']) {
                            $urlParams = '';
                            if($categoryId > 1) {
                                $urlParams .= 'category='. $categoryId;
                            }
                            if($urlParams != '') {$urlParams .='&';}
                            if($countryId > 1 && strpos($countryId, ',')=== false) {
                                $urlParams .= 'country='. $countryId;
                            }
                            if(isset($selectedExamId )) {
                                $urlParams .= 'type=exam&parent='. $selectedExamId;
                            }
                    ?>
                   <div align="right"><a href="/blogs/shikshaBlog/showArticlesList?<?php echo $urlParams .'&c='. rand(); ?>">View All</a></div> 
                    <?php
                        }
					?>
                </div>
		</div>		
	</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<?php
    }
?>
