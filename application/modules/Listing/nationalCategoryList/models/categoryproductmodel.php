<?php

/**
 * Class categoryproductmodel
 *
 * This class provides model service for the purpose of Category Sponsor CMS
 *
 */
class categoryproductmodel extends  MY_Model
{

    private function initiateModel($mode = "write") {
        if($this->dbHandle && $this->dbHandleMode == 'write') {
            return;
        }

        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        } else {
            $this->dbHandle = $this->getWriteHandle();
        }
    }


    /**
     * Find out the primary listings for a client
     *
     * @param int $clientId The client id in question
     *
     * @return bool|array <code>false</code> in case of exception; list of listings(name and id) for a given client
     */
    public function getPrimaryListings($clientId=0)
    {
        try {

            $this->initiateModel('read');

            // find courses for client from <code>listings_main</code>
            $selectFields = array(
                'listing_type_id'
            );
            $whereClause  = array(
                'status'       => 'live',
                'listing_type' => 'course',
            );
            if ($clientId > 0) {
                $whereClause['username'] = $clientId;
            } else {
                return false;
            }

            $clientCourses = $this->getData('listings_main', $selectFields, $whereClause, 0, $this->dbHandle);
            $courseIds     = array();

            foreach ($clientCourses as $oneCourse) {
                $courseIds[] = $oneCourse['listing_type_id']; // The courses corresponding to the input client
            }

            if (count($courseIds) == 0) {
                return array();
            }

            // Find primary institute id from <code>shiksha_courses</code>
            $selectFields = array('DISTINCT(primary_id)');
            $whereClause  = array(
                "status" => "live"
            );

            $this->dbHandle->select($selectFields);
            $this->dbHandle->from('shiksha_courses');
            $this->dbHandle->where($whereClause);
            $this->dbHandle->where_in("course_id", $courseIds);

            $primaryListings = $this->dbHandle->get()->result();

            $clientListings = array();
            foreach($primaryListings as $onePrimary){
                $clientListings[] = $onePrimary->primary_id;
            }

            if(count($clientListings) == 0){
                return array();
            }

            // Find listing title from <code>listings_main</code>
            $selectFields = array(
                'listing_type_id',
                'listing_type',
                'listing_title',
                'expiry_date'
            );
            $whereClause = array(
                "status" => "live"
            );
            $this->dbHandle->select($selectFields);
            $this->dbHandle->from('listings_main');
            $this->dbHandle->where($whereClause);
            $this->dbHandle->where_in("listing_type_id", $clientListings);
            $this->dbHandle->where_in("listing_type", array('institute', 'university_national'));
            
            // $this->dbHandle->get()->result_array();
            // _p($this->dbHandle->last_query());
            return $this->dbHandle->get()->result_array();

        } catch (Exception $exception) {
            error_log($exception->getMessage());

            return false;
        }
    }

    public function getCriteriaByTier($tierId=0){
        $selectFields = array('criterion_id', 'criterion_name');
        $whereClause = array();
        if($tierId > 0){
            $whereClause = array('tier' => $tierId);
        }
        return $this->getData('category_subscription_criteria', $selectFields, $whereClause, 0);
    }

    public function setMainSponsoredInstitutes($input, $product='main')
    {

        try{

            $dataToInsert = array(
                'status' => 'live',
                'criterion_id' => $input['criterionId'],
                'city_id' => $input['cityId'],
                'state_id' => $input['stateId'],
                'institute_id' => $input['instituteId'],
                'start_date' => $input['subscriptionStartDate'],
                'end_date' => $input['subscriptionEndDate'],
                'subscription_id' => $input['subscriptionId'],
            );


            if($product == 'main' || $product == 'category_sponsor'){
                $dataToInsert['product_type'] = $product;
            } else {
                return array(
                    'result'=> "Failure",
                    'error'=>'Invalid product type specified'
                );
            }

            $this->initiateModel('write');

            $this->dbHandle->select();
            $this->dbHandle->from('category_products');
            $this->dbHandle->where($dataToInsert);

            if($this->dbHandle->count_all_results() > 0){
                return array(
                    'result'=> "Failure",
                    'error'=>'Already set for given criteria.'
                );
            } // Already exists, so no need to make a duplicate entry


            $dataToInsert['added_on'] = date('Y-m-d H:i:s');
            $dataToInsert['added_by'] = $input['loggedInUserId'];

            if($categoryProductId = $this->setData('category_products', $dataToInsert, $this->dbHandle) > 0){
                return array(
                    'result'=>$categoryProductId,
                );
            }

        } catch(Exception $exception){
            error_log($exception->getMessage());
            return false;
        }
    }


    /**
     * Check if a category page exists in the table <code>category_page_seo</code> by performing an inner join on the table <code>category_subscription_criteria</code>:
     * <ul><li>stream_id</li><li>substream_id</li><li>base_course_id</li><li>education_type</li><li>delivery_method</li><li>credential</li></ul>
     *
     * @param int $criterionId The criterion id in question
     *
     * @return bool <code>true</code> If category page exists; <code>false</code> otherwise
     */
    public function checkIfCategoryPageExists($criterionId = 0)
    {
        if(empty($criterionId)) {
            return false;
        }

        try {

            $this->initiateModel('read');

            $categoryPage      = "category_page_seo category";
            $categoryCriterion = "category_subscription_criteria criterion";

            $categoryJoinCriterion = array(
                "category.stream_id = criterion.stream_id",
                "category.substream_id = criterion.substream_id",
                "category.base_course_id = criterion.base_course_id",
                "category.education_type = criterion.education_type",
                "category.delivery_method = criterion.delivery_method",
                "category.credential = criterion.credential",
            );

            $whereClause = array(
                "category.status = 'live'",
                "category.result_count > 0",
                "criterion.criterion_id = $criterionId",
            );

            $this->dbHandle->select();
            $this->dbHandle->from($categoryPage);
            $this->dbHandle->join($categoryCriterion, implode(" AND ", $categoryJoinCriterion), "inner");
            $this->dbHandle->where(implode(" AND ", $whereClause));


//			_p($this->dbHandle->_compile_select()); die;
            if ($this->dbHandle->count_all_results() > 0) {
                return true;
            }
            return false;
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }

    /**
     * The Criterion selected from dropdown must be mapped to any primary course of the institute selected from dropdown
     *
     * @param int        $criterionId
     * @param int|string $listingId
     * @see tables - <code>shiksha_courses</code>(institute column is primary_id), <code>shiksha_courses_type_information</code>)
     *
     * @return bool
     */
    public function checkIfListingExists($criterionId = 0, $listingId = ''){
        if(empty($criterionId) || empty($listingId)) {
            return false;
        }
        try{
            $this->initiateModel('read');

            $categoryCriterion = "category_subscription_criteria criterion";
            $coursesType = 'shiksha_courses_type_information courses_type';
            $courses = 'shiksha_courses course';

            $coursesTypeJoinCategoryCriteria = array(
                "courses_type.stream_id = criterion.stream_id",
                "courses_type.substream_id = criterion.substream_id",
                "courses_type.base_course = criterion.base_course_id",
                "courses_type.credential = criterion.credential",
            );
            $categoryCriteriaJoinCourses = array(
                "course.education_type = criterion.education_type",
                "course.delivery_method = criterion.delivery_method",
                "course.status = 'live'",
                "criterion.criterion_id = $criterionId",
                "course.primary_id IN ($listingId)",
            );

            $this->dbHandle->select();
            $this->dbHandle->from($coursesType);
            $this->dbHandle->join($categoryCriterion, implode(" AND ", $coursesTypeJoinCategoryCriteria), "inner");
            $this->dbHandle->join($courses, implode(" AND ", $categoryCriteriaJoinCourses), "inner");

//			_p($this->dbHandle->_compile_select()); die;
            if($this->dbHandle->count_all_results() > 0){
                return true;
            }

            return false;
        } catch(Exception $exception){
            error_log($exception->getMessage());
            return false;
        }

    }

    /**
     * Used to obtain the main sponsored institutes from the system
     *
     * @param string $product Either of <code>category_sponsor</code> or <code>main</code>
     * @param int    $clientId The input client ID
     * @param int    $cityId The input city ID
     * @param int    $stateId The input state ID
     * @param int    $criterionId The input criterion ID
     *
     * @see \categoryproductmodel::getPrimaryListings for the logic to find out the primary institutes for a client
     * @return bool|array <code>false</code> in case of error / not found data; <code>array</code> containing the data otherwise
     */
    public function getMainSponsoredInstitutes($product='main', $clientId=0, $cityId=0, $stateId=0, $criterionId=0)
    {

        if($clientId <=0 ){
            return false;
        }
        try{

            $this->initiateModel('read');

            $listingsForClient = $this->getPrimaryListings($clientId);

            if(count($listingsForClient) <=0) {
                return "";
            } else {

                $listingIds = array();
                foreach($listingsForClient as $oneListing){
                    $listingIds[] = $oneListing['listing_type_id'];
                }

                $mainListings = 'category_products';
                $criteria = 'category_subscription_criteria';
                $selectFields = array(
                    "category_products.institute_id",
                    "category_products.city_id",
                    "category_products.state_id",
                    "category_products.start_date",
                    "category_products.end_date",
                    "category_products.subscription_id",
                    "category_products.listing_subs_id",
                    "category_subscription_criteria.criterion_name"
                );
                $joinConditions = array(
                    "category_products.criterion_id = category_subscription_criteria.criterion_id",
                    "category_products.status = 'live'",
                    "category_products.end_date > now()",
                );

                if($cityId > 0){
                    $joinConditions[] = "category_products.city_id = $cityId";
                } else if ($stateId > 0) {
                    $joinConditions[] = "category_products.state_id = $stateId";
                }
                if($criterionId > 0){
                    $joinConditions[] = "category_products.criterion_id = $criterionId";
                }

                if($product === 'main' || $product == 'category_sponsor'){
                    $joinConditions[] = "category_products.product_type = '$product'";
                } else {
                    return false;
                }

                $this->dbHandle->select($selectFields);
                $this->dbHandle->from($mainListings);
                $this->dbHandle->join($criteria, implode(" AND ", $joinConditions), "inner");
                $this->dbHandle->where_in("category_products.institute_id", $listingIds);

//				_p($this->dbHandle->_compile_select());die;
                return $this->dbHandle->get()->result_array();
            }
        }catch (Exception $exception){
            error_log($exception->getMessage());
            return false;
        }
    }


    public function unsetMainSponsoredInstitutes($product='main', $idsToUnset=array(), $userId) {
        if(count($idsToUnset) <= 0){
            return false;
        }

        if($product !== 'main' && $product !== 'category_sponsor'){
            return false;
        }

        $whereClause = array(
            'product_type' => $product,
            'status' => 'live'
        );

        $data = array(
            'status' => 'deleted',
            'updated_by' => $userId
        );

        try{
            $this->initiateModel('write');

            $this->dbHandle->where($whereClause);
            $this->dbHandle->where_in("listing_subs_id", $idsToUnset);
            $this->dbHandle->update('category_products', $data);

            return true;
        } catch (Exception $exception){
            error_log($exception->getMessage());
            return false;
        }

    }

    /**
     * Get the used and unused shoshkeles from the system
     *
     * @param int $clientId The client id in question
     *
     * @return mixed
     */
    public function getAllShoshkeles($clientId=0)
    {
        $this->initiateModel('read');

        $selectFields = array(
            "tbanners.bannerid",
            "tbanners.bannername",
            "tbanners.bannerurl",
            "category_subscription_criteria.criterion_name",
            "category_banners.city_id",
            "category_banners.state_id",
            "category_banners.subscription_id",
            "category_banners.banner_link_id"
        );

        $tbannersJoinCategoryBanners = array(
            "tbanners.bannerid = category_banners.banner_id",
            "category_banners.status = 'live'",
            "category_banners.end_date > NOW()"
        );

        $categoryBannerJoinCriteria = array(
            "category_banners.criterion_id = category_subscription_criteria.criterion_id"
        );

        $whereClause = array(
            "tbanners.clientId" => $clientId,
            "tbanners.status" => "live"
        );

        $this->dbHandle->select($selectFields);
        $this->dbHandle->from("tbanners");
        $this->dbHandle->join("category_banners", implode(" AND ", $tbannersJoinCategoryBanners), "LEFT");
        $this->dbHandle->join("category_subscription_criteria", implode(" AND ", $categoryBannerJoinCriteria), "LEFT");
        $this->dbHandle->where($whereClause);

        $result = $this->dbHandle->get();
        return $result->result_array();
    }

    public function getUsedShoshkeleDetails($clientId = ''){


        try{
            $this->initiateModel('read');

            $selectFields = array(
                "tbanners.bannerid",
                "tbanners.bannername",
                "tbanners.bannerurl",
                "category_subscription_criteria.criterion_id",
                "category_subscription_criteria.criterion_name",
                "category_banners.city_id",
                "category_banners.state_id",
                "category_banners.subscription_id",
                "category_banners.banner_link_id"
            );

            $tbannersJoinCategoryBanners = array(
                "tbanners.bannerid = category_banners.banner_id",
                "category_banners.status = 'live'",
                "category_banners.end_date > NOW()"
            );

            $categoryBannerJoinCriteria = array(
                "category_banners.criterion_id = category_subscription_criteria.criterion_id"
            );

            $whereClause = array(
                "tbanners.clientId" => $clientId,
                "tbanners.status" => "live"
            );

            $this->dbHandle->select($selectFields);
            $this->dbHandle->from("tbanners");
            $this->dbHandle->join("category_banners", implode(" AND ", $tbannersJoinCategoryBanners), "INNER");
            $this->dbHandle->join("category_subscription_criteria", implode(" AND ", $categoryBannerJoinCriteria), "INNER");
            $this->dbHandle->where($whereClause);

            $result = $this->dbHandle->get();
            return $result->result_array();
        } catch(Exception $exception){
            error_log();
            return $exception->getMessage();
        }
    }

    public function addShoshkele($shoshkeleData, $userId)
    {
        try {

            $this->initiateModel('write');

            $selectFields = array();
            $this->initiateModel('read');

            $joinConditions = array(
                "tbanners.bannerid = category_banners.banner_link_id",
                "tbanners.status = 'live'",
                "category_banners.status = 'live'",
                "category_banners.end_date > now()",
                "category_banners.criterion_id = " . $shoshkeleData['criterionId'],
                "category_banners.banner_id = " . $shoshkeleData['bannerId'],
            );

            if($shoshkeleData['stateId'] > 0){
                $joinConditions[] = "category_banners.state_id = " . $shoshkeleData['stateId'];
            }

            if($shoshkeleData['cityId'] > 0){
                $joinConditions[] = "category_banners.city_id = " . $shoshkeleData['cityId'];
            }

            $this->dbHandle->select($selectFields);
            $this->dbHandle->from("tbanners");
            $this->dbHandle->join("category_banners", implode(" AND ", $joinConditions), "inner");

            if ($this->dbHandle->count_all_results() > 0) {
                return array(
                    'result' => 'Failure',
                    'error'  => 'Already set for given criteria'
                );
            }

            $dataToInsert = array(
                'banner_id'       => $shoshkeleData['bannerId'],
                'criterion_id'    => $shoshkeleData['criterionId'],
                'city_id'         => $shoshkeleData['cityId'],
                'state_id'        => $shoshkeleData['stateId'],
                'subscription_id' => $shoshkeleData['subscriptionId'],
                'start_date'      => $shoshkeleData['startDate'],
                'end_date'        => $shoshkeleData['endDate'],
                'status'          => 'live',
                'added_on'        => date('Y-m-d H:i:s'),
                'added_by'        => $userId,
                'updated_on'      => date('Y-m-d H:i:s'),
                'updated_by'      => $userId,
            );

            if ($shoshkeleId = $this->setData('category_banners', $dataToInsert, $this->dbHandle) > 0) {
                return array(
                    'result' => $shoshkeleId,
                );
            }

        } catch (Exception $exception) {
            error_log($exception->getMessage());

            return false;
        }
    }

    public function uploadShoshkele($bannerId = 0, $bannerUrl = '', $bannerName = '', $clientId = 0)
    {
        try{
            $this->initiateModel('write');

            if ($bannerId > 0) {
                $selectFields = array(
                    "bannerid"
                );
                /*$whereClause  = array(
                    "trim(bannername)" => $bannerName,
                    "status" => "live",
                    "bannerid <> $bannerId"
                );*/

                $this->dbHandle->select($selectFields);
                $this->dbHandle->from("tbanners");
                // $this->dbHandle->where(implode(" AND ", $whereClause));
                $this->dbHandle->where('trim(bannername)', $bannerName);
                $this->dbHandle->where('status', 'live');
                $this->dbHandle->where('bannerid <> '.$bannerId, null, false);
                if ($this->dbHandle->count_all_results() > 0) {
                    return array('error' => 'Banner with same banner name already exists. Please choose a different banner name');
                }
            }

            $this->dbHandle->trans_start();
            if ($bannerId > 0) {

                //delete the existing shoshkele entry and create a new one with same banner id
                $data        = array(
                    'status' => 'deleted'
                );
                $whereClause = array('bannerid' => $bannerId);
                $this->updateData("tbanners", $data, $whereClause, $this->dbHandle);
            } else {
                $sql      = "select ifnull(max(bannerid),0) + 1 as bannerid from tbanners";
                $query    = $this->dbHandle->query($sql);
                $row      = $query->row();
                $bannerId = $row->bannerid;
            }

            $data = array(
                'bannerid'   => $bannerId,
                'clientid'   => $clientId,
                'bannername' => $bannerName,
                'status'     => 'live',
                'bannerurl'  => $bannerUrl
            );
            $this->setData("tbanners", $data, $this->dbHandle);
            $this->dbHandle->trans_complete();

            if($this->dbHandle->trans_status() === FALSE){
                $this->dbHandle->trans_rollback();
                return false;
            }
            $this->dbHandle->trans_commit();
            return $bannerId;
        } catch(Exception $exception){
            error_log($exception->getMessage());
            return false;
        }
    }


    public function removeShoshkele($type = 'unused', $shoshkeleId = 0, $userId)
    {

        try {

            $this->initiateModel('write');
            switch ($type) {
                case 'unused':
                    // set tbanners as deleted for this banner id
                    // also, check if the banner has a relation in tcoupling via category_banners. if yes, decouple that entry

                    $this->dbHandle->trans_start();
                    $dataToSet = array(
                        'status' => 'deleted',
                    );

                    $this->updateData('tbanners', $dataToSet, array('bannerid' => $shoshkeleId), $this->dbHandle);

                    $bannerLinkIds = $this->getData('category_banners', 'banner_link_id', array('banner_id' => $shoshkeleId), 0, $this->dbHandle);

                    $decoupleBannerLinkIds = array();
                    if (count($bannerLinkIds) > 0) {
                        foreach ($bannerLinkIds as $oneBannerLinkId){
                            $decoupleBannerLinkIds[] = $oneBannerLinkId['banner_link_id'];
                        }

                        $shoshkeleDataToUpdate                     = array(
                            'status'     => 'deleted',
                            'updated_on' => date('Y-m-d H:i:s'),
                            'updated_by' => $userId
                        );
                        $this->dbHandle->where_in('banner_link_id', $decoupleBannerLinkIds);
                        $this->dbHandle->update('category_banners', $shoshkeleDataToUpdate);

                        $this->dbHandle->where_in('bannerlinkid', $decoupleBannerLinkIds);
                        $this->dbHandle->update('tcoupling', array('status' => 'decoupled', 'lastModificationDate' => date('Y-m-d H:i:s')));
                    }

                    $this->dbHandle->trans_complete();

                    if($this->dbHandle->trans_status() === FALSE){
                        $this->dbHandle->trans_rollback();
                        return false;
                    }

                    $this->dbHandle->trans_commit();
                    return true;

                case 'used':
                    // set category_banners as deleted for this banner_link_id
                    // and set coupling status as decoupled from here
                    $this->dbHandle->trans_start();
                    $dataToSet                     = array(
                        'status'     => 'deleted',
                        'updated_on' => date('Y-m-d H:i:s'),
                        'updated_by' => $userId
                    );
                    $whereClause['banner_link_id'] = $shoshkeleId;

                    $this->updateData('category_banners', $dataToSet, $whereClause, $this->dbHandle);

                    $this->updateData('tcoupling', array('status' => 'decoupled', 'lastModificationDate' => date('Y-m-d H:i:s')), array('bannerlinkid' => $shoshkeleId), $this->dbHandle);


                    $this->dbHandle->trans_complete();

                    if($this->dbHandle->trans_status() === FALSE){
                        $this->dbHandle->trans_rollback();
                        return false;
                    }

                    $this->dbHandle->trans_commit();
                    return true;

                default:
                    return false;
            }
        } catch (Exception $exception) {
            error_log($exception->getMessage());

            return false;
        }

    }


    public function getCoupledData($clientId=0, $cityId=0, $stateId=0, $criterionId=0){
        try {

            $selectFields = array(
                "category_banners.banner_id",
                "tbanners.bannername",
                "tcoupling.couplingid",
                "category_products.institute_id",
            );

            $whereClause = array(
                "tbanners.clientId" => $clientId,
                "tbanners.status" => 'live',
                "category_banners.status" => 'live',
                "tcoupling.status" => 'coupled',
                "category_products.status" => 'live',
                "category_products.product_type" => 'category_sponsor',
                "category_products.criterion_id" => $criterionId,
                "category_banners.criterion_id" => $criterionId
            );

            if($cityId > 0){
                $whereClause["category_products.city_id"] = $cityId;
                $whereClause["category_banners.city_id"] = $cityId;

            } else if ($stateId > 0){
                $whereClause["category_products.state_id"] = $stateId;
                $whereClause["category_banners.state_id"] = $stateId;
            }

            $this->initiateModel('read');

            $this->dbHandle->select($selectFields);
            $this->dbHandle->from("tbanners");
            $this->dbHandle->join("category_banners", "category_banners.banner_id = tbanners.bannerid", "inner");
            $this->dbHandle->join("tcoupling", "category_banners.banner_link_id = tcoupling.bannerlinkid", "inner");
            $this->dbHandle->join("category_products", "tcoupling.listingsubsid = category_products.listing_subs_id", "inner");
            $this->dbHandle->where($whereClause);

//            _p($this->dbHandle->_compile_select()); die;
            $result = $this->dbHandle->get();
            return $result->result_array();
        } catch(Exception $exception){
            error_log($exception->getMessage());
            return false;
        }
    }

    public function couple($listingSubscriptionId, $bannerLinkId){

        try{

            $checkCondition = array(
                'status' => 'coupled',
                'listingsubsid' => $listingSubscriptionId,
                'bannerlinkid' => $bannerLinkId
            );

            $this->initiateModel('write');

            $this->dbHandle->select();
            $this->dbHandle->from('tcoupling');
            $this->dbHandle->where($checkCondition);

            if($this->dbHandle->count_all_results() > 0){
                return array(
                    'result' => 'Failure',
                    'error' => 'Already coupled in the system'
                );
            }

            return $this->setData('tcoupling', $checkCondition, $this->dbHandle);
        } catch(Exception $exception){
            error_log($exception->getMessage());
            return false;
        }
    }

    public function decouple($couplingId){
        try{
            $this->initiateModel('write');

            $this->dbHandle->where(array('couplingid' => $couplingId));
            $this->dbHandle->update('tcoupling', array('status' => 'decoupled'));

            return true;
        } catch(Exception $exception){
            error_log($exception->getMessage());
            return false;
        }
    }

    private function getData($tableName='', $select = array(), $conditions=array(), $limit = 0, $connection=false){
        try{
            if(!$connection){
                $this->initiateModel('read');
                $connection = $this->dbHandle;
            }
            $connection->select($select);
            $connection->from($tableName);
            $connection->where($conditions);
            if($limit > 0){
                $connection->limit($limit);
            }
            return $connection->get()->result_array();
        }catch (Exception $exception){
            error_log($exception->getMessage());
            return false;
        }
    }


    private function setData($tableName = '', $data = array(), $connection=false){
        try{
            if(!$connection){
                $this->initiateModel('write');
                $connection = $this->dbHandle;
            }
            $connection->insert($tableName, $data);

            if ($connection->insert_id()) {
                return $connection->insert_id();
            } else {
                return 0;
            }
        } catch(Exception $exception){
            error_log($exception->getMessage());
            return false;
        }
    }

    private function updateData($tableName = '', $data = array(), $conditions = array(), $connection = false)
    {
        try {

            if (!$connection) {
                $this->initiateModel('write');
                $connection = $this->dbHandle;
            }

            $connection->where($conditions);
            $connection->update($tableName, $data);

            return true;
        } catch (Exception $exception) {
            error_log($exception->getMessage());

            return false;
        }
    }

    public function getCategoryPageMainSponsoredInstitutes($criteria) {
        $this->initiateModel('read');

        $sql =  "SELECT DISTINCT institute_id, product_type ".
                "FROM category_products as cp ".
                "INNER JOIN category_subscription_criteria as csc ON csc.criterion_id = cp.criterion_id ".
                "WHERE cp.status = 'live' and cp.start_date < NOW() AND cp.end_date > NOW() ";

        if(!empty($criteria['stream'])) {
            $where[] = "csc.stream_id IN (?) ";
            $params[] = $criteria['stream'];
        }else{
            $where[] = "csc.stream_id  = 0 ";
        }
        if(!empty($criteria['substream'])) {
            $where[] = "csc.substream_id IN (?) ";
            $params[] = $criteria['substream'];
        }else{
            $where[] = "csc.substream_id  = 0 ";
        }
        
        if(!empty($criteria['base_course'])) {
            $where[] = "csc.base_course_id IN (?) ";
            $params[] = $criteria['base_course'];
        }else{
            $where[] = "csc.base_course_id = 0 ";
        }

        if(!empty($criteria['credential'])) {
            $where[] = "csc.credential IN (?) ";
            $params[] = $criteria['credential'];
        }else{
            $where[] = "csc.credential = 0 ";
        }

        if(!empty($criteria['education_type'])) {
            $where[] = "csc.education_type IN (?) ";
            $params[] = $criteria['education_type'];
        }else{
            $where[] = "csc.education_type  = 0 ";
        }

        if(!empty($criteria['delivery_method'])) {
            $where[] = "csc.delivery_method IN (?) ";
            $params[] = $criteria['delivery_method'];
        }else{
            $where[] = "csc.delivery_method  = 0 ";
        }

        if(!empty($criteria['city'])) {
            $whereCity = "cp.city_id IN (?) ";
            $params[] = $criteria['city'];
        }
        if(!empty($criteria['state'])) {
            $whereState = "cp.state_id IN (?) ";
            $params[] = $criteria['state'];
        }

        if(!empty($whereCity) && !empty($whereState)) {
            $where[] = "($whereCity OR $whereState)";
        }
        elseif(!empty($whereCity)) {
            $where[] = $whereCity;
        }
        elseif(!empty($whereState)) {
            $where[] = $whereState;
        }
        else { //when location is not found in criteria, it is 'All India'
            $where[] = "cp.city_id = 1 ";
            $where[] = "(cp.state_id = 0 OR cp.state_id = 1) ";
        }
        
        $whereStatement = implode(' AND ', $where);
        $sql = $sql." AND ".$whereStatement;
        
        $result = $this->dbHandle->query($sql, $params)->result_array();
        
        return $result;
    }

    function findShoskeleBannersForInstitute($instituteId, $criteria){

        if(empty($instituteId)) return;
        $this->initiateModel("read");
        //36992
        $sql = "SELECT tb.bannerurl,csc.stream_id, csc.substream_id,csc.base_course_id, 
                csc.education_type, csc.delivery_method,cp.city_id, cp.state_id 
                FROM category_products cp 
                JOIN tcoupling tc ON(cp.listing_subs_id = tc.listingsubsid and tc.status='coupled')  
                JOIN category_banners cb ON(cb.banner_link_id = tc.bannerlinkid AND cb.status = 'live') 
                JOIN tbanners tb ON(tb.bannerid = cb.banner_id AND tb.status = 'live') 
                JOIN category_subscription_criteria csc ON(cp.criterion_id = csc.criterion_id) 
                WHERE cp.institute_id = ? AND cp.status = 'live' AND cp.start_date <= CURDATE()
                AND cp.end_date >= CURDATE() AND cp.product_type ='category_sponsor'";

        $params[] = $instituteId;

        if(!empty($criteria['stream'])) {
            $where[] = "csc.stream_id IN (?) ";
            $params[] = $criteria['stream'];
        }else{
            $where[] = "csc.stream_id = 0 ";
        }
        if(!empty($criteria['substream'])) {
            $where[] = "csc.substream_id IN (?) ";
            $params[] = $criteria['substream'];
        }else{
            $where[] = "csc.substream_id = 0 ";
        }
        // if(!empty($criteria['specialization'])) {
        //     $where[] = "csc.specialization_id IN (".implode(',', $criteria['specialization']).") ";
        // }
        if(!empty($criteria['base_course'])) {
            $where[] = "csc.base_course_id IN (?) ";
            $params[] = $criteria['base_course'];
        }else{
            $where[] = "csc.base_course_id = 0 ";
        }

        if(!empty($criteria['credential'])) {
            $where[] = "csc.credential IN (?) ";
            $params[] = $criteria['credential'];
        }else{
            $where[] = "csc.credential = 0 ";
        }
        if(!empty($criteria['education_type'])) {
            $where[] = "csc.education_type IN (?) ";
            $params[] = $criteria['education_type'];
        }else{
            $where[] = "csc.education_type = 0 ";
        }

        if(!empty($criteria['delivery_method'])) {
            $where[] = "csc.delivery_method IN (?) ";
            $params[] = $criteria['delivery_method'];
        }
        else{
            $where[] = "csc.delivery_method = 0 ";
        }

        if(!empty($criteria['city'])) {
            $whereCity = "cp.city_id IN (?) ";
            $params[] = $criteria['city'];
        }
        if(!empty($criteria['state'])) {
            $whereState = "cp.state_id IN (?) ";
            $params[] = $criteria['state'];
        }

        if(!empty($whereCity) && !empty($whereState)) {
            $where[] = "($whereCity OR $whereState)";
        }
        elseif(!empty($whereCity)) {
            $where[] = $whereCity;
        }
        elseif(!empty($whereState)) {
            $where[] = $whereState;
        }
        else { //when location is not found in criteria, it is 'All India'
            $where[] = "cp.city_id = 1 ";
            $where[] = "(cp.state_id = 0 OR cp.state_id = 1) ";
        }
        
        $whereStatement = implode(' AND ', $where);
        $sql = $sql." AND ".$whereStatement;
        
        $query = $this->dbHandle->query($sql, $params);
        
        $result = $query->result_array();
        return $result;
    }

    public function getBannerKeywords() {
        $sql = "SELECT criterion_id, criterion_name FROM category_subscription_criteria";
        $this->initiateModel("read");

        $result = $this->dbHandle->query($sql)->result_array();
        
        foreach ($result as $key => $value) {
            $nickname = $value['criterion_name'];
            $criterion_id = $value['criterion_id'];
            if(!empty($nickname)) {
                $bannerKeywords[] = rtrim(strtoupper(preg_replace("/[^a-z0-9_]+/i", "_",$nickname)),"_")."_".$criterion_id;
            }
        }

        return $bannerKeywords;
    }
}