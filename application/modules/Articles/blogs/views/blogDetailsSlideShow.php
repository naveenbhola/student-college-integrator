<div class="clearFix"></div>
<div class="bxslider">
<?php
	$blogInfo[0]['blogSlideShow'] = json_decode($blogInfo[0]['blogSlideShow'],true);
	foreach($blogInfo[0]['blogSlideShow'] as $key=>$sn){
?>
<div class="slide-show-cont" style="width:634px;">
	<h3><?=html_escape($sn['title'])?></h3>
    <p><?=html_escape($sn['subTitle'])?></p>
    
    <div class="slide-box">
    	<div class="top-row">
			<?php if($key != 0){
			?>
				<a href="#" class="prev-icn" onclick="slider.goToPreviousSlide();return false;">Prev</a>
			<?php
			}else{
			?>
				<a href="#" class="prev-icn" onclick="slider.goToPreviousSlide();return false;" style="visibility:hidden">Prev</a>
			<?php
			}
			?>
        	
            <div class="photo-caption"><?=($key+1)?> of <?=count($blogInfo[0]['blogSlideShow'])?> photos</div>
			<?php if($key != (count($blogInfo[0]['blogSlideShow'])-1)){
			?>
            <a href="#" class="next-icn" onclick="slider.goToNextSlide();return false;">Next</a>
			<?php
			}else{
			?>
			<a href="#" class="next-icn" onclick="slider.goToNextSlide();return false;" style="visibility:hidden">Next</a>
			<?php
			}
			?>
        </div>
        <div class="clearFix"></div>
        <div class="slide-view-cont">
			<?php if($key != 0){
			?>
        	<div class="prev-scroll"  onclick="slider.goToPreviousSlide();return false;">&nbsp;</div>
			<?php
			}
			?>
			<div style="background:url(<?=$sn['image']?>) no-repeat top center;width:638px;height:400px">
        	&nbsp;
			</div>
			<?php if($key != (count($blogInfo[0]['blogSlideShow'])-1)){
			?>
            <div class="next-scroll" onclick="slider.goToNextSlide();return false;">&nbsp;</div>
			<?php
			}
			?>
        </div>
        
        
        <div class="slide-details">
		<p><?=addAltText($blogInfo[0]['blogTitle'],$sn['description']);?></p>
		<div class="clear_B">&nbsp;</div>
			<?php if($key != 0){
			?>
			<div class="flLt"><a href="#"  onclick="slider.goToPreviousSlide();return false;">&lt; Previous</a></div>
			<?php
			}
			?>
			<?php if($key != (count($blogInfo[0]['blogSlideShow'])-1)){
			?>
			<div class="flRt"><a href="#" onclick="slider.goToNextSlide();return false;">Next &gt;</a></div>
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
<?php 
	$this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'C_LAA'));
?>
<script>
function startSlideShow(){
	if($j.fn.bxSlider) {
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
}
</script>
