<?php
class TaggingCMSLib
{

	private $serverURL = '';
	private $subVal1 ;
	private $subVal2 ;
	private $subVal3 ;
	private $subVal4 ;
	private $subVal5 ;
	private $allLocationsArray;
	private $entityArray;
	private $actualTagsArray;


	/**
	* Constructor Function to get the CI Instance
	*/
	function __construct()
	{
		$this->CI = & get_instance();
		
	}

	/**
	* Init Function to initialize the Model Instance
	*/
	private function _init(){
		$this->CI->load->model('Tagging/taggingcmsmodel');
		$this->CI->load->config('TaggingConfig');
		$this->actualTagsArray = $this->CI->config->item('TAG_ENTITY_ACTUAL');

		$this->tagmodel = new TaggingCMSModel();	
		$this->subVal1 = array('Counselling',
								'Cut-off',
								'Eligibility',
								'GDPI',
								'Fees',
								'Placement',
								'Ranking',
								'Admission Process',
								'Affiliation',
								'Admission chances',
								'Reservation',
								'Scope',
								'Entrance Exam',
								'Preparation',
								'Governement College',
								'Private college');	

		$this->subVal2 = array('Counselling','Cut-off','Eligibility','GDPI','Dates','Pattern','Preparation','Process','Scores','Coaching Centres','colleges accepting');

		$this->subVal3 = array('Counselling','Cut-off','Eligibility','GDPI','Fees','Placement','Ranking','Admission Process','Affiliation','Admission chances','Reservation','Scope','Entrance Exam');

		$this->subVal4 = array('Scope',
							   'exams',
							   'Eligibility');	

		$this->subVal5 = array('Eligibility','Job Scenario','Exam','Admission Process','Post Study Work','Visa','Part time work','Immigration','Intake','Cost of living');			

		$this->allLocationsArray = array('India','Andaman Nicobar Islands','Andaman Nicobar Islands','Andhra Pradesh','Anantapur','	Andhra Pradesh - Other','Chittoor','East Godavari','Guntur','Kadapa','Kakinada','Krishna','Kurnool','Nellore','Prakasam','Rajahmundry','Tirupati','Vijayawada','Visakhapatnam','Vizianagaram','West Godavari','Arunachal Pradesh','Arunachal Pradesh - Other','Itanagar','Assam','Assam - Other','Dibrugarh','Dispur','Guwahati','Jorhat','Nagaon','North Lakhimpur','Silchar','Tezpur','Bihar','Arrah','Bhagalpur','Bihar - Other','Gaya','Hajipur','Muzaffarpur','Nalanda','Patna','Rajgir','Vaishali','Chandigarh','Chandigarh','Chhatisgarh','Chhattisgarh','Bhilai','BilasPur','Bilaspur(CG)','Chhattisgarh - Other','Chirimiri','Korba','Raigarh','Raipur','Dadra And Nagar Haveli','Dadra And Nagar Haveli - Other','Silvassa','Daman & Diu','Daman & Diu','Delhi','Delhi - Other','Goa','Canacona','Goa - Other','Mapusa','Margao','Old Goa','Panaji','Ponda','Vasco Da Gama','Gujarat','Adipur','Ahmedabad','Anand','Bharuch','Bhavnagar','Gandhidham','Gandhinagar','Gujarat - Other','Jamnagar','Kutch District','Navsari','Rajkot','Surat','Vadodara','Vapi','Haryana','Ambala','Bahadurgarh','Faridabad','Gurgaon','Haryana - Other','Hisar','Jhajjar','Kaithal','Karnal','Kurukshetra','Murthal','Panchkula','Panipat','Rewari','Rohtak','Sonepat','Yamuna Nagar','Himachal Pradesh','Dharamsala','Hamirpur','Himachal Pradesh - Other','Kullu','Manali','Mandi','Shimla','Sirmour','Solan','Una','Jammu and Kashmir','Gulmarg','Jammu','Jammu & Kashmir - Other','Ladakh','Leh','Pahalgam','Srinagar','Jharkhand','Bokaro Steel City','Dhanbad','Hazaribagh','Jamshedpur','Jharkhand - Other','Ramgarh','Ranchi','Karnataka','Bangalore','Belgaum','Bidar','Bijapur','Chikballpura','Chitradurga','Coorg','Davangere','Dharwad','Doballapura','Gulbarga','Hampi','Harihar','Hassan','Hoskote','Hubli','Karnataka - Other','Mangalore','Manipal','Mysore','Raichur','Shimoga','Tumkur','Udupi','Kerala','Alleppey','Amritapuri','Calicut','Ernakulum','Idukki','Kannur','Kasargode','Kerala - Other','Kochi','Kollam','Kottayam','Kovalam','Kozhikode','Kumarakom','Munnar','Palakkad','Pathanamthitta','Thekkady','Thiruvananthapuram','Thrissur','Trivandrum','Wayanad','Lakshadweep','Lakshadweep','Madhya Pradesh','Bhopal','Dewas','Gwalior','Indore','Jabalpur','Khajuraho','Madhya Pradesh - Other','Orchha','Sagar','Singrauli','Ujjain','Maharashtra','Ahmednagar','Akola','Amravati','Aurangabad','Baramati','Chandrapur','Dhule','Jalgaon','Kolhapur','Latur','Maharashtra - Other','Mumbai','Nagpur','Nanded','Nashik','Navi Mumbai','Parbhani','Pune','Raigad','Raigarh Pen','Ratnagiri','Sangli','Satara','Shirpur','Solapur','Thane','Ulhasnagar','Wardha','Yavatmal','Manipur','Imphal','Manipur - Other','Meghalaya','Meghalaya - Other','Shillong','Mizoram','Aizawl','Mizoram - Other','Nagaland','Kohima','Nagaland - Other','Orissa','Angul','Bhubaneswar','Brahmapur','Cuttack','Ganjam','Jajpur','Konark','Orissa - Other','Puri','Rourkela','Sambalpur','Pondicherry','Pondicherry','Punjab','Amritsar','Bathinda','Faridkot','Ferozpur','Hoshiarpur','Jalandhar','Ludhiana','Moga','Mohali','Muktsar','Nangal','Nawanshahar','Patiala','Phagwara','Punjab - Other','Ropar','Rajasthan','Ajmer','Alwar','Bharatpur','Bhilwara','Bhiwadi','Bikaner','Bundi','Jaipur','Jaisalmer','Jhunjhunu','Jodhpur','Kota','Neemrana','Pilani','Rajasthan - Other','Ranakpur','Shekhawati','Sikar','Sriganaganagar','Udaipur','Sikkim','Gangtok','Sikkim - Other','Tamil Nadu','Chennai','Coimbatore','Erode','Hosur','Kanchipuram','Kanyakumari','Karaikudi','Karur','Kodaikanal','Madurai','Nagercoil','Namakkal','Neyveli','Ooty','Rameshwaram','Salem','Tamil Nadu - Other','Thanjavur','Tiruchirappalli','Tirunelveli','Trichy','Vellore','Virudhunagar','Telangana','Hyderabad','Karimnagar','Khammam','Medak','Nalgonda','Nizamabad','Ranga Reddy','Secunderabad','Telangana-Other','Warangal','Tripura','Agartala','Tripura - Other','Uttar Pradesh','Agra','Aligarh','Allahabad','Baghpat','Bareilly','Bulandshahr','Dadri','Ghaziabad','Ghazipur','Gorakhpur','Greater Noida','Hapur','Jaunpur','Jhansi','Kanpur','Lucknow','Mathura','Meerut','Modinagar','Moradabad','Muzaffarnagar','Noida','Saharanpur','Uttar Pradesh - Other','Varanasi','Uttarakhand','Dehradun','Haldwani','Haridwar','Nainital','Rishikesh','Roorkee','Rudrapur','Uttarakhand - Other','Uttaranchal','West Bengal','Asansol','Bardhaman','Darjeeling','District 24 Parganas','Durgapur','Haldia','Howrah','Kalyani','Kharagpur','Kolkata','Malda','Midnapore','Murshidabad','Naihati','Serampore','Siliguri','West Bengal - Other','virtual','All over India','Delhi/NCR','Mumbai (All)','USA','UK','Australia','New Zealand','Canada','Italy','Singapore','Germany','Armenia','Austria','Bangladesh','Belgium','China','Estonia','Fiji','France','Greece','Netherlands','Hungary','Ireland','Japan','Lithuania','Luxembourg','Malaysia','Norway','Philippines','Poland','Portugal','Russia','Slovenia','South Africa','South Korea','Spain','Sweden','Switzerland','Thailand','UAE','Ukraine','Nepal','Monaco','Georgia','Hong Kong','Turkey','Cyprus','Denmark','Mauritius','Malta','Bahrain','Barbados','Guyana');

	//	$this->entityArray

		
	}

	public function showVarients($tagName,$tagEntity){
		$this->_init();
		$result = array();
		
		switch ($tagEntity) {

			case $this->actualTagsArray['Stream']:
			case $this->actualTagsArray['Substream']:
				$result = $this->showSubaVal1Varients($tagName);
				break;
			case $this->actualTagsArray['Specialization']:
				$result = $this->showSubaVal1Varients($tagName);
				break;
			case $this->actualTagsArray['Course']:
				$result = $this->showSubaVal1Varients($tagName,'Course');
				break;			
			case $this->actualTagsArray['Exams']:
				$result = $this->showSubaVal2Varients($tagName);
				break;
			case $this->actualTagsArray['Colleges']:
			case $this->actualTagsArray['National-University']:
				$result = $this->showSubaVal3Varients($tagName);
				break;
			case $this->actualTagsArray['University']:
				$result = $this->showSubaVal3Varients($tagName);
				break;
			case $this->actualTagsArray['College_Common']:
				$result = $this->showSubaVal3Varients($tagName);
				break;
			case $this->actualTagsArray['Careers']:
				$result = $this->showSubaVal4Varients($tagName);
				break;
			case $this->actualTagsArray['Country']:
				$result = $this->showSubaVal5Varients($tagName);
				break;	
			case $this->actualTagsArray['Mode']:
				// Find all the tags with entity as course, so passing the 
				$result = $this->showModesVarients($tagName,$this->actualTagsArray['Course']);
				break;
		}


		foreach ($result as $key => $row) {
		    $values[$key]  = $row['value'];
		}
		array_multisort($values, SORT_ASC, $result);
		
		return $result;
	}

	/**
	* Function to generate the Subval1 Varients of tags
	* @param string $tagName
	*/
	public function showSubaVal1Varients($tagName,$tagEntity = ''){

		$finalresult = array();
		$count = 0;
		
		foreach ($this->subVal1 as $value) {
			
			$result['key'] = $count++;
			$result['value'] = trim($tagName." ".$value);
			$finalresult[] = $result;
		}

		// One Extra Varient -- Not Covered in subval1 Array
		foreach ($this->allLocationsArray as $value) {
			
			$result['key'] = $count++;
			$result['value'] = trim($tagName." in ".$value);
			$finalresult[] = $result;
		}

		if($tagEntity == 'Course'){
			$modeArray = $this->CI->taggingcmsmodel->getTagsArray('Mode');
			foreach ($modeArray as $key => $value) {
				$result['key'] = $count++;
				$result['value'] = $value['tags']." ".$tagName;
				$finalresult[] = $result;		
			}
		}
		return $finalresult;
	}


	/**
	* Function to generate the Subval4 Varients of tags
	* @param string $tagName
	*/
	public function showSubaVal2Varients($tagName){

		$finalresult = array();
		$count = 0;
		
		foreach ($this->subVal2 as $value) {
			
			$result['key'] = $count++;
			if($value == "colleges accepting"){
				$result['value'] = trim($value." ".$tagName);
			}else{
				$result['value'] = trim($tagName." ".$value);	
			}

			$finalresult[] = $result;
		}
		return $finalresult;
	}

	/**
	* Function to generate the Subval4 Varients of tags
	* @param string $tagName
	*/
	public function showSubaVal3Varients($tagName){

		$finalresult = array();
		$count = 0;
		
		foreach ($this->subVal3 as $value) {
			
			$result['key'] = $count++;
			$result['value'] = trim($tagName." ".$value);
			$finalresult[] = $result;
		}
		return $finalresult;
	}

	/**
	* Function to generate the Subval4 Varients of tags
	* @param string $tagName
	*/
	public function showSubaVal4Varients($tagName){

		$finalresult = array();
		$count = 0;
		
		foreach ($this->subVal4 as $value) {
			
			$result['key'] = $count++;
			$result['value'] = trim($tagName." ".$value);
			$finalresult[] = $result;
		}

		// One Extra Varient -- Not Covered in subval4 Array
		$result['key'] = $count++;
		$result['value'] = "courses to become a ".$tagName;
		$finalresult[] = $result;
		return $finalresult;
	}

	/**
	* Function to generate the Subval4 Varients of tags
	* @param string $tagName
	*/
	public function showSubaVal5Varients($tagName){

		$finalresult = array();
		$count = 0;
		
		foreach ($this->subVal5 as $value) {
			
			$result['key'] = $count++;
			$result['value'] = trim($tagName." ".$value);
			$finalresult[] = $result;
		}
		return $finalresult;
	}

	/**
	* Function to generate the Subval4 Varients of tags
	* @param string $tagName
	* @param string $tag_entity(Course in this case, needed to create the varients)
	*/
	public function showModesVarients($tagName,$tag_entity){
		$finalresult = array();
		$courseArray = $this->CI->taggingcmsmodel->getTagsArray($tag_entity);
		foreach ($courseArray as $key => $value) {
			$count = 0;
			$result['key'] = $count++;
			$result['value'] = trim($tagName." ".$value['tags']);
			$finalresult[] = $result;
		}
		return $finalresult;

	}


	/**
	* Function to CREATE/UPDATE/DELETE Tags for the Added/Edited/Deleted Shiksha Entities AND corresponding Mappings
	* UPDATION - HERE REFERS TO RENAMING 
	* @param - $entityType - String - UPdated SHiksha Entity Type -  Valid Types (Stream,Substream,Specialization,institute,University,Base-course) - Case Sensitive
	* @param - $entityId - Integer - Updates Shiksha Entity Id - Integer Greater than zero
	* @param - $action  - String - Add / Edit/ Delete
	* @param - $extraData - JSON - Only for Edit In the FORMAT - {"previousName":"House Keeping"}
	*
	* @author - Mobile App Team
	*/

	public function addTagsPendingMappingAction($entityType='', $entityId = 0, $action='', $extraData=array()){
		$this->_init();
		$shikshaEntityToTagsEntity = $this->CI->config->item('SHIKSHA_ENTITY_TAG_ENTITY');
		$totalShikshaEntites = array_keys($shikshaEntityToTagsEntity);		
		$validActions = array("add","edit","delete");
		if(!in_array($entityType, $totalShikshaEntites)) {
		//	echo "Invalid Type";
			return;
		}
		if(!ctype_digit($entityId."") || $entityId <= 0 ) {
		//	echo "Invalid Id";
			return;
		}
		if(!in_array(strtolower($action), $validActions)){
		//	echo "Invalid Action";
			return;
		}
		
		if($action == "Edit"){
			if(!empty($extraData)){
				$isValid = $this->_isValidTagsCMSEditAction($entityType,$entityId,$extraData['newName']);
				if($isValid === false){
					return;	
				}	
			}
			 
		}
		
		$action = ucfirst(strtolower($action));

		$this->tagmodel->insertTagsPendingActions($entityType,$entityId,$action);
	}

	private function _isValidTagsCMSEditAction($entityType='',$entityId=0, $newName){
		
		if($entityType == "" || $entityId == 0){
			return array();
		}
		$inputArray[$entityType][] = $entityId;
		$entityData = $this->tagmodel->getEntityData($inputArray);
		if (isset($entityData[$entityType][$entityId]['name'])) {
			if(strtolower($newName) != strtolower($entityData[$entityType][$entityId]['name'])){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

}
?>
