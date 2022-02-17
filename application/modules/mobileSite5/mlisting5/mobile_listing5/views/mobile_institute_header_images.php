<?php
global $instituteHeaderImage1;
global $instituteHeaderImage2;
$headerImages = array();
foreach($institute->getHeaderImages() as $image){
        $tempImage = array();
        $tempImage['url'] =  $image->getFullURL();
        $headerImages[] = $tempImage;
}
if(count($headerImages) > 0 && $headerImages[0]['url']){
?>
    <div id="owl-example" class="owl-carousel" data-enhance="false">
    <?php 
    	$location =  (($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"");
        $locationCity =  $currentLocation->getCity()->getName() ;
 	?>
        <div>
          <img id="mainImage" width="100%" height="201"  src="<?=$headerImages[0]['url']?>" alt="<?=$institute->getName().' in '.$location.' '.$locationCity;?>" />
        </div>
        <?php
       
        if($headerImages[1]){ 
		$instituteHeaderImage1 = $headerImages[1]['url'];
		
				
	?>
                <div>
                  <img id="headerthumb1" width="100%" height="201" alt="<?=$institute->getName().' in '.$location.' '.$locationCity;?>" />
                </div>
        <?php
        }
        ?>
        <?php
        if($headerImages[2]){ 
		$instituteHeaderImage2 = $headerImages[2]['url'];
	?>                        
                <div>
                  <img id="headerthumb2" width="100%" height="201" alt="<?=$institute->getName().' in '.$location.' '.$locationCity;?>"/>
                </div>
        <?php
        }
        ?>
    </div>
<?php
}
?>
