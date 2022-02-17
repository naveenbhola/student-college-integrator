<?php
    class AbroadListingCronLibrary
    {
        private $CI;

        function __construct()
        {
            $this->CI = &get_instance();
            $this->abroadListingCronModel = $this->CI->load->model("abroadlistingcronmodel");
            $this->useElasticForUserMovementData = USE_ELASTIC_SEARCH && true;
        }

        public function getUniversityToUserData()
        {
            if (!$this->useElasticForUserMovementData) {
                echo "Can not fetch data from mysql";
                return;
            }
            $offsetForData = 0;
            $dataChunk = 5000;
            // This is pseudo User ID generated for users who are not logged in. There userId in database is -1 and 0 in elastic
            $tempUserIdStart = 70000000;
            $uniqueCountOfEntityToUserSet = 0;
            $sessionMapToPsuedoUserId = array();
            $entityToUserSet = array();
            $this->CI->load->model('abroadlistingcronmodel');
            $abroadListingCronModel = new abroadlistingcronmodel();
            $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
            $this->elasticClientCon = $ESConnectionLib->getShikshaESServerConnection();
            $params['pageIdentifier'] = array('universityPage');
            $totalRows = $abroadListingCronModel->getUserMovementTotalRows($params, $this->useElasticForUserMovementData, $this->elasticClientCon);
            do {
                $result = $abroadListingCronModel->getUniversityUserMovementDataFromElastic($params, $offsetForData, $dataChunk, $this->elasticClientCon);
                foreach ($result as $data) {
                    $userId = $data['userId'];
                    if ($data['userId'] == 0) {
                        $sessionId = $data['sessionId'];
                        if ($sessionMapToPsuedoUserId[$sessionId]) {
                            $userId = $sessionMapToPsuedoUserId[$sessionId];
                        } else {
                            $tempUserIdStart += 1;
                            $sessionMapToPsuedoUserId[$sessionId] = $tempUserIdStart;
                            $userId = $sessionMapToPsuedoUserId[$sessionId];
                        }
                    }
                    $universityId = $data['universityId'];
                    if (!(array_key_exists($universityId, $entityToUserSet) && in_array($userId, $entityToUserSet[$universityId]))) {
                        $entityToUserSet[$universityId][] = $userId;
                        ++$uniqueCountOfEntityToUserSet;
                    }
                }
                $offsetForData += $dataChunk;
            } while ($offsetForData <= $totalRows);
            //error_log('Data Fetch done'.': '.date('H:i:s').PHP_EOL,3,'/tmp/logliklihood.txt');
            return array('data' => $entityToUserSet, 'uniqueCombination' => $uniqueCountOfEntityToUserSet);
        }

        public function setUniversityLogLikeliHoodAnalysisData(&$param)
        {
            $dataArrayForDB = array();
            $dateAdded = date('Y-m-d');
            foreach ($param as $primaryEntityId => $dataSet) {
                arsort($dataSet);
                $topItemsToConsider = 10;
                foreach ($dataSet as $secondayEntityId => $score) {
                    if (0 == $topItemsToConsider--) {
                        break;
                    }
                    $dataArrayForDB[] = array('primaryEntityId' => $primaryEntityId,
                        'secondayEntityId' => $secondayEntityId,
                        'entityType' => 'university',
                        'score' => $score,
                        'dateUpdated' => $dateAdded,
                        'status' => 'live'
                    );
                }
            }
            $abroadListingCronModel = $this->CI->load->model('abroadlistingcronmodel');
            $abroadListingCronModel->insertLogLikelihoodData($dataArrayForDB, 'university');
            return;
        }

        /*
         * function to create cache keys for courses based on whether they are desired or category-level courses
         */
        public function createAbroadCourseCacheKeys()
        {
            $this->abroadListingCronModel = $this->CI->load->model("abroadlistingcronmodel");
            $result = $this->abroadListingCronModel->getAbroadCategoryPageData();
            // get popular/ desired courses
            $this->abroadCommonLib = $this->CI->load->library("listingPosting/AbroadCommonLib");
            $desiredCourses = $this->abroadCommonLib->getAbroadMainLDBCourses();
            $desiredCourses = array_map(function ($a) {
                return $a['SpecializationId'];
            }, $desiredCourses);
            $this->courseCacheKeys = array();
            foreach ($result as $row) {
                // check if key was created  for this course
                if (!array_key_exists($row['course_id'], $this->courseCacheKeys)) {   // create key if not
                    $this->courseCacheKeys[$row['course_id']] = array();
                    // if this is a desired course ...
                    if (in_array($row['ldb_course_id'], $desiredCourses) !== false) {
                        $this->courseCacheKeys[$row['course_id']]['isDesiredCourse'] = true;
                        $this->courseCacheKeys[$row['course_id']]['key'] = $row['country_id'] . '-' . $row['ldb_course_id'];
                    } else { // non desired course 's key includes category, level
                        $this->courseCacheKeys[$row['course_id']]['isDesiredCourse'] = false;
                        $this->courseCacheKeys[$row['course_id']]['key'] = $row['country_id'] . '-' . $row['category_id'] . '-' . str_replace(' ', '-', strtolower($row['course_level']));
                    }
                } else {
                    // if the key exists for the course check if it was for a desired course, then cat-level based key must not be created
                    // but if cat-level key exists & course is a desired course, update the key to be that desired course
                    if (!($this->courseCacheKeys[$row['course_id']]['isDesiredCourse']) &&
                        in_array($row['ldb_course_id'], $desiredCourses) !== false) {
                        $this->courseCacheKeys[$row['course_id']]['isDesiredCourse'] = true;
                        $this->courseCacheKeys[$row['course_id']]['key'] = $row['country_id'] . '-' . $row['ldb_course_id'];
                    }
                }
            }
            //_p($this->courseCacheKeys);
            return $this->courseCacheKeys;
        }


        /*
         * function to create first year fee averages
         */
        public function createFirstYearFeeAverages()
        {
            $this->abroadListingCronModel = $this->CI->load->model("abroadlistingcronmodel");
            $result = $this->abroadListingCronModel->getFirstYearFeesForAllAbroadCourses();
            $abroadListingCache = $this->CI->load->library("cache/AbroadListingCache");
            $allExchangeRates = $abroadListingCache->getCurrencyConversionFactor(NULL, NULL, true);
            $keyWiseFees = array();
            foreach ($result as $row) {
                // convert the fee into INR
                $fee = $row['fees_unit'] == 1 ? $row['fees_value'] : $row['fees_value'] * $allExchangeRates[$row['fees_unit']][1]['factor'];
                if (!array_key_exists($this->courseCacheKeys[$row['course_id']]['key'], $keyWiseFees)) {
                    $keyWiseFees[$this->courseCacheKeys[$row['course_id']]['key']] = array('sum' => 0, 'total' => 0);
                }
                $keyWiseFees[$this->courseCacheKeys[$row['course_id']]['key']]['sum'] += $fee;
                $keyWiseFees[$this->courseCacheKeys[$row['course_id']]['key']]['total']++;
            }
            // now add this to cache
            foreach ($keyWiseFees as $key => $fees) {
                $avg = $fees['sum'] / $fees['total'];
                $data = array('average' => $avg, 'count' => $fees['total']);
                if ($avg > 0) {
                    $abroadListingCache->storeAverage1stYearFees($key, $data);
                }
            }// done
        }


        /*
         * function to create living expense averages
         */
        public function createLivingExpenseAverages()
        {
            $this->abroadListingCronModel = $this->CI->load->model("abroadlistingcronmodel");
            $result = $this->abroadListingCronModel->getLivingExpensesForAllUniversities();
            $abroadListingCache = $this->CI->load->library("cache/AbroadListingCache");
            $allExchangeRates = $abroadListingCache->getCurrencyConversionFactor(NULL, NULL, true);
            $univWiseExpense = array();
            foreach ($result as $row) {
                // convert the living expense into INR
                $livingExpense = $row['currency'] == 1 ? $row['living_expenses'] : $row['living_expenses'] * $allExchangeRates[$row['currency']][1]['factor'];
                if (!array_key_exists($row['country_id'], $univWiseExpense)) {
                    $univWiseExpense[$row['country_id']] = array('sum' => 0, 'total' => 0);
                }
                $univWiseExpense[$row['country_id']]['sum'] += $livingExpense;
                $univWiseExpense[$row['country_id']]['total']++;
            }

            // now add this to cache
            foreach ($univWiseExpense as $countryId => $expense) {
                $avg = $expense['sum'] / $expense['total'];
                $data = array('average' => $avg, 'count' => $expense['total']);
                if ($avg > 0) {
                    $abroadListingCache->storeAverageLivingExpense($countryId, $data);
                }
            }// done
        }


        /*
         * function to create exam score averages
         */
        public function createExamScoreAverages()
        {
            $this->abroadListingCronModel = $this->CI->load->model("abroadlistingcronmodel");
            $result = $this->abroadListingCronModel->getExamScoresForAllAbroadCourses();
            //_p($result[0]);
            // get exam master list from cache
            $abroadCommonLib = $this->CI->load->library("listingPosting/AbroadCommonLib");
            $examMasterList = $abroadCommonLib->getAbroadExamsMasterList();
            $allExams = array();
            foreach ($examMasterList as $exam) {
                $allExams[$exam['examId']] = $exam;
            }//_p($allExams);
            // this will be used for conversion of CAE scores to numeric so that average can be calculated
            $CAEScores = array_reverse(explode(',', $allExams[9]['range']));
            $abroadListingCache = $this->CI->load->library("cache/AbroadListingCache");
            //_p($abroadListingCache->getAverageExamScores("8-239-Bachelors-3"));die;//60
            $keyWiseScores = array();
            foreach ($result as $row) {
                $examScore = $row['cutoff'];
                // convert the score into a numeric value if not numeric already
                $pos = array_search($examScore, $CAEScores);
                if ($pos !== false) {
                    $examScore = $pos + 1;
                }
                // add examId to the key & store the score sum & total records
                $currKey = $this->courseCacheKeys[$row['course_id']]['key'] . '-' . $row['examId'];
                if (!array_key_exists($currKey, $keyWiseScores)) {
                    $keyWiseScores[$currKey] = array('sum' => 0, 'total' => 0);
                }
                $keyWiseScores[$currKey]['examId'] = $row['examId'];
                $keyWiseScores[$currKey]['sum'] += $examScore;
                $keyWiseScores[$currKey]['total']++;
            }
            //_p($keyWiseScores);
            $examCountryLevelSum = array();
            // now add this to cache
            foreach ($keyWiseScores as $key => $score) {
                $avg = $score['sum'] / $score['total'];
                if ($allExams[$score['examId']]['type'] == "Grades") {
                    $range = 1;
                } else {
                    $range = $allExams[$score['examId']]['range'];
                    if ($allExams[$score['examId']]['range'] >= 1) {
                        $avg = round($avg);
                    }
                }
                $avg = $this->_getNearestValueByRangeInterval($avg, $range);
                // if exam was CAE convert back to grades
                if ($allExams[$score['examId']]['type'] == "Grades") {
                    $avg = $CAEScores[intval($avg) - 1];
                }
                echo $key;
                _p($avg);
                if ($avg > 0 || in_array($avg, $CAEScores)) {
                    $abroadListingCache->storeAverageExamScores($key, array('avg' => $avg, 'totalRecords' => $score['total']));
                }

                //added code for average exam score for all courses at country level
                $countryId = explode("-", $key)[0];
                $keyCountryLevel = $countryId . "-" . $score['examId'];
                if (!array_key_exists($keyCountryLevel, $examCountryLevelSum)) {
                    $examCountryLevelSum[$keyCountryLevel]['sum'] = 0;
                    $examCountryLevelSum[$keyCountryLevel]['count'] = 0;
                }
                $examCountryLevelSum[$keyCountryLevel]['sum'] += $score['sum'];
                $examCountryLevelSum[$keyCountryLevel]['count'] += $score['total'];

            }// done

            //added code for average exam score for all courses at country level
            $examCountryLevelAvg = array();
            foreach ($examCountryLevelSum as $key => $value) {
                $avgCountryLevel = $value['sum'] / $value['count'];
                $examId = explode("-", $key)[1];
                if ($allExams[$examId]['type'] == "Grades") {
                    $range = 1;
                } else {
                    $range = $allExams[$examId]['range'];
                    if ($allExams[$examId]['range'] >= 1) {
                        $avgCountryLevel = round($avgCountryLevel);
                    }
                }
                $avgCountryLevel = $this->_getNearestValueByRangeInterval($avgCountryLevel, $range);
                if ($avgCountryLevel > 0) {
                    $abroadListingCache->storeAllCoursesAverageExamScores($key, array('avg' => $avgCountryLevel, 'count' => $value['count']));
                }
            }

        }

        /*
         * function to get nearest numeric value based on range interval
         * Note: currently this function is used for getting closest valid exam score
         * @param: value, range interval
         * E.g. value is 223 & range interval is 10, then 220 would be returned
         */
        private function _getNearestValueByRangeInterval($value, $rangeInterval)
        {
            if ($rangeInterval < 1) // if range interval is like 0.5
            {
                $remainder = fmod($value, $rangeInterval);
            } else {
                $remainder = $value % $rangeInterval;
            }
            if ($remainder < ($rangeInterval / 2)) {
                $returnValue = $value - $remainder;
            } else {
                $returnValue = $value + ($rangeInterval - $remainder);
            }
            return $returnValue;
        }

        private function _responseCountWithActionTypeOneTime()
        {

            $finalArray = array();
            $startYear = 2008;
            $endYear = 2016;
            for ($x = $startYear; $x <= $endYear; $x++) {
                $startTime = $x . '-01-01 00:00:00';
                $endTime = $x . '-03-31 23:59:59';
                $finalArray[] = $this->abroadListingCronModel->getCoursesWithResposeCountByTime($startTime, $endTime);

                $startTime = $x . '-04-01 00:00:00';
                $endTime = $x . '-06-30 23:59:59';
                $finalArray[] = $this->abroadListingCronModel->getCoursesWithResposeCountByTime($startTime, $endTime);

                $startTime = $x . '-07-01 00:00:00';
                $endTime = $x . '-09-30 23:59:59';
                $finalArray[] = $this->abroadListingCronModel->getCoursesWithResposeCountByTime($startTime, $endTime);

                $startTime = $x . '-10-01 00:00:00';
                $endTime = $x . '-12-31 23:59:59';
                $finalArray[] = $this->abroadListingCronModel->getCoursesWithResposeCountByTime($startTime, $endTime);
            }
            return $finalArray;
        }

        private function _responseCountWithActionTypeOneDay()
        {
            $finalArray = array();
            $date = date('Y-m-d', strtotime('-1 day'));
            $startTime = $date . ' 00:00:00';
            $endTime = $date . ' 23:59:59';

            $timeResult = $this->abroadListingCronModel->getLastDateWhenResponseCountDetailsTableUpdated();
            if (count($timeResult) > 0) {
                $timeResult = reset($timeResult);
                $timeResult = date('Y-m-d', strtotime($timeResult['lastDate']));
                $startTime = $timeResult . ' 00:00:00';
            }

            $finalArray[] = $this->abroadListingCronModel->getCoursesWithResposeCountByTime($startTime, $endTime);
            return $finalArray;
        }

        private function _createSubmitDateData($finalArray)
        {

            $courseIds = array();
            foreach ($finalArray as $key => $valArr) {
                foreach ($valArr as $valArrKey => $val) {
                    $courseIds[] = $val['courseId'];
                }
            }
            $submitDateData = array();
            if (count($courseIds) > 0) {
                $rawDateData = $this->abroadListingCronModel->getCourseSubmitDateByCourseIds($courseIds);
                foreach ($rawDateData as $rawRow) {
                    foreach ($rawRow as $row) {
                        $submitDateData[$row['listing_type_id']] = date('Y-m-d', strtotime($row['submit_date']));
                    }
                }
            }
            return $submitDateData;
        }

        private function _checkActionStatusForPopularityIndex($actionType)
        {
            $popularityFlag = true;
            $actionType = strtoupper($actionType);

            if (in_array($actionType, array('VIEWED_LISTING', 'VIEWED_LISTING_SA_MOBILE', 'MOB_VIEWED_LISTING_PRE_REG', 'VIEWED_LISTING_PRE_REG'))) {
                $popularityFlag = false;
            } elseif (strpos($actionType, 'CLIENT') !== false) {
                $popularityFlag = false;
            }
            return $popularityFlag;
        }

        public function populateCourseResponseCountWithActionType($dailyFlag = true)
        {
            $finalArray = array();
            if ($dailyFlag) {
                error_log("Course Response Count: daily start " . date("H:i:s"));
                $finalArray = $this->_responseCountWithActionTypeOneDay();
            } else {
                error_log("Course Response Count: one time activity start" . date("H:i:s"));
                $finalArray = $this->_responseCountWithActionTypeOneTime();
            }
            error_log("Course Response Count: count by action fetching done" . date("H:i:s"));

            $submitDateData = $this->_createSubmitDateData($finalArray);
            $courseIdActionTypeArr = array();
            foreach ($finalArray as $key => $valArr) {
                foreach ($valArr as $valArrKey => $val) {

                    $arrkey = $val['courseId'] . "-" . $val['action'];
                    if (key_exists($arrkey, $courseIdActionTypeArr)) {
                        $courseIdActionTypeArr[$arrkey]['count'] = $courseIdActionTypeArr[$arrkey]['count'] + $val['responseCount'];
                    } else {
                        $courseIdActionTypeArr[$arrkey]['count'] = $val['responseCount'];
                    }
                    $courseIdActionTypeArr[$arrkey]['courseId'] = $val['courseId'];
                    $courseIdActionTypeArr[$arrkey]['action'] = $val['action'];
                    $courseIdActionTypeArr[$arrkey]['universityId'] = $val['university_id'];
                    $courseIdActionTypeArr[$arrkey]['coursePostedDate'] = $submitDateData[$val['courseId']];
                    $courseIdActionTypeArr[$arrkey]['popularityFlag'] = $this->_checkActionStatusForPopularityIndex($val['action']);

                }
            }
            unset($finalArray);
            unset($submitDateData);
            error_log("Course Response Count: insert data array prepared" . date("H:i:s"));
            $this->abroadListingCronModel->saveCoursesResposeCountByAction($courseIdActionTypeArr);
        }


        public function getCourseIdAndPackTypeFromDb()
        {
            $result = $this->abroadListingCronModel->getCourseIdAndPackTypeFromDb();
            $res = array();
            foreach ($result as $value) {
                $res[$value['course_id']] = $value;
            }
            return $res;
        }

        public function extractCourseIds($dataFromdb)
        {
            $courseIds = array();
            foreach ($dataFromdb as $key => $value) {
                array_push($courseIds, intval($value['course_id']));
            }
            return $courseIds;
        }

        public function updateDataInCityVideosTable()
        {
            $result = $this->abroadListingCronModel->getCityVideosUrls();
            _p($result);
            foreach ($result as $key => $row) {
                $videoUrls = array();
//                array_push($videoUrls, str_replace("youtube.com/v/", "youtube.com/watch?v=", $row['videoUrl']));
                array_push($videoUrls,$row['videoUrl']);

                _p($videoUrls);
//            die;

                $this->CI->load->library('upload_client');
                $uploadClient = new Upload_client();
                $appId = 1;
                $mediaDataType = "ytvideo";
                $newData = array();
                $FILES = $videoUrls;
                $upload_forms = $uploadClient->uploadFile($appId, $mediaDataType, $FILES, array(), -1, 'city', 'videoUrls');
                if (is_array($upload_forms)) {
                    if ($upload_forms['status'] == 1) {
                        for ($k = 0; $k < $upload_forms['max']; $k++) {
                            $newData['thumbUrl'][$k] = $upload_forms[$k]['thumburl'];
                            $newData['videoUrl'][$k] = $upload_forms[$k]['imageurl'];
                            $newData['mediaId'][$k] = $upload_forms[$k]['mediaid'];
                        }

                        //update data
                        $postData = array();
                        $primaryIds = array();
                        if (isset($newData['thumbUrl'][0])) {
                            array_push($primaryIds, $row['id']);
                            $postData = array(
                                'cityId' => $row['cityId'],
                                'mediaId' => $newData['mediaId'][0],
                                'videoTitle' => $row['videoTitle'],
                                'videoUrl' => $newData['videoUrl'][0],
                                'thumbUrl' => $newData['thumbUrl'][0],
                                'status' => ENT_SA_PRE_LIVE_STATUS,
                                'addedAt' => date('Y-m-d H:i:s')
                            );
                        }
                        $this->abroadListingCronModel->updateCityVideosData($primaryIds);
                        $this->abroadListingCronModel->populateCityVideosData($postData);
                    }
                } else {
                    error_log("SACityVideosUrlUpdateCron for cityId = " . $row['cityId'] . " " . $upload_forms);
                }

            }
            return "successful executed";
        }
    }
?>

