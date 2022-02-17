<?php
  $shortlistedCoursesOfUser = array();
  if(isset($validateuser[0]['userid'])) {
    $shortlistedCoursesOfUser =  Modules::run('myShortlist/MyShortlist/getShortlistedCourse',$validateuser[0]['userid']); 
  }
  global $flagForShortlist;
  $flagWidget =0;
  $flagForShortlist=1;
?>
<div class="ask-section" id="sho">
<?php $i=0; foreach ($courseIdArr as $key => $course) {
  $courseShortlistedStatus = 0;
  if(in_array($course, $shortlistedCoursesOfUser)) {
              $courseShortlistedStatus = 1;
  }
  ?>
  <div class = "cmp-shortlist-col">
  <div id="slSec<?php echo $course;?>" onclick="var customParam = {'shortlistCallback':'shortlistCallbackComparePage', 'shortlistCallbackParam':{'thisObj':$('slSec<?php echo $course;?>')}, 'shortlistCallbackObj':'comparePageObj', 'trackingKeyId':681, 'pageType':'ND_Compare'}; myShortlistObj.checkCourseForShortlist('<?php echo $course;?>', customParam);">
  <span id="comp_star_<?php echo $course; ?>" class="cmpre-sprite <?php if($courseShortlistedStatus != 1) echo 'icon-shortlist'; else echo 'icon-shortlisted'?>"></span>
  <a class="shrlist-txt <?php echo "shrt".$course?>" href="javascript:void(0);"><span class="btn-label"><?=$courseShortlistedStatus == 1 ? 'Shortlisted' :'Shortlist' ?></span></a>    
  </div>
  </div>

<?php 
$i++; } ?>
</div>