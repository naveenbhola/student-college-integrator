<!--Annual Alumni Salary-->
<?php 
if(isset($compareData['alumniSalary'])){

  $showNaukriData = false;
  foreach ($compareData['alumniSalary'] as $courseId => $naukriData) {
    if($naukriData['AvgCTC'] >0){
      $showNaukriData = true;
    }
  }
  if($showNaukriData == true){
  $AvgCTCSalData = array();
  foreach ($compareData['alumniSalary'] as $key=>$value) {
    $AvgCTCSalData[$key] = $value['AvgCTC'];
  }
  $maxAvgCTC = max($AvgCTCSalData);
  ?>
  <tr id="compareRow4">
    <td>
      <div class="cmpre-head"><label>Annual Alumni Salary<sup>*</sup><br/>(INR)</label></div>
    </td>
    <td colspan="<?php echo $currentCourseCount;?>" class="al-sal">
      <table class="al-sal-tbl" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <?php 
            foreach ($courseIdArr as $key) {
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
            echo '<td align="center" style="vertical-align:middle;"><div class="cmpre-head">--</div></td>';
          }
        }?>
        </tr>
        <tr ><td colspan="4" class="al-sal-tr" align="right"><p class="source-link">*Data Source : </p><i class="compare-sprite sml-naukri-logo"></i></td></tr>
      </table>
    </td>
  </tr>
<?php }} ?>