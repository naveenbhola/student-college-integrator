<?php
$resultArrAll = unserialize(base64_decode($details['relatedListings'][0]['institutes']));
$resultArr = array();
for ($i = 0; $i < count($resultArrAll) && count($resultArr) <= 3; $i++) {
    $thisResult = (array) $resultArrAll[$i];
    if ($thisResult['id'] != $type_id && $listing_type == 'institute') {
        array_push($resultArr, $thisResult);
    }
    
}
?>

<div class="wdh100">
    <div class="nlt_head Fnt14 bld mb10">Shiksha Recommends</div>
    <div class="mlr5">
        <!--Repeat_data-->
            <?php 
    for($i = 0; $i < count($resultArr) && $i < 5; $i++) {
        $thisResult = (array)$resultArr[$i];
        $collegeName = $thisResult['title'];
        $url = $thisResult['url'];
        $param =
        $locationArr = unserialize($thisResult['locationArr']);
        $courseArr = unserialize($thisResult['courseArr']);
        echo "<pre>";
        //print_r($courseArr);
        echo "</pre>";
        $aimaRating = $thisResult['aimaRating'];
        $overallRating = $thisResult['overallRating'];
        $ratingSum=0;
        for($count = 0;$count<count($overallRating);$count++){
            $ratingSum += $overallRating[$count]['criteria_rating'];
            
        }
        $ratingSum = round($ratingSum/count($overallRating));
        ?>
        
        
        
        <div class="wdh100 mtb10">
            <div class="ln">&nbsp;</div>
            <div><a href="<?php echo $url;?>" class="bld"><?php echo $collegeName.", ".$locationArr['0']['0']['locality']['0']." ".$locationArr['0']['0']['city_name']['0'];?></a></div>
            <div class="lineSpace_6">&nbsp;</div>
            <div class="wdh100">
                <div class="Fnt11">
                    <?php if(!empty($aimaRating)){?>
                    <span class="float_L">AIMA Rating:</span>
                    <span class="aRating"><img src = "/public/images/<?php echo $aimaRating;?>.gif"/></span>
                    <?php }?>
                    <?php if($ratingSum!='0'){?>
                    <span class="float_L">Alumini Rating:</span>
                    <span class="float_L"><?php for($count=0;$count<$ratingSum;$count++){?><img src="/public/images/nlt_str_full.gif" border="0" align="absbottom" /><?php } echo $ratingSum."/5"?></span>
                    <?php }?>
                    <div class="clear_B">&nbsp;</div>
                </div>
                <?php echo "<pre>";
                      //print_r($courseArr['0']['0']['eligibility']);
                      echo "</pre>";
                ?>
                <div class="mt5 mb6"><a href="<?php echo $courseArr['0']['0']['url']['0'];?>"><?php echo $courseArr['0']['0']['title']['0'];?></a>
                    <span class="Fnt11">
                <?php echo "-".$courseArr['0']['0']['type']['0'];
                if($courseArr['0']['0']['duration_unit']['0']=='Years'){
                $courseArr['0']['0']['duration_unit']['0']= 'Year';
                }
                if($courseArr['0']['0']['duration_unit']['0']=='Weekss'){
                $courseArr['0']['0']['duration_unit']['0']= 'Week';
                }
                if($courseArr['0']['0']['duration_unit']['0']=='Months'){
                $courseArr['0']['0']['duration_unit']['0']= 'Month';
                }
                if($courseArr['0']['0']['duration_unit']['0']=='Weeks'){
                $courseArr['0']['0']['duration_unit']['0']= 'Week';
                }
                echo " ".$courseArr['0']['0']['duration_value']['0'];
                if($courseArr['0']['0']['duration_value']['0']>1){
                    echo $courseArr['0']['0']['duration_unit']['0']."s";
                    }else{
                        echo $courseArr['0']['0']['duration_unit']['0'];
                        }
                ?>
                    </span>
                </div>
                <div class="Fnt11 mb8"><?php if(count($courseArr['0']['0']['recognitionStatus']['0'])>0){?>
                        <?php foreach($courseArr['0']['0']['recognitionStatus']['0'] as $status){
                            if($status['attribute'] == 'AICTEStatus'){
                                echo "AICTE approved";
                            }elseif($status['attribute'] == 'UGCStatus'){
                                echo "UGC Recognized";
                            }
                            elseif($status['attribute'] == 'DECStatus'){
                                echo "DEC Recognized";
                            }
                        }
                            ?>
                    <span class="sepClr">|</span>
                    <?php }?>

                     <?php foreach($courseArr['0']['0']['salient']['0'] as $feature){
                    if($feature['feature_name'] == 'jobAssurance'){
                        if($feature['value'] == 'record'){
                            echo "100% Placement";
                        }
                    }

                    }?>
                </div>
                <div class="Fnt11 mb7">
                <?php if(($courseArr['0']['0']['fee']['0']['value'])!= ""){?><span class="fcdGya">Fee:</span><?php echo $courseArr['0']['0']['fee']['0']['unit']." ".$courseArr['0']['0']['fee']['0']['value'];?><?php } ?>
                <?php if(count($courseArr['0']['0']['eligibility']['0'])>0){?><span class="sepClr"></span><span class="fcdGya">Eligibility:</span>
                <?php
                                            $eligibility_arr = array();
                                            foreach($courseArr['0']['0']['eligibility']['0'] as $ex) {
                                                    $el = $ex['acronym'];
                                                array_push($eligibility_arr, $el);
                                            }
                                            echo implode(" <span class=\"sepClr\">|</span> ", $eligibility_arr);
                                                ?>
                                                <?php }?>
                </div>
                <div>
                    <?php if(count($courseArr['0']['0']['salient']['0'])>0){
                        $salient_features_arr = array();
                        foreach($courseArr['0']['0']['salient']['0'] as $ex){

                            $key = false;
                                                if($ex['feature_name']=='freeLaptop'){
                                               if($ex['value']=='yes'){
                                                    $value = "Free-Laptop";
                                                    $key = true;
                                                }
                                                }

                                                if($ex['feature_name']=='hostel'){
                                                if($ex['value']=='yes'){
                                                    $value = "In-campus hostel";
                                                    $key = true;
                                                }
                                                }

                                                if($ex['feature_name']=='transport'){
                                                if($ex['value']=='yes'){
                                                    $value = "Transport facility";
                                                    $key = true;
                                                }
                                                }

                                                if($ex['feature_name']=='freeTraining'){
                                                if($ex['value']=='english'){
                                                    $value = "Free Training English";
                                                    $key = true;
                                                }
                                                if($ex['value']=='sap'){
                                                    $value = "Free Training SAP";
                                                    $key = true;
                                                }
                                                if($ex['value']=='soft_skills'){
                                                    $value = "Free Training Soft Skills";
                                                    $key = true;
                                                }
                                                if($ex['value']=='foreign_language'){
                                                    $value = "Free Training Foreign Language";
                                                    $key = true;
                                                }
                                                }

                                                if($ex['feature_name']=='dualDegree'){
                                                if($ex['value']=='yes'){
                                                    $value = "MBA + PGDM – MBA + PGGM";
                                                    $key = true;
                                                }
                                                }

                                                if($ex['feature_name']=='jobAssurance'){
                                                if($ex['value']=='record'){
                                                    $value = "100% job track record";
                                                    $key = true;
                                                }
                                                if($ex['value']=='guarantee'){
                                                    $value = "100% job track guarantee";
                                                    $key = true;
                                                }
                                                if($ex['value']=='assurance'){
                                                    $value = "100% job track assurance";
                                                    $key = true;
                                                }
                                                }

                                                if($ex['feature_name']=='wifi'){
                                                if($ex['value']=='yes'){
                                                    $value = "Wifi Campus";
                                                    $key = true;
                                                }
                                                }
                                                if($ex['feature_name']=='foreignStudy'){
                                                if($ex['value']=='yes'){
                                                    $value = "Foreign Study Tour";
                                                    $key = true;
                                                }

                                                if($ex['feature_name']=='studyExchange'){
                                                if($ex['value']=='yes'){
                                                    $value = "Foreign Exchange Program";
                                                    $key = true;
                                                }

                                                }
                                            }
                                            if($key == true){
                                            array_push($salient_features_arr, $value);
                                            }
                        }
echo implode(" <span class=\"sprt_nlt sqrBlts Fnt11 mr15\">|</span> ", $salient_features_arr);
                            ?>

                    
                    <div class="ln">&nbsp;</div>
                    <?php }?>
                </div>
            </div>
            <div class="ln">&nbsp;</div>
        </div>
<? } ?>
        <!--Repeat_Data-->
    </div>
</div>
 <div class="lineSpace_20">&nbsp;</div>
