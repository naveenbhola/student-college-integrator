<style type="text/css">
.country-layer{background:#fff; border-bottom:1px solid #c5c5c5; padding:10px; font-family:Arial, Helvetica, sans-serif; float:left;}
.country-layer-cols{float:left; font-size:12px; margin-right:12px;}
.country-layer .last{margin-right:0}
.country-layer .first{margin-right:5px}
.country-layer .first label{padding-right:0}
.country-layer .first ul li{padding:0}
.country-layer-cols strong{font-weight:bold; margin-bottom:7px; display:block; width:125px}
.country-layer-cols ul{margin:10px 0 0 22px; padding:0; height:200px; overflow-y:auto; overflow-x:hidden; width:122px}
.country-layer-cols ul li{margin:0; 0padding:0 35px 0 0; list-style:none; margin-bottom:6px; display:block; width:100%;}
.country-layer-cols span input{margin:0; padding:0; vertical-align:middle; width:13px; height:13px}
.country-layer-cols span{width:19px; float:left;}
.country-layer-cols label{color:#3665e9; vertical-align:middle; margin-left:20px; display:block;}
.clearFix{clear:both}
.spacer15{height:15px; overflow:hidden}
</style>


<div id="userPreferenceCountry" style="width:535px;background-color:#ffffff;display:none;position:absolute;">
    <div id="countryOverlayForStudyAbroad" class="country-layer" >
    <?php
        $regionIdInUse = null;
        
        $sortIndex = array('',1,6,2,3,8,4,5,7);
        $sortedRegions = array();
        for($i=0;$i<count($sortIndex);$i++) {
            $sortedRegions[$sortIndex[$i]] = $regions[$sortIndex[$i]];
        }
        $regions = $sortedRegions;

        foreach($regions as $regionId => $region) {
            $i = 0;
            foreach($region as $countryId =>  $country) {
                $countryName = $country['name'];
                $regionName = $country['regionname'];
                $paddingLeft = 'padding-left:25px';
                $tagName = $regionName;
                if($i++==0){
                    if(empty($regionId))
                        echo '<div class="country-layer-cols first">
                                <strong>Popular Countries</strong>
                                <ul style="margin:0">';
                    else {
                        
                        if($regionId == 1 || $regionId == 3 || $regionId == 4) {
                            echo '<div style="float:left;">';
                        }
                        
                        
                        echo '<div class="country-layer-cols" '.($regionId == 4 || $regionId == 5 || $regionId == 1 || $regionId == 3 || $regionId == 6 ? "style='float:none;'" : "").'">';
                        
                        echo '<strong><span><input type="checkbox" id="region_'.$regionId.'" value="'.$regionId.'" onclick="toggleRegionCountries($(\'region_'.$regionId.'\'));" /></span> '.$regionName.'</strong>
                        <ul ';
                        if($regionId == 4) {
                            echo "style='height:50px;'";
                        }
                        if($regionId == 3) {
                            echo "style='height:110px;'";
                        }
                        if($regionId == 8) {
                            echo "style='height:90px;'";
                        }
                        else if($regionId == 1) {
                            echo "style='height:70px;'";
                        }
                        else if($regionId == 6) {
                            echo "style='height:130px;'";
                        }
                        else if($regionId == 5) {
                            echo "style='height:50px;'";
                        }
                        else if($regionId == 7) {
                            echo "style='height:60px;'";
                        }
                        echo '>';
                    }
                }
                if($regionId == '') {
                    $paddingLeft = '';
                    $tagName = $countryName;
                    $countryName = $countryName;
                }
                echo '<li region="'.$regionId.'">
                        <span><input type="checkbox" id="countryInput_'.$countryId.'" type="checkbox" name="ctry[]" value="'.$countryId.'" onclick="toggleRegionForCountry($(\'countryInput_'.$countryId.'\'));" tag="'.$tagName.'"/></span>
                        <label>'.$countryName.'</label>
                    </li>';
            }
            echo '</ul></div>';
            if($regionId == 7 || $regionId == 6  || $regionId == 8) {
                echo '</div>';
            }
        }
    ?>
    </div>
    <div class="spacer15 clearFix"></div>
    <div align="center"><input type="button" value="OK" class="okBtn" onClick="getDataFromCityLayer1();" /></div>
    <div class="spacer15 clearFix"></div>
</div>
