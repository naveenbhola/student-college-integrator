<!-- <script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script> -->
<script>
  var googletag = googletag || {};
  googletag.cmd = googletag.cmd || [];
</script>
<?php 
    if(!in_array($mobilePageName,array('campusAmbassador','writeForUs','CollegeReviewForm'))){
        global $DFP_BANNER_CONFIG_MOBILE;
          $currentConfig = $DFP_BANNER_CONFIG_MOBILE[$dfpData['parentPage']];
          if(empty($currentConfig))
          {
              //$currentConfig = $DFP_BANNER_CONFIG_MOBILE['DFP_Others'];
          }
          if(!array_key_exists('leaderboard', $currentConfig))
          {
            //$currentConfig['leaderboard'] = $DFP_BANNER_CONFIG_MOBILE['DFP_Others']['leaderboard']; 
          }    
    }
	?>

<script>
  googletag.cmd.push(function() {
    <?php foreach ($currentConfig as $dfpKey => $dfpValue) {?>
          googletag.defineSlot('<?=$dfpValue['slotId']?>', [<?=$dfpValue['width']?>, <?=$dfpValue['height']?>], '<?=$dfpValue['elementId']?>').addService(googletag.pubads());
    <?php }?>

    <?php if(!empty($DFP_BANNER_CONFIG_MOBILE['shiksha']['footer']) && !in_array($mobilePageName,array('campusAmbassador','writeForUs','CollegeReviewForm'))) { 

          $footerConfig = $DFP_BANNER_CONFIG_MOBILE['shiksha']['footer'];
      ?>
          googletag.defineSlot('<?=$footerConfig['slotId']?>', [<?=$footerConfig['width']?>, <?=$footerConfig['height']?>], "<?=$footerConfig['elementId']?>").addService(googletag.pubads());
    <?php }?>

    googletag.pubads().collapseEmptyDivs();
    <?php 
    if(!in_array($mobilePageName,array('campusAmbassador','writeForUs','CollegeReviewForm')))
    {
      foreach($dfpData as $key=>$val){ 
            if($val!=''){ 
                 if(is_array($val)){
                    $str = "'" . implode ( "', '", $val ) . "'";?>
                    googletag.pubads().setTargeting('<?php echo $key;?>', [<?php echo $str;?>]);
          <?php }else{ $val = str_replace(array("'",'"'), "", $val);?>
                    googletag.pubads().setTargeting('<?php echo $key;?>', '<?php echo $val;?>');
    <?php }}}} ?>
    googletag.enableServices();
  });
</script>
