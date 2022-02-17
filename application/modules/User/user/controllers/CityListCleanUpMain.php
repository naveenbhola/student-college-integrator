<?php
class CityListCleanUpMain extends MX_Controller {
	//renamed again ##########
	public $oldcityNewCityMappingAgainRenamed = array("All Mumbai"=>array("10224","Mumbai (All)",""),
	"Andaman Nicobar Islands - Other"=>array("10210","Andaman Nicobar Islands",""),
	"Lakshadweep - Other"=>array("1980","Lakshadweep",""),"Canannore/Kannur"=>array("10257","Kannur",""),"Thrissur/Trichur"=>array("10260","Thrissur",""),"Tanjore/Thanjavur"=>array("194","Thanjavur","")/*,"Pondicherry - Other"=>array("10263","Pondicherry","")*/);
	//It holds the mapping of old to new city
	public $oldcityNewCityMapping = 
	array("Andaman Nicobar Islands"=>array("10210","Andaman Nicobar Islands - Other",""),
	"Dirbrugarh"=>array("732","Dibrugarh",""),"Jamanagar"=>array("115","Jamnagar",""),
	"Vadodra"=>array("2767","Vadodara",""),
	"Lakshadweep"=>array("1980","Lakshadweep - Other",""),"Manipur"=>array("144","Manipur - Other","")
	,"Punjab"=>array("916","Punjab - Other",""),"Uttarakhand"=>array("10248","Uttarakhand - Other",""),
	"Burdwan"=>array("906","Bardhaman",""),"Cuddapah"=>array("719","Kadapa","10178"),"Goa"=>array("758","Goa - Other","89"),
	"Quilon"=>array("10195","Kollam","131"),"Trichy"=>array("198","Tiruchirappalli","915"),"Trivandrum"=>array("200","Thiruvananthapuram","757"),
	"New Mumbai"=>array("2246","Navi Mumbai","158"),"Bhubaneshwar"=>array("56","Bhubaneswar","912"),
	"Calcutta"=>array("1359","Kolkata","130"),"Mumbai Suburbs"=>array("152","Mumbai","151"),
	"Others"=>array("10247","All over India","10166"),"Uttaranchal - Other"=>array("204","Uttarakhand - Other","10248"),
	/*"Pondicherry"=>array("172","Pondicherry - Other","10263"),*/"Chandigarh"=>array("10206","Chandigarh","63"),
	"Daman"=>array("1450","Daman & Diu","72"),"City"=>array("862","Delhi","74"),"Krishna"=>array("704","Kolkata","130"),
	"Dadra & Nagar Haveli - Silvassa"=>array("70","Silvassa",""),"Chhattisgarh - Other"=>array("65","Chhattisgarh - Other","10249"),
     "Thanjavur"=>array("194","Tanjore/Thanjavur",""));
	//city mapping for chandigardh 
	//public $oldcityNewCityMappingSpecial = array("Chandigarh"=>array("63","Chandigarh","63"));
	// city to be updated 
	public $city_to_updated = array("Karimnagar"=>array("998","100","3","0"),"Nizamabad"=>array("160","100","3","0"),"Hoskote"=>array("280","106","3","0"),
	"Pathanamthitta"=>array("2326","107","3","0"),"Wayanad"=>array("1059","107","3","0"),
	"Kanchipuram"=>array("1820","123","3","0"),"Nagercoil"=>array("155","123","3","0"),
	"Tirunelveli"=>array("196","123","3","0"),"Vellore"=>array("211","123","3","0"),
	"Moradabad"=>array("150","126","3","0"),"Dehradun"=>array("73","133","2","0"),
	"Ahmednagar"=>array("31","114","3","0"),"Akola"=>array("822","114","3","0"),
	"Aligarh"=>array("34","126","3","0"),"Ambala"=>array("36","120","3","0"),"Amravati"=>array("823","114","3","0"),
	"Anantapur"=>array("39","100","3","0"),"Bareilly"=>array("47","126","3","0"),
	"Bathinda"=>array("48","120","3","0"),"Belgaum"=>array("49","106","3","0"),"Bhagalpur"=>array("736","103","3","0"),
	"Bhilai"=>array("749","104","3","0"),"Bidar"=>array("58","106","3","0"),"Bijapur"=>array("796","106","3","0"),
	"Bokaro Steel City"=>array("10184","112","3","0"),"Calicut"=>array("62","107","3","0"),
	"Chitradurga"=>array("808","106","3","0"),"Chittoor"=>array("703","100","3","0"),
	"Davangere"=>array("806","106","3","0"),"Dharwad"=>array("78","106","3","0"),
	"Dhule"=>array("821","114","3","0"),"Dibrugarh"=>array("732","102","3","0"),
	"District 24 Parganas"=>array("1092","127","3","0"),"Erode"=>array("82","123","3","0"),
	"Faridkot"=>array("864","120","3","0"),"Ferozpur"=>array("1546","120","3","0"),"Ganjam"=>array("861","119","3","0"),
	"Ghazipur"=>array("1577","126","3","0"),"Gorakhpur"=>array("90","126","3","0"),"Gulbarga"=>array("92","106","3","0"),
	"Guntur"=>array("94","100","3","0"),"Haldia"=>array("98","127","3","0"),"Hisar"=>array("101","105","3","0"),
	"Howrah"=>array("905","127","3","0"),"Hubli"=>array("103","106","3","0"),"Jabalpur"=>array("108","113","3","0"),
	"Jalandhar"=>array("111","120","3","0"),"Jalgaon"=>array("112","114","3","0"),"Jamnagar"=>array("115","109","3","0"),
	"Jaunpur"=>array("893","126","3","0"),"Kadapa"=>array("10178","100","3","0"),"Kaithal"=>array("765","105","3","0"),
	"Kanpur"=>array("122","126","2","0"),"Karur"=>array("999","123","3","0"),"Khammam"=>array("699","100","3","0"),
	"Kharagpur"=>array("126","127","3","0"),"Kochi"=>array("127","107","3","0"),"Kolhapur"=>array("129","114","3","0"),
	"Kollam"=>array("131","107","3","0"),"Kottayam"=>array("133","107","3","0"),"Kurnool"=>array("136","100","3","0"),
	"Kurukshetra"=>array("137","105","3","0"),"Latur"=>array("848","114","3","0"),"Mandi"=>array("775","110","3","0"),
	"Manipal"=>array("261","106","3","0"),""=>array("145","126","3","0"),"Meerut"=>array("146","126","3","0"),
	"Midnapore"=>array("1011","127","3","0"),"Modinagar"=>array("887","126","3","0"),"Moga"=>array("2113","120","3","0"),
	"Muktsar"=>array("2144","120","3","0"),"Murthal"=>array("914","105","3","0"),"Muzaffarpur"=>array("737","103","3","0"),
	"Nanded"=>array("847","114","3","0"),"Nellore"=>array("159","100","3","0"),"Panipat"=>array("166","105","3","0"),
	"Parbhani"=>array("846","114","3","0"),"Punjab - Other"=>array("916","120","3","0"),"Raichur"=>array("727","106","3","0"),
	"Raigad"=>array("839","114","3","0"),"Rajkot"=>array("179","109","3","0"),"Rohtak"=>array("181","105","3","0"),
	"Roorkee"=>array("182","133","3","0"),"Rourkela"=>array("183","119","3","0"),"Saharanpur"=>array("888","126","3","0"),
	"Salem"=>array("184","123","3","0"),"Sangli"=>array("833","114","3","0"),"Satara"=>array("835","114","3","0"),
	"Secunderabad"=>array("713","100","3","0"),"Shimoga"=>array("807","106","3","0"),"Solan"=>array("770","110","3","0"),
	"Solapur"=>array("190","114","3","0"),"Sonepat"=>array("2631","105","3","0"),"Tiruchirappalli"=>array("915","123","3","0"),
	"Tumkur"=>array("728","106","3","0"),"Wardha"=>array("840","114","3","0"),"Yamuna Nagar"=>array("2823","105","3","0"),
	"Yavatmal"=>array("2825","114","3","0"),"Nainital"=>array("2179","133","3","0"),"Haridwar"=>array("1651","133","3","0"),
	"Rishikesh"=>array("901","133","3","0"),"Uttarakhand - Other"=>array("10248","133","3","0"),"Kadapa"=>array("10178","100","3","0"),
	"Navi Mumbai"=>array("158","114","2","0"),"Thane"=>array("838","114","3","0"),"Greater Noida"=>
	array("1616","126","2","0"),"Noida"=>array("161","126","2","0"),
	"Daman & Diu"=>array("72","135","3","0"),"Thiruvananthapuram"=>array("757","107","3","0"),
	"Ghaziabad"=>array("87","126","2","0"),
	"Chandigarh"=>array("63","134","2","0"),
	"Dadra & Nagar Haveli - Silvassa"=>array("70","136","3","0"));
	//List to be added 
	public $list_to_be_added = array("Chhattisgarh - Other"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"104","tier"=>"3"),
	"Sirmour"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"110","tier"=>"3"),
	"Nawanshahar"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"120","tier"=>"3"),
	"Sriganaganagar"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"121","tier"=>"3"),
	"Dadri"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"126","tier"=>"3"),
	/*"Daman & Diu - Other"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"135","tier"=>"3"),*/
	"Chikballpura"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"106","tier"=>"3"),
	"Coorg"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"106","tier"=>"3"),
	"Doballapura"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"106","tier"=>"3"),
	"Canannore/Kannur"
	=>array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"107","tier"=>"3"),
	"Idukki"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"107","tier"=>"3"),
	"Kasargode"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"107","tier"=>"3"),
	/*"Thiruvananthapuram/Trivandrum"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"107","tier"=>"3"),*/
	"Thrissur/Trichur"=>
	array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"107","tier"=>"3")
	/*"Pondicherry - Other"
	=>array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"131","tier"=>"3"),*/
	/*"Tanjore/Thanjavur"
	=>array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"123","tier"=>"3")*//*,
	"Chandigarh"
	=>array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"134","tier"=>"2")*/
	,"Dadra And Nagar Haveli - Other"
	=>array("countryId"=>"2","creationDate"=>"now()","enabled"=>"0","notes"=>"new cities added as part of citylist cleanup project","state_id"=>"136","tier"=>"3"));
	//Refering tables mapping10265
	// "virtualCityMapping"=>array("city_id","virtualCityId"), no need to make check for this
	//"tregionmapping"=>array("id","") is not required, used for abroad
	public $refering_tables_mapping_for_cities = array(/*"tuser_deleted"=>array("city","varchar","",""),*/"tUserLocationPref"=>array("CityId","int","",""),
	"tUserEducation"=>array("City","int","",""),"tuser"=>array("city","varchar","",""),
	/*"tSetIds"=>array("city","int","",""),"tschoolNetwork"=>array("cityId","int","",""),*/
	/*"topSearches"=>array("location","varchar","",""),*/
	/*"tmp_insti_insti_table"=>array("city_id","int","city_name","varchar"),*/"tlmsProfile"=>array("city","varchar","",""),"categoryPageData"=>array("city_id","int","",""),
	"tlistingsubscription"=>array("cityid","int","",""),"tLeadKeyValue"=>array("cityid","int","",""),
	"tLeadInfo"=>array("residencelocation","int","",""),/*"tcollegeNetwork"=>array("cityId","int","",""),*/
	"tbannerlinks"=>array("cityid","int","",""),/*"scholarship"=>array("city_id","int","",""),*/
	"SAPreferedLocationSearchCriteria"=>array("city","int","",""),/*"reviewcollege"=>array("city","varchar","",""),*/
	"other_exam_centres_table"=>array("city_id","int",""),/*"otherCourses"=>array("city_name","varchar","",""),*/
	/*"marketingUserKey"=>array("residenceLocation","varchar","residenceLocationName","varchar"),*/"localityCityMapping"=>array("cityId","int","",""),
	"institute_location_table"=>array("city_id","int","city_name","varchar"),/*"fairStudentTable"=>array("city","varchar","",""),*/
	/*"fairClientTable"=>array("city","varchar","",""),*/"event_venue_mapping"=>array("venue_id","int","",""),
	"event_venue"=>array("city","int","",""),"event_subscription"=>array("city_id","int","",""),
	"event"=>array("venue_id","int","",""),"course_location_attribute"=>array("location_id","int","",""),
	"categoryselector"=>array("cityid","int","",""),/*"alertEventTable"=>array("city","varchar","",""),*/
	/*"admission_notification_exam_centres_table"=>array("city_id","int","",""),*//*"tSaveSearch"=>array("location","varchar","",""),*/
	/*"tvc_table"=>array("ucity","varchar","",""),*//*"Payee_Address_Details"=>array("City","varchar","",""),
	"Payment_Details"=>array("Cheque_City","varchar","",""),"Payment_Logs"=>array("Cheque_City","varchar","",""),*/
	"tPageKeyCriteriaMapping"=>array("cityId","int","",""),/*"filterValueTable"=>array("filterValueName","varchar","query","varchar"),*/"tmp_insti_insti_table"=>array("city_id","int","city_name","varchar")
	);
	//Refering tables mapping
	// "virtualCityMapping"=>array("city_id","virtualCityId"), no need to make check for this
	public $refering_tables_mapping_for_cities_to_be_deleted = array("tuser"=>array("city",""),"tlmsProfile"=>array("city",""),
	"marketingUserKey"=>array("residenceLocation","residenceLocationName"),"institute_location_table"=>array("city_id","city_name"),
	"event_venue_mapping"=>array("venue_id",""),"event"=>array("venue_id",""),"tSaveSearch"=>array("location",""),"tvc_table"=>array("ucity",""),
	"Payee_Address_Details"=>array("City",""),"Payment_Details"=>array("Cheque_City",""),"Payment_Logs"=>array("Cheque_City",""),
	"tUserLocationPref"=>array("CityId",""),"tUserEducation"=>array("City",""),"tmp_insti_insti_table"=>array("city_id","int","city_name","varchar"),
	"tcollegeNetwork"=>array("cityId",""),"event_venue"=>array("city",""),"tschoolNetwork"=>array("cityId",""),
	"tLeadInfo"=>array("residencelocation",""),"other_exam_centres_table"=>array("city_id",""),
	"scholarship"=>array("city_id",""),"tLeadKeyValue"=>array("cityid",""),"tPageKeyCriteriaMapping"=>array("cityId","")
	);
	//mapping of tables where stateid needs to be updates
	public $refering_tabes_mapping_for_state = array("tUserLocationPref"=>array("StateId","CityId"),"SAPreferedLocationSearchCriteria"=>
	array("state","city"));
	// return connection object
	/*	
	protected function getDataBaseConnection() {

		/*
		$this->load->library('userconfig');
		$obj = new userconfig();
		$dbConfig = array( 'hostname'=>'localhost');
		$obj->getDbConfig("1",$dbConfig);
		$dbHandle = $this->load->database($dbConfig,TRUE);
		if($dbHandle == ''){
			log_message('error','updateUser can not create db handle');
		}
		return $dbHandle;		
	}
	*/
	
}
?>
