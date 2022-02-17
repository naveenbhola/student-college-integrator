<?php
class ListingProfileConfig {
	
	public static $KEY_SEPERATOR = "%**===################&&&&&&@@@@@";
	public static $NO_OF_FIELDS_TO_DISPLAY = 5;
	public static $MEDIA_MAX_COUNT = 5;
	public static  $INSTITUTE_COURSE_SCORES_MAPPING = array('institute'=>
	array('national' =>
	array('abbreviation'=>array('label'=>'Also known as','max-score'=>1),
                                                    'establish_year'=>array('label'=>'Year of establishment','max-score'=>1),
                                                    'usp'=>array('label'=>'USP','max-score'=>1),
                                                    'institute_logo'=>array('label'=>'Logo of the Institute','max-score'=>1),
						    						'institute_request_brochure_link'=>array('label'=>'Upload Brochure','max-score'=>3),	                            
                                                    'contact_main_phone'=>array('label'=>'Main phone','max-score'=>1),
                                                    'contact_cell'=>array('label'=>'Mobile number','max-score'=>1),
                                                    'contact_email'=>array('label'=>'Email Address','max-score'=>1),
                                                    'website'=>array('label'=>'Website','max-score'=>1),
                                                    'address'=>array('label'=>'Address','max-score'=>1),
                                                    'joinreason'=>array('label'=>'Why should students join this institute','max-score'=>5),
                                                    'insti_desc'=>array('label'=>'Institute Description','max-score'=>5),
						    						'rankings'=>array('label'=>'Rankings & Awards','max-score'=>3),
						    						'recruiting_companies'=>array('label'=>'Top Recruiting Companies','max-score'=>3),
						    						'infrastructure'=>array('label'=>'Infrastructure/Teaching Facilities','max-score'=>3),	 			
                                                    'hostel_details'=>array('label'=>'Hostel Details','max-score'=>2),
                                                    'faculty'=>array('label'=>'Top Faculty','max-score'=>2),
                                                    //'seofieldsvalues'=>array('label'=>'SEO Fields','max-score'=>4),
                                                    'photo-video-count'=>array('label'=>'Photo/Video (media tab)','max-score'=>10),
                                                    'recruiting-companies-media-tab'=>array('label'=>'Recruiting companies (media tab)','max-score'=>6)
	),'local'=>
	array(
                                                    'establish_year'=>array('label'=>'Year of establishment','max-score'=>1),
                                                    'usp'=>array('label'=>'USP','max-score'=>3),
                                                    'institute_logo'=>array('label'=>'Logo of the Institute','max-score'=>1),
						    						'institute_request_brochure_link'=>array('label'=>'Upload Brochure','max-score'=>8),	                            
                                                    'contact_main_phone'=>array('label'=>'Main phone','max-score'=>2),
                                                    'contact_cell'=>array('label'=>'Mobile number','max-score'=>2),
                                                    'contact_email'=>array('label'=>'Email Address','max-score'=>2),
                                                    'website'=>array('label'=>'Website','max-score'=>2),
                                                    'address'=>array('label'=>'Address','max-score'=>2),
                                                    'joinreason'=>array('label'=>'Why should students join this institute','max-score'=>5),
                                                    'insti_desc'=>array('label'=>'Institute Description','max-score'=>5),						    
                                                    //'seofieldsvalues'=>array('label'=>'SEO Fields','max-score'=>4),
                                                    'photo-video-count'=>array('label'=>'Photo/Video (media tab)','max-score'=>10)
	)
	),
                                           'course'=>
	array('national' =>
	array('fees_value'=>array('label'=>'Entire course fee','max-score'=>3),
                                                    'AffiliatedTo'=>array('label'=>'Affiliated to','max-score'=>2),
                                                    'approved_by'=>array('label'=>'Approved / Granted by','max-score'=>2),
                                                    'AccreditedBy'=>array('label'=>'Accredited by','max-score'=>2),
						    						'courseExams'=>array('label'=>'Entrance Exam Requried','max-score'=>3),	                            
                                                    'otherEligibilityCriteria'=>array('label'=>'Other eligibility criteria','max-score'=>2),
                                                    'no_of_seats'=>array('label'=>'Number of Seat','max-score'=>2),
                                                    'important_dates'=>array('label'=>'Important dates','max-score'=>3),
                                                    'course_request_brochure_link'=>array('label'=>'Upload Brochure','max-score'=>8),
                                                    'salary_statistics'=>array('label'=>'Salary Statistics','max-score'=>3),
                                                    'contact_details'=>array('label'=>'Contact Person Details','max-score'=>3),
                                                    'course_desc'=>array('label'=>'Course Description','max-score'=>5),
						    						'eligibility'=>array('label'=>'Eligibility','max-score'=>3),
						    						'admission_procedure'=>array('label'=>'Admission Procedure','max-score'=>3),
						    						'syllabus'=>array('label'=>'Syllabus','max-score'=>3),	 	
                                                    'faculty'=>array('label'=>'Faculty','max-score'=>2)
                                                    //'seofieldsvalues'=>array('label'=>'SEO Fields','max-score'=>4)
	),'local'=>
	array(
                                                    'fees_value'=>array('label'=>'Entire course fee','max-score'=>3),
                                                    'approved_by'=>array('label'=>'Approved / Granted by','max-score'=>2),
                                                    'courseExams'=>array('label'=>'Entrance Exam Requried','max-score'=>1),
						    						'otherEligibilityCriteria'=>array('label'=>'Other eligibility criteria','max-score'=>1),	                            
                                                    'course_request_brochure_link'=>array('label'=>'Upload Brochure','max-score'=>8),                                         
                                                    'contact_details'=>array('label'=>'Contact Person Details','max-score'=>3),
						    						'course_desc'=>array('label'=>'Course Description','max-score'=>5),
						    						'eligibility'=>array('label'=>'Eligibility','max-score'=>3)
						    						//'seofieldsvalues'=>array('label'=>'SEO Fields','max-score'=>4)		
	)
	)

	);






}
