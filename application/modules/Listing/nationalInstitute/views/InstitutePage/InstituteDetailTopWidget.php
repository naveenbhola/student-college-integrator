<?php
  $GA_Tap_On_Change_Branch = 'CHANGE_BRANCH';
  $GA_Tap_On_Shortlist = 'SHORTLIST';
  $GA_TAP_ON_HELP_TEXT_TEXT = 'HELP_TEXT';
  $GA_Tap_On_Header_Image = 'HEADER_IMAGE';
  $Answered_Test = ' Answered Questions';

  if($anaWidget['count'] == 1){
    $Answered_Test = ' Answered Question'; 
  }
  
  if($listing_type == 'university')
  {
    $shortlistTrackingKeyId = 971;
  }
  elseif ($listing_type == 'institute') {
    $shortlistTrackingKeyId = 931;
  }
  $inlineText = $topCardData['inlineData'];
  $inlineText = implode(" | ", $inlineText);

  $importantDataRows = count($topCardData['instituteImportantData']);
  $instituteImageTitle = $instituteObj->getAbbreviation() ? $instituteObj->getAbbreviation() : $instituteObj->getName();

  $headerImage = $topCardData['headerImage']['url'];
  $headerImageType = $topCardData['headerImage']['type'];
  $headerImageTitle = $topCardData['headerImage']['title'];
  $template = 1;
  $imageSectionClass = "";
  $rightColClass = 'col-md-6';
  if($importantDataRows <=2){
    $template = 1;
    $headerImage = getImageVariant($headerImage, 3);
    $imageClass = 'img-s';
    $imageSectionClass = 'case135';
    $rightColClass = 'col-md-4';
  }
  else if($importantDataRows <=4){
    $template = 2;
    $headerImage = getImageVariant($headerImage, 6);
    $imageClass = 'img-m';
    $imageSectionClass = 'case210';
  }
  else {
    $template = 3;
    $headerImage = getImageVariant($headerImage, 7);
    $imageClass = 'img-l';
  }

  $clickEventOfHeaderImage = "";
  if($headerImageType == 'header'){
    $headerImageTagName = $topCardData['headerImage']['tags'][0]['type'] == 'event' ? 'Event' : $topCardData['headerImage']['tags'][0]['name'];
    $headerImageTagName = $headerImageTagName ? $headerImageTagName : 'Others';
    $clickEventOfHeaderImage = "openGallery('".$listing_id."','".$listing_type."','".$headerImageTagName."','".$topCardData['headerImage']['id']."',".$currentCityId.",".$currentLocalityId.")";
    $headerImageClass        = "cursor-point";
  }

  $leftSectionRows  = array();
  $rightSectionRows = array();

  $count = 0;
  foreach ($topCardData['instituteImportantData'] as $key => $value) {
    if($count%2 == 1)
      $rightSectionRows[$key] = $value;
    else
      $leftSectionRows[$key] = $value;
    $count++;
  }

  if($headerImageTitle){
      $instituteImageTitle .= " - ".$headerImageTitle;
  }

$rowSectionData   = array();
$rowSectionData['left'] = $leftSectionRows;
$rowSectionData['right'] = $rightSectionRows;
?>
<div class="new-row">
<div class="group-card gap">
<div class="new-row">
  <div class="col-md-3 <?php echo $imageSectionClass;?>">
     <a class="logo-img">
    <img class="<?php echo $imageClass." ".$headerImageClass;?>" src=<?php echo $headerImage;?> alt="<?php echo htmlentities($instituteImageTitle);?>" title="<?php echo htmlentities($instituteImageTitle);?>" ga-attr="<?=$GA_Tap_On_Header_Image?>" onclick="<?php echo $clickEventOfHeaderImage;?>"/>
    </a>
  </div>
  <div class="col-md-9">
  <?php 
 //_P($anaWidget);die;
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
    <h1 class="head-3 right-head"><?php echo htmlentities($topCardData['instituteName']);?><span class="location-of-clg"><?php echo ($locationName ? ", ".$locationName : $locationName);?></span></h1>
    <a class="shrt-list shortlist-site-tour" ga-attr="<?=$GA_Tap_On_Shortlist?>" cta-type="shortlist" onclick="ajaxDownloadEBrochure(this,<?php echo $listing_id;?>,'<?php echo $listing_type;?>','<?php echo addslashes(htmlentities($instituteName));?>','ND_InstituteDetailPage','<?php echo $shortlistTrackingKeyId;?>','','','','')" customCallBack="listingShortlistCallback" customActionType="ND_InstituteDetailPage">Shortlist
    <span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Shortlist']; ?></p></span>
    </a>
  <div class="location-of-clg">
  <?php  if($isMultilocation && $seeAllBranchesData['hasLocation'] && $seeAllBranchesData['locationCount'] > 1){ ?>
  <a class="link multi-loc-link" ga-attr="<?=$GA_Tap_On_Change_Branch?>" onclick="showMultilocationLayer();">change branch</a>
  <?php 
  if($inlineText)
    echo "| ";
  } 
  echo $inlineText;

  if(!empty($aggregateReviewsData) && $aggregateReviewsData['aggregateRating']['averageRating'] > 0  ) {
    echo "| ";  
    $this->load->view("listingCommon/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $instituteObj->getAllContentPageUrl('reviews'), 'aggregateReviewsData' => $aggregateReviewsData, 'reviewsCount' => $reviewWidget['count'] , 'isPaid' => $instiuteIsPaid,'forPredictor'=>1));     
  }
  if($anaWidget['count'] > 0){ 
  echo "| ";
  ?>
      <a class="view_rvws qstn" ga-attr="Header_AnsweredQuestions" href="<?php echo $anaWidget['allQuestionURL']?>" target="_blank">
        <i class="qstn_ico"></i><?php echo formatNumber($anaWidget['count'])?><?php echo $Answered_Test?> 
      </a>
  <?php
  }

  ?>

   </div>
  <div class="new-row top-inst-widget"> 
  <?php 
  foreach ($rowSectionData as $sectionName=>$sectionRows) {
  ?>
  <div class="right-data-col <?php echo $rightColClass;?>">
    <?php
      foreach ($sectionRows as $key=>$value) {
    ?>
        <div class="para-1">
        <?php echo $value;?>
    <?php
        if(in_array($key, array('affiliation', 'rank')) ){
    ?>
              <?php echo $topCardData['detailedData'][$key];?>
    <?php
        }
        if($instituteToolTipData[$key]){
    ?>
      <div class="tp-block">
      <i class="info-icn"></i>
      <div class="tooltip top">
          <div class="tooltip-arrow"></div>
          <div class="tooltip-inner">
              <?php echo $instituteToolTipData[$key]['helptext'];?>
          </div>
      </div>
      </div>
    <?php
      } // tooltip if end
    ?>
      </div>
    <?php
      }
    ?>
    <?php 
    if(in_array($template, array(2,3)) && $sectionName=='right' )
      $this->load->view("nationalInstitute/InstitutePage/topCTA"); 
    ?>

  </div>
  <?php 
  }
    if(in_array($template, array(1))){
  ?>
      <div class="right-data-col col-md-4">
        <?php
          $this->load->view("nationalInstitute/InstitutePage/topCTA"); 
        ?>
      </div>
  <?php
    }
  ?>
 </div> 
     </div>     
</div>
</div>
</div>
