<?php
  if(isset($validateuser[0]['userid'])) {
    $shortlistedCoursesOfUser =  Modules::run('myShortlist/MyShortlist/fetchShortlistedCoursesOfAUser',$validateuser[0]['userid']); 
  }
  global $flagForShortlist;
  $flagWidget =0;
  foreach ($courses as $key => $course) {
    $flagSho[$key] = $national_course_lib->checkForMBATemplateEligibility(array($subCatIdArray[$key]), $courseObj[$key]);
    if($flagSho[$key] == 1){
      $flagWidget = 1;
    }
  }
  if($flagWidget == 1){
    $flagForShortlist=1;
?>
<div class="ask-section" id="sho">
<?php $i=0; foreach ($courses as $key => $course) {
  $courseShortlistedStatus = 0;
  if($subCatIdArray[$key] == 23 && $flagSho[$key] == 1){
    if(in_array($course, $shortlistedCoursesOfUser)) {
                $courseShortlistedStatus = 1;
    }
    $shortlistStatus[$course] = $courseShortlistedStatus;
    ?>
  <script>var shortlistStatus = <?php echo json_encode($shortlistStatus);?>;
  </script>
  <div class = "cmp-shortlist-col">
  <div id="slSec<?php echo $course;?>" onclick="if(shortlistStatus[<?php echo $course;?>] == 1){ globalShortlistParams = {pageName : 'compareDesktop', courseId: <?php echo $course?>, action: 'delete', shortlistCallback: 'removeShortlistTupleCallback'}; removeFromShortlist(<?php echo $course;?>); gaTrackEventCustom('COMPARE_DESK', 'UnShortList', '<?php echo $course?>', this, '');} else{if(isShortListingInProgress) { return false;}  globalShortlistParams = {courseId: <?php echo $course?>, pageType: 'ND_Compare', buttonId: '', shortlistCallback: 'shortlistCallbackForCtpgTuple',tracking_keyid :'682'}; shikshaUserRegistration.showShortlistRegistrationLayer({courseId: <?=$course?>, source: 'ND_Compare'}); gaTrackEventCustom('COMPARE_DESK', 'ShortList', '<?php echo $course?>', this, '');}">
  <span id="comp_star_<?php echo $course; ?>" class="cmpre-sprite <?php if($courseShortlistedStatus != 1) echo 'icon-shortlist'; else echo 'icon-shortlisted'?>"></span>
  <a class="shrlist-txt <?php echo "shrt".$course?>" href="javascript:void(0);"><span class="btn-label"><?=$courseShortlistedStatus == 1 ? 'Shortlisted' :'Shortlist' ?></span></a>    
  </div>
  </div>

<?php }
  else{?>
  <div class="cmp-shortlist-col"> -- </div>
<?php }
$i++; } ?>
</div>
<?php } ?>