<div class="clearFix"></div>
<div class="bxslider">
<?php
	$description = $blogObj->getDescription();
	$slideShowCount = count($description);
	$index = 0;
	foreach($description as $key => $ssObj){
?>
<div class="slide-show-cont" style="width:634px;">
	<h3><?=html_escape($ssObj->getTitle())?></h3>
    <p><?=html_escape($ssObj->getSubtitle())?></p>
    
    <div class="slide-box">
    	<div class="top-row">
			<?php if($key != 0){
			?>
				<a href="#" class="prev-icn" onclick="goToPreviousSlideForAtricleDetailPage();return false;">Prev</a>
			<?php
			}else{
			?>
				<a href="#" class="prev-icn" onclick="goToPreviousSlideForAtricleDetailPage();return false;" style="visibility:hidden">Prev</a>
			<?php
			}
			?>
        	
            <div class="photo-caption"><?=($key+1)?> of <?php echo $slideShowCount?> photos</div>
			<?php if($key != ($slideShowCount-1)){
			?>
            <a href="#" class="next-icn" onclick="goToNextSlideForAtricleDetailPage();return false;">Next</a>
			<?php
			}else{
			?>
			<a href="#" class="next-icn" onclick="goToNextSlideForAtricleDetailPage();return false;" style="visibility:hidden">Next</a>
			<?php
			}
			?>
        </div>
        <div class="clearFix"></div>
        <div class="slide-view-cont">
			<?php if($key != 0){
			?>
        	<div class="prev-scroll"  onclick="goToPreviousSlideForAtricleDetailPage();return false;">&nbsp;</div>
			<?php
			}
			if($index < 2){ ?>
				<div class ="articleDetailsPageSlider" style="background:url(<?=$ssObj->getImage()?>) no-repeat top center;width:638px;height:400px">
        			&nbsp;
				</div>
			<?php }else{ ?>
				<div class="lazy  articleDetailsPageSlider" style="width:638px;height:400px"  data-src="background:url(<?=$ssObj->getImage()?>) no-repeat top center;width:638px;height:400px">
        	&nbsp;
			</div>
			<?php } $index++;
			?>
			<?php if($key != ($slideShowCount-1)){
			?>
            <div class="next-scroll" onclick="goToNextSlideForAtricleDetailPage();return false;">&nbsp;</div>
			<?php
			}
			?>
        </div>
        
        
        <div class="slide-details">
		<p><?=addAltText($blogObj->getTitle(), $ssObj->getDescription());?></p>
		<div class="clear_B">&nbsp;</div>
			<?php if($key != 0){
			?>
			<div class="flLt"><a href="#"  onclick="goToPreviousSlideForAtricleDetailPage();return false;">&lt; Previous</a></div>
			<?php
			}
			?>
			<?php if($key != ($slideShowCount-1)){
			?>
			<div class="flRt"><a href="#" onclick="goToNextSlideForAtricleDetailPage();return false;">Next &gt;</a></div>
			<?php
			}
			?>
	
			<div class="clearFix"></div>
        </div>
        
        
    </div>
    
</div>
<?php
	}
?>
</div>
<div class="spacer10 clearFix"></div>
<script>
function startSlideShow(){
	slider = $j('.bxslider').bxSlider({
		adaptiveHeight: true,
		mode: 'fade',
		infiniteLoop:false,
		hideControlOnEnd:true,
		controls: false,
		nextText:"Next >",
		prevText:"< Previous"
	});
}
function goToPreviousSlideForAtricleDetailPage(){
  slider.goToPreviousSlide();
}

function goToNextSlideForAtricleDetailPage(){
	slider.goToNextSlide();
  	var selectedDiv;
  	var articleDetailsPageSliderClass  =$j('.articleDetailsPageSlider');
  	articleDetailsPageSliderClass.closest('.slide-show-cont').each(function(key,ele){
    	if($j(ele).css('opacity') == 1){
	      	key +=2;
      		selectedDiv = articleDetailsPageSliderClass[key];
      		if(typeof(selectedDiv) != 'undefined'){
		      	var src = $j(selectedDiv).attr("data-src");
	      		$j(selectedDiv).attr("style", src);	
      		}
	      	return;
    	}
  	});
  	selectedDiv = articleDetailsPageSliderClass[0];
  	var src = $j(selectedDiv).attr("data-src");
  	$j(selectedDiv).attr("style", src);
}
</script>
