<?php

/**
 * Created by PhpStorm.
 * User: ankur
 * Date: 14/9/15
 * Time: 1:18 PM
 */
class Listings_model extends MY_Model
{
    function __construct()
    {
        parent::__construct("MISTracking");
    }

    public function getData($criteria = '', $splits = array())
    {

        $groupBy = '';

        switch ($criteria) {
            case 'device':
                $groupBy     = 'tracking.siteSource';
                $selectPivot = $groupBy;
                break;

            case 'page':
                $groupBy     = 'tracking.page';
                $selectPivot = $groupBy;
                break;

            case 'widget':
                $groupBy     = 'tracking.id';
                $selectPivot = 'CONCAT( page, " > ", CONCAT(UCASE(MID(keyname, 1, 1 )), MID(keyname, 2 )), " > ",  CONCAT(UCASE(MID(widget, 1, 1 )), MID(widget, 2 ) ), " > ", siteSource )';
                break;

            case 'paidOrFree':
                $groupBy     = 'leadData.listing_subscription_type';
                $selectPivot = $groupBy;
                break;

            case 'action':
                $groupBy     = 'leadData.action';
                $selectPivot = $groupBy;
                break;
            case 'session':
                $groupBy    = 'leadData.visitorsessionid';
                $selectPivot = $groupBy;
        }

        $selectFields = array(
            $selectPivot . ' AS Pivot',
            'COUNT(distinct leadData.id) AS ResponseCount'
        );

        $dateDifference = 30;
        $where = array(
            "DATE(leadData.submit_date) <= curdate()",
            "DATE(leadData.submit_date) >= date_sub(curdate(), interval $dateDifference day)"
        );

        if (count($splits) > 0) {
            $where = array(
                "DATE(leadData.submit_date) >= '$splits[startDate]'",
                "DATE(leadData.submit_date) <= '$splits[endDate]'"
            );
        }

        $db = $this->getReadHandle();

        $db->select($selectFields);
        $db->from('tempLMSTable leadData');

        $trackingFilters = array(
            'leadData.tracking_keyid = tracking.id',
            "tracking.site != 'Study Abroad'"
        );
        if(count($splits) > 0){
            if( ( $getWhere = $this->getWhereClauseForSplit($splits)) != '') {
                $trackingFilters[] = $getWhere;
            };

            if ($splits['source'] != '' && $splits['source'] != 'all') {
                $trackingFilters[] = "tracking.siteSource = '$splits[source]'";
            }

            if( $splits['widgetId'] != 0) {
                $trackingFilters[] = "tracking.id = '$splits[widgetId]'";
            }
        }
        $db->join('tracking_pagekey tracking', implode(" AND ", $trackingFilters), 'inner');

        $categoryInformationFilter = array(
            'leadData.listing_type_id = categoryInformation.listing_type_id',
            'leadData.listing_type = categoryInformation.listing_type',
//            "categoryInformation.status in ('live', 'deleted')",
            "categoryInformation.listing_type = 'course'"
        );
        if (count($splits) > 0) {
            if ($splits['pivotType'] != 'all') {
                $categoryInformationFilter[] = "leadData.listing_subscription_type = '$splits[pivotType]'";
            }
            if ($splits['metricSubId'] != '') {
                $categoryInformationFilter[] = "categoryInformation.category_id IN ($splits[metricSubId])";
            }
        }
        $db->join('listing_category_table categoryInformation', implode(" AND ", $categoryInformationFilter), 'inner');

        $whereClause = implode(" AND ", $where);

        $db->where($whereClause);
        $db->group_by($groupBy);
        $db->order_by("ResponseCount", "desc");

        return $db->get()->result();
        $result = $db->get()->result();
        _p($db->last_query()); return;
    }


    public function getSplitData($criteria = '', $inputRequest = array())
    {

        $groupBy = '';

        switch ($criteria) {
            case 'device':
            case 'responseDevice':
                $groupBy     = 'tracking.siteSource';
                $selectPivot = $groupBy;
                break;

            case 'page':
                $groupBy     = 'tracking.page';
                $selectPivot = $groupBy;
                break;

            case 'widget':
                $groupBy     = 'tracking.keyName';
                $selectPivot = $groupBy;
//                $selectPivot = 'CONCAT( page, " > ", CONCAT(UCASE(MID(keyname, 1, 1 )), MID(keyname, 2 )), " > ",  CONCAT(UCASE(MID(widget, 1, 1 )), MID(widget, 2 ) ), " > ", siteSource )';
                break;

            case 'paidOrFree':
            case 'pivotType':
                $groupBy     = 'leadData.listing_subscription_type';
                $selectPivot = $groupBy;
                break;

            case 'action':
                $groupBy     = 'leadData.action';
                $selectPivot = $groupBy;
                break;
            case 'session':
            case 'responseTrafficSource':
                $groupBy    = 'leadData.visitorsessionid';
                $selectPivot = $groupBy;
        }

        $selectFields = array(
            $selectPivot . ' AS PivotName',
            'COUNT(distinct leadData.id) AS ScalarValue'
        );

        $dateDifference = 30;
        $where = array(
            "DATE(leadData.submit_date) <= curdate()",
            "DATE(leadData.submit_date) >= date_sub(curdate(), interval $dateDifference day)"
        );

        if (count($inputRequest) > 0) {
            $where = array(
                "DATE(leadData.submit_date) >= '$inputRequest[startDate]'",
                "DATE(leadData.submit_date) <= '$inputRequest[endDate]'"
            );
        }

        $db = $this->getReadHandle();

        $db->select($selectFields);
        $db->from('tempLMSTable leadData');

        $trackingFilters = array(
            'leadData.tracking_keyid = tracking.id',
            "tracking.site != 'Study Abroad'"
        );
        if(count($inputRequest) > 0){
            if( ( $getWhere = $this->getWhereClauseForSplit($inputRequest)) != '') {
                $trackingFilters[] = $getWhere;
            };

            if ($inputRequest['source'] != '' && $inputRequest['source'] != 'all') {
                $trackingFilters[] = "tracking.siteSource = '$inputRequest[source]'";
            }

            if( $inputRequest['widgetId'] != 0) {
                $trackingFilters[] = "tracking.id = '$inputRequest[widgetId]'";
            }
        }
        $db->join('tracking_pagekey tracking', implode(" AND ", $trackingFilters), 'inner');

        $categoryInformationFilter = array(
            'leadData.listing_type_id = categoryInformation.listing_type_id',
            'leadData.listing_type = categoryInformation.listing_type',
            "categoryInformation.listing_type = 'course'"
        );
        if (count($inputRequest) > 0) {
            if ($inputRequest['pivotType'] != 'all') {
                $categoryInformationFilter[] = "leadData.listing_subscription_type = '$inputRequest[pivotType]'";
            }
            if ($inputRequest['metricSubId'] != '') {
                $categoryInformationFilter[] = "categoryInformation.category_id IN ($inputRequest[metricSubId])";
            }
        }
        $db->join('listing_category_table categoryInformation', implode(" AND ", $categoryInformationFilter), 'inner');

        $whereClause = implode(" AND ", $where);

        $db->where($whereClause);
        $db->group_by($groupBy);
        $db->order_by("ScalarValue", "desc");

        return $db->get()->result();
    }

    /**
     * @param string $source The device type (also known as the source application)
     * @param string $metricName The category name which is used to determine for which page the responses are to be calculated
     * @param string $metricId The category id
     * @param string $metricSubId The subcategory name
     * @param string $pivotName The name of the pivot (response in this case)
     * @param string $pivotType The type of the pivot (paid response / free response)
     * @param int    $widgetId The id of the widget in question (taken from the <code>tracking_pagekey</code> table)
     * @param array  $dateRange The <code>startDate</code> and the <code>endDate</code>
     * @param string $viewType The type of view (day wise / week wise / month wise)
     * @see Listings::getDateDifference
     *
     * @return Array A list of stdClass objects corresponding to that many number of records satisfying the criteria mentioned above
     */
    public function pullInformation($source = 'all', $metricName = 'category', $metricId = '3', $metricSubId = '23', $pivotName = 'response', $pivotType = 'all', $widgetId = 0, $dateRange = array(), $viewType='day')
    {

        $db = $this->getReadHandle();
        $selectFields = array(
            'DATE(leadData.submit_date) AS ResponseDate',
            'categoryInformation.category_id as SubCategoryId',
            'tracking.siteSource AS DeviceName',
            'leadData.listing_subscription_type AS ResponseType',
//            'CONCAT( page, " > ", CONCAT(UCASE(MID(keyname, 1, 1 )), MID(keyname, 2 )), " > ",  CONCAT(UCASE(MID(widget, 1, 1 )), MID(widget, 2 ) ) ) AS WidgetName',
//            'COUNT(1) AS ResponseCount',
            'COUNT(distinct leadData.id) AS ResponseCount',
            'leadData.action AS Action');
        $db->select($selectFields);
        $db->from('tracking_pagekey tracking');

        $trackingFilters = array(
            'tracking.id = leadData.tracking_keyid',
            "tracking.site != 'Study Abroad'",
        );
        if ( ($getWhere = $this->getWhereClauseForSplit(array('metricName'=>$metricName))) !='' ){
            $trackingFilters[] = $getWhere;
        };
        if ($source != 'all') {
            $trackingFilters[] = "tracking.siteSource = '$source'";
        }
        if ($widgetId != 0) {
            $trackingFilters[]   = "tracking.id= $widgetId";
//            $groupBy[] = "leadData.tracking_keyid";
        }
        $db->join('tempLMSTable leadData', implode(" AND ", $trackingFilters), 'inner');

        $responseInformationFilters = array(
            "leadData.listing_type_id = categoryInformation.listing_type_id",
            "categoryInformation.listing_type = 'course'",
//            "categoryInformation.status in ('live', 'deleted')",
        );
        if ($pivotType != 'all') {
            $responseInformationFilters[] = "leadData.listing_subscription_type = '$pivotType' ";
        }
        if($metricSubId != '' && $metricSubId != 0) {
            $responseInformationFilters[] = "categoryInformation.category_id in ($metricSubId)";
        }
        $db->join('listing_category_table categoryInformation', implode(" AND ", $responseInformationFilters), 'inner');

        $where   = array(
            "DATE(leadData.submit_date) >= '$dateRange[startDate]'",
            "DATE(leadData.submit_date) <= '$dateRange[endDate]'",
        );
        $whereClause = implode(" AND ", $where);
        $db->where($whereClause);

        $numberOfDays = $this->getDateDifference($dateRange['startDate'], $dateRange['endDate']);
        $groupBy = array(
            "DATE(leadData.submit_date)",
        );

        if (( $numberOfDays > 60 && $numberOfDays <= 180) || $viewType == 'week' ) {
            $groupBy = array(
                "WEEK( ADDDATE( `submit_date` , WEEKDAY( '$dateRange[startDate]' ) ) )",
            );
        } else if ( ($numberOfDays > 180) || $viewType == 'month') {
            $groupBy = array(
                "MONTH(leadData.submit_date)",
            );
        }

        $groupBy[] = "SubCategoryId";

        $groupByClause = implode(",", $groupBy);
        $db->group_by($groupByClause);
        $db->order_by("leadData.submit_date", "desc");

        return $db->get()->result();
    }

    public function getResponseTrends($source = 'all', $metricName = 'category', $metricId = '3', $metricSubId = '23', $pivotName = 'response', $pivotType = 'all', $widgetId = 0, $dateRange = array(), $viewType=1)
    {

        $db = $this->getReadHandle();
        $selectFields = array(
            'DATE(leadData.submit_date) AS Date',
            'COUNT(distinct leadData.id) AS ScalarValue',
        );

        $groupBy = array(
            "DATE(leadData.submit_date)",
        );

        $numberOfDays = $this->getDateDifference($dateRange['startDate'], $dateRange['endDate']);
        if ( $viewType == 2 ) {

            $selectFields = array(
                "DATE_ADD(MIN(Date(leadData.submit_date)), INTERVAL(1-DAYOFWEEK(MIN(Date(leadData.submit_date)))) +1 DAY) as Date",
                "YEARWEEK(leadData.submit_date, 1)",
                "COUNT(DISTINCT leadData.id) AS ScalarValue"
            );
            $groupBy = array(
                "YEARWEEK(leadData.submit_date, 1)",
            );
        } else if ( $viewType == 3 ) {
            $selectFields = array(
                "DATE_SUB(MIN(Date(leadData.submit_date)), INTERVAL (DAYOFMONTH( MIN( Date(leadData.submit_date) ) ) ) -1 DAY) as Date",
                "COUNT(DISTINCT leadData.id) AS ScalarValue"
            );
            $groupBy = array(
                "MONTH(leadData.submit_date)",
            );
        }

        $db->select($selectFields);
        $db->from('tracking_pagekey tracking');

        $trackingFilters = array(
            'tracking.id = leadData.tracking_keyid',
            "tracking.site != 'Study Abroad'",
        );
        if ( ($getWhere = $this->getWhereClauseForSplit(array('metricName'=>$metricName))) !='' ){
            $trackingFilters[] = $getWhere;
        };
        if ($source != 'all') {
            $trackingFilters[] = "tracking.siteSource = '$source'";
        }
        if ($widgetId != 0) {
            $trackingFilters[]   = "tracking.id= $widgetId";
//            $groupBy[] = "leadData.tracking_keyid";
        }
        $db->join('tempLMSTable leadData', implode(" AND ", $trackingFilters), 'inner');

        $responseInformationFilters = array(
            "leadData.listing_type_id = categoryInformation.listing_type_id",
            "categoryInformation.listing_type = 'course'",
//            "categoryInformation.status in ('live', 'deleted')",
        );
        if ($pivotType != 'all') {
            $responseInformationFilters[] = "leadData.listing_subscription_type = '$pivotType' ";
        }
        if($metricSubId != '' && $metricSubId != 0) {
            $responseInformationFilters[] = "categoryInformation.category_id in ($metricSubId)";
        }
        $db->join('listing_category_table categoryInformation', implode(" AND ", $responseInformationFilters), 'inner');

        $where   = array(
            "DATE(leadData.submit_date) >= '$dateRange[startDate]'",
            "DATE(leadData.submit_date) <= '$dateRange[endDate]'",
        );
        $whereClause = implode(" AND ", $where);
        $db->where($whereClause);

        $db->group_by($groupBy);
        $db->order_by("leadData.submit_date", "desc");

        return $db->get()->result();
        $result = $db->get()->result();
        _p($db->last_query()); die;
    }

    public function topTiles($source = 'all', $metricName = 'category', $metricId = '3', $metricSubId = '23', $pivotName = 'response', $pivotType = 'all', $widgetId = 0, $dateRange = array())
    {

        $db = $this->getReadHandle();

        $db->select('count(DISTINCT(courseId)) as paidCoursesCount');
        $db->from('courseSubscriptionHistoricalDetails courses');

        $paidProducts = array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID);

        $db->where("courses.addedOnDate <= '$dateRange[endDate]'");
        $db->where('(courses.endedOnDate >=\''.$dateRange['startDate'].'\' OR courses.endedOnDate = "0000:00:00" ) ','',false);
        if($source != 'all'){
            $db->where('courses.source',$source);
        }

        if($metricSubId != '' && $metricSubId != 0) {
            $db->join('listing_category_table categoryInformation', "courses.courseId = categoryInformation.listing_type_id
        AND categoryInformation.listing_type = 'course'", 'inner');
            $db->where("categoryInformation.category_id in ($metricSubId)");
        }
        $db->where_in('courses.packType',$paidProducts);
        $db->where("courses.source = 'national'");

        $result = $db->get()->result();
        $paidCoursesCount = $result[0]->paidCoursesCount;


        $getUserCount = function($db, $dateRange, $pageFilter='', $pivotType, $widgetId, $source){

            $selectFields = array(
                "distinct(userId) as users"
            );

            $db->select($selectFields);
            $db->from("tracking_pagekey tracking");
            $joinConditions = array(
                "responses.tracking_keyid = tracking.id",
                "responses.listing_type = 'course'",
                "tracking.site != 'Study Abroad'"
            );
            if($pageFilter != ''){
                $joinConditions[] = $pageFilter;
            }
            if($pivotType != 'all'){
                $joinConditions[] = "responses.listing_subscription_type = '$pivotType'";
            }
            if($widgetId != 0){
                $joinConditions[] = "tracking.id = $widgetId";
            }
            if ($source != 'all') {
                $joinConditions[] = "tracking.siteSource = '$source'";
            }


            $db->join("tempLMSTable responses", implode(" AND ", $joinConditions), "inner");
            $whereClauses = array(
                "DATE(submit_date) >= '$dateRange[startDate]'",
                "DATE(submit_date) <= '$dateRange[endDate]'"
            );
            $db->where(implode(" AND ", $whereClauses));

            $allUsers = $db->get()->result();
            $allUserCount = count($allUsers);

            foreach($allUsers as $key => $oneUser){

                $userId = $oneUser->users;
                $db->select('userId');
                $db->from("tempLMSTable responses");

                $whereClauses = array(
                    "userId = $userId",
                    "DATE(submit_date) < '$dateRange[startDate]'"
                );
                $whereClauses = implode(" AND ", $whereClauses);

                $db->where($whereClauses);
                $db->limit(1);
                $result = $db->get()->result();

                if(isset($result[0]->userId)){
                    unset($allUsers[$key]);
                }
            }
            $firstTimeUserCount = count($allUsers);


            return array(
                'all' => $allUserCount,
                'firstTime' => $firstTimeUserCount
            );
        };

        $pageFilter = $this->getWhereClauseForSplit(array('metricName'=>$metricName));
        $userIds = $getUserCount($db, $dateRange, $pageFilter, $pivotType, $widgetId, $source);

        return array(
            'paidCoursesCount' => $paidCoursesCount,
            'firstTimeUserCount' => $userIds['firstTime'],
            'allUserCount' => $userIds['all']
        );
    }

    /**
     * Get the paid courses
     *
     * @param stdClass $inputRequest An object which contains the data input from the GET / POST data
     * @param string $team Can assume any of the values global / shiksha / abroad / studyAbroad / domestic / national
     *
     * @return array
     */
    public function getPaidCoursesCount($inputRequest, $team='global'){
        $subcategoryIds = '';
        if($inputRequest->category != '' && $inputRequest->category != 0){
            if($inputRequest->subcategoryId == 0 || $inputRequest->subcategoryId == ''){
                $subcategoryIds = $this->getSubCategories($inputRequest->category);
                $subcategoryIdsComposite = array();
                foreach($subcategoryIds as $oneSubcategoryId){
                    $subcategoryIdsComposite[] = $oneSubcategoryId->SubCategoryId;
                }
                $subcategoryIds = implode(", ", $subcategoryIdsComposite);
            }
        }

        $db = $this->getReadHandle();
        $dateRange['startDate'] = $inputRequest->startDate;
        $dateRange['endDate'] = $inputRequest->endDate;

        $db->select('count(DISTINCT(courseId)) as paidCoursesCount');
        $db->from('courseSubscriptionHistoricalDetails courses');


        $db->where("courses.addedOnDate <= '$dateRange[endDate]'");
        $db->where('(courses.endedOnDate >=\''.$dateRange['startDate'].'\' OR courses.endedOnDate = "0000:00:00" ) ','',false);

        if($inputRequest->subcategoryId != '' && $inputRequest->subcategoryId != 0) {
            $subcategoryIds = $inputRequest->subcategoryId;
        }

        if($subcategoryIds != ''){

            $db->join('listing_category_table categoryInformation', "courses.courseId = categoryInformation.listing_type_id
        AND categoryInformation.listing_type = 'course'", 'inner');
            $db->where("categoryInformation.category_id IN ($subcategoryIds)");
        }


        $paidProducts = array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID);
        if(strtolower($team) == 'domestic' || strtolower($team) == 'national'){
            $db->where("courses.source != 'abroad'");
        }
        $db->where_in('courses.packType',$paidProducts);
        $result = $db->get()->result();
        return $result[0]->paidCoursesCount;
    }

    //    TODO: Replace this with the method Listings::getNewSubCategories()
    public function getSubCategories($categoryId = 3)
    {
        $db = $this->getReadHandle();

        $selectFields = array(
            'boardId AS SubCategoryId',
            'name AS SubCategoryName'
        );

        $where       = array(
            "parentId = $categoryId",
            "flag != 'studyabroad'"
        );
        $whereClause = implode(" AND ", $where);

        $db->select($selectFields);
        $db->from('categoryBoardTable');
        $db->where($whereClause);

        return $db->get()->result();
    }

    public function getNewSubCategories($categoryId = 3)
    {

        /*SELECT
    l.categoryId as SubCategoryId,
    t.CourseName as SubCategoryName
FROM
    tCourseSpecializationMapping t
        INNER JOIN
    LDBCoursesToSubcategoryMapping l ON t.SpecializationId = l.ldbCourseID
WHERE
    scope = 'india' AND t.status = 'live'
    AND l.status = 'live'
    and t.CategoryId = 3
        group by SubCategoryId*/

        $db = $this->getReadHandle();

        $selectFields = array(
            'l.categoryId AS SubCategoryId',
            't.CourseName AS SubCategoryName'
        );

        $where       = array(
            't.CategoryId' => $categoryId,
            'scope' => 'india',
            't.status' => 'live',
            'l.status' => 'live',
        );

        $db->select($selectFields);
        $db->from('tCourseSpecializationMapping t');
        $db->join('LDBCoursesToSubcategoryMapping l', 't.SpecializationId = l.ldbCourseID', 'inner');
        $db->where($where);
        $db->group_by('SubCategoryId');

        $result = $db->get()->result();
        return $result;
    }

    public function getCategories()
    {
        $db = $this->getReadHandle();

        $selectFields = array(
            'boardId AS CategoryId',
            'name AS CategoryName'
        );

        $where       = array(
            "parentId = 1",
            "flag != 'studyabroad'"
        );
        $whereClause = implode(" AND ", $where);

        $db->select($selectFields);
        $db->from('categoryBoardTable');
        $db->where($whereClause);

        return $db->get()->result();
    }

    public function getWidgetInformation($pageName='', $widgetId = 0)
    {
        $db = $this->getReadHandle();

        $selectFields = array(
            'id',
            'CONCAT( page, " > ", CONCAT(UCASE(MID(keyname, 1, 1 )), MID(keyname, 2 )), " > ",  CONCAT(UCASE(MID(widget, 1, 1 )), MID(widget, 2 ) ) ) AS WidgetName'
        );

        $where = array();

        if( $pageName!='' && ( $getWhere = $this->getWhereClauseForSplit(array('metricName'=>$pageName)) ) != '') {
            $where[] = $getWhere;
        }
        if ($widgetId != 0) {
            $where[] = "id = $widgetId";
        }

        $where[] = "site != 'Study Abroad'";
        $whereClause = implode(" AND ", $where);

        $db->select($selectFields);
        $db->from('tracking_pagekey tracking');
        $db->where($whereClause);

        return $db->get()->result();
    }

    private function getWhereClauseForSplit($splits)
    {
        $whereClause = '';

        switch($splits['metricName']){
            case 'category':
                $whereClause = "tracking.page = 'categoryPage'";
                break;

            case 'courselisting':
                $whereClause = "tracking.page = 'courseDetailsPage'";
                break;

            case 'institute':
                $whereClause = "tracking.page = 'instituteListingPage'";
                break;

            case 'coursehome':
                $whereClause = "tracking.page = 'courseHomePage'";
                break;

            case 'ranking':
                $whereClause = "tracking.page = 'rankingPage'";
                break;

            case 'exam':
                $whereClause = "tracking.page = 'examPage'";
                break;

            case 'shortlist':
                $whereClause = "tracking.page = 'shortlistPage'";
                break;

            case 'rank_predictor':
                $whereClause = "tracking.page = 'rankPredictor'";
                break;

            case 'home':
                $whereClause = "tracking.page = 'homePage'";
                break;

            case 'exam_calendar':
                $whereClause = "tracking.page = 'eventCalendar'";
                break;

            case 'college_review_home':
            case 'college_review_intermediate':
                $whereClause = "tracking.page = 'collegeReviewPage'";
                break;

            case 'campus_rep_home':
            case 'campus_rep_intermediate':
                $whereClause = "tracking.page = 'campusRepresentative'";
                break;

            case 'college_predictor':
                $whereClause = "tracking.page = 'collegePredictor'";
                break;

            case 'mentorship':
                $whereClause = "tracking.page = 'mentorshipPage'";
                break;

            case 'career_compass':
                $whereClause = "tracking.page = 'careerCompasPage'";
                break;

            case 'qna':
                $whereClause = "tracking.page = 'qnaPage'";
                break;

            case 'article_detail':
                $whereClause = "tracking.page = 'articleDetailPage'";
                break;

            case 'article_home':
                $whereClause = "tracking.page = 'articlePage'";
                break;
            case 'iim_predictor':
                $whereClause = "tracking.page = 'ICP'";

        }

        return $whereClause;
    }

    private function getDateDifference($startDate, $endDate)
    {
        return (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);
    }
    function getCampaignForSessionId($sessionId)
    {
        $dbHandle = $this->getReadHandle();
        $sessionIds = '"'.implode('", "', $sessionId).'"';
        $sql = "SELECT utm_source as campaignName,sessionId FROM session_tracking WHERE sessionId IN (".$sessionIds.")  AND utm_source is not null";
        $result = $dbHandle->query($sql)->result_array();
        return $result;
    }
    function getSourceForSessionId($sessionId = array())
    {
        $dbHandle = $this->getReadHandle();
        $dbHandle->select('source,sessionId');
        $dbHandle->from('session_tracking');
        $dbHandle->where_in('sessionId',$sessionId);
        $result = $dbHandle->get()->result_array();
        return $result;
    }

    function getSubeExams($examId){
        //select blogId,acronym from blogTable where blogType ='exam' AND status = 'live' and parentId=464;
        $dbHandle = $this->getReadHandle();
        $dbHandle->select('blogId as subExamId,acronym as subExamName');
        $dbHandle->from('blogTable');
        $dbHandle->where('blogType','exam');
        $dbHandle->where('status','live');
        $dbHandle->where('parentId',$examId);
        $result = $dbHandle->get()->result_array();
        return $result;
    }

}