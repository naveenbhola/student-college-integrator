<?php

$GA_TAP_ON_BRANCH = 'CHANGE_BRANCH';
$GA_TAP_ON_HELP_TEXT = 'HELP_TEXT';
$GA_Tap_On_Shortlist = 'SHORTLIST';
$GA_TAP_ON_ANSWERED_QUESTIONS = 'Header_AnsweredQuestions';
$Answered_Test = 'Answered Questions';

if($anaWidget['count'] == 1){
  $Answered_Test = 'Answered Question'; 
}

$dataPoints             = array();
$ReviewsData            = array(); 
$counterforReviewData = 0;
    $inlineData             = $topCardData['inlineData'];
    $instituteImportantData = $topCardData['instituteImportantData'];
    $toolTipData = $this->config->item("instituteToolTipData");
    $counter = 0;
    if(!empty($aggregateReviewsData) || !empty($anaWidget) ){
        if(!empty($aggregateReviewsData)){
        $html = "<div class=new_rating>";
        $html .= $this->load->view("mobile_listing5/aggregateReviewInterlinkingWidgetAmp", array('allReviewsUrl' => $instituteObj->getAllContentPageUrl('reviews'), 'aggregateReviewsData' => $aggregateReviewsData, 'reviewsCount' => $reviewWidget['count'],'isPaid' => $instituteIsPaid,'id'=>'institute'.$instituteObj->getId(),'forAnsweredQuestions'=>1), true);
            if($anaWidget['count']>0){
                $html .= "| ";
            }
        }
            if($anaWidget['count']>0){
                $html .= "<a class=\"view_rvws qstn ga-analytic\" data-vars-event-name='Header_AnsweredQuestions' href=".$anaWidget['allQuestionURL']."> <i class=qstns_ico></i>".formatNumber($anaWidget['count'])." ".$Answered_Test."</a>";
            }
            if(!empty($aggregateReviewsData)){
                $html .= "</div>";
            }
        $ReviewsData[$counterforReviewData]['html'] = $html;
        $ReviewsData[$counterforReviewData]['type'] = 'review';
        $counterforReviewData++;
    }
    if($inlineData['ownership']){
        $dataPoints[$counter]['html'] = "<span class='font-w6 f12 color-3'>".$inlineData['ownership']."</span>";
        $dataPoints[$counter]['type'] = 'ownership';
        $counter++;
    }
    if($inlineData['estbYear']){
        $dataPoints[$counter]['html'] = "<span class='font-w6 f12 color-3'>Established ".$inlineData['estbYear']."</span>";
        $dataPoints[$counter]['type'] = 'estbYear';
        $counter++;
    }
    if($inlineData['studentType']){
        $dataPoints[$counter]['html'] = "<span class='font-w6 f12 color-3'>".$inlineData['studentType']."</span>";
        $dataPoints[$counter]['type'] = 'studentType';
        $counter++;
    }

    if($instituteImportantData['ugc_approved']){
        $dataPoints[$counter]['html'] = "<span class='font-w6 f12 color-3'>".$instituteImportantData['ugc_approved']."</span>";
        $dataPoints[$counter]['type'] = 'ugc_approved';
        $counter++;
    }
    foreach ($instituteImportantData as $key => $value) {
        if (preg_match('/university_type_[a-z_]*/',$key)){
            $dataPoints[$counter]['html'] = "<span class='font-w6 f12 color-3'>".$instituteImportantData[$key]."</span>";
            $dataPoints[$counter]['type'] = $key;
            $counter++;
            break;
        }
    }
    if($instituteImportantData['autonomous']){
        $dataPoints[$counter]['html'] = "<span class='font-w6 f12 color-3'>".$instituteImportantData['autonomous']."</span>";
        $dataPoints[$counter]['type'] = 'autonomous';
        $counter++;
    }
    if($instituteImportantData['nationalImportance']){
        $dataPoints[$counter]['html'] = "<span class='font-w6 f12 color-3'>".$instituteImportantData['nationalImportance']."</span>";
        $dataPoints[$counter]['type'] = 'nationalImportance';
        $counter++;
    }
    if($instituteImportantData['naac_accreditation']){
        $dataPoints[$counter]['html'] = "<span class='font-w6 f12 color-3'>".$instituteImportantData['naac_accreditation']."</span>";
        $dataPoints[$counter]['type'] = 'naac_accreditation';
        $counter++;
    }
    if($instituteImportantData['aiu_member']){
        $dataPoints[$counter]['html'] = "<span class='font-w6 f12 color-3'>".$instituteImportantData['aiu_member']."</span>";
        $dataPoints[$counter]['type'] = 'aiu_member';
        $counter++;
    }

    $singleColumnData = array();
    if($instituteImportantData['rank']){
        $singleColumnData[$counter]['html'] = $instituteImportantData['rank'];
        $singleColumnData[$counter]['type'] = 'rank';
        $counter++;
    }
    if($instituteImportantData['affiliation']){
        $singleColumnData[$counter]['html'] = $instituteImportantData['affiliation'];
        $singleColumnData[$counter]['type'] = 'affiliation';
        $counter++;
    }
    //_P($anaWidget);die;
    $dataPoints = array_chunk($dataPoints, 2);
    $cityName = $currentLocationObj->getCityName();
    if(strpos(strtolower($topCardData['instituteName']), strtolower($cityName)) !== false){
        if($currentLocationObj->getLocalityName()){
        $locationName = $currentLocationObj->getLocalityName();
        }
    }
    else{
        $locationName = $cityName;
        if($currentLocationObj->getLocalityName()){
            $locationName = $currentLocationObj->getLocalityName().", ".$cityName;
        }
    }
  ?>

<!--basic info-->
         <div class="data-card">
             <div class="card-cmn color-w">
                <div class="pos-rl">
                   <h1 class="color-3 f16 font-w6 pos-rl"><?php echo htmlentities($topCardData['instituteName']);?>
                     <?php if(!empty($locationName)){ ?>
                        <p class="loc-col f12 color-6 font-w4"><i class="loc-i"></i><?php echo $locationName;?></p>   
                     <?php } ?>   
                     </h1>
                    <?php  if($isMultilocation && $seeAllBranchesData['hasLocation'] && $seeAllBranchesData['locationCount'] > 1){ ?>
                      <a class="color-b f12 i-block v-arr chng-arw ga-analytic font-w6" on="tap:change-location" role="button" tabindex="0" data-vars-event-name="<?=$GA_TAP_ON_BRANCH;?>">Change branch<i class="arw-icn"></i></a>
                    <?php } ?>
                </div>
                <?php if($ReviewsData){ 
                    echo $ReviewsData[0]['html'];
                }?>
                <?php
                if($dataPoints || $singleColumnData){
                    ?>
                 <div class="">
                     <ul class="m-5top cli-i">
                         <?php
                        foreach ($dataPoints as $dataPointsRows) {
                        echo "<li class='v-top'>";
                        foreach ($dataPointsRows as $key=>$dataPointSingleRow) {?>
                             <div class="tab-cell-b t-width-b">
                                <?php
                            echo $dataPointSingleRow['html'];
                            if(array_key_exists($dataPointSingleRow['type'], $toolTipData))
                                echo '<a class="pos-rl ga-analytic" on="tap:view-info-'.$dataPointSingleRow['type'].'" role="button" tabindex="0" data-vars-event-name="'.$GA_TAP_ON_HELP_TEXT.'"><i class="cmn-sprite clg-info i-block v-mdl"></i></a>';
                            if(in_array($dataPointSingleRow['type'], array('affiliation', 'rank')) ){
                            ?>
                             <a class="block color-b f12 font-w6" on="tap:more-data" role="button" tabindex="0"><?php echo $topCardData['detailedData'][$dataPointSingleRow['type']]; ?></a>
                            <?php
                            }
                            ?>
                             </div>
                <?php
                        }
                        echo "</li>";

                    }
                ?>
                </ul>
                <?php
                foreach ($singleColumnData as $key => $dataPointSingleRow) {
                ?>
                     <div class="f12 color-3 font-w6 m-5top">
                        <?php
                            echo $dataPointSingleRow['html'];
                            if(array_key_exists($dataPointSingleRow['type'], $toolTipData))
                                echo '<a class="pos-rl ga-analytic" on="tap:view-info-'.$dataPointSingleRow['type'].'" role="button" tabindex="0" data-vars-event-name="'.$GA_TAP_ON_HELP_TEXT.'"><i class="cmn-sprite clg-info i-block v-mdl"></i></a>';
                            if(in_array($dataPointSingleRow['type'], array('affiliation', 'rank')) ){
                            ?>
                             <a class="block color-b f12 font-w6" on="tap:more-data" role="button" tabindex="0"><?php echo $topCardData['detailedData'][$dataPointSingleRow['type']]; ?></a>
                            <?php
                            }
                            ?>

                     </div>
                     <?php
                }
            ?>

                 </div>
                 <?php
            }
        ?>
		 <a class="ga-analytic" href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingType=<?=$listing_type?>&listingId=<?=$listing_id?>&actionType=shortlist&fromwhere=<?=$listing_type?>" data-vars-event-name="<?=$GA_Tap_On_Shortlist;?>">
                        <p class="btn btn-primary color-o color-f f14 font-w7 m-15top" tabindex="0" role="button">Shortlist</p>
                    </a>
                 </div>
             </div>

 <!--amp view info lighthbox-->
 <?php foreach ($instituteImportantData as $key => $value) {?>
  <amp-lightbox class="" id="view-info-<?php echo $key?>" layout="nodisplay" scrollable>
       <div class="lightbox">
         <a class="cls-lightbox f25 color-f font-w6" on="tap:view-info-<?php echo $key?>.close" role="button" tabindex="0">&times;</a>
          <div class="m-layer">
             <div class="min-div color-w pad10">
                <strong class="block m-5btm color-3 f14 font-w6"><?php echo $toolTipData[$key]['name']?></strong>
                <p class="color-3 l-18 f12"><?php echo $toolTipData[$key]['helptext'] ?></p>
             </div>
          </div>
        </div>
  </amp-lightbox>
  <?php } ?>
