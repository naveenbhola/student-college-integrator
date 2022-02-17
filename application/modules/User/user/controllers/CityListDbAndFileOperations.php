<?php
class CityListDbAndFileOperations extends CityListCleanUpMain {
	
	/**
	 * Insert new cities to db
	 *
	 * @param array $listOfCitiesToBeadded List of cities to be added
	 */
	public function inserNewCitiesToDB($listOfCitiesToBeadded) {
		//code not in use
		$this->benchmark->mark('code_start');
		error_log("STARTED METHOD ########################### inserNewCitiesToDB");
		$connection_obj = $this->getDataBaseConnection();
		if(!$connection_obj) {
			throw new CityListException("",600);
		}
		try {
			$insert_state_query = "insert IGNORE into stateTable (state_id,state_name,countryId) values('','Chandigarh',2)";
			$insert_state_query1 = "insert IGNORE into stateTable (state_id,state_name,countryId) values('','Daman & Diu',2)";
			$insert_state_query2 = "insert IGNORE into stateTable (state_id,state_name,countryId) values('','Dadra And Nagar Haveli',2)";
			$query_state = $connection_obj->query($insert_state_query);
			$query_state1 = $connection_obj->query($insert_state_query1);
			$query_state2 = $connection_obj->query($insert_state_query2);
                        /* Drop triger on virtual city mapping starts here*/
                       /* $triger_drop_query[] = "DROP TRIGGER IF EXISTS shiksha.virtualCity";
                          $triger_drop_query[] = "DROP TRIGGER IF EXISTS shiksha.virtualCity_delete";
                        foreach($triger_drop_query as $drop_query) {
                                error_log("drop trigger query".$drop_query);
				$connection_obj->query($drop_query);
			}*/
                        /* Drop triger on virtual city mapping ends here*/
			foreach ($listOfCitiesToBeadded as $key=>$value) {
				$insert_query = "insert IGNORE into countryCityTable values ('','".$key."',".$value['countryId'].",". $value['creationDate'].",".$value['enabled'].",'".$value['notes']."',". $value['state_id'].",".$value['tier'].")";
				error_log("insert citylist query".$insert_query);
				$query = $connection_obj->query($insert_query);
                                /*select the max id from country city table and inser it into virtual city mapping*/
                                $select_query = "select max(city_id) as maxid from countryCityTable where countryId=2";
                                $query = $connection_obj->query($select_query);
                                $results = $query->result();
				$current_city_id = $results[0]->maxid;
                                /*insert into virtual city table*/
                                $virtual_city_insert_query = "insert IGNORE into virtualCityMapping values ($current_city_id, $current_city_id)";
                                $query = $connection_obj->query($virtual_city_insert_query);
                                error_log("insert virtualcity query".$virtual_city_insert_query);
				error_log("db operation result citylist".print_r($query,true));
			}
		} catch (CityListException $e) {
			throw new CityListException("", 1000);
		}
		error_log("ENDED METHOD ########################### inserNewCitiesToDB");
		$this->benchmark->mark('code_end');
		error_log("total time taken to run inserNewCitiesToDB():::".$this->benchmark->elapsed_time('code_start', 'code_end')."seconds");
	}
	/**
	 * Delete unwanted cities from db, this method was written for analysis purpose
	 *
	 * @param array $listOfCitiesToBedeleted List of cities to be deleted
	 */
	public function deleteUnwantedCitiesFromDB($listOfCitiesToBedeleted) {
		// code not in use
		$this->benchmark->mark('code_start');
		error_log("STARTED METHOD ########################### deleteUnwantedCitiesFromDB");
		$refering_tables = $this->refering_tables_mapping_for_cities;
		$connection_obj = $this->getDataBaseConnection();
		if(!$connection_obj) {
			throw new CityListException("",600);
		}
		$city_refernce_array = array();
		foreach ($listOfCitiesToBedeleted as $city_id=>$city_name) {
			$flag = true;
			foreach ($refering_tables as $table_name=>$column_array) {
				if(empty($column_array[1])) {
					if(!empty($city_name)) {
						$select_query = "select count(*) as count from $table_name where $column_array[0]!='0' AND ($column_array[0] ='$city_id' OR $column_array[0]='$city_name')";
					} else {
						$select_query = "select count(*) as count from $table_name where $column_array[0]!='0' AND $column_array[0] ='$city_id'";
					}
				} else {
					if(!empty($city_name)) {
						$select_query = "select count(*) as count from $table_name where ($column_array[0]!='0' AND ($column_array[0] ='$city_id' or $column_array[0] ='$city_name')) OR ($column_array[1]!='0' AND ($column_array[1] ='$city_id' or $column_array[1] ='$city_name'))";
					} else {
						$select_query = "select count(*) as count from $table_name where ($column_array[0]!='0' AND $column_array[0] ='$city_id') OR ($column_array[1]!='0' AND $column_array[1] ='$city_id')";
					}
				}
				error_log("checking for deletion for city_id_".$city_id."city name__".$city_name);
				error_log("checking for deletion query:::".$select_query);
				if($table_name!="Payee_Address_Details" && $table_name!="Payment_Details" && $table_name!="Payment_Logs") {
					$query = $connection_obj->query($select_query);
				} else {
					$this->load->database('sums');
					$query = $this->db->query($select_query);
				}
				$count = $query->result();
				$count = $count[0]->count;
				error_log('count is'.$count);
				if($flag == true && $count>0) {
					$city_refernce_array[$city_id] = $city_name;
					$flag = false;
					break;
				}
			}
		}
		error_log("ENDED METHOD ########################### deleteUnwantedCitiesFromDB");
		$this->benchmark->mark('code_end');
		error_log("total time taken to run deleteUnwantedCitiesFromDB():::".$this->benchmark->elapsed_time('code_start', 'code_end')."seconds");
	}
	
	/**
	 * check for the reference in foreign tables
	 *
	 * @param array $refering_tables foreign tables to check in for deleted cities
	 * @param array $deleted_list_mapping_array list of cities deleted
	 */
	public function checkReferenceInForeignTablesForDeletedcities($refering_tables,$deleted_list_mapping_array) {
		//code not in use
		$this->benchmark->mark('code_start');
		error_log("STARTED METHOD ########################### checkReferenceInForeignTablesForDeletedcities");
		$connection_obj = $this->getDataBaseConnection();
		if(!$connection_obj) {
			throw new CityListException("",600);
		}
		// get the mapping of cities
		$oldcityNewCityMapping = $deleted_list_mapping_array;
		$update_query_array1 = array();
		try{
			// start matching of every city in all the refering tables, every city is matched with all the refering tables
			foreach ($oldcityNewCityMapping as $key=>$value) {
				$value2= trim($value[2]);
				foreach ($refering_tables as $table_name=>$table_colmun) {
					$this->benchmark->mark('code_start1');
					// if we need to match more than one column of the table
					if(empty($table_colmun[2])) {
						if(!empty($value2)){
							// if we need to match for city name and id bothe
							if(!empty($key)) {
								if($table_colmun[1] == 'varchar') {
									$update_query_array1[] = "update IGNORE $table_name set $table_colmun[0]= '$value[1]' where $table_colmun[0]!='0' AND $table_colmun[0]='$key'";
								}
							}
							if($table_colmun[1] == 'int' || $table_colmun[1] == 'varchar') {
								$update_query_array1[] = "update IGNORE $table_name set $table_colmun[0]= '$value[2]' where $table_colmun[0]!='0' AND $table_colmun[0]='$value[0]'";
							}
						} else {
							// if we need to match for city name/id
							if(!empty($key)) {
								if($table_colmun[1] == 'varchar') {
									$update_query_array1[] = "update IGNORE $table_name set $table_colmun[0]= '$value[1]' where $table_colmun[0]!='0' AND $table_colmun[0]='$key'";
								}
							}
							error_log("update query to check refrence".$update_query);
						}
					} else {
						if(!empty($value2)){
							// if we need to match for city name and id both
							if(!empty($key)){
								if($table_colmun[1] == 'varchar') {
									$update_query_array1[] = "update IGNORE $table_name set $table_colmun[0]= '$value[1]' where $table_colmun[0]!='0' AND $table_colmun[0]='$key'";
								}
								if($table_colmun[3] == 'varchar') {
									$update_query_array1[] = "update IGNORE $table_name set $table_colmun[2]= '$value[1]' where $table_colmun[2]!='0' AND $table_colmun[2]='$key'";
								}
							}
							if($table_colmun[1] == 'int' || $table_colmun[1] == 'varchar') {
								$update_query_array1[] = "update IGNORE $table_name set $table_colmun[0]= '$value[2]' where $table_colmun[0]!='0' AND $table_colmun[0]='$value[0]'";
							}
							if($table_colmun[3] == 'int' || $table_colmun[3] == 'varchar') {
								$update_query_array1[] = "update IGNORE $table_name set $table_colmun[2]= '$value[2]' where $table_colmun[2]!='0' AND $table_colmun[2]='$value[0]'";
							}
						} else {
							// if we need to match for city name/id
							if(!empty($key)){
								if($table_colmun[1] == 'varchar') {
									$update_query_array1[] = "update IGNORE $table_name set $table_colmun[0]= '$value[1]' where $table_colmun[0]!='0' AND $table_colmun[0]='$key'";
								}
								if($table_colmun[3] == 'varchar') {
									$update_query_array1[] = "update IGNORE $table_name set $table_colmun[2]= '$value[1]' where $table_colmun[2]!='0' AND $table_colmun[2]='$key'";
								}
							}
						}

					}
					// check whether table is a part of sums data base or shiksha, this is for shiksha
					if(is_array($update_query_array1) && !empty($update_query_array1[0]) && $table_name!="filterValueTable") {
					foreach ($update_query_array1 as $update_query) {
						error_log("query#: ".$update_query);
						$query = $connection_obj->query($update_query);
						error_log("affected rows for shiksha table $table_name.'____'.$key:::".$connection_obj->affected_rows());
					}
						} else {
						if(is_array($update_query_array1) && !empty($update_query_array1[0])) {
						// check whether table is a part of sums data base or shiksha, this is for sums
						$dbHandleMailer = $this->getDataBaseConnectionForMailer();
						//if(empty($value2)) {
						foreach ($update_query_array1 as $update_query) {
						$update_query = $update_query." AND filterId=1";
						error_log("query_mailer#: ".$update_query);
						$query = $dbHandleMailer->query($update_query);
						error_log("affected rows for mailer table $table_name.'____'.$key:::".$dbHandleMailer->affected_rows());
						}
						/*} else {
							$delete_from_mailer = "delete from $table_name where filterId=1 and query=$value[0]";
							error_log("query_mailer#: ".$delete_from_mailer);
							$query_mailer = $dbHandleMailer->query($delete_from_mailer);
							error_log("affected rows for mailer table $table_name.'____'.$key:::".$dbHandleMailer->affected_rows());
						}*/
						}
						}
					unset($update_query_array1);
					$this->benchmark->mark('code_start2');
					error_log("total time taken to run for table $table_name:::".$this->benchmark->elapsed_time('code_start1', 'code_start2')."seconds");
				}
				// Finally update parent table after updating every other refering tables
				if(!empty($value2)){
					$query = "delete from countryCityTable where city_id = $value[0]";
                                        $virtual_city_delete_query = "delete from virtualCityMapping where virtualCityId = $value[0] or city_id = $value[0]";
                                        error_log("virtual city delete query is".$virtual_city_delete_query);
                                        $query_virtual = $connection_obj->query($virtual_city_delete_query);
				} else {
					$query = "update countryCityTable set city_name ='$value[1]' where city_id=$value[0]";
				}
				error_log("parent table query is".$query);
				$query = $connection_obj->query($query);
			}
		} catch (CityListException $e) {
			throw new CityListException("", 1100);
		}
		error_log("ENDED METHOD ########################### checkReferenceInForeignTablesForDeletedcities");
		$this->benchmark->mark('code_end');
		error_log("total time taken to run checkReferenceInForeignTablesForDeletedcities():::".$this->benchmark->elapsed_time('code_start', 'code_end')."seconds");
	}
	/**
	 * updates cities in main city list
	 */
	public function updateCity() {
		// code not in use
		$this->benchmark->mark('code_start');
		error_log("STARTED METHOD ########################### updateCity");
		$connection_obj = $this->getDataBaseConnection();
		if(!$connection_obj) {
			throw new CityListException("",600);
		}
		$mapping_array = $this->refering_tabes_mapping_for_state;
		$list_to_be_updated = $this->city_to_updated;
		//update data in referring table
		try {
			foreach ($list_to_be_updated as $key=>$value) {
				error_log(print_r($value,true));
				foreach ($mapping_array as $table_name=>$column_value) {
					$queryCmd = "update IGNORE $table_name set $column_value[0]= $value[1] where $column_value[1]= $value[0]";
					error_log("update city query is".$queryCmd);
					$query = $connection_obj->query($queryCmd);
				}
				// update city list main table
				$query_to_update_citylist_main_table= "update IGNORE countryCityTable set state_id=$value[1], tier=$value[2], enabled=$value[3] where city_id=$value[0]";
				error_log("main query to update countrycitytable".$query_to_update_citylist_main_table."for city".$key);
				$query = $connection_obj->query($query_to_update_citylist_main_table);
			}
			$delete_state = "delete IGNORE from stateTable where state_id= 125";
			$query = $connection_obj->query($delete_state);
		} catch (CityListException $e) {
			throw new CityListException("", 900);
		}
		error_log("ENDED METHOD ########################### updateCity");
		$this->benchmark->mark('code_end');
		error_log("total time taken to run updateCity():::".$this->benchmark->elapsed_time('code_start', 'code_end')."seconds");
	}
	
	/**
	 * write new list in js files
	 * @param array $file_name_array names of files
	 * @param array $data_array cities data
	 */
	public function writeCitiesDataInJSFiles($file_name_array,$data_array) {
		$data_array = json_encode($data_array);
		$base_path = "/var/www/html/shiksha/public/js/";
		try {
			if(!empty($file_name_array) && is_array($file_name_array)) {
				foreach ($file_name_array as $file_name) {
					$file_name = $base_path.$file_name;
					// read the file
					$file_contents = file_get_contents($file_name);
					$file_contents = preg_replace("/\"2\":\{.*\},\"3\"/","\"2\":".$data_array.",\"3\"",$file_contents,1,$count);
					// write the file
					file_put_contents($file_name, $file_contents);

				}
			}
		} catch (Exception $e) {
			throw new CityListException("", 1300);
		}
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
	 * Connect the database manually
	 *
	 * @return object Database
	 */
	public function getDataBaseConnectionForMailer()
	{
		$this->load->library('mailerconfig');
		$obj = new Mailerconfig();
		$dbConfig = array( 'hostname'=>'localhost');
		$obj->getDbConfig("1",$dbConfig);
		$dbHandle = $this->load->database($dbConfig,TRUE);
		if($dbHandle == ''){
			error_log('error','can not create db handle');
		}
		return $dbHandle;
	}
}
?>
