<?php
global $hideCityForInstitutes;

$resultArrAll = unserialize(base64_decode($details['relatedListings'][0]['institutes']));
$resultArr = array();

for ($i = 0; $i < count($resultArrAll) && count($resultArr) <= 3; $i++) {
    $thisResult = (array) $resultArrAll[$i];
    if ($thisResult['id'] != $type_id && $listing_type == 'institute') {
        array_push($resultArr, $thisResult);
    }
}
if(!empty($resultArr)){

?>
<div class="nlt_head Fnt14 bld mb10">Also on Shiksha</div>
                            <!--Repeat_data-->
                            <div class="wdh100 mtb10">

<?php
        for($i = 0; $i < count($resultArr) && $i < 3; $i++) {
        $thisResult = (array)$resultArr[$i];
        $collegeName = $thisResult['title'];
	$id =  $thisResult['id'];
        $urlName = $thisResult['url'];
        $imgUrl = urldecode($thisResult['headerImageUrl']);
        $locationArr = unserialize($thisResult['locationArr']);
        $courseArr = unserialize($thisResult['courseArr']);
	$seoLocation = array('location'=>array($locationArr['0']['0']['locality']['0'],$locationArr['0']['0']['city_name']['0']));
	/*This was added on 14 Jan 2011,a day after we went live because the urls  are coming from search server but now this issue is solved 
	so commenting this code.  
	*/  
	//$urlName = getSeoUrl($id,'institute',$collegeName,$seoLocation);
        $aimaRating = $thisResult['aimaRating'];
        $overallRating = $thisResult['overallRating'];
        $ratingSum=0;
        for($count = 0;$count<count($overallRating);$count++){
            $ratingSum += $overallRating[$count]['criteria_rating'];

        }
        $ratingSum = round($ratingSum/count($overallRating));
?>
                               	<div><a href="<?php echo $urlName;?>" class="Fnt14 bld">
                                
                                <?php
                                /*
                                    Vikas K, July 13 2011 | Ticket #412
                                    Do not show city if we found institute's entry in hideCityForInstitutes global array
                                    defined in shikshaConstants.php
                                */
                                
                                if(isset($hideCityForInstitutes) && is_array($hideCityForInstitutes) && in_array($id,$hideCityForInstitutes))
                                {
                                    echo $collegeName;
                                }
                                else
                                {
                                    if(!empty($locationArr['0']['0']['locality']['0']))
                                    {
                                                echo $collegeName.", ".$locationArr['0']['0']['locality']['0'].", ".$locationArr['0']['0']['city_name']['0'];
                                    }
                                    else
                                    {
                                                echo $collegeName.", ".$locationArr['0']['0']['city_name']['0'];
                                    }
                                }
                                ?>
                                
                                </a></div>
                               	<div class="lineSpace_6">&nbsp;</div>
                               	<div class="wdh100">
                                    <?php if(!empty($imgUrl)){?>
                               	 	<div class="float_L w134"><img src="<?php echo $imgUrl;?>" border="0" /></div>
                                    <?php }else{?>
                                        <div class="float_L w134"><img src="/public/images/recommendation-default-image.jpg" border="0" /></div>
                                        <?php }?>
                                        <div class="ml135">
                                    	<div class="float_L wdh100">
                                            <div class="Fnt11">
                                                <?php if(!empty($aimaRating)){
						if($toolRating == 'SL'){
						  $toolRating = 'Super League';
						}else{
						$toolRating = $aimaRating;
						} 
						?>
                                                <span class="float_L">AIMA Rating:</span>
                                                <span class="aRating" onMouseOver = "showHideRecommendAimaToolTip('show','<?php echo $i?>')" onMouseOut = "showHideRecommendAimaToolTip('hide',<?php echo $i?>)"><img src = "/public/images/<?php echo $aimaRating;?>.gif"/></span>
<div style="postion:relative;display:none;" id = "recommend_aima_tool_tip_<?php echo $i?>">
<div style="position:absolute;margin:14px 0 0 80px">
<div style="position:relative;top:1px;left:10px"><img src="/public/images/rArw.gif" /></div>
<div style="float:left;background:url(/public/images/rbg.gif) left top repeat-x;height:20px;line-height:20px;padding:0 5px;border:1px solid #abcaf6;font-size:11px">AIMA Rating - <?php echo $toolRating;?></div>
</div>
</div>
                                                <?php }?>
                                                <?php if($ratingSum!='0'){?>
                    <span class="float_L">Alumni Rating:</span>
                    <span class="float_L"><?php for($count=0;$count<$ratingSum;$count++){?>&nbsp;<img src="/public/images/nlt_str_full.gif" border="0" align="absbottom" />&nbsp;<?php } echo $ratingSum."/5"?></span>
                    <?php }?>
                                                <div class="clear_B">&nbsp;</div>
                                            </div>
                                            <div class="mt5 mb6"><a href="<?php echo $courseArr['0']['0']['url']['0'];?>"><?php echo $courseArr['0']['0']['title']['0'];?></a>
                    <span class="Fnt11">
                <?php 
                if($courseArr['0']['0']['duration_unit']['0']=='Years'){
                $courseArr['0']['0']['duration_unit']['0']= 'Year';
                }
                if($courseArr['0']['0']['duration_unit']['0']=='Weekss'){
                $courseArr['0']['0']['duration_unit']['0']= 'Week';
                }
                if($courseArr['0']['0']['duration_unit']['0']=='Months'){
                $courseArr['0']['0']['duration_unit']['0']= 'Month';
                }
                if($courseArr['0']['0']['duration_unit']['0']=='Days'){
                $courseArr['0']['0']['duration_unit']['0']= 'Day';
                }
                if($courseArr['0']['0']['duration_unit']['0']=='Hours'){
                $courseArr['0']['0']['duration_unit']['0']= 'Hour';
                }
                echo "- ".$courseArr['0']['0']['duration_value']['0'];
                if($courseArr['0']['0']['duration_value']['0']>1){
                    echo " ".$courseArr['0']['0']['duration_unit']['0']."s";
                    }else{
                        echo " ".$courseArr['0']['0']['duration_unit']['0'];
                        }
                        echo ", ".$courseArr['0']['0']['type']['0'];
                ?>
                    </span>
                </div>
                                            <div class="Fnt11 mb8"><?php if(count($courseArr['0']['0']['recognitionStatus']['0'])>0){?>
                        <?php
                            $recognition = array();
                            foreach($courseArr['0']['0']['recognitionStatus']['0'] as $status){
                            if($status['attribute'] == 'AICTEStatus'){
                                $recognition[] = " AICTE approved";
                            }elseif($status['attribute'] == 'UGCStatus'){
                                $recognition[] = " UGC Recognized";
                            }elseif($status['attribute'] == 'DECStatus'){
                                $recognition[] = " DEC Approved";
                            }
                        }
                        echo implode(", ",$recognition);
                            ?>
                    
                    <?php }?>

                     <?php /*foreach($courseArr['0']['0']['salient']['0'] as $feature){
                    if($feature['feature_name'] == 'jobAssurance'){
                        if($feature['value'] == 'record'||$feature['value'] == 'guarantee'||$feature['value'] == 'assurance'){
                            echo "<span class=\"sepClr\">|</span>";
                            if($feature['value'] == 'record'){
                            echo "100% job track record";
                            }
                            if($feature['value'] == 'guarantee'){
                            echo "100% job guarantee";
                            }
                            if($feature['value'] == 'assurance'){
                            echo "100% job track assistance";
                            }
                        }
                    }

                    }*/?>
                                            </div>
                                         <div class="Fnt11 mb7">
                                            <?php if(($courseArr['0']['0']['fee']['0']['value'])!= ""){?><span class="fcdGya">Fees: </span><?php echo $courseArr['0']['0']['fee']['0']['unit']." ".$courseArr['0']['0']['fee']['0']['value'];?><?php } ?>
                                            <?php
                                            $eligibility_arr = array();
                                            foreach($courseArr['0']['0']['eligibility']['0'] as $ex) {
                                                if(!empty($ex['acronym'])){
                                                //$el = $ex['acronym']."-".$ex['marks']." ".$ex['marks_type'];
                                                  $el = $ex['acronym'];

                                                array_push($eligibility_arr, $el);
                                                }
                                            }?>
                                            <?php if(count($eligibility_arr)>0){?>
                                            <span class="sepClr"></span>
                                            <span class="fcdGya">
                                            <?php if(($courseArr['0']['0']['fee']['0']['value'])!= ""){echo " | ";}?>
                                            <?php if($instituteType == '1'){
                                                    echo " Eligibility:";
                                                    }elseif($instituteType == '2'){
                                                        echo " Exam Prepared For:";

                                                        }?>
                                            </span>
                                            <?php echo implode("<span class=\"sepClr\">, </span>", $eligibility_arr);
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
                                                    $value = "MBA + PGDM";
                                                    $key = true;
                                                }
                                                }

                                                if($ex['feature_name']=='jobAssurance'){
                                                if($ex['value']=='record'){
                                                    $value = "100% job track record";
                                                    $key = true;
                                                }
                                                if($ex['value']=='guarantee'){
                                                    $value = "100% job  guarantee";
                                                    $key = true;
                                                }
                                                if($ex['value']=='assurance'){
                                                    $value = "100% placement assistance";
                                                    $key = true;
                                                }
                                                }

                                                if($ex['feature_name']=='wifi'){
                                                if($ex['value']=='yes'){
                                                    $value = "Wifi Campus";
                                                    $key = true;
                                                }
                                                }

                                                if($ex['feature_name']=='acCampus'){
                                                if($ex['value']=='yes'){
                                                    $value = "AC Campus";
                                                    $key = true;
                                                }
                                                }
                                                if($ex['feature_name']=='foreignStudy'){
                                                if($ex['value']=='yes'){
                                                    $value = "Foreign Study Tour";
                                                    $key = true;
                                                }
                                                }
                                                if($ex['feature_name']=='studyExchange'){
                                                    if($ex['value']=='yes'){
                                                    $value = "Foreign Exchange Program";
                                                    $key = true;
                                                    }
                                                }
                                            if($key == true){
                                            array_push($salient_features_arr, $value);
                                            }

                        ?>

                    <?php }
                    foreach($salient_features_arr as $salient){
                    echo " <span class=\"sprt_nlt sqrBlts Fnt11 mr15\">".$salient."</span>" ;
                    }
                    ?>
                    <!--<span class="sprt_nlt sqrBlts Fnt11 mr15"><?php echo $feature['feature_name'];?></span>-->
                    <div class="ln">&nbsp;</div>
                    <?php }?>
											</div>
										</div>
                                    </div>
                                    <div class="clear_B">&nbsp;</div>
								</div>
                                <div class="ln">&nbsp;</div>
<?php }?>
                                
                            </div>
                            
<?php }?>
