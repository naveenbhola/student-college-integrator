<tr id="compareRow7">
<?php 
if(!empty($compareData['facilities']['collegeFacilitiesFinalList']) || !empty($compareData['facilities']['collegeExtraFacilitiesFinalList']))
{
  ?>
    <td><div class="cmpre-head"><label>Infrastructure & Facilities</label></div></td>
    <?php 
    $clgFacilities = $compareData['facilities']['collegeFacilitiesFinalList'];
    $clgExtraFacilities = $compareData['facilities']['collegeExtraFacilitiesFinalList'];
    foreach ($courseIdArr as $courseId) {
      $noValueCounter = 0;
      foreach ($collegeHostelFacilities[$courseId] as &$hostelType) {
        $hostelType = trim(trim($hostelType, 'Hostel'));
      }
      $temp = array();
      foreach ($collegeHostelFacilities[$courseId] as $key => $value) {
        if($value != 'Mandatory'){
          $temp[$courseId][] = $value;
        }
      }
      $collegeHostelFacilities[$courseId] = $temp[$courseId];
      if(isset($collegeHostelFacilities[$courseId]) && count($collegeHostelFacilities[$courseId]) > 0){
        $collegeHostelFacilities[$courseId] = '('.implode(', ', $collegeHostelFacilities[$courseId]).')';
      }
      ?>
      <td>
      <div class="cmpre-head">
        <ul class="facilities-list">
          <?php 
          foreach ($clgFacilities as $key => $value) {
            if(isset($value[$courseId]) && $value[$courseId] == '1'){
              ?>
              <li><a href="javascript:;"><?php echo $key.(($key=='Hostel') ? ' '.$collegeHostelFacilities[$courseId] : '')?></a></li>
              <?php 
            }else if(isset($value[$courseId]) && $value[$courseId] == '0'){
              ?>
              <li><a href="javascript:;" class="stroke-txt"><?php echo $key?></a></li>
              <?php 
            }else if(isset($value[$courseId])){
              ?>
              <li><a href="javascript:;">--</a></li>
              <?php 
            }else{
              $noValueCounter++;
            }
          }
          foreach ($clgExtraFacilities as $key => $value) {
            if(isset($value[$courseId]) && $value[$courseId] == '1'){
              ?>
              <li><a href="javascript:;"><?php echo $key?></a></li>
              <?php 
            }else if(isset($value[$courseId])){
              ?>
              <li><a href="javascript:;" class="stroke-txt"><?php echo $key?></a></li>
              <?php 
            }else{
              $noValueCounter++;
            }
          }
          if(count($clgExtraFacilities) + count($clgFacilities) == $noValueCounter){
            echo '<li><a href="javascript:;">No data available</a></li>';
          }
          ?>
        </ul>
      </div>
      </td>
      <?php 
    } 
}
?>
</tr>