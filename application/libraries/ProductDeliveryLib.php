<?php
class ProductDeliveryLib {
	
	private $CI;
	//public $numOfDaysInQuarter = array(88, 90, 91, 91, 89);
    //public $startDateOfQuarter = array('2013-01-01', '2013-04-01', '2013-07-01', '2013-10-01', '2014-01-01');
    //public $endDateOfQuarter   = array('2013-03-31', '2013-06-30', '2013-09-30', '2013-12-31', '2014-03-31');
	public $numOfDaysInQuarter = array(90, 90, 90, 90, 90, 90);
    public $startDateOfQuarter = array('2014-04-01', '2014-07-01', '2014-10-01', '2015-01-01', '2015-04-01', '2015-07-01');
    public $endDateOfQuarter   = array('2014-06-30', '2014-09-30', '2014-12-31', '2015-03-31', '2015-06-31', '2015-09-30');
	public $subcatId_subcatName_map;
	public $instituteId_clientId_map;
	public $course_institute_map;
	public $clientId_totalResponses_map;
	public $courseId_clientId_map;
	public $catId_catName_map;
	
	private $abroadMonthStarts = array('2014-06-01','2014-07-01','2014-08-01','2014-09-01','2014-10-01');
	private $abroadMonthEnds   = array('2014-06-30','2014-07-31','2014-08-31','2014-09-30','2014-10-31');
	private $abroadMonthHeadings = array('June 2014','July 2014','August 2014','September 2014','October 2014');
	
	function __construct() {
		$this->CI =& get_instance();
		
		$this->logFileName = 'log_product_delivery_details_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;
		
		//load model
		$this->productDeliveryModel = $this->CI->load->model('productdeliverymodel');
		
		//load phpexcel library
		$this->objPHPExcel = $this->CI->load->library('common/PHPExcel');
		
		$this->CI->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$this->locationRepository = $locationBuilder->getLocationRepository();
		
		//load libraries to send mail
        $this->alertClient = $this->CI->load->library('alerts_client');
	}
	
	function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
	
	public function getOurCustomQuarter($month, $year) {
        /*
         * year 2014, quarter 2 (Q1 2014-2015)    as----- quarter = 0
         * year 2014, quarter 3 (Q2 2014-2015)    as----- quarter = 1
         * year 2014, quarter 4 (Q3 2014-2015)    as----- quarter = 2
         * year 2015, quarter 1 (Q3 2015-2016)    as----- quarter = 3
         * year 2015, quarter 2 (Q3 2015-2016)    as----- quarter = 4
         * year 2015, quarter 3 (Q3 2015-2016)    as----- quarter = 5
         */
        
        $quarter = ceil($month/3);
        if($year == 2014 && $quarter == 2) {
            $quarter = 0;
        }
        else if($year == 2014 && $quarter == 3) {
            $quarter = 1;
        }
        else if($year == 2014 && $quarter == 4) {
            $quarter = 2;
        }
        else if($year == 2015 && $quarter == 1) {
            $quarter = 3;
        }
        else if($year == 2015 && $quarter == 2) {
            $quarter = 4;
        }
        else if($year == 2015 && $quarter == 3) {
            $quarter = 5;
        }
        else if($year < 2014 || ($year == 2014 && $quarter < 3)) {
            $quarter = -1; //started before 2014 quarter 3
        }
        else if($year > 2015 || ($year == 2015 && $quarter > 3)) {
            $quarter = 6; //ending in upcoming quarters of 2014
        }
        
        return $quarter;
    }
	
	public function getNumOfDaysRemainingInEachQuarter($strtDate, $endDate, $listingId) {
		$remainingDays = array();
		$listingsForThisQuarter = array();
		
		foreach($strtDate as $key=>$startDate) {
			$dateObj = DateTime::createFromFormat("Y-m-d", $startDate);
			$start_year    = $dateObj->format("Y");
			$start_month   = $dateObj->format("m");
			$start_day     = $dateObj->format("d");
			$start_quarter = $this->getOurCustomQuarter($start_month, $start_year);
			
			$dateObj = DateTime::createFromFormat("Y-m-d", $endDate[$key]);
			$end_year    = $dateObj->format("Y");
			$end_month   = $dateObj->format("m");
			$end_day     = $dateObj->format("d");
			$end_quarter = $this->getOurCustomQuarter($end_month, $end_year);
			
			if($start_quarter == $end_quarter) {
				for($i = 0; $i <= 5; $i++) {  //quarter number
					if(empty($remainingDays[$i])) {
						$remainingDays[$i] = 0;
					}
					if($i == $start_quarter) {
						if(abs(strtotime($endDate[$key]) - strtotime($startDate)) == 0) {
							$remainingDays[$i] += 1;
						} else {
							$remainingDays[$i] += (floor(abs(strtotime($endDate[$key]) - strtotime($startDate)) / 86400) + 1);
						}
						$listingsForThisQuarter[$i][] = $listingId[$key];
					}
					else {
						$remainingDays[$i] += 0;
					}
					if($remainingDays[$i] > 90) {
						$remainingDays[$i] = 90;
					} 
				}
			} else {
				for($start = -1; $start <= 5; $start++) {
					if($start == $start_quarter) {
						for($i = 0; $i <= 5; $i++) {  //quarter number
							if(empty($remainingDays[$i])) {
								$remainingDays[$i] = 0; 
							}
							if($i < $start) {
								$remainingDays[$i] += 0;
							}
							else if($i == $start) {
								if(abs(strtotime($startDate) - strtotime($this->endDateOfQuarter[$i])) == 0) {
									$remainingDays[$i] += 1;
								} else {
									$remainingDays[$i] += (floor(abs(strtotime($startDate) - strtotime($this->endDateOfQuarter[$i])) / 86400) + 1);
								}
								$listingsForThisQuarter[$i][] = $listingId[$key];
							}
							else if($i > $start) {
								if($i < $end_quarter) {
									$remainingDays[$i] += $this->numOfDaysInQuarter[$i];
									$listingsForThisQuarter[$i][] = $listingId[$key];
								}
								else if($i == $end_quarter) {
									if(abs(strtotime($this->startDateOfQuarter[$i]) - strtotime($endDate[$key])) == 0) {
										$remainingDays[$i] += 1;
									} else {
										$remainingDays[$i] += (floor(abs(strtotime($this->startDateOfQuarter[$i]) - strtotime($endDate[$key])) / 86400) + 1);
									}
									$listingsForThisQuarter[$i][] = $listingId[$key];
								}
								else if($i > $end_quarter){
									$remainingDays[$i] += 0;
								}
							}
							if($remainingDays[$i] > 90) {
								$remainingDays[$i] = 90;
							}
						}
					} else {
						continue;
					}
				}
			}
		}
		
		$result['subscriptionDays'] = $remainingDays;
		
		//number of courses for this subscription in this quarter
		foreach($listingsForThisQuarter as $quarter=>$listings) {
			$uniqueListingsForThisQuarter[$quarter] = array_unique($listings);
		}
		
		for($i = 0; $i <= 5; $i++) {
			if($uniqueListingsForThisQuarter[$i] && !empty($uniqueListingsForThisQuarter[$i])) {
				$result['numOfListings'][$i] = sizeof($uniqueListingsForThisQuarter[$i]);
			} else {
				$result['numOfListings'][$i] = 0;
			}
		}
		
        return $result;
    }

    public function getProductClientDetails() {
		error_log("Getting product delivery details.....\n", 3, $this->logFilePath);
		
		$categorySponsorDetails = array();
		$goldListingDetails = array();
		$mainListingDetails = array();
		
		//fetch all categories
		$categoryResult = $this->productDeliveryModel->getAllCategories();
		foreach($categoryResult->result() as $row) {
			$this->catId_catName_map[$row->category_id] = $row->category_name;
		}
		
		//get category sponsor details
		$categorySponsorDetails = $this->getCategorySponsorDetails();
		
		//get gold listings details
		//$goldListingDetails = $this->getGoldListingsDetails();
		
		//get main listings details
		//$mainListingDetails = $this->getMainListingsDetails();
		
		$finalProductDetails = array_merge($categorySponsorDetails, $goldListingDetails, $mainListingDetails);
		
		//make excel
        $documentDetails = $this->exportDataToExcel($finalProductDetails);
		
		//create zip files
		//$zip_name = $this->compressFiles($documentDetails['name']);
		
		//send mail
		$attachmentUrl = "www.shiksha.com/mediadata/reports/".$documentDetails['name'];
		$this->sendMailWithAttachmentLink($attachmentUrl);
	}
	
	public function getCategorySponsorDetails() {
		//call model function
		$categorySponsorDetailsResult = $this->productDeliveryModel->getCategorySponsorDetails();
		
		//process category sponsor result
		$instituteId_array                  	= array();
        $instituteId_courseId_map           	= array();
        $endDate_quarter_numOfDaysRem_map   	= array();
		$subcatId_cityName_clientDetails_map 	= array();
		$clientId_totalResponses_map			= array();
		$subcat_cityId_clientDetails_map		= array();
		foreach($categorySponsorDetailsResult->result() as $row) {
            $subcat_cityId_clientDetails_map[$row->subcategory_id][$row->city_id][$row->client_id]['start_date'][] = $row->start_date;
            $subcat_cityId_clientDetails_map[$row->subcategory_id][$row->city_id][$row->client_id]['end_date'][]   = $row->end_date;
			$subcat_cityId_clientDetails_map[$row->subcategory_id][$row->city_id][$row->client_id]['institute_id'][] = $row->institute_id;
            $clientId_clientName_map[$row->client_id]                               = $row->client_name;
            $this->instituteId_clientId_map[$row->institute_id]                     = $row->client_id;
            $this->subcatId_subcatName_map[$row->subcategory_id]                    = $row->subcategory_name;
			$this->subcatId_catId_map[$row->subcategory_id]							= $row->category_id;
            $cityId_cityName_map[$row->city_id]                                     = $row->city_name;
            $instituteId_array[]                                                    = $row->institute_id;
        }
        
        $instituteIdsUnique = array_unique($instituteId_array);
		$institutesIdsString = implode(",", $instituteIdsUnique);
		
		//get all courses
		$allCoursesQueryResult = $this->productDeliveryModel->getCoursesForInstitutes($institutesIdsString);
		
		foreach($allCoursesQueryResult->result() as $row) {
			$this->course_institute_map[$row->course_id] = $row->institute_id;
			$courseId_array[] = $row->course_id;
		}
		
		$courseIdsUnique = array_unique($courseId_array);
		
		$clientId_totalResponses_map = $this->getClientWiseResponses($courseIdsUnique, 'category');
		
		//format data
        $subcatId_cityName_clientDetails_map = array();
        foreach($subcat_cityId_clientDetails_map as $subcatId=>$cityId_clientDetails_map) {
            foreach($cityId_clientDetails_map as $cityId=>$clientDetails) {
                foreach($clientDetails as $clientId=>$date) {
                    $cityName               = $cityId_cityName_map[$cityId];
                    $clientName             = $clientId_clientName_map[$clientId];
					$subscriptionDays	    = $this->getNumOfDaysRemainingInEachQuarter($date['start_date'], $date['end_date'], $date['institute_id']);
					$totalResponses         = $clientId_totalResponses_map[$clientId][$subcatId][$cityId];
					for($quarter = 0; $quarter <= 5; $quarter++) {
                        $subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['clientName']              = $clientName;
                        $subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['subscriptionDays']    	   = $subscriptionDays['subscriptionDays'][$quarter];
						$subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['numOfListings']    	   = $subscriptionDays['numOfListings'][$quarter];
                        $subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['totalResponses']          = !empty($totalResponses[$quarter]) ? $totalResponses[$quarter] : 0;
                        $subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['avgResponses']            = '';
                    }
                }
            }
        }
		
		$categorySponsorDetails = array('Category Sponsor', $subcatId_cityName_clientDetails_map);
		
		return $categorySponsorDetails;
	}
	
	public function getGoldListingsDetails() {
		//call model function
		$goldListingDetailsResult = $this->productDeliveryModel->getGoldListingDetails();
		
		//process result
		$subcat_cityId_clientDetails_map = array();
        $clientId_clientName_map         = array();
        //$courseId_clientId_map           = array();
        $clientId_totalResponses_map     = array();
        $cityId_cityName_map             = array();
        $courseId_array                  = array();
        
        foreach($goldListingDetailsResult->result() as $row) {
            $subcat_cityId_clientDetails_map[$row->subcategory_id][$row->city_id][$row->client_id]['start_date'][] = $row->start_date;
            $subcat_cityId_clientDetails_map[$row->subcategory_id][$row->city_id][$row->client_id]['end_date'][]   = $row->end_date;
			$subcat_cityId_clientDetails_map[$row->subcategory_id][$row->city_id][$row->client_id]['course_id'][]  = $row->course_id;
            $clientId_clientName_map[$row->client_id]                               = $row->client_name;
            $this->courseId_clientId_map[$row->course_id]                           = $row->client_id;
			$this->subcatId_subcatName_map[$row->subcategory_id]                    = $row->subcategory_name;
			$this->subcatId_catId_map[$row->subcategory_id]							= $row->category_id;
            $cityId_cityName_map[$row->city_id]                                     = $row->city_name;
            $courseId_array[]                                                       = $row->course_id;
        }
        
        $courseIdsUnique = array_unique($courseId_array);
		
		$clientId_totalResponses_map = $this->getClientWiseResponses($courseIdsUnique, 'gold');
		
		//format data
        $subcatId_cityName_clientDetails_map = array();
        foreach($subcat_cityId_clientDetails_map as $subcatId=>$cityId_clientDetails_map) {
            foreach($cityId_clientDetails_map as $cityId=>$clientDetails) {
                foreach($clientDetails as $clientId=>$date) {
                    //$subcatName             = $subcatId_subcatName_map[$subcatId];
                    $cityName               = $cityId_cityName_map[$cityId];
                    $clientName             = $clientId_clientName_map[$clientId];
					$subscriptionDays       = $this->getNumOfDaysRemainingInEachQuarter($date['start_date'], $date['end_date'], $date['course_id']);
					$totalResponses         = $clientId_totalResponses_map[$clientId][$subcatId][$cityId];
                    for($quarter = 0; $quarter <= 5; $quarter++) {
                        $subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['clientName']              = $clientName;
                        $subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['subscriptionDays']		   = $subscriptionDays['subscriptionDays'][$quarter];
                        $subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['numOfListings']    	   = $subscriptionDays['numOfListings'][$quarter];
						$subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['totalResponses']          = $totalResponses[$quarter] ? $totalResponses[$quarter] : 0;
                        $subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['avgResponses']            = '';
                    }
                }
            }
        }
		
		$goldListingDetails = array('Gold Listing', $subcatId_cityName_clientDetails_map);
		
		return $goldListingDetails;
	}
	
	public function getMainListingsDetails() {
		//call model function
		$mainListingDetailsResult = $this->productDeliveryModel->getMainListingDetails();
		
		//process result
		$subcat_cityId_clientDetails_map = array();
        $clientId_clientName_map         = array();
        $clientId_totalResponses_map     = array();
        $cityId_cityName_map             = array();
        $instituteId_array               = array();
        
        foreach($mainListingDetailsResult->result() as $row) {
            $subcat_cityId_clientDetails_map[$row->subcategory_id][$row->city_id][$row->client_id]['start_date'][] = $row->start_date;
            $subcat_cityId_clientDetails_map[$row->subcategory_id][$row->city_id][$row->client_id]['end_date'][]   = $row->end_date;
			$subcat_cityId_clientDetails_map[$row->subcategory_id][$row->city_id][$row->client_id]['institute_id'][]  = $row->institute_id;
            $clientId_clientName_map[$row->client_id]                               = $row->client_name;
            $this->instituteId_clientId_map[$row->institute_id]                     = $row->client_id;
            $this->subcatId_subcatName_map[$row->subcategory_id]                    = $row->subcategory_name;
			$this->subcatId_catId_map[$row->subcategory_id]							= $row->category_id;
            $cityId_cityName_map[$row->city_id]                                     = $row->city_name;
            $instituteId_array[]                                                    = $row->institute_id;
        }
        
        //Get all responses for an institute found in category sponsor details (quarter-year wise)
        $instituteIdsUnique = array_unique($instituteId_array);
        $institutesIdsString = implode(",", $instituteIdsUnique);
		
		//get all courses
		$allCoursesQueryResult = $this->productDeliveryModel->getCoursesForInstitutes($institutesIdsString);
		
		foreach($allCoursesQueryResult->result() as $row) {
			$this->course_institute_map[$row->course_id] = $row->institute_id;
			$courseId_array[] = $row->course_id;
		}
		
		$courseIdsUnique = array_unique($courseId_array);
		
        $clientId_totalResponses_map = $this->getClientWiseResponses($courseIdsUnique, 'main');
		
		//format data
        $subcatId_cityName_clientDetails_map = array();
        foreach($subcat_cityId_clientDetails_map as $subcatId=>$cityId_clientDetails_map) {
            foreach($cityId_clientDetails_map as $cityId=>$clientDetails) {
                foreach($clientDetails as $clientId=>$date) {
                    $cityName               = $cityId_cityName_map[$cityId];
                    $clientName             = $clientId_clientName_map[$clientId];
                    $subscriptionDays	    = $this->getNumOfDaysRemainingInEachQuarter($date['start_date'], $date['end_date'], $date['institute_id']);
                    $totalResponses         = $clientId_totalResponses_map[$clientId][$subcatId][$cityId];
                    for($quarter = 0; $quarter <= 5; $quarter++) {
                        $subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['clientName']              = $clientName;
                        $subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['subscriptionDays']        = $subscriptionDays['subscriptionDays'][$quarter];
                        $subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['numOfListings']    	   = $subscriptionDays['numOfListings'][$quarter];
						$subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['totalResponses']          = $totalResponses[$quarter] ? $totalResponses[$quarter] : 0;
                        $subcatId_cityName_clientDetails_map[$subcatId][$cityName][$clientId][$quarter]['avgResponses']            = '';
                    }
                }
            }
        }
        
		$mainListingDetails = array('Main Listing', $subcatId_cityName_clientDetails_map);
		
		return $mainListingDetails;
	}
	
	public function getClientWiseResponses($courseIdsUnique, $productType) {
		$course_subcat_map = array();
		$course_city_map = array();
		$course_response_map = array();
		$clientId_totalResponses_map = array();
		$courseIdsString = implode(",", $courseIdsUnique);
		
		//create subcat course map
		$subcatWiseCoursesQueryResult = $this->productDeliveryModel->getSubcatForCourses($courseIdsString);
		
		foreach($subcatWiseCoursesQueryResult->result() as $row) {
			$course_subcat_map[$row->course_id][] = $row->subcategory_id;
		}
		
		//create city course map
		$cityWiseCoursesQueryResult = $this->productDeliveryModel->getCityForCourses($courseIdsString);
		
		foreach($cityWiseCoursesQueryResult->result() as $row) {
			$course_city_map[$row->course_id][] = $row->city_id;
		}
		
		if($productType == 'category') {
			$courseResponsesQueryResult = $this->productDeliveryModel->getCourseResponsesForCategorySponsor($courseIdsString);
		} else if($productType == 'gold') {
			$courseResponsesQueryResult = $this->productDeliveryModel->getCourseResponsesForGoldListing($courseIdsString);
		} else {
			$courseResponsesQueryResult = $this->productDeliveryModel->getCourseResponsesForMainListing($courseIdsString);
		}
		
		foreach($courseResponsesQueryResult->result() as $row) {
			$quarter = $this->getOurCustomQuarter($row->month, $row->year);
			if($quarter != 6) {
				if($productType != 'gold') {
					$course_response_map[$row->course_id][$row->subcategory_id][$row->city_id][$quarter] += $row->responses;
				} else {
					$course_response_map[$row->course_id][$quarter] += $row->responses;
				}
			}
		}
		
		foreach($courseIdsUnique as $courseId) {
			if($productType == 'gold') {
				$clientId = $this->courseId_clientId_map[$courseId];
			} else {
				$clientId = $this->instituteId_clientId_map[$this->course_institute_map[$courseId]];
			}
			
			$subcatIdArr = $course_subcat_map[$courseId];
			$cityIdArr = $course_city_map[$courseId];
			
			if($productType != 'gold') {
				$reponsesInEachQuarterSubcatWise = $course_response_map[$courseId];
				foreach($reponsesInEachQuarterSubcatWise as $subcatId=>$reponsesInEachQuarterCityWise) {
					if(in_array($subcatId, $subcatIdArr)) {
						foreach($reponsesInEachQuarterCityWise as $cityId=>$reponsesInEachQuarter) {
							$virtualCity = $this->cityExistsUnderVirtualCity($cityIdArr, $cityId); //course is in location(s) ($cityIdArr) that comes under the city ($cityId) where it is set as category sponsor
							if(in_array($cityId, $cityIdArr) || $cityId == 1 || $virtualCity) {
								for($quarter = 0; $quarter <= 5; $quarter++) {
									if(empty($clientId_totalResponses_map[$clientId][$subcatId][$cityId][$quarter])) {
										$clientId_totalResponses_map[$clientId][$subcatId][$cityId][$quarter] = 0;
									}
									$clientId_totalResponses_map[$clientId][$subcatId][$cityId][$quarter] += $reponsesInEachQuarter[$quarter];
								}
							}
						}
					}
				}
			} else {
				$reponsesInEachQuarter = $course_response_map[$courseId];
				foreach($subcatIdArr as $subcatId) {
					foreach($cityIdArr as $cityId) {
						for($quarter = 0; $quarter <= 5; $quarter++) {
							if(empty($clientId_totalResponses_map[$clientId][$subcatId][$cityId][$quarter])) {
								$clientId_totalResponses_map[$clientId][$subcatId][$cityId][$quarter] = 0;
							}
							$clientId_totalResponses_map[$clientId][$subcatId][$cityId][$quarter] += $reponsesInEachQuarter[$quarter];
						}
					}
				}
			}
		}
		
		return $clientId_totalResponses_map;
	}
	
	public function cityExistsUnderVirtualCity($cityIdArr, $virtualCityId) {
		$allVirtualCityObjects = $this->locationRepository->getCitiesByVirtualCity($virtualCityId);
		foreach($cityIdArr as $cityId) {
			foreach($allVirtualCityObjects as $cityObj) {
				if($cityObj->getId() == $cityId) {
					return true;
				}
			}
		}
		return false;
	}
	
	/*
	public function formalFinalResult($subcat_cityId_clientDetails_map, $subcatId_subcatName_map, $cityId_cityName_map, $clientId_clientName_map, $clientId_totalResponses_map) {
		$subcatId_cityName_clientDetails_map = array();
        foreach($subcat_cityId_clientDetails_map as $subcatId=>$cityId_clientDetails_map) {
            foreach($cityId_clientDetails_map as $cityId=>$clientDetails) {
                foreach($clientDetails as $clientId=>$date) {
                    $subcatName             = $subcatId_subcatName_map[$subcatId];
                    $cityName               = $cityId_cityName_map[$cityId];
                    $clientName             = $clientId_clientName_map[$clientId];
                    $subscriptionDays	    = $this->getNumOfDaysRemainingInEachQuarter($date['start_date'], $date['end_date']);
                    $totalResponses         = $clientId_totalResponses_map[$clientId];
                    for($quarter = 0; $quarter <= 4; $quarter++) {
                        $subcatId_cityName_clientDetails_map[$subcatName][$cityName][$clientId][$quarter]['clientName']              = $clientName;
                        $subcatId_cityName_clientDetails_map[$subcatName][$cityName][$clientId][$quarter]['subscriptionDays']		   = $subscriptionDays['subscriptionDays'][$quarter];
                        $subcatId_cityName_clientDetails_map[$subcatName][$cityName][$clientId][$quarter]['numOfListings']    		   = $subscriptionDays['numOfListings'][$quarter];
						$subcatId_cityName_clientDetails_map[$subcatName][$cityName][$clientId][$quarter]['totalResponses']          = $totalResponses[$quarter];
                        $subcatId_cityName_clientDetails_map[$subcatName][$cityName][$clientId][$quarter]['avgResponses']            = '';
                    }
                }
            }
        }
		
		return $subcatId_cityName_clientDetails_map;
	}*/
    
	public function exportDataToExcel($dataList) {
        $numOfQuatersToShow = 6;
        $current_year       = date('Y');
        $documentURLArray   = array();
		
        $headerArray_1 = array('Q1 2014-2015',
        					   'Q2 2014-2015',
                               'Q3 2014-2015',
                               'Q4 2015-2016',
                               'Q1 2015-2016',
                               'Q2 2015-2016');
        
        $headerArray_2 = array(array('name'=>'Product',                         'width'=>20),
							   array('name'=>'Category',                        'width'=>40),
                               array('name'=>'Subcategory',                     'width'=>40),
                               array('name'=>'City',                            'width'=>20),
                               array('name'=>'Client Name',                     'width'=>30),
                               array('name'=>'Subscription Duration (Days)',    'width'=>30),
							   array('name'=>'Number of Listings', 				'width'=>20),
                               array('name'=>'Total Responses',                 'width'=>15),
                               array('name'=>'Avg Responses/Day',               'width'=>20)
                              );
		
		error_log("Preparing excel.....\n", 3, $this->logFilePath);
		$time_start = microtime_float();
		
		$this->objPHPExcel->setActiveSheetIndex(0);
		
		error_log("Inserting 1st header.....\n", 3, $this->logFilePath);
		$rowCount = 1;
		$column = 'E';
		for ($i = 0; $i < count($headerArray_1); $i++) {
			$column_next = $column;
			$column_next++;
			$column_next++;
			$column_next++;
			$column_next++;
			$this->objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $headerArray_1[$i]);
			$this->objPHPExcel->getActiveSheet()->mergeCells($column.'1:'.($column_next).'1');
			$this->objPHPExcel->getActiveSheet()->getStyle($column.'1:'.($column_next).'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$column = $column_next;
			$column++;
		}
		
		error_log("Inserting 2nd header part 1.....\n", 3, $this->logFilePath);
		$rowCount = 2;
		$column = 'A';
		$round = 1;
		for ($i = 0; $i < 4; $i++) {
			$this->objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $headerArray_2[$i]['name']);
			$this->objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth($headerArray_2[$i]['width']);
			$this->objPHPExcel->getActiveSheet()->getStyle($column)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$column++;
		}
		
		error_log("Inserting 2nd header part 2.....\n", 3, $this->logFilePath);
		while ($round <= $numOfQuatersToShow) {   
			for ($i = 4; $i < count($headerArray_2); $i++) {
				$this->objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $headerArray_2[$i]['name']);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth($headerArray_2[$i]['width']);
				$this->objPHPExcel->getActiveSheet()->getStyle($column)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$column++;
			}
			$round++;
		}
		
		$column = 'A'; $rowCount = 4;
		foreach($dataList as $key=>$productData) {
			if($key % 2 == 0) {
				$productName = $productData;
			} else {
				foreach($productData as $subcatId=>$cityLevelData) {
					foreach($cityLevelData as $city=>$clientData) {
						foreach($clientData as $clientId=>$quarter_clientData_map) {
							$column = 'A';
							$this->objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $productName);
							$this->objPHPExcel->getActiveSheet()->setCellValue(++$column.$rowCount, $this->catId_catName_map[$this->subcatId_catId_map[$subcatId]]);
							$this->objPHPExcel->getActiveSheet()->setCellValue(++$column.$rowCount, $this->subcatId_subcatName_map[$subcatId]);
							$this->objPHPExcel->getActiveSheet()->setCellValue(++$column.$rowCount, $city);
							foreach($quarter_clientData_map as $quarter=>$data) {
								$this->objPHPExcel->getActiveSheet()->setCellValue(++$column.$rowCount, $data['clientName']);
								$this->objPHPExcel->getActiveSheet()->setCellValue(++$column.$rowCount, $data['subscriptionDays']);
								$this->objPHPExcel->getActiveSheet()->setCellValue(++$column.$rowCount, $data['numOfListings']);
								$this->objPHPExcel->getActiveSheet()->setCellValue(++$column.$rowCount, $data['totalResponses']);
								$this->objPHPExcel->getActiveSheet()->setCellValue(++$column.$rowCount, $data['avgResponses']);
							}
							$rowCount++;
						}
					}
				}
			}
		}
		
		$documentName = "Product_delivery_report_".date('Y-m-d').'.xlsx';
		$documentURL = "/var/www/html/shiksha/mediadata/reports/".$documentName;
		
		$objWriter = new PHPExcel_Writer_Excel2007($this->objPHPExcel);
		$objWriter->save($documentURL);
		
		$time_end = microtime_float();
		$time = $time_end - $time_start;
		error_log("Excel prepared. Time taken: ".round($time, 4)." seconds\n", 3, $this->logFilePath);
		error_log($documentName." created and saved as: ".$documentURL.".\n", 3, $this->logFilePath);
		
		$documentDetails = array('name'=>$documentName, 'url'=>$documentURL);
		return $documentDetails;
	}
	
	/*
	function compressFiles($documentName) {
		error_log("Compressing files.....\n", 3, $this->logFilePath);
		
		$zip = new ZipArchive();
		$zip_name = "Product_delivery_zip_file_".date('y-m-d').".zip";
		$zip_path = "/var/www/html/shiksha/mediadata/reports/".$zip_name;
		error_log("zip name: ".$zip_name, 3, $this->logFilePath);
		
		if($zip->open($zip_path, ZIPARCHIVE::CREATE)!==TRUE){
			error_log("ZIP creation failed.\n", 3, $this->logFilePath);
		} else {
			error_log("\nAdding file: ".$documentName."  to zip folder", 3, $this->logFilePath);
			
			$zip->addFile($documentName);
			$zip->close();
		}
		error_log("Zip created.....\n", 3, $this->logFilePath);
		
		return $zip_name;
	}*/
	
	function sendMailWithAttachmentLink($attachmentUrl) {
		//send mail
        $time_start = microtime_float();
        error_log("Preparing mail.....\n", 3, $this->logFilePath);
        
        $subject = "Product delivery report ".date('y-m-d');
        $content = "<p>Hi,</p> <p>You can access the report through following link:</p> <p>".$attachmentUrl."</p> <p>- Shiksha Dev</p>";
        
        $emailIdarray=array('saurabh.gupta@shiksha.com', 'nikita.jain@shiksha.com', 'pankaj.meena@shiksha.com');
		foreach($emailIdarray as $key=>$emailId){
			$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $content, "html", '', 'n');
		}
		
        $time_end = microtime_float();
        $time = $time_end - $time_start;
		
        error_log("Mail prepared. Time taken: ".round($time, 4)." seconds\n", 3, $this->logFilePath);
		return;
    }
	
	
	
	public function getAbroadProductClientDetails(){
		
		$categorySponsorDetails = array();
		$goldListingDetails = array();
		$mainListingDetails = array();
		
		//fetch all categories
		$categoryResult = $this->productDeliveryModel->getAllCategories();
		foreach($categoryResult->result() as $row) {
			$this->catId_catName_map[$row->category_id] = $row->category_name;
		}
		error_log("::REAVER:: Starting Process");
		//get category sponsor details
		$categorySponsorDetails = $this->getAbroadCategorySponsorDetails();
		error_log("::REAVER:: CategorySponsorsDone");
		//get main listings details
		$mainListingDetails = $this->getAbroadMainListingsDetails();
		error_log("::REAVER:: MainListingsDone");
		//get gold listings details
		$goldListingDetails = $this->getAbroadGoldListingsDetails();
		error_log("::REAVER:: GoldListingsDone");
		$goldListingDetails = $this->removeAbroadGoldListingMultipleCounts($categorySponsorDetails,$mainListingDetails,$goldListingDetails);
		error_log("::REAVER:: CountingIssuesFixed");
		$finalProductDetails = array_merge($categorySponsorDetails, $mainListingDetails, $goldListingDetails);
		
		//make excel
        $documentDetails = $this->exportAbroadDataToExcel($finalProductDetails);
		error_log("::REAVER:: Excel Ready");
		
		//send mail
		//$attachmentUrl = "www.shiksha.com/mediadata/reports/".$documentDetails['name'];
		//$this->sendAbroadReportEmail($attachmentUrl);
	}
	
	function getAbroadCategorySponsorDetails(){
		$categorySponsorResult =  $this->productDeliveryModel->getAbroadCategorySponsorDetails();
		$excelData = array();
		foreach($categorySponsorResult as $rowData){
			if(empty($excelData['Category Sponsor'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['monthlyData'])){
				$excelData['Category Sponsor'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['monthlyData'] = $this->extractAndPrepareMonthlyData($rowData,'CategorySponsor');
			}
			else{
				$adds = $this->extractAndPrepareMonthlyData($rowData,'CategorySponsor');
				foreach($adds as $monthNumber=>$monthData){
					$excelData['Category Sponsor'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['monthlyData'][$monthNumber]['subscription_days'] += $monthData['subscription_days'];
					$excelData['Category Sponsor'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['monthlyData'][$monthNumber]['response_count'] += $monthData['response_count'];
				}
			}
			$excelData['Category Sponsor'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['client_name'] = $rowData['client_name'];
		}
		return $excelData;
	}
	
	function getAbroadGoldListingsDetails(){
		$goldListingsResult = $this->productDeliveryModel->getAbroadGoldListingsDetails();
		$excelData = array();
		foreach($goldListingsResult as $rowData){
			if(empty($excelData['Gold Listings'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['monthlyData'])){
				$excelData['Gold Listings'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['monthlyData'] = $this->extractAndPrepareMonthlyData($rowData,'Gold');
			}
			else{
				$adds = $this->extractAndPrepareMonthlyData($rowData,'Gold');
				foreach($adds as $monthNumber=>$monthData){
					$excelData['Gold Listings'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['monthlyData'][$monthNumber]['subscription_days'] += $monthData['subscription_days'];
					$excelData['Gold Listings'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['monthlyData'][$monthNumber]['response_count'] += $monthData['response_count'];
				}
			}
			$excelData['Gold Listings'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['client_name'] = $rowData['client_name'];
		}
		return $excelData;
	}
	
	function getAbroadMainListingsDetails(){
		$mainListingsResult = $this->productDeliveryModel->getAbroadMainListingsDetails();
		$excelData = array();
		foreach($mainListingsResult as $rowData){
			if(empty($excelData['Main Listings'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['monthlyData'])){
				$excelData['Main Listings'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['monthlyData'] = $this->extractAndPrepareMonthlyData($rowData,'MainListing');
			}
			else{
				$adds = $this->extractAndPrepareMonthlyData($rowData,'MainListing');
				foreach($adds as $monthNumber=>$monthData){
					$excelData['Main Listings'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['monthlyData'][$monthNumber]['subscription_days'] += $monthData['subscription_days'];
					$excelData['Main Listings'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['monthlyData'][$monthNumber]['response_count'] += $monthData['response_count'];
				}
			}
			$excelData['Main Listings'][$rowData['category_name']][$rowData['country_name']][$rowData['client_id']]['client_name'] = $rowData['client_name'];
		}
		return $excelData;
		
	}
	
	function extractAndPrepareMonthlyData($rowData,$bucket){
		switch($bucket){
			case 'CategorySponsor':
			case 'MainListing':
				$listingType = 'university';
				break;
			case 'Gold':
				$listingType = 'course';
				break;
		}
		$monthlyFragments = array();
		$baseMonth = 6;
		for($monthCounter = 0; $monthCounter < count($this->abroadMonthStarts);$monthCounter++){
			
			$monthStart 	= $this->abroadMonthStarts[$monthCounter];
			$monthEnd 		= $this->abroadMonthEnds[$monthCounter];
			$monthlyFragments[$monthCounter+$baseMonth]['subscription_days'] = $this->dateRangeIntersect($monthStart,$monthEnd,$rowData['start_date'],$rowData['end_date']);
			if($monthlyFragments[$monthCounter+$baseMonth]['subscription_days']  > 0){
				$monthlyFragments[$monthCounter+$baseMonth]['response_count'] = $this->getResponseCountForClient($rowData,$monthStart,$monthEnd,$listingType);
				$monthlyFragments[$monthCounter+$baseMonth]['responses_per_day'] = $monthlyFragments[$monthCounter+$baseMonth]['response_count'] / $monthlyFragments[$monthCounter+$baseMonth]['subscription_days'];
			}
			else{
				$monthlyFragments[$monthCounter+$baseMonth]['response_count'] = 0;
				$monthlyFragments[$monthCounter+$baseMonth]['responses_per_day'] = 0;
			}
			
			
		}
		return $monthlyFragments;
	}
	
	function dateRangeIntersect($monthStart,$monthEnd,$rangeStart,$rangeEnd){
		if(strtotime($rangeStart) >= strtotime($monthStart) && strtotime($rangeStart) <= strtotime($monthEnd)){	//If range starts within this month
			$startDate = $rangeStart;
		}
		if(strtotime($monthStart) >= strtotime($rangeStart) && strtotime($monthStart) <= strtotime($rangeEnd)){	// If month starts within the range
			$startDate = $monthStart;
		}
		if($startDate){	//if we have set a startdate, then we have days in this month that are within range.
			if(strtotime($rangeEnd) <= strtotime($monthEnd)){
				return (-1*(strtotime($startDate) - strtotime($rangeEnd)) / 86400)+1;	// If our days end at the range's end
			}
			else{
				return (-1*(strtotime($startDate) - strtotime($monthEnd)) / 86400)+1;	// If our days end at the month's end
			}
		}
		return 0;
	}

	function getResponseCountForClient($rowData, $monthStart,$monthEnd,$listing_type){
		// listing_type will be university when used for CategorySponsor/MIL and course for Gold Listings
		// Get required start and end dates for this month
		$rangeStart = $rowData['start_date'];
		$rangeEnd = $rowData['end_date'];
		if(strtotime($rangeStart) >= strtotime($monthStart) && strtotime($rangeStart) <= strtotime($monthEnd)){	//If range starts within this month
			$startDate = $rangeStart;
		}
		if(strtotime($monthStart) >= strtotime($rangeStart) && strtotime($monthStart) <= strtotime($rangeEnd)){	// If month starts within the range
			$startDate = $monthStart;
		}
		if($startDate){	//if we have set a startdate, then we have days in this month that are within range.
			if(strtotime($rangeEnd) <= strtotime($monthEnd)){
				$endDate = $rangeEnd;	// If our days end at the range's end
			}
			else{
				$endDate = $monthEnd;	// If our days end at the month's end
			}
		}
		if($startDate){
			if($listing_type == 'university'){		//Get all courses of this university from acpd and response count from tempLMSTable
				$coursesOfUniversity = $this->productDeliveryModel->getCoursesOfUniversity($rowData['university_id'],$rowData['category_id']);
				$netResponseCount = $this->productDeliveryModel->getResponseCountOfCourses($coursesOfUniversity,$startDate,$endDate);
				
			}elseif($listing_type == 'course'){
				$netResponseCount = $this->productDeliveryModel->getResponseCountOfCourses(array($rowData['course_id']),$startDate,$endDate);
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		return $netResponseCount;
	}
	
	public function removeAbroadGoldListingMultipleCounts($csArray, $milArray, $filArray){
		foreach($filArray as $productName => $catArray){
			foreach($catArray as $categoryName => $countryArray){
				foreach($countryArray as $countryName => $clientArray){
					foreach($clientArray as $clientId => $monthlyArray){
						foreach($monthlyArray as $monthlyLabel => $monthArray){
							foreach($monthArray as $monthCounter=>$monthData){
								$csVal = $csArray['Category Sponsor'][$categoryName][$countryName][$clientId][$monthlyLabel][$monthCounter]['response_count'];
								$milVal = $milArray['Main Listings'][$categoryName][$countryName][$clientId][$monthlyLabel][$monthCounter]['response_count'];
								$csVal = (empty($csVal)?0:$csVal);
								$milVal = (empty($milVal)?0:$milVal);
								$filArray[$productName][$categoryName][$countryName][$clientId][$monthlyLabel][$monthCounter]['response_count'] -= ($csVal + $milVal);
								if($filArray[$productName][$categoryName][$countryName][$clientId][$monthlyLabel][$monthCounter]['response_count'] <= 0){
									$filArray[$productName][$categoryName][$countryName][$clientId][$monthlyLabel][$monthCounter]['response_count'] = 0;
								}
							}
						}
					}
				}
			}
		}
		return $filArray;
	}
	
	public function exportAbroadDataToExcel($data){
		$upperHeader = $this->abroadMonthHeadings;
        $lowerHeader = array("Product","Category","Country");
		$columnWidths= array(20,25,15);
		$sectionHeaderValues = array("Client Name","Subscription Days", "Total Responses","Responses/Day");
		$sectionWidths = array(40,20,15,15);
		foreach($upperHeader as $month){
			$lowerHeader = array_merge($lowerHeader,$sectionHeaderValues);	//Add one ser of section headers per month
			$columnWidths = array_merge($columnWidths,$sectionWidths);
		}
		
		$this->objPHPExcel->setActiveSheetIndex(0);
		
		// Generate the first header
		
		$rowCount = 1;
		$column = 'D';
		for ($i = 0; $i < count($upperHeader); $i++) {
			$column_next = $column;
			$column_next++;
			$column_next++;
			$column_next++;
			$this->objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $upperHeader[$i]);
			$this->objPHPExcel->getActiveSheet()->mergeCells($column.'1:'.($column_next).'1');
			$this->objPHPExcel->getActiveSheet()->getStyle($column.'1:'.($column_next).'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$column = $column_next;
			$column++;
		}
		
		$rowCount = 2;
		$column = 'A';
		$round = 1;
		for ($i = 0; $i < count($lowerHeader); $i++) {
			$this->objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $lowerHeader[$i]);
			$this->objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth($columnWidths[$i]);
			$this->objPHPExcel->getActiveSheet()->getStyle($column)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$column++;
		}
		
		$column = 'A'; $rowCount = 3;
		foreach($data as $productName=>$categoryArray){
			foreach($categoryArray as $categoryName => $countryArray){
				foreach($countryArray as $countryName=> $clientArray){
					foreach($clientArray as $clientId => $monthlyArray){
						$monthArray = $monthlyArray['monthlyData'];
						$clientName = $monthlyArray['client_name'];
						$column = 'A';
						$this->objPHPExcel->getActiveSheet()->setCellValue($column++.$rowCount, $productName);
						$this->objPHPExcel->getActiveSheet()->setCellValue($column++.$rowCount, $categoryName);
						$this->objPHPExcel->getActiveSheet()->setCellValue($column++.$rowCount, $countryName);
						foreach($monthArray as $monthCounter=>$monthData){
							$this->objPHPExcel->getActiveSheet()->setCellValue($column++.$rowCount, $clientName);
							$this->objPHPExcel->getActiveSheet()->setCellValue($column++.$rowCount, $monthData['subscription_days']);
							$this->objPHPExcel->getActiveSheet()->setCellValue($column++.$rowCount, $monthData['response_count']);
							$this->objPHPExcel->getActiveSheet()->setCellValue($column++.$rowCount, $monthData['responses_per_day']);
						}
						$rowCount++;
						
					}
				}
			}
		}
		
		$documentName = "Product_delivery_report_".date('Y-m-d').'.xlsx';
		$documentURL = "/var/www/html/shiksha/mediadata/reports/".$documentName;
		
		$objWriter = new PHPExcel_Writer_Excel2007($this->objPHPExcel);
		$objWriter->save($documentURL);
		
		$documentDetails = array('name'=>$documentName, 'url'=>$documentURL);
		return $documentDetails;
	}
	
	public function sendAbroadReportEmail($attachmentURL){
		$subject = "Product Delivery Report from ".$this->abroadMonthStarts[0]." to ".end($this->abroadMonthEnds);
		$content = "<p>Hi,</p><p>The generated report can be accessed at the following URL : </p><p>".$attachmentURL."</p><p>Shiksha Dev</p>";
		$emailIdarray = array('simrandeep.singh@shiksha.com');
		foreach($emailIdarray as $emailId){
			$this->alertClient->externalQueAdd("12",ADMIN_EMAIL,$emailId,$subject,$content,"html",'','n');
		}
	}
	
}