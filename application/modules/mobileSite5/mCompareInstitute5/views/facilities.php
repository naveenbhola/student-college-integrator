<?php 
if(!empty($compareData['facilities']['collegeFacilitiesFinalList']) || !empty($compareData['facilities']['collegeExtraFacilitiesFinalList']))
{
  $clgFacilities = $compareData['facilities']['collegeFacilitiesFinalList'];
  $clgExtraFacilities = $compareData['facilities']['collegeExtraFacilitiesFinalList'];
?>
  <tr>
    <td colspan="2" class="compare-title">
      <h2>Infrastructure & Facilities</h2>
    </td>
  </tr>
  <tr class="facility-row ">
    <td class="border-right"></td>
  </tr>
<?php 
  $noValueCounter = array();
  foreach ($courseIdArr as $courseId) {
    $noValueCounter[$courseId] = 0;
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
  }?>
<tr class="facility-row rlt-dv" >
  <td class="border-right" style="padding:8px !important"></td>
  <td class="border-right" style="padding:8px !important"></td>
</tr>
  <?php
foreach ($clgFacilities as $fac_name => $value) {
?>
  <tr class="facility-row">
    <?php 
    for($i=0; $i<2; $i++){
      $courseId = $courseIdArr[$i];
      $tdClass = ($i<$compare_count_max) ? "border-right" : '';
      if(isset($value[$courseId]) && $value[$courseId] == '1'){
      ?>
        <td class="<?php echo $tdClass?>"><?php echo $fac_name.(($fac_name=='Hostel') ? ' '.$collegeHostelFacilities[$courseId] : '')?></td>
      <?php 
      }else if(isset($value[$courseId]) && $value[$courseId] == '0'){
      ?>
        <td class="<?php echo $tdClass?> crs-txt"><?php echo $fac_name?></td>
      <?php
      }else if(isset($value[$courseId])){
        echo '<td class="'.$tdClass.'">--</td>';
      }else{
        echo '<td class="'.$tdClass.'"></td>';
        $noValueCounter[$courseId]++;
      } 
    }
    ?>
  </tr>
<?php 
}

foreach ($clgExtraFacilities as $fac_name => $value) {
  echo '<tr class="facility-row">';
  for($i=0; $i<2; $i++){
    $courseId = $courseIdArr[$i];
    $tdClass = ($i<$compare_count_max) ? "border-right" : '';
    if(isset($value[$courseId]) && $value[$courseId] == '1'){
      ?>
      <td class="<?php echo $tdClass?>"><?php echo $fac_name?></td>
      <?php 
    }else if(isset($value[$courseId])){
      ?>
      <td class="<?php echo $tdClass?> crs-txt"><?php echo $fac_name?></td>
      <?php 
    }else{
      echo '<td class="'.$tdClass.'"></td>';
      $noValueCounter[$courseId]++;
    }
  }
  echo '</tr>';
}
$totalFacilitiesCount = count($clgExtraFacilities) + count($clgFacilities);
if(($totalFacilitiesCount == $noValueCounter[$courseIdArr[0]]) || ($totalFacilitiesCount == $noValueCounter[$courseIdArr[1]])){  if($totalFacilitiesCount == $noValueCounter[$courseIdArr[0]]){
      $block = 1;
    }else{
      $block = 2;
    }
  
  ?>
  <tr class="no-height">
    <td>
    <?php 
          if($block == 1){ 
    ?>
            <span class="abs-msg">No data available</span>
    <?php

          }
    ?>
      
    </td>
    <td>
     <?php 
          if($block == 2){ 
    ?>
            <span class="abs-msg">No data available</span>
    <?php

          }
    ?>
    </td>
  </tr>
  <?php //echo '<td class="'.$tdClass.'">No Information available</td>';
}
}
?>
<tr class="facility-row" >
  <td class="border-right" style="padding:8px !important"></td>
  <td class="border-right" style="padding:8px !important"></td>
</tr>
<tr class="facility-row">
  <td class="border-right"></td>
</tr>