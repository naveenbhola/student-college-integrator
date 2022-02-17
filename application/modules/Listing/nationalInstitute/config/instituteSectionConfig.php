<?php
$config = array();

$instituteFieldsToSectionMapping = array(
										"is_satellite_entity"      => "basicSectionData",
										"is_dummy"                 => "basicSectionData",
										"name"                     => "basicSectionData",
										"abbreviation"             => "basicSectionData",
										"synonyms"                 => "basicSectionData",
										"ownership"                => "basicSectionData",
										"students_type"            => "basicSectionData",
										"accreditation"            => "basicSectionData",
										"establishment_year"       => "basicSectionData",
										"is_autonomous"            => "basicSectionData",
										"is_national_importance"   => "basicSectionData",
										"main_location"            => "locationSectionData",
										"locations"                => "locationSectionData",
										"academic_staff"           => "academicSectionData",
										"staff_faculty_highlights" => "academicSectionData",
										"facilities"               => "facilitySectionData",
										"research_projects"        => "researchProjectSectionData",
										"events"                   => "eventsSectionData",
										"scholarships"             => "scholarshipSectionData",
										"usp"                      => "uspSectionData",
										"brochure_url"             => "brochureSectionData",
										"brochure_year"            => "brochureSectionData",
										"logo_url"                 => "mediaSectionData",
										"photos"                   => "mediaSectionData",
										"videos"                   => "mediaSectionData",
										"companies"                => "companiesSectionData",
										"seo_title"                => "seoSectionData",
										"seo_description"          => "seoSectionData",
										"seo_url"                  => "seoSectionData",
										"parent_entity_id"         => "hierarchySectionData",
										"parent_entity_type"       => "hierarchySectionData",
										"primary_entity_id"        => "hierarchySectionData",
										"primary_entity_type"      => "hierarchySectionData",
										"contact_details"          => "contactSectionData",
										"placementPageExists" => "childPageExists", 
										"flagshipCoursePlacementDataExists" => "childPageExists",
										"naukriPlacementDataExists" => "childPageExists",
										"cutoffPageExists" => "childPageExists",
										"cutoffPageExamName" => "childPageExists",
										"aboutCollege" => "wikiSection",
										"seoData" => "seoData"
										);

$config['instituteFieldsToSectionMapping'] = $instituteFieldsToSectionMapping;

global $instituteFieldSectionToTableMapping;
$instituteFieldSectionToTableMapping = array(
											"basicSectionData"           => array("shiksha_institutes","listings_main"),
											"locationSectionData"        => array("shiksha_institutes_locations","shiksha_listings_contacts","shiksha_institutes_media_locations_mapping","shiksha_institutes_media_tags_mapping"),
											"academicSectionData"        => array("shiksha_institutes_academic_staffs"),
											"facilitySectionData"        => array("shiksha_institutes_facilities","shiksha_institutes_facilities_mappings"),
											"researchProjectSectionData" => array("shiksha_institutes_additional_attributes"),
											"eventsSectionData"          => array("shiksha_institutes_events"),
											"scholarshipSectionData"     => array("shiksha_institutes_scholarships"),
											"uspSectionData"             => array("shiksha_institutes_additional_attributes"),
											"brochureSectionData"        => array("shiksha_listings_brochures"),
											"mediaSectionData"           => array("shiksha_institutes_medias","shiksha_institutes_media_locations_mapping","shiksha_institutes_media_tags_mapping"),
											"companiesSectionData"       => array("shiksha_institutes_companies_mapping"),
											"seoSectionData"             => array("listings_main"),
											"hierarchySectionData"       => array("shiksha_institutes"),
											"contactSectionData"         => array("shiksha_listings_contacts")
											);


$listingMainStatus = array("draft"=>"draft",
						   "live"=>"live");
$config['listingMainStatus'] = $listingMainStatus;

global $instituteSections;
$instituteSections = array('basic','location','academic','facility','research_project','usp','event','media','scholarship','company','childPageExists','brochureSectionData', 'wikiSection','seoData');

global $instituteSectionNotCached;
$instituteSectionNotCached = array();

$config['instituteToolTipData'] = 
				  array('autonomous'             => array('name' => 'Autonomous' , 'helptext' => 'Autonomous Institute is one which is independent of State control over day-to-day operations and curriculum. Such an institute falls under the administrative control of the Department of Higher Education but is free from external control over its academic matters.'),
						'nationalImportance'     => array('name' => 'National Importance' , 'helptext' => 'Institute of National Importance is a status accorded by an act of Parliament, to an institution of higher education which plays a pivotal role in developing highly skilled personnel within the specified region of the country/state and discipline.'),
				  		'ugc_approved' => array('name' => 'UGC Approved', 'helptext' => 'The University Grants Commission (UGC) is a statutory body established in 1956 by the Government of India through an Act of Parliament for the coordination, determination and maintenance of standards of university education in India.'),
				  		'university_type_deemed' => array('name' => 'Deemed University', 'helptext' => "Deemed (or Deemed-to-be) University is an Institution of higher education, other than universities, working at a very high standard in specific area of study. Such institutes enjoy academic status and privileges of a university and are declared 'Deemed-to-be' by the Department of Higher Education on the advice of the UGC."),
						'university_type_central' => array('name' => 'Central University', 'helptext' => 'Central University is a national level university which fucntions under the purview of the Department of Higher Education in the Ministry of Human Resource Development  and can award degrees to the students.'),
						'university_type_state' => array('name' => 'State University', 'helptext' => 'State University is a university that is funded and run by the State Government. Since such universities are established under State Act, they are eligible to grant degrees to the students.'),
						'university_type_private' => array('name' => 'Private University', 'helptext' => "Private University is a university established through a State/ Central Act by a sponsoring body viz. a Society, a Public Trust or a Company. Such universities may be independent of UGC control over day-to-day activities, but cannot award degrees, affiliate institutes/colleges or set up off campus centres etc. without the UGC's approval."),
				  		'naac_accreditation'     => array('name' => 'NAAC Accreditation' , 'helptext' => 'The National Assessment and Accreditation Council (NAAC) is an autonomous body established by the UGC to assess and accredit institutions of higher education in the country. It grades an institution from A++ (best rating) to C. Rating D indicates that the institution is not accredited.'),					
						'university_status'      => array('name' => 'University Status' , 'helptext' => 'University Grants Commission (UGC) - It is the apex grant giving body for institutes of higher education. It provides funds to institutes and is responsible for maintaining high standards of education in the country'),
						'university_association' => array('name' => 'University Association' , 'helptext' => 'Association of Indian Universities (AIU) - This is an inter-university organization set up as a bureau to facilitate communication between all Indian universities, provide consultation, liaise between universities and the government and to help them maintain their autonomy.'),						
						'aiu_member' => array('name' => "Member of AIU", 'helptext' => 'Association of Indian Universities (AIU) is an association of major universities in India set up with the aim of serving as an Inter-University Organisation; to act as a bureau of information and to facilitate communication, coordination and mutual consultation amongst universities; to help universities to maintain their autonomous character and to act as the representative of universities of India.'),
						'affiliated_definition' => array('helptext' => 'Colleges can be of 2 types – Constituent colleges and Affiliated colleges. Constituent colleges are colleges which are maintained by the university itself. Affiliated colleges are educational institutions that operate independently, but also have a formal collaborative agreement with a university for the purpose of awarding the degree with some level of control or influence over its academic policies, standards or programs.')
					);




global $AdmissonOfficeIdCanonical;
$AdmissonOfficeIdCanonical = array(
	47414 =>	32303,
	25137 =>	37062,
	38577 =>	26485,
	54763 =>	54757,
	46944 =>	25061,
	46893 =>	36995,
	34457 =>	47391,
	34455 =>	47392,
	36321 =>	28499,
	54762 =>	54754,
	54768 =>	54755,
	41287 =>	47393,
	54761 =>	54756,
	61759 =>	61743,
	38134 =>	30663,
	29191 =>	36540,
	46863 =>	34247
);

global $removeInsAmpPages;

$removeInsAmpPages = array(29851,3701,23593,30302,25287,29714,23069,843,31514,25139,3155,29714,25028,51559,344,32767,35965,53841,53877,26486,46852,53802,53876,53859,53869,54218,53872,39366,53865,53843,53849,53868,46856,38111,39384,39392,51894,59105,53806,53875,29623,47711,49179,20188,32740,36085,23700,36076,36080,20190,318,333,32728,32736,47709,49314,48265,47712,307,47703,2999,54212,53833,3065,32705,38240,13669,53787,53834,32693,25116,3031,53938,3057,32717,32712,32697,33322,3011,53829,53794,53791,32726,35298,24357,36786,4288,54019,24395,24247,28530,28583,25425,39335,39060,24366,23147,24372,23159,24399,23645,24414,24187,32742,24264,32739,39201,39397,38584,2996,38697,39180,39238,59093);