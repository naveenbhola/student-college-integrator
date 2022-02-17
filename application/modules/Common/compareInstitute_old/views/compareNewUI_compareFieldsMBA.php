<!--Rank-->
<tr id="compareRow1">
  <td>
    <div class="cmpre-head"><label>Rank</label></div>
  </td>
  <?php 
  $hideRow = true;
  $j = 0; $k = 0;
  //_p($rankings);die;
  foreach($institutes as $institute){
    $k++;
    $course = $institute->getFlagshipCourse();
    if(NEW_RANKING_PAGE && !empty($rankings[$course->getId()])) {
      $hideRow = false;
      $j++;
      ?>
      <td>
        <div class="cmpre-items">
          <ul>
          <?php 
          $i = 0;
          foreach($rankings[$course->getId()] as $sourceRank){
            ?>
            <li>
              <?php if($sourceRank['rank'] != 'NA') { ?>
              <span>
                <a href="<?php echo $sourceRank['ranking_page_url']; ?>" class="busns-txt"><?php echo $sourceRank['source_name']; ?></a>
              </span>
              <a class="round-col" href="<?php echo $sourceRank['ranking_page_url']; ?>"><?php echo $sourceRank['rank']; ?></a>
              <?php }else{?>
              <span>
                <a href="javascript:;" class="dull-txt"><?php echo $sourceRank['source_name']; ?></a>
              </span>
              <a class="dull-round-col" href="javascript:;">- -</a>
              <?php } ?>
            </li>
            <?php 
          }
          ?>
          </ul>
        </div>
      </td>
      <?php 
    }else if(!NEW_RANKING_PAGE && isset($rankings[$course->getId()]['course_rank']) && $rankings[$course->getId()]['course_rank']>0){
          $hideRow = false;
          $j++;
    ?>
      <td>
        <div class="compare-items">
          <span class="rank-nmbr"><?=$rankings[$course->getId()]['course_rank']?> <i class="compare-sprite rank-icon"></i></span>
          <?php if($rankings[$course->getId()]['ranking_page_text']!=''){ echo "<div style='color: grey; font-size: 11px;margin-top:5px;'>(in ".$rankings[$course->getId()]['ranking_page_text'].")</div>";} ?>
        </div>
      </td>
    <?php 
    }else{
    ?>
      <td align="center" style="vertical-align:middle;">
        <a class="dull-round-col" href="javascript:;">- -</a>
      </td>
    <?php 
    }
  }
  ?>
</tr>
<?php 
if($hideRow)
{
  echo '<script>$("compareRow1").parentNode.removeChild($("compareRow1"));</script>';
}
$hideRow = true;
?>
<!-- Recognition -->
<tr id="compareRow2">
  <td><div class="cmpre-head"><label>Recognition</label></div></td>
  <?php
  $j = 0;
  foreach($institutes as $institute){
    $recogFlag = false;
    $j++;
    $k = 0;
    ?>
    <td id="compareRow2Col<?php echo $j?>">
    <?php
    $course = $institute->getFlagshipCourse();
    ?>
    <div class="cmpre-head" style="height:100%">
      <?php 
      if($course->isUGCRecognised()){
        $recogFlag = true;
        echo '<p class="affliatn-txt">UGC Recognised</p>';
        $hideRow = false;
      }
      if($course->isAICTEApproved()){
        $recogFlag = true;
        echo '<p class="affliatn-txt">AICTE Approved</p>';
        $hideRow = false;
      }
      if($course->isDECApproved()){
        $recogFlag = true;
        echo '<p class="affliatn-txt">DEC Approved</p>';
        $hideRow = false;
      }
      if(!$recogFlag)
      {
        $k = $j;
        echo '<div style="text-align:center;vertical-align:middle;">-</div>';
      }
      ?>
    </div>
    </td>
  <?php 
    if($k != 0)
    {
      echo '<script>$(compareRow2Col'.$k.').style.verticalAlign = "middle";</script>';
    }
  }
  ?>
</tr>
<?php 
if($hideRow)
{
  echo '<script>$("compareRow2").parentNode.removeChild($("compareRow2"));</script>';
}
$hideRow = true;
?>
<!--Affliations-->
<tr id="compareRow3">
  <td><div class="cmpre-head"><label>Course Status</label></div></td>
  <?php
      $j = 0;$k = 0;
      foreach($institutes as $institute){
        $k++;
        $course = $institute->getFlagshipCourse();
        $affiliations = $course->getAffiliations();
        foreach($affiliations as $affiliation) {
          //$Affiliations[] = langStr('affiliation_'.$affiliation[0],$affiliation[1]);
          $Affiliations[] = str_replace(array('(',')','Program'),array('','',''),langStr('affiliation_'.$affiliation[0],$affiliation[1]));
        }
        natcasesort($Affiliations);
        if($Affiliations[0]){
          $hideRow = false;
          $j++;
          ?>
          <td>
            <div class="cmpre-head">
            <?php 
            foreach ($Affiliations as $aff) {
            ?>
              <p class="affliatn-txt"><?php echo ($aff=='Affiliated to Deemed University')?'Deemed University':$aff?></p>
              <?php 
            }
              ?>
            </div>
          </td>
          <?php 
        }else{
          ?>
          <td align="center" style="vertical-align:middle;">-</td>
          <?php 
        }
        unset($Affiliations);
      }
      ?>
</tr>
<?php 
if($hideRow)
{
  echo '<script>$("compareRow3").parentNode.removeChild($("compareRow3"));</script>';
}
$hideRow = true;
?>
<!--Annual Alumni Salary-->
<?php 
foreach($institutes as $institute){
  $course = $institute->getFlagshipCourse();
  $naukriDataIns[$course->getId()] = array('institute'=>$course->getInstId(),'subCat'=>$subCategoryOfCourses[$course->getId()]['subCat']);
}
$salaryData = Modules::run('compareInstitute/compareInstitutes/createBarChart', $naukriDataIns, false);
$AvgCTCSalData = array();
foreach ($salaryData['data'] as $key=>$value) {
  $AvgCTCSalData[$key] = $value['AvgCTC'];
}
$maxAvgCTC = max($AvgCTCSalData);
?>
<tr id="compareRow4">
  <td>
    <div class="cmpre-head"><label>Annual Alumni Salary<sup>*</sup><br/>(INR)</label></div>
  
  </td>
  
    <td colspan="<?php echo count($institutes);?>" class="al-sal">
      <table class="al-sal-tbl" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <?php 
  foreach ($salaryData['data'] as $key=>$value) {
    if($AvgCTCSalData[$key] > 0 && $maxAvgCTC > 0){
      $hideRow = false;
      ?>
        <td>
          <div class="cmpre-head">
            <p class="inr-amnt"><?php echo number_format($AvgCTCSalData[$key],2)?><span> lacs</span></p>
            <div class="amnt-graph-bar">
              <div class="amnt-graph-percent" style="width:<?php echo ($AvgCTCSalData[$key]*100)/$maxAvgCTC?>%"></div>
            </div>
            <p class="year-exp">2-5 years work experience</p>
          </div>
        </td>
      <?php 
    }else{
      echo '<td align="center" style="vertical-align:middle;"><div class="cmpre-head">-</div></td>';
    }
  }
  ?>
        </tr>
        <tr ><td colspan="4" class="al-sal-tr" align="right"><p class="source-link">*Data Source : </p><i class="compare-sprite sml-naukri-logo"></i></td></tr>
      </table>
    </td>
  <?php 
    
  ?>
</tr>
<?php 
if($hideRow)
{
  echo '<script>$("compareRow4").parentNode.removeChild($("compareRow4"));</script>';
}
$hideRow = true;
?>
<!--Exams Required -->
<tr id="compareRow5">
  <td>
    <div class="cmpre-head"><label>Exams Required & <br/>Cut-off<sup>*</sup></label></div>
  </td>
    <td colspan="<?php echo count($institutes)?>" class="al-sal">
    <table class="al-sal-tbl" cellpadding="0" cellspacing="0" width="100%">
      <tr>
      <?php
      $j = 0;$z = 0;
      global $exam_weightage_array;
      foreach($institutes as $institute){
        $z++;
        $course = $institute->getFlagshipCourse();
        $eligibilities = $course->getEligibilityExams();
        $eligibilitiesArr = array();
        
        foreach($eligibilities as $k=>$v)
        {
            if($eligibilities[$k]->getMarksType()=='percentile' && !in_array($eligibilities[$k]->getAcronym(),$GLOBALS['MBA_EXAMS_REQUIRED_SCORES']))
            {
          array_push($eligibilitiesArr,array('Acronym'=>$eligibilities[$k]->getAcronym(), 'Percentile'=>$eligibilities[$k]->getMarks(), 'Weightage'=>$exam_weightage_array[$eligibilities[$k]->getAcronym()], 'MarksUnit'=>'%ile'));
            }
            else if($eligibilities[$k]->getMarksType()=='total_marks' && in_array($eligibilities[$k]->getAcronym(),$GLOBALS['MBA_EXAMS_REQUIRED_SCORES']))
            {
          array_push($eligibilitiesArr,array('Acronym'=>$eligibilities[$k]->getAcronym(), 'Percentile'=>$eligibilities[$k]->getMarks(), 'Weightage'=>$exam_weightage_array[$eligibilities[$k]->getAcronym()], 'MarksUnit'=>'Marks'));
            }
        }
        //sort by exam priority order(weightage)
        usort($eligibilitiesArr,function($a, $b)
        {
            return ($a['Weightage'] < $b['Weightage']);
        });
        $eligibilities = $eligibilitiesArr;
        if(count($eligibilities)>0)
        {
            $hideRow = false;
            $widgetData['eligibility'] = array('completeEligilibilityData'=>$eligibilities,
                       'examRequiredHTML'=>'');
            foreach($eligibilities as $k=>$eligibility)
            {
              $widgetData['eligibility']['examRequiredHTML'] .= "<p class='inst-fees'><span>".$eligibility['Acronym']." ".($eligibility['Percentile']>0?"</span>".$eligibility['Percentile']:"").($eligibility['Percentile']>0 && $eligibility['Percentile']!="No Exam Required" ?"<span>".$eligibility['MarksUnit']:"</span>")."</p>";
            }
            $numAvailableCourseWidgets++;
        }
        else
        {
            $widgetData['eligibility'] = 0;
        }
        if($widgetData['eligibility'] != 0){
          $j++;
      ?>
        <td>
        <div class="cmpre-head">
          <?php
            echo $widgetData['eligibility']['examRequiredHTML'];
          ?>
        </div>
        </td>
      <?php 
        }else{
          echo '<td align="center" style="vertical-align:middle;"><div class="cmpre-head">-</div></td>';
        }
      }
      ?>
      </tr>
      <tr>
        <td colspan="4" class="al-sal-tr" align="right"><p class="source-link">*Cut off for general category</p></td>
      </tr>
    </table>
    </td>
</tr>
<?php 
if($hideRow)
{
  echo '<script>$("compareRow5").parentNode.removeChild($("compareRow5"));</script>';
}
$hideRow = true;
?>
<!--fees-->
<tr id="compareRow6">
  <td><div class="cmpre-head"><label>Total Course Fees</label></div></td>
  <?php
  $j = 0;$k = 0;
  foreach($institutes as $institute){
    $k++;
    $course = $institute->getFlagshipCourse();
    if($course->getFees()->getValue()){
      $hideRow = false;
      $j++;
      if($course->getFees()->getValue() >= 100000)
      {
          $feevalue =round(($course->getFees()->getValue()/100000),1);
          $feeunit  = ($course->getFees()->getValue() == 100000)?"Lac":"Lacs";
      }
      else
      {
          $feevalue = moneyFormatIndia($course->getFees()->getValue());
          $feeunit  = "";
      }
  ?>
    <td>
      <div class="cmpre-head">
         <p class="inst-fees"><span><?=$course->getFees()->getCurrency()?>&nbsp;</span><?=$feevalue?><span><?=$feeunit?></span></p>
        <?php if($showFeeDisc[$course->getId()] ==1 && SHOW_FEE_DISC_CMPR){ ?><p class="year-class" style="margin-left:4px">Note - Fees mentioned is Full Course Fees. LPU offers various scholarships based on academic performance. Check LPU details page for more information.</p><?php } ?>
       </div>
    </td>
    <?php 
    }else{
    ?>
    <td align="center" style="vertical-align:middle;">-</td>
    <?php 
    }
  }
  ?>
</tr>
<?php 
if($hideRow)
{
  echo '<script>$("compareRow6").parentNode.removeChild($("compareRow6"));</script>';
}
?>