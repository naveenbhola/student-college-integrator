<?php

class AbroadCourseDemo extends MX_Controller{

    public function testCourse($courseId){


        $this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder();
        $abroadCourseRepositoryOld = $listingBuilder->getAbroadCourseRepository(false);
        $abroadCourseRepositoryNew = $listingBuilder->getAbroadCourseRepository();

        $courseIds = array($courseId);

        $courseOldObject = $abroadCourseRepositoryOld->findMultiple($courseIds);
        $courseNewObject = $abroadCourseRepositoryNew->findMultiple($courseIds);


        foreach ($courseIds as $courseId){
            $oldObj = $courseOldObject[$courseId];
            $newObj = $courseNewObject[$courseId];

            echo "<html>";
            echo "<body>";
            echo "<div class='test'>";

            echo "value from Old Object(brochureUrl): ".$oldObj->getRequestBrochure().'<br/>';
            echo "value from New Object(brochureUrl): ".$newObj->getRequestBrochure().'<br/>';

            echo "value from Old Object(lastModifiedDate): ".$oldObj->getLastUpdatedDate().'<br/>';
            echo "value from New Object(lastModifiedDate): ".$newObj->getLastUpdatedDate().'<br/>';

            echo "value from Old Object(expiryDate): ".$oldObj->getExpiryDate().'<br/>';
            echo "value from New Object(expiryDate): ".$newObj->getExpiryDate().'<br/>';

            echo "value from Old Object(clientId): ".$oldObj->getClientId().'<br/>';
            echo "value from New Object(clientId): ".$newObj->getClientId().'<br/>';

            echo "value from Old Object(seoURL): ".$oldObj->getURL().'<br/>';
            echo "value from New Object(seoURL): ".$newObj->getURL().'<br/>';

            echo "value from Old Object(locations): <br/>";
            _p($oldObj->getMainLocation());
            echo "value from New Object(locations): <br/>";
            _p($newObj->getMainLocation());

            echo "value from Old Object(location locality object): <br/>";
            _p($oldObj->getMainLocation()->getLocality());
                echo "value from New Object(location locality object): <br/>";
            _p($newObj->getMainLocation()->getLocality());

            echo "value from Old Object(location Zone object): <br/>";
            _p($oldObj->getMainLocation()->getZone());
            echo "value from New Object(location Zone object): <br/>";
            _p($newObj->getMainLocation()->getZone());

            echo "value from Old Object(location city object): <br/>";
            _p($oldObj->getMainLocation()->getState());
            echo "value from New Object(location city object): <br/>";
            _p($newObj->getMainLocation()->getState());

            echo "value from Old Object(location Country object): <br/>";
            _p($oldObj->getMainLocation()->getCountry());
            echo "value from New Object(location Country object): <br/>";
            _p($newObj->getMainLocation()->getCountry());

            echo "value from Old Object(location region object): <br/>";
            _p($oldObj->getMainLocation()->getRegion());
            echo "value from New Object(location region object): <br/>";
            _p($newObj->getMainLocation()->getRegion());

            echo "value from Old Object(Meta Data): <br/>";
            _p($oldObj->getMetaData());
            echo "value from New Object(Meta Data): <br/>";
            _p($newObj->getMetaData());

            echo "value from Old Object(CourseLevel): ".$oldObj->getCourseLevel().'<br/>';
            echo "value from New Object(CourseLevel): ".$newObj->getCourseLevel().'<br/>';

            echo "value from Old Object(Exam Eligibility): <br/>";
            _p($oldObj->getEligibilityExams());
            echo "value from New Object(Exam Eligibility): <br/>";
            $eligiblityArray = $newObj->getEligibilityExams();

            $finalArr = array();
            $sorted = array();
            foreach($eligiblityArray as $obj) {
                $listingPriority = $obj->getListingPriority();
                if(isset($listingPriority)) {
                    $sorted[$obj->getListingPriority()] = $obj;
                }
                else{
                    array_push($finalArr,$obj);
                }
            }
            ksort($sorted);
            foreach($sorted as $obj) {
                  $finalArr[] = $obj;
            }
            _p($finalArr);

            echo "value from Old Object(remove Exam Eligibility): ".$oldObj->removeEligibilityExams().'<br/>'; //look
            echo "value from New Object(remove Exam Eligibility): ".$newObj->removeEligibilityExams().'<br/>';

            echo "value from Old Object(has Recruiting Company check): ".$oldObj->hasRecruitingCompanies().'<br/>';
            echo "value from New Object(has Recruiting Company check): ".$newObj->hasRecruitingCompanies().'<br/>';

            $oldCompanyObject = $oldObj->getRecruitingCompanies();
            $newCompanyObject = $newObj->getRecruitingCompanies();
            echo "value from Old Object(recruitingCompanies): <br/>";
            _p($oldCompanyObject);
            echo "value from New Object(recruitingCompanies): <br/>";
            _p($newCompanyObject);

            echo "value from Old Object(instituteId): ".$oldObj->getInstId().'<br/>';
            echo "value from New Object(instituteId): ".$newObj->getInstId().'<br/>';

            echo "value from Old Object(courseId): ".$oldObj->getId().'<br/>';
            echo "value from New Object(courseId): ".$newObj->getId().'<br/>';

            echo "value from Old Object(name): ".$oldObj->getName().'<br/>';
            echo "value from New Object(name): ".$newObj->getName().'<br/>';

//            echo "value from Old Object(institute): ".$oldObj->getInstitute().'<br/>'; //may be removed
//            echo "value from New Object(institute): ".$newObj->getInstitute().'<br/>';


            $oldDuration = $oldObj->getDuration();
            $newDuration = $newObj->getDuration();

            echo "value from Old Object(duration): <br/>";
            _p($oldDuration);
            echo "value from New Object(duration): <br/>";
            _p($newDuration);



            //adding new duration getters

            echo "value from Old Object(durationDisplayValue): ".$oldDuration->getDisplayValue().'<br/>';
            echo "value from New Object(durationDisplayValue): ".$newDuration->getDisplayValue().'<br/>';

            echo "value from Old Object(getValueInHours): ".$oldDuration->getValueInHours().'<br/>';
            echo "value from New Object(getValueInHours): ".$newDuration->getValueInHours().'<br/>';


            echo "value from Old Object(getDurationUnit): ".$oldDuration->getExactDurationValue().'<br/>';
            echo "value from New Object(getDurationUnit): ".$newDuration->getExactDurationValue().'<br/>';

            echo "value from Old Object(getDurationValue): ".$oldDuration->getDurationValue().'<br/>';
            echo "value from New Object(getDurationValue): ".$newDuration->getDurationValue().'<br/>';

            echo "value from Old Object(getExactDurationValue): ".$oldDuration->getExactDurationValue().'<br/>';
            echo "value from New Object(getExactDurationValue): ".$newDuration->getExactDurationValue().'<br/>';

            //till here

            $oldFeesObj = $oldObj->getFees();
            $newFeesObj = $newObj->getFees();
            echo "value from Old Object(fees): <br/>";
            _p($oldFeesObj);
            echo "value from New Object(fees): <br/>";
            _p($newFeesObj);

            //Fees addition getters
            echo "value from Old Object(getValue): ".$oldFeesObj->getValue().'<br/>';
            echo "value from New Object(getValue): ".$newFeesObj->getValue().'<br/>';

            echo "value from Old Object(getCurrency): ".$oldFeesObj->getCurrency().'<br/>';
            echo "value from New Object(getCurrency): ".$newFeesObj->getCurrency().'<br/>';

            echo "value from Old Object(getCurrencySymbol): ".$oldFeesObj->getCurrencySymbol().'<br/>';
            echo "value from New Object(getCurrencySymbol): ".$newFeesObj->getCurrencySymbol().'<br/>';

            echo "value from Old Object(getFeeDisclaimer): ".$oldFeesObj->getFeeDisclaimer().'<br/>';
            echo "value from New Object(getFeeDisclaimer): ".$newFeesObj->getFeeDisclaimer().'<br/>';

            echo "value from Old Object(getCurrencyEntity): ".$oldFeesObj->getCurrencyEntity().'<br/>';
            echo "value from New Object(getCurrencyEntity): ".$newFeesObj->getCurrencyEntity().'<br/>';

            echo "value from Old Object(__toString): ".$oldFeesObj->__toString().'<br/>';
            echo "value from New Object(__toString): ".$newFeesObj->__toString().'<br/>';

            //till here

//            echo "value from Old Object(course_type): ".$oldObj->getCourseType().'<br/>';
//            echo "value from New Object(course_type): ".$newObj->getCourseType().'<br/>';  //removed i think
//
//            echo "value from Old Object(course_order): ".$oldObj->getOrder().'<br/>';
//            echo "value from New Object(course_order): ".$newObj->getOrder().'<br/>';       //removed

            echo "value from Old Object(attributes): <br/>";
            _p($oldObj->getAttributes());
            echo "value from New Object(attributes): <br/>";
            _p($newObj->getAttributes());

//            echo "value from Old Object(course_level): ".$oldObj->getCourseLevelValue().'<br/>';
//            echo "value from New Object(course_level): ".$newObj->getCourseLevelValue().'<br/>';    //removed

            echo "value from Old Object(level): ".$oldObj->getCourseLevel1Value().'<br/>';
            echo "value from New Object(level): ".$newObj->getCourseLevel1Value().'<br/>';

//            echo "value from Old Object(course_level_2): ".$oldObj->getCourseLevel2Value().'<br/>';
//            echo "value from New Object(course_level_2): ".$newObj->getCourseLevel2Value().'<br/>';

            echo "value from Old Object(packtype): ".$oldObj->getCoursePackType().'<br/>';
            echo "value from New Object(packtype): ".$newObj->getCoursePackType().'<br/>';

            echo "value from Old Object(BooleanPaidCheck): ".$oldObj->isPaid().'<br/>';
            echo "value from New Object(BooleanPaidCheck): ".$newObj->isPaid().'<br/>';

            echo "value from Old Object(cumulativeViewCount): ".$oldObj->getViewCount().'<br/>';
            echo "value from New Object(cumulativeViewCount): ".$newObj->getViewCount().'<br/>';

            echo "value from Old Object(institute_name): ".$oldObj->getInstituteName().'<br/>';
            echo "value from New Object(institute_name): ".$newObj->getInstituteName().'<br/>';

            echo "value from Old Object(university_type_BasedCheck): ".$oldObj->isDirectlyAssociatedWithUniversity().'<br/>';
            echo "value from New Object(university_type_BasedCheck): ".$newObj->isDirectlyAssociatedWithUniversity().'<br/>';

            echo "value from Old Object(courseDescription): ".$oldObj->getCourseDescription().'<br/>';
            echo "value from New Object(courseDescription): ".$newObj->getCourseDescription().'<br/>';

            echo "value from Old Object(university_type): ".$oldObj->getUniversityType().'<br/>';
            echo "value from New Object(university_type): ".$newObj->getUniversityType().'<br/>';

            echo "value from Old Object(universityId): ".$oldObj->getUniversityId().'<br/>';
            echo "value from New Object(universityId): ".$newObj->getUniversityId().'<br/>';

            echo "value from Old Object(university_name): ".$oldObj->getUniversityName().'<br/>';
            echo "value from New Object(university_name): ".$newObj->getUniversityName().'<br/>';

            echo "value from Old Object(jobProfile): <br/>";
            _p($oldObj->getJobProfile());
            echo "value from New Object(jobProfile): <br/>";
            _p($newObj->getJobProfile());

//            echo "value from Old Object(scholarshipDescription): ".$oldObj->getScholarshipDescription().'<br/>';
//            echo "value from New Object(scholarshipDescription): ".$newObj->getScholarshipDescription().'<br/>'; //removed
//
//            echo "value from Old Object(scholarshipEligibility): ".$oldObj->getScholarshipEligibility().'<br/>';
//            echo "value from New Object(scholarshipEligibility): ".$newObj->getScholarshipEligibility().'<br/>';
//
//            echo "value from Old Object(scholarshipDeadLine): ".$oldObj->getScholarshipDeadLine().'<br/>';
//            echo "value from New Object(scholarshipDeadLine): ".$newObj->getScholarshipDeadLine().'<br/>';
//
//            echo "value from Old Object(scholarshipAmount): ".$oldObj->getScholarshipAmount().'<br/>';
//            echo "value from New Object(scholarshipAmount): ".$newObj->getScholarshipAmount().'<br/>';
//
//            echo "value from Old Object(scholarshipCurrency): ".$oldObj->getScholarshipCurrency().'<br/>';
//            echo "value from New Object(scholarshipCurrency): ".$newObj->getScholarshipCurrency().'<br/>';
//
//            echo "value from Old Object(scholarshipCurrencyCode): ".$oldObj->getScholarshipCurrencyCode().'<br/>';
//            echo "value from New Object(scholarshipCurrencyCode): ".$newObj->getScholarshipCurrencyCode().'<br/>';
//
//            echo "value from Old Object(scholarshipLink): ".$oldObj->getScholarshipLink().'<br/>';
//            echo "value from New Object(scholarshipLink): ".$newObj->getScholarshipLink().'<br/>';
//
//            echo "value from Old Object(customScholarship): ".$oldObj->getCustomScholarship().'<br/>';
//            echo "value from New Object(customScholarship): ".$newObj->getCustomScholarship().'<br/>';

            echo "value from Old Object(scholarshipURLCourseLevel): ".$oldObj->getScholarshipLinkCourseLevel().'<br/>';
            echo "value from New Object(scholarshipURLCourseLevel): ".$newObj->getScholarshipLinkCourseLevel().'<br/>';

            echo "value from Old Object(scholarshipURLDeptLevel): ".$oldObj->getScholarshipLinkDeptLevel().'<br/>';
            echo "value from New Object(scholarshipURLDeptLevel): ".$newObj->getScholarshipLinkDeptLevel().'<br/>';

            echo "value from Old Object(scholarshipURLUniversityLevel): ".$oldObj->getScholarshipLinkUniversityLevel().'<br/>';
            echo "value from New Object(scholarshipURLUniversityLevel): ".$newObj->getScholarshipLinkUniversityLevel().'<br/>';

            echo "value from Old Object(facultyInfoURL): ".$oldObj->getFacultyInfoLink().'<br/>';
            echo "value from New Object(facultyInfoURL): ".$newObj->getFacultyInfoLink().'<br/>';

            echo "value from Old Object(faqURL): ".$oldObj->getCourseFaqLink().'<br/>';
            echo "value from New Object(faqURL): ".$newObj->getCourseFaqLink().'<br/>';

            echo "value from Old Object(alumniInfoURL): ".$oldObj->getAlumniInfoLink().'<br/>';
            echo "value from New Object(alumniInfoURL): ".$newObj->getAlumniInfoLink().'<br/>';

            echo "value from Old Object(ScholarshipBasedCheck): ".$oldObj->isOfferingScholarship().'<br/>';
            echo "value from New Object(ScholarshipBasedCheck): ".$newObj->isOfferingScholarship().'<br/>';

            echo "value from Old Object(courseCategory): ".$oldObj->getCourseSubCategoryObj().'<br/>';
            echo "value from New Object(courseCategory): ".$newObj->getCourseSubCategoryObj().'<br/>';

            echo "value from Old Object(desiredCourseId): ".$oldObj->getDesiredCourseId().'<br/>';
            echo "value from New Object(desiredCourseId): ".$newObj->getDesiredCourseId().'<br/>';

            echo "value from Old Object(specializationIds): ".$oldObj->getLDBCourseId().'<br/>';
            echo "value from New Object(specializationIds): ".$newObj->getLDBCourseId().'<br/>';

            echo "value from Old Object(classProfile): <br/>";
            _p($oldObj->getClassProfile());
            echo "value from New Object(classProfile): <br/>";
            _p($newObj->getClassProfile());

            echo "value from Old Object(country_id): ".$oldObj->getCountryId().'<br/>';
            echo "value from New Object(country_id): ".$newObj->getCountryId().'<br/>';

            echo "value from Old Object(country_name): ".$oldObj->getCountryName().'<br/>';
            echo "value from New Object(country_name): ".$newObj->getCountryName().'<br/>';

            echo "value from Old Object(city_name): ".$oldObj->getCityName().'<br/>';
            echo "value from New Object(city_name): ".$newObj->getCityName().'<br/>';

            echo "value from Old Object(customFees): <br/>";
            _p($oldObj->getCustomFees());
            echo "value from New Object(customFees): <br/>";
            _p($newObj->getCustomFees());

            echo "value from Old Object(roomBoard): ".$oldObj->getRoomBoard().'<br/>';
            echo "value from New Object(roomBoard): ".$newObj->getRoomBoard().'<br/>';

            echo "value from Old Object(insurance): ".$oldObj->getInsurance().'<br/>';
            echo "value from New Object(insurance): ".$newObj->getInsurance().'<br/>';

            echo "value from Old Object(transportation): ".$oldObj->getTransportation().'<br/>';
            echo "value from New Object(transportation): ".$newObj->getTransportation().'<br/>';

            echo "value from Old Object(subCategoryId): ".$oldObj->getCourseSubCategory().'<br/>';
            echo "value from New Object(subCategoryId): ".$newObj->getCourseSubCategory().'<br/>';

            echo "value from Old Object(TotalFees): ".$oldObj->getTotalFees().'<br/>';
            echo "value from New Object(TotalFees): ".$newObj->getTotalFees().'<br/>';

//            echo "value from Old Object(cleanForCategorypage): ".$oldObj->cleanForCategorypage().'<br/>';
//            echo "value from New Object(cleanForCategorypage): ".$newObj->cleanForCategorypage().'<br/>';

            echo "value from Old Object(applicationDetailId): ".$oldObj->getCourseApplicationDetail().'<br/>';
            echo "value from New Object(applicationDetailId): ".$newObj->getCourseApplicationDetail().'<br/>';

            echo "value from Old Object(isRmcEnabled): ".$oldObj->getRmcEnabledDetail().'<br/>';
            echo "value from New Object(isRmcEnabled): ".$newObj->getRmcEnabledDetail().'<br/>';

            echo "value from Old Object(isRmcEnabled_applicationDetailId_basedCheck): ".$oldObj->showRmcButton().'<br/>';
            echo "value from New Object(isRmcEnabled_applicationDetailId_basedCheck): ".$newObj->showRmcButton().'<br/>';

            echo "value from Old Object(courseWebsiteURL): ".$oldObj->getCourseWebsite().'<br/>';
            echo "value from New Object(courseWebsiteURL): ".$newObj->getCourseWebsite().'<br/>';

//
//            echo "value from Old Object(): ".$oldObj->.'<br/>';
//            echo "value from New Object(): ".$newObj->.'<br/>';
//
//            echo "value from Old Object(): ".$oldObj->.'<br/>';
//            echo "value from New Object(): ".$newObj->.'<br/>';
//
//            echo "value from Old Object(): ".$oldObj->.'<br/>';
//            echo "value from New Object(): ".$newObj->.'<br/>';
//
//            echo "value from Old Object(): ".$oldObj->.'<br/>';
//            echo "value from New Object(): ".$newObj->.'<br/>';

            echo "</div>";
            echo "</body>";
            echo "</html>";

        }


    }

    function checkCourseReco($courseId){

            if(empty($courseId)){
                  die("Please specify course id");
            }

            $headers = array('authrequest: INFOEDGE_SHIKSHA');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://172.16.3.107:9016/listingrecommendations/api/v1/info/getAbroadAlsoViewedCourseIds?courseId=$courseId&count=20");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); //timeout in seconds
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

            // execute the curl request        
            $result = array();
            $result = curl_exec($ch);
            $culrRetcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            $oldReco = array();
            if($culrRetcode == 200){
                  $oldReco = json_decode($result, true);
                  $oldReco = $oldReco['data'];
            }

            $abroadcoursemodel = $this->load->model("listing/abroadcoursemodel");    

            $reco = $abroadcoursemodel->getTestCourseReco($courseId, $oldReco);
            $displayData['inputCourseDetails'] = $reco['courseInfo'];
            $displayData['collabReco'] = $reco['reco'];
            $displayData['oldReco'] = $reco['detailsCourses'];
            $displayData['courseId'] = $courseId;

            $this->load->view("listing/testcoursereco", $displayData);


    }
}

?>
