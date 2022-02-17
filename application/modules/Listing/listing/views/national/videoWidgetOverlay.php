<?php

if(empty($mediaRequest) || $mediaRequest != 'institute') {       
		foreach($mediaData as $media){
			$urlArray[] =  array('name'=>$media->getName(),'url'=>$media->getURL(),'thumb'=>$media->getThumbURL());
		}
}
?>
<div id="modal-overlay"></div>
<div class="management-layer" style = "display :none">
    <?php //echo var_dump($mediaData);?>
    <div class="layer-head">
   <a href="javascript:void(0);" class="flRt sprite-bg cross-icon" title="Close" onclick = "closePhotoVideoOverlay('videoOverLay');"></a>
    <div class="layer-title-txt">Videos of <?php echo $institute->getName() ;?></div>
    
   </div>
  
<div class="photo-slider-cont">
	<div class="photo-preview-col">
    	<div class="photo-box">
               <div id="currentImage">
               <?php $index=0; foreach($urlArray as $url):?>
                 <div id="obj<?php echo $index;?>" <?php if($index>0):?> style="display:none;"<?php endif;?>>
                 <object name="" width="425" height="385" uniqueattr="Listing-Video">
			<param id="param1" name="movie" value="<?php echo $urlArray[$index]['url']."?version=3&autoplay=1"; ?>"></param>
			<param id="param2" name="allowFullScreen" value="true"></param>
			<param id="param3" name="allowscriptaccess" value="always"></param>
		<embed id="objembed" wmode="transparent" src="<?php echo $urlArray[$index]['url']."?version=3&autoplay=1"; ?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="470" height="385"></embed>
		</object>
                </div>
            <?php $index++;endforeach;?>
            </div>
            <?php if(count($urlArray)>1):?>
            <div class="photo-sliders">
            	<a class="prev-arr-box" uniqueattr="Listing-Video" onclick="showImage('prev','prevButton');return false;" id="prevButton" style="visibility:hidden" href="#"><i class="sprite-bg prev-arr-icn"></i></a>
            	<a class="next-arr-box" uniqueattr="Listing-Video" onclick="showImage('next','nextButton');return false;" id="nextButton" href="#"><i class="sprite-bg next-arr-icn"></i></a>
                
            </div>
           <?php endif;?>
        </div>
        
        
        <div class="photo-thumb-cont">
                <?php if(count($urlArray)>5) :?>
        	<div class="prev-scroll" uniqueattr="Listing-Video" onclick="showImage('prev','prevButton1');return false;" id="prevButton1" style="visibility:hidden">&nbsp;</div>
                <?php endif;?>
            <div class="photo-thumb-child">
        	<ul style="width:400px;_width:410px;"id="thumbParent">
               <?php $i=0; foreach($urlArray as $url):?>
            	<li <?php if($i>4): ?>style="display:none;" <?php else:?>style="display:inline;"<?php endif;?> id="showThumbControll<?php echo $i;?>" <?php if($i == 0):?> class="selected" <?php else:?>class=""<?php endif;?>><div class="thumb-box" onclick="showThumbControll(<?php echo $i;?>);"><img width="70" height="48" src="<?php echo $url['thumb']; ?>" uniqueattr="Listing-Video" />
<span class="sprite-bg play-icn"></span></div>
</li>
               <?php $i++;endforeach;?>
            </ul>
            <div class="video-caption">
            <div class="photo-label" id="currentImageName"><?php echo $urlArray[0]['name']; ?></div>
            <p id="image-number-video"><strong>1</strong> of <?php echo count($urlArray);?></p>
            </div>
            </div>
            <?php if(count($urlArray)>5) :?>
            <div class="next-scroll" uniqueattr="Listing-Video" onclick="showImage('next','nextButton1');return false;" id="nextButton1">&nbsp;</div>
            <?php endif;?>
        </div>
        
    </div>
<div class="clearFix"></div>
</div>
<div class="clearFix"></div>
</div>
<script>
var urlArray = <?php echo json_encode($urlArray) ?>;
var count = '<?php echo count($urlArray);?>';
var currentImage = 0;
var current_chapter = 0;
var type = '<?=$type?>'; 
function showImage(order,elementid){
		if(order == 'prev'){
				if((currentImage-1) < 0){
						return false;
				}
				currentImage--;
		}else{
				if((currentImage+1) == urlArray.length){
						return false;
				}
				currentImage++;
		}
                var chapter = parseInt(currentImage/5); 
                if(chapter >=0) {
                if(current_chapter != chapter) {
                        for(var i=0;i<count;i++) {
				if(i>=(chapter)*5 && i<=(chapter*5+4) && $('showThumbControll'+i)) {
					$('showThumbControll'+i).style.display = "inline";	
                                 } else {
					$('showThumbControll'+i).style.display = "none";
                                 }
                        }
                	current_chapter = chapter;       
                }
                } else {
                        current_chapter = 0; 
                        chapter = 0;
                }
                updateVideoHTML(currentImage);
}

function showThumbControll(imagenumber) {
        currentImage = imagenumber;
	updateVideoHTML(imagenumber);
}
function updateVideoHTML(index) {
        try {
        highlighSelectedItem();
	if(typeof(urlArray[index]) != 'undefined'){
                                				
                                for(var i=0;i<count;i++) {
                                        $('obj'+i).style.display = "none";
					if(index == i) {
						$('obj'+i).style.display = "block";
                                        }
                                }
				$('currentImageName').innerHTML = urlArray[currentImage]['name'].substr(0, urlArray[index]['name'].lastIndexOf('.')) || urlArray[index]['name'];
				$('image-number-video').innerHTML = '<strong>'+(index+1) +'</strong>'+ " of " + urlArray.length;
                                
		}
		if(urlArray[index-1]){
                                
                                if($('prevButton'))
				$('prevButton').style.visibility = 'visible';
                                if($('prevButton1'))
                                $('prevButton1').style.visibility = 'visible';
		}else{
                                
                                if($('prevButton'))
				$('prevButton').style.visibility = 'hidden';
                                if($('prevButton1'))
                                $('prevButton1').style.visibility = 'hidden';
		}
		if(urlArray[index+1]){
                                
                                if($('nextButton'))
				$('nextButton').style.visibility = 'visible';
                                if($('nextButton1'))
                                $('nextButton1').style.visibility = 'visible';
		}else{
                                if($('nextButton'))
				$('nextButton').style.visibility = 'hidden';
                                if($('nextButton1'))
                                $('nextButton1').style.visibility = 'hidden';
		}
       } catch(e) {
		alert(e);
       }
}

function highlighSelectedItem() {
        for(var i=0;i<count;i++) {
                $('showThumbControll'+i).className = "";
		if(i == currentImage) {
			$('showThumbControll'+currentImage).className = "selected";	
                }
	}
}
</script>
<style>
.selected { 
	background: none repeat scroll 0 0 #02874A;
        opacity: 0.3;
	filter: alpha(opacity=30);
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=30)";
}


.photo-view-cont {
    background: none repeat scroll 0 0 #FFFFFF;
    float: left;
    font: 12px Tahoma,Geneva,sans-serif;
    padding: 10px;
    width: 775px;
}
.photo-preview-col {
    float: left;
    width: 470px;
}
.photo-preview-col .photo-box {
    position: relative;
    width: 470px;
}
.photo-sliders {
    position: absolute;
}
.photo-label {
    color: #000000;
    float: left;
    font: bold 14px "Trebuchet MS",Arial,Helvetica,sans-serif;
    text-align: center;
    width: 340px;
}
.video-caption {
    clear: both;
    float: left;
    padding-top: 8px;
    width: 100%;
}
.photo-info-col {
    border: 1px solid #DDDDDD;
    box-shadow: 0 0 4px #D4D4D4;
    float: right;
    padding: 3px;
    width: 280px;
}
.photo-info-col h5 {
    background: url("/public/images/management-sprite.png") no-repeat scroll 9px -222px #F3F3F3;
    color: #282828;
    display: block;
    font: 16px/20px "Trebuchet MS",Arial,Helvetica,sans-serif;
    margin: 0 0 5px;
    padding: 10px 10px 10px 40px;
}
.photo-info-col h6 {
    color: #282828;
    display: block;
    font: 14px Tahoma,Geneva,sans-serif;
    margin: 0 0 10px;
    padding: 0;
}
.photo-info-col ul li {
    color: #838383;
    font-family: Tahoma,Geneva,sans-serif;
    list-style: disc outside none !important;
    margin: 0 0 5px 15px;
}
.photo-info-col ul li span {
    color: #282828;
}
.photo-sliders {
    left: 0;
    position: absolute;
    top: 45%;
    width: 100%;
}
.photo-sliders .prev-slide, .photo-sliders .next-slide, .photo-thumb-cont .prev-scroll, .photo-thumb-cont .next-scroll, .play-icn {
    background: url("/public/images/management-sprite.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
    cursor: pointer;
}
/* .prev-slide-bg, .next-slide-bg{background-color: rgba(246, 246, 246, 0.6); width:36px; height:36px;} */
.photo-sliders .prev-slide, .photo-sliders .next-slide {
    background-position: -191px -77px;
    display: inline-block;
    float: left;
    height: 32px;
    width: 18px;

}
.photo-sliders .next-slide {
    background-position: -212px -77px;
    float: right;
}
.photo-thumb-cont {
    float: left;
    margin-top: 15px;
    width: 100%;
}
.photo-thumb-child {
    float: left;
    margin-left: 0;
    width: 425px;
}
.photo-thumb-child p {
    float: right;
    font: 14px "Trebuchet MS",Arial,Helvetica,sans-serif;
}
.photo-thumb-cont ul {
    float: left;
    margin:0 0 6px 23px;
    width: 100%;
}
.photo-thumb-cont ul li {
    cursor: pointer;
    float: left;
    margin-right: 10px;
    width: 70px;
}
.photo-thumb-cont ul li img {
    border: 1px solid #969696;
    float: left;
}
.photo-thumb-cont .prev-scroll, .photo-thumb-cont .next-scroll {
    background-position: -272px -77px;
    cursor: pointer;
    display: inline-block;
    float: left;
    height: 26px;
    margin-top: 10px;
    width: 13px;
}
.photo-thumb-cont .next-scroll {
    background-position: -287px -77px;
    float: right;
}
ul.form-box {
    margin: 0;
    padding: 0;
}
ul.form-box li {
    float: left;
    list-style: none outside none !important;
    margin: 0 0 10px;
    padding: 0;
    width: 100%;
}
ul.form-box li .universal-txt-field {
    width: 96%;
}
.thumb-box {
    height: 48px;
    position: relative;
    width: 70px
}
.play-icn {
    background-position: -192px -134px;
    height: 35px;
    left: 20px;
    position: absolute;
    top: 9px;
    width: 32px;
}
</style>
