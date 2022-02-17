<!-- Start: Display the Fixed Header on the Listing detail page -->
 <?php  $courses = $institute->getCourses();


if(isset($_COOKIE['currentCourse']) && $_COOKIE['currentCourse']!='' && $tab!='courseTabs' ){
	foreach($courses as $course){
	if($course->getId() == $_COOKIE['currentCourse']){
		$courseURL = $course->getURL();
		break;	
	}
    }
}
?>
   
    <section id="tab" class="clearfix"> 
    	<table width="100%" cellpadding="0" cellspacing="0">
       	    <tr>
                <td <?php echo ($tab == 'overview')?'class="active"':''; ?>>
			<a id="overviewTabLink" href="<?=$overviewTabUrl?>" >
			<i class="icon-info"></i>
			College
			</a>
                </td>
                <td <?php echo ($tab == 'courses' || $tab == 'courseTabs')?'class="active"':''; ?>>
                	<a id="overviewCoursesTab" href="<?php if($courseURL!=''){ echo $courseURL;} else echo $courseTabUrl; ?>"> 
			<i class="icon-course"></i>
	                Courses</a>
                </td>
            </tr>
        </table>
    </section>
<!-- End: Display the Fixed Header on the Listing detail page -->
    
