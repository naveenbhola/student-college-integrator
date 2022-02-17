<?php
	$pageIdTemp = $pageId;
	if($pageIdTemp != 'NEW_HOME')
    	echo recordTime('banner_start');

	if(isset($pageId) && isset($pageZone)) {
		if($pageId != '' && $pageZone!=''){
			$width =  constant(strtoupper($pageId) . '_PAGE_'. strtoupper($pageZone) .'_BANNER_WIDTH');
			$height =  constant(strtoupper($pageId) . '_PAGE_'. strtoupper($pageZone) .'_BANNER_HEIGHT');
			$zone = constant(strtoupper($pageId) . '_PAGE_'. strtoupper($pageZone) .'_BANNER_ZONE');
            $width +=2;
            $height +=4;
            $shikshaCriteriaCategory = $shikshaCriteriaCountry = $shikshaCriteriaCity = $shikshaCriteriaKeyword = $shikshaCriteriaLocation = '';
            if(isset($shikshaCriteria) && is_array($shikshaCriteria)) {
                foreach($shikshaCriteria as $key => $value){
                	if($key == 'keyword' && strpos($value,'BMS_UK-IRELAND')!== FALSE){
						$value = str_replace('BMS_UK-IRELAND','BMS_UK_IRELAND',$value);
                	}
                    switch($key){
                        case 'category': $shikshaCriteriaCategory = $value == 1 ? '' : $value ; break;
                        case 'country': $shikshaCriteriaCountry = $value == 1 ? '' : $value ; break;
                        case 'city': $shikshaCriteriaCity = $value; break;
                        case 'keyword': $shikshaCriteriaKeyword = $value; break;
                        case 'location': $shikshaCriteriaLocation = $value; break;
                    }
                }
            }
            $shikshaCriteria = '';
            if($shikshaCriteriaCategory != '' || $shikshaCriteriaCountry != '' || $shikshaCriteriaCity != '' || $shikshaCriteriaKeyword != '' || $shikshaCriteriaLocation != '') {
                $shikshaCriteria = 'shikshaCriteria='. $shikshaCriteriaCategory .'|'. $shikshaCriteriaCountry .'|'. $shikshaCriteriaCity .'|' . htmlentities($shikshaCriteriaKeyword)." ".htmlentities($shikshaCriteriaLocation);
            } else {
                $shikshaCriteria = 'shikshaCriteria=|||';
            }
			if($course_key_id){
				$shikshaCriteria .= '&shikshaCourse='.$course_key_id;
			}
			
			if(isset($product) && strtolower($product) == "exampage") {
				$shikshaCriteria = 'shikshaCriteria=|||'.$examPageShikshaCriteria;
			}
			
			
			if(!(defined(strtoupper($pageId) . '_PAGE_'. strtoupper($pageZone) .'_BANNER_TYPE') && constant(strtoupper($pageId) . '_PAGE_'. strtoupper($pageZone) .'_BANNER_TYPE') == 'google')){
                if($width!='' && $height!=''){
                 $bannerAdSrc = 'https://www.ieplads.com/bmsjs/bms_display_final.php?zonestr='. $zone .'&showall=1&data=&'. $shikshaCriteria;
?>
                 <script>if(typeof pushBannerToPool == 'function'){pushBannerToPool('<?php echo $pageZone;?>','<?php echo $bannerAdSrc; ?>');}</script>
<IFRAME MARGINWIDTH=0 MARGINHEIGHT=0 HSPACE=0 VSPACE=0 FRAMEBORDER=0 SCROLLING=no BORDERCOLOR=#000000 FRAMEBORDER=0 ID=<?php echo $pageZone;?> HEIGHT=<?php echo $height;?> WIDTH=<?php echo  $width ;?> SRC = 'about:blank' style='display:block;margin:0 auto;'></IFRAME>
<?php
                    $shikshaCriteria = '';
				}
			} else {
				$adsLayout = constant(strtoupper($pageId) . '_PAGE_'. strtoupper($pageZone) .'_BANNER_AD_LAYOUT');;
?>
	<script>
		google_ad_client = 'ca-shiksha_js';
		google_ad_channel ='<?php echo constant(strtoupper($pageId) . '_PAGE_'. strtoupper($pageZone) .'_BANNER_ADS_CHANNEL');?>';
		google_ad_output = 'js';
		google_max_num_ads = '<?php echo constant(strtoupper($pageId) . '_PAGE_'. strtoupper($pageZone) .'_BANNER_NUM_ADS');?>';
		google_encoding = 'utf8';
		google_safe = 'high';
		google_adtest = 'off';
		google_feedback= "on";
		google_ad_type = "text";
		google_color_line = "FF0000";
		google_kw_type='broad';
		google_bid_type='cpc,cpm';
        <?php
            if(strtoupper($pageId) == 'LISTINGS' && strtoupper($pageZone) == 'FOOTER'){
        ?>
        google_skip=5;
        <?php }
        ?>
		/*google_hints = '';
		google_page_url = 'https://www.shiksha.com';
		google_language = 'it';
		google_kw = document.getElementById('google_keyword').value;
		google_kw_type = 'broad';
		google_contents = '';
		google_ad_section = 'default';
		*/		
	</script>
	<div style="border:1px solid #fff;overflow:hidden;">
        <?php
            global $shikshaAdsJsInclusion;
            if(!$shikshaAdsJsInclusion) {
                $shikshaAdsJsInclusion = true;
        ?>
		<script language="JavaScript" src="https://<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("shikshaAds$adsLayout"); ?>"></script>
        <?php }
        ?>
		 <?php if(0) { ?>
		<script language="JavaScript" src="https://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	<?php } ?>
	</div>
<?php
			}
		}
	}
	$pageZone = $pageId = '';
	
	if($pageIdTemp != 'NEW_HOME')
    	echo recordTime('banner_end');
?>
