<?php
            $banner_url = $config_data_array['banner_url'];
            $pageZone = 'LEFT';
			$width = '410';
			$height = '250';
			$width +=2;
            $height +=2;
            if(!empty($banner_url)):?>
            <div id="bannerImage"></div> 
            <script type="text/javascript">
            function loadImage() {
            	document.getElementById('bannerImage').innerHTML = "<IFRAME MARGINWIDTH=0 MARGINHEIGHT=0 HSPACE=0 VSPACE=0 FRAMEBORDER=0 SCROLLING=no BORDERCOLOR=#000000 FRAMEBORDER=0 ID=<?php echo $pageZone;?> HEIGHT=<?php echo $height;?> WIDTH=<?php echo  $width ;?> SRC = <?php echo $banner_url;?>></IFRAME>";	
            }
            window.setTimeout(loadImage,1000);
            </script>
            <?php
            else:
            echo recordTime('banner_start'); 
			$zone = $config_data_array['banner_zone_id'];
            $shikshaCriteria = '';
            if($shikshaCriteriaCategory != '' || $shikshaCriteriaCountry != '' || $shikshaCriteriaCity != '' || $shikshaCriteriaKeyword != '' || $shikshaCriteriaLocation != '') {
                $shikshaCriteria = 'shikshaCriteria='. $shikshaCriteriaCategory .'|'. $shikshaCriteriaCountry .'|'. $shikshaCriteriaCity .'|' . htmlentities($shikshaCriteriaKeyword)." ".htmlentities($shikshaCriteriaLocation);
            } else {
                $shikshaCriteria = 'shikshaCriteria=|||';
            }            
                 $bannerAdSrc =  IEPLADS_DOMAIN . '/bmsjs/bms_display_final.php?zonestr='. $zone .'&showall=1&data=&'. $shikshaCriteria;
?>
                 <script>pushBannerToPool('<?php echo $pageZone;?>','<?php echo $bannerAdSrc; ?>');</script>
<IFRAME MARGINWIDTH=0 MARGINHEIGHT=0 HSPACE=0 VSPACE=0 FRAMEBORDER=0 SCROLLING=no BORDERCOLOR=#000000 FRAMEBORDER=0 ID=<?php echo $pageZone;?> HEIGHT=<?php echo $height;?> WIDTH=<?php echo  $width ;?> SRC = 'about:blank'></IFRAME>
<?php
    $shikshaCriteria = '';
	$pageZone = '';
    echo recordTime('banner_end');
?>
<?php endif;?>
