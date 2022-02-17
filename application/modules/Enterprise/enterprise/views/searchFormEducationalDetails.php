<!--Start_EducationInterest-->
<div class="line_space20">&nbsp;</div>
<div class="cmsSearch_contentBoxTitle"><div style="padding:0 0 17px 25px"><b>Educational Interest</b></div></div>
<input type="hidden" value="<?php echo $actual_course_name; ?>" name="actual_course_name">
<?php
//echo $boolen_flag_to_apply_hack_on_mba_courses . "XXXXXXX".$course_name;
if (($course_name =='IT Courses' || $course_name =='IT Degrees')&&($boolen_flag_to_apply_hack_on_mba_courses ==
'true'))  {
	if ($course_name =='IT Courses') {
	    ?>
	    <input type="hidden" value="IT Courses" name="search_form_course_name">
	    <?php
	    $this->load->view('enterprise/itCoursesEducationInterest'); //Desired Courses, Select Student Details, Current Location(from itCourseEdIn,searchFormEducationDetailsCurrentLocation.php)
	} else {
	  
	    ?>
	    <input type="hidden" value="IT Degree" name="search_form_course_name">
	    <?php
	    $this->load->view('enterprise/itDegreeEducationInterest'); //Desired Course, 
	    $this->load->view('enterprise/searchFormEducationDetailsCourseMode'); //full time or part time
	    
	    ?>
	    <div class="cmsSearch_SepratorLine">&nbsp;</div>
	    <div class="cmsSearch_contentBoxTitle"><div style="padding:0 0 17px 25px"><b>Select Student Details</b></div></div>
	    <?php
	    
	    $this->load->view('enterprise/searchFormEducationDetailsCurrentLocation');	//current location
	    $this->load->view('enterprise/searchFormEducationDetailsPreferredLocation'); //preferred location almost everything commented 
	    //$this->load->view('enterprise/whn_plan_start');
	}
    } else if($course_name == 'Study Abroad') {
    ?>
	    <input type="hidden" value="Study Abroad" name="search_form_course_name">
    <?php
	    $this->load->view('enterprise/searchFormFieldOfInterestField');
		if($sa_course_type == 'category' || $sa_course == 'All') {
			$this->load->view('enterprise/searchFormDesiredCourseField');
		}
	    $this->load->view('enterprise/searchFormEducationDetailsDestinationCountry');
	    $this->load->view('enterprise/searchFormEducationDetailsCurrentLocation');
	    //$this->load->view('enterprise/searchFormSourceOfFunding');
		$this->load->view('enterprise/searchFormEducationDetailsAbroad');
    } else {
      $message = ($boolen_flag_to_apply_hack_on_mba_courses == 'false') ? $actual_course_name : $course_name;

      if ($message == 'Engineering Distance Diploma')
      {
	$message = 'Distance Diploma';
      }
      if ($message == 'Science & Engineering PHD')
      {
	$message = 'Phd';
      }
      if ($message == 'Engineering Diploma')
      {
	$message = 'Diploma';
      }
      if ($message == 'Management Certifications')
      {
	$message = 'Certifications';
      }

//echo $message . '-----------------';
	?>
	<input type="hidden" value="<?php echo $message; ?>" name="search_form_course_name">
	<?php
	if ($message == 'Distance/Correspondence MBA' || $message == 'Online MBA' || $message == 'Executive MBA' ||
	  $message == 'Part-time MBA' || $message == 'Certifications' || $message == 'Distance Diploma' || $message ==
	  'Distance B.Sc' || $message == 'Distance M.Sc' || $message == 'Distance B.Tech')
	{

	  $this->load->view('enterprise/searchFormEducationDetailsDesiredCourse');	//desired course
	  $this->load->view('enterprise/searchFormEducationDetailsCourseSpecialization'); //course speciaization
	  if($message == 'Executive MBA')
	  {
	    //$this->load->view('enterprise/searchFormEducationDetailsCourseMode');   //mode
	     ?>
	    <div class="cmsSearch_SepratorLine">&nbsp;</div>
	    <div class="cmsSearch_contentBoxTitle"><div style="padding:0 0 17px 25px"><b>Select Student Details</b></div></div>
	    <?php
	    $this->load->view('enterprise/searchFormEducationDetailsCurrentLocation'); 
	    $this->load->view('enterprise/searchFormEducationDetailsPreferredLocation');  
	  }
	}
	elseif ($message == 'Science & Engineering Degrees'|| $message == 'Aircraft Maintenance Engineering')
	{
	  $this->load->view('enterprise/itCoursesEducationInterest_forscienceandmngt');
	  $this->load->view('enterprise/searchFormEducationDetailsCourseMode');
	   ?>
	    <div class="cmsSearch_SepratorLine">&nbsp;</div>
	    <div class="cmsSearch_contentBoxTitle"><div style="padding:0 0 17px 25px"><b>Select Student Details</b></div></div>
	    <?php
	  $this->load->view('enterprise/searchFormEducationDetailsCurrentLocation'); //current location
	  $this->load->view('enterprise/searchFormEducationDetailsPreferredLocation'); //preferred location almost everything commented 
	  //$this->load->view('enterprise/searchFormEducationDetailsDegreePref'); //degree mode pref(AICTE approved)
	}
	else if (($message == 'Medicine, Beauty & Health Care Degrees' ||
	  $message == 'Marine Engineering' ||
	  $message == 'Integrated MBA Courses')
&& ($_REQUEST['categoryId'] == '2'))
	{
	  $this->load->view('enterprise/itCoursesEducationInterest_forscienceandmngt'); //desired course
	  //$this->load->view('enterprise/searchFormEducationDetailsCourseMode'); //mode
	   ?>
	    <div class="cmsSearch_SepratorLine">&nbsp;</div>
	    <div class="cmsSearch_contentBoxTitle"><div style="padding:0 0 17px 25px"><b>Select Student Details</b></div></div>
	    <?php
	  $this->load->view('enterprise/searchFormEducationDetailsCurrentLocation'); 
	  $this->load->view('enterprise/searchFormEducationDetailsPreferredLocation'); 
	  $this->load->view('enterprise/searchFormEducationDetailsDegreePref');  
	}
	else
	{
	  $this->load->view('enterprise/searchFormEducationDetailsDesiredCourse'); //desired course
	  $this->load->view('enterprise/searchFormEducationDetailsCourseSpecialization'); //course spec
	  if($course_name!='Distance/Correspondence MBA') {
	      $this->load->view('enterprise/searchFormEducationDetailsCourseMode');
	  }
	   ?>
	    <div class="cmsSearch_SepratorLine">&nbsp;</div>
	    <div class="cmsSearch_contentBoxTitle"><div style="padding:0 0 17px 25px"><b>Select Student Details</b></div></div>
	    <?php
	  $this->load->view('enterprise/searchFormEducationDetailsCurrentLocation'); //curr
	  if($course_name!='Distance/Correspondence MBA') {
	      $this->load->view('enterprise/searchFormEducationDetailsPreferredLocation');
	  }
	  if($course_name!='Distance/Correspondence MBA') {
	      $this->load->view('enterprise/searchFormEducationDetailsDegreePref');
	  }
	}
    }
?>
<!--End_EducationInterest-->
