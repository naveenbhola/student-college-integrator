<!--intrested courses-->
<?php 
$totalFilled = count($courseIdArr);
$displayData['totalFilled'] = $totalFilled;
?>
<tr id="CTAsec">
  <td><div class="cmpre-head"><label>Interested in this course?</label></div></td>
<?php 
//$displayData['courses'] = $courses;
global $flagForApplyNowButton;
$flagForApplyNowButton=0;
global $flagForShortlist;
$flagForShortlist=0;
?>
<td id = "sec" colspan="<?php echo $totalFilled;?>" style="padding:0;border-top:0;">
    <?php 
    /*if(in_array('23', $subCatIdArray)){
      $national_course_lib  = $this->load->library('listing/NationalCourseLib');
      $displayData['courseObj'] = $courseObj;
      $displayData['national_course_lib']= $national_course_lib;
      }*/
      $this->load->view('shortlist',$displayData);
    ?>    
    <?php $this->load->view('askCurrentStudents', $displayData);?>
    <?php $this->load->view('applyNow', $displayData);?>
</td> 
  <?php
    if($flagForApplyNowButton == 0 && $flagForCampurRepExists == 0 && $flagForShortlist == 0)
    {
    ?>
      <script>$('sec').parentNode.removeChild($('sec'));</script>
    <?php 
      foreach ($courseIdArr as $courseId) {
        echo '<td align="center" style="vertical-align:middle;">';
        if($courseObjs[$courseId]->getBrochureURL()){
          $this->load->view('downloadBrochure', array('courseId' => $courseId, 'trackingKeyId' => 683));
        }else{
          echo '--';
        }
        echo '</td>';
      }
    }
    ?>
</tr>