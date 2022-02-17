<?php
    $tTitle = substr($catPageTitle,0,strrpos($catPageTitle," in "));
    $tTitle = str_ireplace('colleges','',$tTitle);
?>
<?php if(!empty($recommendedCountryData)){ ?>
<section>
    <div class="browse-country-widget clearfix pb2">
        <p>Browse <?=$tTitle ?> colleges in other countries</p>
    </div>
    <div class="browse-country-widget clearfix pt2">
        <ul id="countryWidgetUL">
            <?php foreach($recommendedCountryData as $country){?>
            <li>
                <div class="college-country-detail-col">
                    <div class="college-country-flag flLt"><i class="flags-sprite <?=str_replace(" ",'',strtolower($country['countryData']->getName()))?>"></i></div>
                    <div class="college-country-course">
                        <a href="<?=$country['url']?>"><?=$tTitle." in ".$country['countryData']->getName()?></a>
                        <span><?=$country['collegeCount']?> college<?=$country['collegeCount']==1?'':'s'?></span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </li>
            <?php } ?>
        </ul>
    </div>
</section>
<?php } ?>