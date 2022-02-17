
<input type="hidden" id="courseId_<?=$widget?>" name="courseId" value="<?=$course->getId()?>">
<input type="hidden" id="pageName" name="pageName" value="compareToolPage">
<input type="hidden" id="hideLoader_ComparePage" name="hideLoader_ComparePage" value=1>

<?php
		$widget = 'askAQuestion';
    $formCustomData['widget'] = $widget;
    $formCustomData['trackingPageKeyId'] = $trackingPageKeyId;
    $formCustomData['buttonText'] = '';
    $formCustomData['customCallBack'] = '';
    $formCustomData['instituteCoursesLPR'] = $instituteCoursesLPR;
    $formCustomData['pageType'] = $pageType;
    $formCustomData['defaultCourseId'] = $course->getId();
    $formCustomData['defaultCourse']                   = $instituteCoursesLPR[$course->getId()]['desiredCourse'];
    $formCustomData['defaultCategory']                 = $instituteCoursesLPR[$course->getId()]['categoryId'];
echo Modules::run('registration/Forms/LDB',NULL,'askQuestionBottomCompare',$formCustomData); 
?>  
<script>      
  var func = window['regFormLoadaskAQuestion'];
   if(typeof func === 'function') {
      regFormLoadaskAQuestion();
   }
   var askQuestionBottomCompare = true;
   var widget = '<?=$widget?>';
   var $categorypage = 0;
</script>
