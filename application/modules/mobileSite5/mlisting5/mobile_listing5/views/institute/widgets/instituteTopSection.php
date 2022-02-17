<?php
    $GA_TAP_ON_BRANCH = 'CHANGE_BRANCH';
    $GA_TAP_ON_HELP_TEXT = 'HELP_TEXT';
    $GA_Tap_On_Shortlist = 'SHORTLIST'; 
    if($listing_type == 'university')
    {
        $shortlistTrackingKeyId = 1080;
    }
    else
    {
        $shortlistTrackingKeyId = 950;
    }

    $dataPoints             = array();
    $inlineData             = $topCardData['inlineData'];
    $instituteImportantData = $topCardData['instituteImportantData'];
    $toolTipData = $this->config->item("instituteToolTipData");

    $counter = 0;
    if($inlineData['ownership']){
        $dataPoints[$counter]['html'] = "<span>".$inlineData['ownership']."</span>";
        $dataPoints[$counter]['type'] = 'ownership';
        $counter++;
    }
    if($inlineData['estbYear']){
        $dataPoints[$counter]['html'] = "<span>Established ".$inlineData['estbYear']."</span>";
        $dataPoints[$counter]['type'] = 'estbYear';
        $counter++;
    }
    if($inlineData['studentType']){
        $dataPoints[$counter]['html'] = "<span>".$inlineData['studentType']."</span>";
        $dataPoints[$counter]['type'] = 'studentType';
        $counter++;
    }
    if($instituteImportantData['ugc_approved']){
        $dataPoints[$counter]['html'] = "<span>".$instituteImportantData['ugc_approved']."</span>";   
        $dataPoints[$counter]['type'] = 'ugc_approved';
        $counter++;
    }
    foreach ($instituteImportantData as $key => $value) {
        if (preg_match('/university_type_[a-z_]*/',$key)){
            $dataPoints[$counter]['html'] = "<span>".$instituteImportantData[$key]."</span>";   
            $dataPoints[$counter]['type'] = $key;
            $counter++;
            break;
        }
    }
    if($instituteImportantData['autonomous']){
        $dataPoints[$counter]['html'] = "<span>".$instituteImportantData['autonomous']."</span>";   
        $dataPoints[$counter]['type'] = 'autonomous';
        $counter++;
    }
    if($instituteImportantData['nationalImportance']){
        $dataPoints[$counter]['html'] = "<span>".$instituteImportantData['nationalImportance']."</span>";
        $dataPoints[$counter]['type'] = 'nationalImportance';
        $counter++;
    }
    if($instituteImportantData['naac_accreditation']){
        $dataPoints[$counter]['html'] = "<span>".$instituteImportantData['naac_accreditation']."</span>";   
        $dataPoints[$counter]['type'] = 'naac_accreditation';
        $counter++;
    }
    if($instituteImportantData['aiu_member']){
        $dataPoints[$counter]['html'] = "<span>".$instituteImportantData['aiu_member']."</span>";   
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
    if(!empty($aggregateReviewsData)){
        $html = "<div class=new_rating>";
        $html .= $this->load->view("mobile_listing5/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $instituteObj->getAllContentPageUrl('reviews'), 'aggregateReviewsData' => $aggregateReviewsData, 'reviewsCount' => $reviewWidget['count'],'isPaid' => $instiuteIsPaid ), true);
        $html .= "</div>";
        $singleColumnData[$counter]['html'] = $html;
        $singleColumnData[$counter]['type'] = 'review';
        $counter++;
    }

    $dataPoints = array_chunk($dataPoints, 2);

    $locationName = $currentLocationObj->getCityName();
    if($currentLocationObj->getLocalityName())
        $locationName = $currentLocationObj->getLocalityName().", ".$locationName;
  ?>
<div class="new-container panel-pad">
    <div class="lcard clg-panel">
        <div class="clg-panel-head">
            <h1 class="head-L2"><span><?php echo htmlentities($topCardData['instituteName']);?></span>
            <p class="para-L3"><i class="clg-sprite crs-loc"></i><?php echo $locationName;?>
            </p>
            </h1>
            <?php  if($isMultilocation && $seeAllBranchesData['hasLocation'] && $seeAllBranchesData['locationCount'] > 1){ ?>
                <a href="javascript:void(0);" class="link-blue-small chng-arw" ga-attr="<?=$GA_TAP_ON_BRANCH;?>" onclick="showLocationLayer();">change branch<i class="arw-icn"></i></a>
            <?php } ?>
        </div>
        <?php
            if($dataPoints || $singleColumnData){
        ?>
        <div class="clg-detail">
            <ul>
            <?php
                foreach ($dataPoints as $dataPointsRows) {
                    echo "<li>";
                    foreach ($dataPointsRows as $key=>$dataPointSingleRow) {
            ?>
                        <div class="clg-col">
                        <?php 
                            echo $dataPointSingleRow['html'];
                            if(array_key_exists($dataPointSingleRow['type'], $toolTipData))
                                echo ' <a><i class="clg-sprite clg-inf" ga-attr="'.$GA_TAP_ON_HELP_TEXT.'" onclick=\'openHelpTextLayer("help_'.$dataPointSingleRow['type'].'");\'></i></a>';
                            if(in_array($dataPointSingleRow['type'], array('affiliation', 'rank')) ){
                            ?>
                                <?php echo $topCardData['detailedData'][$dataPointSingleRow['type']];?>
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
            <div class="clg-col single-col">
                        <?php 
                            echo $dataPointSingleRow['html'];
                            if(array_key_exists($dataPointSingleRow['type'], $toolTipData))
                                echo ' <i class="clg-sprite clg-inf" ga-attr="'.$GA_TAP_ON_HELP_TEXT.'" onclick=\'openHelpTextLayer("help_'.$dataPointSingleRow['type'].'");\'></i>';
                            if(in_array($dataPointSingleRow['type'], array('affiliation', 'rank')) ){
                            ?>
                                <?php echo $topCardData['detailedData'][$dataPointSingleRow['type']];?>
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
        <a class="btn-mob" ga-attr="<?=$GA_Tap_On_Shortlist;?>" onclick="listingShortlist('<?php echo $listing_id?>','<?php echo $shortlistTrackingKeyId;?>','<?php echo $listing_type;?>', {'pageType':'instituteDetailPage','listing_type':'<?php echo $listing_type;?>','callbackFunctionParams':{'pageType':'instituteDetailPage'}});">Shortlist</a>
    </div>
</div>
