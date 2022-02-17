 <?php
 $ImgSrcArray =array();
 if(count($mediaData['videos'])>0 || count($mediaData['photos'])>0) {?>
<div class="other-details-wrap clear-width photos-vids-sec">
	<h2 class="mb14">Photos and videos of this <?php echo $collegeOrInstituteRNR;?></h2>
	<div class="photo-vid-tab clear-width">
		<?php if(count($mediaData['videos'])>0 && count($mediaData['photos'])>0) {?>
        <a class="active photoLink"	style="cursor: default" uniqueattr="NATIONAL_COURSE_PAGE/photos" onclick="showMedia('photos');">Photos (<?php echo count($mediaData['photos']);?>)</a>
		<span>|</span> <a class="videoLink" style="cursor: pointer"	uniqueattr="NATIONAL_COURSE_PAGE/videos" onclick="showMedia('videos');">Videos (<?php echo count($mediaData['videos']);?>)</a>
        <?php } elseif(count($mediaData['photos'])>0) {?>
        <a class="active photoLink"	style="cursor: default">Photos (<?php echo count($mediaData['photos']);?>)</a>
        <?php } elseif(count($mediaData['videos'])>0){?>
       	<a class="active videoLink"	style="cursor: default">Videos (<?php echo count($mediaData['videos']);?>)</a>
        <?php }?>
       	</div>
        <?php if(count($mediaData['photos'])>0) { ?>                  
        <div id="slider_photo" class="photos slider1 clear-width">
		<div class="prevArrow">
			<div id="navPhotoPrev" class="sprite-bg slide-prev-active flLt prev" onclick="stopClick('navPhotoPrev','slider_photo',event);"></div>
		</div>
		<div class="viewport" style="width: 580px">
			<ul id ="mainMediaContainerPhoto" class="overview " <?php if(count($mediaData['photos']) <=3){ ?>
				style="width: 600px" <?php }?>>
			<?php $index =0 ;foreach($mediaData['photos'] as $photo) { ?>
			<li itemscope itemtype="http://schema.org/ImageObject" style = "width: 180px;" ><img id="ins_hdr_img<?php echo $index?>" src="<? if($index > 2) {$ImgSrcArray[$index] = $photo->getURL(); } else {echo $photo->getURL(); }?>"
					onclick="openPhotoVideoOverLay('photoOverLay',<?php echo $index?>);"
					alt="<?php echo $photo->getName()?>"
					title="<?php echo $photo->getName()?>"
					style="width: 180px;cursor: pointer"
					itemprop="contentUrl"
					></li>
				<?php $index++;}?>
			</ul>
		</div>

		<div class="nextArrow">
			<div id="navPhotoNxt" class="sprite-bg slide-next-active flLt next"	onclick="stopClick('navPhotoNxt','slider_photo',event);loadImg();"></div>
		</div>
		<?php if(count($mediaData['photos'])>3) {?>
		<div class="spacer10 clearFix"></div>
		<a class="flRt font-14" style="margin-right: 53px"
			href="javascript:void(0)"
			onclick="openPhotoVideoOverLay('photoOverLay',0)">View all <?php echo count($mediaData['photos']);?> photos</a>
	<?php }?>
	</div>

	<?php } if(count($mediaData['videos'])>0) { ?>
	   <?php  if((count($mediaData['videos'])>0) && !(count($mediaData['photos'])>0)) {?>
	        <div id="slider_video" class="videos slider1" style="display: block">
	        <?php } else {?>
	         <div id="slider_video" class="videos slider1" style="display: block">
	         <?php }?>
		<div class="prevArrow">
			<div id="navVideoPrev" class="sprite-bg slide-prev-active flLt prev"
				onclick="stopClick('navVideoPrev','slider_video',event);"></div>
		</div>
		<div class="viewport" style="width: 580px">
			<ul id = "mainMediaContainerVideo" 	class="overview " <?php if(count($mediaData['videos']) <=3){ ?>
				style="width: 600" <?php }?>>
			<?php $index=0; foreach($mediaData['videos'] as $video) {?>
			<li style = "width: 180px;"><img src="<? echo $video->getThumbURL() ?>"
					onclick="openPhotoVideoOverLay('videoOverLay',<?php echo $index;?>);"
					alt="<?php echo $video->getName()?>"
					title="<?php echo $video->getName()?>"
				       style = "width: 180px;">
				<div class="sprite-bg video-icon" onclick="openPhotoVideoOverLay('videoOverLay',<?php echo $index;?>);"></div></li>
				
				<?php $index++;}?>
				
			</ul>
		</div>
		<div class="nextArrow">
			<div id="navVideoNxt" class="sprite-bg slide-next-active flLt next"
				onclick="stopClick('navVideoNxt','slider_video',event);"></div>
		</div>
	<?php if(count($mediaData['videos'])>3) {?>
	<div class="spacer10 clearFix"></div>
		<a class="flRt font-14" style="margin-right: 53px"
			href="javascript:void(0)"
			onclick="openPhotoVideoOverLay('videoOverLay',0)">View all <?php echo count($mediaData['videos']);?> videos</a>
	<?php }?>
	</div>
 <?php } if(count($mediaData['videos'])>0) {?>
             
	      <div id="videoOverLay" style="display: none">             
          <?php $this->load->view('listing/national/videoWidgetOverlay',array('mediaData'=>$mediaData['videos']));?>
          </div> 
          <?php } if(count($mediaData['photos'])>0) {?> 
           <div id="photoOverLay" style="display: none">             
          <?php $this->load->view('listing/national/photoAndVideoWidgetOverlay',array('mediaData'=>$mediaData['photos']));?>
          </div> 
          <?php }?>
                          
              
<style>
/* Tiny Carousel */
.slider1 {
	padding: 0px;
}

.slider1 .viewport {
	float: left;
	height: 99px;
	overflow: hidden;
	position: relative;
}

.prevArrow {
	margin-left: 10px;
}

.slider1 .prevArrow .slide-prev-active {
	cursor: pointer;
	background-position: -152px -135px;
	width: 16px;
	height: 37px;
}

.slider1 .prevArrow .disable {
	cursor: default;
	background-position: -75px -77px;
	width: 16px;
	height: 37px;
}

.slider1 .nextArrow .slide-next-active {
	cursor: pointer;
	background-position: -174px -135px;
	width: 16px;
	height: 37px;
}

.slider1 .nextArrow .disable {
	cursor: default;
	background-position: -98px -77px;
	width: 16px;
	height: 37px;
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
	margin: 34px 0 0 18px;
}

.slider1 .prev {
	margin: 34px 10px 0 0px;
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
	margin:0 10px
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
	  
	if(typeof($j("#"+layerType).find(".management-layer"))!='undefined'){
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
	var posTop = ($j(window).height()/2) - ($j("#"+layerType).find(".management-layer").height()/2);
	var posLeft = ($j(window).width()/2) - ($j("#"+layerType).find(".management-layer").width()/2);;
	$j("#"+layerType).find(".management-layer").css({
	    'position':'fixed',
	    'top':posTop+'px',
	    'left':posLeft+'px',
	    'z-index':9999}).show();
	}
	 if(layerType == 'videoOverLay')
	 {
		  showThumbControll(index);
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

royalSliderInstance.st.transitionSpeed = 0;
royalSliderInstance.goTo(index);
setTimeout(function() {
	royalSliderInstance.st.transitionSpeed = 600;
}, 10);
	 
$j("#"+layerType).show();

if(typeof($j("#"+layerType).find(".management-layer"))!='undefined'){
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
var posTop = ($j(window).height()/2) - ($j("#"+layerType).find(".management-layer").height()/2);
var posLeft = ($j(window).width()/2) - ($j("#"+layerType).find(".management-layer").width()/2);;
$j("#"+layerType).find(".management-layer").css({
    'position':'fixed',
    'top':posTop+'px',
    'left':posLeft+'px',
    'z-index':9999}).show();
}
	 }
 }

function closePhotoVideoOverlay(mediaType){
	$j("#"+mediaType).find("#modal-overlay").hide();
	$j("#"+mediaType).find(".management-layer").hide();
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

$j(document).ready(function($) {


	var videoCount = <?=( empty($mediaData['videos']) ? 0 : count($mediaData['videos']));?> ;
	var photoCount = <?=( empty($mediaData['photos']) ? 0 : count($mediaData['photos']));?> ;


if(($j('#slider_photo').length >0) && ($j('#slider_video').length > 0))
{	
$j('#slider_photo').tinycarousel({ display: 3 });
$j(".videos").show();
$j(".photos").hide();
$j(".photoLink").css('cursor','pointer');
$j(".videoLink").css('cursor','default');
$j(".photoLink").removeClass('active');
$j(".videoLink").addClass('active');

$j('#slider_video').tinycarousel({ display: 3 });
$j(".photos").show();
$j(".photoLink").css('cursor','default');
$j(".videoLink").css('cursor','pointer');
$j(".videos").hide();
$j(".videoLink").removeClass('active');
$j(".photoLink").addClass('active');
}else if(!($j('#slider_photo').length >0) && ($j('#slider_video').length > 0))
{
	$j(".videos").show();
	$j(".videoLink").css('cursor','default');
	$j(".videoLink").addClass('active');
	$j('#slider_video').tinycarousel({ display: 3 });
	
}else if(($j('#slider_photo').length >0) && !($j('#slider_video').length > 0))
{
	$j(".photos").show();
	$j(".photoLink").css('cursor','default');
	$j(".photoLink").addClass('active');
	$j('#slider_photo').tinycarousel({ display: 3 });
 }

if($j('#mainMediaContainerPhoto').length>0) {
        var myWidth = $j('#mainMediaContainerPhoto').width();
        $j('#mainMediaContainerPhoto').width(myWidth+(photoCount/3));
        }
if($j('#mainMediaContainerVideo').length >0) {

      var myWidth = $j('#mainMediaContainerVideo').width();
      $j('#mainMediaContainerVideo').width(myWidth+(photoCount/3));


}

});

</script>
</div>
<?php }?>       
                           
