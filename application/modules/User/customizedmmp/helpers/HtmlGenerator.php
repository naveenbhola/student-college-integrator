<?php

class ValueGenerator{

	public function getDataForView($data,$groupCourses,$groupId,$courseId,$field,$pageType){
		$returnArray = array();
		$arrType = array();
		$arrVisible = array();
		$arrValues = array();
		$counter = 0;
		foreach($data as $k=>$v){
			
			if($v['id'] == 'exams' && !$v['values']) {
				continue;
			}
			
			if($v['id'] == 'desiredCourse'){
				if($groupId>0){
					$tempArr = array();
			            foreach($groupCourses as $k1=>$v1){
							if($v1['groupid'] == $groupId){
								$tempArr[] = $v1['courseid']."=".str_replace("'","",$v1['coursename']);	
							}
						}
					$arrValues[$counter] = "'".join("|",$tempArr)."'";
                }
				elseif($courseId>0){
					$tempArr = array();					
                            foreach($groupCourses as $k1=>$v1){
								if($v1['courseid'] == $courseId){
									$tempArr[] = $v1['courseid']."=".str_replace("'","",$v1['coursename']);									
								}
                            }                     
                     $arrValues[$counter] = "'".join("|",$tempArr)."'";
				}
				else{
					$arrValues[$counter] = '\'\'';
				}
			}

			elseif($v['id'] == 'preferredStudyLocality'){
				$temp = array();
				$temp2 = array();
				$temp3 = array();
				$citiesArr = array();
				foreach($v['values'] as $key=>$val){
					if ($key == "metroCities"){
						foreach($val as $k1=>$v1){
							$temp['metro'][] = 'C:'.$v1['cityId']."=".$v1['cityName'];
							$citiesArr[$v1['cityId']] = $v1['cityName'];
							$temp2[$v1['cityId']]['localities'] = $field->getValues(array('cityId'  => $v1['cityId']));
                            $temp2[$v2['CityId']]['name'] = $v2['CityName'];
						}
					}
					if($key == 'virtualCities'){
						foreach($val as $k1=>$v1){
							$temp['metro'][] = 'C:'.$k1."=".$v1['name'];
							foreach($v1['cities'] as $k2=>$v2){
								$temp['cities'][] = 'C:'.$v2['city_id']."=".str_replace("'","",$v2['city_name']);
								$citiesArr[$v2['city_id']] = $v2['city_name'];
								$temp2[$v2['city_id']]['localities'] = $field->getValues(array('cityId'  => $v2['city_id']));
                                $temp2[$v2['CityId']]['name'] = $v2['city_name'];
							}
						}	
					}
					if( $key == 'stateCities'){
						foreach($val as $k1=>$v1){
							$temp['states'][] = 'S:'.$v1['StateId']."=".$v1['StateName'];
							foreach($v1['cityMap'] as $k2=>$v2){
								$temp['cities'][] = 'C:'.$v2['CityId']."=".str_replace("'","",$v2['CityName']);
								$citiesArr[$v2['CityId']] = $v2['CityName'];
								$temp2[$v2['CityId']]['localities'] = $field->getValues(array('cityId'  => $v2['CityId']));
                                $temp2[$v2['CityId']]['name'] = $v2['CityName'];
							}
						}
					}
				}
				foreach($temp2 as $key=>$value){
                    if(count($value['localities']) > 0){
                        $temp3[] = "optgroup=".$value['name'];
                        foreach($value['localities'] as $key1=>$val1){
                            if (count($val1)>0){
                                foreach($val1 as $k1=>$v1){
                                    $temp3[] = $key."+".$k1."=".str_replace("'","",$v1['localityName']);
                                }
                            }
                        }
                    }
                    else{
    					$temp3[] = $key.'+*'."=".$citiesArr[$key];
                    }        
				}

				$prefLocal = join(",",$temp3);
				$prefNational = "**=METRO,";
				$prefNational .=  join(",",$temp['metro']);
				$prefNational .=",**=STATES,";
				$prefNational .= join(",",$temp['states']);
				$prefNational .=",**=CITIES,";
				$prefNational .= join(",",$temp['cities']);
				$arrValues[$counter] = "''";
			}

			/*elseif($v['id'] == 'residenceCity'){
				$tempArr = array();
                foreach($v['values'] as $k1=>$v1){
					if($k1 == 'tier1Cities'){
						foreach($v1 as $k2=>$v2){
							$tempArr[] = $v2['cityId']."=".$v2['cityName'];
						}
					}
					if($k1 == 'citiesByStates'){
                                                foreach($v1 as $k2=>$v2){
							$tempArr[] = "optgroup=".$v2['StateName'];
							foreach($v2['cityMap'] as $k3=>$v3){
								$tempArr[] = $v3['CityId']."=".$v3['CityName'];
							}
						}
					}
                                }
				$arrValues[$counter] = "'".join("|",$tempArr)."'";
			}*/ elseif($v['id'] == 'residenceCityLocality' || $v['id'] == 'residenceCity'){
				//echo "====================================".$pageType."__________".$v['id'];
				//var_dump($v['values']);
				if($pageType == 'abroadpage' && $v['id'] == 'residenceCity') {
					//_P($v['values']);
					$tempArr = array();
					foreach($v['values'] as $k1=>$v1){
					if($k1 == 'tier1Cities'){
						foreach($v1 as $k2=>$v2){
							$tempArr[] = $v2['cityId']."=".$v2['cityName'];
						}
					}
					if($k1 == 'citiesByStates'){
                                                foreach($v1 as $k2=>$v2){
							$tempArr[] = "optgroup=".$v2['StateName'];
							foreach($v2['cityMap'] as $k3=>$v3){
								$tempArr[] = $v3['CityId']."=".$v3['CityName'];
							}
						}
					}
                                }
				$arrValues[$counter] = "'".join("|",$tempArr)."'";
					
				} else {
				$tempArr = array();
                foreach($v['values'] as $k1=>$v1){
					if($k1 == 'virtualCities'){						
						foreach($v1 as $k2=>$v2){
							$tempArr[] = "optgroup=".$v2['name'];
							foreach($v2['cities'] as $k3=>$v3){
								if($v3['city_id'] == 10224) {
										continue;
								}
								$tempArr[] = $v3['city_id']."=".$v3['city_name'];
							}
							
						}
					}
					 
					if($k1 == 'metroCities') {	
						$tempArr[] = "optgroup=Metro Cities";
						foreach($v1 as $k2=>$v2){
							$tempArr[] = $v2['cityId']."=".$v2['cityName'];
						}
					}
					
					if($k1 == 'stateCities'){
                            foreach($v1 as $k2=>$v2){
							$tempArr[] = "optgroup=".$v2['StateName'];
							foreach($v2['cityMap'] as $k3=>$v3){
								$tempArr[] = $v3['CityId']."=".$v3['CityName'];
							}
						}
					}
                                }
				$arrValues[$counter] = "'".join("|",$tempArr)."'";	
			}
					
			}else if($v['id'] == 'abroadSpecialization') {
				
				$tempArr = array();
				foreach($v['values'] as $k1=>$v1){
					$tempArr[] = "optgroup=".$v1['name'];
                                        foreach($v1['subcategory'] as $k2=>$v2) {
						$tempArr[] = $v2['id']."=".$v2['name'];
					}
				}
				$arrValues[$counter] = "'".join("|",$tempArr)."'";
			}

                        elseif($v['id'] == 'destinationCountry'){
                                $tempArr = array();
                                foreach($v['values'] as $k1=>$v1){
                                        if($k1 == 'countries'){
                                                foreach($v1 as $k2=>$v2){
							foreach($v2 as $k3=>$v3)
	                                                        $tempArr[] = $v3->getId()."=".$v3->getName();
                                                }
                                        }
                                }
                                $arrValues[$counter] = "'".join("|",$tempArr)."'";
                                $arrType[$counter] = "'select'";
                                $arrVisible[$counter] = "'".$v['isVisible']."'";
                                $arrLabel[$counter] = "'".$v['label']."'";
                                $arrMandatory[$counter] = "'".$v['isMandatory']."'";
                                $arrPreSelected[$counter] = "'".$v['preselected']."'";
                                $arrId[$counter] = "'".$v['id']."'";
                                $arrIsCustom[$counter] = "'no'";
                                $counter++;
				continue;

                        }
			else if($v['id'] == 'exams') {
				$tmpArr = array();
				foreach($v['values'] as $examKey => $examValue) {
					$tmpArr[] = $examKey."=".$examValue;
				}
				$arrValues[$counter] = "'".join("|",$tmpArr)."'";
			}

			elseif($v['isCustom']){
				$arrValues[$counter] = "'".$v['values']."'";
			}

			else{
				if($v['type'] == 'checkbox' && $v['id'] != "mode" && $v['id'] != "degreePreference"  && $v['id'] != "fund"){
					$tempArr = array();
					foreach($v['values'] as $k1=>$v1){
						$tempArr[$k1] = $v1;
					}
					$arrValues[$counter] = "'".join("|",$tempArr)."'";
				}
                	        if($v['type'] == 'radio'){
					$tempArr = array();
	                                foreach($v['values'] as $k1=>$v1){
						$tempArr[$k1] = $v1;
                        	        }
					$arrValues[$counter] ="'".join("|",$tempArr)."'";
        	                }
	                        if($v['type'] == 'select' || $v['id'] == "mode" || $v['id'] == "degreePreference" || $v['id'] == "fund"){
					$tempArr = array();
                	                foreach($v['values'] as $k1=>$v1){
						$tempArr[] = $k1."=".str_replace("'","",$v1);
	                                }
					$arrValues[$counter] ="'".join("|",$tempArr)."'";
                        	}
                	        if($v['type'] == 'textbox'){
					$arrValues[$counter] = "''";
	                        }

			}
			if($v['type'] == 'textbox' || $v['type'] == 'select' || $v['type'] == 'radio' || $v['type'] == 'checkbox' || $v['isCustom']){
				$arrType[$counter] = "'".$v['type']."'";
				$arrVisible[$counter] = "'".$v['isVisible']."'";
				$arrLabel[$counter] = "'".$v['label']."'";
				$arrMandatory[$counter] = "'".$v['isMandatory']."'";
				$arrPreSelected[$counter] = "'".$v['preselected']."'";
				$arrId[$counter] = "'".$v['id']."'";
				if($v['isCustom'])
					$arrIsCustom[$counter] = "'yes'";
				else
					$arrIsCustom[$counter] = "'no'";
				$counter++;
			}

	
		}
		$returnArray['count'] = count($arrType);
		$returnArray['type'] = join(",",$arrType);
		$returnArray['visible'] = join(",",$arrVisible);
		$returnArray['values'] = join(",",$arrValues);
		$returnArray['label'] = join(",",$arrLabel);
		$returnArray['mandatory'] = join(",",$arrMandatory);
		$returnArray['preselected'] = join(",",$arrPreSelected);
		$returnArray['isCustom'] = join(",",$arrIsCustom);
		$returnArray['prefLocal'] = $prefLocal;
		$returnArray['prefNational'] = $prefNational;
		$returnArray['id'] = join(",",$arrId);
		return $returnArray;
	}
}
