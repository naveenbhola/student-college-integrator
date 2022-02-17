<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?=$courseName?> Brochure</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="https://<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('courseBrochure'); ?>" />
    <link rel="icon" href="https://<?=IMGURL;?>/public/images/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="https://<?=IMGURL;?>/public/images/favicon.ico" type="image/x-icon" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
</head>

<body>
    <div class="padf-wrap">
        <div class="pdf-container">
          <!--main contnet-->
          <div class="cmn-card mb2">
                <h1 class="start-titl"><?=htmlentities($topCardData['instituteName']);?></h1>
                <?php if(!empty($headerImage)) { ?>
                          <div class="bc-img-col" style="background-image:url(<?=$headerImage;?>)"></div>
                <?php } ?>
                <?php
                    $locationName = $currentLocationObj->getCityName();
                    if($currentLocationObj->getLocalityName()) {
                      $locationName = $currentLocationObj->getLocalityName().", ".$locationName;
                    }
                ?>
                <?php 
                $inlineInstituteText = '';
                    if(!empty($topCardData['inlineData'])) {
                      $inlineInstituteText = "<span> | <span>" . implode('<span> | </span>', $topCardData['inlineData']);
                    }
                ?>
                <p class="tags-txt"><?=$locationName?> <?=($inlineInstituteText != '') ? $inlineInstituteText: ''?></p>
                <?php 
                    $instituteImportantText = '';
                    if(!empty($topCardData['instituteImportantData'])) {
                      $instituteImportantText = implode('<span> | </span>', $topCardData['instituteImportantData']);
                    }

                    if($instituteImportantText != '') { ?>
                        <p class="tags-txt"><?=$instituteImportantText?></p>
                <?php } ?>

          </div>
           <!--partner colleges-->
             <div class="cmn-card">
                <?php $extraInfo = getCourseTupleExtraData($courseObj,'courseDetail',false);?>
                 <h1 class="pdf-titl clor3 f-semi"><?=$courseName;?></h1>
                 <p class="f16 clor9"><?=$extraInfo?></p>
                <?php 
                $mediumOfInstruction = $courseObj->getMediumOfInstruction();
                $difficultyLevel = $courseObj->getDifficultyLevel()->getName(); 
                if(!empty($recognitionData['approvals']) || !empty($recognitionData['institute']) || !empty($courseRankData) || !empty($mediumOfInstruction) || !empty($difficultyLevel)) { ?>
                 <div class="clg-section">
                   <ul class="max-ul">
                    <?php if(!empty($recognitionData['approvals'])) { ?>
                           <li>
                             <div class="clg-max">
                             <?php foreach ($recognitionData['approvals'] as $recognition) { ?>
                                    <p class="f12 clor9"><strong class="f16 clor6"><?=$recognition['name']?></strong> Approved</p>
                              <?php }
                             ?>
                            </div>
                         </li>
                    <?php } ?>
                    
                    <?php if(!empty($recognitionData['institute'])) { ?>
                          <li>
                             <div class="clg-max">
                             <?php foreach ($recognitionData['institute'] as $recognition) { ?>
                                    <p class="f12 clor9"><strong class="f16 clor6"><?=$recognition['name']?></strong> Accredited</p>
                              <?php }
                             ?>
                            </div>
                         </li>
                    <?php } ?>
                    <?php if(!empty($courseRankData)) { ?>
                           <li>
                             <div class="clg-max">
                             <?php foreach($courseRankData as $rankData) { ?>
                                    <p class="f12 clor9"><strong class="f16 clor6">Rank #<?=$rankData['rank']?></strong>  by <?=$rankData['source_name']?></p>
                              <?php } ?>
                             </div>
                           </li>
                    <?php } ?>
                    <?php if(!empty($mediumOfInstruction)) { ?>
                           <li>
                             <div class="clg-max">
                               <p class="f12 clor9"><strong class="f16 clor6">Medium</strong></p>
                               <?php foreach ($mediumOfInstruction as $instructionData) { ?>
                                        <span class="f12 clor9"><?=$instructionData->getName();?></span>
                               <?php } ?>
                             </div>
                           </li>
                    <?php } ?>
                    <?php if(!empty($difficultyLevel)) { ?> 
                            <li>
                             <div class="clg-max">
                               <p class="f12 clor9"><strong class="f16 clor6"><?=$difficultyLevel?></strong></p>
                               <p class="f12 clor9">Difficulty Level</p>
                             </div>
                            </li>
                    <?php } ?>

                 </ul>
                <?php if(!empty($affiliatedUniversityName)) { ?>
                        <p class="f16 clor9 "><strong class="f16 clor9 f-weight1 mb2">Affiliated to </strong> <?=$affiliatedUniversityName?></p>
                <?php }
                 ?>

                 </div>
                 <?php } ?>
             </div>

             <?php foreach ($navigationSection as $key => $section) {
                      switch ($section) {
                          case 'Eligibility':
                              if(!empty($eligibility) && $eligibility['showEligibilityWidget']) {
                                  $this->load->view('courseBrochureWidgets/autogeneratedEligibilitySection');
                              }
                          break;
                          case 'Highlights':
                              if(!empty($highlights))
                                  $this->load->view('courseBrochureWidgets/autogeneratedHighlightSection');
                          break;
                          case 'Fees':
                              if(!empty($fees['feesData']))
                                  $this->load->view('courseBrochureWidgets/autogeneratedFeesSection');
                          break;
                          case 'Structure':
                              if(!empty($courseStructure))
                               $this->load->view('courseBrochureWidgets/autogeneratedStructureSection');
                          break;
                          case 'Admissions':
                              if(!empty($admissions))
                               $this->load->view('courseBrochureWidgets/autogeneratedAdmissionSection');
                          break;
                          case 'Placements':
                              if(!empty($placements) || !empty($placementsCompanies)){
                                  $this->load->view('courseBrochureWidgets/autogeneratedPlacementSection');
                              }
                              if(!empty($facilities['facilities']) && count($facilities['facilities']) > 0) {
                                  $this->load->view('courseBrochureWidgets/autogeneratedFacilitySection');
                              }
                          break;
                          case 'Seats':
                              $this->load->view('courseBrochureWidgets/autogeneratedScholarshipSection');
                              if(!empty($seats))
                                  $this->load->view('courseBrochureWidgets/autogeneratedSeatsSection'); 
                          break;
                          case 'Contact':
                              if(!empty($contactDetails['address']) || !empty($contactDetails['admission_contact_number']) || !empty($contactDetails['admission_email']) || !empty($contactDetails['website_url']) || !empty($contactDetails['generic_contact_number']) || !empty($contactDetails['generic_email'])) {
                                  $this->load->view('courseBrochureWidgets/autogeneratedContactSection');
                              }
                          break;
                          case 'Partner':
                              if(!empty($partners))
                                  $this->load->view('courseBrochureWidgets/autogeneratedPartnerSection');
                          break;
                          // default:
                          // break;                        
                      }
                  }
              ?>

    </div>
  </div>

</body>

</html>