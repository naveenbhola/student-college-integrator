<?php
    $mediaData['photos'] = reset($consultantObj->getMedia());
    if(count($mediaData['photos'])>0) {?>
    <div class="widget-wrap clearwidth photos-vids-sec">
	<h2>Photos</h2>
        <div class="photo-video-tab">
            <a class="active photoLink" style="cursor: default; text-decoration: none;">Photos (<?php echo count($mediaData['photos']);?>)</a>
        </div>
        <div id="slider_photo" class="photo-video-slider photos slider1 clear-width">
            <div class="prevArrow">
                <div id="navPhotoPrev" class="common-sprite slide-prev-active flLt prev" onclick="stopClick('navPhotoPrev','slider_photo',event);"></div>
            </div>
            <div class="viewport" style="width: 567px">
                    <ul id ="mainMediaContainerPhoto" class="overview " <?=(count($mediaData['photos']) <=3)?'style="width: 580px"':''?>>
                    <?php
                        $index =0 ;
                        foreach($mediaData['photos'] as $photo) { ?>
                            <li style = "width: 172px;height: 115px;" >
                                <img id="ins_hdr_img<?php echo $index?>" src="<?=$photo['thumburl']?>"
                                    onclick="openPhotoVideoOverLay('photoOverLay',<?php echo $index?>);"
                                    alt="<?=$photo['name']?>"
                                    title="<?=$photo['name']?>"
                                    style="width: 172px; height: 115px; cursor: pointer">
                            </li>
                            <?php
                            $index++;
                        }
                    ?>
                    </ul>
            </div>

            <div class="nextArrow">
                    <div id="navPhotoNxt" class="common-sprite slide-next-active flLt next" onclick="stopClick('navPhotoNxt','slider_photo',event);"></div>
            </div>
            <?php if(count($mediaData['photos'])>3) {?>
            <div class="spacer10 clearFix"></div>
            <a class="flRt" style="margin-right: 53px" href="javascript:void(0)" onclick="openPhotoVideoOverLay('photoOverLay',0)">
                View all <?php echo count($mediaData['photos']);?> photos
            </a>
            <?php }?>
        </div>
	<div id="photoOverLay" style="display: none">             
	    <?php $this->load->view('consultantProfile/widgets/consultantPhotoOverlay',array('mediaData'=>$mediaData['photos']));?>
	</div> 
    </div>
    
    
<style>
/* Tiny Carousel */
.slider1 {
	padding: 0px;
}

.slider1 .viewport {
	float: left;
	height: 127px;
	overflow: hidden;
	position: relative;
}

.prevArrow {
	margin-left: 0px;
}

.slider1 .prevArrow .slide-prev-active {
	cursor: pointer;
	background-position: -94px -49px;
	width: 16px;
	height: 37px;
}

.slider1 .prevArrow .disable {
	cursor: default;
	background-position: -53px -49px;
	width: 16px;
	height: 37px;
}

.slider1 .nextArrow .slide-next-active {
	cursor: pointer;
	background-position: -117px -49px;
	width: 17px;
	height: 37px;
}

.slider1 .nextArrow .disable {
	cursor: default;
	background-position: -76px -49px;
	width: 17px;
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
	margin: 42px 0 0 18px;
}

.slider1 .prev {
	margin: 42px 10px 0 0px;
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
	var posTop = ($j(window).height()/2) - 268;
	var posLeft = ($j(window).width()/2) - 235;
    $j("#"+layerType).find(".abroad-media-layer").css({
	    'position':'fixed',
	    'top':posTop+'px',
	    'left':posLeft+'px',
	    'width':'635px',
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
var posTop = ($j(window).height()/2) - ($j("#"+layerType).find(".abroad-media-layer").height()/2);
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

function prepareRoyalSliderForPhotos(){
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

}


</script>
<?php } ?>