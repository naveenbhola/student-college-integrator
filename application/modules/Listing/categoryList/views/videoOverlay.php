<?php
                
                $data = $institute->getVideos();
		$urlArray = array();
 		foreach($data as $media){
			$urlArray[] =  array('name'=>$media->getName(),'url'=>$media->getURL(),'thumb'=>$media->getThumbURL());
		}
?>
<div class="blkRound">
	<div class="layer-title">
<a href="#" title="Close" class="close" onclick="$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';closeMessage();<?php if(!empty($thanks_layer)) :?> window.location.reload(true);<?php endif;?>"></a>
			<h4><?=$type?>s of <?=$name?></h4>
			</div>

<div class="photo-view-cont" id="photo-view-cont">
	<div class="photo-preview-col">
    	<div class="photo-box">
               <div id="currentImage">
                <?php $index=0; foreach($urlArray as $url):?>
                 <div id="obj<?php echo $index;?>" <?php if($index>0):?> style="display:none;"<?php endif;?>>
                 <object name="" width="425" height="385" uniqueattr="Listing-Video">
			<param id="param1" name="movie" value="<?php echo $urlArray[$index]['url']; ?>"></param>
			<param id="param2" name="allowFullScreen" value="true"></param>
			<param id="param3" name="allowscriptaccess" value="always"></param>
		<embed id="objembed" wmode="transparent" src="<?php echo $urlArray[$index]['url']; ?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="470" height="385"></embed>
		</object>
                </div>
            <?php $index++;endforeach;?>
            </div>
            <?php if(count($urlArray)>1):?>
            <div class="photo-sliders">
            	<div class="prev-slide" uniqueattr="Listing-Video" onclick="showImage('prev','prevButton');return false;" id="prevButton" style="visibility:hidden">&nbsp;</div>
                <div class="next-slide" uniqueattr="Listing-Video" onclick="showImage('next','nextButton');return false;" id="nextButton">&nbsp;</div>
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
            	<li <?php if($i>4): ?>style="display:none;" <?php else:?>style="display:inline;"<?php endif;?> id="showThumbControll<?php echo $i;?>" <?php if($i == 0):?> class="selected" <?php else:?>class=""<?php endif;?>><div class="thumb-box" onclick="showThumbControll(<?php echo $i;?>);"><img width="63" height="44" src="<?php echo $url['thumb']; ?>" uniqueattr="Listing-Video" />
<span class="sprite-bg play-icn"></span></div>
</li>
               <?php $i++;endforeach;?>
            </ul>
            <div class="video-caption">
            <div class="photo-label" id="currentImageName"><?php echo $urlArray[0]['name']; ?></div>
            <p id="image-number"><strong>1</strong> of <?php echo count($urlArray);?></p>
            </div>
            </div>
            <?php if(count($urlArray)>5) :?>
            <div class="next-scroll" uniqueattr="Listing-Video" onclick="showImage('next','nextButton1');return false;" id="nextButton1">&nbsp;</div>
            <?php endif;?>
        </div>
        
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
				$('image-number').innerHTML = '<strong>'+(index+1) +'</strong>'+ " of " + urlArray.length;
                                
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
</style>
