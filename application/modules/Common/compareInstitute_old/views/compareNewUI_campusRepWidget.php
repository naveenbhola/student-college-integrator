  <!--intrested courses-->
<?php $totalFilled = count($institutes);
$displayData['totalFilled'] = $totalFilled;
?>
<tr id="CTAsec">
  <td><div class="cmpre-head"><label>Interested in this course?</label></div></td>

<?php 
foreach($institutes as $key => $institute){
  $courseObj[$key] = $institute->getFlagshipCourse();
  $courses[$key] = $courseObj[$key]->getId();
  $subCatIdArray[$key] = $institute->getFlagshipCourse()->getDominantSubcategory()->getId();
}
$displayData['courses'] = $courses;
$displayData['subCatIdArray'] = $subCatIdArray;
global $flagForApplyNowButton;
$flagForApplyNowButton=0;
global $flagForShortlist;
$flagForShortlist=0;
?>
<td id = "sec" colspan="<?php echo $totalFilled;?>" style="padding:0;border-top:0;">
    
    <?php 
    if(in_array('23', $subCatIdArray)){
      $national_course_lib  = $this->load->library('listing/NationalCourseLib');
      $displayData['courseObj'] = $courseObj;
      $displayData['national_course_lib']= $national_course_lib;
      $this->load->view('shortlist',$displayData);
      }?>
        
    <?php $this->load->view('askCurrentStudents',$displayData);?>
      
    <?php $this->load->view('applyNow',$displayData);?>
</td> 
  <?php
    if($flagForApplyNowButton == 0 && $flagForCampurRepExists == 0 && $flagForShortlist == 0){?>
    <script>$('sec').parentNode.removeChild($('sec'));</script>
    <?php
      $this->load->view('requestEBrochure',$displayData);
      }?>
</tr>