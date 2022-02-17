<?php 
if(!empty($compareData['eligibility'])){
?>
  <!--Exams Required -->
  <tr id="compareRow5">
    <td>
      <div class="cmpre-head"><label>Exams Required & <br/>Cut-off<sup>*</sup></label></div>
    </td>
      <td colspan="<?php echo $currentCourseCount;?>" class="al-sal">
      <table class="al-sal-tbl" cellpadding="0" cellspacing="0" width="100%">
        <tr>
        <?php
        $j = 0;$z = 0;
        foreach($compareData['eligibility'] as $courseId => $eligibility){
          $z++;
          if(count($eligibility) > 0){
            $j++;
        ?>
          <td>
          <div class="cmpre-head">
            <?php 
            foreach ($eligibility as $elig) {
              echo '<p class="inst-fees"><span>'.$elig['examName'].'&nbsp;</span>'.$elig['examCutOff'].'&nbsp;<span>'.$elig['unit'].'</span></p>';
            }
            ?>
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
          <td colspan="4" class="al-sal-tr" align="right"><p class="source-link">*Cut off for general category</p></td>
        </tr>
      </table>
      </td>
  </tr>
<?php 
}
?>