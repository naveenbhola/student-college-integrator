<?php
if(is_array($recent_view) && count($recent_view) > 0)
{    
       foreach((object)$recent_view as $key=>$recentView)
       {
            $course    = $courseRepository->find($recentView->course_id);
            $institute = $instituteRepository->find($course->getInstId());
            
            echo $course->getId();
            echo $course->getName();
            
            echo $institute->getName();
            echo $institute->getId();
            //echo $institute->getMainHeaderImage();
            echo ($institute->getMainHeaderImage()?$institute->getMainHeaderImage()->getThumbURL():'');
            echo '<br>';
       }
        
        
}
?>