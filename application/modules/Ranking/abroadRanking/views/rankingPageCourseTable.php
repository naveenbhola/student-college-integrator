<?php
$this->abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
$rankingPageDataArr = $rankingPageObject->getRankingPageData();
?>
<div class="univ-tab-details clearwidth">
   <?php if($noOfCourses > 0){?>
    <table class="ranking-table" cellpadding="0" cellspacing="0">
        <tr class="header-border">
                <th width="5%">Rank</th>
            <th width="35%">College name</th>
            <th width="20%">1st year total course fees</th>
            <th width="20%">Exams accepted</th>
            <!--<th width="15%">Avg salary /package</th>-->
            <th width="20%"></th>
        </tr>
        <?php
            $rankCounter = 0;
            foreach($rankingPageDataArr as $key=>$ranking){

            $courseObj = $ranking['course'];
            $universityObj = $ranking['university'];
            if(!($courseObj instanceof AbroadCourse && $universityObj instanceof University)){
                if(!(($courseObj->getId() && $universityObj->getId()))){
                    continue;
                }
            }
        ?>
        <tr class="seperator"></tr>
        <tr>
            <td class="number-bg"><?php echo $key+1?></td>
            <td>
               <a href="<?php echo $universityObj->getURL(); ?>" style="color:#111 !important" target="_blank"><?php echo htmlentities($universityObj->getName())?></a>
               <p style="font-size:16px;">
                  <a href="<?php echo $courseObj->getURL()?>" target="_blank"><strong><?php echo htmlentities($courseObj->getName())?></strong></a>
               </p>
               <span class="cntry-title"><?php echo $universityObj->getLocation()->getCity()->getName().", ",$universityObj->getLocation()->getCountry()->getName()?></span>
            </td>
            <td>
                <?php
                    $fees = $courseObj->getTotalFees()->getValue();
                    if($fees){
                        $feesCurrency = $courseObj->getTotalFees()->getCurrency();
                        $courseFees = $this->abroadListingCommonLib->convertCurrency($feesCurrency, 1, $fees);
                        if($courseFees>0){
                            $courseFees = $this->abroadListingCommonLib->getIndianDisplableAmount($courseFees, 1);
                        }else{
                            $courseFees = "---";
                        }
                    }else{
                        $courseFees = "---";
                    }
                    $courseFees = str_replace("Lac","Lakh",$courseFees);
                    ?>
                <p><?php echo $courseFees?></p>
            </td>
            <td>
                <?php
                     $examCount = 0;
                     foreach($courseObj->getEligibilityExams() as $examObj){
                     if($examObj->getId() == -1){continue;}
                     if(++$examCount >= 3){continue;}
                     if($examObj->getCutoff() == "N/A"){
                        $printSpan = true;
                        $cutOffText = "Accepted";
                     }
                     else{
                        $printSpan = false;
                        $cutOffText = $examObj->getCutoff();
                     }
                ?>
                     <p <?php echo ($printSpan)?" onmouseover='showAcceptedMessage(this)' onmouseout='hideAcceptedMessage(this)'":""?> style="position: relative;width:118px !important;">
                        <?php
                           if($printSpan){
                              $this->load->view("listing/abroad/widget/examAcceptedTooltip",array('examName'=>$examObj->getName()));
                           }
                        ?>
                        <?php echo htmlentities($examObj->getName())?>: <?php echo $cutOffText?>

                     </p>
                <?php
                    }
                    if($examCount>=3){?>
                        <a class="extra-exam-anchor" href="javascript:void(0)" onclick="showExamDiv(this)"><?php echo "+".($examCount-2)." more";?></a>
                        <div class="extra-exam-div" style="display: none;width:105px !important;">
                        <?php   $examCount = 0;
                                foreach($courseObj->getEligibilityExams() as $examObj){
                                if($examObj->getId() == -1){continue;}
                                 if(++$examCount <= 2){continue;}
                                 if($examObj->getCutoff() == "N/A"){
                                    $printSpan = true;
                                    $cutOffText = "Accepted";
                                 }
                                 else{
                                    $printSpan = false;
                                    $cutOffText = $examObj->getCutoff();
                                 }
                        ?>
                        <p <?php echo ($printSpan)?" onmouseover='showAcceptedMessage(this)' onmouseout='hideAcceptedMessage(this)'":""?> style="position: relative;width:118px !important;">
                        <?php
                           if($printSpan){
                              $this->load->view("listing/abroad/widget/examAcceptedTooltip",array('examName'=>$examObj->getName()));
                           }
                        ?>
                        <?php echo htmlentities($examObj->getName())?>: <?php echo $cutOffText?>

                     </p>
                        <?php   }?>
                        </div>
                <?php }?>
            </td>
            <!--<td>
                <?php
                    /*$jobProfile = $courseObj->getJobProfile();
                    $averageSalaryVal   = $jobProfile->getAverageSalary();
                    if($averageSalaryVal != ''){
                       $averageSalaryCurr  = $jobProfile->getAverageSalaryCurrencyId();
                       $averageSalaryFinal = $this->abroadListingCommonLib->convertCurrency($averageSalaryCurr, 1, $averageSalaryVal);
                       if($averageSalaryFinal>0){
                        $averageSalaryFinal = $this->abroadListingCommonLib->getIndianDisplableAmount($averageSalaryFinal, 1);
                       }else{
                        $averageSalaryFinal = "---";
                       }
                    }else{
                        $averageSalaryFinal = "---";
                    }*/
                ?>
                <p><?//=$averageSalaryFinal?></p>
            </td>-->
            <td class="last">
               <?php
                  $courseData = array($courseObj->getId()   => array(
                                                                     'desiredCourse'   => $courseObj->getDesiredCourseId()?$courseObj->getDesiredCourseId():$courseObj->getLDBCourseId(),
                                                                     'paid'            => $courseObj->isPaid(),
                                                                     'name'            => $courseObj->getName,
                                                                     'subcategory'     => $courseObj->getCourseSubCategoryObj()->getId()
                                                                     )
                                      );
                  $dataObj = array(
                                 'sourcePage'               => 'course_rankingpage_abroad',
                                 'courseId'                 => $courseObj->getId(),
                                 'courseName'               => $courseObj->getName(),
                                 'universityId'             => $universityObj->getId(),
                                 'universityName'           => $universityObj->getName(),
                                 'widget'                   => 'courseRankingPage',
                                 'destinationCountryId'     => $universityObj->getLocation()->getCountry()->getId(),
                                 'destinationCountryName'   => $universityObj->getLocation()->getCountry()->getName(),
                                 'courseData'               => base64_encode(json_encode($courseData)),
                                 'trackingPageKeyId'     => 25,
                                 'consultantTrackingPageKeyId' => 383
                                   );
               ?>
            <div style="position:relative">
                <a href="javascript:void(0)" class="button-style" style="margin:30px 0 0 10px;" onclick = "loadBrochureDownloadForm('<?php echo base64_encode(json_encode($dataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer','downloadBrochure');"><i class="common-sprite dwnld-icon"></i><strong>Download Brochure</strong></a>
            </div>
            </td>
        </tr>
        <?php
            }
        ?>
    </table>
    <?php }?>
</div>
<?php
if($noOfCourses == 0)
{
            $resetFilterHtml = "<a href='javascript:void(0);' onclick='resetAllFilters();' >reset filters</a> ";
        ?>
            <div class="zero-result clearwidth" >
                <p>Your search refinement resulted in zero results. </p>
                <p>Please <?php echo $resetFilterHtml?> and try again.</p>
            </div>
<?php } ?>
<script>
    function showExamDiv(obj){
        $j(obj).closest('td').find('.extra-exam-div').slideDown();
        $j(obj).hide();
    }
</script>
