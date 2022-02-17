<?php 
if(is_array($compareData['courseFee'])){
?>
  <!--Exams Required -->
  <tr id="compareRow5">
    <td>
      <div class="cmpre-head"><label>Total Course Fee<sup>*</sup></label></div>
    </td>
      <td colspan="<?php echo $currentCourseCount;?>" class="al-sal">
      <table class="al-sal-tbl" cellpadding="0" cellspacing="0" width="100%">
        <tr>
        <?php
        $j = 0;$z = 0;
        foreach($compareData['courseFee'] as $courseId => $courseFee){
          $z++;
          if(is_object($courseFee)){
            $j++;
            if($courseFee->getFeesValue() >= 100000)
            {
                $feevalue =round(($courseFee->getFeesValue()/100000),1);
                $feeunit  = ($courseFee->getFeesValue() == 100000)?"Lac":"Lacs";
            }
            else
            {
                $feevalue = moneyFormatIndia($courseFee->getFeesValue());
                $feeunit  = "";
            }
            /*$feeComponents = '';
            $otherFeeComponent = '';
            foreach ($courseFee->getTotalFeesIncludes() as $key => $value) {
              if($key != 'Others'){
                $feeComponents .= ($feeComponents=='') ? $value : ', '.$value;
              }else{
                $otherFeeComponent = ' and Others';
              }
            }
            if($feeComponents != ''){
              $feeComponents = '(Includes '.$feeComponents.$otherFeeComponent.')';
            }*/
        ?>
          <td>
          <div class="cmpre-head">
            <p class="inst-fees"><span><?php echo $courseFee->getFeesUnitName()?>&nbsp;</span><?php echo $feevalue?><span>&nbsp;<?php echo $feeunit;?></span></p>
            <?php 
            if($courseFee->getFeeDisclaimer())
            {
            ?>
              <p class="inst-fees"><span><?php echo FEES_DISCLAIMER_TEXT?></span></p>
            <?php 
            }
            ?>
            <?php if($showFeeDisc[$courseId] == 1 && SHOW_FEE_DISC_CMPR){ ?><p class="year-class" style="margin-left:4px">Note - Fees mentioned is Full Course Fees. LPU offers various scholarships based on academic performance. Check LPU details page for more information.</p><?php } ?>
          </div>
          </td>
        <?php 
          }else{
            echo '<td align="center" style="vertical-align:middle;"><div class="cmpre-head">--</div></td>';
          }
        }
        ?>
        </tr>
        <tr>
          <td colspan="4" class="al-sal-tr" align="right"><p class="source-link">*Fees for general category</p></td>
        </tr>
      </table>
      </td>
  </tr>
<?php 
}
?>