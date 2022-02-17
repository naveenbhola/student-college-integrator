<?php 
$course = $courseObjs[$courseId];
if($showAcademicUnitSection){
  $instituteId = $academicUnitRawData[$courseId]['userSelectedInstitute'];
  $coursesForDropDown = $instituteWithCoursesData[$courseId][$instIdArr[$courseId]]->getCourse();
}else{
  $instituteId = $instIdArr[$courseId];
  $coursesForDropDown = $instituteWithCoursesData[$courseId][$instituteId]->getCourse();
}
if(count($coursesForDropDown) > 0){
?>
<select class="custom-select" style="display:none;" id="courseSelect<?=$j?>">
<?php 
$selectedCourse = 'Select Course';
foreach ($coursesForDropDown as $courseD){
  $selected = "";
  if(empty($courseD))
  {
    continue;
  }
  if($courseD->getId() == $courseId){
    $selected = "selected='selected'";
    $selectedCourse = $courseD->getName();
  }
  $tmpArr[$courseId][] =  $courseD->getId();
  ?>
  <option value='<?php echo $courseD->getId()?>' <?=$selected?> ><?=$courseD->getName()?></option>
<?php 
}
$defaultId = '';
// if url has course with different level
if(!in_array($courseId, $tmpArr)){
  $defaultName = $courseObjs[$courseId]->getName();
  $defaultId   = $courseObjs[$courseId]->getId();
  $selectedCourse = $defaultName;
}
if($defaultId){?>
  <option value='<?php echo $defaultId;?>' selected='selected' ><?=$defaultName?></option>
<?php }
?>
</select>
<?php 
  foreach ($coursesForDropDown as $courseD){
    ?>
    <li courseId="<?php echo $courseD->getId()?>" courseTupple="<?php echo $j?>"><a href="javascript:;"><?php echo $courseD->getName();
    if($courseD->getOfferedById() != '' && $instituteId != $courseD->getOfferedById() && $courseD->getOfferedByShortName() != ''){ echo ', '.$courseD->getOfferedByShortName();}?></a></li>
    <?php 
  }
}