<?php 
$mediaForDisplay = array();
$headerImageCount =0;
$photoCount =0;
$videoCount =0;
$index = 1;
$urlArray = array();
$ImgSrcArray =array();
foreach($institute->getHeaderImages() as $image){
   	$imgURL = $image->getFullURL();
	if(!empty($imgURL)){
    $media['mediaType'] = 'photo';
	$media['url'] =  $imgURL;
	$media['thumb'] = $image->getThumbURL();
	$media['name'] = "Photo of ".html_escape($institute->getName()).' '.(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?', '.$currentLocation->getLocality()->getName().", ":", ").$currentLocation->getCity()->getName()." ".$index;
	$index ++;
	$headerImageCount ++;
	$mediaForDisplay [] = $media;
	$urlArray['photos'] []= $media;
	}
}

foreach ($mediaData['photos'] as $photo)
{   
    $photoURL = $photo->getURL();
	if(!empty($photoURL)) {
	$media['mediaType'] = 'photo';
	$media['url'] = $photoURL;
	$media['thumb'] = $photo->getThumbURL();
	$media['name'] = $photo->getName();
	$photoCount ++;
	$mediaForDisplay [] = $media;
	$urlArray['photos'] []= $media;
	}
}

foreach ($mediaData['videos'] as $video)
{   
 	$videoURL = $video->getURL();
    if(!empty($videoURL)) {
	$media['mediaType'] = 'video';
	$media['url'] = $videoURL;
	$media['thumb'] = $video->getThumbURL();
	$media['name'] = $video->getName(); 
 	$videoCount ++;
	$mediaForDisplay [] = $media;
	$urlArray['videos'] [] = $media;
   }
}

?>


<?php if(($photoCount+$headerImageCount) >0 || $videoCount >0) {?>
<!-- PHOTO / VIDEO SLIDER WIDGET STARTS	-->
<div  id= "inst-slider" class="ins-slider-cont clear-width photos-videos-sec other-details-wrap">
	<h2 class="mb14">Photos and videos of this <?php echo $collegeOrInstituteRNR;?></h2>
	<div class="prevArrow">
			<div id="navPhotoPrev" class="sprite-bg slide-prev-active flLt prev" onclick="stopClick('navPhotoPrev','inst-slider',event);" uniqueattr="NATIONAL_INSTITUTE_PAGE/HeaderImageWidget_slide_to_previous"></div>
		</div>
    						<div class="viewport insSlider-img-box" style="width:615px;">
    						<ul class ="overview">
    						<?php $indexPhoto =-1;$indexVideo =-1 ; $index_src =1; foreach($mediaForDisplay as $media) { ?>
    						<li><img id="ins_hdr_img<?php echo $index_src?>" src="<?php if($media['mediaType'] == 'photo') {if($index_src > 2){$ImgSrcArray[$index_src] = $media['url'];}else{ echo $media['url'];} $type ="photo"; $indexPhoto ++;} else {if($index_src > 2){$ImgSrcArray[$index_src] = $media['thumb'];}else {echo $media['thumb'];} $type = "video"; $indexVideo++;} ?>"
									alt="<?php echo $media['name']?>"
									title="<?php echo $media['name']?>"
									<?php if($type == "photo"){?>
									onclick="openPhotoVideoOverLay('photoOverLay',<?php echo $indexPhoto?>);"
									<?php }else {?>
									onclick="openPhotoVideoOverLay('videoOverLay',<?php echo $indexVideo;?>);"
									<?php }?>
									uniqueattr="NATIONAL_INSTITUTE_PAGE/HeaderImageWidget<?php echo $media['name'];?>"
									style="width: 304px;cursor: pointer">
									<?php if($type != "photo") {?>
									<div class="sprite-bg video-icon" onclick="openPhotoVideoOverLay('videoOverLay',<?php echo $indexVideo;?>);"></div>
									<?php }?>
									</li>
						  	<?php $index_src++; /*echo "<br>".$type ;*/}?>		
						  	</ul>
<ul class="photo-vid-caption">
								<?php if($headerImageCount+$photoCount >0) {?>
                                <li><a href="javascript:void(0)" onclick="openPhotoVideoOverLay('photoOverLay',0);" uniqueattr="NATIONAL_INSTITUTE_PAGE/HeaderImageWidget_Photos_IN_RoyalSlider"><?php echo $headerImageCount+$photoCount;?> Photos</a></li>
                                <?php } if($headerImageCount+$photoCount >0 && $videoCount >0) {?>
                                <li><span>|</span></li>
                                <?php } if($videoCount >0) { ?>
                                <li><a href="javascript:void(0)" onclick="openPhotoVideoOverLay('videoOverLay',0); " uniqueattr="NATIONAL_INSTITUTE_PAGE/HeaderImageWidget_Videos_IN_RoyalSlider"><?php echo $videoCount;?> Videos</a></li>
                               <?php }?>
                                </ul>

                                </div>
                          <div class="nextArrow">
			<div id="navPhotoNxt" class="sprite-bg slide-next-active flLt next"	onclick="stopClick('navPhotoNxt','inst-slider',event);loadImg();" uniqueattr="NATIONAL_INSTITUTE_PAGE/HeaderImageWidget_slide_to_next"></div>
		</div>
                                </div>
                               <?php  if($headerImageCount+$photoCount>0) {?>
           <div id="photoOverLay" style="display: none">             
          <?php $this->load->view('listing/national/photoAndVideoWidgetOverlay',array('urlArray'=>$urlArray['photos'], 'mediaRequest' => 'institute'));?>
          </div>
                      
          <?php } if($videoCount >0) {?> 
 		  <div id="videoOverLay" style="display: none">             
          <?php $this->load->view('listing/national/videoWidgetOverlay',array('urlArray'=>$urlArray['videos'], 'mediaRequest' => 'institute' ));?>
          </div> 
          <?php }?>
                                     
                                
                                <!-- PHOTO / VIDEO SLIDER WIDGET ENDS	-->
<style>                              
/* Tiny Carousel */
.ins-slider-cont {
	padding: 0 0 0 10px;
}

.ins-slider-cont  .viewport {
	float: left;
	height: 210px;
	overflow: hidden;
	position: relative;
}

.ins-slider-cont .prevArrow .slide-prev-active {
	cursor: pointer;
	background-position: -152px -135px;
	width: 16px;
	height: 37px;
}

.ins-slider-cont .prevArrow .disable {
	cursor: default;
	background-position: -75px -77px;
	width: 16px;
	height: 37px;
}

.ins-slider-cont .nextArrow .slide-next-active {
	cursor: pointer;
	background-position: -174px -135px;
	width: 16px;
	height: 37px;
}

.ins-slider-cont .nextArrow .disable {
	cursor: default;
	background-position: -98px -77px;
	width: 16px;
	height: 37px;
}

.ins-slider-cont .buttons {
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

.ins-slider-cont .next {
	margin: 84px 0 0 10px;
}

.ins-slider-cont .prev {
	margin: 84px 10px 0 0px;
}

.ins-slider-cont .overview {
	list-style: none;
	position: absolute;
	padding: 0;
	margin: 0;
	left: 0 top:  0;
}

.ins-slider-cont .overview li {
	float: left;
	margin: 0 2px;
	height: 210px;
	cursor: pointer;
	position: relative;
	width: 304px; overflow:hidden;
	
}
</style>                                


  <script>
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
</script>
<?php }?>