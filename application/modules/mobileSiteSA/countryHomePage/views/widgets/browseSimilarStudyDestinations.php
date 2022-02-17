<?php $countryRecommendations = $coursesData[$mainCourseId]['countryRecommendations'];?>
<section class="countryReco<?php echo $mainCourseId;?>">
    <div class="browse-country-widget clearfix" style="padding-bottom: 2px !important;overflow: hidden !important;">
        <p>Study destinations similar to <?php echo $countryObj->getName(); ?> </p>
    </div>
    <div class="browse-country-widget clearfix" style="padding-top: 2px !important; overflow-x: scroll;overflow-y: hidden;">
        <ul id="countryWidgetUL" style= "width:<?php echo (20+(count($countryRecommendations)*135))?>px;">
            <?php foreach($countryRecommendations as $country){?>
            <li>
                
                    <div class="college-country-detail-col">
                        <div class="college-country-flag flLt"><i class="flags-sprite <?=str_replace(" ",'',strtolower($country['countryObj']->getName()))?>"></i></div>
                        <div class="college-country-course">
                            <a href="<?php echo $country['canonicalUrl']; ?>"><?php $headingArr = explode(" - ",$country['seoTitle']); echo $headingArr[0];?></a>
                        </div>
                    </div>
                <div class="clearfix"></div>
            </li>
            <?php } ?>
        </ul>
    </div>
</section>

