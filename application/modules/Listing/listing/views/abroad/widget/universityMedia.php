 <?php
  if(!empty($universityObj)){
	    $mediaData['photos'] = $universityObj->getPhotos();
	    $mediaData['videos'] = $universityObj->getVideos();
	    }
 $ImgSrcArray =array();
  if(count($mediaData['videos'])>0 || count($mediaData['photos'])>0) {?>
  <?php if($isCountryPage !=1){ ?>
<div class="widget-wrap clearwidth photos-vids-sec">
	<h2>Photos & Videos</h2>
	<div class="photo-video-tab">
		<?php if(count($mediaData['videos'])>0 && count($mediaData['photos'])>0) {?>
        <a class="active photoLink"	style="cursor: default;text-decoration: none; " onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'photoVideoTab', 'photo'); showMedia('photos');">Photos (<?php echo count($mediaData['photos']);?>)</a>
		<span>|</span> <a class="videoLink" style="cursor: pointer;text-decoration: none;" onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'photoVideoTab', 'video'); showMedia('videos');">Videos (<?php echo count($mediaData['videos']);?>)</a>
        <?php } elseif(count($mediaData['photos'])>0) {?>
        <a class="active photoLink"	style="cursor: default; text-decoration: none;">Photos (<?php echo count($mediaData['photos']);?>)</a>
        <?php } elseif(count($mediaData['videos'])>0){?>
       	<a class="active videoLink"	style="cursor: default; text-decoration: none;">Videos (<?php echo count($mediaData['videos']);?>)</a>
        <?php }?>
       	</div>
        <?php if(count($mediaData['photos'])>0) { ?>                  
        <div id="slider_photo" class="photo-video-slider photos slider1 photo-video-slider2 clearwidth clear-width">
		<?php if(count($mediaData['photos']) > 2) {?>
		<div class="prev-arrow2 flLt">
			<div id="navPhotoPrev" class="common-sprite prev-slide-actv flLt prev" onclick="stopClick('navPhotoPrev','slider_photo',event);"></div>
		</div>
		<?php }?>
		<div class="viewport" style="width: 616px">
			<ul id ="mainMediaContainerPhoto" class="overview " <?php if(count($mediaData['photos']) <=3){ ?>
				style="width: 580px" <?php }?>>
			<?php $index =0 ;foreach($mediaData['photos'] as $photo) { ?>
			<li style = "width: 300px;height: 200px;" ><img id="ins_hdr_img<?php echo $index?>" src="<? if($index > 2) {$ImgSrcArray[$index] = $photo->getThumbURL('300x200'); } else {echo $photo->getThumbURL('300x200'); }?>"
					onclick="studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'photoVideoOverlay', 'photo'); openPhotoVideoOverLay('photoOverLay',<?php echo $index?>);"
					alt="<?php echo $photo->getName()?>"
					title="<?php echo $photo->getName()?>"
					style="width: 300px; height: 200px; cursor: pointer"></li>
				<?php $index++;}?>
			</ul>
		</div>
       <?php if(count($mediaData['photos']) > 2) {?>
		<div class="next-arrow2 flRt">
			<div id="navPhotoNxt" class="common-sprite next-slide-actv flLt next"	onclick="stopClick('navPhotoNxt','slider_photo',event);loadImg();"></div>
		</div>
		<?php }?>
			<?php if(count($mediaData['photos'])>2) {?>
		<div class="spacer10 clearFix"></div>
		<a class="flRt" style="  margin-right: 17px;margin-top: 10px;"
			href="javascript:void(0)"
			onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'photoVideoOverlay', 'viewAllPhotos'); openPhotoVideoOverLay('photoOverLay',0)">View all <?php echo count($mediaData['photos']);?> photos</a>
	<?php }?>
	</div>

	<?php } if(count($mediaData['videos'])>0) { ?>
	   <?php  if((count($mediaData['videos'])>0) && !(count($mediaData['photos'])>0)) {?>
	        <div id="slider_video" class="photo-video-slider videos slider1 photo-video-slider2 clearwidth" style="display: block">
	        <?php } else {?>
	         <div id="slider_video" class="videos photo-video-slider slider1 photo-video-slider2 clearwidth" style="display: block">
	         <?php }?>
	    <?php if(count($mediaData['videos'])>2) {?>     
		<div class="prev-arrow2 flLt">
			<div id="navVideoPrev" class="common-sprite prev-slide-actv flLt prev"
				onclick="stopClick('navVideoPrev','slider_video',event);"></div>
		</div>
		<?php } ?>
		<div class="viewport" style="width: 616px">
			<ul id = "mainMediaContainerVideo" 	class="overview " <?php if(count($mediaData['videos']) <=3){ ?>
				style="width: 580" <?php }?>>
			<?php $index=0; foreach($mediaData['videos'] as $video) {?>
			<li style = "width: 300px; height: 200px;"><img src="<? echo $video->getThumbURL() ?>"
					onclick="openPhotoVideoOverLay('videoOverLay',<?php echo $index;?>);"
					alt="<?php echo $video->getName()?>"
					title="<?php echo $video->getName()?>"
				       style = "width: 300px; height: 200px;">
				<div class="common-sprite video-icon" onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'photoVideoOverlay', 'video'); openPhotoVideoOverLay('videoOverLay',<?php echo $index;?>);"></div></li>
				
				<?php $index++;}?>
				
			</ul>
		</div>
		<?php if(count($mediaData['videos'])>2) {?>
		<div class="next-arrow2 flRt">
			<div id="navVideoNxt" class="common-sprite next-slide-actv flLt next"
				onclick="stopClick('navVideoNxt','slider_video',event);"></div>
		</div>
		<?php } ?>
	<?php if(count($mediaData['videos'])>2) {?>
	<div class="spacer10 clearFix"></div>
		<a class="flRt " style="margin-right: 17px;margin-top: 10px;"
			href="javascript:void(0)"
			onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'photoVideoOverlay', 'viewAllVideos'); openPhotoVideoOverLay('videoOverLay',0)">View all <?php echo count($mediaData['videos']);?> videos</a>
	<?php }?>
	</div>
    <?php } ?><!-- $isCountryPage != 1 -->
 <?php } if(count($mediaData['videos'])>0) { ?>
             
	      <div id="videoOverLay" style="display: none">             
          <?php $this->load->view('listing/abroad/widget/courseMediaVideoOverlay',array('mediaData'=>$mediaData['videos']));?>
          </div> 
          <?php } if(count($mediaData['photos'])>0) { ?> 
           <div id="photoOverLay" style="display: none">             
          <?php $this->load->view('listing/abroad/widget/courseMediaPhotoOverlay',array('mediaData'=>$mediaData['photos']));?>
          </div> 
          <?php }?>
                          
              
<style>
/* Tiny Carousel */
.slider1 {
	padding: 0px;
}

.slider1 .viewport {
	float: left;
	height: 200px;
	overflow: hidden;
	position: relative;
}


.slider1 .nextArrow .disable {
	cursor: default;
	background-position: -74px -49px;
	width: 17px;
	height: 37px;
}

.next-arrow2 {
    margin: 0 10px !important;
}
.prev-arrow2 {
     margin: 0 5px !important;
}
.slider1 .prev-arrow2 .disable {
	cursor: default;
    background-position: -148px -20px !important;
}

.slider1 .next-arrow2 .disable {
	cursor: default;
    background-position: -161px -21px !important;
}



.slider1 .buttons {
	background: url("../images/buttons.png") no-repeat scroll 0 0
		transparent;
	display: block;
	margin: 30px 10px 0 0;
	background-position: 0 -38px;
	text-indent: -999em;
	float: left;
	width: 39px;
	height: 37px;
	overflow: hidden;
	position: relative;
}

.slider1 .next {
	margin: 0px 0 0 5px;
}

.slider1 .prev {
	margin: 0px 10px 0 0px;
}

.slider1 .overview {
	list-style: none;
	position: absolute;
	padding: 0;
	margin: 0;
	left: 0 top:  0;
}

.slider1 .overview li {
	float: left;
	height: 99px;
	cursor: pointer;
	position: relative;
	overflow:hidden;
	margin:0 4px
}

.video-icon {
    left: 41% !important;
    top: 41% !important;
}
</style>
	<script type="text/javascript">
		
  var ImgArray = new Array();
  <?php foreach ($ImgSrcArray as $key => $src ){?>
  ImgArray[<?php echo $key?>] = "<?php echo $src ?>";
  <?php }?>
  var ImgLoadedFlg =false;
  function loadImg()
  {
	  if(ImgLoadedFlg)
	   return; 
	  for (var i=3;i<ImgArray.length;i++)
	  { 
	  $j("#ins_hdr_img"+i).attr("src",ImgArray[i]);
	  }
	  
	  ImgLoadedFlg = true;
  }
  
  function stopClick(NavId,SliderID,event)
  {   
	  if($j("#"+SliderID).find("#"+NavId).hasClass("disable"))
  		{
		 event.stopImmediatePropagation();
		 return false;
		
  		}
  }
  
 function openPhotoVideoOverLay(layerType,index){
	 
	 $j("#"+layerType).show();
	  
	if(typeof($j("#"+layerType).find(".abroad-media-layer"))!='undefined'){
	$j("#"+layerType).find("#modal-overlay").css({
	    'position':'fixed',
	    'background-color':'#000000',
	    'opacity':0.35,
	    '-ms-filter': 'progid:DXImageTransform.Microsoft.Alpha(Opacity=35)',
	    'height':$j(window).height()+'px',
	    'width':$j(window).width()+'px',
	    'top':'0px',
	    'left':'0px',
	    'z-index':999}).show();
	var posTop = 64;
	var posLeft = ($j(window).width()/2) - 235;
    $j("#"+layerType).find(".abroad-media-layer").css({
	    'position':'fixed',
	    'top':posTop+'px',
	    'left':posLeft+'px',
	    'z-index':9999}).show();
	}
	 if(layerType == 'videoOverLay')
	 {
		 openVideoLayer(index);
	 }
 	 if(layerType == 'photoOverLay')
	 {		
		
var royalSliderInstance = $j('#royalSlider').royalSlider({
    controlNavigation: 'thumbnails',
    slideshowAutoStart:false,
    numImagesToPreload:6,
    arrowsNavAutohide: false,
    arrowsNavHideOnTouch: false,
    startSlideId: index,
   thumbs: {
    		fitInViewport: true,
    		controlsInside : false,
    		spacing: 4,
    		arrowsAutoHide: false
  },
   globalCaption:true
  }).data("royalSlider");

$j('.rsOverflowt').attr('style', function(i, style)
		{
		    return style.replace(/display[^;]+;?/g, '');
		});
royalSliderInstance.st.transitionSpeed = 0;
royalSliderInstance.goTo(index);
setTimeout(function() {
	royalSliderInstance.st.transitionSpeed = 600;
}, 10);
	 
$j("#"+layerType).show();

if(typeof($j("#"+layerType).find(".abroad-media-layer"))!='undefined'){
	
  $j("#"+layerType).find("#modal-overlay").css({
	  'position':'fixed',
	  'background-color':'#000000',
	  'opacity':0.35,
	  '-ms-filter': 'progid:DXImageTransform.Microsoft.Alpha(Opacity=35)',
	  'height':$j(window).height()+'px',
	  'width':$j(window).width()+'px',
	  'top':'0px',
	  'left':'0px',
	  'z-index':999}).show();
   var posTop = 64;
   var posLeft = ($j(window).width()/2) - ($j("#"+layerType).find(".abroad-media-layer").width()/2);;
   $j("#"+layerType).find(".abroad-media-layer").css({
	  'position':'fixed',
	  'top':posTop+'px',
	  'left':posLeft+'px',
	  'z-index':9999}).show();
 }
	 }
 }

function closePhotoVideoOverlay(mediaType){
	$j("#"+mediaType).find("#modal-overlay").hide();
	$j("#"+mediaType).find(".abroad-media-layer").hide();
	$j("#"+mediaType).hide();
}
function showMedia(type)
{
    if(type == "photos")
    {
        $j(".photos").show();
        $j(".photoLink").css('cursor','default');
        $j(".videoLink").css('cursor','pointer');
        $j(".videos").hide();
        $j(".videoLink").removeClass('active');
        $j(".photoLink").addClass('active');
    }
    else
    {    
        	$j(".videos").show();
    	  	$j(".photos").hide();
        	$j(".photoLink").css('cursor','pointer');
            $j(".videoLink").css('cursor','default');
            $j(".photoLink").removeClass('active');
            $j(".videoLink").addClass('active');
    }
}
function initializeMediaStuff()
{ 
  var videoCount = <?=( empty($mediaData['videos']) ? 0 : count($mediaData['videos']));?> ;
  var photoCount = <?=( empty($mediaData['photos']) ? 0 : count($mediaData['photos']));?> ;
  if(($j('#slider_photo').length >0) && ($j('#slider_video').length > 0))
  {	
   $j('#slider_photo').tinycarousel({ display: 2 });
   $j(".videos").show();
   $j(".photos").hide();
   $j(".photoLink").css('cursor','pointer');
   $j(".videoLink").css('cursor','default');
   $j(".photoLink").removeClass('active');
   $j(".videoLink").addClass('active');
   
   $j('#slider_video').tinycarousel({ display: 2 });
   $j(".photos").show();
   $j(".photoLink").css('cursor','default');
   $j(".videoLink").css('cursor','pointer');
   $j(".videos").hide();
   $j(".videoLink").removeClass('active');
   $j(".photoLink").addClass('active');
  }
  else if(!($j('#slider_photo').length >0) && ($j('#slider_video').length > 0))
  {
	$j(".videos").show();
	$j(".videoLink").css('cursor','default');
	$j(".videoLink").addClass('active');
	$j('#slider_video').tinycarousel({ display: 2 });
	
  }
  else if(($j('#slider_photo').length >0) && !($j('#slider_video').length > 0))
  {
	$j(".photos").show();
	$j(".photoLink").css('cursor','default');
	$j(".photoLink").addClass('active');
	$j('#slider_photo').tinycarousel({ display: 2 });
  }

  if($j('#mainMediaContainerPhoto').length>0)
  {
        var myWidth = $j('#mainMediaContainerPhoto').width();
        $j('#mainMediaContainerPhoto').width(myWidth+(photoCount/3));
  }
  if($j('#mainMediaContainerVideo').length >0)
  {
      var myWidth = $j('#mainMediaContainerVideo').width();
      $j('#mainMediaContainerVideo').width(myWidth+(photoCount/3));
  }
}
initializeMediaStuff();
</script>
</div>
<?php }?>       
                           
