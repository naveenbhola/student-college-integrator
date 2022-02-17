<div class="carausel-section article-carausel clearfix">
	<div class="arrow-box">
	<a href="javascript:void(0);" class="home-sprite prev-scroll" title="Previous" onclick="enterpriseSlideLeft();" id="prevButton"></a>
</div>
<div class="carausel-content" style="overflow: hidden;">
	<div class="clearfix">
		<i class="home-sprite article-icon flLt"></i><h2 class="flLt">Most popular student guides</h2>
	</div>
	<ul style="width:2398px; position: relative; left:0px;" id="slideContainer" onmouseover="pauseSlider();" onmouseout="resumeSlider();">
		<?php for ($j=0;$j<3;$j++){ ?>
			<li style="float: left; width:798px;">
			    <?php if(is_array($topGuides) && count($topGuides)>0){
			    	$startLimit = $j*3;
			    	$endLimit = ($j+1)*3;
				    for ($i=$startLimit;$i<$endLimit;$i++){
				    	$commentCountString='';
				    	$viewCountString='';
			    		if($topGuides['guides'][$i]['commentCount'] > 0){
                            $commentCountString = $topGuides['guides'][$i]['commentCount'].' comment';
                            $commentCountString .= ($topGuides['guides'][$i]['commentCount'] > 1 ? 's':'');
                        }
                        if($topGuides['guides'][$i]['viewCount'] > 0){
                            $viewCountString .= $topGuides['guides'][$i]['viewCount'].' view';
                            $viewCountString .= ($topGuides['guides'][$i]['viewCount'] > 1 ? 's':'');
                        }
                        $imgURL = str_replace("_s","_172x115",$topGuides['guides'][$i]['contentImageURL']);
	                    if($j === 0) {
	                      $lazyClass = 'class="lazy"';
	                    }else{
	                      $lazyClass = 'class="lazyImg"';
	                    }
	                    $dataSrc = 'data-original="'.$imgURL.'"';
						?>
					    <div class="carausel-details <?php if($i==$endLimit-1){echo 'last';} ?>" style="cursor: pointer;width:266px;<?php if($i==$endLimit-1){echo 'border-right:1px solid #ccc';} ?>" onClick=" studyAbroadTrackEventByGA('ABROAD_HOME_PAGE', 'PopularGuides' , '<?=$topGuides['guides'][$i]['content_id']?>'); window.location='<?php echo $topGuides['guides'][$i]['contentURL'];?>';">
						    <h3 class="article-tupple-title"><?php echo htmlentities(formatArticleTitle($topGuides['guides'][$i]['strip_title'],53));?></h3>
						    <div class="university-thumb"><img title="<?php echo htmlentities($topGuides['guides'][$i]['strip_title']); ?>" alt="<?php echo htmlentities($topGuides['guides'][$i]['strip_title']); ?>" <?php echo $lazyClass.' '.$dataSrc;?> /></div>
						    <div class="article-carausel-content clearfix">
							    <p><?php
							    $summary = strip_tags($topGuides['guides'][$i]['summary']);
							    echo formatArticleTitle($summary,240); ?></p>
							    <p class="added-txt"><span class="article-cmnts">
	                			<i class="home-sprite home-comment-icon"></i>
	                			<?php if($topGuides['guides'][$i]['commentCount'] > 0) {  echo $commentCountString; }?>
	                		    <?php if($topGuides['guides'][$i]['commentCount'] > 0 && $topGuides['guides'][$i]['viewCount'] > 0 ) { ?></span> | <span class="articles-views"><?php } ?>
	                		    <?php if($topGuides['guides'][$i]['viewCount'] > 0) { echo $viewCountString;} ?></span></p>
							</div>
						    <?php if($topGuides['guides'][$i]['is_downloadable']=="yes"){
						    	$inputData = json_encode(array(base64_encode($topGuides['guides'][$i]['download_link']),$topGuides['guides'][$i]['content_id'],$topGuides['guides'][$i]['strip_title']." Guide",13,'downloadGuide',$topGuides['guides'][$i]['type']));
						    ?>
						    <div class="articledwnldbtn" >
	                  			<a class="button-style dwnld-pdf downloadbtn" href="javascript:void(0);" style="vertical-align:middle;width:100%;" onclick='downloadHomeGuide(<?=$inputData?>);'>
	                            <i class="home-sprite home-pdf-icon"></i>
	                          	<span style="font-weight:bold" class="font-14">Download Guide</span>
	                   			</a>
	                   		</div>
							<?php  } ?>
	                   		<?php if($topGuides['downloadCounts'][$topGuides['guides'][$i]['content_id']] > 50) {?>
	                       	<p class="articledwntxt"><?php echo  $topGuides['downloadCounts'][$topGuides['guides'][$i]['content_id']];?> people downloaded this guide</p>
							<?php } ?>
					    </div>
				    <?php }
			    }?>
			</li>
		<?php } ?>
		<div class="clearFix"></div>
	</ul>
	<div class="clearwidth">
	    <ol class="carausel-bullets">
		<li id="slidecontrol1" class="active" style="cursor:pointer;" onclick="enterpriseSlide(1);"></li>
		<li id="slidecontrol2" style="cursor:pointer;" onclick="enterpriseSlide(2);"></li>
		<li id="slidecontrol3" style="cursor:pointer;" onclick="enterpriseSlide(3);"></li>
	    </ol>
	</div>
</div>
<div class="arrow-box">
    <a href="javascript:void(0)" class="home-sprite next-scroll-active" title="Next" onclick="enterpriseSlideRight();" id="nextButton"></a>
</div>
</div>
<script>
var slideWidth = 798;
var numSlides = 3;
var currentSlide = 0;
var intervalPeriod = 5000;
var interval;
</script>
