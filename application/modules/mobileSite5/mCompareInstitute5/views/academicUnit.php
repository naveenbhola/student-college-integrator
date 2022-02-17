<!--Academic Unit-->
<tr id="row8_H">
  <td colspan="2" class="compare-title"><h2>College / Dept.</h2></td>
</tr>
<tr id="row8_C" align="center">
<?php
foreach ($compareData['academicUnit'] as $courseId => $academicUnitName) {
?>
  <td class="<?php echo ($k<$compare_count_max)?'border-right ':''; if(empty($academicUnitName)){ echo "verticalalign";}?>">
    <div class="college-rank">
      <p class="recognition"><?php echo $academicUnitName?></p>
    </div>
  </td>
<?php 
}
?>
</tr>