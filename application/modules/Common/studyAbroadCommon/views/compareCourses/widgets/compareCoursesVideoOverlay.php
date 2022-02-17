<?php
$universityName = $universityObject->getName();
$univId  = $universityObject->getId();
if (strlen ( $universityName ) > 37) {
	$universityName = preg_replace ( '/\s+?(\S+)?$/', '', substr ( $universityName, 0, 37 ) ) . "...";
}
if(empty($mediaRequest) || $mediaRequest != 'institute') {       
		foreach($mediaData as $media){
			if(strlen ( $universityName ) > 37)
			{
				$universityNameForVideoURL = substr ( $universityName, 0, 30 );
			}
			else 
			{
				$universityNameForVideoURL = $universityName;
			}
			$urlArray[] =  array('name'=>htmlentities($universityNameForVideoURL. "  video"),'url'=>$media->getURL(),'thumb'=>$media->getThumbURL());
		}
}
?>
<div id="modal-overlay"></div>
<div class="abroad-media-layer" style = "display :none">
    <?php //echo var_dump($mediaData);?>
    <div class="layer-head">
   <a href="javascript:void(0);" class="flRt common-sprite close-icon" title="Close" onclick = "removePlayer('<?php echo $univId; ?>'); closePhotoVideoOverlay('videoOverLay','<?php echo $univId; ?>');"></a>
    <div class="layer-title-txt">Videos of <?php echo htmlentities($universityName) ;?></div>
    
   </div>
  
<div class="photo-slider-cont">
	<div class="photo-preview-col">
    	<div class="photo-box">
               <div id="currentImage">
               <?php $index=0; foreach($urlArray as $url):?>
                 <div id="obj<?php echo $univId; ?><?php echo $index;?>" <?php if($index>0):?> style="display:none;"<?php endif;?>>
                 <div class="Vobj" width="425" height="385"  >
                 <object name="" >
			<param id="param1" name="movie" value="<?php echo $urlArray[$index]['url']."?version=3&autoplay=1"; ?>"></param>
			<param id="param2" name="allowFullScreen" value="true"></param>
			<param id="param3" name="allowscriptaccess" value="always"></param>
		<embed id="objembed" wmode="transparent" src="<?php echo $urlArray[$index]['url']."?version=3&autoplay=1"; ?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="470" height="385"></embed>
		</object>
		</div>
                </div>
            <?php $index++;endforeach;?>
            </div>
            <?php if(count($urlArray)>1):?>
            <div class="photo-sliders">
            	<a class="prev-arr-box"  onclick="showImage('prev','prevButton<?php echo $univId; ?>','<?php echo $univId; ?>');return false;" id="prevButton<?php echo $univId; ?>" style="visibility:hidden" href="#"><i class="common-sprite prev-arr-icn"></i></a>
            	<a class="next-arr-box"  onclick="showImage('next','nextButton<?php echo $univId; ?>','<?php echo $univId; ?>');return false;" id="nextButton<?php echo $univId; ?>" href="#"><i class="common-sprite next-arr-icn"></i></a>
                
            </div>
           <?php endif;?>
        </div>
        
        
        <div class="photo-thumb-cont">
                <?php if(count($urlArray)>5) :?>
        	<div class="prev-scroll"  onclick="showImage('prev','prevButton1<?php echo $univId; ?>','<?php echo $univId; ?>');return false;" id="prevButton1<?php echo $univId; ?>" style="visibility:hidden">&nbsp;</div>
                <?php endif;?>
            <div class="photo-thumb-child">
        	<ul style="width:400px;_width:410px;"id="thumbParent">
               <?php $i=0; foreach($urlArray as $url):?>
            	<li <?php if($i>4): ?>style="display:none;" <?php else:?>style="display:inline;"<?php endif;?> id="showThumbControll<?php echo $univId; ?><?php echo $i;?>" <?php if($i == 0):?> class="selected" <?php else:?>class=""<?php endif;?>><div class="thumb-box" onclick="showThumbControll(<?php echo $i;?>,'<?php echo $univId;?>');"><img width="70" height="48" src="<?php echo $url['thumb']; ?>" />
<span class="common-sprite video-icon"></span></div>
</li>
               <?php $i++;endforeach;?>
            </ul>
            <div class="video-caption">
            <div class="photo-label" id="currentImageName<?php echo $univId; ?>"><?php echo $urlArray[0]['name']; ?></div>
            <p id="image-number-video<?php echo $univId; ?>"><strong>1</strong> of <?php echo count($urlArray);?></p>
            </div>
            </div>
            <?php if(count($urlArray)>5) :?>
            <div class="next-scroll"  onclick="showImage('next','nextButton1<?php echo $univId; ?>','<?php echo $univId; ?>');return false;" id="nextButton1<?php echo $univId; ?>">&nbsp;</div>
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
function removePlayer(univId) {
	for(var i=0;i<count;i++) {
		 $j('#obj'+univId+i).find('.Vobj').remove();
	 }
}
function showImage(order,elementid,univId){
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
				if(i>=(chapter)*5 && i<=(chapter*5+4) && $j('#showThumbControll'+univId+i)) {
					$j('#showThumbControll'+univId+i).css('display','inline');	
                                 } else {
					$j('#showThumbControll'+univId+i).hide();
                                 }
                        }
                	current_chapter = chapter;       
                }
                } else {
                        current_chapter = 0; 
                        chapter = 0;
                }
                updateVideoHTML(currentImage,univId);
}

function openVideoLayer(imagenumber,univId) {
	adjustThumbNavBar(imagenumber,univId);
	showThumbControll(imagenumber,univId);
}
function adjustThumbNavBar(imagenumber,univId){
	var chapter = parseInt(imagenumber/5); 
    if(chapter >=0) {
    if(current_chapter != chapter) {
            for(var i=0;i<count;i++) {
	if(i>=(chapter)*5 && i<=(chapter*5+4) && $j('#showThumbControll'+univId+i)) {
		$j('#showThumbControll'+univId+i).css('display','inline');	
                     } else {
		$j('#showThumbControll'+univId+i).hide();
                     }
            }
    	current_chapter = chapter;       
    }
    } else {
            current_chapter = 0; 
            chapter = 0;
    }
}

function showThumbControll(imagenumber,univId) {
	currentImage = imagenumber;
    for(var i=0;i<count;i++) {
		 if(typeof videoClones[i] == 'undefined'){
		 videoClones[i] = $j('#obj'+univId+i).clone(true);
		 }
		 }
	updateVideoHTML(imagenumber,univId);
}
var videoClones = new Array() ;
function updateVideoHTML(index,univId) {
        try {
        highlighSelectedItem(univId);
	if(typeof(urlArray[index]) != 'undefined'){
                                				
                                for(var i=0;i<count;i++) {
          
                                        $j('#obj'+univId+i).find('.Vobj').remove();
                                                                         
					if(index == i) {
					
							$j('#obj'+univId+i).html(videoClones[i].html());
							$j('#obj'+univId+i).show();
						
                                        }
                                }
				$j('#currentImageName'+univId).html(urlArray[currentImage]['name'].substr(0, urlArray[index]['name'].lastIndexOf('.')) || urlArray[index]['name']);
				$j('#image-number-video'+univId).html('<strong>'+(index+1) +'</strong>'+ " of " + urlArray.length);
                                
		}
		if(urlArray[index-1]){
                                
                                if($j('#prevButton'+univId))
			                	$j('#prevButton'+univId).css("visibility", 'visible');
                                if($j('#prevButton1'+univId))
                                $j('#prevButton1'+univId).css("visibility", 'visible');
		}else{
                                
                                if($j('#prevButton'+univId))
				                $j('#prevButton'+univId).css("visibility", 'hidden');
                                if($j('#prevButton1'+univId))
                                $j('#prevButton1'+univId).css("visibility", 'hidden');
		}
		if(urlArray[index+1]){
                                
                                if($j('#nextButton'+univId))
				                $j('#nextButton'+univId).css("visibility", 'visible');
                                if($j('#nextButton1'+univId))
                                $j('#nextButton1'+univId).css("visibility", 'visible');
		}else{
                                if($j('#nextButton'+univId))
				                $j('#nextButton'+univId).css("visibility", 'hidden');
                                if($j('#nextButton1'+univId))
                                $j('#nextButton1'+univId).css("visibility", 'hidden');
		}
       } catch(e) {
       
   		alert(e);
       }
}

function highlighSelectedItem(univId) {
        for(var i=0;i<count;i++) {
           
                $j('#showThumbControll'+univId+i).removeClass("selected");
                
		if(i == currentImage) {
			$j('#showThumbControll'+univId+currentImage).addClass("selected");	
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
    background: url("/public/images/abroad-common-sprite3.png") no-repeat scroll 9px -222px #F3F3F3;
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
    background: url("/public/images/abroad-common-sprite3.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
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
    background-position: -123px -21px;
    cursor: pointer;
    display: inline-block;
    float: left;
    height: 26px;
    margin-top: 14px;
    width: 13px;
}
.photo-thumb-cont .next-scroll {
    background-position: -135px -21px;
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

.thumb-box .video-icon {
top : 22% !important;
left : 29% !important;
}
</style>
