        <div class="carausel-section article-carausel clearfix">
		
        	<div class="arrow-box">
			<a href="javascript:void(0)" class="home-sprite prev-scroll" title="Previous" onclick="enterpriseSlideLeft();" id="prevButton"></a>
		</div>
		
		<div class="carausel-content" style="overflow: hidden;">
		    
			<div class="clearfix">
				<i class="home-sprite article-icon flLt"></i><h2 class="flLt">Articles related to study abroad</h2>
			</div>
		    
			<ul style="width:2398px; position: relative; left:0px;" id="slideContainer" onmouseover="pauseSlider();" onmouseout="resumeSlider();">
				<li id="slide1" style="float: left; width:798px;">
				    <?php if(is_array($articles) && count($articles)>0){
					    for ($i=0;$i<4;$i++){ ?>
						    <div class="carausel-details <?php if($i==3){echo 'last';} ?>" style="cursor: pointer" onClick=" studyAbroadTrackEventByGA('ABROAD_HOME_PAGE', 'PopularArticles' , '<?=$articles[$i]['content_id']?>'); window.location='<?php echo $articles[$i]['contentURL'];?>';">
							    <h3 class="article-tupple-title"><?php echo cutString($articles[$i]['strip_title'],53);?></h3>
							    <div class="university-thumb"><img title="<?=$articles[$i]['strip_title']?>" alt="<?=$articles[$i]['strip_title']?>" src="<?php echo str_replace("_s","_172x115",$articles[$i]['contentImageURL']);?>" /></div>
							    <div class="article-carausel-content clearfix">
								    <p style="height:50px; overflow: hidden;"><?php echo cutString(strip_tags($articles[$i]['summary']),80);?></p>
								    <p class="added-txt">Added <?php echo makeRelativeTime($articles[$i]['created']);?></p>
								    <a class="knw-more" href="<?php echo $articles[$i]['contentURL'];?>">Know More</a>
							    </div>
						    </div>
						    
					    <?php }			
				    }
				    ?>
				</li>

				<li id="slide2" style="float: left; width:798px;">
				    <?php if(is_array($articles) && count($articles)>0){
					    for ($i=4;$i<8;$i++){ ?>
						    <div class="carausel-details <?php if($i==7){echo 'last';} ?>" style="cursor: pointer" onClick="studyAbroadTrackEventByGA('ABROAD_HOME_PAGE', 'PopularArticles' , '<?=$articles[$i]['content_id']?>'); window.location='<?php echo $articles[$i]['contentURL'];?>';">
							    <h3 class="article-tupple-title"><?php echo cutString($articles[$i]['strip_title'],53);?></h3>
							    <div class="university-thumb"><img title="<?=$articles[$i]['strip_title']?>" alt="<?=$articles[$i]['strip_title']?>" src="<?php echo str_replace("_s","_172x115",$articles[$i]['contentImageURL']);?>" /></div>
							    <div class="article-carausel-content clearfix">
								    <p style="height:50px; overflow: hidden;"><?php echo cutString(strip_tags($articles[$i]['summary']),80);?></p>
								    <p class="added-txt">Added <?php echo makeRelativeTime($articles[$i]['created']);?></p>
								    <a class="knw-more" href="<?php echo $articles[$i]['contentURL'];?>">Know More</a>
							    </div>
						    </div>
						    
					    <?php }			
				    }
				    ?>
				</li>

				<li id="slide3" style="float: left; width:798px;">
				<?php if(is_array($articles) && count($articles)>0){
					for ($i=8;$i<12;$i++){ ?>
						<div class="carausel-details <?php if($i==11){echo 'last';} ?>" style="cursor: pointer" onClick="studyAbroadTrackEventByGA('ABROAD_HOME_PAGE', 'PopularArticles' , '<?=$articles[$i]['content_id']?>'); window.location='<?php echo $articles[$i]['contentURL'];?>';">
							<h3 class="article-tupple-title"><?php echo cutString($articles[$i]['strip_title'],53);?></h3>
							<div class="university-thumb"><img title="<?=$articles[$i]['strip_title']?>" alt="<?=$articles[$i]['strip_title']?>" src="<?php echo str_replace("_s","_172x115",$articles[$i]['contentImageURL']);?>" /></div>
							<div class="article-carausel-content clearfix">
								    <p style="height:50px; overflow: hidden;"><?php echo cutString(strip_tags($articles[$i]['summary']),80);?></p>
								    <p class="added-txt">Added <?php echo makeRelativeTime($articles[$i]['created']);?></p>
								    <a class="knw-more" href="<?php echo $articles[$i]['contentURL'];?>">Know More</a>
							</div>
						</div>
						
					<?php }			
				}
				?>
				</li>
				
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
//interval = setInterval(function(){changeSlider();},intervalPeriod);	
</script>
