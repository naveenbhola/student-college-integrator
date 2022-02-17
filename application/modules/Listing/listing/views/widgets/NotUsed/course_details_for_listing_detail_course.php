<?php
// echo "<pre>"; print_r($course_details); echo "</pre>";

$params = array('instituteId'=>$details['institute_id'],'instituteName'=>$details['title'],'type'=>'course','locality'=>$details['location']['0']['locality'],'city'=>$details['location']['0']['city_name'],'courseName'=>$details['courseDetails']['0']['title'],'courseId'=>$details['courseDetails']['0']['course_id']);
// echo "TAB = ".$tab."<pre>"; print_r($params); echo "</pre>";
if($overviewTabUrl == ''){
    $overviewTabUrl = listing_detail_overview_url($params);
}
$courseTabUrl = listing_detail_course_url($params);
?>
<?php
// $course_details = $details['courseDetails'][0];
//echo "<pre>-------<br/>";print_r($course_details);echo "</pre>";
$course_attributes = $course_details['courseAttributes'];
$cattr = array();
foreach($course_attributes as $attr){
    $cattr[$attr['attribute']] = $attr['value'];
}
?>
<div id="course_details">
<div class="Fnt11 bld mb6" style="color:#5b5b5b">
<?php
    if($course_details['duration_unit']=='Years'){
        $course_details['duration_unit']= 'Year';
    }
    if($course_details['duration_unit']=='Weeks'){
        $course_details['duration_unit']= 'Week';
    }
    if($course_details['duration_unit']=='Months'){
        $course_details['duration_unit']= 'Month';
    }
    if($course_details['duration_unit']=='Days'){
        $course_details['duration_unit']= 'Day';
    }
    if($course_details['duration_unit']=='Hours'){
        $course_details['duration_unit']= 'Hour';
    }
    echo $course_details['duration_value'];
    if($course_details['duration_value']>1)
    {
    echo " ".$course_details['duration_unit']."s";
    }else{
    echo " ".$course_details['duration_unit'];
    }
    echo ", ".$course_details['course_type']
?>
</div>
                                    <?php if($instituteType==1){?>
                                    <div class="Fnt11 mb8">
                                            <?php
                                            $recognition = array();
                                            if($cattr['AICTEStatus'] == 'yes')
                                                $recognition[] = "AICTE Approved";
                                            if($cattr['UGCStatus'] == 'yes')
                                                $recognition[] = "UGC Recognised";
                                            if($cattr['DECStatus'] == 'yes')
                                                $recognition[] = "DEC Approved";
                                            echo implode(",",$recognition);
                                            if( !empty($cattr['AffiliatedTo'])) echo " (Affilated to ".$cattr['AffiliatedToName'].")";
                                            ?>
                                             <?php if($cattr['AffiliatedToIndianUni']=='yes'|| $cattr['AffiliatedToForeignUni']=='yes'||$cattr['AffiliatedToDeemedUni']=='yes'||$cattr['AffiliatedToAutonomous']=='yes'){?>

                                             <?php if($cattr['AffiliatedToIndianUni']=='yes'){
                                                        echo " (Affiliated to ".$cattr['AffiliatedToIndianUniName'].")";
                                                    }

                                                    if($cattr['AffiliatedToForeignUni']=='yes'){
                                                        echo " (Affiliated to ".$cattr['AffiliatedToForeignUniName'].")";
                                                    }

                                                    if($cattr['AffiliatedToDeemedUni']=='yes'){
                                                        echo " (Affiliated to Deemed University)";
                                                    }

                                                    if($cattr['AffiliatedToAutonomous']=='yes'){
                                                        echo " (Affiliated to Autonomous University)";
                                                    }

                                             ?>

                                                <?php }?>

                                                </div>

                                             <div>
                                            <ul class="rndBlts">
                                            <?php if(!empty($cattr['AccreditedBy'])) {?>
                                            <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Accrediation:</strong> <?php echo $cattr['AccreditedBy'];?></li>
                                            <? } ?>
                                            </ul>
                                             </div>
                                     <?php }?>
                                             <div>
                                                 <ul class="rndBlts">
                                            <?php if(!empty($course_details['fees_value'])) {?>
                                            <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Fees:</strong> <?php echo $course_details['fees_unit']?> <?php echo $course_details['fees_value']?></li>
                                            <? } ?>

                                            <?php if((!empty($course_details['seats_total']))||(!empty($course_details['seats_general']))||(!empty($course_details['seats_management']))||(!empty($course_details['seats_reserved']))) {
                                                $seats = array();?>
                                            <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Seats:</strong>
                                            <?php if(!empty($course_details['seats_total'])){
                                                $seats[] = "Total"."-".$course_details['seats_total'];
                                            }?>
                                             <?php if(!empty($course_details['seats_general'])){
                                                $seats[] = "General"."-".$course_details['seats_general'];
                                            }?>
                                             <?php if(!empty($course_details['seats_management'])){
                                                $seats[] = "Management"."-".$course_details['seats_management'];
                                            }?>
                                            <?php if(!empty($course_details['seats_reserved'])){
                                                $seats[] = "Reserved"."-".$course_details['seats_reserved'];
                                            }?>
                                             <?php echo implode(" <span class=\"sepClr\">|</span> ", $seats);   ?>
                                            </li>
                                            <? } ?>

                                            <?php if(!empty($course_details['courseExams'])){?>
                                            <?php if($instituteType == 1){?>
                                            <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Eligibility:</strong>
                                                <?php }else{?>
                                                <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Exams Prepared for:</strong>
                                                <?php }?>

                                            <?php
                                            $eligibility_arr = array();
                                            foreach($course_details['courseExams'] as $ex) {
                                                if(!empty($ex['marks'])){
                                                if($ex['marks_type'] == 'total_marks'){
                                                    $ex['marks_type'] = 'marks';
                                                }
                                                $el = $ex['acronym']."-".$ex['marks']." ".$ex['marks_type'];
                                                }else{
                                                    $el = $ex['acronym'];
                                                }
                                                if(!empty($ex['practiceTestsOffered'])){
                                                    $el = $ex['acronym']."(".$ex['practiceTestsOffered'].")";
                                                }
                                                array_push($eligibility_arr, $el);
                                            }
                                            if(!empty($cattr['otherEligibilityCriteria'])){
                                                $oec = $cattr['otherEligibilityCriteria'];
                                                array_push($eligibility_arr, $oec);
                                            }
                                            echo implode(" <span class=\"sepClr\">|</span> ", $eligibility_arr);

                                                ?>

                                            </li>
                                            <?php }else{
                                                if(!empty($cattr['otherEligibilityCriteria'])){
                                                $eligibility_arr = array();
                                                $oec = $cattr['otherEligibilityCriteria'];
                                                array_push($eligibility_arr, $oec);
                                                ?>
                                            <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Eligibility:</strong>
                                            <?php
                                            echo implode(" <span class=\"sepClr\">|</span> ", $eligibility_arr);
                                            }}?></li>
                                            <?php if($instituteType==1){?>
                                            <?php if(!empty($course_details['courseFeatures'])){?>
                                            <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Salient Features:</strong>
                                                <?php
                                            $salient_features_arr = array();

                                            foreach($course_details['courseFeatures'] as $ex) {
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
                                                    $value = "100% job guarantee";
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
                                            }
                                            echo implode(" <span class=\"sepClr\">|</span> ", $salient_features_arr);
                                                ?>
                                                </li>
                                                <?php }?>
                                                 <?php }else{?>
                                                <?php if(!empty($cattr)){?>
                                                <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Salient Features:</strong>
                                                    <?php
                                                    $salient_features_arr = array();
                                                    if($cattr['morningClasses']=='yes')
                                                        array_push($salient_features_arr, 'Morning Classes');
                                                    if($cattr['eveningClasses']=='yes')
                                                        array_push($salient_features_arr, 'Evening Classes');
                                                    if($cattr['weekendClasses']=='yes')
                                                        array_push($salient_features_arr, 'Weekend Classes');

                                                    echo implode(" <span class=\"sepClr\">|</span> ", $salient_features_arr);
                                                    ?>
                                                <?php }?>
                                                    </li>
                                                <?php }?>

                                        </ul>
                                    </div> <?php

            $params = array('instituteId'=>$details['institute_id'],'instituteName'=>$details['title'],'type'=>'course','locality'=>$details['location']['0']['locality'],'city'=>$details['location']['0']['city_name'],'courseName'=>$course_details['title'],'courseId'=>$course_details['course_id']);
            $url = listing_detail_overview_url($params);    ?>
                            <div class="mt2 mb10"><a class="Fnt11" href="<?php echo $url;?>">View Course Details</a></div>
                                    <div class="lineSpace_15">&nbsp;</div>
</div>