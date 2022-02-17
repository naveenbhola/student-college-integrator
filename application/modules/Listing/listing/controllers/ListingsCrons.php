<?php

class ListingsCrons extends MX_Controller
{
    private $instituteRepository;
    private $courseRepository;
    private $bookingStartDate = '2014-01-15'; // Booking Start date for Subscription : Transaction Start Date
    //private $bookingStartDate = '2008-11-04'; // For Testing
    
    private $skipSubscriptionToExpire = array('00000074072', '00000075822', '00000075823', '00000082054', '00000044732', '00000082695', '00000073784', '00000073783', '00000074074', '00000049070', '00000064967', '00000076153', '00000035983', '00000082159', '00000094434', '00000064951', '00000064945', '00000044629', '00000065036', '00000065046', '00000098542', '000000101188', '000000100794', '000000100793', '00000070243', '000000100798', '00000024762', '00000050287', '00000026526', '00000057042', '00000072076', '00000082846', '00000082848', '00000082851', '00000082850', '000000105483', '00000087953', '00000035269', '00000096416', '00000069706');
    private $logData;
    private $abroadCourseRepository;
    
    function _init()
    {
        // load the builder
        $this->load->builder("nationalInstitute/InstituteBuilder");
        $this->load->builder("nationalCourse/CourseBuilder");
        $instituteBuilder          = new InstituteBuilder();
        $courseBuilder             = new CourseBuilder();
        $this->instituteRepository = $instituteBuilder->getInstituteRepository();
        $this->courseRepository    = $courseBuilder->getCourseRepository();
        // get institute repository with all dependencies loaded
        
        $this->load->builder('ListingBuilder', 'listing');
        $listingBuilder               = new ListingBuilder;
        $this->abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
        //$this->instituteRepository = $listingBuilder->getInstituteRepository();
        //$this->courseRepository = $listingBuilder->getCourseRepository();
    }
    
    /*
     *
     *  Cron to handle cases with Zero Subscription End date in Listings Main with Paid Pack Type
     *
     */
    function expirePaidListingHavingZeroExpiryDate()
    {
        $this->validateCron(); // prevent browser access
        $this->logData['startTime']     = microtime(true);
        $this->logData['CronStartTime'] = date("Y-m-d H:i:s");
        $this->logData['CronType']      = "ZeroExpiryDate";
        $this->_init();
        
        $expiringDataResultSet = $this->getCoursesHavingZeroExpiryDate();
        
        foreach ($expiringDataResultSet as $expiringData) {
            $expiringSubscription[] = $expiringData['subscriptionId'];
        }
        $expiringSubscription = array_unique($expiringSubscription);
        
        $dateToCheckFrom                         = $this->_getDate(-1);
        $sumsmodel                               = $this->load->model('sumsmodel', 'sums');
        $filteredSubscriptionIds                 = $sumsmodel->filterSubscriptionWithZeroExpiryDate($expiringSubscription, $dateToCheckFrom);
        $this->logData['skippedSubscriptionIds'] = array_intersect($filteredSubscriptionIds, $this->skipSubscriptionToExpire);
        
        $filteredSubscriptionIds = array_diff($filteredSubscriptionIds, $this->skipSubscriptionToExpire);
        
        foreach ($expiringDataResultSet as $expiringData) {
            if (in_array($expiringData['subscriptionId'], $filteredSubscriptionIds)) {
                $expiringCourses[] = $expiringData['listing_type_id'];
            } else {
                if (in_array($expiringData['subscriptionId'], $this->skipSubscriptionToExpire)) {
                    $this->logData['filteredCourses_ManuallySkipped'][] = array(
                        'courseId' => $expiringData['listing_type_id'],
                        'subscriptionId' => $expiringData['subscriptionId']
                    );
                } else {
                    $this->logData['filteredCourses'][] = array(
                        'courseId' => $expiringData['listing_type_id'],
                        'subscriptionId' => $expiringData['subscriptionId']
                    );
                }
                
            }
        }
        
        if (count($expiringCourses) < 1) {
            $coursesToExpire = 'NO_COURSES_FOUND';
        } else {
            $coursesToExpire = $this->courseRepository->findMultiple($expiringCourses);
        }
        
        $this->_expiryAndNotifyCourseListings($coursesToExpire);
        
    }
    
    /*
     * Fetch listings to expire with expiry date zero and pack type paid in listings_main table
     */
    
    function getCoursesHavingZeroExpiryDate()
    {
        $expiringDataResultSet = $this->courseRepository->getCoursesHavingZeroExpiryDate();
        
        $this->logData['coursesToExpire'] = $expiringDataResultSet;
        
        foreach ($expiringDataResultSet as $key => $value) {
            if ($value['subscriptionId'] == 0) {
                $coursesHavingZeroSubscriptionId[] = $value;
                unset($expiringDataResultSet[$key]);
            }
        }
        $this->logData['coursesHavingZeroSubscriptionId'] = $coursesHavingZeroSubscriptionId;
        return $expiringDataResultSet;
    }
    
    /*  Expire Coruses for past N number of Days.
     *  Function that gets listings expire after their expiry date is over and then send notifications
     *  to their Clients and Sales Person.
     */
    // $n would specify days interval from yesterday
    
    function expirePaidListingsForPastNDays($n = -7)
    {
        $this->validateCron(); // prevent browser access
        $this->logData['startTime']        = microtime(true);
        $this->logData['CronStartTime']    = date("Y-m-d H:i:s");
        $this->logData['bookingStartDate'] = $this->bookingStartDate;
        $this->_init();
        $dateToCheckFrom                        = $this->_getDate(-1);
        $dateToCheckUptoInPast                  = $this->_getDate($n);
        $this->logData['dateToCheckFrom']       = $dateToCheckFrom;
        $this->logData['dateToCheckUptoInPast'] = $dateToCheckUptoInPast;
        $coursesToExpire                        = $this->getExpiryCoursesInfoForAnInterval($dateToCheckFrom, $dateToCheckUptoInPast);
        $this->_expiryAndNotifyCourseListings($coursesToExpire);
    }
    
    
    /*
     *  Function that gets listings expire after their expiry date is over and then send notifications
     *  to their Clients and Sales Person.
     */
    function expirePaidListingsAndNotify()
    {
        $this->validateCron(); // prevent browser access
        ini_set("memory_limit", "1000M");
        $this->logData['startTime']        = microtime(true);
        $this->logData['CronStartTime']    = date("Y-m-d H:i:s");
        $this->logData['bookingStartDate'] = $this->bookingStartDate;
        $this->_init();
        
        
        $dateToCheckFor = $this->_getDate(-1); // Get yesterday's date.
        // $dateToCheckFor = '2008-11-05';$dateToCheckFor = '2016-01-01';
        
        $this->logData['dateToCheckFor'] = $dateToCheckFor;
        $coursesToExpire                 = $this->getExpiryCoursesInfoByDate($dateToCheckFor);
        $mailContent                     = array();
        if ($coursesToExpire != 'NO_COURSES_FOUND') {
            $mailContent['coursesPickedToExpire'] = $this->getCoursesPickedToExpire($coursesToExpire);
            $this->_expiryAndNotifyCourseListings($coursesToExpire);
        } else {
            $mailContent['coursesPickedToExpire'] = "";
        }
        //sleep for 2 seconds
        sleep(2);
        $mailContent['coursesSuccessfullyExpired'] = $this->getCoursesSuccessfullyExpired();
        // courses which could not get expired.
        $dateToCheckUpto                           = $dateToCheckFor;
        $dateToCheckFrom                           = $this->bookingStartDate;
        
        $mailContent['PendingCourses']           = $this->getCoursesFailedToExpire($dateToCheckFrom, $dateToCheckUpto);
        $fileNameForDailySubcriptionExpireReport = "shikshaDailySubscriptionExpiringReport.xlsx";
        //could be in a try-catch block
        if ($this->getXLSForMail($mailContent, $fileNameForDailySubcriptionExpireReport) == "Report Generated") {
            if ($this->emailDailySubscriptionExpireReport($fileNameForDailySubcriptionExpireReport, $mailContent)) {
                echo "Emails Sent Succesfully";
            } else {
                echo "There was an error in Sending Mails";
            }
        } else {
            // do something like error log
            echo "Report Sheet Could Not be created";
        }
        
    }
    
    function emailDailySubscriptionExpireReport($fileNameForDailySubcriptionExpireReport, $mailContent)
    {
        
        $mailSubject = "Courses' subscription expiry report for " . $this->logData['dateToCheckFor'] . " .";
        /*$mailReceivers        =  array("1"=>array("to"=>"ashujindal92@gmail.com",
        "cc"=>"",
        "bcc"=>"")
        );
        */
        
        $mailReceivers = array(
            "1" => array(
                "to" => "satech@shiksha.com",
                "cc" => "listingstech@shiksha.com",
                "bcc" => "abhinav.k@shiksha.com"
            ),
            "2" => array(
                "to" => "vikas.k@shiksha.com",
                "cc" => "pranjul.raizada@shiksha.com",
                "bcc" => "aditya.roshan@shiksha.com"
            ),
            "3" => array(
                "to" => "ankur.gupta@shiksha.com",
                "cc" => "karundeep.gill@shiksha.com",
                "bcc" => "jahangeer.alam@shiksha.com"
            ),
            "4" => array(
                "to" => "neha.maurya@shiksha.com",
                "cc" => "azhar.ali@shiksha.com",
                "bcc" => "jahangeer.alam@shiksha.com"
            )
        );
        
        //$mailbody              = 'Hi team,<br>Please find attached the report containing data: "Listings Picked for downgradation yesterday","Listings that were successfully downgraded yesterday","Listings that have PAST expiry date but still are paid".<br><br>Regards,<br>Shiksha Technology Team.';
        $mailbody              = '<html>
              <body>
              <p>Hi team,<br><br>Please find below the listings expiry data report:</p>
               <table border="1" cellpadding="0" cellspacing="0">
                 <tr>
                   <th></th><th>Abroad Listings</th><th>Domestic Listings</th>
                 </tr>
                 <tr>
                   <td>Listings Picked for downgradation</td><td>' . $mailContent['coursesPickedToExpire']['abroadCourses'] . '</td><td>' . $mailContent['coursesPickedToExpire']['domesticCourses'] . '</td>
                 </tr>
                 <tr>
                   <td>Listings that were successfully downgraded</td><td>' . $mailContent['coursesSuccessfullyExpired']['abroadCourses'] . '</td><td>' . $mailContent['coursesSuccessfullyExpired']['domesticCourses'] . '</td>
                 </tr>
              <tr>
                   <td>Listings that have PAST expiry date but still are paid</td><td>' . $mailContent['PendingCourses']['abroadCourses'] . '</td><td>' . $mailContent['PendingCourses']['domesticCourses'] . '</td>
                 </tr>
               </table><p>Please refer the attached report for more details.</p><p>Regards,<br>Shiksha Team.</p>
               </body>
              </html>
              ';
        //$mailAttachmentContent = file_get_contents("/tmp/".$fileNameForDailySubcriptionExpireReport);
        $emailFrom             = "studyabroad@shiksha.com";
        $flagForSuccessOFMails = 1;
        foreach ($mailReceivers as $key => $receivers) {
            $this->load->library('email');
            $this->email->clear(TRUE);
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->email->from($emailFrom, 'shiksha');
            $this->email->to($receivers['to']);
            $this->email->cc($receivers['cc']);
            $this->email->bcc($receivers['bcc']);
            $this->email->subject($mailSubject);
            $this->email->message($mailbody);
            $this->email->attach("/tmp/" . $fileNameForDailySubcriptionExpireReport);
            if (!$this->email->send()) {
                $flagForSuccessOFMails = 0;
            }
        }
        return $flagForSuccessOFMails;
    }
    
    
    /*
     * Generates an excel sheet of the report and stores it in tmp directory with name passed as an argument
     */
    function getXLSForMail($mailContent, $fileNameForDailySubcriptionExpireReport)
    {
        
        $this->load->library('common/PHPExcel');
        $this->load->library('common/PHPExcel/IOFactory');
        $objPHPExcel = new PHPExcel();
        // it should have write permission
        define('PCLZIP_TEMPORARY_DIR', '/tmp/'); // required for temporary data the PHPExcel_IOFactory uses for writing files
        $objWriter   = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $sheetNumber = 0;
        
        foreach ($mailContent as $type => $courses) {
            $objWorkSheet = $objPHPExcel->createSheet($sheetNumber);
            $objPHPExcel->setActiveSheetIndex($sheetNumber);
            $objWorkSheet->setTitle($type);
            $headers          = array(
                'courseId',
                'clientId',
                'subscriptionId',
                'expiryDate',
                'source',
                'packType'
            );
            $rowNumber        = 1;
            $firstRowColumnNo = "A";
            foreach ($headers as $columnNumber => $headerValue) {
                $objPHPExcel->getActiveSheet()->getStyle($firstRowColumnNo . "1")->getFont()->setBold(true);
                $firstRowColumnNo++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnNumber, $rowNumber, $headerValue);
                //           $objPHPExcel->getActiveSheet()->getStyle($columnNumber,$rowNumber)->getFont()->setBold(true);
            }
            $rowNumber++;
            $noOfCourses = count($courses);
            foreach ($courses as $courseId => $course) {
                //this is a check because two entries are there containing count of the abroadCourses and domesticCourses
                if ($courseId == 'abroadCourses' || $courseId == 'domesticCourses')
                    continue;
                $rowNumber      = $rowNumber + $i;
                $clientId       = $course['clientId'];
                $subscriptionId = $course['subscriptionId'];
                $expiryDate     = $course['expiryDate'];
                $source         = $course['source'];
                $packType       = $course['packType'];
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowNumber, $courseId);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rowNumber, $clientId);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $rowNumber, $subscriptionId);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $rowNumber, $expiryDate);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $rowNumber, $source);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $rowNumber, $packType);
                $rowNumber++;
            }
            
            $sheetNumber++;
        }
        //$objPHPExcel->save("/tmp/expireCoursesMails/attachement.xlsx");
        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        //$path = "/tmp/".$fileNameForDailySubcriptionExpireReport;
        $objWriter->save("/tmp/" . "" . $fileNameForDailySubcriptionExpireReport);
        return "Report Generated";
        //Could use some sort of error handling like following
        /*
        if($objWriter->save("/tmp/"."".$fileNameForDailySubcriptionExpireReport)){
        return "Report Generated";
        }
        else
        return "Failed to Create File";
        */
    }
    /*
     * This function is required because we need to read updated values from masterDB
     */
    function getUpdatedDataForCourses($coursesExpiredSuccessfully)
    {
        $abroadcmsmodel = $this->load->model('listingPosting/abroadcmsmodel');
        
        $courseUpdatedDataFromDB = $abroadcmsmodel->getUpdatedDataForCourses($coursesExpiredSuccessfully);
        $courseUpdatedData       = array();
        foreach ($courseUpdatedDataFromDB as $key => $value) {
            $courseId                     = $value['courseId'];
            $courseUpdatedData[$courseId] = $value;
        }
        
        return $courseUpdatedData;
    }
    
    /*
     *  Returns mail content for courses whose subscription expired current day and expiring of them was successful.
     */
    function getCoursesSuccessfullyExpired()
    {
        
        $coursesExpiredSuccessfully = $this->logData['coursesExpiredSuccessfully'];
        $expiringCourses            = array();
        foreach ($coursesExpiredSuccessfully as $expiringData) {
            if (isset($expiringData['listing_type_id'])) {
                $expiringCourses[] = $expiringData['listing_type_id'];
            }
        }
        $mailContent = array();
        if (count($expiringCourses) > 0) {
            $courseUpdatedData          = $this->getUpdatedDataForCourses($expiringCourses);
            $coursesExpiredSuccessfully = $this->_getCourseObjectByCourseIds($expiringCourses);
            $mailContent                = $this->getMailContent($coursesExpiredSuccessfully, $courseUpdatedData);
        }
        return $mailContent;
    }
    /*
     * Picks the courses whose subscription date has expired but have
     * failed to expire in the database and then returns mailContent of them.
     * Input is the dates interval, between which we have to pick.
     */
    function getCoursesFailedToExpire($dateToCheckFrom, $dateToCheckUpto)
    {
        if ($dateToCheckFrom == '' || $dateToCheckUpto == '') {
            return;
        }
        $coursesFailedToExpire                          = $this->courseRepository->findExpiringCoursesForAnInterval($dateToCheckUpto, $dateToCheckFrom, 'PAID'); // $dateToCheckUpto and $dateToCheckFrom are passed in reverse oreder because the function demands them in reverse order.
        //$coursesFailedToExpire   = $this->_filterCoursesToExpire($coursesFailedToExpire); //filter is removed because we need all types of failed courses
        $this->logData['courseToSubscriptionIdMapping'] = array();
        $expiringCourses                                = array();
        foreach ($coursesFailedToExpire as $expiringData) {
            $this->logData['courseToSubscriptionIdMapping'][$expiringData['listing_type_id']] = $expiringData['subscriptionId'];
            $expiringCourses[]                                                                = $expiringData['listing_type_id'];
        }
        $mailContent = array();
        if (count($expiringCourses) > 0) {
            $coursesFailedToExpire = $this->_getCourseObjectByCourseIds($expiringCourses);
            $mailContent           = $this->getMailContent($coursesFailedToExpire);
        }
        return $mailContent;
    }
    
    /*
     * It returns mailContent of the courses picked for expiration as Subscription has expired for those courses.
     * Takes input as objects of courses which have been picked for expiration.
     */
    function getCoursesPickedToExpire($coursesToExpire)
    {
        
        if ($coursesToExpire == 'NO_COURSES_FOUND') {
            //$this->_sendEmailNotificationToShikshaInternals();  <----- Not sure yet.
            return;
        }
        $mailContent = $this->getMailContent($coursesToExpire);
        return $mailContent;
        
    }
    
    /*
     * A function to prepare content of the mail to send from coursedata.
     */
    function getMailContent($courseData, $courseUpdatedData = array())
    {
        $abroadCourses   = 0;
        $domesticCourses = 0;
        if ($courseData == 'NO_COURSES_FOUND') {
            return;
        }
        $mailContent = array();
        foreach ($courseData as $key => $course) {
            
            $currentCourseId                                 = $key;
            $mailContent[$currentCourseId]                   = array();
            $mailContent[$currentCourseId]['courseId']       = $currentCourseId;
            $mailContent[$currentCourseId]['clientId']       = $course->getClientId();
            $mailContent[$currentCourseId]['subscriptionId'] = ($courseUpdatedData[$currentCourseId]['subscriptionId'] != "") ? $courseUpdatedData[$currentCourseId]['subscriptionId'] : $this->logData['courseToSubscriptionIdMapping'][$currentCourseId];
            $mailContent[$currentCourseId]['expiryDate']     = ($courseUpdatedData[$currentCourseId]['expiry_date'] != "") ? $courseUpdatedData[$currentCourseId]['expiry_date'] : $course->getExpiryDate();
            $mailContent[$currentCourseId]['packType']       = ($courseUpdatedData[$currentCourseId]['pack_type'] != "") ? $courseUpdatedData[$currentCourseId]['pack_type'] : $course->getCoursePackType();
            if ($course instanceof AbroadCourse) {
                $mailContent[$currentCourseId]['source'] = "AbroadCourse";
                $abroadCourses++;
            } else if ($course instanceof Course) {
                $mailContent[$currentCourseId]['source'] = "NationalCourse";
                $domesticCourses++;
            }
        }
        
        $mailContent['abroadCourses']   = $abroadCourses;
        $mailContent['domesticCourses'] = $domesticCourses;
        return $mailContent;
    }
    
    function _expiryAndNotifyCourseListings($coursesToExpire)
    {
        $this->logData['coursesExpiredSuccessfully'] = array();
        if ($coursesToExpire == 'NO_COURSES_FOUND') {
            $this->_sendEmailNotificationToShikshaInternals();
            die("NO_COURSES_FOUND_TO_EXPIRE");
        }
        
        $courseInfoArray      = array();
        $clientIdsArray       = array();
        $clientsListingsArray = array();
        $nationalCourses = array();
        
        //New Variables for Abroad
        $abroadCourseInfoArray      = array();
        $abroadClientIdsArray       = array();
        $abroadClientsListingsArray = array();
        $abroadCoursesToExpire      = array();
        
        $nationalCoursesToExpire = array();
        
        foreach ($coursesToExpire as $key => $course) {
            if ($course instanceof AbroadCourse) {
                $abroadCourseInfoArray[$course->getId()]['courseName']    = $course->getName();
                $abroadCourseInfoArray[$course->getId()]['instituteName'] = htmlentities($course->getUniversityName());
                $abroadCourseInfoArray[$course->getId()]['expiryDate']    = $course->getExpiryDate();
                $abroadCourseInfoArray[$course->getId()]['seoUrl']        = $course->getURL();
                
                $abroadClientIdsArray[]                               = $course->getClientId();
                $abroadClientsListingsArray[$course->getClientId()][] = $course->getId();
                $abroadCoursesToExpire[$key]                          = $course;
                
                $this->logData['courseExpired'][] = array(
                    'courseId' => $course->getId(),
                    'expiryDate' => $course->getExpiryDate(),
                    'type' => 'AbroadCourse'
                );
            } elseif ($course instanceof Course) {
                $courseInfoArray[$course->getId()]['courseName']    = $course->getName();
                $courseInfoArray[$course->getId()]['instituteName'] = htmlentities($course->getInstituteName());
                $courseInfoArray[$course->getId()]['expiryDate']    = $course->getExpiryDate();
                $courseInfoArray[$course->getId()]['seoUrl']        = $course->getURL();
                
                $clientIdsArray[]                               = $course->getClientId();
                $clientsListingsArray[$course->getClientId()][] = $course->getId();
                $nationalCoursesToExpire[$key]                  = $course;

                $courseId = $course->getId();
                $nationalCourses[$courseId] = (int) $courseId;
                
                $this->logData['courseExpired'][] = array(
                    'courseId' => $course->getId(),
                    'expiryDate' => $course->getExpiryDate(),
                    'type' => 'Course'
                );
            }
        }
        
        $clientIdsArray       = array_unique($clientIdsArray);
        $abroadClientIdsArray = array_unique($abroadClientIdsArray);
        
        /*
         *  Lets downgrade all these Courses now..
         *  This will be performed for national and abroad separately
         */
        //_p($nationalCoursesToExpire);
        //_p($abroadCoursesToExpire);die;
        // for national
        
        if (count($clientsListingsArray)) {
            //expire National Courses
            
            $this->expireCourses($nationalCoursesToExpire, $clientsListingsArray);
            
            $rdcLib = $this->load->library('enterprise/ResponseDeliveryCriteriaLib');
            $rdcLib->disableResponseCriteria($nationalCourses);
            
            // National
            $clientInfo       = $this->_getClientUsersInfo($clientIdsArray);
            $salesPersonsInfo = $this->_getSalesPersonInfoForClients($clientIdsArray);
            
            /*
             *  Now lets send the email to the Client Users first..
             */
            $mailInfo['mailSubject'] = "Your listings on Shiksha have expired.";
            $mailInfo['mailContent'] = $this->load->view('listing/listingsCrons/expiredListingsClientNotification', '', true);
            
            // Sending mail to National Clients
            $this->_sendEmailToClientUsers($clientsListingsArray, $courseInfoArray, $clientInfo, $salesPersonsInfo, $mailInfo, 'national');
            
            /*
             *  Now lets send the email to the Sales Persons of these Clients..
             */
            $mailInfo['mailSubject'] = "Your Clients' listings subscription on Shiksha have expired.";
            $mailInfo['mailContent'] = $this->load->view('listing/listingsCrons/expiredListingsSalesPersonNotification', '', true);
            
            // Sending mail to National Sales Persons
            $this->_sendEmailToSalesPersons($clientsListingsArray, $salesPersonsInfo, $courseInfoArray, $clientInfo, $mailInfo, 'national');
            
            
        }
        
        // for abroad
        if (count($abroadClientsListingsArray)) {
            // Expire Abroad Courses
            $this->expireCourses($abroadCoursesToExpire, $abroadClientsListingsArray);
            // Abroad
            $abroadClientInfo       = $this->_getClientUsersInfo($abroadClientIdsArray);
            $abroadSalesPersonsInfo = $this->_getSalesPersonInfoForClients($abroadClientIdsArray);
            
            /*
             *  Now lets send the email to the Client Users first..
             */
            $mailInfo['mailSubject'] = "Your listings on Shiksha have expired.";
            $mailInfo['mailContent'] = $this->load->view('listing/listingsCrons/expiredListingsClientNotification', '', true);
            
            // Sending mail to Abroad Clients
            $this->_sendEmailToClientUsers($abroadClientsListingsArray, $abroadCourseInfoArray, $abroadClientInfo, $abroadSalesPersonsInfo, $mailInfo, 'abroad');
            
            /*
             *  Now lets send the email to the Sales Persons of these Clients..
             */
            $mailInfo['mailSubject'] = "Your Clients' listings subscription on Shiksha have expired.";
            $mailInfo['mailContent'] = $this->load->view('listing/listingsCrons/expiredListingsSalesPersonNotification', '', true);
            
            // Sending mail to National Sales Persons
            $this->_sendEmailToSalesPersons($abroadClientsListingsArray, $abroadSalesPersonsInfo, $abroadCourseInfoArray, $abroadClientInfo, $mailInfo, 'abroad');
            
            
        }
        $this->_sendEmailNotificationToShikshaInternals();
        echo "<BR> Thanks, All Done Successfully.";
        
    }
    
    function _sendEmailNotificationToShikshaInternals()
    {
        $this->logData['CronEndTime']          = date("Y-m-d H:i:s");
        $this->logData['CronPeakMemoryUsages'] = memory_get_peak_usage(TRUE) / (1024 * 1024) . "  MB";
        $this->logData['CronExecutionTime']    = ((microtime(true) - $this->logData['startTime']) / 60) . " Sec";
        $this->emailIds                        = array(
            0 => array(
                'cc' => 'sukhdeep.kaur@99acres.com',
                'bcc' => 'muppuri.hemanth@shiksha.com',
                'to' => 'yaseen@shiksha.com'
            )
        );
        
        if (empty($this->logData['coursesToExpire'])) {
            $status = "SUCCESS - NO Course Found to downgrade ";
        } elseif (!empty($this->logData['filteredCourses'])) {
            $status = "FAILED - Some Courses NOT downgraded ";
        } else {
            $status = " SUCCESS - All courses downgraded ";
        }
        
        $displayData['logData'] = $this->logData;
        $alerts_client          = $this->load->library('alerts_client');
        
        if (!empty($this->logData['dateToCheckFor'])) { // Daily cron for Listing downgrade
            $subject = "Listing Downgrade CRON STATUS : " . $status . " { Exipry Date on :" . $this->logData['dateToCheckFor'] . " }";
        } else if (!empty($this->logData['CronType']) && $this->logData['CronType'] == "ZeroExpiryDate") {
            if (count($this->logData['coursesToExpire']) == (count($this->logData['filteredCourses_ManuallySkipped']) + count($this->logData['filteredCourses']) + count($this->logData['coursesHavingZeroSubscriptionId']))) {
                $status = "SUCCESS - NO Course Found to downgrade ";
            } else {
                $status = "SUCCESS - Some Courses downgraded  ";
            }
            
            $dateForName = $this->_getDate(-1);
            $subject     = "ZeroExpiryDate :: Listing Downgrade CRON STATUS : " . $status . " { Exipry Date on :" . $dateForName . " }";
            $dataToPrint = print_r($this->logData, true);
            error_log($dataToPrint, 3, '/tmp/ZeroExpiryDateDowngradeCron_' . $dateForName);
        } else { // Weekly or N days interval downgarde Cron
            $subject = "Listing Downgrade CRON STATUS : " . $status . " { Exipry Date between :" . $this->logData['dateToCheckUptoInPast'] . " & " . $this->logData['dateToCheckFrom'] . " }";
        }
        
        if (!empty($this->logData['CourseId'])) { //Cron for single Course Downgrade
            $subject = "Listing Downgrade CRON STATUS : " . $status . " { Course expired : " . $this->logData['CourseId'] . " }";
            _p($this->logData);
        }
        
        $content = $this->load->view('listing/listingsCrons/expiredListingsCronShikshaInternalNotification', $displayData, true);
        foreach ($this->emailIds as $email) {
            $flag = $alerts_client->externalQueueAdd("12", ADMIN_EMAIL, $email['to'], $subject, $content, "html", "0000-00-00 00:00:00", 'n', array(), $email['cc'], $email['bcc']);
        }
        
    }
    
    function expireACourse($courseId)
    {
        $userData = $this->checkUserValidation();
        if (isset($userData[0]['usergroup']) && $userData[0]['usergroup'] == "cms") {
            $this->_init();
            $this->logData['startTime']        = microtime(true);
            $this->logData['CronStartTime']    = date("Y-m-d H:i:s");
            $this->logData['bookingStartDate'] = $this->bookingStartDate;
            $this->logData['CourseId']         = $courseId;
            $dateToCheckFrom                   = $this->_getDate(-1);
            $expiringDataResultSet             = $this->courseRepository->getCourseInfoToExpire($courseId, $dateToCheckFrom);
            
            if (empty($expiringDataResultSet)) {
                $this->logData['error'] = "Please check if Course is live, it is Paid & its exipry date is before Today .";
            }
            
            $coursesToExpire = $this->_filterCoursesToExpire($expiringDataResultSet);
            $this->_expiryAndNotifyCourseListings($coursesToExpire);
        } else {
            _p("You are not authorised. To downgrade a listing.");
        }
    }
    
    
    function expireCourses($coursesToExpire, $clientsListingsArray)
    {
        
        /*
         *  1. Check for Clients' Subscription and assign if NOT available.
         */
        $clientSubscriptionInfo = array();
        foreach ($clientsListingsArray as $clientId => $listingsArray) {
            $clientSubscriptionInfo[$clientId] = $this->_getClientsBronzeListingSubscriptionInfo($clientId);
            
            // If no BRONZE Listing subscription available to this client/user then provide him the subscription with 1000 quantity.
            if (count($clientSubscriptionInfo[$clientId]) < 1) {
                $this->_provideBronzeListingSubscriptionToClient($clientId);
                $clientSubscriptionInfo[$clientId] = $this->_getClientsBronzeListingSubscriptionInfo($clientId);
            }
        }
        
        
        /*
         *  2. Upgrade the Courses now with BRONZE_LISTINGS_BASE_PRODUCT_ID & Publish it..
         */
        // Commented this section to avoid loading unnecessary libraries as it is not required now
        // Loading required Client Libraries..
        //$clientObj = $this->load->library('listing_client');
        //$entClientObj = $this->load->library('enterprise_client');
        //$clientObjectsArray = array();
        //$clientObjectsArray['listingClientObj'] = $clientObj;
        //$clientObjectsArray['entClientObj'] = $entClientObj;
        
        $abroadPostingLib = $this->load->library('listingPosting/AbroadPostingLib');
        
        $dataArray                             = array();
        // User id of 'edy@shiksha.com' to indicate this update has been done by the Auto Downgrade Listing Script.
        $dataArray['editedBy']                 = 1;
        // We don't have to update the Listing's profile completion % as we are not changing any related info that contributes to the profile.
        $dataArray['updateListingProfileFlag'] = 0;
        foreach ($coursesToExpire as $course) {
            
            if ($course instanceof AbroadCourse) {
                $dataArray['course_id']           = $course->getId();
                $dataArray['courseTitle']         = $course->getName();
                $dataArray['subscription']        = $clientSubscriptionInfo[$course->getClientId()]['SubscriptionId'];
                $dataArray['SubscriptionEndDate'] = $clientSubscriptionInfo[$course->getClientId()]['SubscriptionEndDate'];
                $dataArray['client_id']           = $course->getClientId();
                
                $dataArray['courseCountryFlag'] = 'abroad';
            } else {
                $dataArray['course_id']           = $course->getId();
                $dataArray['courseTitle']         = $course->getName();
                $dataArray['subscription']        = $clientSubscriptionInfo[$course->getClientId()]['SubscriptionId'];
                $dataArray['SubscriptionEndDate'] = $clientSubscriptionInfo[$course->getClientId()]['SubscriptionEndDate'];
                $dataArray['client_id']           = $course->getClientId();
                
                $dataArray['courseCountryFlag'] = 'national';
                $dataArray['addedFrom']         = 'updowncron';
            }
            
            //_p($dataArray);die;
            if ($dataArray['subscription'] != '') {
                
                //logging successfully expired coureses
                $this->logData['coursesExpiredSuccessfully'][] = array(
                    'listing_type_id' => $dataArray['course_id'],
                    'subscriptionId' => $this->logData['courseToSubscriptionIdMapping'][$dataArray['course_id']] // this exsists because courses successfully are only from courses picked for expiration.
                );
                //downgrade course;
                $abroadPostingLib->addPaidClientToCourse($dataArray);
            }
            
            //error_log(print_r($dataArray,true));
            
            // No need to call this upgrade and publish code now as it is being handled in above abroadPostingLib->addPaidClientToCourse($dataArray) call
            // Lets upgrade this Course now..
            //$clientObj->upgradeCourse($dataArray);
            
            // Lets make the draft entries of upgraded Course 'LIVE' to take the changes in effect.
            //$this->publishCourse($dataArray, $clientObjectsArray);
        }
    }
    
    
    function publishCourse($dataArray, $clientObjectsArray)
    {
        $ListingClientObj = $clientObjectsArray['listingClientObj'];
        $entClientObj     = $clientObjectsArray['entClientObj'];
        
        $currentListingArray              = array();
        $currentListingArray[0]['type']   = 'course';
        $currentListingArray[0]['typeId'] = $dataArray['courseId'];
        $currentListingArray[0]['title']  = $dataArray['courseTitle'];
        $indexCourseId                    = $dataArray['courseId'];
        
        $updateStatus = $ListingClientObj->makeListingsLive("1", $currentListingArray);
        
        // We have to Consume the Subscription as we are pretty sure (We have BRONZE_LISTINGS_BASE_PRODUCT_ID alright? ;) )..
        $listingInfoToBeConsumed = array();
        array_push($listingInfoToBeConsumed, array(
            'consumptionFlag' => 'Yes',
            'listing_type' => 'course',
            'listing_type_id' => $dataArray['courseId']
        ));
        
        // Actual Subscription Consumption (Not the Psuedo one OK).
        $audit                 = array();
        $audit['editedBy']     = $dataArray['editedBy'];
        $audit['toConsumeArr'] = $listingInfoToBeConsumed;
        $audit['updateStatus'] = $updateStatus;
        $consumeResponse       = $entClientObj->checkAndConsumeActualSubscription(1, $currentListingArray, $audit);
        
        if ($dataArray['updateListingProfileFlag'] && $updateStatus[0]['version'] > 0) {
            $listingprofilelib = $this->load->library('listing/ListingProfileLib');
            $listingprofilelib->updateProfileCompletion($dataArray['institute_id']);
        }
        modules::run('search/Indexer/addToQueue', $indexCourseId, 'course', 'index');
    }
    
    private function _getClientsBronzeListingSubscriptionInfo($clientId)
    {
        $objSumsProduct         = $this->load->library('sums_product_client');
        $subscriptionDetails    = $objSumsProduct->getAllPseudoSubscriptionsForUser(1, array(
            'userId' => $clientId
        ));
        $clientSubscriptionInfo = array();
        foreach ($subscriptionDetails as $subscriptionId => $subscriptionsData) {
            if ($subscriptionsData['BaseProductId'] == BRONZE_LISTINGS_BASE_PRODUCT_ID && $subscriptionsData['BaseProdPseudoRemainingQuantity'] > 200) {
                $clientSubscriptionInfo['SubscriptionId']      = $subscriptionsData['SubscriptionId'];
                $clientSubscriptionInfo['SubscriptionEndDate'] = $subscriptionsData['SubscriptionEndDate'];
                break;
            }
        }
        
        return $clientSubscriptionInfo;
    }
    
    private function _provideBronzeListingSubscriptionToClient($userId)
    {
        $prodClient = $this->load->library('sums_product_client');
        $result     = $prodClient->getFreeDerivedId(1);
        
        $param['derivedProdId']   = $result['derivedProdId'];
        $param['derivedQuantity'] = 1000;
        $param['clientUserId']    = $userId;
        $param['sumsUserId']      = '2492'; // The user id of the user who generally provide the subscription by login into SUMS.
        $param['subsStartDate']   = date("Y-m-d H:i:s");
        $param['byDowngradeCron'] = 1; //check to handle only for downgrade cron
        
        $subscriptionClient = $this->load->library('Subscription_client');
        $respSubs           = $subscriptionClient->addFreeSubscription(1, $param);
    }
    
    /**
     * API for Adding Free Bronze listings User Ids list for ticket #1550 Bronze listings Subscription
     */
    
    function giveBronzeListingSubscriptionToClient()
    {
        
        $UserIdsListArray = array(
            '0' => '2265680'
        );
        
        foreach ($UserIdsListArray as $key => $clientId) {
            $this->_provideBronzeListingSubscriptionToClient($clientId);
        }
        
        echo "Succesfully Given Bronze Listings ";
    }
    
    /*
     *  Function that finds the listings before X no of days prior to their expiry date and then send notifications
     *  to their Clients and Sales Person.
     */
    function listingsExpiryNotifications()
    {
        $this->validateCron(); // prevent browser access
        $this->_init();
        $notificationAdvanceDayArray = array(
            7,
            14,
            30
        );
        
        foreach ($notificationAdvanceDayArray as $dayValue) {
            
            $dateToCheckFor = $this->_getDate($dayValue);
            //$dateToCheckFor = '2008-11-05';  $dateToCheckFor = '2009-10-13';$dateToCheckFor = '2016-01-01';
            
            $coursesToExpire = $this->getExpiryCoursesInfoByDate($dateToCheckFor);
            if ($coursesToExpire == 'NO_COURSES_FOUND') {
                echo "<BR> No listings expiring on date: " . $dateToCheckFor;
                continue;
            }
            
            $courseInfoArray      = array();
            $clientIdsArray       = array();
            $clientsListingsArray = array();
            
            // New Variables for abroad
            $abroadCourseInfoArray      = array();
            $abroadClientIdsArray       = array();
            $abroadClientsListingsArray = array();
            
            foreach ($coursesToExpire as $course) {
                if ($course instanceof AbroadCourse) {
                    $abroadCourseInfoArray[$course->getId()]['courseName']    = $course->getName();
                    $abroadCourseInfoArray[$course->getId()]['instituteName'] = htmlentities($course->getUniversityName());
                    $abroadCourseInfoArray[$course->getId()]['expiryDate']    = $course->getExpiryDate();
                    $abroadCourseInfoArray[$course->getId()]['seoUrl']        = $course->getURL();
                    
                    $abroadClientIdsArray[]                               = $course->getClientId();
                    $abroadClientsListingsArray[$course->getClientId()][] = $course->getId();
                } elseif ($course instanceof Course) {
                    $courseInfoArray[$course->getId()]['courseName']    = $course->getName();
                    $courseInfoArray[$course->getId()]['instituteName'] = htmlentities($course->getInstituteName());
                    $courseInfoArray[$course->getId()]['expiryDate']    = $course->getExpiryDate();
                    $courseInfoArray[$course->getId()]['seoUrl']        = $course->getURL();
                    
                    $clientIdsArray[]                               = $course->getClientId();
                    $clientsListingsArray[$course->getClientId()][] = $course->getId();
                }
            }
            
            $clientIdsArray       = array_unique($clientIdsArray);
            $abroadClientIdsArray = array_unique($abroadClientIdsArray);
            
            //National Data
            if (!empty($clientIdsArray)) {
                $clientInfo       = $this->_getClientUsersInfo($clientIdsArray);
                $salesPersonsInfo = $this->_getSalesPersonInfoForClients($clientIdsArray);
            }
            
            // Abroad Data
            if (!empty($abroadClientIdsArray)) {
                $abroadClientInfo       = $this->_getClientUsersInfo($abroadClientIdsArray);
                $abroadSalesPersonsInfo = $this->_getSalesPersonInfoForClients($abroadClientIdsArray);
            }
            
            /*
             *  Now lets send the email to the Client Users first..
             *  It will now be sent to Abroad and National Clients separately
             */
            $mailInfo['mailSubject'] = "Your listing subscription on Shiksha will expire in " . $dayValue . " days.";
            $mailInfo['mailContent'] = $this->load->view('listing/listingsCrons/listingsExpiryClientNotification', '', true);
            $mailInfo['mailContent'] = str_replace('--EXPIRY_DAY_VALUE--', $dayValue, $mailInfo['mailContent']);
            
            //Sending mail to National Client Users
            $this->_sendEmailToClientUsers($clientsListingsArray, $courseInfoArray, $clientInfo, $salesPersonsInfo, $mailInfo, 'national');
            
            //Sending mail to Abroad Client User
            $this->_sendEmailToClientUsers($abroadClientsListingsArray, $abroadCourseInfoArray, $abroadClientInfo, $abroadSalesPersonsInfo, $mailInfo, 'abroad');
            
            /*
             *  Now lets send the email to the Sales Persons of these Clients..
             *  It will now be sent to Abroad and National Sales Persons separately
             */
            $mailInfo['mailSubject'] = "Your Clients' listings subscription on Shiksha will expire in " . $dayValue . " days.";
            $mailInfo['mailContent'] = $this->load->view('listing/listingsCrons/listingsExpirySalesPersonNotification', '', true);
            $mailInfo['mailContent'] = str_replace('--EXPIRY_DAY_VALUE--', $dayValue, $mailInfo['mailContent']);
            
            //Sending mail to National Sales Persons
            $this->_sendEmailToSalesPersons($clientsListingsArray, $salesPersonsInfo, $courseInfoArray, $clientInfo, $mailInfo, 'national');
            
            //Sending mail to Abroad Sales Persons
            $this->_sendEmailToSalesPersons($abroadClientsListingsArray, $abroadSalesPersonsInfo, $abroadCourseInfoArray, $abroadClientInfo, $mailInfo, 'abroad');
            
            echo "<BR> Thanks, All Done Successfully for date: " . $dateToCheckFor;
        }
    }
    
    private function _sendEmailToSalesPersons($clientsListingsArray, $salesPersonsInfo, $courseInfoArray, $clientInfo, $mailInfo, $salesPersonCountry = 'national')
    {
        $salesPesonsClientsArray = array();
        foreach ($salesPersonsInfo as $clientId => $salesPersonArray) {
            $clientsOfSalesPesonsArray[$salesPersonArray['SalesBy']][]         = $clientId;
            $salesPersonEmailInfo[$salesPersonArray['SalesBy']]['email']       = $salesPersonArray['email'];
            $salesPersonEmailInfo[$salesPersonArray['SalesBy']]['displayName'] = $salesPersonArray['displayName'];
        }
        
        foreach ($clientsOfSalesPesonsArray as $salesPersonId => $clientIdsArray) {
            $dataArray = array();
            if ($salesPersonCountry == 'abroad') {
                $dataArray[] = array(
                    'Course Name',
                    'University Name',
                    'Expiry Date',
                    'Client Id',
                    'Course Url'
                );
            } else {
                $dataArray[] = array(
                    'Course Name',
                    'Institute Name',
                    'Expiry Date',
                    'Client Id',
                    'Course Url'
                );
            }
            foreach ($clientIdsArray as $clientId) {
                
                foreach ($clientsListingsArray[$clientId] as $listingId) {
                    $dataArray[] = array(
                        $courseInfoArray[$listingId]['courseName'],
                        $courseInfoArray[$listingId]['instituteName'],
                        date("Y-m-d", strtotime($courseInfoArray[$listingId]['expiryDate'])),
                        $clientId,
                        $courseInfoArray[$listingId]['seoUrl']
                    );
                }
            }
            
            //$filename = $this->_putDataIntoCSVFile($salesPersonId, $dataArray);
            $filename = $this->_putDataIntoCSVFile($salesPersonId . '_' . $salesPersonCountry, $dataArray); // Added salesPersonCountry in name of file to avoid conflict if same sales_person have national as well as abroad course
            
            unset($dataArray);
            
            // Lets send the email to this Sales Person now..
            $mailAttachmentContent = file_get_contents($filename);
            unlink($filename);
            
            $mailSubject = $mailInfo['mailSubject'];
            $mailContent = str_replace('--SALES_PERSON_NAME--', $salesPersonEmailInfo[$salesPersonId]['displayName'], $mailInfo['mailContent']);
            
            if ($salesPersonCountry == 'abroad') {
                $ccEmail  = 'nandita@naukri.com';
                $bccEmail = 'simrandeep.singh@shiksha.com';
                //$ccEmail = 'abhinav.pandey@shiksha.com';
            } else {
                $ccEmail = 'ambrish@shiksha.com';
                // $bccEmail = 'saurabh.gupta@shiksha.com';
                //$ccEmail = 'saurabh.bhardwaj@shiksha.com';
            }
            //$bccEmail = 'saurabh.gupta@shiksha.com';
            //$bccEmail = 'abhinav.pandey@shiksha.com';
            
            //$this->sendEmailwithAttachment($salesPersonEmailInfo[$salesPersonId]['email'], $mailSubject, $mailContent, $mailAttachmentContent, "info@shiksha.com", $salesPersonId."_SP", $ccEmail, $bccEmail);
            $this->sendEmailwithAttachment($salesPersonEmailInfo[$salesPersonId]['email'], $mailSubject, $mailContent, $mailAttachmentContent, "info@shiksha.com", $salesPersonId . '_' . $salesPersonCountry . "_SP", $ccEmail, $bccEmail); // Added salesPersonCountry in name of file to avoid conflict if same sales_person have national as well as abroad course
        }
    }
    
    
    private function _sendEmailToClientUsers($clientsListingsArray, $courseInfoArray, $clientInfo, $salesPersonsInfo, $mailInfo, $salesPersonCountry = 'national')
    {
        foreach ($clientsListingsArray as $clientId => $listingsIdArray) {
            $dataArray = array();
            if ($salesPersonCountry == 'abroad') {
                $dataArray[] = array(
                    'Course Name',
                    'University Name',
                    'Expiry Date',
                    'Course Url'
                );
            } else {
                $dataArray[] = array(
                    'Course Name',
                    'Institute Name',
                    'Expiry Date',
                    'Course Url'
                );
            }
            
            foreach ($listingsIdArray as $listingId) {
                $dataArray[] = array(
                    $courseInfoArray[$listingId]['courseName'],
                    $courseInfoArray[$listingId]['instituteName'],
                    date("Y-m-d", strtotime($courseInfoArray[$listingId]['expiryDate'])),
                    $courseInfoArray[$listingId]['seoUrl']
                );
            }
            
            //$filename = $this->_putDataIntoCSVFile($clientId, $dataArray);
            $filename = $this->_putDataIntoCSVFile($clientId . '_' . $salesPersonCountry, $dataArray); // Added salesPersonCountry in name of file to avoid conflict if same client have national as well as abroad course
            
            unset($dataArray);
            
            // Lets send the email to this client user now..
            $mailAttachmentContent = file_get_contents($filename);
            unlink($filename);
            
            $mailSubject = $mailInfo['mailSubject'];
            $mailContent = str_replace('--CLIENT_NAME--', $clientInfo[$clientId]['displayname'], $mailInfo['mailContent']);
            
            $ccEmail = $salesPersonsInfo[$clientId]['email']; // need to discuss
            if ($salesPersonCountry == 'abroad') {
                $bccEmail = 'nandita@naukri.com';
                //$ccEmail = 'abhinav.pandey@shiksha.com';
            } else {
                $bccEmail = 'ambrish@shiksha.com';
                //$ccEmail = 'saurabh.bhardwaj@shiksha.com';
            }
            // echo "<br>CC email = ".$ccEmail. " for CID = ".$clientId;
            
            //$this->sendEmailwithAttachment($clientInfo[$clientId]['email'], $mailSubject, $mailContent, $mailAttachmentContent, "info@shiksha.com", $clientId."_CU", $ccEmail, $bccEmail);
            $this->sendEmailwithAttachment($clientInfo[$clientId]['email'], $mailSubject, $mailContent, $mailAttachmentContent, "info@shiksha.com", $clientId . '_' . $salesPersonCountry . "_CU", $ccEmail, $bccEmail); // Added salesPersonCountry in name of file to avoid conflict if same client have national as well as abroad course
            
        }
    }
    
    function getExpiryCoursesInfoByDate($dateToCheckFor)
    {
        // Find all the Paid courses ids expiring on this date..
        // $this->courseRepository->disableCaching(); // HAVE TO COMMENT THIS LATER ON.
        
        $expiringDataResultSet = $this->courseRepository->findExpiringCourses($dateToCheckFor, 'PAID');
        return $this->_filterCoursesToExpire($expiringDataResultSet);
    }
    
    /*
     * Fetch listings to expire for an interval
     */
    
    function getExpiryCoursesInfoForAnInterval($dateToCheckFrom, $dateToCheckUpto)
    {
        $expiringDataResultSet = $this->courseRepository->findExpiringCoursesForAnInterval($dateToCheckFrom, $dateToCheckUpto, 'PAID');
        return $this->_filterCoursesToExpire($expiringDataResultSet);
    }
    
    private function _filterCoursesToExpire($expiringDataResultSet)
    {
        
        
        $this->logData['coursesToExpire']               = $expiringDataResultSet;
        $this->logData['courseToSubscriptionIdMapping'] = array();
        $expiringCourses                                = array();
        $expiringSubscription                           = array();
        foreach ($expiringDataResultSet as $expiringData) {
            $expiringSubscription[]                                                           = $expiringData['subscriptionId'];
            $this->logData['courseToSubscriptionIdMapping'][$expiringData['listing_type_id']] = $expiringData['subscriptionId'];
        }
        $expiringSubscription    = array_unique($expiringSubscription);
        $sumsmodel               = $this->load->model('sumsmodel', 'sums');
        $filteredSubscriptionIds = $sumsmodel->getSubscriptionAfterDate($expiringSubscription, $this->bookingStartDate);
        foreach ($expiringDataResultSet as $expiringData) {
            if (in_array($expiringData['subscriptionId'], $filteredSubscriptionIds)) {
                $expiringCourses[] = $expiringData['listing_type_id'];
            } else {
                $this->logData['filteredCourses'][] = array(
                    'courseId' => $expiringData['listing_type_id'],
                    'subscriptionId' => $expiringData['subscriptionId']
                );
            }
        }
        
        if (count($expiringCourses) < 1) {
            return 'NO_COURSES_FOUND';
        } else {
            return $this->_getCourseObjectByCourseIds($expiringCourses);
        }
    }
    /*
     * Input : courseIds in an array
     * Output: course objects array indexed at their courseids. 
     * It accepts course ids of both national and abroad courses.
     */
    private function _getCourseObjectByCourseIds($courseIds)
    {
        $courseObjects = array();
        if (empty($courseIds) || !is_array($courseIds)) {
            return $courseObjects;
        }
        $nationalCourseObjects = $this->courseRepository->findMultiple($courseIds);
        $nationalCourseIds     = array();
        $abroadCourseIds       = array();
        foreach ($courseIds as $key => $courseId) {
            if (isset($nationalCourseObjects[$courseId]) && $nationalCourseObjects[$courseId] instanceof Course) {
                $nationalCourseIds[] = $courseId;
            } else {
                $abroadCourseIds[] = $courseId;
            }
        }
        if (!empty($abroadCourseIds)) {
            $abroadCourseObjects = $this->abroadCourseRepository->findMultiple($abroadCourseIds);
        }
        if (!empty($abroadCourseObjects)) {
            $courseObjects = $nationalCourseObjects + $abroadCourseObjects;
        } else {
            $courseObjects = $nationalCourseObjects;
        }
        foreach ($courseObjects as $courseId => $courseObject) {
            if ($courseObject->getId() == "") {
                unset($courseObjects[$courseId]);
            }
        }
        return $courseObjects;
    }
    private function _getClientUsersInfo($clientIdsArray)
    {
        // Get client users' basic info (email, display name etc.)..
        $userModel  = $this->load->model('user/UserModel');
        $clientInfo = $userModel->getUsersBasicInfo($clientIdsArray);
        return $clientInfo;
    }
    
    private function _getSalesPersonInfoForClients($clientIdsArray)
    {
        // Now get Sales Persons info for these clients Ids..
        $subscriptionClient = $this->load->library('Subscription_client');
        $salesPersonsInfo   = $subscriptionClient->sgetSalesPersonInfo(implode(', ', $clientIdsArray));
        return $salesPersonsInfo;
    }
    
    public function sendEmailwithAttachment($emailTo, $mailSubject, $mailContent, $mailAttachmentContent, $emailFrom, $flagId, $ccEmail = "", $bccEmail = "")
    {
        $alertClientObj = $this->load->library('alerts_client');
        $filename       = 'CoursesExpiryNotification_' . $flagId . '_' . date("d-m-Y") . '.csv';
        $type           = date('His') . rand(1, 9999);
        
        if ($bccEmail == "") {
            $bccEmail = 'ambrish@shiksha.com';
        }
        
        // $ccEmail = 'aditya.roshan@shiksha.com';
        // $emailTo = 'amit.kuksal@shiksha.com';
        
        $attachmentId = $alertClientObj->createAttachment("12", $type, 'course', 'E-Brochure', $mailAttachmentContent, $filename, 'text');
        $alertClientObj->externalQueueAdd("12", $emailFrom, $emailTo, $mailSubject, $mailContent, $contentType = "html", '', 'y', array(
            $attachmentId
        ), $ccEmail, $bccEmail);
        // $alertClientObj->externalQueueAdd("12", $emailFrom, 'amit.kuksal@shiksha.com', $mailSubject, $mailContent, $contentType="html", '', 'y', array($attachmentId));
        // echo "<hr>Email sent to :".$emailTo.' for type id: '.$type."<br>".$mailSubject."<br>".$mailContent;
    }
    
    private function _putDataIntoCSVFile($fileNameFlag, $csvDataArray)
    {
        $filename    = '/tmp/listingsExpiryNotification_' . $fileNameFlag . '_' . $this->_getDate(0) . '.csv';
        $filePointer = fopen($filename, "w");
        foreach ($csvDataArray as $fields) {
            fputcsv($filePointer, $fields);
        }
        fclose($file_pointer);
        return $filename;
    }
    
    private function _getDate($dayValue)
    {
        return (date("Y-m-d", strtotime("+" . $dayValue . " days")));
    }
    
    public function getDataForListingsProfileCompletion($parent_category_id = "")
    {
        ob_start();
        // ini_set("memory_limit", '600M');
        
        if (empty($parent_category_id)) {
            return;
        }
        $this->load->builder('CategoryBuilder', 'categoryList');
        $this->load->model('coursemodel');
        $this->load->model('listingmodel');
        
        $CategoryBuilder    = new CategoryBuilder();
        $CategoryRepository = $CategoryBuilder->getCategoryRepository();
        $category           = $CategoryRepository->find($parent_category_id);
        
        $course_model          = new CourseModel();
        $data                  = $course_model->getClientCoursesByParentCategory(array(
            $parent_category_id
        ), 300);
        $category_wise_courses = $data[$parent_category_id];
        unset($data);
        
        if (count($category_wise_courses) == 0) {
            return;
        }
        
        $listings_model     = new ListingModel();
        $course_reach_array = $listings_model->getCourseReachForCourses($category_wise_courses, "'live'");
        
        
        // load profile lib
        $this->load->library('listing/ListingProfileLib');
        $profile_object = new ListingProfileLib();
        
        $profiles_data[] = array(
            'course_id',
            'course_title',
            'percentage_score',
            'category_id',
            'category_name'
        );
        
        // starts the logic now
        $count = 0;
        foreach ($category_wise_courses as $course_id) {
            
            error_log('PROFILE DATA STARTED FOR ' . ($count + 1));
            $courses_array = array();
            $temp_array    = array();
            
            $temp_array = $profile_object->calculateProfileCompeletionForCourse($course_id, $course_reach_array[$course_id]);
            
            if (count($temp_array) == 0) {
                continue;
            }
            
            $profiles_data[] = array(
                $course_id,
                $temp_array['course_title_name'],
                ($temp_array['actual_score'] / $temp_array['total_score']) * 100,
                $category->getId(),
                $category->getName()
            );
            $count++;
            error_log('PROFILE DATA ENDFOR FOR ' . ($count));
            
        }
        
        unset($category_wise_courses);
        unset($category);
        
        $filename     = "report_profile_completion.csv";
        $file_pointer = fopen("/tmp/" . $filename, "w");
        foreach ($profiles_data as $fields) {
            fputcsv($file_pointer, $fields);
        }
        fclose($file_pointer);
        
        unset($profiles_data);
        
        $csv = file_get_contents("/tmp/" . $filename);
        $csv = trim($csv);
        header("Content-type: text/csv");
        header("Content-language: en");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        print_r($csv);
        ob_end_flush();
    }
    
    public function cronToGetEbrochureInformation()
    {
        ini_set('error_level', E_ALL);
        // ini_set('memory_limit','600M');
        set_time_limit(0);
        
        try {
            // Load and instantiate objects
            $this->load->builder('ListingBuilder', 'listing');
            $this->load->model('coursemodel');
            $this->load->model('institutemodel');
            $listingBuilder             = new ListingBuilder;
            //get data for courses and institutes
            $instituteRepository        = $listingBuilder->getInstituteRepository();
            $all_live_institutes        = $instituteRepository->getLiveInstitutes();
            $institute_wise_course_list = $instituteRepository->getCoursesOfInstitutes($all_live_institutes);
            unset($all_live_institutes);
            
            // iterate through above indexed list and make data for the institute
            $chunk_wise_data = array_chunk($institute_wise_course_list, 5000);
            $report_data     = array();
            unset($institute_wise_course_list);
            // loop throught the data
            foreach ($chunk_wise_data as $chunk_key => $chunk_value) {
                
                $institute_list        = array();
                $institutes_data       = array();
                $institutes_owner_info = array();
                
                foreach ($chunk_value as $value) {
                    $institute_list[] = $value['institute_id'];
                }
                
                $institutes_data       = $instituteRepository->findMultiple($institute_list);
                $institutes_owner_info = $this->institutemodel->getInstitutesOwnerInfo($institute_list);
                
                foreach ($chunk_value as $value) {
                    
                    $institute_id                    = $value['institute_id'];
                    $inst_object                     = $institutes_data[$institute_id];
                    $course_list                     = explode(",", $value['courseList']);
                    $course_wise_browser_uploaded    = $this->coursemodel->getEbrochureStatusForCourses($course_list);
                    $brochure_uploaded               = 'No';
                    $brochure_uploaded_courses_count = 0;
                    
                    foreach ($course_wise_browser_uploaded as $course_id => $brochure_url) {
                        
                        if (!empty($brochure_url)) {
                            $brochure_uploaded = 'Yes';
                            ++$brochure_uploaded_courses_count;
                        }
                    }
                    
                    $report_data[] = array(
                        $institute_id,
                        $inst_object->getName(),
                        count($course_list),
                        $inst_object->getMainLocation()->getCity()->getName(),
                        $brochure_uploaded,
                        $brochure_uploaded_courses_count,
                        $institutes_owner_info[$institute_id]['usergroup'],
                        $inst_object->getURL()
                    );
                }
                
            }
            
            unset($chunk_wise_data);
            //_P($report_data);exit;
            
            $this->_generateAndSendReport($report_data);
            
        }
        catch (Exception $e) {
            error_log("cronToGetEbrochureInformation error Message : " . $e->getMessage());
            error_log("cronToGetEbrochureInformation error Code : " . $e->getCode());
        }
    }
    
    private function _generateAndSendReport($report_data)
    {
        
        try {
            if (count($report_data) == 0) {
                throw new Exception('No details found.');
            }
            
            $filename      = date(Ymdhis) . 'ebrochureuploaddata.csv';
            $zip_file_name = date(Ymdhis) . 'ebrochureuploaddata.zip';
            $data_array[]  = array(
                'Institute ID',
                'Institute Name',
                'Number of courses',
                'Location',
                'Brochure uploaded (Yes/No)',
                'If yes, number of courses for which brochure is uploaded',
                'Owner type',
                'Institute URL'
            );
            foreach ($report_data as $data) {
                $data_array[] = $data;
            }
            
            $file_pointer = fopen("/tmp/" . $filename, "w");
            foreach ($data_array as $fields) {
                fputcsv($file_pointer, $fields);
            }
            fclose($file_pointer);
            $csv = file_get_contents("/tmp/" . $filename);
            unlink("/tmp/" . $filename);
            // compress the content
            $attachement_path = $this->_generateZip($csv);
            $csv              = base64_encode($csv);
            
            $type_id = time();
            $date    = date("d-m-Y");
            $content = "<p>Hi,</p> <p>Please find the attached file for Ebrochure Uploaded Data Report for last 7 days on Shiksha. </p><p>- Shiksha Tech.</p>";
            $subject = "";
            $subject .= $date . ' Ebrochureuploaddata Report for last 7 days';
            $email = array(
                'surya.prakash@shiksha.com',
                'Prakash.sangam@naukri.com',
                'ambrish@shiksha.com',
                'saurabh.gupta@shiksha.com',
                'anupama.m@shiksha.com'
            );
            
            for ($i = 0; $i < count($email); $i++) {
                
                $to = $email[$i];
                $this->_sendEmailWithAttachement('info@shiksha.com', $to, $subject, $content, $attachement_path);
            }
            
        }
        catch (Exception $e) {
            error_log("cronToGetEverySeventhDayInformation error Message : " . $e->getMessage());
            error_log("cronToGetEverySeventhDayInformation error Code : " . $e->getCode());
        }
        
    }
    
    function _generateZip($csv)
    {
        
        $this->load->library('zip');
        $name = '/tmp/Ebrochureuploaddata.csv';
        $data = $csv;
        
        $this->zip->add_data($name, $data);
        
        // Write the zip file to a folder on your server. Name it "my_backup.zip"
        $this->zip->archive('/tmp/Ebrochureuploaddata.zip');
        
        return '/tmp/Ebrochureuploaddata.zip';
        
        return $zip_file_content;
    }
    
    function _sendEmailWithAttachement($from, $to, $subject, $message, $attachement_path)
    {
        
        $this->load->library('email');
        
        $this->email->clear(TRUE);
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from($from, 'shiksha');
        $this->email->to($to);
        //$this->email->cc("amit.kuksal@gmail.com");
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach($attachement_path);
        
        $this->email->send();
        
        
        
    }
    
    public function updateProfileCompletionForCourses()
    {
        ini_set('error_level', E_ALL);
        // ini_set('memory_limit','1400M');
        set_time_limit(0);
        
        $this->load->builder('ListingBuilder', 'listing');
        $listingBuilder   = new ListingBuilder;
        //get data for courses and institutes
        $courseRepository = $listingBuilder->getCourseRepository();
        $all_live_courses = $courseRepository->getLiveCourses();
        
        $this->load->library('ListingProfileLib');
        error_log('COURSEPROFILE PROCESSING STARTS FOR : ' . count($all_live_courses) . ' LIVE COURSES.');
        $this->benchmark->mark('code_start');
        $count = 0;
        foreach ($all_live_courses as $course_id) {
            $this->listingprofilelib->updateCourseProfileCompletion($course_id);
            $count++;
            if (($count % 5000) == 0) {
                error_log('COURSEPROFILE PROCESSING DONE FOR : ' . $count . ' COURSES, CURRENT COURSE ID : ' . $course_id);
            }
        }
        
        error_log('ALL DONE, TOTAL COURSEPROFILE PROCESSING DONE FOR : ' . $count . ' COURSES, LAST PROCESSED COURSE ID : ' . $course_id);
        
        $this->benchmark->mark('code_end');
        $totalTimeTaken = $this->benchmark->elapsed_time('code_start', 'code_end');
        error_log('COURSEPROFILE COMPLETION TOOK : ' . $totalTimeTaken . ' SECS.');
    }
    
    public function generateEbrochureForAllCourses()
    {
        ini_set('error_level', E_ALL);
        // ini_set('memory_limit','2000M');
        set_time_limit(0);
        
        $this->load->builder('ListingBuilder', 'listing');
        $listingBuilder   = new ListingBuilder;
        // Get data for courses and institutes
        $courseRepository = $listingBuilder->getCourseRepository();
        $all_live_courses = $courseRepository->getLiveCourses();
        $this->_generateEbrochures($all_live_courses);
    }
    /*
     * (called to run the script for creating brochure on all abroad courses)
     */
    public function generateEbrochureForAllAbroadCourses()
    {
        ini_set('error_level', E_ALL);
        // ini_set('memory_limit','2000M');
        set_time_limit(0);
        $this->load->builder('ListingBuilder', 'listing');
        $listingBuilder         = new ListingBuilder;
        // Get data for courses and institutes
        $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
        error_log("\n" . '==================================== AUTO-BROCHURE-GENERATION SCRIPT KICKSTART ==================================== ', 3, "/tmp/listingsAutoBrochureAbroad.log");
        error_log("\n" . 'GET LIVE COURSES QRY START : ' . date("Y-m-d:H:i:s"), 3, "/tmp/listingsAutoBrochureAbroad.log");
        $all_live_abroad_courses = $abroadCourseRepository->getLiveAbroadCourses();
        error_log("\n" . 'GET LIVE COURSES QRY END : ' . date("Y-m-d:H:i:s"), 3, "/tmp/listingsAutoBrochureAbroad.log");
        error_log("\n" . 'NO OF COURSES : ' . count($all_live_abroad_courses), 3, "/tmp/listingsAutoBrochureAbroad.log");
        // generate brochure for these courses
        $this->_generateEbrochuresForAbroadCourses($all_live_abroad_courses);
        error_log("\n" . '==================================== AUTO-BROCHURE-GENERATION SCRIPT END ==================================== ', 3, "/tmp/listingsAutoBrochureAbroad.log");
        
    }
    /*
     * function to generate brochure for a munber of course ids passed as array
     */
    private function _generateEbrochuresForAbroadCourses($coursesArray)
    {
        // return if no course id passed..
        if (!count($coursesArray)) {
            return;
        }
        //load the ListingEbrochureGenerator library
        $listingebrochuregenerator = $this->load->library('ListingEbrochureGenerator');
        
        // error_log('COURSEBROCHURE GENERATION STARTS FOR : '.count($coursesArray).' LIVE COURSES.');
        error_log("\n\n===============================================================", 3, "/tmp/listingsAutoBrochureAbroad.log");
        error_log("\n" . 'COURSEBROCHURE GENERATION STARTS FOR : ' . count($coursesArray) . ' LIVE COURSES AT ' . date("Y-m-d:H:i:s"), 3, "/tmp/listingsAutoBrochureAbroad.log");
        // start benchmark ... 
        $this->benchmark->mark('code_start');
        
        $count = 0;
        foreach ($coursesArray as $course_id) {
            // generate brochure & get url
            $urlArray = $listingebrochuregenerator->genearteAbroadCourseEbrochure($course_id);
            $count++;
            if (($count % 500) == 0) { // to check for progress in terms of how many courses have got their brochures generated
                error_log('COURSEBROCHURE GENERATION DONE FOR : ' . $count . ' COURSES, CURRENT COURSE ID : ' . $course_id, 3, "/tmp/listingsAutoBrochureAbroad.log");
                //break;
            }
        }
        // get rid of library object 
        unset($listingebrochuregenerator);
        // end benchmark
        $this->benchmark->mark('code_end');
        // time taken
        $totalTimeTaken = (int) ($this->benchmark->elapsed_time('code_start', 'code_end'));
        error_log("\n" . 'COURSEBROCHURE GENERATION TOOK : ' . $totalTimeTaken . ' SECS FOR ' . $count . ' COURSES, COMPLETED AT ' . date("Y-m-d:H:i:s"), 3, "/tmp/listingsAutoBrochureAbroad.log");
        // error_log('COURSEBROCHURE GENERATION TOOK : '.$totalTimeTaken.' SECS.');
    }
    /*
     * function to generate brochure via cron
     */
    public function reGenerateEbrochureForUpdatedAbroadCourses()
    {
        $this->validateCron(); // prevent browser access
        $interval                = 2; // In Hours.. since cron runs every 2 hours
        $abroadCoursefindermodel = $this->load->model('abroadcoursefindermodel');
        // get courses that got modified in last 2 hours
        $courses                 = $abroadCoursefindermodel->getModifiedAbroadCoursesByHours($interval);
        // generate brochure
        $this->_generateEbrochuresForAbroadCourses($courses);
    }
    private function _generateEbrochures($coursesArray)
    {
        if (!count($coursesArray)) {
            return;
        }
        $listingebrochuregenerator = $this->load->library('ListingEbrochureGenerator');
        
        // error_log('COURSEBROCHURE GENERATION STARTS FOR : '.count($coursesArray).' LIVE COURSES.');
        error_log("\n\n===============================================================", 3, "/tmp/listingsAutoBrochure.log");
        error_log("\n" . 'COURSEBROCHURE GENERATION STARTS FOR : ' . count($coursesArray) . ' LIVE COURSES AT ' . date("Y-m-d:H:i:s"), 3, "/tmp/listingsAutoBrochure.log");
        $this->benchmark->mark('code_start');
        $count = 0;
        foreach ($coursesArray as $course_id) {
            $urlArray = $listingebrochuregenerator->genearteEbrochure('course', $course_id);
            $count++;
            if (($count % 500) == 0) {
                error_log('COURSEBROCHURE GENERATION DONE FOR : ' . $count . ' COURSES, CURRENT COURSE ID : ' . $course_id);
            }
        }
        unset($listingebrochuregenerator);
        $this->benchmark->mark('code_end');
        $totalTimeTaken = (int) ($this->benchmark->elapsed_time('code_start', 'code_end'));
        error_log("\n" . 'COURSEBROCHURE GENERATION TOOK : ' . $totalTimeTaken . ' SECS FOR ' . $count . ' COURSES, COMPLETED AT ' . date("Y-m-d:H:i:s"), 3, "/tmp/listingsAutoBrochure.log");
        // error_log('COURSEBROCHURE GENERATION TOOK : '.$totalTimeTaken.' SECS.');
    }
    
    public function reGenerateEbrochureForUpdatedCourses()
    {
        $timeDuration      = -2; // In Hours..                
        $lastModifiedSince = date("Y-m-d H:i:s", strtotime("+" . $timeDuration . " hours"));
        $coursefindermodel = $this->load->model('coursefindermodel');
        $courses           = $coursefindermodel->getModifiedCoursesOfType($lastModifiedSince);
        $this->_generateEbrochures($courses);
    }
    
    /**
     * Purpose       : Method to refresh cache of Abroad courses consolidated view-count details(of last 21 days)
     * Params        : none
     * Author        : Romil Goel
     */
    public function refreshAbroadCoursesViewCount()
    {
        $this->validateCron(); // prevent browser access
        // load files
        $abroadListingModel    = $this->load->model('listing/abroadlistingmodel');
        $abroadListingCacheLib = $this->load->library('listing/cache/AbroadListingCache');
        
        // get the view count for all abroad courses
        $viewCountForCourseList = $abroadListingModel->getViewCountDumpForListingType('course', 21);
        
        // store the view-count details in the cache
        if (!empty($viewCountForCourseList)) {
            $abroadListingCacheLib->storeViewCountForCourses($viewCountForCourseList);
        }
    }
    
    /**
     * Purpose       : to store all course reviews data in cache
     * Params        : none
     * Author        : Ankit Garg
     */
    public function cacheCoursesReviewsData()
    {
        $startTime           = microtime(true);
        // load files
        $national_course_lib = $this->load->library('listing/NationalCourseLib');
        
        if ($national_course_lib->storeCourseIdsWithReviewsInCache()) {
            echo "Cron executed successfully in " . (microtime(true) - $startTime) . " seconds";
        } else {
            echo "Cron did not run successfully";
        }
    }
    
    
    /**
     * Purpose       : cron to send mail and notification to  myshortlist user
     * Author        : Aman Varshney
     * @return 
     */
    public function sendShortlistMailsToUser()
    {
        $start                 = microtime(true);
        $currentDate           = date('Y-m-d-H-i-s');
        // notification mail to cron started
        $listingErrorReporting = $this->load->library('listing/ListingErrorReportingLib');
        $mailAlert['subject']  = "My Shortlist Cron Started $currentDate";
        $mailAlert['content']  = "My Shortlist Cron Started $currentDate";
        $listingErrorReporting->sendMyShortlistNotificationAlert($mailAlert);
        
        // get template from shortlist template table
        $myShortlistModel     = $this->load->model('myShortlist/myshortlistmodel');
        $notificationTemplate = $myShortlistModel->getNotificationTemplate();
        $templateBodyArray    = array();
        
        // use for logging  my shortlist user count
        $activityLog = '';
        
        // set template value to be processed
        // templateId is the identifier
        foreach ($notificationTemplate as $key => $tempVal) {
            $templateId                                     = $tempVal['id'];
            $templateIds[]                                  = $templateId;
            $templateBodyArray[$templateId]['body']         = $tempVal['body'];
            $templateBodyArray[$templateId]['fromMailId']   = $tempVal['from'];
            $templateBodyArray[$templateId]['subject']      = $tempVal['subject'];
            $templateBodyArray[$templateId]['maxCourseNum'] = $tempVal['shortlist_no'];
            $templateBodyArray[$templateId]['linkType']     = $tempVal['link_type'];
            $templateBodyArray[$templateId]['type']         = $tempVal['type'];
            
            // for subject template
            $templateBodyArray[$templateId . '_subject']['body']         = $tempVal['subject'];
            $templateBodyArray[$templateId . '_subject']['maxCourseNum'] = $tempVal['shortlist_no'];
            $templateBodyArray[$templateId . '_subject']['linkType']     = $tempVal['link_type'];
        }
        
        $data['templateArr'] = $templateBodyArray;
        // api to get parsed body of mail and notification
        $resultSet           = Modules::run('myShortlist/MyShortlistCMS/parseBody', $data);
        
        if (!empty($resultSet) && is_array($resultSet)) {
            foreach ($resultSet as $notificationTemplateId => $tempVal) {
                if (is_numeric($notificationTemplateId)) {
                    $notificationType = $templateBodyArray[$notificationTemplateId]['type'];
                    $fromEmail        = $templateBodyArray[$notificationTemplateId]['fromMailId'];
                    
                    foreach ($tempVal as $userId => $res) {
                        
                        if (!is_numeric($userId)) {
                            continue;
                        }
                        $parsedBody  = $res['parsedMsg'];
                        $isValid     = ($res['valid'] == 1) ? '1' : '0';
                        $createdTime = date('Y-m-d H:i:s');
                        $userEmail   = $res['email'];
                        $courseId    = $res['courseId'];
                        
                        switch ($notificationType) {
                            case 'mail':
                                // get Parsed subject from another template
                                $subject      = $resultSet[$notificationTemplateId . "_subject"][$userId]['parsedMsg'];
                                $subjectValid = $resultSet[$notificationTemplateId . "_subject"][$userId]['valid'];
                                if ($isValid == $subjectValid) {
                                    $isValid = $isValid;
                                } else {
                                    $isValid = '0';
                                }
                                // myshortlist user mail
                                $myShortlistMail = array(
                                    'user_id' => $userId,
                                    'template_id' => $notificationTemplateId,
                                    'body' => $parsedBody,
                                    'subject' => $subject,
                                    'is_valid' => $isValid,
                                    'created' => $createdTime
                                );
                                
                                if ($isValid == '0') {
                                    $myShortlistMail['reason'] = $res['reason'];
                                }
                                // tMailQueue Data
                                $tMailQueue = array(
                                    'fromEmail' => $fromEmail,
                                    'toEmail' => $userEmail,
                                    'subject' => $subject,
                                    'content' => $parsedBody,
                                    'contentType' => 'html',
                                    'attachment' => 'n',
                                    'fromUserName' => 'Shiksha.com'
                                );
                                
                                $processedData['myShortlistMail'] = $myShortlistMail;
                                $processedData['tMailQueue']      = $tMailQueue;
                                
                                $myShortlistModel->processedMyShortlistMailToUser($processedData);
                                error_log("My Shortlist Mail Processed User Id:" . $userId . PHP_EOL, 3, "/tmp/myshortlistMailSendUserId$currentDate.txt");
                                break;
                            case 'notification':
                                // myshortlist user notification
                                $myShortlistNotification = array(
                                    'user_id' => $userId,
                                    'template_id' => $notificationTemplateId,
                                    'body' => $parsedBody,
                                    'is_valid' => $isValid,
                                    'created' => $createdTime,
                                    'updated' => $createdTime
                                );
                                if ($isValid == '0') {
                                    $myShortlistNotification['reason'] = $res['reason'];
                                }
                                
                                $myShortlistModel->processedMyShortlistNotificationToUser($myShortlistNotification);
                                error_log("My Shortlist Mail Processed User Id:" . $userId . PHP_EOL, 3, "/tmp/myshortlistNotificationUserId$currentDate.txt");
                                break;
                            
                            case 'mobile_notification':
                                // myshortlist user mobile notification
                                $myShortlistNotification = array(
                                    'user_id' => $userId,
                                    'template_id' => $notificationTemplateId,
                                    'body' => $parsedBody,
                                    'course_id' => $courseId,
                                    'is_valid' => $isValid,
                                    'created' => $createdTime,
                                    'updated' => $createdTime
                                );
                                if ($isValid == '0') {
                                    $myShortlistNotification['reason'] = $res['reason'];
                                }
                                
                                $myShortlistModel->processedMyShortlistMobileNotification($myShortlistNotification);
                                error_log("My Shortlist Mail Processed User Id:" . $userId . PHP_EOL, 3, "/tmp/myshortlistNotificationUserId$currentDate.txt");
                                break;
                            
                            default:
                                break;
                        } // switch close                  
                    } //inner loop
                    error_log("My Shortlist Total Entry Processed To $notificationType:" . count($tempVal) . PHP_EOL, 3, "/tmp/myshortlistTotalActivityLog$currentDate.txt");
                    $activityLog .= "Total Entry Processed for Template Id $notificationTemplateId is " . count($tempVal) . "<br/>";
                    //update my shortlist template is processed
                    $myShortlistModel->updateNotificationTemplate($notificationTemplateId);
                    error_log("My Shortlist Template Ids  Processed:" . $notificationTemplateId . PHP_EOL, 3, "/tmp/myshortlistNotificationTemplateId$currentDate.txt");
                    
                } // endif condition  
                
                
            } // main loop
            
            $stop                 = microtime(true);
            $seconds              = $stop - $start;
            $resultLog            = 'Start:' . $start . PHP_EOL . 'Stop:' . $stop . PHP_EOL . 'Seconds:' . $seconds . PHP_EOL;
            $templateIdsString    = implode(",", $templateIds);
            $mailAlert['subject'] = "My Shortlist Cron successfully executed $currentDate";
            $mailAlert['content'] = "Hello,<br/>
                                          My Shortlist Cron successfully executed.<br/>
                                          Cron execution Time :   $seconds seconds<br/>
                                          Templates Ids processed : $templateIdsString<br/>
                                          $activityLog<br/><br/>
                                          ";
            error_log("My Shortlist Notification Script Cron has been executed Successfully:$resultLog");
        } else {
            $mailAlert['subject'] = "MyShortlist : No template found to processed $currentDate";
            $mailAlert['content'] = "MyShortlist : No template found to processed $currentDate";
        } //endif
        $listingErrorReporting->sendMyShortlistNotificationAlert($mailAlert);
        
        
    }
    
    /**
     * Purpose       : to migrate old shortlist courses to new my_shortlist
     * Params        : none
     * Author        : Ankit Garg
     * date          : 2015-04-17
     */
    public function migrateUserShortlistedCourses()
    {
        
        ini_set('memory_limit', '-1');
        $startTime = microtime(true);
        
        $MyShortlist               = $this->load->model('myShortlist/myshortlistmodel');
        $courseIds                 = $MyShortlist->getAllShortListedCourseIds();
        $national_course_lib       = $this->load->library('listing/NationalCourseLib');
        $courseDominantSubCategory = $national_course_lib->getCourseDominantSubCategoryDB($courseIds);
        $mbaCourseIds              = array();
        foreach ($courseDominantSubCategory['subCategoryInfo'] as $courseId => $subcategoryInfo) {
            if ($subcategoryInfo['dominant'] == 23) {
                $mbaCourseIds[] = $courseId;
            }
        }
        
        $final_array = array_chunk($mbaCourseIds, 500);
        foreach ($final_array as $key => $chunk) {
            if ($MyShortlist->migrateUserShortlistedCourses($chunk)) {
                echo "Cron for chunk " . ($key + 1) . " executed successfully";
            } else {
                echo "Cron for chunk " . ($key + 1) . " failed";
            }
        }
        
        echo "Cron executed successfully in " . (microtime(true) - $startTime) . " seconds";
    }
    
    
    /**
     * Purpose       : To change name of sub category and refresh cache
     * Params        : none
     * Author        : Ankit Garg
     * date          : 2015-06-01
     */
    function changeCategoryBoardName()
    {
        $previousName     = "BBA/BBM/BBS";
        $finalName        = "BBA/BMS/BBM/BBS";
        $subcategoryId    = 28;
        $specializationId = 781;
        // $subcategoryId = 81;
        // $specializationId = 781;
        $this->load->model('listing/listingmodel');
        $subCategoryData = array();
        $subCategoryData = $this->listingmodel->updateCategoryBoardName($previousName, $finalName, $subcategoryId, $specializationId);
        if (!empty($subCategoryData)) {
            //refreshing ldb course object
            $this->load->builder('CategoryBuilder', 'categoryList');
            $categoryBuilder    = new CategoryBuilder;
            $CategoryRepository = $categoryBuilder->getCategoryRepository();
            $CategoryRepository->disableCaching();
            _p($CategoryRepository->find($subcategoryId));
            _p($CategoryRepository->getSubCategories($subCategoryData['parentId'], 'national'));
            //refreshing ldb course object
            $this->load->builder('LDBCourseBuilder', 'LDB');
            $LDBCourseBuilder    = new LDBCourseBuilder;
            $LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
            $LDBCourseRepository->disableCaching();
            // _p($LDBCourseRepository->find($specializationId));
            _p($LDBCourseRepository->getLDBCoursesForSubCategory($subcategoryId));
        }
        //delete files from all three servers, HomePageRedesignCache/middlepanel.html, HomePageRedesignCache/footer.html
        exit;
    }
    
    function refreshCategoryBoardCache()
    {
        $this->load->builder('CategoryBuilder', 'categoryList');
        $categoryBuilder    = new CategoryBuilder;
        $CategoryRepository = $categoryBuilder->getCategoryRepository();
        $CategoryRepository->disableCaching();
        $CategoryRepository->findAll();
    }
    
    /**
     * To check duplicate listing entries
     * @author Aman Varshney <varshney.aman@gmail.com>
     * @date   2015-12-07
     */
    function checkDuplicateListingEntries()
    {
        error_log("CRON : checking duplicate entries for Institute");
        $this->_checkDuplicateListings('institute');
        
        error_log("CRON : checking duplicate entries for Course");
        $this->_checkDuplicateListings('course');
        
    }
    
    private function _checkDuplicateListings($type)
    {
        $start = microtime(true);
        $this->load->model('listing/listingmodel');
        $alerts_client = $this->load->library('alerts_client');
        
        $listingData = $this->listingmodel->checkDuplicateListingEntries($type);
        $content     = "";
        
        if (!empty($listingData)) {
            //send Mail to listing Team
            $subject = "Duplicate Entry of $type in listings main table";
            
            $stop    = microtime(true);
            $seconds = $stop - $start;
            
            $content = "Hello,<br/>
                                          Check Duplicate Listing Cron executed.<br/>
                                          Today is : " . date("Y/m/d") . "<br/>
                                          Cron execution Time :   $seconds seconds<br/><br/>
                                          ";
            foreach ($listingData as $key => $value) {
                $content .= $type . "id : " . $value['listing_type_id'] . " , " . $type . " name : " . $value['listing_title'] . " and duplicate entries count : " . $value['listingCount'] . "<br/>";
            }
            
            $flag = $alerts_client->externalQueueAdd("12", ADMIN_EMAIL, 'aman.varshney@shiksha.com', $subject, $content, "html", "0000-00-00 00:00:00", 'n', array());
        }
    }
    
    public function createHistoricalDataForCourses()
    {
        ini_set("memory_limit", "3000M");
        $startTime = microtime(true);
        $this->load->model('listing/listingmodel');
        $result              = $this->listingmodel->getAllUpgradedAbroadCourses();
        $courseIDs           = $result['courseId'];
        $listingsMainDetails = $result['result'];
        $subscriptionIDs = array_map(function($a, $b)
        {
            return $a['subscriptionId'];
        }, $listingsMainDetails);
        $subscriptionIDs = array_values(array_unique($subscriptionIDs));
        //_p($listingsMainDetails);
        
        $subscriptionClient = $this->load->library('Subscription_client');
        $subData            = $subscriptionClient->getMultipleSubscriptionDetails(1, $subscriptionIDs, true);
        $subscriptionArray  = array();
        foreach ($subData as $key => $val) {
            $subscriptionArray[(int) $val['SubscriptionId']] = $val;
        }
        //_p($subscriptionArray);
        
        $cmsTrackingData = $this->listingmodel->getListingCMSUserTracking($courseIDs);
        $cmUserIds       = array();
        foreach ($cmsTrackingData as $key => $val) {
            $cmUserIds[$val['listingId']][$val['updatedAt']] = $val['userId'];
        }
        
        foreach ($listingsMainDetails as $key => $val) {
            
            $addedBy      = ($cmUserIds[$val['listing_type_id']][$val['approve_date']] != '') ? $cmUserIds[$val['listing_type_id']][$val['approve_date']] : 1;
            $subStartDate = ($subscriptionArray[(int) $val['subscriptionId']]['SubscriptionStartDate'] != '') ? $subscriptionArray[(int) $val['subscriptionId']]['SubscriptionStartDate'] : '0000-00-00 00:00:00';
            $subEndDate   = ($subscriptionArray[(int) $val['subscriptionId']]['SubscriptionEndDate'] != '') ? $subscriptionArray[(int) $val['subscriptionId']]['SubscriptionEndDate'] : '0000-00-00 00:00:00';
            
            $this->listingmodel->subscriptionHistoricalDetails($val['approve_date'], $val['listing_type_id'], $val['pack_type'], $val['subscriptionId'], $val['username'], $subStartDate, $subEndDate, $addedBy);
            
        }
        
        
        //_p($cmUserIds);
        echo "Migration Done " . "<br/>";
        echo "Memory Peak: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MiB </br>";
        $endTime = microtime(true);
        echo "Time Taken: " . (($endTime - $startTime) / (60)) . " minute";
    }
    
    /**
     * [addAllArticlesToQueue one time script to convert all article pages html to its corresponding amp html]
     * @author Ankit Garg <g.ankit@shiksha.com>
     * @date   2018-01-24
     */
    function addAllArticlesToQueue() {
        ini_set("memory_limit", "3000M");        
        $this->articlemodel = $this->load->model('article/articlenewmodel','',TRUE);
        $articleIds = $this->articlemodel->getAllLiveArticles();
        foreach ($articleIds as $id) {
            modules::run('common/GlobalShiksha/insertIntoAmpRabbitMQueue',$id,array(), 'article');
        }
        echo "All articles html converted to amp";
        exit;
    }    

}
