<?php
/**
 * Class CityListCleanUp for managing cities data
 */
//Include the parent class
require_once 'CityListCleanUpMain.php';
require_once 'CityListException.php';
require_once 'CityListDbAndFileOperations.php';

/**
 * Class CityListCleanUp for managing cities data
 */
class CityListCleanUp extends CityListCleanUpMain{
	
	/**
	 *  Get the final list of cities from xl
	 *
	 *  @param string $file_name - File name to read list from
	 *  @return $cities_list - List of cities
	 */
	public function readFinalListOfCitiesFromXL($file_name) {
		error_reporting(E_ERROR);
		$cities_list = array();
		if(!empty($file_name)) {
			$file_extension_array = explode(".", basename($file_name));
			$file_extension = $file_extension_array[1];
			$file_pointer = fopen($file_name, "r",true);
			if(empty($file_pointer)) {
				throw new CityListException("",500);
			}
			while (!feof($file_pointer)) {
				if(strtoupper($file_extension) == 'CSV') {
					$csv_array = fgetcsv($file_pointer);
					$count_csv = count($csv_array);
					if(is_array($csv_array) && !empty($csv_array[0]) && $count_csv == 1) {
						$cities_list[] = trim($csv_array[0]);
					} else if(is_array($csv_array) && !empty($csv_array[0]) && $count_csv >1) {
						for ($i=0;$i<$count_csv;$i++) {
							if($csv_array[1] != 'City')
							$cities_list[trim($csv_array[1])] = array(trim(trim($csv_array[0],"="),"[,],' '"),trim($csv_array[2]));
						}
					}
				} else {
					$cities_list[] = fgets($file_pointer);
				}
			}
			fclose($file_pointer);
		} else  {
			throw new CityListException("",400);
		}
		return $cities_list;
	}
	
	/**
	 * Connect the database manually
	 *
	 * @return object Database
	 */
	public function getDataBaseConnection()
	{
		return $this->load->database('default',TRUE);
	}
	
	/**
	 * Get the current list of cities fron the database
	 *
	 * @return array $city_array list of cities
	 */
	public function readExistingListOfCitiesFromDatabase() {
		$city_array = array();
		$connection_obj = $this->getDataBaseConnection();
		if(!$connection_obj) {
			throw new CityListException("",600);
		}
		$queryCmd = "select city_id,city_name from countryCityTable where countryId =2 and state_id!=-1 order by city_name";
		error_log($queryCmd.'query to get all the cities');
		$query = $connection_obj->query($queryCmd);
		foreach($query->result_array() as $row) {
			$city_array[$row['city_id']] = $row['city_name'];
		}
		return $city_array;
	}
	
	/**
	 * Get the list of new cities to be added
	 *
	 * @param array $newList List of cities to be added(inclusive of already existing cities)
	 * @param array $oldList List of cities earlier present
	 * @return array $to_be_added_array List of cities to be added(excluding already existing cities)
	 */
	public function getListofCitiesTobeAddedinTheDB($newList,$oldList) {
		$to_be_added_array = array();
		if(is_array($newList) && is_array($oldList)) {
			$to_be_added_array = array_diff($newList, $oldList);
		} else {
			throw new CityListException("",700);
		}
		return $to_be_added_array;
	}
	
	/**
	 * Get the list of cities to be deleted
	 *
	 * @param array $newList List of cities to be added
	 * @param array $oldList List of cities earlier present
	 * @return array $to_be_deleted_array List of cities that are not in $newList but in $oldList and need to be deleted
	 */
	public function getListofCitiesTobeDeletedFromTheDB($newList,$oldList) {
		$to_be_deleted_array = array();
		if(is_array($newList) && is_array($oldList)) {
			$to_be_deleted_array = array_diff($newList, $oldList);
		} else {
			throw new CityListException("",800);
		}
		return $to_be_deleted_array;
	}
	
	/**
	 * Main method to execute
	 * @param $param
	 */
	public function index($param) {
		$this->benchmark->mark('code_start');
		error_log("STARTED METHOD ########################### index");
		$file_name = SHIKSHA_HOME."/public/ListOfCities.csv";
		$obj_citylist_file_operation = new CityListDbAndFileOperations();
		try {
			/* following codes were used for analysis
			 $csv_ciyt_list_array = $this->readFinalListOfCitiesFromXL($file_name);
			 $db_city_list_array = $this->readExistingListOfCitiesFromDatabase();
			 $list_to_be_added = $this->getListofCitiesTobeAddedinTheDB($csv_ciyt_list_array, $db_city_list_array);
			 $list_to_be_deleted = $this->getListofCitiesTobeDeletedFromTheDB($db_city_list_array, $csv_ciyt_list_array);
			 */
			//method to update cities states
			//$obj_citylist_file_operation->updateCity();
			//method to insert cities
			//$list_to_be_added = $this->list_to_be_added;
			//$obj_citylist_file_operation->inserNewCitiesToDB($list_to_be_added);
			//special case 
			//$deleted_list_mapping_array = $this->oldcityNewCityMappingSpecial;
			$refering_tables = $this->refering_tables_mapping_for_cities;
			//$obj_citylist_file_operation->checkReferenceInForeignTablesForDeletedcities($refering_tables,$deleted_list_mapping_array);
			// first run the methdod to update city name in the referring tables
			//$deleted_list_mapping_array = $this->oldcityNewCityMapping;
			$deleted_list_mapping_array = array("Cochin"=>array("66","Kochi","127"));
			$obj_citylist_file_operation->checkReferenceInForeignTablesForDeletedcities($refering_tables,$deleted_list_mapping_array);
			//method to get the deleted list mapping
			//$deleted_list_mapping_array = $this->getDeletedCitiesMapping();
			//$refering_tables = $this->refering_tables_mapping_for_cities_to_be_deleted;
			//check for references for deleted cities and delete it from main table
			//$obj_citylist_file_operation->checkReferenceInForeignTablesForDeletedcities($refering_tables,$deleted_list_mapping_array);
			//Product's again change request
			//$deleted_list_mapping_array = $this->oldcityNewCityMappingAgainRenamed;
			//$obj_citylist_file_operation->checkReferenceInForeignTablesForDeletedcities($refering_tables,$deleted_list_mapping_array);
			//write into js files
			/*$file_array = array('unRestrictedCityList.js','cityList.js');
			$db_city_list_array = $this->readExistingListOfCitiesFromDatabase();
			$obj_citylist_file_operation->writeCitiesDataInJSFiles($file_array,$db_city_list_array);*/
		} catch (CityListException $e){
			error_log("city list script exception_".$e->getExceptionMessage($e->getErrorcode()));
		}
		error_log("ENDED METHOD ########################### index");
		$this->benchmark->mark('code_end');
		error_log("total time taken to run index():::".$this->benchmark->elapsed_time('code_start', 'code_end')."seconds");
		register_shutdown_function("self::printErrorStack");
	}
	
	/**
	 * To generate mapping for the cities that we need to delete
	 *
	 * @return array $deleted_array_mapping
	 */
	public function getDeletedCitiesMapping() {
		$file_name = SHIKSHA_HOME."/public/deleted_cities_mapping.csv";
		$arry = $this->readFinalListOfCitiesFromXL($file_name);
		$connection_obj = $this->getDataBaseConnection();
		if(!$connection_obj) {
			throw new CityListException("",600);
		}
		$deleted_array_mapping = array();
		foreach ($arry as $key=>$value) {
			$value[1] = trim($value[1]);
			$queryCmd = "select city_id from countryCityTable where countryId =2 and city_name=?";
			error_log($queryCmd.'query to get city id for new mapping');
			$query = $connection_obj->query($queryCmd, array($value[1]));
			$city_id = $query->result();
			if(!array_key_exists("0", $city_id) || empty($city_id[0])) {
				continue;
				//throw new CityListException("",1200);
			}
			$city_id = $city_id[0]->city_id;
			$value[2] = $city_id;
			$deleted_array_mapping[$key] = $value;
		}
		return $deleted_array_mapping;
	}
	
	/**
	 * Final method that will be called
	 */
	public static function printErrorStack() {
		echo "Print erorr if any ++++++++++++++++++++STARTS<pre>";
		print_r(error_get_last());
		echo "Print erorr if any ++++++++++++++++++++ENDS</pre>";
	}
}
?>
