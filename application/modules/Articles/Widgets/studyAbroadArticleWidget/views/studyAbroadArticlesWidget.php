<?php
	$uniqId = 'ds2fr';
	$numberOfSlides = 3;
	$art_data = json_decode($articlesData);
	$articles = $art_data[0];
	$heading = $art_data[1];
?>
<div class="widget-wrap clearwidth" style="margin-top:10px;">
    <div class="institute-head clearwidth">
        <h3 class="font-14 flLt"><?php echo $heading; ?></h3>
        <?php if($numberOfSlides > 1) { ?>
			<div class="next-prev flRt">
				<span id="recoSlide<?php echo $uniqId; ?>" class="flLt slider-caption">1 of <?php echo $numberOfSlides; ?></span>
				<a id="prev<?php echo $uniqId; ?>" href="javascript:void(0)" class="prev-box" onclick="slideRecoLeft('<?php echo $uniqId; ?>', true);"><i id="prevIcon<?php echo $uniqId; ?>" class="common-sprite prev-icon"></i></a>
				<a id="next<?php echo $uniqId; ?>" href="javascript:void(0)" class="next-box active" onclick="slideRecoRight('<?php echo $uniqId; ?>', true);"><i id="nextIcon<?php echo $uniqId; ?>" class="common-sprite next-icon-active"></i></a>
			</div>
        <?php } ?>
    </div>
    <div class="institute-list clearwidth" style="width:629px; overflow: hidden;">
    	<ul id="slideContainer<?php echo $uniqId; ?>" style="width: 2000px; position: relative; left: 0px;">
            <?php
            $count = 0;
            foreach($articles as $key => $data) {
                $count++;
                
            ?>
            <li <?php if($count % 3 == 0) { echo 'class="last-item"'; } ?>>
				<div class="carausel-details carausel-details-links gaTrack" gaParams="ABROAD_COURSE_PAGE,ArticlesWidget,<?=$data->content_id?>" style="cursor: pointer">
					<h3 class="article-tupple-title"><a href="<?php echo $data->contentURL; ?>" title="<?php echo $data->strip_title; ?>"><?php echo cutString($data->strip_title,40);?></a></h3>
					<div class="university-thumb">
						<a href="<?php echo $data->contentURL; ?>" title="<?php echo $data->strip_title; ?>"><img class="lazy" src="" data-original="<?php echo str_replace("_s","_172x115",$data->contentImageURL); ?>" alt="<?php echo htmlentities($data->strip_title); ?>" height="114" width="172" /></a>
					</div>
                    <div class="article-carausel-content clearfix">
						<p style="height:50px; overflow: hidden;"><?php echo (strip_tags($data->summary));?></p>
						
						<div class="comment-section">
							<?php
							if($data->commentCount > 0)
							{
							?>
								<span class="article-cmnts"><i class="listing-sprite comment-icon"></i><?php echo $data->commentCount;?> comments</span> |
							<?php
							}
							?>
							<span class="article-views"><?php echo $data->viewCount;?> views</span>
						</div>
						<a class="read-more" href="<?php echo $data->contentURL;?>">Read more <i class="listing-sprite read-more-icon"></i></a>
					</div> 
				</div>
            </li>
            <?php
            }
            ?>
        </ul>    
    </div>
    <div class="slide-bullets clearwidth">
        <ul class="slider-control">
            <?php
		if($numberOfSlides > 1) {
		    $slideButtonHTML = '<li id="recoSliderButton1'.$uniqId.'" class="active" onclick="changeRecoSlide(1, \''.$uniqId.'\', true);"></li>';
		}
                
                if($numberOfSlides >= 2) {
		    $slideButtonHTML .= '<li id="recoSliderButton2'.$uniqId.'" onclick="changeRecoSlide(2, \''.$uniqId.'\', true);"></li>';
		}
                
		if($numberOfSlides == 3) {
		    $slideButtonHTML .= '<li id="recoSliderButton3'.$uniqId.'" onclick="changeRecoSlide(3, \''.$uniqId.'\', true);"></li>';
		}
                
                echo $slideButtonHTML;
	    ?>            
        </ul>
    </div>
</div>

<script>
var slideWidth = 629;
var uniqueId = '<?php echo $uniqId; ?>';

if (typeof(numSlides) == 'undefined') {
	numSlides = {};
	currentSlide = {};
	firstSlide = {};
	lastSlide = {};
}

numSlides[uniqueId] = <?php echo $numberOfSlides; ?>;
currentSlide[uniqueId] = 0;
firstSlide[uniqueId] = 0;
lastSlide[uniqueId] = (numSlides[uniqueId] - 1) * (-1);
</script>