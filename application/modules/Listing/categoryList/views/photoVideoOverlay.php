<?php
                $data = $institute->getPhotos();
		$urlArray = array();
 		foreach($data as $media){
			$urlArray[] =  array('name'=>$media->getName(),'url'=>$media->getURL(),'thumb'=>$media->getThumbURL());
		}
?>
<div class="blkRound">
	<div class="layer-title">
	<a href="#" title="Close" class="close" onclick="runautoCarousle();$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';closeMessage();<?php if(!empty($thanks_layer)) :?> window.location.reload(true);<?php endif;?>"></a>
<h4><?=$type?>s of <?=$name?></h4>
</div>
<div class="photo-view-cont" id="photo-view-cont">
	<div class="photo-preview-col" style="height:410px;">
    	<div class="photo-box" uniqueattr="Listing-Photo">
    	<div id="royalSlider" class="royalSlider rsDefault">
    	<?php $index_photo = 0; foreach ($urlArray as $url) :?>
    	<a class="rsImg"  data-rsDelay="1000" data-rsBigImg="<?php echo $url['url'];?>" href="<?php echo $url['url'];?>"><div><span class="flLt"><?php if(!empty($url['name'])) {echo $url['name'];} else {echo "Image";}?></span><span class="flRt" id="image-number"><strong><?php echo ($index_photo+1); ?></strong> of <?php echo count($urlArray);?></span></div><img width="63" height="44" class="rsTmb" src="<?php echo $url['thumb'];?>" uniqueattr="Listing-Photo"/></a>
        <?php $index_photo++; endforeach;?>
        </div>    
            <!-- div class="photo-sliders">
            	<div class="prev-slide">&nbsp;</div>
                <div class="next-slide">&nbsp;</div>
            </div-->
        </div>
        <!-- div class="photo-label">Campus</div-->
    <div class="clearFix"></div>
    </div>
     <div class="photo-info-col" id="photo-info-col">
        <?php if(!empty($thanks_layer)) :
		echo $thanks_layer;
         else :
                echo $response_form;
         ?>
    <?php endif;?> 
    </div>
<div class="clearFix"></div>
</div>
<div class="clearFix"></div>
</div>
<script>
   $j(document).ready(function($) {
  $('#royalSlider').royalSlider({
    fullscreen: {
      enabled: false,
      nativeFS: false
    },
    controlNavigation: 'thumbnails',
    autoScaleSlider: true, 
    autoScaleSliderWidth: 470,     
    autoScaleSliderHeight: 366,
    loop: true,
    numImagesToPreload:1,
    arrowsNavAutohide: true,
    arrowsNavHideOnTouch: true,
    keyboardNavEnabled: true,
    autoPlay: {
    		// autoplay options go gere
    		enabled: false,
    		pauseOnHover: true
   },
   thumbs: {
    		// thumbnails options go gere
    		spacing: 8,
    		arrowsAutoHide: true
  },
  globalCaption:true
  });
});
</script>
<style>
/******************************
*
*  RoyalSlider Default Skin 
*
*    1. Arrows 
*    2. Bullets
*    3. Thumbnails
*    4. Tabs
*    5. Fullscreen button
*    6. Play/close video button
*    7. Preloader
*    
*  Sprite: 'http://dimsemenov.com/plugins/royal-slider/royalslider/default/rs-default.png'
*  Feel free to edit anything
*  If you don't some part - just delete it
* 
******************************/


/* Background */
.rsDefault .rsOverflow,
.rsDefault .rsSlide,
.rsDefault .rsVideoFrameHolder,
.rsDefault .rsThumbs {
}


/***************
*
*  1. Arrows
*
****************/

.rsDefault .rsArrow {
	height: 100%;
	width: 44px;
	position: absolute;
	display: block;
	cursor: pointer;
	z-index: 21;
}
.rsDefault.rsVer .rsArrow {
	width: 100%;
	height: 44px;
	
}
.rsDefault.rsVer .rsArrowLeft { top: 0; left: 0; }
.rsDefault.rsVer .rsArrowRight { bottom: 0;  left: 0; }

.rsDefault.rsHor .rsArrowLeft { left: 0; top: 0; }
.rsDefault.rsHor .rsArrowRight { right: 0; top:0; }

.rsDefault .rsArrowIcn {		
	width: 32px;
	height: 32px;
	top: 50%;
	left: 50%;
	margin-top:-16px;	
	margin-left: -16px;

	position: absolute;	
	cursor: pointer;	
	background: url('/public/images/abroad-sprite.gif');
	background-color: rgba(0,0,0,0.75);
	*background-color: #111;
	border-radius: 2px;
}

.rsDefault.rsHor .rsArrowLeft .rsArrowIcn { background-position: -99px 0; }
.rsDefault.rsHor .rsArrowRight .rsArrowIcn { background-position: -133px 0; }


.rsDefault .rsArrowDisabled .rsArrowIcn { background-color: rgba(0,0,0,0.4); opacity: .4; *display: none; }


/***************
*
*  2. Bullets
*
****************/

.rsDefault .rsBullets {
	position: absolute;
	z-index: 35;
	left: 0;
	bottom: 0;
	width: 100%;
	height: auto;
	margin: 0 auto; 
	background: #000;
	background: rgba(0,0,0,0.75);
	text-align: center;
	line-height: 18px;
	overflow: hidden;
}
.rsDefault .rsBullet {
	width: 8px;
	height: 8px;
	display: inline-block;
	*display:inline; 
	*zoom:1;
	margin: 0 5px 1px;
	border-radius: 50%;
	background: #777;
	background: rgba(255,255,255,0.5);
}
.rsDefault .rsBullet.rsNavSelected  {
	background-color: #FFF;
}





/***************
*
*  3. Thumbnails
*
****************/

.rsDefault .rsThumbsHor {
	width: 100%;
	height: 44px;
        margin-top: 20px;
}
.rsDefault .rsThumbsVer {
	padding-right: 4px;
	width: 96px;
	height: 100%;
	position: absolute;
	top: 0;
	right: 0;
}
.rsDefault.rsWithThumbsHor .rsThumbsContainer {
	position: relative;
	height: 100%;
}
.rsDefault.rsWithThumbsVer .rsThumbsContainer {
	position: relative;
	width: 100%;
}
.rsDefault .rsThumb {
	float: left;
	overflow: hidden;
	width: 63px;
	height: 44px;
}
.rsDefault .rsThumb img {
	width: 100%;
	height: 100%;
	border:1px solid #969696;
}
.rsDefault .rsThumb.rsNavSelected {
	background: #02874a;
}
.rsDefault .rsThumb.rsNavSelected img {
	opacity: 0.3;
	filter: alpha(opacity=30);
}
.rsDefault .rsTmb {
	display: block;
}

/* Thumbnails arrow icons */
.rsDefault .rsThumbsArrow {
	height: 100%;
	width: 22px;
	position: absolute;
	display: block;
	cursor: pointer;	
	z-index: 21;	

}

.rsDefault.rsWithThumbsVer .rsThumbsArrow {
	width: 100%;
	height: 20px;
}
.rsDefault.rsWithThumbsVer .rsThumbsArrowLeft { top: 0; left: 0; }
.rsDefault.rsWithThumbsVer .rsThumbsArrowRight { bottom: 0;  left: 0; }

.rsDefault.rsWithThumbsHor .rsThumbsArrowLeft { left: 0; top: 0; }
.rsDefault.rsWithThumbsHor .rsThumbsArrowRight { right: 0; top:0; }

.rsDefault .rsThumbsArrowIcn {		
	width: 22px;
	height: 27px;
	top: 9px;
	left: 0;
	position: absolute;	
	cursor: pointer;	
	background: url('/public/images/abroad-sprite.gif');
}

.rsDefault.rsWithThumbsHor .rsThumbsArrowLeft .rsThumbsArrowIcn { background-position: 0 0; }
.rsDefault.rsWithThumbsHor .rsThumbsArrowRight .rsThumbsArrowIcn { background-position: -25px 0; }



.rsDefault .rsThumbsArrowDisabled { display: none !important; }

/* Thumbnails resizing on smaller screens */
@media screen and (min-width: 0px) and (max-width: 800px) {
	.rsDefault .rsThumb {
		width: 59px;
		height: 44px;
	}
	.rsDefault .rsThumbsHor {
		height: 44px;
	}
	.rsDefault .rsThumbsVer {
		width: 59px;
	}
}




/***************
*
*  4. Tabs
*
****************/

.rsDefault .rsTabs {
	width: 100%;
	height: auto;
	margin: 0 auto;
	text-align:center;
	overflow: hidden; padding-top: 12px; position: relative;
}
.rsDefault .rsTab {
	display: inline-block;
	cursor: pointer;
	text-align: center;
	height: auto;
	width: auto;
	color: #333;
	padding: 5px 13px 6px;
	min-width: 72px;
	border: 1px solid #D9D9DD;
	border-right: 1px solid #f5f5f5;
	text-decoration: none;

	background-color: #FFF;
	background-image: -webkit-linear-gradient(top, #fefefe, #f4f4f4); 
	background-image:    -moz-linear-gradient(top, #fefefe, #f4f4f4);
	background-image:         linear-gradient(to bottom, #fefefe, #f4f4f4);

	-webkit-box-shadow: inset 1px 0 0 #fff;
	box-shadow: inset 1px 0 0 #fff;

	*display:inline; 
	*zoom:1;
}
.rsDefault .rsTab:first-child {
	-webkit-border-top-left-radius: 4px;
	border-top-left-radius: 4px;
	-webkit-border-bottom-left-radius: 4px;
	border-bottom-left-radius: 4px;
}
.rsDefault .rsTab:last-child { 
	-webkit-border-top-right-radius: 4px;
	border-top-right-radius: 4px;
	-webkit-border-bottom-right-radius: 4px;
	border-bottom-right-radius: 4px;

	border-right:  1px solid #cfcfcf;
}
.rsDefault .rsTab:active { 
	border: 1px solid #D9D9DD;   
	background-color: #f4f4f4;
	    -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2) inset;
	box-shadow:  0 1px 4px rgba(0, 0, 0, 0.2) inset;
}
.rsDefault .rsTab.rsNavSelected { 
		color: white;
	border: 1px solid #999;
	text-shadow: 1px 1px #838383;
	-webkit-box-shadow: 0 1px 9px rgba(102, 102, 102, 0.65) inset;
	box-shadow: 0 1px 9px rgba(102, 102, 102, 0.65) inset;
	background: #ACACAC;
	background-image: -webkit-linear-gradient(top, #ACACAC, #BBB);
	background-image: -moz-llinear-gradient(top, #ACACAC, #BBB);
	background-image: linear-gradient(to bottom, #ACACAC, #BBB);
}





/***************
*
*  5. Fullscreen button
*
****************/

.rsDefault .rsFullscreenBtn {
	right: 0;
	top: 0;
	width: 44px;
	height: 44px;
	z-index: 22;
	display: block;
	position: absolute;
	cursor: pointer;
	
}
.rsDefault .rsFullscreenIcn {
	display: block;
	margin: 6px;
	width: 32px;
	height: 32px;

	background: url('/public/images/rs-default.png') 0 0;
	background-color: rgba(0,0,0,0.75);
	*background-color: #000;
	border-radius: 2px;

}
.rsDefault .rsFullscreenIcn:hover {
	background-color: rgba(0,0,0,0.9);
}
.rsDefault.rsFullscreen .rsFullscreenIcn {
	background-position: -32px 0;
}





/***************
*
*  6. Play/close video button
*
****************/

.rsDefault .rsPlayBtn {
	-webkit-tap-highlight-color:rgba(0,0,0,0.3);
	width:64px;
	height:64px;
	margin-left:-32px;
	margin-top:-32px;
	cursor: pointer;
filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#ac000000', endColorstr='#ac000000');
}
.rsDefault .rsPlayBtnIcon {
	width:64px;
	display:block;
	height:64px;
	-webkit-border-radius: 4px;
	border-radius: 4px;
	background:url(/public/images/rs-default.png) no-repeat 0 -32px;
	
	background-color: rgba(0,0,0,0.75);

	-webkit-transition: .3s;
	-moz-transition: .3s;
	transition: .3s;
	*background-color: #000;
}
.rsDefault .rsPlayBtn:hover .rsPlayBtnIcon {
	background-color: rgba(0,0,0,0.9);
}
.rsDefault .rsBtnCenterer {
	position:absolute;
	left:50%;
	top:50%;
	width: 20px;
	height: 20px;
}
.rsDefault .rsCloseVideoBtn {
	right: 0;
	top: 0;
	width: 44px;
	height: 44px;
	z-index: 500;
	position: absolute;
	cursor: pointer;
	-webkit-backface-visibility: hidden;
	-webkit-transform: translateZ(0);
	
}
.rsDefault .rsCloseVideoBtn.rsiOSBtn {
	top: -38px;
	right: -6px;
	/*top: -6px;
	right: -38px;*/
}

.rsDefault .rsCloseVideoIcn {
	margin: 6px;
	width: 32px;
	height: 32px;
	background: url('/public/images/rs-default.png') -64px 0;
	background-color: #000;
	background-color: rgba(0,0,0,0.75);
}
.rsDefault .rsCloseVideoIcn:hover {
	background-color: rgba(0,0,0,0.9);
}



/***************
*
*  7. Preloader
*
****************/

.rsDefault .rsPreloader {
	width:20px;
	height:20px;
	background-image:url(/public/images/preloader-white.gif);

	left:50%;
	top:50%;
	margin-left:-10px;
	margin-top:-10px;	
}
/* Core RS CSS file. 95% of time you shouldn't change anything here. */
.royalSlider {
	width: 470px;
	position: relative;
	direction: ltr;
}

.rsWebkit3d .rsSlide,
.rsWebkit3d .rsContainer,
.rsWebkit3d .rsThumbs,
.rsWebkit3d .rsPreloader,
.rsWebkit3d img,
.rsWebkit3d .rsOverflow,
.rsWebkit3d .rsBtnCenterer,
.rsWebkit3d .rsAbsoluteEl {
	-webkit-backface-visibility: hidden;
	-webkit-transform: translateZ(0); 
}
.rsFade.rsWebkit3d .rsSlide,
.rsFade.rsWebkit3d img,
.rsFade.rsWebkit3d .rsContainer {
	-webkit-transform: none;
}
.rsOverflow {
	width: 100%;
	height: 100%;
	position: relative;
	overflow: hidden;
	float: left;
	-webkit-tap-highlight-color:rgba(0,0,0,0);
}

.rsContainer {
	position: relative;
	width: 470px;
	height: 320px;
	-webkit-tap-highlight-color:rgba(0,0,0,0);
}

.rsArrow,
.rsThumbsArrow {
	cursor: pointer;
}

.rsArrow,
.rsNav,
.rsThumbsArrow {
	opacity: 1;
	-webkit-transition:opacity 0.3s linear;
	-moz-transition:opacity 0.3s linear;
	-o-transition:opacity 0.3s linear;
	transition:opacity 0.3s linear;
}
.rsHidden {
	opacity: 0;
	visibility: hidden;
	-webkit-transition:visibility 0s linear 0.3s,opacity 0.3s linear;
	-moz-transition:visibility 0s linear 0.3s,opacity 0.3s linear;
	-o-transition:visibility 0s linear 0.3s,opacity 0.3s linear;
	transition:visibility 0s linear 0.3s,opacity 0.3s linear;
}


.rsGCaption {
	width: 100%;
	float: left;
	text-align: center;
        color: #000000;
        text-align: center;
        font: 12px Trebuchet MS,Arial,Helvetica,sans-serif;
        padding-top: 8px;
}
.rsGCaption .flLt{font-weight:bold;width:410px;font-size:14px;text-align:center;}

/* Fullscreen options, very important ^^ */
.royalSlider.rsFullscreen {
	position: fixed !important;
	height: auto !important;
	width: auto !important;
	margin: 0 !important;
	padding: 0 !important;
	z-index: 2147483647 !important;
	top: 0 !important;
	left: 0 !important;
	bottom: 0 !important;
	right: 0 !important;
}

.royalSlider .rsSlide.rsFakePreloader {
	opacity: 1 !important;
	-webkit-transition: 0s;
	-moz-transition: 0s;
	-o-transition:  0s;
	transition:  0s;
	display: none;
}

.rsSlide {
	position: absolute;
	left: 0;
	top: 0;
	display: block;
	overflow: hidden;
	height: 320px;
	width: 100%;
	text-align:center; overflow:hidden;
}

.royalSlider.rsAutoHeight,
.rsAutoHeight .rsSlide {
	height: auto;
}

.rsContent {
	width: 100%;
	height: 100%;
	position: relative;
}

.rsPreloader {
	position:absolute;
	z-index: 0;	
}

.rsNav {
	-moz-user-select: -moz-none;
	-webkit-user-select: none;
	user-select: none;
}
.rsNavItem {
	-webkit-tap-highlight-color:rgba(0,0,0,0.25);
}

.rsThumbs {
	cursor: pointer;
	position: relative;
	overflow: hidden;
	float: left;
	z-index: 22;
}
.rsTabs {
	float: left;
}
.rsTabs,
.rsThumbs {
	-webkit-tap-highlight-color:rgba(0,0,0,0);
	-webkit-tap-highlight-color:rgba(0,0,0,0);
}


.rsVideoContainer {
	/*left: 0;
	top: 0;
	position: absolute;*/
	/*width: 100%;
	height: 100%;
	position: absolute;
	left: 0;
	top: 0;
	float: left;*/
	width: auto;
	height: auto;
	line-height: 0;
	position: relative;
}
.rsVideoFrameHolder {
	position: absolute;
	left: 0;
	top: 0;
	background: #141414;
	opacity: 0;
	-webkit-transition: .3s;
}
.rsVideoFrameHolder.rsVideoActive {
	opacity: 1;
}
.rsVideoContainer iframe,
.rsVideoContainer video,
.rsVideoContainer embed,
.rsVideoContainer .rsVideoObj {
	position: absolute;
	z-index: 50;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
}
/* ios controls over video bug, shifting video */
.rsVideoContainer.rsIOSVideo iframe,
.rsVideoContainer.rsIOSVideo video,
.rsVideoContainer.rsIOSVideo embed {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding-right: 44px;
}

.rsABlock {
	left: 0;
	top: 0;
	position: absolute;
	z-index: 25;
	-webkit-backface-visibility: hidden;
}

.grab-cursor {
	/*cursor:url(grab.png) 8 8, move; */
}

.grabbing-cursor{ 
	/*cursor:url(grabbing.png) 8 8, move;*/
}

.rsNoDrag {
	cursor: auto;
}
</style>
