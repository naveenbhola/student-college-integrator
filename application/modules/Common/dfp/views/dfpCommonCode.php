<?php $this->load->config('dfp/dfpConfig');
global $dfpConfig;
$childPage = $dfpData['pageType'];
$pageType = $dfpData['parentPage'];
$pageTypeOrig = $dfpData['parentPage'];
if(!isset($pageType) || !array_key_exists($pageType, $dfpConfig['header'])){
        $pageType='Others';
}
?>
<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
<script>
  var googletag = googletag || {};
  googletag.cmd = googletag.cmd || [];

  googletag.cmd.push(function() {
    googletag.defineSlot("<?php echo $dfpConfig['header'][$pageType]['LB1']['adUnitPath'];?>", [<?php echo $dfpConfig['header'][$pageType]['LB1']['width'];?>, <?php echo $dfpConfig['header'][$pageType]['LB1']['height'];?>], "<?php echo $dfpConfig['header'][$pageType]['LB1']['opt_div'];?>").addService(googletag.pubads());
    <?php if($dfpData['parentPage']!='DFP_CategoryPage'){ ?>
    googletag.defineSlot("<?php echo $dfpConfig['header'][$pageType]['LB2']['adUnitPath'];?>", [<?php echo $dfpConfig['header'][$pageType]['LB2']['width'];?>, <?php echo $dfpConfig['header'][$pageType]['LB1']['height'];?>], "<?php echo $dfpConfig['header'][$pageType]['LB2']['opt_div'];?>").addService(googletag.pubads());
   <?php } ?>
   googletag.defineSlot("<?php echo $dfpConfig['footer']['adUnitPath'];?>", [<?php echo $dfpConfig['footer']['width'];?>,<?php echo $dfpConfig['header'][$pageType]['LB1']['height'];?>], "<?php echo $dfpConfig['footer']['opt_div'];?>").addService(googletag.pubads());
    googletag.defineSlot("<?php echo $dfpConfig['CookieBanner']['adUnitPath'];?>", [<?php echo $dfpConfig['CookieBanner']['width'];?>,<?php echo $dfpConfig['CookieBanner']['height'];?>], "<?php echo $dfpConfig['CookieBanner']['opt_div'];?>").addService(googletag.pubads());
   <?php 
   if(array_key_exists($dfpData['parentPage'], $dfpConfig['content']))
   {
      $tempConfig = $dfpConfig['content'][$dfpData['parentPage']];
      $tempKeys = array_keys($tempConfig);
      $isChild = strpos($x,"C_");
      if($childPage=="homepage")
      {
        foreach($tempKeys as $a=>$b)
        {
          if(strpos($b,"C_")!==false)
          {
            unset($tempConfig[$b]);
            unset($tempKeys[$a]);
          }
        }
      }
      else
      {
        $childPageArr = explode("_",$childPage);
        $subPageType = (array_key_exists(1, $childPageArr))?$childPageArr[1]:$childPageArr[0];
        if($subPageType=="admission" || $subPageType=="scholarships")
        {
          unset($tempConfig);
          $tempConfig['C_C1']=$dfpConfig['content'][$dfpData['parentPage']]['C_C1'];
          $tempConfig['C_C1_2']=$dfpConfig['content'][$dfpData['parentPage']]['C_C1_2'];
          $tempConfig['C_C2']=$dfpConfig['content'][$dfpData['parentPage']]['C_C2'];
          $tempConfig['C_C2_2']=$dfpConfig['content'][$dfpData['parentPage']]['C_C2_2'];
        }
        else
        {
          foreach ($tempKeys as $a => $b) 
          {
            if((strpos($b,"C_")===false && $subPageType !=="cutoff")|| ($subPageType=="questions" && strpos($b,"ANA_")===false) ||($subPageType!="questions" && strpos($b,"ANA_")!==false))
            {
              unset($tempConfig[$b]);
              unset($tempKeys[$a]);
            }
          }
        }
      }

      foreach($tempConfig as $x=>$y)
      {
        ?>
        googletag.defineSlot("<?php echo $y['adUnitPath'];?>",[<?php echo $y['width'];?>,<?php echo $y['height'];?>], "<?php echo $y['opt_div'];?>").addService(googletag.pubads());
        <?php
      }
      unset($tempConfig);
   }

   //Add Right panel slot. It will either be for main page or child page
   if(isset($dfpConfig['rightPanel'][$pageTypeOrig]['RP'])){ ?>
        googletag.defineSlot("<?php echo $dfpConfig['rightPanel'][$pageTypeOrig]['RP']['adUnitPath'];?>", [<?php echo $dfpConfig['rightPanel'][$pageTypeOrig]['RP']['width'];?>, <?php echo $dfpConfig['rightPanel'][$pageTypeOrig]['RP']['height'];?>], "<?php echo $dfpConfig['rightPanel'][$pageTypeOrig]['RP']['opt_div'];?>").addService(googletag.pubads());
   <?php
   }
   else if(isset($dfpConfig['rightPanel'][$pageTypeOrig]['C_RP'])){ ?>
        googletag.defineSlot("<?php echo $dfpConfig['rightPanel'][$pageTypeOrig]['C_RP']['adUnitPath'];?>", [<?php echo $dfpConfig['rightPanel'][$pageTypeOrig]['C_RP']['width'];?>, <?php echo $dfpConfig['rightPanel'][$pageTypeOrig]['C_RP']['height'];?>], "<?php echo $dfpConfig['rightPanel'][$pageTypeOrig]['C_RP']['opt_div'];?>").addService(googletag.pubads());
   <?php
   }

   ?>
    googletag.pubads().collapseEmptyDivs();
    
    <?php 
      $this->load->view('dfp/mmpformdisplay');
    ?>

    <?php foreach($dfpData as $key=>$val){ 
            if($val!=''){ 
                 if(is_array($val)){
                    $str = "'" . implode ( "', '", $val ) . "'";?>
                    googletag.pubads().setTargeting('<?php echo $key;?>', [<?php echo $str;?>]);
          <?php }else{ $val = str_replace(array("'",'"'), "", $val);?>
                    googletag.pubads().setTargeting('<?php echo $key;?>', '<?php echo $val;?>');
    <?php }}}?>
    googletag.enableServices();

  });
</script>

