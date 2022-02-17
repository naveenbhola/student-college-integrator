<?php
/**
 * This class stores config variable in static member
 *
 * @version
 */
class TestConfig {
	// associative array stores static details for the colleges
	public static $type_of_exams =
	array(
			'CAT' => array(
							'instructions'=>'This practice test is designed to provide you with a comprehensive overviw of what to epect on the day of your test. It contains a fully functional practice test - giving you the ability to interact with well ahead of your exam.<div style="margin-top:10px;"/><br/> - You are not allowed to stop/pause the test once it is started<br/> - To skip a question click on <b>Skip</b> button.<br/> - To finish the test in between click on <b>Finish</b> button<br/><div style="margin-top:10px;"/> Once you are Ready click on <b>Start Test</b> below.<div style="margin-top:10px;"/>
							This practice test is designed to provide you with a comprehensive overviw of what to epect on the day of your test. It contains a fully functional practice test - giving you the ability to interact with well ahead of your exam.<div style="margin-top:10px;"/><br/> - You are not allowed to stop/pause the test once it is started<br/> - To skip a question click on <b>Skip</b> button.<br/> - To finish the test in between click on <b>Finish</b> button<br/><div style="margin-top:10px;"/> Once you are Ready click on <b>Start Test</b> below.<div style="margin-top:10px;"/>
							<b>Disclaimer:</b><br/>By clicking on the below link, you agree to our terms and policies', 'id'=>1
						),
			'MAT' => array( 
							'instructions'=>'This practice test is designed to provide you with a comprehensive overviw of what to epect on the day of your test. It contains a fully functional practice test - giving you the ability to interact with well ahead of your exam.<div style="margin-top:10px;"/><br/> - You are not allowed to stop/pause the test once it is started<br/> - To skip a question click on <b>Skip</b> button.<br/> - To finish the test in between click on <b>Finish</b> button<br/><div style="margin-top:10px;"/> Once you are Ready click on <b>Start Test</b> below.<div style="margin-top:10px;"/>
							This practice test is designed to provide you with a comprehensive overviw of what to epect on the day of your test. It contains a fully functional practice test - giving you the ability to interact with well ahead of your exam.<div style="margin-top:10px;"/><br/> - You are not allowed to stop/pause the test once it is started<br/> - To skip a question click on <b>Skip</b> button.<br/> - To finish the test in between click on <b>Finish</b> button<br/><div style="margin-top:10px;"/> Once you are Ready click on <b>Start Test</b> below.<div style="margin-top:10px;"/>
							<b>Disclaimer:</b><br/>By clicking on the below link, you agree to our terms and policies', 'id'=>2
						),
			'XAT' => array(
							'instructions'=>'This practice test is designed to provide you with a comprehensive overviw of what to epect on the day of your test. It contains a fully functional practice test - giving you the ability to interact with well ahead of your exam.<div style="margin-top:10px;"/><br/> - You are not allowed to stop/pause the test once it is started<br/> - To skip a question click on <b>Skip</b> button.<br/> - To finish the test in between click on <b>Finish</b> button<br/><div style="margin-top:10px;"/> Once you are Ready click on <b>Start Test</b> below.<div style="margin-top:10px;"/>
							This practice test is designed to provide you with a comprehensive overviw of what to epect on the day of your test. It contains a fully functional practice test - giving you the ability to interact with well ahead of your exam.<div style="margin-top:10px;"/><br/> - You are not allowed to stop/pause the test once it is started<br/> - To skip a question click on <b>Skip</b> button.<br/> - To finish the test in between click on <b>Finish</b> button<br/><div style="margin-top:10px;"/> Once you are Ready click on <b>Start Test</b> below.<div style="margin-top:10px;"/>
							Disclaimer:<br/>By clicking on the below link, you agree to our terms and policies', 'id'=>3			
								)
	);

	public static $duration_array = 
	array(
		  '30'=>
			array('2' =>5,'3'=>5,'4'=>5,'5'=>5,'Total'=>20),
		  '60'=>
			array('2' =>10,'3'=>10,'4'=>10,'5'=>10,'Total'=>40),
		  '90'=>
			array('2' =>15,'3'=>15,'4'=>15,'5'=>15,'Total'=>60),
		  '120'=>
			array('2' =>20,'3'=>20,'4'=>20,'5'=>20,'Total'=>40),
		 );

	public static $section_array = 
	array(
		  'Quantitative Ability'=>
			array('Help' => 'To test your Quant and make it strong', 'id'=>2),
		  'Data Interpretation'=>
			array('Help' => 'To test your Aptitude and make it strong', 'id'=>3),
		  'Verbal Ability'=>
			array('Help' => 'To test your language and vocabulary and make it strong', 'id'=>4),
		  'Logical Reasoning'=>
			array('Help' => 'To test your logical reasoning and make it strong', 'id'=>5),
		  'All'=>
			array('Help' => 'Complete test with all the sections', 'id'=>1),
		 );

	public static $level_array = 
	array(
		  'Easy'=>
			array('Help' => 'Easy level for Beginners', 'id'=>1),
		  'Medium'=>
			array('Help' => 'Medium level for Experienced persons', 'id'=>2),
		  'Difficult'=>
			array('Help' => 'Difficult level for Experts persons', 'id'=>3),
		 );
	
}
?>

