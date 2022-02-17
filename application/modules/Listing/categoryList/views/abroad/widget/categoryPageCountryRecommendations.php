<?php
    $tTitle = substr($catPageTitle,0,strrpos($catPageTitle," in "));
    $tTitle = str_ireplace('colleges','',$tTitle);
?>
<?php if(!empty($recommendedCountryData)){ ?>
<li style="background-color:transparent;border:none;" class="clearwidth">
    <div style="text-align:center; margin:10px 0 6px;" class="download-widget clearwidth">
        <i class="abroad-shadow abroad-shadow-top"></i>
        <p class="mba-count-hd">
            Browse <?= $tTitle ?> colleges in other countries
        </p>
        <div style="padding:30px 20px 15px;">
        <div class="new-slider-box">
            <a class="ui-arrow lft-arrw recommendedCountriesLeft"><i class="prv-disbl"></i></a>
            <div class="recommendedCollegesViewport">
                <ul class="mba-count-list recommendedCountries-list">
                    <?php 
                        $index = 0;
                        $sliderIndicatorCount =0;
                        foreach($recommendedCountryData as $country){
                            if($index%4 == 0){ $sliderIndicatorCount++;?>
                                <li>
                    <?php   }
                    ?>
                            <div style="">
                                <i style="vertical-align:top;" class="flags <?=str_replace(' ','',strtolower($country['countryData']->getName()))?> flLt"></i>
                                <div class="mba-count-txt">
                                    <p class="mba-count-name"><a href="<?=$country['url']?>"><?=$tTitle." in ".$country['countryData']->getName()?></a></p>
                                    <p class="mba-collage-count"><?=$country['collegeCount']?> college<?=$country['collegeCount']==1?'':'s'?></p>
                                </div>

                            </div>
                    <?php 
                            if($index%4 == 3){?>
                                </li>
                    <?php   }
                            $index++;
                        } 
                    ?>
                </ul>
            </div>
            <a class="ui-arrow rgt-arrw recommendedCountriesRight"><i class="<?php echo count($recommendedCountryData)>4 ?'nxt':'nxt-disbl'?>"></i></a>
        </div>
        </div>
        <div>
        
        <div class="slider-indicator padTop recommendedCountriesSliderInd">
            <ul>
                <?php
                for($i=0;$i<$sliderIndicatorCount;$i++){ ?> 
                <li><a class="dot-pt <?php echo (($i==0)?"active":""); ?>"></a></li>
                <?php } ?>
            </ul>
            </div>
            
        </div>
            <i class="abroad-shadow abroad-shadow-bottom"></i>
    </div>  
</li>
<?php } ?>