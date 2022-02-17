<?php 
$hideRow = true;
if(empty($instituteFacilities[0]) && empty($instituteFacilities[1]) && empty($instituteFacilities[2]) && empty($instituteFacilities[3])){ 
  foreach($institutes as $key => $institute){
    $instituteFacilities[$key] = $institute->getInstituteFacilities();
  }
}
?>
<!--facilities-->
<tr id="compareRow7">
<?php 
if(!(empty($instituteFacilities[0]) && empty($instituteFacilities[1]) && empty($instituteFacilities[2]) && empty($instituteFacilities[3])))
{
  foreach ($institutes as $key => $institute) {
    foreach ($instituteFacilities[$key] as $key1 => $value) {
      $facilities[$key][$key1] = $value->getFacilityName();
      if($facilities[$key][$key1] != 'Library'){
        $hasFacilities[$key][3] = 1; 
      }
      if($facilities[$key][$key1] == 'Hostels'){
       $hasFacilities[$key][2] = 1;  
      }
      if($facilities[$key][$key1] == 'Wifi Campus'){
        $hasFacilities[$key][7] = 1; 
      }
      if($facilities[$key][$key1] == 'Cafeteria/Mess'){
        $hasFacilities[$key][0] = 1; 
      }
      if($facilities[$key][$key1] == 'Transport Facilities'){
        $hasFacilities[$key][6] = 1; 
      }
      if($facilities[$key][$key1] == 'Hospital/Medical Facilities'){
        $hasFacilities[$key][4] = 1; 
      }
      if($facilities[$key][$key1] == 'Sports Complex'){
        $hasFacilities[$key][5] = 1; 
      }
      if($facilities[$key][$key1] == 'Gym'){
        $hasFacilities[$key][1] = 1; 
      }
    }
  }
  ?>
    <td><div class="cmpre-head"><label>Infrastructure & Facilities</label></div></td>
    <?php 
    $i =1;
    foreach ($institutes as $key => $institute){
    echo '<td>';
      ?>
      <?php 
      if(empty($hasFacilities[$key])) {
        echo '<div class="cmpre-head"><ul class="facilities-list"><li><a href="javascript:;">No Information available</a></li></ul></div>';
      }else{
        $hideRow = false;
        ?>
        <div class="cmpre-head">
          <ul class="facilities-list">
            <li><a href="javascript:;" <?php if($hasFacilities[$key][0] != 1){?> class = "stroke-txt"<?php } ?>>Cafeteria/Mess</a></li>
            <li><a href="javascript:;" <?php if($hasFacilities[$key][1] != 1){?> class = "stroke-txt"<?php } ?>>Gym</a></li>
            <li><a href="javascript:;" <?php if($hasFacilities[$key][2] != 1){?> class = "stroke-txt"<?php } ?>>Hostel</a></li>
            <li><a href="javascript:;" <?php if($hasFacilities[$key][3] != 1){?> class = "stroke-txt"<?php } ?>>Library</a></li>
            <li><a href="javascript:;" <?php if($hasFacilities[$key][4] != 1){?> class = "stroke-txt"<?php } ?>>Medical Facilities</a></li>
            <li><a href="javascript:;" <?php if($hasFacilities[$key][5] != 1){?> class = "stroke-txt"<?php } ?>>Sports Complex</a></li>
            <li><a href="javascript:;" <?php if($hasFacilities[$key][6] != 1){?> class = "stroke-txt"<?php } ?>>Transport Facility</a></li>
            <li><a href="javascript:;" <?php if($hasFacilities[$key][7] != 1){?> class = "stroke-txt"<?php } ?>>WifiCampus</a></li>
          </ul>
        </div>
        <?php 
      }
      $i++;
      ?>
      <?php 
    echo '</td>';
    } 
}
?>
</tr>
<?php 
if($hideRow)
{
  echo '<script>$("compareRow7").parentNode.removeChild($("compareRow7"));</script>';
}
?>