<?php

/**
 * Class overview_model
 *
 * This class will be responsible for providing the data for the overview pages for Shiksha / Domestic / Abroad domains
 *
 */
class Overview_model extends MY_Model
{
    //private  static $trafficdata_sessions;
    //private static $trafficdata_pageviews;

    function __construct()
    {
        parent::__construct('MISTracking');
    }

    private function getElasticSearch()
    {
        require_once('vendor/autoload.php');

        $this->clientParams = array();
        //$this->clientParams['hosts'] = array('10.10.16.72');
        $this->clientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
        //$this->clientParams['hosts'] = array('172.16.3.108');
        $this->MISCommonLib = $this->load->library('trackingMIS/MISCommonLib');
        $this->clientCon = $this->MISCommonLib->_getSearchServerConnection();
        //Overview_model::$trafficdata_sessions = 'trafficdata_sessions_3';
        //Overview_model::$trafficdata_pageviews = 'trafficdata_pageviews_2';
    }
    private function initiateModel($operation='read'){
        if($operation=='read'){
            $this->dbHandle = $this->getReadHandle();
        }else{
            $this->dbHandle = $this->getWriteHandle();
        }
    }

    public function getPages($count, $type = "registration", $team='', $deviceType='', $dateRange = array())
    {
        $this->getElasticSearch();
        if ($type == 'registration') {
            $elasticQuery = $this->getRegistrationQuery($team, $deviceType, $dateRange);
            $elasticQuery['body']['aggs']['pageWise'] = array(
                'terms' => array(
                    'field' => 'pageIdentifier',
                    'size'  => $count,
                    'order' => array('_count' => 'desc')
                )
            );
            $topPages = $this->clientCon->search($elasticQuery);
            return $topPages['aggregations']['pageWise']['buckets'];
        } else if ($type == 'traffic') {
            $elasticQuery  = $this->getQuery($team, $deviceType, $dateRange);
            $elasticQuery['body']['aggs']['pageWise'] = array(
                'terms' => array(
                    'field' => 'landingPageDoc.pageIdentifier',
                    'size'  => $count
                )
            );
            $topPages = $this->clientCon->search($elasticQuery);

            return $topPages['aggregations']['pageWise']['buckets'];
        }
    }

    private function getRegistrationPage($team, $pageIdentifiers, $deviceType, $dateRange, $count){
                $selectFields = array(
                    'tracking.page AS PageName',
                    'COUNT(1) AS ScalarValue',
                );

                $dbConnection = $this->getReadHandle();
                $dbConnection->select($selectFields);
                //$dbConnection->from('tusersourceInfo tinfo');
                $dbConnection->from('registrationTracking rt');
                $dbConnection->join('tracking_pagekey tracking', 'rt.trackingkeyId = tracking.id AND rt.trackingkeyId IS NOT NULL', 'inner');

                $whereClause = array(
                );

                if($deviceType){
                    $whereClause = array(
                        "tracking.siteSource = '$deviceType'",
                    );
                }

                $whereClause[] = "rt.submitDate >= '$dateRange[startDate]'";
                $whereClause[] = "rt.submitDate <= '$dateRange[endDate]'";

                $whereClause[] = "tracking.page IN ('". implode("', '", $pageIdentifiers). "')";

                $this->getTeamSelector($team, $whereClause);
                $whereClause = implode(" AND ", $whereClause);

                $dbConnection->where($whereClause);
                $dbConnection->where('rt.isNewReg','yes');
                $dbConnection->group_by('PageName');
                $dbConnection->order_by("ScalarValue", "DESC");
                $dbConnection->limit($count);

                return $dbConnection->get()->result_array();
            }

    public function getDeltaPages($count, $pageIdentifiers, $aspect, $team, $deviceType, $dates){

        $this->getElasticSearch();
        if($aspect == 'registration') {

            $pageWiseGrouping = array(
                'terms' => array(
                    'field' => 'pageIdentifier',
                    'size'  => $count
                )
            );
            $elasticQuery  = $this->getRegistrationQuery($team, $deviceType, $dates['mom']);
            $elasticQuery['body']['aggs']['pageWise'] = $pageWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['pageIdentifier'] = $pageIdentifiers;
            $topPagesMOM = $this->clientCon->search($elasticQuery);

            $elasticQuery  = $this->getRegistrationQuery($team, $deviceType, $dates['yoy']);
            $elasticQuery['body']['aggs']['pageWise'] = $pageWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['pageIdentifier'] = $pageIdentifiers;
            $topPagesYOY = $this->clientCon->search($elasticQuery);

            return array(
                'mom' => $topPagesMOM['aggregations']['pageWise']['buckets'],
                'yoy' => $topPagesYOY['aggregations']['pageWise']['buckets']
            );


        } else if($aspect == 'traffic'){
            $pageWiseGrouping = array(
                'terms' => array(
                    'field' => 'landingPageDoc.pageIdentifier',
                    'size'  => $count
                )
            );
            $elasticQuery  = $this->getQuery($team, $deviceType, $dates['mom']);
            $elasticQuery['body']['aggs']['pageWise'] = $pageWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['landingPageDoc.pageIdentifier'] = $pageIdentifiers;
            $topPagesMOM = $this->clientCon->search($elasticQuery);

            $elasticQuery  = $this->getQuery($team, $deviceType, $dates['yoy']);
            $elasticQuery['body']['aggs']['pageWise'] = $pageWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['landingPageDoc.pageIdentifier'] = $pageIdentifiers;
            $topPagesYOY = $this->clientCon->search($elasticQuery);

            return array(
                'mom' => $topPagesMOM['aggregations']['pageWise']['buckets'],
                'yoy' => $topPagesYOY['aggregations']['pageWise']['buckets']
            );

        }
    }

    public function getRegistrationQuery($team, $deviceType, $dateRange,$catSubCatFilter =''){
        $elasticQuery = array();
        $elasticQuery['index'] = MISCommonLib::$REGISTRATION_DATA['indexName'];
        $elasticQuery['type'] = MISCommonLib::$REGISTRATION_DATA['type'];

        $elasticQuery['body']['size'] = 0;

        $startDate = $dateRange['startDate'].'T00:00:00';
        $endDate = $dateRange['endDate'].'T23:59:59';

        if($catSubCatFilter){
            $gtZero = array(
                'gt' => 1
            );
            if($catSubCatFilter == 'category'){
                $categoryFilter = array(
                    'range' => array(
                        'categoryId' => $gtZero
                    )
                );
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $categoryFilter;
            }else if($catSubCatFilter == 'subCategory'){
                $subcategoryFilter = array(
                    'range' => array(
                        'subCategoryId' => $gtZero
                    )
                );
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $subcategoryFilter;
            }
        }
        if ($team != 'global' && $team != 'shiksha') {

            $teamFilter = array(
                'term' => array(
                    'site' => 'Study Abroad'
                )
            );
            if ($team == 'abroad' || $team == 'studyabroad') {
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $teamFilter;
            } else if ($team == 'domestic' || $team == 'national') {
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must_not'][] = $teamFilter;
            }
        }

        if($deviceType !='all' && $deviceType !='' && $deviceType != 'undefined'){
            $isMobile = strcasecmp($deviceType, "mobile") == 0 ? "Mobile" : "Desktop";
            $deviceFilter = array(
                "term" => array(
                    "sourceApplication" => $isMobile
                )
            );
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $deviceFilter;
        }

        $startDateFilter = array(
            "range" => array(
                "registrationDate" => array(
                    "gte" => $startDate
                )
            )
        );

        $endDateFilter = array(
            "range" => array(
                "registrationDate" => array(
                    "lte" => $endDate
                )
            )
        );

        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
        return $elasticQuery;
    }


    public function getCategories($count, $type = "registration", $team, $deviceType, $dateRange)
    {
        $this->getElasticSearch();
        if ($type == 'registration') {
            $elasticQuery = $this->getRegistrationQuery($team, $deviceType, $dateRange,'category');
            $elasticQuery['body']['aggs']['categoryWise'] = array(
                'terms' => array(
                    'field' => 'categoryId',
                    'size'  => $count,
                    'order' => array('_count' => 'desc')
                )
            );
            $categories = $this->clientCon->search($elasticQuery);
            $categories = $categories['aggregations']['categoryWise']['buckets'];
            foreach($categories as $index => $oneCategory){
                $categoryName = $this->getName($oneCategory['key'], "category");
                $categories[$index]['category_name'] = $categoryName[0]->CategoryName;
            }

            return $categories;
        } else if($type == 'traffic'){
            $elasticQuery = $this->getQuery($team, $deviceType, $dateRange,'category');

            $elasticQuery['body']['aggs'] = array(
                'categoryWise' => array(
                    'terms' => array(
                        'field' => 'landingPageDoc.categoryId',
                        'size' => $count
                    )
                )
            );
            $categories = $this->clientCon->search($elasticQuery);

            $categories = $categories['aggregations']['categoryWise']['buckets'];
            foreach($categories as $index => $oneCategory){
                $categoryName = $this->getName($oneCategory['key'], "category");
                $categories[$index]['category_name'] = $categoryName[0]->CategoryName;
            }

            return $categories;
        }
    }

    private function getRegistrationCategory($team, $categories, $deviceType, $dateRange, $count){
                if(count($categories)<=0){
                    return;
                }
                $trackingKeys = $this->getTrackingIdsString($team, $deviceType);
                //$userIds = $this->getUserIdsString($trackingKeys, $dateRange);
                /*if($userIds == '')
                    return;*/
                $dbConnection = $this->getReadHandle();
                $selectFields = array(
                    "COUNT(1) AS ScalarValue",
                    "rt.categoryId AS CategoryId"
                );
                $dbConnection->select($selectFields);
                //$dbConnection->from("tCourseSpecializationMapping specialization");
                $dbConnection->from("registrationTracking rt"); // specializationId
                $categories = implode(",", $categories);
                $whereClause = array(
                    //"pref.UserId IN ($userIds)",
                    "rt.trackingkeyId IN ($trackingKeys)",
                    "rt.categoryId in ($categories)"
                );
                $dbConnection->where(implode(" AND ", $whereClause));
                $dbConnection->where('rt.isNewReg','yes');
                $dbConnection->where('rt.submitDate >=',$dateRange['startDate']);
                $dbConnection->where('rt.submitDate <=',$dateRange['endDate']);
//                $dbConnection->where('rt.trackingkeyId IN ('.$trackingKeys.')');
                $dbConnection->group_by("CategoryId");
                $dbConnection->order_by("ScalarValue", "DESC");
                $dbConnection->limit($count);
                return $dbConnection->get()->result();
            }


    public function getDeltaCategories($count, $categories, $aspect, $team, $deviceType, $dates){
        $this->getElasticSearch();
        if ($aspect == 'registration' ) {

            $categoryWiseGrouping = array(
                'terms' => array(
                    'field' => 'categoryId',
                    'size'  => $count
                )
            );
            $elasticQuery  = $this->getRegistrationQuery($team, $deviceType, $dates['mom']);
            $elasticQuery['body']['aggs']['categoryWise'] = $categoryWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['categoryId'] = $categories;
            $topPagesMOM = $this->clientCon->search($elasticQuery);

            $elasticQuery  = $this->getRegistrationQuery($team, $deviceType, $dates['yoy']);
            $elasticQuery['body']['aggs']['categoryWise'] = $categoryWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['categoryId'] = $categories;
            $topPagesYOY = $this->clientCon->search($elasticQuery);

            return array(
                'mom' => $topPagesMOM['aggregations']['categoryWise']['buckets'],
                'yoy' => $topPagesYOY['aggregations']['categoryWise']['buckets']
            );
        } else if($aspect == 'traffic'){


            $categoryWiseGrouping = array(
                'terms' => array(
                    'field' => 'landingPageDoc.categoryId',
                    'size'  => $count
                )
            );
            $elasticQuery  = $this->getQuery($team, $deviceType, $dates['mom']);
            $elasticQuery['body']['aggs']['categoryWise'] = $categoryWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['landingPageDoc.categoryId'] = $categories;
            $topPagesMOM = $this->clientCon->search($elasticQuery);

            $elasticQuery  = $this->getQuery($team, $deviceType, $dates['yoy']);
            $elasticQuery['body']['aggs']['categoryWise'] = $categoryWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['landingPageDoc.categoryId'] = $categories;
            $topPagesYOY = $this->clientCon->search($elasticQuery);

            return array(
                'mom' => $topPagesMOM['aggregations']['categoryWise']['buckets'],
                'yoy' => $topPagesYOY['aggregations']['categoryWise']['buckets']
            );
        }
    }

    public function getTrackingKeys($team, $deviceType, $extraFilter = array())
    {
        $dbConnection = $this->db;
        $dbConnection->select('id');
        $dbConnection->from('tracking_pagekey tracking');
        $whereClause = array();
        if($team){
            $this->getTeamSelector($team, $whereClause);
        }

        if($deviceType){
            $this->getDeviceSelector($deviceType, $whereClause);
        }

        if(isset($extraFilter['keyName']) && $extraFilter['keyName'] != ""){
            $this->getKeyNameSelector($extraFilter['keyName'], $whereClause);
        }

        if(count($whereClause) >0){
            $whereClause = implode(" AND ", $whereClause);
        }
        $dbConnection->where($whereClause);
        $result = $dbConnection->get()->result();
        //echo $dbConnection->last_query();die;
        return $result;
    }

    private function getKeyNameSelector($keyName, &$whereClause){
        $whereClause[] = "tracking.keyName = '$keyName'";
    }

    private function getUserIds($trackingKeys, $dateRange)
    {
        /*SELECT
            userid
        FROM
            tusersourceInfo tinfo
        WHERE
            tinfo.tracking_keyid IN (SELECT
                    id
                FROM
                    tracking_pagekey tpk
                WHERE
                    `tpk`.`site` <> 'Study Abroad')
                AND DATE(time) >= '2015-11-16'
    AND DATE(time) < '2015-12-16'*/
        $dbConnection = $this->getReadHandle();
        $dbConnection->select('userid');
        $dbConnection->from('tusersourceInfo userInfo');
        $whereClause = array(
            "DATE(time) >= '$dateRange[startDate]'",
            "DATE(time) <= '$dateRange[endDate]'",
            "userInfo.tracking_keyid IN ($trackingKeys)"
        );
        $whereClause = implode(" AND ", $whereClause);
        $dbConnection->where($whereClause);

        return $dbConnection->get()->result();
    }

    public function getSubcategories($count, $type = "registration", $team, $deviceType, $dateRange)
    {
        $this->getElasticSearch();
        if ($type == 'registration') {
            $elasticQuery = $this->getRegistrationQuery($team, $deviceType, $dateRange,'subCategory');

            $elasticQuery['body']['aggs'] = array(
                'subcategoryWise' => array(
                    'terms' => array(
                        'field' => 'subCategoryId',
                        'size' => $count,
                        'order' => array('_count' => 'desc')
                    )
                )
            );
        } else if( $type == 'traffic') {
            $elasticQuery = $this->getQuery($team, $deviceType, $dateRange,'subCategory');

            $elasticQuery['body']['aggs'] = array(
                'subcategoryWise' => array(
                    'terms' => array(
                        'field' => 'landingPageDoc.subCategoryId',
                        'size' => $count
                    )
                )
            );
        }
        $subcategories = $this->clientCon->search($elasticQuery);
        $subcategories = $subcategories['aggregations']['subcategoryWise']['buckets'];
        foreach($subcategories as $index => $oneSubcategory){
            $subcategoryName = $this->getName($oneSubcategory['key'], "category");
            $subcategories[$index]['category_name'] = $subcategoryName[0]->CategoryName;
        }
        return $subcategories;
    }

    private function getRegistrationSubcategory($team, $subCategories, $deviceType, $dateRange, $count){

                if(count($subCategories)<=0){
                    return;
                }

                $trackingKeys = $this->getTrackingIdsString($team, $deviceType);

                /*$userIds = $this->getUserIdsString($trackingKeys, $dateRange);
                if($userIds == ''){
                    return;
                }*/
                $dbConnection = $this->getReadHandle();
                $subCategories = implode(", ", $subCategories);

                $selectFields = array(
                    "COUNT(1) AS ScalarValue",
                    "rt.subCatId AS SubCategoryId",
                );

                $dbConnection->select($selectFields);
                $dbConnection->from("registrationTracking rt");
                $whereClause = array(
                    "rt.trackingkeyId IN ($trackingKeys)",
                    "rt.subCatId IN ($subCategories)"
                );

                $whereClause = implode(" AND ", $whereClause);
                $dbConnection->where($whereClause);
                $dbConnection->where('rt.isNewReg','yes');
                $dbConnection->where('rt.submitDate >=',$dateRange['startDate']);
                $dbConnection->where('rt.submitDate <=',$dateRange['endDate']);
                $dbConnection->group_by("SubCategoryId");
                $dbConnection->order_by("ScalarValue", "DESC");
                $dbConnection->limit($count);
                return $dbConnection->get()->result();
            }

    public function getDeltaSubcategories($count, $subCategories, $aspect, $team, $deviceType, $dates){
        $this->getElasticSearch();
        if($aspect == 'registration') {
            $subcategoryWiseGrouping = array(
                'terms' => array(
                    'field' => 'subCategoryId',
                    'size'  => $count
                )
            );
            $elasticQuery  = $this->getRegistrationQuery($team, $deviceType, $dates['mom']);
            $elasticQuery['body']['aggs']['subcategoryWise'] = $subcategoryWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['subCategoryId'] = $subCategories;

            $topPagesMOM = $this->clientCon->search($elasticQuery);
            $elasticQuery  = $this->getRegistrationQuery($team, $deviceType, $dates['yoy']);
            $elasticQuery['body']['aggs']['subcategoryWise'] = $subcategoryWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['subCategoryId'] = $subCategories;

            $topPagesYOY = $this->clientCon->search($elasticQuery);
            return array(
                'mom' => $topPagesMOM['aggregations']['subcategoryWise']['buckets'],
                'yoy' => $topPagesYOY['aggregations']['subcategoryWise']['buckets']
            );

        } else if($aspect == 'traffic'){


            $subcategoryWiseGrouping = array(
                'terms' => array(
                    'field' => 'landingPageDoc.subCategoryId',
                    'size'  => $count
                )
            );
            $elasticQuery  = $this->getQuery($team, $deviceType, $dates['mom']);
            $elasticQuery['body']['aggs']['subcategoryWise'] = $subcategoryWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['landingPageDoc.subCategoryId'] = $subCategories;

            $topPagesMOM = $this->clientCon->search($elasticQuery);
            $elasticQuery  = $this->getQuery($team, $deviceType, $dates['yoy']);
            $elasticQuery['body']['aggs']['subcategoryWise'] = $subcategoryWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['landingPageDoc.subCategoryId'] = $subCategories;

            $topPagesYOY = $this->clientCon->search($elasticQuery);
            return array(
                'mom' => $topPagesMOM['aggregations']['subcategoryWise']['buckets'],
                'yoy' => $topPagesYOY['aggregations']['subcategoryWise']['buckets']
            );
        }
    }

    private function getName($id, $type = "category")
    {
        $dbConnection = $this->getReadHandle();

        if ($type == 'category') {

            /*SELECT
        category.name
    FROM
        categoryBoardTable category
            INNER JOIN
        categoryBoardTable subcategory ON subcategory.parentId = category.boardId
    WHERE
        subcategory.boardId = 56;*/

            $selectFields = array(
                "category.name CategoryName",
            );
            $dbConnection->select($selectFields);
            $dbConnection->from("categoryBoardTable category");
            $whereClause = array(
                "category.boardId" => $id
            );
            $dbConnection->where($whereClause);
        } else if ($type == 'city') {
            $dbConnection->select("city_name AS CityName");
            $dbConnection->from("countryCityTable");
            $dbConnection->where(array("city_id" => $id));
        }

        return $dbConnection->get()->result();
    }

    private function getTrackingIdsString($team, $deviceType)
    {
        $trackingKeysRaw = $this->getTrackingKeys($team, $deviceType);
        $trackingKeys    = array();
        foreach ($trackingKeysRaw as $oneTrackingKey) {
            $trackingKeys[] = $oneTrackingKey->id;
        }
        $trackingKeys = implode(", ", $trackingKeys);
        return $trackingKeys;
    }

    private function getUserIdsString($trackingKeys, $dateRange)
    {
        $userIdsRaw = $this->getUserIds($trackingKeys, $dateRange);
        $userIds    = array();

        foreach ($userIdsRaw as $userId) {
            $userIds[] = $userId->userid;
        }

        $userIds = implode(", ", $userIds);

        return $userIds;
    }

    public function getWidgets($count, $type = "registration", $team, $deviceType, $dateRange)
    {

        /*SELECT
    CONCAT(page,
        ' > ',
        CONCAT(UCASE(MID(keyname, 1, 1)),
            MID(keyname, 2)),
        ' > ',
        CONCAT(UCASE(MID(widget, 1, 1)), MID(widget, 2))) AS WidgetName,
    COUNT(1) AS ScalarValue
FROM
    `tusersourceInfo` tinfo
        INNER JOIN
    `tracking_pagekey` tpk ON `tpk`.`id` = `tinfo`.`tracking_keyid`
WHERE
    `tpk`.`site` <> 'Study Abroad'
    AND DATE(time) >= '2015-12-02'
    AND DATE(time) <= '2015-12-16'
    and tpk.siteSource = 'mobile'
GROUP BY WidgetName
ORDER BY ScalarValue DESC
LIMIT 10;*/
        $trackingIdsRaw = $this->getTrackingIdsString($team, $deviceType);

        $selectFields = array(
            "CONCAT(page, ' > ', CONCAT(UCASE(MID(keyname, 1, 1)), MID(keyname, 2)), ' > ', CONCAT(UCASE(MID(widget, 1, 1)), MID(widget, 2))) AS WidgetName",
            "COUNT(1) AS ScalarValue"
        );
        $dbConnection = $this->getReadHandle();
        $dbConnection->select($selectFields);
        $dbConnection->from("tusersourceInfo userInfo");
        $dbConnection->join("tracking_pagekey tracking", "userInfo.tracking_keyid = tracking.id", "inner");

        $whereClause = array(
            "DATE(time) >= '$dateRange[startDate]'",
            "DATE(time) < '$dateRange[endDate]'",
        );

        if (strcasecmp($team, 'abroad') == 0) {
            $whereClause[] = "tracking.site = 'Study Abroad'";
        } else {
            $whereClause[] = "tracking.site != 'Study Abroad'";
        }

        if (strcasecmp($deviceType, 'desktop') == 0 || strcasecmp($deviceType, 'mobile') == 0) {
            $whereClause[] = "tracking.siteSource = '$deviceType'";
        }

        $whereClause = implode(" AND ", $whereClause);
        $dbConnection->where($whereClause);
        $dbConnection->group_by("WidgetName");
        $dbConnection->order_by("ScalarValue", "DESC");
        $dbConnection->limit($count);

        return $dbConnection->get()->result();
    }

    public function getCities($count, $type = "registration", $team, $deviceType, $dateRange)
    {
        $this->getElasticSearch();
        if( $type =='registration') {

            $elasticQuery = $this->getRegistrationQuery($team, $deviceType, $dateRange);

            $elasticQuery['body']['aggs']['cityWise'] = array(
                'terms' => array(
                    'field' => 'cityId',
                    'size'  => $count,
                    'order' => array('_count' => 'desc')
                )
            );
            $topCities = $this->clientCon->search($elasticQuery);
            $topCities = $topCities['aggregations']['cityWise']['buckets'];
            foreach($topCities as $index => $oneCity){
                $cityName = $this->getName($oneCity['key'], "city");
                $topCities[$index]['name'] = $cityName[0]->CityName;
            }

            return $topCities;
        } else {
            $elasticQuery = $this->getQuery($team, $deviceType, $dateRange);

            $elasticQuery['body']['aggs'] = array(
                'cityWise' => array(
                    'terms' => array(
                        'field' => 'geocity',
                        'size' => $count
                    )
                )
            );
            $cities = $this->clientCon->search($elasticQuery);
            $cities = $cities['aggregations']['cityWise']['buckets'];

            foreach($cities as $index => $oneCountry){
                $cities[$index]['name'] = $oneCountry['key'];
            }
            return $cities;
        }
    }

    function getDesiredCountries($count, $type = "registration", $team, $deviceType, $dateRange,$prefCountry = 1)
    {
        $this->getElasticSearch();
        $elasticQuery = $this->getRegistrationQuery($team, $deviceType, $dateRange);
        $elasticQuery['body']['aggs']['desiredCountryWise'] = array(
            'terms' => array(
                'field' => 'prefCountry'.$prefCountry,
                'size'  => 0,
                'order' => array('_count' => 'desc')
            )
        );
        $topDesiredCountries = $this->clientCon->search($elasticQuery);
        $topDesiredCountries = $topDesiredCountries['aggregations']['desiredCountryWise']['buckets'];
        return $topDesiredCountries;
    }
    public  function getCountries($count, $type = "registration", $team, $deviceType, $dateRange){
        $this->getElasticSearch();
        if( $type == 'traffic') {
            $elasticQuery = $this->getQuery($team, $deviceType, $dateRange);
            $elasticQuery['body']['aggs'] = array(
                'countryWise' => array(
                    'terms' => array(
                        'field' => 'geocountry',
                        'size' => $count
                    )
                )
            );
            $countries = $this->clientCon->search($elasticQuery);
            $countries = $countries['aggregations']['countryWise']['buckets'];
            foreach($countries as $index => $oneCountry){
                $countries[$index]['name'] = $oneCountry['key'];
            }
            return $countries;
        }else if($type == 'registration'){
            $elasticQuery = $this->getRegistrationQuery($team, $deviceType, $dateRange);
            $elasticQuery['body']['aggs'] = array(
                'countryWise' => array(
                    'terms' => array(
                        'field' => 'countryId',
                        'size' => $count,
                        'order' => array('_count' => 'desc')
                    )
                )
            );
            $countries = $this->clientCon->search($elasticQuery);
            $countries = $countries['aggregations']['countryWise']['buckets'];
            $this->load->builder('LocationBuilder','location');
            $locationBuilder    = new LocationBuilder();
            $this->locationRepository = $locationBuilder->getLocationRepository();
            foreach($countries as $index => $oneCountry){
                $countries[$index]['name'] = ucfirst($this->locationRepository->findCountry($oneCountry['key'])->getName());
            }
            return $countries;
        }
    }

    private function getRegistrationCity($team, $cities, $deviceType, $dateRange, $count){
            $trackingKeys = $this->getTrackingIdsString($team, $deviceType);
            if(count($cities)<=0){
                return;
            }
            $dbConnection = $this->getReadHandle();
            $selectFields = array(
                "rt.city",
                "count(1) as ScalarValue"
            );
            $dbConnection->select($selectFields);
            //$dbConnection->from("tusersourceInfo userSource");
            //$dbConnection->join("tuser user", "userSource.userId = user.userid", "inner");
            $dbConnection->from('registrationTracking rt');
            $cities = implode(",", $cities);
            $whereClause = array(
                "rt.trackingkeyId IN ($trackingKeys)",
                "rt.submitDate >= '$dateRange[startDate]'",
                "rt.submitDate <= '$dateRange[endDate]'",
                "rt.city IN ($cities)"
            );
            $whereClause = implode(" AND ", $whereClause);

            $dbConnection->where($whereClause);
            $dbConnection->where('rt.isNewReg','yes');
            $dbConnection->group_by("rt.city");
            $dbConnection->order_by("ScalarValue", "DESC");
            $dbConnection->limit($count);

            return $dbConnection->get()->result();
        }



    private function getRegistrationCountry($team, $countries, $deviceType, $dateRange, $count){
            $trackingKeys = $this->getTrackingIdsString($team, $deviceType);
            if(count($countries)<=0){
                return;
            }
            //_p($countries);die;
            $dbConnection = $this->getReadHandle();
            $selectFields = array(
                "rt.country",
                "count(1) as ScalarValue"
            );
            $dbConnection->select($selectFields);
            //$dbConnection->from("tusersourceInfo userSource");
            //$dbConnection->join("tuser user", "userSource.userId = user.userid", "inner");
            $dbConnection->from('registrationTracking rt');

            $countries = implode(",", $countries);
            $whereClause = array(
                "rt.trackingkeyId IN ($trackingKeys)",
                "rt.submitDate >= '$dateRange[startDate]'",
                "rt.submitDate <= '$dateRange[endDate]'",
                "rt.country IN ($countries)"
            );
            $whereClause = implode(" AND ", $whereClause);

            $dbConnection->where($whereClause);
            $dbConnection->where('rt.isNewReg','yes');
            $dbConnection->group_by("rt.country");
            $dbConnection->order_by("ScalarValue", "DESC");
            $dbConnection->limit($count);
            //echo $dbConnection->last_query();die;
            return $dbConnection->get()->result();
        }

    public function getDeltaCities($count, $cities, $aspect, $team, $deviceType, $dates){
        $this->getElasticSearch();
        if($aspect == 'registration') {
            $fieldName = 'cityId';
            $countryWiseGrouping = array(
                'terms' => array(
                    'field' => $fieldName,
                    'size'  => $count
                )
            );
            //        _p($countries); die;
            $elasticQuery  = $this->getRegistrationQuery($team, $deviceType, $dates['mom']);
            $elasticQuery['body']['aggs']['cityWise'] = $countryWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms'][$fieldName] = $cities;
            $topCitiesMOM = $this->clientCon->search($elasticQuery);
            $elasticQuery  = $this->getRegistrationQuery($team, $deviceType, $dates['yoy']);
            $elasticQuery['body']['aggs']['cityWise'] = $countryWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms'][$fieldName] = $cities;
            $topCitiesYOY = $this->clientCon->search($elasticQuery);

            return array(
                'mom' => $topCitiesMOM['aggregations']['cityWise']['buckets'],
                'yoy' => $topCitiesYOY['aggregations']['cityWise']['buckets']
            );
        }else if($aspect == 'traffic') {
            $fieldName = 'geocity';
            $countryWiseGrouping = array(
                'terms' => array(
                    'field' => $fieldName,
                    'size'  => $count
                )
            );
            //        _p($countries); die;
            $elasticQuery  = $this->getQuery($team, $deviceType, $dates['mom']);
            $elasticQuery['body']['aggs']['cityWise'] = $countryWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms'][$fieldName] = $cities;
            $topCountriesMOM = $this->clientCon->search($elasticQuery);

            $elasticQuery  = $this->getQuery($team, $deviceType, $dates['yoy']);
            $elasticQuery['body']['aggs']['cityWise'] = $countryWiseGrouping;
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms'][$fieldName] = $cities;
            $topCountriesYOY = $this->clientCon->search($elasticQuery);

            return array(
                'mom' => $topCountriesMOM['aggregations']['cityWise']['buckets'],
                'yoy' => $topCountriesYOY['aggregations']['cityWise']['buckets']
            );
        }
    }

public function getDeltaCountries($count, $countries, $aspect, $team, $deviceType, $dates){
    $this->getElasticSearch();
    if($aspect == 'registration') {
        $fieldName = 'countryId';
        $countryWiseGrouping = array(
            'terms' => array(
                'field' => $fieldName,
                'size'  => $count
            )
        );
        //        _p($countries); die;
        $elasticQuery  = $this->getRegistrationQuery($team, $deviceType, $dates['mom']);
        $elasticQuery['body']['aggs']['countryWise'] = $countryWiseGrouping;
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms'][$fieldName] = $countries;
        $topCountriesMOM = $this->clientCon->search($elasticQuery);

        $elasticQuery  = $this->getRegistrationQuery($team, $deviceType, $dates['yoy']);
        $elasticQuery['body']['aggs']['countryWise'] = $countryWiseGrouping;
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms'][$fieldName] = $countries;
        $topCountriesYOY = $this->clientCon->search($elasticQuery);

        return array(
            'mom' => $topCountriesMOM['aggregations']['countryWise']['buckets'],
            'yoy' => $topCountriesYOY['aggregations']['countryWise']['buckets']
        );
    }else if($aspect == 'traffic') {
        $fieldName = 'geocountry';
        $countryWiseGrouping = array(
            'terms' => array(
                'field' => $fieldName,
                'size'  => $count
            )
        );
        //        _p($countries); die;
        $elasticQuery  = $this->getQuery($team, $deviceType, $dates['mom']);
        $elasticQuery['body']['aggs']['countryWise'] = $countryWiseGrouping;
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms'][$fieldName] = $countries;
        $topCountriesMOM = $this->clientCon->search($elasticQuery);

        $elasticQuery  = $this->getQuery($team, $deviceType, $dates['yoy']);
        $elasticQuery['body']['aggs']['countryWise'] = $countryWiseGrouping;
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms'][$fieldName] = $countries;
        $topCountriesYOY = $this->clientCon->search($elasticQuery);

        return array(
            'mom' => $topCountriesMOM['aggregations']['countryWise']['buckets'],
            'yoy' => $topCountriesYOY['aggregations']['countryWise']['buckets']
        );
    }
}


    public function getDeltaDesiredCountries($count, $countries, $aspect, $team, $deviceType, $dates,$prefCountry = 1){
        if(count($countries)<=0){
            return;
        }
        $this->getElasticSearch();
        //$elasticQuery = $this->getRegistrationQuery($team, $deviceType, $dateRange);
        $countryWiseGrouping = array(
            'terms' => array(
                'field' => 'prefCountry'.$prefCountry,
                'size'  => $count,
            )
        );

        $elasticQuery  = $this->getRegistrationQuery($team, $deviceType, $dates['mom']);
        $elasticQuery['body']['aggs']['desiredCountryWise'] = $countryWiseGrouping;
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['prefCountry'.$prefCountry] = $countries;
        $topCitiesMOM = $this->clientCon->search($elasticQuery);

        $elasticQuery  = $this->getRegistrationQuery($team, $deviceType, $dates['yoy']);
        $elasticQuery['body']['aggs']['desiredCountryWise'] = $countryWiseGrouping;
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['terms']['prefCountry'.$prefCountry] = $countries;
        $topCitiesYOY = $this->clientCon->search($elasticQuery);

        return array(
            'mom' => $topCitiesMOM['aggregations']['desiredCountryWise']['buckets'],
            'yoy' => $topCitiesYOY['aggregations']['desiredCountryWise']['buckets']
        );
    }

    private function getQuery($team, $deviceType, $dateRange,$catSubCatFilter='', $view=1)
    {
        $elasticQuery = array();
        $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $elasticQuery['type'] = 'session';

        $elasticQuery['body']['size'] = 0;

        $startDate = $dateRange['startDate'].'T00:00:00';
        $endDate = $dateRange['endDate'].'T23:59:59';

        if($catSubCatFilter){
            $gtZero = array(
                'gt' => 1
            );
            if($catSubCatFilter == 'category'){
                $categoryFilter = array(
                    'range' => array(
                        'landingPageDoc.categoryId' => $gtZero
                    )
                );
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $categoryFilter;
            }else if($catSubCatFilter == 'subCategory'){
                $subcategoryFilter = array(
                    'range' => array(
                        'landingPageDoc.subCategoryId' => $gtZero
                    )
                );
                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $subcategoryFilter;
            }
        }

        if($team != 'global'){
            $isStudyAbroad = strcasecmp($team, "abroad") == 0 ? "yes" : "no";
            $teamFilter = array(
                "term" => array(
                    "isStudyAbroad" => $isStudyAbroad
                )
            );

            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $teamFilter;
        }

        if($deviceType !='all' && $deviceType !='' && $deviceType != 'undefined'){
            $isMobile = strcasecmp($deviceType, "mobile") == 0 ? "yes" : "no";
            $deviceFilter = array(
                "term" => array(
                    "isMobile" => $isMobile
                )
            );
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $deviceFilter;
        }

        $startDateFilter = array(
            "range" => array(
                "startTime" => array(
                    "gte" => $startDate
                )
            )
        );

        $endDateFilter = array(
            "range" => array(
                "startTime" => array(
                    "lte" => $endDate
                )
            )
        );

        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;

        return $elasticQuery;
    }

    private function getTeamSelector($team, &$whereClause)
    {
        if(strcasecmp($team, 'abroad') == 0){
            $whereClause[] = "tracking.site = 'Study Abroad'";
        } else if(strcasecmp($team, 'domestic') == 0){
            $whereClause[] = "tracking.site != 'Study Abroad'";
        }else if(strcasecmp($team, 'national') == 0){
            $whereClause[] = "tracking.site != 'Study Abroad'";
        }
    }

    private function getDeviceSelector($deviceType, &$whereClause){
        if($deviceType != ''){
            if (strcasecmp($deviceType, 'desktop') == 0 || strcasecmp($deviceType, 'mobile') == 0) {
                $whereClause[] = "tracking.siteSource = '$deviceType'";
            }
        }
    }

    public function getTrafficSplit($team='', $deviceType, $dateRange){

        $this->getElasticSearch();
        $elasticQuery = $this->getQuery($team, $deviceType, $dateRange);
        $splitTypes = array(
            "sourceWise" => "source",
            "deviceWise" => "isMobile"
        );
        $trafficSplits = array();
        foreach($splitTypes as $oneSplitName => $oneGroupingIndex){

            $elasticQuery['body']['aggs'] = array(
                $oneSplitName => array(
                    "terms" => array(
                        "field" => $oneGroupingIndex
                    )
                )
            );

            $oneSplit = $this->clientCon->search($elasticQuery);
            $trafficSplits[$oneSplitName] = $oneSplit['aggregations'][$oneSplitName]['buckets'];
        }

        return $trafficSplits;
    }

    //-------------------------------------For Responses------------------------------------------------------
    function getTopPagesForResponses($source,$sourceApplication,$dateRange,$topPagesArray='')
    {
        $this->initiateModel();
        $this->dbHandle->select('tp.page as pageName, count(1) as count');
        $this->dbHandle->from('tempLMSTable tlt');
        $this->dbHandle->join('tracking_pagekey tp','tlt.tracking_keyid = tp.id and tlt.tracking_keyid is not null','inner');
        $this->dbHandle->where('tlt.submit_date >=',$dateRange['startDate'].' 00:00:00');
        $this->dbHandle->where('tlt.submit_date <=',$dateRange['endDate'].' 23:59:59');
        if($source){
            if($source == 'abroad'){
                $this->dbHandle->where('tp.site','Study Abroad');
            }else if($source =='national'){
                /*$siteArray = array('National','National Listing','UGC');
                $this->dbHandle->where_in('tp.site',$siteArray);*/
                $this->dbHandle->where('tp.site !=','Study Abroad');
            }
        }
        if($sourceApplication){
            $this->dbHandle->where('tp.siteSource',$sourceApplication);
        }
        if($topPagesArray){
            $this->dbHandle->where_in('tp.page',$topPagesArray);
        }
        $this->dbHandle->group_by('pageName');
        $this->dbHandle->order_by('count','desc');
        $this->dbHandle->limit(10,0);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getTrackingIdsForResponses($source='',$sourceApplication='')
    {
        $this->initiateModel();
        if($source){
            $this->dbHandle->select('id');
        }else{
            $this->dbHandle->select('id,site');
        }

        $this->dbHandle->FROM('tracking_pagekey');
        if($source){
            if($source == 'abroad'){
                $this->dbHandle->where('site','Study Abroad');
            }else if($source =='national'){
                /*$siteArray = array('National','National Listing','UGC');
                $this->dbHandle->where_in('site',$siteArray);*/
                $this->dbHandle->where('site !=','Study Abroad');
            }
        }/*else{
            $siteArray = array('Study Abroad','National','National Listing','UGC');
            $this->dbHandle->where_in('site',$siteArray);
        }*/
        if($sourceApplication){
            $this->dbHandle->where('siteSource',$sourceApplication);
        }
        $conversionType = array('response','Course shortlist','downloadBrochure','send Contat Detail','send Contact Detail');
        $this->dbHandle->where_in('conversionType',$conversionType);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }


    function getTopResponsesForCategoryForNational($source,$dateRange,$topCategories,$trackingIds){
        $this->initiateModel();
        $this->dbHandle->select('cbt.parentId, count(tlt.id) as id');
        $this->dbHandle->from('tempLMSTable tlt');
        $this->dbHandle->join('categoryPageData  cpd','tlt.listing_type_id = cpd.course_id','inner');
        $this->dbHandle->join('categoryBoardTable cbt','cbt.boardId = cpd.category_id','inner');

        $this->dbHandle->where_in('cbt.parentId',$topCategories);
        $this->dbHandle->where('cpd.status != ','deleted'); // table
        $this->dbHandle->where_in('tlt.tracking_keyid',$trackingIds);
        $this->dbHandle->where('tlt.listing_type','course');
        $this->dbHandle->where('date(tlt.submit_date) >=',$dateRange['startDate']);
        $this->dbHandle->where('date(tlt.submit_date) <=',$dateRange['endDate']);

        $this->dbHandle->group_by('cbt.parentId');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();
        return $result;
    }

    function getSubCategoriesIdsForCategoryIds($topCategories){
        $this->initiateModel();
        $this->dbHandle->select('parentID,boardId');
        $this->dbHandle->from('categoryBoardTable');
        $this->dbHandle->where_in('parentId',$topCategories);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getTopResponsesForSubCategories($dateRange,$topSubcategories,$trackingIds){
        $this->initiateModel();
        $this->dbHandle->select('lct.category_id as subCategoryId , count(distinct tlt.id) as count');
        $this->dbHandle->from('tempLMSTable tlt');
        $this->dbHandle->join('listing_category_table lct','lct.listing_type_id = tlt.listing_type_id','inner'); // table
        $this->dbHandle->where('lct.status','live'); // table
        $this->dbHandle->where_in('lct.category_id',$topSubcategories);
        $this->dbHandle->where_in('tlt.tracking_keyid',$trackingIds);
        $this->dbHandle->where('tlt.listing_type','course');
        $this->dbHandle->where('date(tlt.submit_date) >=',$dateRange['startDate']);
        $this->dbHandle->where('date(tlt.submit_date) <=',$dateRange['endDate']);
        $this->dbHandle->group_by('subCategoryId');  // table

        $result = $this->dbHandle->get()->result_array();
        //echo 'Memory Used : '.memory_get_peak_usage().'  Byte</br>';
        //echo $this->dbHandle->last_query();die;
        return $result;
    }


    function getTopResponsesForResponseStats($source,$dateRange,$viewFilter,$viewFilterArray,$trackingIds){
        $this->initiateModel();
        if($source == 'abroad'){
            switch ($viewFilter) {
                case 'category':
                    $selectFields = 'acpd.category_id , count(distinct tlt.id) as count';
                    $whereClause = 'acpd.category_id';
                    break;

                case 'city':
                    $selectFields = 'acpd.city_id , count(distinct tlt.id) as count';
                    $whereClause = 'acpd.city_id';
                    break;

                case 'listing':
                    $selectFields = 'acpd.university_id as listing_id , count(distinct tlt.id) as count';
                    $whereClause = 'acpd.university_id';
                    break;

                case 'country':
                    $selectFields = 'acpd.country_id as country_id , count(distinct tlt.id) as count';
                    $whereClause = 'acpd.country_id';
                    break;
            }
            $this->dbHandle->select($selectFields);
        }else if($source == 'national'){
            switch ($viewFilter) {
                case 'city':
                    $selectFields = 'cpd.city_id , count(distinct tlt.id) as count';
                    $whereClause = 'cpd.city_id';
                    break;

                case 'listing':
                    $selectFields = 'cpd.institute_id as listing_id  , count(distinct tlt.id) as count';
                    $whereClause = 'cpd.institute_id';
                    break;
            }
            $this->dbHandle->select($selectFields);
            //$this->dbHandle->select('cpd.category_id as subCategoryId,cpd.city_id,cpd.institute_id, count(distinct tlt.id) as count');
        }

        $this->dbHandle->from('tempLMSTable tlt');
        $this->dbHandle->where_in('tlt.tracking_keyid',$trackingIds);
        if($source == 'abroad'){
            $this->dbHandle->join('abroadCategoryPageData acpd','acpd.course_id = tlt.listing_type_id','inner'); // table
            //$this->dbHandle->where('acpd.status','live'); // table  
            //$viewFilterArray = array(239,240);
            $this->dbHandle->where_in($whereClause,$viewFilterArray); // view Filter
        }else if($source == 'national'){
            $this->dbHandle->join('categoryPageData cpd','cpd.course_id = tlt.listing_type_id','inner'); // table
            //$this->dbHandle->where('cpd.status','live'); // table
            $this->dbHandle->where_in($whereClause,$viewFilterArray); // view Filter
        }

        $this->dbHandle->where('tlt.listing_type','course');
        $this->dbHandle->where('date(tlt.submit_date) >=',$dateRange['startDate']);
        $this->dbHandle->where('date(tlt.submit_date) <=',$dateRange['endDate']);

        $this->dbHandle->group_by($whereClause);

        $result = $this->dbHandle->get()->result_array();
        //echo 'Memory Used : '.memory_get_peak_usage().'  Byte</br>';
        //echo $this->dbHandle->last_query();
        return $result;
    }

    function getResponseStatsData($source,$trackingIdsArray,$dateRange)
    {
        $this->initiateModel();
        if($source == 'abroad'){
            $this->dbHandle->select('acpd.category_id,acpd.sub_category_id,acpd.city_id,acpd.university_id,acpd.country_id,`tlt`.`listing_type_id`, count(distinct tlt.id) as count'); //table
            $this->dbHandle->join('abroadCategoryPageData acpd','acpd.course_id = tlt.listing_type_id','inner'); // table
            //$this->dbHandle->where('acpd.status = ','live'); // table  
        }else if($source == 'national'){
            $this->dbHandle->select('cpd.category_id as subCategoryId,cpd.city_id,cpd.institute_id,`tlt`.`listing_type_id`, count(distinct tlt.id) as count');
            $this->dbHandle->join('categoryPageData cpd','cpd.course_id = tlt.listing_type_id','inner'); // table
            //$this->dbHandle->where('cpd.status = ','live'); // table
        }

        $this->dbHandle->from('tempLMSTable tlt');
        $this->dbHandle->where('tlt.listing_type','course');
        $this->dbHandle->where_in('tlt.tracking_keyid',$trackingIdsArray);
        $this->dbHandle->where('tlt.submit_date >=',$dateRange['startDate'].' 00:00:00');
        $this->dbHandle->where('tlt.submit_date <=',$dateRange['endDate'].' 23:59:59');

        $this->dbHandle->group_by('tlt.listing_type_id');  // table
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getResponsesData($source,$sourceApplication,$dateRange){
        $this->initiateModel();
        $this->dbHandle->select('sum(paidRMC) as paidRMC,sum(freeRMC) as freeRMC,sum(paidResponses) as paidResponses,sum(freeResponses) as freeResponses,sourceApplication,trafficSource');
        $this->dbHandle->from('MISOverviewData');
        $this->dbHandle->where('date >=',$dateRange['startDate']);
        $this->dbHandle->where('date <=',$dateRange['endDate']);
        if($source){
            $this->dbHandle->where('source',$source);
        }
        if($sourceApplication){
            $this->dbHandle->where('sourceApplication',$sourceApplication);
        }else{
            $this->dbHandle->group_by('sourceApplication');
        }
        $this->dbHandle->group_by('trafficSource');

        $result= $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }
    function getRegistrationsData($source,$sourceApplication,$dateRange,$crieteria='deviceWise')
    {
        $this->getElasticSearch();
        $elasticQuery = $this->getRegistrationQuery($source, $sourceApplication, $dateRange);

        if($crieteria == 'deviceWise')        {
            $fieldName = 'sourceApplication';
        }else if($crieteria == 'sourceWise'){
            $fieldName = 'trafficSource';
        }else if($crieteria == 'paidFree'){
            $fieldName = 'source';
        }
        $crieteriaWiseGrouping = array(
            'terms' => array(
                'field' => $fieldName,
                'size'  => 0,
                'order' => array('_count' => 'desc')
            )
        );
        $elasticQuery['body']['aggs']['crieteriaWise'] = $crieteriaWiseGrouping;
        $result = $this->clientCon->search($elasticQuery);
        $result = $result['aggregations']['crieteriaWise']['buckets'];
        return $result;
    }

    function getPaidCourses($source='',$dateRange,$productIdsArray){
        $this->initiateModel();
        $this->dbHandle->select('DISTINCT(courseId) as courseId');
        $this->dbHandle->from('courseSubscriptionHistoricalDetails cshd');

        $this->dbHandle->where('cshd.addedOnDate <=',$dateRange['endDate']);
        $this->dbHandle->where('(cshd.endedOnDate >=\''.$dateRange['startDate'].'\' OR cshd.endedOnDate = "0000:00:00" ) ','',false);
        if($source){
            $this->dbHandle->where('cshd.source',$source);
        }
        if(is_array($productIdsArray)){
            $this->dbHandle->where_in('cshd.packType',$productIdsArray);
        }else{
            $this->dbHandle->where('cshd.packType',$productIdsArray);
        }

        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    function getTrafficTilesData($source = '', $deviceType, $dateRange){
        $this->getElasticSearch();

        $elasticQuery                             = array(
            'index' => MISCommonLib::$TRAFFICDATA_SESSIONS,
            'type' => 'session',
            'body' => array(
                'size' => 0,
                'query' => array(
                    'filtered' => array(
                        'filter' => array(
                            'bool' => array(
                                'must' => array(
                                    array(
                                        'range' => array(
                                            'startTime' => array(
                                                'gte' => $dateRange['startDate'].'T00:00:00'
                                            )
                                        )
                                    ),
                                    array(
                                        'range' => array(
                                            'startTime' => array(
                                                'lte' => $dateRange['endDate'].'T23:59:59'
                                            )
                                        )
                                    ),
                                )
                            )
                        )
                    )
                ),
                'aggs' => array(
                    'userCount' => array(
                        'cardinality' => array(
                            'field' => 'visitorId'
                        )
                    ),
                    'sessionDuration' => array(
                        'sum' => array(
                            'field' => 'duration'
                        )
                    )
                )
            ),
        );


        if($deviceType != 'all'){
            $deviceTypeForSessions = $deviceType == 'desktop' ? 'no' : 'yes';

            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                'term' => array(
                    'isMobile' => $deviceTypeForSessions
                )
            );
        }

        if($source != 'global'){
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                'term' => array(
                    'isStudyAbroad' => $source == 'national' ? 'no' : 'yes'
                )
            );
        }

        $tileInformation = $this->clientCon->search($elasticQuery);
        //$dateRange, $pageName='', $extraData = array())//$source, $deviceType,
        //$deviceTypeForPageviews = $deviceType == 'yes' ? 'mobile' : 'desktop';
        $totalPageViewsQuery = $this->getPageviewTopTileQuery($dateRange,'', array('team'=>$source,'deviceType'=>$deviceType));
        $totalPageViews = $this->clientCon->search($totalPageViewsQuery);

        return array(
            'users' => number_format($tileInformation['aggregations']['userCount']['value']),
            //'sessions' => $tileInformation['aggregations']['sessionCount']['value'],
            'sessions' => number_format($tileInformation['hits']['total']),
            'pageviews' => number_format($totalPageViews['hits']['total']),
            'avgsessdur' => number_format(doubleval($tileInformation['aggregations']['sessionDuration']['value']) / doubleval($tileInformation['hits']['total']) / 60, 2, '.', ''), // data in minutes
        );
    }


    function getTrackingIdsForRegistration($sourceApplication='',$site = ''){
        $this->initiateModel();
        $this->dbHandle->select('*');
        $this->dbHandle->from('tracking_pagekey tracking');
        if($sourceApplication){
            $this->dbHandle->where('siteSource',$sourceApplication);
        }
        $whereClause = array();
        if( ! empty($site))
        {
            $this->getTeamSelector($site,$whereClause);
            $whereClause = implode(" AND ", $whereClause);
            $this->dbHandle->where($whereClause);
        }
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;

    }

    function getRegistrationData($dateRange,$trackingIdsArray,$view=1){
        if($view == 1)
        {
            $res = '';
        }else if($view == 2)
        {
            $res = ', week(submitDate,1) as week';
        }else if($view == 3)
        {
            $res = ', month(submitDate) as month';
        }
        $this->initiateModel();

        $this->dbHandle->select('trackingkeyId as tracking_keyid,source,submitDate as responseDate ,count(1) as reponsesCount'.$res, false);
        //$this->dbHandle->from('tusersourceInfo');
        $this->dbHandle->from('registrationTracking rt');

        $this->dbHandle->where('submitDate >=', $dateRange['startDate']);
        $this->dbHandle->where('submitDate <=', $dateRange['endDate']);
        $this->dbHandle->where('trackingkeyId IN ('.implode(',',$trackingIdsArray).')');
        $this->dbHandle->where('rt.isNewReg','yes');
        if($view == 1)
        {
            $this->dbHandle->group_by('responseDate');
        }else if ($view == 2) {
            $this->dbHandle->group_by('week');
        }else if ($view == 3) {
            $this->dbHandle->group_by('month,responseDate');
        }

        $this->dbHandle->group_by('tracking_keyid');
        $this->dbHandle->group_by('source');

        $this->dbHandle->order_by("responseDate", "asc");
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //_p($result);die;
        //error_log('qry chart = '.$this->dbHandle->last_query());
        return $result;
    }

    function getSessionsIds($dateRange,$trackingIdsArray){
        $this->initiateModel();
        //$trackingIdsArray = array(0,'NULL');
        $this->dbHandle->select('DISTINCT(visitorsessionid)');
        $this->dbHandle->from('tusersourceInfo');
        $this->dbHandle->where('date(time) >=', $dateRange['startDate']);
        $this->dbHandle->where('date(time) <=', $dateRange['endDate']);

        $this->dbHandle->where('tracking_keyId is not NULL');
        $this->dbHandle->where('tracking_keyId !=', 0);
        $this->dbHandle->where('visitorsessionid is not NULL');

        $result = $this->dbHandle->get()->result_array();
        //echo  $this->dbHandle->last_query();die;
        return $result;
    }

    function getPaidRegistration($dateRange,$sessionsIds){
        /*
            $this->initiateModel();
            //$trackingIdsArray = array(0,'NULL');
            $this->dbHandle->select('utm_campaign as utm_campaign, count(1) as count');
            $this->dbHandle->from('session_tracking');
        
            $this->dbHandle->where('source', 'mailer');
            $startDate =  date('Y-m-d',strtotime($dateRange['startDate']."-1 days"));
            $this->dbHandle->where('date(startTime) >=', $startDate);
            $this->dbHandle->where('date(startTime) <=', $dateRange['endDate']);
            $this->dbHandle->where_in('sessionId',$sessionsIds);
            $this->dbHandle->where('utm_campaign is not NULL','','false');

            $this->dbHandle->group_by('utm_campaign');

            $result = $this->dbHandle->get()->result_array();
            echo  $this->dbHandle->last_query();die;
            return $result;
        */
        $this->initiateModel();
        //$trackingIdsArray = array(0,'NULL');
        $this->dbHandle->select('st.utm_campaign as utm_campaign, count(1) as count');
        $this->dbHandle->from('tusersourceInfo tusi');
        $this->dbHandle->join('session_tracking st','tusi.visitorsessionId = st.sessionId','inner');
        $this->dbHandle->where('date(tusi.time) >=', $dateRange['startDate']);
        $this->dbHandle->where('date(tusi.time) <=', $dateRange['endDate']);
        $this->dbHandle->where('st.source', 'paid');
        $startDate =  date('Y-m-d',strtotime($dateRange['startDate']."-1 days"));
        $this->dbHandle->where('date(st.startTime) >=', $startDate);
        $this->dbHandle->where('date(st.startTime) <=', $dateRange['endDate']);
        $this->dbHandle->where('st.utm_campaign is not NULL');

        $this->dbHandle->where('tusi.tracking_keyId is not NULL');
        $this->dbHandle->where('tusi.tracking_keyId !=', 0);

        $this->dbHandle->group_by('utm_campaign');

        $result = $this->dbHandle->get()->result_array();
        //echo  $this->dbHandle->last_query();die;
        return $result;
    }

    function getSourceApplicationDataForFlatTable($dateRange,$sourceApplication){
        $this->initiateModel();
        $this->dbHandle->select("sum(paidResponses) as paidResponses,sum(freeResponses) as freeResponses,sourceApplication ");
        $this->dbHandle->from('MISOverviewData');
        $this->dbHandle->where('date >= ',$dateRange['startDate']);
        $this->dbHandle->where('date <= ',$dateRange['endDate']);
        if($sourceApplication != ''){
            $this->dbHandle->where('sourceApplication',ucfirst($sourceApplication));
        }
        $this->dbHandle->group_by('sourceApplication');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }


    // Code for the response line chart
    function getResponseTrend($dateRange,$team, $sourceApplication,$view=1){
        $this->initiateModel();

        /*
            SELECT
                DATE(leadData.submit_date) AS ResponseDate,
                COUNT(1) AS ResponseCount
                FROM
                (`tracking_pagekey` tracking)
                        INNER JOIN
                    `tempLMSTable` leadData ON `tracking`.`id` = `leadData`.`tracking_keyid`
                            --         AND tracking.site != 'Study Abroad'
                        INNER JOIN
                    `listing_category_table` categoryInformation ON `leadData`.`listing_type_id` = `categoryInformation`.`listing_type_id`
                        AND categoryInformation.listing_type = 'course'
                        AND categoryInformation.status = 'live'
                WHERE
                DATE(leadData.submit_date) >= '2015-12-23'
                AND DATE(leadData.submit_date) <= '2016-01-22'
            GROUP BY DATE(leadData.submit_date)
            ORDER BY `leadData`.`submit_date` DESC
        */
        if($view == 1)
        {
            $res = '';
        }else if($view == 2)
        {
            $res = ', week(date,1) as week';
        }else if($view == 3)
        {
            $res = ', month(date) as month';
        }

        $selectFields = array(
            'date AS ResponseDate',
            'sum(paidResponses) as paidResponses, sum(freeResponses) as freeResponses'.$res,
        );
        $this->dbHandle->select($selectFields);
        $this->dbHandle->from('MISOverviewData');

        $this->dbHandle->where('date >= ',$dateRange['startDate']);
        $this->dbHandle->where('date <= ',$dateRange['endDate']);

        if($sourceApplication != '' && $sourceApplication !='all'){
            $this->dbHandle->where('sourceApplication',ucfirst($sourceApplication));
        }

        if($view == 1){
            $this->dbHandle->group_by('ResponseDate');
        }else if ($view == 2) {
            $this->dbHandle->group_by("week");
        }else if ($view == 3) {
            $this->dbHandle->group_by('month,ResponseDate');
        }

        $this->dbHandle->order_by("ResponseDate", "asc");
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }


    // Code for the response donut charts
    // Unused
    function getResponseSplit($dateRange, $team, $sourceApplication, $view, $splitName){

        $this->initiateModel('read');
        $fullSplitName = '';
        switch($splitName){
            case 'page':
                $fullSplitName = 'tracking.page';
                break;

            case 'widget':
                $fullSplitName = 'CONCAT( tracking.page, " > ", CONCAT(UCASE(MID(tracking.keyname, 1, 1 )), MID(tracking.keyname, 2 )), " > ",  CONCAT(UCASE(MID(tracking.widget, 1, 1 )), MID(tracking.widget, 2 ) ) )';
                break;

            case 'sourceApp':
                $fullSplitName = 'tracking.siteSource';
                break;

            case 'responseType':
                $fullSplitName = 'responses.listing_subscription_type';
                break;

            case 'action':
                $fullSplitName = 'responses.action';
                break;

            case 'trafficSource':
                $fullSplitName = 'responses.visitorSessionId';

        }
        $selectFields = array(
            'COUNT(1) as ScalarValue',
            "$fullSplitName as PivotName"
        );

        $this->dbHandle->select($selectFields);
        $this->dbHandle->from('tempLMSTable responses');

        $joinConditions = array(
            "responses.tracking_keyid = tracking.id",
            "date(submit_date) >= '$dateRange[startDate]'",
            "date(submit_date) <= '$dateRange[endDate]'",
        );
        if($sourceApplication != ''){
            $joinConditions[] = "tracking.siteSource = '$sourceApplication'";
        }

        $this->getTeamSelector($team, $joinConditions);

        $this->dbHandle->join('tracking_pagekey tracking', implode(" AND ", $joinConditions), 'inner');

        $this->dbHandle->group_by("PivotName");

        $this->dbHandle->order_by("ScalarValue", "DESC");
        $result = $this->dbHandle->get()->result();
        return $result;
    }

    //Code to get traffic source for responses
    // Unused
    public function getResponseDataBasedOnSession($sessionResult)
    {
        $sessionId = array();
        $i = 0;
        $sessionResultArray = array();
        foreach ($sessionResult as $object) {
            $sessionId[$i++] = $object->PivotName;
            $sessionResultArray[$object->PivotName] = $object->ScalarValue;
        }

        $sourceWise = $this->getSourceWiseForDonutChart($sessionId,$sessionResultArray);
        $result['sourceWise'] = $sourceWise['sourceWise'];
        $result['sourceSession'] = $sourceWise['sourceSession'];
        return $result;
    }

    // Unused
    private function getSourceWiseForDonutChart($sessionId,$sessionResult)
    {
        $sourceResult = array();
        $resultArray = array();
        if( ! empty($sessionId))
        {
            $this->dbHandle = $this->getReadHandle();
            $this->dbHandle->select('source,sessionId');
            $this->dbHandle->from('session_tracking');
            $this->dbHandle->where_in('sessionId',$sessionId);
            $sourceResult = $this->dbHandle->get()->result_array();
        }
        $sourceSessionMapping = array();
        $sourceSessionArray = array();
        foreach ($sourceResult as $key => $value) {
            $sourceSessionMapping[$value['sessionId']] = $value['source'];
            if( ! empty($value['source']))
            {
                if( array_key_exists($value['source'], $sourceSessionArray))
                {
                    array_push($sourceSessionArray[$value['source']], $value['sessionId']);
                }
                else
                {
                    $sourceSessionArray[$value['source']] = array($value['sessionId']);
                }
            }

        }
        $sourceWiseResult = array();
        $i = 0;
        foreach ($sessionResult as $key => $value) {
            if( empty($sourceSessionMapping[$key]))
                $sourceSessionMapping[$key] = 'Other';
            $sourceWiseResult[ $sourceSessionMapping[ $key ] ] += $value;
        }
        foreach ($sourceWiseResult as $key => $value) {
            $sourceWiseSingleSplit = new stdClass();
            $sourceWiseSingleSplit->PivotName = $key;
            $sourceWiseSingleSplit->ScalarValue = $value;
            $resultArray[] = $sourceWiseSingleSplit;
        }
        $returnArray['sourceWise'] = $resultArray;
        $returnArray['sourceSession'] = $sourceSessionArray;
        return $returnArray;
    }

    //Till Here

    function getEngagementTrend($dateRange,$team, $sourceApplication,$view=1, $aspect){
        switch($aspect){
            case 'pageview':
                return $this->getPageviewTrend($dateRange, $team, $sourceApplication, $view);
                break;

            case 'pgpersess':
                return $this->getPgPerSessTrend($dateRange, $team, $sourceApplication, $view);
                break;

            case 'bounce':
                return $this->getBounceTrend($dateRange, $team, $sourceApplication, $view);
                break;

            case 'avgsessdur':
                return $this->getAvgSessDurTrend($dateRange, $team, $sourceApplication, $view);
                break;
        }


    }

    private function getPageviewTrend($dateRange,$team, $sourceApplication,$view=1)
    {
        $this->getElasticSearch();
        $elasticQuery = $this->getPageviewQuery($dateRange,$team, $sourceApplication,$view);
        $pageViewData = $this->clientCon->search($elasticQuery);

        $dateWiseData = $pageViewData['aggregations']['dateWise']['buckets'];
        $chartData = array();

        $firstDate = $this->MISCommonLib->extractDate(strval($dateWiseData[0]['key_as_string']));
        if( strtotime($dateRange['startDate']) > strtotime($firstDate)
        ){
            $dateWiseData[0]['key_as_string'] = $dateRange['startDate'].'T00:00:00.000Z';
        }

        foreach($dateWiseData as $oneDateData){
            $chartOnOneDate = new stdClass();
            $chartOnOneDate->ScalarValue = $oneDateData['doc_count'];
            $chartOnOneDate->Date = $this->MISCommonLib->extractDate(strval($oneDateData['key_as_string']));
            $chartData[] = $chartOnOneDate;
        }
        return $chartData;
    }

    public function getEngagementSplit($dateRange, $team, $sourceApplication, $view = 1, $aspect, $splitName, $trafficSource = '')
    {
        $this->getElasticSearch();
        $splits = array();

        switch ($aspect) {
            case 'pageview':
                $elasticQuery                 = $this->getPageviewQuery($dateRange, $team, $sourceApplication, $view = 1);

                if ($trafficSource != '') {
                    $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = array(
                        'term' => array(
                            'source' => $trafficSource
                        )
                    );
                }

                switch ($splitName) {
                    case 'page':
                        $elasticQuery['body']['aggs'] = array(
                            'pageWise' => array(
                                'terms' => array(
                                    'field' => 'pageIdentifier',
                                    'size'  => 0
                                )
                            )
                        );
                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['pageWise']['buckets'] as $oneResult) {

                            $splitData              = new stdClass();
                            $splitData->PivotName   = ucfirst(htmlentities($oneResult['key']));
                            $splitData->ScalarValue = number_format($oneResult['doc_count']);
                            $splits[]               = $splitData;
                        }

                        return $splits;


                    case 'source Application':
                        $elasticQuery['body']['aggs'] = array(
                            'deviceWise' => array(
                                'terms' => array(
                                    'field' => 'isMobile',
                                    'size'  => 0,
                                    'order' => array(
                                        '_count' => 'desc'
                                    )
                                )
                            )
                        );
                        $result                       = $this->clientCon->search($elasticQuery);


                        foreach ($result['aggregations']['deviceWise']['buckets'] as $oneResult) {
                            $splitData              = new stdClass();
                            $splitData->PivotName   = ucfirst(htmlentities($oneResult['key'] == 'yes' ? 'Mobile' : 'Desktop'));
                            $splitData->ScalarValue = number_format($oneResult['doc_count']);
                            $splits[]               = $splitData;
                        }

                        return $splits;

                    case 'utmCampaign':
                        $elasticQuery['body']['aggs'] = array(
                            'utmWise' => array(
                                'terms' => array(
                                    'field' => 'utm_campaign',
                                    'size'  => 0
                                )
                            )
                        );

                        $totalCount = 0;
                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['utmWise']['buckets'] as $oneResult) {
                            $splitData              = new stdClass();
                            $splitData->RawValue = $oneResult['doc_count'];
                            $splitData->PivotName   = ucfirst(htmlentities($oneResult['key']));
                            $splitData->ScalarValue = number_format($oneResult['doc_count']);
                            $totalCount += $oneResult['doc_count'];
                            $splits[]               = $splitData;
                        }

                        if($totalCount < $result['hits']['total']){
                            $splitData              = new stdClass();
                            $splitData->RawValue = $result['hits']['total'] - $totalCount;
                            $splitData->PivotName   = "Other";
                            $splitData->ScalarValue = number_format($result['hits']['total'] - $totalCount);
                            $totalCount += $splitData->RawValue;
                            $splits[]               = $splitData;
                        }

                        arsort($splits);

                        foreach($splits as $index => $oneSplit){
                            $splits[$index]->Percentage = number_format($oneSplit->RawValue * 100 / $totalCount , 2);
                            unset($splits[$index]->RawValue);
                        }

                        return array_values($splits);

                    case 'utmSource':
                        $elasticQuery['body']['aggs'] = array(
                            'utmWise' => array(
                                'terms' => array(
                                    'field' => 'utm_source',
                                    'size'  => 0
                                )
                            )
                        );

                        $totalCount = 0;

                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['utmWise']['buckets'] as $oneResult) {
                            $splitData              = new stdClass();
                            $splitData->RawValue = $oneResult['doc_count'];
                            $splitData->PivotName   = ucfirst(htmlentities($oneResult['key']));
                            $splitData->ScalarValue = number_format($oneResult['doc_count']);
                            $totalCount += $oneResult['doc_count'];
                            $splits[]               = $splitData;
                        }

                        if($totalCount < $result['hits']['total']){
                            $splitData              = new stdClass();
                            $splitData->RawValue = $result['hits']['total'] - $totalCount;
                            $splitData->PivotName   = "Other";
                            $splitData->ScalarValue = number_format($result['hits']['total'] - $totalCount);
                            $totalCount += $splitData->RawValue;
                            $splits[]               = $splitData;
                        }

                        arsort($splits);

                        foreach($splits as $index => $oneSplit){
                            $splits[$index]->Percentage = number_format($oneSplit->RawValue * 100 / $totalCount , 2);
                            unset($splits[$index]->RawValue);
                        }

                        return array_values($splits);

                    case 'utmMedium':
                        $elasticQuery['body']['aggs'] = array(
                            'utmWise' => array(
                                'terms' => array(
                                    'field' => 'utm_medium',
                                    'size'  => 0
                                )
                            )
                        );
                        $totalCount = 0;

                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['utmWise']['buckets'] as $oneResult) {
                            $splitData              = new stdClass();
                            $splitData->RawValue = $oneResult['doc_count'];
                            $splitData->PivotName   = ucfirst(htmlentities($oneResult['key']));
                            $splitData->ScalarValue = number_format($oneResult['doc_count']);
                            $totalCount += $oneResult['doc_count'];
                            $splits[]               = $splitData;
                        }

                        if($totalCount < $result['hits']['total']){
                            $splitData              = new stdClass();
                            $splitData->RawValue = $result['hits']['total'] - $totalCount;
                            $splitData->PivotName   = "Other";
                            $splitData->ScalarValue = number_format($result['hits']['total'] - $totalCount);
                            $totalCount += $splitData->RawValue;
                            $splits[]               = $splitData;
                        }

                        arsort($splits);

                        foreach($splits as $index => $oneSplit){
                            $splits[$index]->Percentage = number_format($oneSplit->RawValue * 100 / $totalCount , 2);
                            unset($splits[$index]->RawValue);
                        }
                        return array_values($splits);

                    case 'traffic Source':
                        $elasticQuery['body']['aggs'] = array(
                            'sourceWise' => array(
                                'terms' => array(
                                    'field' => 'source',
                                    'size'  => 0
                                )
                            )
                        );
                        $result                       = $this->clientCon->search($elasticQuery);


                        foreach ($result['aggregations']['sourceWise']['buckets'] as $oneResult) {
                            $splitData              = new stdClass();
                            $splitData->PivotName   = ucfirst(htmlentities($oneResult['key']));
                            $splitData->ScalarValue = number_format($oneResult['doc_count']);
                            $splits[]               = $splitData;
                        }
                        return $splits;
                }

                break;

            case 'pgpersess':
                $elasticQuery = $this->getQuery($team, $sourceApplication, $dateRange, '', $view);

                $pageviewAggregation = array(
                    'pageviewWise' => array(
                        'sum' => array(
                            'field' => 'pageviews'
                        )
                    )
                );

                switch ($splitName) {
                    case 'page':

                        $pageAggregation = array(
                            'pageWise' => array(
                                'terms' => array(
                                    'field' => 'landingPageDoc.pageIdentifier',
                                    'size' => 0
                                ),
                                'aggs' => $pageviewAggregation
                            )
                        );

                        $elasticQuery['body']['aggs'] = $pageAggregation;
                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['pageWise']['buckets'] as $oneResult) {
                            $splitData              = new stdClass();
                            $splitData->ScalarValue = number_format($oneResult['pageviewWise']['value'] / $oneResult['doc_count'], 2, '.', '');
                            $splitData->PivotName   = ucfirst(htmlentities($oneResult['key']));
                            $splits[]               = $splitData;
                        }
                        arsort($splits);
                        return array_values($splits);


                    case 'source Application':

                        $deviceAggregation = array(
                            'deviceWise' => array(
                                'terms' => array(
                                    'field' => 'isMobile'
                                ),
                                'aggs' => $pageviewAggregation
                            )
                        );

                        $elasticQuery['body']['aggs'] = $deviceAggregation;
                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['deviceWise']['buckets'] as $oneResult) {
                            $splitData              = new stdClass();
                            $splitData->ScalarValue = number_format($oneResult['pageviewWise']['value'] / $oneResult['doc_count'], 2, '.', '');
                            $splitData->PivotName   = ucfirst(htmlentities($oneResult['key']) == 'yes' ? 'Mobile' : 'Desktop');
                            $splits[]               = $splitData;
                        }
                        arsort($splits);
                        return array_values($splits);


                    case 'utm Campaign':

                        $deviceAggregation = array(
                            'utmWise' => array(
                                'terms' => array(
                                    'field' => 'utm_campaign',
                                    'size' => 0
                                ),
                                'aggs' => $pageviewAggregation
                            )
                        );

                        $elasticQuery['body']['aggs'] = $deviceAggregation;
                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['utmWise']['buckets'] as $oneResult) {
                            $splitData              = new stdClass();
                            $splitData->ScalarValue = number_format($oneResult['pageviewWise']['value'] / $oneResult['doc_count'], 2, '.', '');
                            $splitData->PivotName   = ucfirst(htmlentities($oneResult['key']));
                            $splits[]               = $splitData;
                        }
                        arsort($splits);
                        return array_values($splits);

                    case 'traffic Source':
                        $elasticQuery['body']['aggs'] = array(
                            'sourceWise' => array(
                                'terms' => array(
                                    'field' => 'source',
                                    'size'  => 0
                                ),
                                'aggs' => $pageviewAggregation
                            )
                        );
                        $result                       = $this->clientCon->search($elasticQuery);


                        foreach ($result['aggregations']['sourceWise']['buckets'] as $oneResult) {
                            $splitData              = new stdClass();
                            $splitData->ScalarValue = number_format($oneResult['pageviewWise']['value'] / $oneResult['doc_count'], 2, '.', '');
                            $splitData->PivotName   = ucfirst(htmlentities($oneResult['key']));
                            $splits[]               = $splitData;
                        }
                        arsort($splits);
                        return array_values($splits);
                }
                break;

            case 'bounce':

                $bounceAggregation = array(
                    'bounceWise' => array(
                        'terms' => array(
                            'field' => 'bounce',
                            'size' => 0
                        )
                    )
                );

                $elasticQuery = $this->getQuery($team, $sourceApplication, $dateRange, '', $view);

                switch ($splitName) {
                    case 'page':

                        $pageAggregation = array(
                            'pageWise' => array(
                                'terms' => array(
                                    'field' => 'landingPageDoc.pageIdentifier',
                                    'size' => 0
                                ),
                                'aggs' => $bounceAggregation
                            )
                        );

                        $elasticQuery['body']['aggs'] = $pageAggregation;
                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['pageWise']['buckets'] as $oneResult) {

                            if(count($oneResult['bounceWise']['buckets']) < 2)
                                continue;

                            foreach($oneResult['bounceWise']['buckets'] as $bounceOrNot){
                                if($bounceOrNot['key'] == 1){
                                    $splitData              = new stdClass();
                                    $splitData->ScalarValue = number_format($bounceOrNot['doc_count'] / $oneResult['doc_count'] * 100, 2, '.', '');
                                    $splitData->PivotName   = ucfirst(htmlentities($oneResult['key']));
                                    $splits[]               = $splitData;
                                }
                            }
                        }
                        arsort($splits);
                        return array_values($splits);


                    case 'source Application':

                        $deviceAggregation = array(
                            'deviceWise' => array(
                                'terms' => array(
                                    'field' => 'isMobile'
                                ),
                                'aggs' => $bounceAggregation
                            )
                        );

                        $elasticQuery['body']['aggs'] = $deviceAggregation;
                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['deviceWise']['buckets'] as $oneResult) {
                            if(count($oneResult['bounceWise']['buckets']) < 2)
                                continue;

                            foreach($oneResult['bounceWise']['buckets'] as $bounceOrNot){
                                if($bounceOrNot['key'] == 1){
                                    $splitData              = new stdClass();
                                    $splitData->ScalarValue = number_format($bounceOrNot['doc_count'] / $oneResult['doc_count'] * 100, 2, '.', '');
                                    $splitData->PivotName   = ucfirst($oneResult['key'] == 'yes' ? 'mobile' : 'desktop');
                                    $splits[]               = $splitData;
                                }
                            }
                        }
                        arsort($splits);
                        return array_values($splits);

                    case 'utm Campaign':

                        $deviceAggregation = array(
                            'utmWise' => array(
                                'terms' => array(
                                    'field' => 'utm_campaign',
                                    'size' => 0
                                ),
                                'aggs' => $bounceAggregation
                            )
                        );

                        $elasticQuery['body']['aggs'] = $deviceAggregation;
                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['utmWise']['buckets'] as $oneResult) {
                            if(count($oneResult['bounceWise']['buckets']) < 2)
                                continue;

                            foreach($oneResult['bounceWise']['buckets'] as $bounceOrNot){
                                if($bounceOrNot['key'] == 1){
                                    $splitData              = new stdClass();
                                    $splitData->ScalarValue = number_format($bounceOrNot['doc_count'] / $oneResult['doc_count'] * 100, 2, '.', '');
                                    $splitData->PivotName   = ucfirst(htmlentities($oneResult['key']));
                                    $splits[]               = $splitData;
                                }
                            }
                        }
                        arsort($splits);
                        return array_values($splits);

                    case 'traffic Source':
                        $trafficAggregation = array(
                            'sourceWise' => array(
                                'terms' => array(
                                    'field' => 'source',
                                    'size'  => 0
                                ),
                                'aggs' => $bounceAggregation
                            )
                        );

                        $elasticQuery['body']['aggs'] = $trafficAggregation;

                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['sourceWise']['buckets'] as $oneResult) {
                            if(count($oneResult['bounceWise']['buckets']) < 2)
                                continue;

                            foreach($oneResult['bounceWise']['buckets'] as $bounceOrNot){
                                if($bounceOrNot['key'] == 1){
                                    $splitData              = new stdClass();
                                    $splitData->ScalarValue = number_format($bounceOrNot['doc_count'] / $oneResult['doc_count'] * 100, 2, '.', '');
                                    $splitData->PivotName   = ucfirst(htmlentities($oneResult['key']));
                                    $splits[]               = $splitData;
                                }
                            }
                        }
                        arsort($splits);
                        return array_values($splits);
                }

                break;

            case 'avgsessdur':
                $totalDuration = array(
                    'totalDuration' => array(
                        'sum' => array(
                            'field' => 'duration'
                        )
                    )
                );

                $elasticQuery = $this->getQuery($team, $sourceApplication, $dateRange, '', $view);

                switch ($splitName) {
                    case 'page':

                        $pageAggregation = array(
                            'pageWise' => array(
                                'terms' => array(
                                    'field' => 'landingPageDoc.pageIdentifier',
                                    'size' => 0
                                ),
                                'aggs' => $totalDuration
                            )
                        );

                        $elasticQuery['body']['aggs'] = $pageAggregation;
                        $result                       = $this->clientCon->search($elasticQuery);
                        foreach ($result['aggregations']['pageWise']['buckets'] as $oneResult) {
                            $chartOnOneDate               = new stdClass();
                            $averageSessionDurationInSeconds = number_format($oneResult['totalDuration']['value'] / $oneResult['doc_count'], 2, '.', '');
                            $chartOnOneDate->ScalarValue  = $averageSessionDurationInSeconds;
                            $chartOnOneDate->PivotName = ucfirst(htmlentities($oneResult['key']));
                            $splits[] = $chartOnOneDate;
                        }

                        arsort($splits);
                        return array_values($splits);

                    case 'source Application':

                        $deviceAggregation = array(
                            'deviceWise' => array(
                                'terms' => array(
                                    'field' => 'isMobile'
                                ),
                                'aggs' => $totalDuration
                            )
                        );

                        $elasticQuery['body']['aggs'] = $deviceAggregation;
                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['deviceWise']['buckets'] as $oneResult) {
                            $chartOnOneDate               = new stdClass();
                            $averageSessionDurationInSeconds = number_format($oneResult['totalDuration']['value'] / $oneResult['doc_count'], 2, '.', '');
                            $chartOnOneDate->ScalarValue  = $averageSessionDurationInSeconds;
                            $chartOnOneDate->PivotName = ucfirst(htmlentities($oneResult['key'] == 'yes' ? 'mobile' : 'desktop'));
                            $splits[] = $chartOnOneDate;
                        }
                        arsort($splits);
                        return array_values($splits);

                    case 'utm Campaign':

                        $deviceAggregation = array(
                            'utmWise' => array(
                                'terms' => array(
                                    'field' => 'utm_campaign',
                                    'size' => 0
                                ),
                                'aggs' => $totalDuration
                            )
                        );

                        $elasticQuery['body']['aggs'] = $deviceAggregation;
                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['utmWise']['buckets'] as $oneResult) {
                            $chartOnOneDate               = new stdClass();
                            $averageSessionDurationInSeconds = number_format($oneResult['totalDuration']['value'] / $oneResult['doc_count'], 2, '.', '');
                            $chartOnOneDate->ScalarValue  = $averageSessionDurationInSeconds;
                            $chartOnOneDate->PivotName = ucfirst(htmlentities($oneResult['key']));
                            $splits[] = $chartOnOneDate;
                        }

                        arsort($splits);
                        return array_values($splits);

                    case 'traffic Source':
                        $trafficAggregation = array(
                            'sourceWise' => array(
                                'terms' => array(
                                    'field' => 'source',
                                    'size'  => 0
                                ),
                                'aggs' => $totalDuration
                            )
                        );

                        $elasticQuery['body']['aggs'] = $trafficAggregation;

                        $result                       = $this->clientCon->search($elasticQuery);

                        foreach ($result['aggregations']['sourceWise']['buckets'] as $oneResult) {
                            $chartOnOneDate               = new stdClass();
                            $averageSessionDurationInSeconds = number_format($oneResult['totalDuration']['value'] / $oneResult['doc_count'], 2, '.', '');
                            $chartOnOneDate->ScalarValue  = $averageSessionDurationInSeconds;
                            $chartOnOneDate->PivotName = ucfirst(htmlentities($oneResult['key']));
                            $splits[] = $chartOnOneDate;
                        }
                        arsort($splits);
                        return array_values($splits);
                }
                break;
        }
    }

    private function getPageviewQuery($dateRange,$team, $sourceApplication,$view=1){

        $startDate = $dateRange['startDate'].'T00:00:00';
        $endDate = $dateRange['endDate'].'T23:59:59';

        $startDateFilter = array(
            'range' => array(
                'visitTime' => array(
                    'gte' => $startDate
                )
            )
        );
        $endDateFilter = array(
            'range' => array(
                'visitTime' => array(
                    'lte' => $endDate
                )
            )
        );

        $dateAggregation = array(
            'dateWise' => array(
                'date_histogram' => array(
                    'field' => 'visitTime',
                    'interval' => MISCommonLib::getView($view),
                    'order' =>  array(
                        '_key' => 'desc'
                    )
                ),
            )
        );

        $elasticQuery = array(
            'index' => MISCommonLib::$TRAFFICDATA_PAGEVIEWS,
            'type' => 'pageview',
            'body' => array(
                'size' => 0,
                'query' => array(
                    'filtered' => array(
                        'filter' => array(
                            'bool' => array(
                                'must' => array(
                                    $startDateFilter,
                                    $endDateFilter
                                )
                            )
                        )
                    )
                ),
                'aggs' => $dateAggregation,
                "sort" => array(
                    'visitTime' => array(
                        'order' => 'desc'
                    )
                )
            )
        );

        $isMobile = '';
        if($sourceApplication != '' && strcasecmp($sourceApplication, 'all') != 0){

            if(strcasecmp($sourceApplication, 'desktop') == 0){
                $isMobile = 'no';
            } else if (strcasecmp($sourceApplication, 'mobile') == 0){
                $isMobile = 'yes';
            }

            $deviceFilter = array(
                'term' => array(
                    'isMobile' => $isMobile
                )
            );

            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $deviceFilter;
        }

        if($team != 'global'){
            $isStudyAbroad = strcasecmp($team, "abroad") == 0 ? "yes" : "no";
            $teamFilter = array(
                "term" => array(
                    "isStudyAbroad" => $isStudyAbroad
                )
            );

            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $teamFilter;
        }

        return $elasticQuery;

    }

    private function getPgPerSessTrend($dateRange, $team, $sourceApplication, $view=1){

        $this->getElasticSearch();
        $elasticQuery = $this->getQuery($team, $sourceApplication, $dateRange, '', $view);

        $pageviewWise = array(
            'pageviewWise' => array(
                'sum' => array(
                    'field' => 'pageviews'
                )
            )
        );

        $elasticQuery['body']['aggs'] = array(
            'dateWise' => array(
                'date_histogram' => array(
                    'field' => 'startTime',
                    'interval' => MISCommonLib::getView($view)
                ),
                'aggs' => $pageviewWise
            )
        );

        $result = $this->clientCon->search($elasticQuery);
        $chartData = array();

        foreach($result['aggregations']['dateWise']['buckets'] as $oneDateData) {

            $chartOnOneDate = new stdClass();
            $chartOnOneDate->ScalarValue = number_format($oneDateData['pageviewWise']['value'] / $oneDateData['doc_count'], 2, '.', '');
            $chartOnOneDate->Date = $this->MISCommonLib->extractDate(strval($oneDateData['key_as_string']));
            $chartData[] = $chartOnOneDate;
        }

        return $chartData;

}

    private function getBounceTrend($dateRange, $team, $sourceApplication, $view = 1)
    {
        $this->getElasticSearch();
        $elasticQuery = $this->getQuery($team, $sourceApplication, $dateRange, '', $view);

        $bounceAggregation = array(
            'bounceWise' => array(
                'terms' => array(
                    'field' => 'bounce'
                )
            )
        );

        $elasticQuery['body']['aggs'] = array(
            'dateWise' => array(
                'date_histogram' => array(
                    'field'    => 'startTime',
                    'interval' => MISCommonLib::getView($view)
                ),
                'aggs'           => $bounceAggregation
            )
        );

        $result    = $this->clientCon->search($elasticQuery);
        $chartData = array();

        foreach ($result['aggregations']['dateWise']['buckets'] as $oneDateData) {

            $bounceInformation = $oneDateData['bounceWise']['buckets'];
            if (count($bounceInformation) < 2)
                continue;


            foreach ($bounceInformation as $bounceOrNot) {
                if ($bounceOrNot['key'] == '1') {
                    $chartOnOneDate               = new stdClass();
                    $chartOnOneDate->Date = $this->MISCommonLib->extractDate(strval($oneDateData['key_as_string']));
                    $chartOnOneDate->ScalarValue  = number_format($bounceOrNot['doc_count'] / $oneDateData['doc_count'] * 100, 2, '.', '');
                }
            }
            $chartData[] = $chartOnOneDate;
        }

        return $chartData;
    }

    private function getAvgSessDurTrend($dateRange, $team, $sourceApplication, $view=1)
    {
        $this->getElasticSearch();
        $elasticQuery = $this->getQuery($team, $sourceApplication, $dateRange, '', $view);

        $totalDuration = array(
            'totalDuration' => array(
                'sum' => array(
                    'field' => 'duration'
                )
            )
        );

        $elasticQuery['body']['aggs'] = array(
            'dateWise' => array(
                'date_histogram' => array(
                    'field' => 'startTime',
                    'interval' => MISCommonLib::getView($view)
                ),
                'aggs' => $totalDuration
            )
        );

        $result = $this->clientCon->search($elasticQuery);
        $chartData = array();

        foreach($result['aggregations']['dateWise']['buckets'] as $oneDateData){
            $chartOnOneDate               = new stdClass();
            $chartOnOneDate->Date = $this->MISCommonLib->extractDate(strval($oneDateData['key_as_string']));
            $averageSessionDurationInSeconds = number_format($oneDateData['totalDuration']['value'] / $oneDateData['doc_count'], 2, '.', '');
//            $seconds = $averageSessionDurationInSeconds % 60;
//            $minutes = ($averageSessionDurationInSeconds /60)  % 60;
//            $hours = ( $averageSessionDurationInSeconds / (60*60) )  % 60;
//            $chartOnOneDate->ScalarValue  = str_pad($hours, 2, '0', STR_PAD_LEFT).':'.str_pad($minutes, 2, '0', STR_PAD_LEFT). ':'. str_pad($seconds, 2, '0', STR_PAD_LEFT); // This is useful
            $chartOnOneDate->ScalarValue  = $averageSessionDurationInSeconds;
            $chartData[] = $chartOnOneDate;
        }

        return $chartData;
    }

    // Top tile functionality
    public function getTopTilesForEngagement($dateRange, $pageName = '', $extraData=array(), $aspect){

        $this->getElasticSearch();
        $elasticQuery = $this->getPageviewTopTileQuery($dateRange, $pageName, $extraData);
        $pageViews = $this->clientCon->search($elasticQuery);
        $topTiles = array(
            'pageview' => number_format($pageViews['hits']['total'])
        );

        $elasticQuery = $this->getOtherEngagementTopTileQuery($dateRange, $pageName, $extraData);

        $pageviewWise = array(
            'pageviewWise' => array(
                'sum' => array(
                    'field' => 'pageviews'
                )
            )
        );
        $elasticQuery['body']['aggs'] = $pageviewWise;
        $result = $this->clientCon->search($elasticQuery);

        $topTiles['pgpersess'] = number_format($result['aggregations']['pageviewWise']['value'] / $result['hits']['total'], 2, '.', '');

        $elasticQuery = $this->getOtherEngagementTopTileQuery($dateRange, $pageName, $extraData);

        $totalDuration = array(
            'totalDuration' => array(
                'sum' => array(
                    'field' => 'duration'
                )
            )
        );
        $elasticQuery['body']['aggs'] = $totalDuration;
        $result = $this->clientCon->search($elasticQuery);

        $averageSessionDuration = number_format($result['aggregations']['totalDuration']['value'] / $result['hits']['total'], 2, '.', '');
        $hourFormat = explode(".", $averageSessionDuration);
        //$topTiles['avgsessdur'] = date('H:i:s', mktime(0, 0, $hourFormat[0])).'.'.$hourFormat[1];
		$topTiles['avgsessdur'] = date('i:s', mktime(0, 0, $hourFormat[0]));

        $elasticQuery = $this->getOtherEngagementTopTileQuery($dateRange, $pageName, $extraData);
        $bounceAggregation = array(
            'bounceWise' => array(
                'terms' => array(
                    'field' => 'bounce'
                )
            )
        );
        $elasticQuery['body']['aggs'] = $bounceAggregation;

        $result    = $this->clientCon->search($elasticQuery);
        foreach($result['aggregations']['bounceWise']['buckets'] as $bounceOrNot){
            if($bounceOrNot['key'] == 1){
                $topTiles['bounce'] = number_format($bounceOrNot['doc_count'] / $result['hits']['total'] * 100, 2, '.', '');
                break;
            }
        }

        return $topTiles;

    }

    private function getPageviewTopTileQuery($dateRange, $pageName='', $extraData = array())
    {
        $startDate = $dateRange['startDate'].'T00:00:00';
        $endDate = $dateRange['endDate'].'T23:59:59';
        $sourceApplication = $extraData['deviceType'];

        $startDateFilter = array(
            'range' => array(
                'visitTime' => array(
                    'gte' => $startDate
                )
            )
        );
        $endDateFilter = array(
            'range' => array(
                'visitTime' => array(
                    'lte' => $endDate
                )
            )
        );

        $elasticQuery = array(
            'index' => MISCommonLib::$TRAFFICDATA_PAGEVIEWS,
            'type' => 'pageview',
            'body' => array(
                'size' => 0,
                'query' => array(
                    'filtered' => array(
                        'filter' => array(
                            'bool' => array(
                                'must' => array(
                                    $startDateFilter,
                                    $endDateFilter
                                )
                            )
                        )
                    )
                ),
                "sort" => array(
                    'visitTime' => array(
                        'order' => 'desc'
                    )
                )
            )
        );

        $isMobile = '';
        if($sourceApplication != '' && strcasecmp($sourceApplication, 'all') != 0){

            if(strcasecmp($sourceApplication, 'desktop') == 0){
                $isMobile = 'no';
            } else if (strcasecmp($sourceApplication, 'mobile') == 0){
                $isMobile = 'yes';
            }

            if(!empty($isMobile)){
                $deviceFilter = array(
                    'term' => array(
                        'isMobile' => $isMobile
                    )
                );

                $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $deviceFilter;
            }
                
        }

        $team = $extraData['team'];
        if( $team != 'global' && $team != ''){
            $isStudyAbroad = strcasecmp($team, "abroad") == 0 ? "yes" : "no";
            $teamFilter = array(
                "term" => array(
                    "isStudyAbroad" => $isStudyAbroad
                )
            );

            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $teamFilter;
        }

        return $elasticQuery;
    }

    private function getOtherEngagementTopTileQuery($dateRange, $pageName='', $extraData = array()){
        $elasticQuery = array();
        $elasticQuery['index'] = MISCommonLib::$TRAFFICDATA_SESSIONS;
        $elasticQuery['type'] = 'session';

        $elasticQuery['body']['size'] = 0;

        $startDate = $dateRange['startDate'].'T00:00:00';
        $endDate = $dateRange['endDate'].'T23:59:59';

        $team = $extraData['team'];
        $sourceApplication = $extraData['deviceType'];

        if( $team != 'global'){
            $isStudyAbroad = strcasecmp($team, "abroad") == 0 ? "yes" : "no";
            $teamFilter = array(
                "term" => array(
                    "landingPageDoc.isStudyAbroad" => $isStudyAbroad
                )
            );

            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $teamFilter;
        }

        if($sourceApplication !='all' && $sourceApplication !=''){
            $isMobile = strcasecmp($sourceApplication, "mobile") == 0 ? "yes" : "no";
            $deviceFilter = array(
                "term" => array(
                    "isMobile" => $isMobile
                )
            );
            $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $deviceFilter;
        }

        $startDateFilter = array(
            "range" => array(
                "startTime" => array(
                    "gte" => $startDate
                )
            )
        );

        $endDateFilter = array(
            "range" => array(
                "startTime" => array(
                    "lte" => $endDate
                )
            )
        );

        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
        return $elasticQuery;
    }

    // Probably unused
    public function getTopTilesForResponses($dateRange, $pageName='', $extraData = array()){

        $team = $extraData['team'];
        $sourceApplication = $extraData['deviceType'];
        $products = array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID);
        $paidCoursesData =             $this->getPaidCourses($team, $dateRange,$products); // count of free courses
        $numberOfPaidCourses = count($paidCoursesData); // Count of paid courses
        $responsesData = $this->getResponsesDataForShikshaResponses($team, $sourceApplication, $dateRange); // count of RMC Response, total responses as a sum of paid and free responses, paid responses
        $aggregateCount = $this->getResponseCountFromResponseData($responsesData);
        $usersData =             $this->getRespondents($dateRange, $pageName, $extraData); // get unique respondents and responses / respondent ratio

        return array(
            'totalResponseCount' =>  number_format($aggregateCount['total']),
            'paidResponseCount' => number_format($aggregateCount['paid']),
            'rmcResponseCount' => number_format($aggregateCount['rmc']),
            'paidCoursesCount' => number_format($numberOfPaidCourses),
            'firstTimeUserCount' => number_format($usersData['firstTimeUsers']),
            'respondentRatio' => number_format($aggregateCount['total'] / $usersData['allUsers'], 2, '.', '')
        );
    }

    function getResponsesDataForShikshaResponses($team, $sourceApplication, $dateRange){
        // only applicable to shiksha not for Domestic/Abroad
        //_p($team);_p($sourceApplication);_p($dateRange);die;
        $this->initiateModel();
        $this->dbHandle->select("sum(paidResponses) as paidResponses,sum(freeResponses) as freeResponses ,sum(paidRMC) as paidRMC,sum(freeRMC) as freeRMC");
        $this->dbHandle->from('MISOverviewData');
        $this->dbHandle->where('date >= ',$dateRange['startDate']);
        $this->dbHandle->where('date <= ',$dateRange['endDate']);
        if($sourceApplication != ''){
            $this->dbHandle->where('sourceApplication',ucfirst($sourceApplication));
        }
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    private function getResponseCountFromResponseData($responseArray){
        $responseArray = $responseArray[0];
        $rmcResponses = $responseArray['paidRMC']+$responseArray['freeRMC'];
        $paidResponses = $responseArray['paidResponses'];
        $totalResponses = $responseArray['paidResponses'] + $responseArray['freeResponses'];

        return array(
            'total' => $totalResponses,
            'rmc' => $rmcResponses,
            'paid' => $paidResponses
        );

    }

    private function getRespondents($dateRange, $pageName, $extraData)
    {
        /*SELECT
            COUNT(DISTINCT (userid)) AS firstTimeUsers
            FROM
                tempLMSTable
            WHERE
            userId NOT IN (SELECT DISTINCT
                    userId
                FROM
                    tempLMSTable
                WHERE
                    submit_date < '2016-01-04'
                    AND tracking_keyid IS NOT NULL
            AND tracking_keyid != 0)
                AND submit_date >= '2016-01-04'
            AND submit_date <= '2016-02-01';
        */



        $this->dbHandle->select('distinct(tlt.userId) as AllUsers');
        $this->dbHandle->from('tempLMSTable tlt');
        $this->dbHandle->join('tracking_pagekey tp','tlt.tracking_keyid = tp.id','inner');
        $this->dbHandle->where("date(tlt.submit_date) >= ",$dateRange['startDate']);
        $this->dbHandle->where("date(tlt.submit_date) <= ",$dateRange['endDate']);
        if($extraData['deviceType']){

            $this->dbHandle->where("tp.siteSource",ucfirst($extraData['deviceType']));
        }

        $result = $this->dbHandle->get()->result_array();
        //_p($result);die;
        //echo $this->dbHandle->last_query();
        $userIdsArray = array_map(function($a){
                return $a['AllUsers'];
        }, $result);

        $allUsers = count($result);


        $this->dbHandle->select('count(distinct(userId)) as count');
        $this->dbHandle->from('tempLMSTable');
        $this->dbHandle->where("date(submit_date) < ",$dateRange['startDate']);
        $this->dbHandle->where_in('userId',$userIdsArray);


        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        $repeatUsers = $result[0]['count'];
        $firstTimeUsers = $allUsers - $repeatUsers;
        //$firstTimeUsers = $result[0]->AllUsers;
        //_p($allUsers);_p($firstTimeUsers);die;
        return array(
            'allUsers' => $allUsers,
            'firstTimeUsers' => $firstTimeUsers
        );
    }
	function getCountryIdToName($countryIds){
        $this->initiateModel();
        $this->dbHandle->select('distinct(countryId),name');
        $this->dbHandle->from('countryTable');
        $this->dbHandle->where_in('countryId',$countryIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }
    function getUTMwiseDataBasedOnSessionId($sessionId = array(),$source = '',$groupByFlag = '')
    {
        $this->initiateModel();
        if($groupByFlag == 'utmSource')
        {
            $PivotName = "utm_source";
            //$groupByClause = "group by utm_source";
        }
        else if($groupByFlag == 'utmMedium')
        {
            $PivotName = "utm_medium";
            //$groupByClause = "group by utm_medium";
        }
        else if($groupByFlag == 'utmCampaign')
        {
            $PivotName = "utm_campaign";
            //$groupByClause = "group by utm_campaign";
        }
        $sessionFilter = '"'.implode('","', $sessionId).'"';
        $sql = "SELECT LOWER(".$PivotName.") as PivotName , sessionId From session_tracking where sessionId IN (".$sessionFilter.") and source = '".$source."'";
        return $this->dbHandle->query($sql)->result_array();
    }

    function getTrackingIdsForOverviewRegistration($sourceApplication){
        $this->initiateModel();
        $this->dbHandle->select('id');
        $this->dbHandle->FROM('tracking_pagekey');
        if($sourceApplication){
            $sourceApplication = ucwords($sourceApplication);
            $this->dbHandle->where('siteSource',$sourceApplication);
        }
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();
        foreach ($result as $key => $value) {
            $trackingIds[] = $value['id'];
        }
        return $trackingIds;
    }

    function getSessionIdsForOverviewRegistration($trackingIdsForSessions,$dateRange){
        $this->initiateModel();
        $this->dbHandle->select('visitorSessionId as visitorsessionid,count(distinct id) as count');

        //$this->dbHandle->from('tusersourceInfo');
        $this->dbHandle->from('registrationTracking rt');
        $this->dbHandle->where('submitDate >=', $dateRange['startDate']);
        $this->dbHandle->where('submitDate <=', $dateRange['endDate']);
        $this->dbHandle->where('rt.isNewReg','yes');

        //$this->dbHandle->where_in('trackingkeyId', $trackingIdsForSessions);    
        $this->dbHandle->where('trackingkeyId IN ('.implode(',', $trackingIdsForSessions).')');

        $this->dbHandle->group_by('visitorsessionid');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        //echo _p($result);die;
        //error_log('qry  tracking = '.$this->dbHandle->last_query());
        return $result;
    }

    function getSourceForSessionIdForOverviewRegistration($sessionId = array(),$columnFilter='source',$defaultView='')
    {
        $this->initiateModel();
        switch ($columnFilter) {
            case 'source':
                $this->dbHandle->select('sessionId,source as value');
                break;

            case 'utmSource':
                $this->dbHandle->select('sessionId,utm_source as value');
                break;

            case 'utmCampaign':
                $this->dbHandle->select('sessionId,utm_campaign as value');
                break;

            case 'utmMedium':
                $this->dbHandle->select('sessionId,utm_medium as value');
                break;

            default:
                $this->dbHandle->select('sessionId,source as value');
                break;
        }
        //_p($selectFields);die;
        //_p($sessionId);die;
        $this->dbHandle->from('session_tracking');
        if($defaultView){
            $this->dbHandle->where('source',$defaultView);
        }
        $this->dbHandle->where_in('sessionId',$sessionId);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }
    function getSourceForSessionIds($sessionId)
    {
        $this->initiateModel();
        $this->dbHandle->select('source,sessionId');
        $this->dbHandle->from('session_tracking');
        $this->dbHandle->where_in('sessionId',$sessionId);
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }


    function getAllSubExamId($examId){
        $this->initiateModel('read');
        //select blogId from blogTable where blogType ='exam' AND status = 'live' and parentId=464;
        $this->dbHandle->select('distinct(blogId)');
        $this->dbHandle->from('blogTable');
        $this->dbHandle->where('blogType','exam');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where('parentId',$examId);
        //echo $dbHandle->_compile_select();die;
        $result = $this->dbHandle->get()->result_array();
        //echo $dbHandle->last_query();die;
        foreach ($result as $key => $value) {
            $blogIds[] = $value['blogId'];
        }
        return $blogIds;
    }

    // Abroad Search Quries
    public function getTrackingIdsForAbroadSearch($inputRequest){
        $this->initiateModel();
        $this->dbHandle->select('id');
        $this->dbHandle->from('tracking_pagekey');
        if($inputRequest['sourceApplication'] && $inputRequest['sourceApplication'] != 'all'){
            $this->dbHandle->where('siteSource',ucfirst($inputRequest['sourceApplication']));
        }
        $this->dbHandle->where('conversionType','searchLayerOpening');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getSearchCountForSearch($inputRequest,$trackingIds){
        $this->initiateModel();
        $this->dbHandle->select('historyTracking,count(id) as count');
        $this->dbHandle->from('searchTrackingSA');
        if($inputRequest['sourceApplication'] && $inputRequest['sourceApplication'] != 'all'){
            $this->dbHandle->where('sourceApplication',strtolower($inputRequest['sourceApplication']));
        }
        $this->dbHandle->where_in('trackingKeyId',$trackingIds);
        $this->dbHandle->where('searchTime >=',$inputRequest['dateRange']['startDate'].' 00:00:00');
        $this->dbHandle->where('searchTime <=',$inputRequest['dateRange']['endDate'].' 23:59:59');
        $this->dbHandle->group_by('historyTracking');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getSearchSplitwiseCount($inputRequest,$trackingIds,$fieldName){
        //_p($fieldName);die;
        $this->initiateModel();
        //$selectFields = 
        $this->dbHandle->select($fieldName.' as field ,count(id) as count');
        $this->dbHandle->from('searchTrackingSA');
        if($inputRequest['sourceApplication'] && $inputRequest['sourceApplication'] != 'all'){
            $this->dbHandle->where('sourceApplication',strtolower($inputRequest['sourceApplication']));
        }
        $this->dbHandle->where_in('trackingKeyId',$trackingIds);
        $this->dbHandle->where('searchTime >=',$inputRequest['dateRange']['startDate'].' 00:00:00');
        $this->dbHandle->where('searchTime <=',$inputRequest['dateRange']['endDate'].' 23:59:59');
        $this->dbHandle->group_by($fieldName);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getStudyAbroadSearchTrands($inputRequest,$trackingIds,$fieldName){
        //_p($inputRequest);die;
        $this->initiateModel();
        if($inputRequest['view'] == 1){
            $res = '';
            $this->dbHandle->group_by('date');
        }else if($inputRequest['view'] == 2){
            $res = ', week(date('.$fieldName.'),1) as week';
            $this->dbHandle->group_by('week');
        }else if($inputRequest['view'] == 3){
            $res = ', month('.$fieldName.') as month';
            $this->dbHandle->group_by('month,date');
        }
        $this->dbHandle->select('date('.$fieldName.') as date ,count(1) as count'.$res,false);
        $this->dbHandle->from('searchTrackingSA');
        if($inputRequest['sourceApplication'] && $inputRequest['sourceApplication'] != 'all'){
            $this->dbHandle->where('sourceApplication',strtolower($inputRequest['sourceApplication']));
        }
        $this->dbHandle->where_in('trackingKeyId',$trackingIds);
        $this->dbHandle->where('searchTime >=',$inputRequest['dateRange']['startDate'].' 00:00:00');
        $this->dbHandle->where('searchTime <=',$inputRequest['dateRange']['endDate'].' 23:59:59');
        $this->dbHandle->order_by("count", "asc");
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getIdsFromSearchTrackingSATable($inputRequest,$trackingIds){
        $this->initiateModel();
        $this->dbHandle->select('id');
        $this->dbHandle->from('searchTrackingSA');
        if($inputRequest['sourceApplication'] && $inputRequest['sourceApplication'] != 'all'){
            $this->dbHandle->where('sourceApplication',strtolower($inputRequest['sourceApplication']));
        }
        $this->dbHandle->where_in('trackingKeyId',$trackingIds);
        $this->dbHandle->where('searchTime >=',$inputRequest['dateRange']['startDate'].' 00:00:00');
        $this->dbHandle->where('searchTime <=',$inputRequest['dateRange']['endDate'].' 23:59:59');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getDistinctSessionCountForSearch($inputRequest,$trackingIds){
        $this->initiateModel();
        $this->dbHandle->select('count(distinct(visitorSessionId)) as count');
        $this->dbHandle->from('searchTrackingSA');
        if($inputRequest['sourceApplication'] && $inputRequest['sourceApplication'] != 'all'){
            $this->dbHandle->where('sourceApplication',strtolower($inputRequest['sourceApplication']));
        }
        $this->dbHandle->where_in('trackingKeyId',$trackingIds);
        $this->dbHandle->where('searchTime >=',$inputRequest['dateRange']['startDate'].' 00:00:00');
        $this->dbHandle->where('searchTime <=',$inputRequest['dateRange']['endDate'].' 23:59:59');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result[0]['count'];
    }

    //$inputRequest,$trackingIds
    public function getAppliedFilterForSearch($inputRequest,$trackingIds){
        $this->initiateModel();
        $this->dbHandle->select('count(spfat.id) as count');
        $this->dbHandle->from('SearchPageFiltersAppliedTrackingSA spfat');
        $this->dbHandle->join('searchTrackingSA st','spfat.searchTrackingSAId = st.id','inner');
        if($inputRequest['sourceApplication'] && $inputRequest['sourceApplication'] != 'all'){
            $this->dbHandle->where('st.sourceApplication',strtolower($inputRequest['sourceApplication']));
        }
        $this->dbHandle->where_in('st.trackingKeyId',$trackingIds);
        $this->dbHandle->where('st.searchTime >=',$inputRequest['dateRange']['startDate'].' 00:00:00');
        $this->dbHandle->where('st.searchTime <=',$inputRequest['dateRange']['endDate'].' 23:59:59');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result[0]['count'];
    }

    public function getSortingAppliedForSearch($inputRequest,$trackingIds){
        $this->initiateModel();
        $this->dbHandle->select('count(spsat.id) as count');
        $this->dbHandle->from('SearchPageSortingAppliedTrackingSA spsat');
        $this->dbHandle->join('searchTrackingSA st','spsat.searchTrackingSAId = st.id','inner');
        if($inputRequest['sourceApplication'] && $inputRequest['sourceApplication'] != 'all'){
            $this->dbHandle->where('st.sourceApplication',strtolower($inputRequest['sourceApplication']));
        }
        $this->dbHandle->where_in('st.trackingKeyId',$trackingIds);
        $this->dbHandle->where('st.searchTime >=',$inputRequest['dateRange']['startDate'].' 00:00:00');
        $this->dbHandle->where('st.searchTime <=',$inputRequest['dateRange']['endDate'].' 23:59:59');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result[0]['count'];
    }

    public function getPageInterectionWiseCount($inputRequest,$trackingIds)
    {
        $this->initiateModel();
        $this->dbHandle->select('clickSource, count(spit.id) as count');
        $this->dbHandle->from('searchTrackingSA st');
        $this->dbHandle->join('searchPageInteractionTrackingSA spit','spit.searchTrackingSAId = st.id','inner');

        if($inputRequest['sourceApplication'] && $inputRequest['sourceApplication'] != 'all'){
            $this->dbHandle->where('st.sourceApplication',strtolower($inputRequest['sourceApplication']));
        }
        $this->dbHandle->where_in('st.trackingKeyId',$trackingIds);
        $this->dbHandle->where('st.searchTime >=',$inputRequest['dateRange']['startDate'].' 00:00:00');
        $this->dbHandle->where('st.searchTime <=',$inputRequest['dateRange']['endDate'].' 23:59:59');
        $this->dbHandle->group_by('spit.clickSource');
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

    public function getPageInteractionForSearch($inputRequest,$trackingIds){
        $this->initiateModel();
        $this->dbHandle->select('count(spit.id) as count');
        $this->dbHandle->from('searchPageInteractionTrackingSA spit');
        $this->dbHandle->join('searchTrackingSA st','spit.searchTrackingSAId = st.id','inner');

        if($inputRequest['sourceApplication'] && $inputRequest['sourceApplication'] != 'all'){
            $this->dbHandle->where('st.sourceApplication',strtolower($inputRequest['sourceApplication']));
        }
        $this->dbHandle->where_in('st.trackingKeyId',$trackingIds);
        $this->dbHandle->where('st.searchTime >=',$inputRequest['dateRange']['startDate'].' 00:00:00');
        $this->dbHandle->where('st.searchTime <=',$inputRequest['dateRange']['endDate'].' 23:59:59');

        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result[0]['count'];
    }

    public function getPageName($trackingIds){
        if($trackingIds && count($trackingIds) > 0){
            $this->initiateModel();
            $this->dbHandle->select('id,page');
            $this->dbHandle->from('tracking_pagekey');
            $this->dbHandle->where_in('id',$trackingIds);
            $result = $this->dbHandle->get()->result_array();
            //echo $this->dbHandle->last_query();die;
            return $result;
        }
    }

    function getUniquePBTPixels(){

        $this->initiateModel();
        $this->dbHandle->select('distinct(pixel_id)');
        $this->dbHandle->from('OF_Conversion_Tracking');

        $result = $this->dbHandle->get()->result_array();

        return $result;
    }

     function getPBTTrackingDetails($fromDate, $toDate){

        $this->initiateModel();

        // 4. total form landings
        $this->dbHandle->select('distinct(pixel_id), count(*) as cnt');
        $this->dbHandle->from('OF_Conversion_Tracking');
        $this->dbHandle->where('action','landed');
        if(!empty($fromDate))
            $this->dbHandle->where("time BETWEEN '". $fromDate. "' and '". $toDate."'");
        $this->dbHandle->group_by('pixel_id');
        $this->dbHandle->order_by("cnt", "DESC");
        $result = $this->dbHandle->get()->result_array();

        $pixelWiseData = array();
        foreach ($result as $key => $value) {
            $pixelWiseData[$value['pixel_id']]['landings']['total'] = $value['cnt'];
        }

        // 5. total form landings + shiksha visitors + non-registered
        $this->dbHandle->select('distinct(pixel_id), count(*) as cnt');
        $this->dbHandle->from('OF_Conversion_Tracking');
        $this->dbHandle->where('action','landed');
        $this->dbHandle->where('user_id',0);
        $this->dbHandle->where('is_shiksha_visitor',1);
        if(!empty($fromDate))
            $this->dbHandle->where("time BETWEEN '". $fromDate. "' and '". $toDate."'");
        $this->dbHandle->group_by('pixel_id');
        $this->dbHandle->order_by("cnt", "DESC");
        $result = $this->dbHandle->get()->result_array();
        foreach ($result as $key => $value) {
            $pixelWiseData[$value['pixel_id']]['landings']['nonRegisteredshikshaVisitors'] = $value['cnt'];
        }

        // 6. total form landings + shiksha visitors + registered
        $this->dbHandle->select('distinct(pixel_id), count(*) as cnt');
        $this->dbHandle->from('OF_Conversion_Tracking');
        $this->dbHandle->where('action','landed');
        $this->dbHandle->where("user_id !=", 0);
        $this->dbHandle->where('is_shiksha_visitor',1);
        if(!empty($fromDate))
            $this->dbHandle->where("time BETWEEN '". $fromDate. "' and '". $toDate."'");
        $this->dbHandle->group_by('pixel_id');
        $this->dbHandle->order_by("cnt", "DESC");
        $result = $this->dbHandle->get()->result_array();
        foreach ($result as $key => $value) {
            $pixelWiseData[$value['pixel_id']]['landings']['registeredshikshaVisitors'] = $value['cnt'];
        }

        // 8. total form landings + non- shiksha visitors
        $this->dbHandle->select('distinct(pixel_id), count(*) as cnt');
        $this->dbHandle->from('OF_Conversion_Tracking');
        $this->dbHandle->where('action','landed');
        $this->dbHandle->where('is_shiksha_visitor',0);
        if(!empty($fromDate))
            $this->dbHandle->where("time BETWEEN '". $fromDate. "' and '". $toDate."'");
        $this->dbHandle->group_by('pixel_id');
        $this->dbHandle->order_by("cnt", "DESC");
        $result = $this->dbHandle->get()->result_array();
        foreach ($result as $key => $value) {
            $pixelWiseData[$value['pixel_id']]['landings']['nonShikshaVisitors'] = $value['cnt'];
        }

        // 9. total form submissions 
        $this->dbHandle->select('distinct(pixel_id), count(*) as cnt');
        $this->dbHandle->from('OF_Conversion_Tracking');
        $this->dbHandle->where('action','submitted');
        if(!empty($fromDate))
            $this->dbHandle->where("time BETWEEN '". $fromDate. "' and '". $toDate."'");
        $this->dbHandle->group_by('pixel_id');
        $this->dbHandle->order_by("cnt", "DESC");
        $result = $this->dbHandle->get()->result_array();
        foreach ($result as $key => $value) {
            $pixelWiseData[$value['pixel_id']]['submitted']['total'] = $value['cnt'];
        }

        // 10. total form submission + shiksha visitors + non-registered
        $this->dbHandle->select('distinct(pixel_id), count(*) as cnt');
        $this->dbHandle->from('OF_Conversion_Tracking');
        $this->dbHandle->where('action','submitted');
        $this->dbHandle->where('user_id',0);
        $this->dbHandle->where('is_shiksha_visitor',1);
        if(!empty($fromDate))
            $this->dbHandle->where("time BETWEEN '". $fromDate. "' and '". $toDate."'");
        $this->dbHandle->group_by('pixel_id');
        $this->dbHandle->order_by("cnt", "DESC");
        $result = $this->dbHandle->get()->result_array();
        foreach ($result as $key => $value) {
            $pixelWiseData[$value['pixel_id']]['submitted']['nonRegisteredshikshaVisitors'] = $value['cnt'];
        }

        // 11. total form submission + shiksha visitors + registered
        $this->dbHandle->select('distinct(pixel_id), count(*) as cnt');
        $this->dbHandle->from('OF_Conversion_Tracking');
        $this->dbHandle->where('action','submitted');
        $this->dbHandle->where("user_id !=", 0);
        $this->dbHandle->where('is_shiksha_visitor',1);
        if(!empty($fromDate))
            $this->dbHandle->where("time BETWEEN '". $fromDate. "' and '". $toDate."'");
        $this->dbHandle->group_by('pixel_id');
        $this->dbHandle->order_by("cnt", "DESC");
        $result = $this->dbHandle->get()->result_array();
        foreach ($result as $key => $value) {
            $pixelWiseData[$value['pixel_id']]['submitted']['registeredshikshaVisitors'] = $value['cnt'];
        }

        // 13. total form submission + non- shiksha visitors
        $this->dbHandle->select('distinct(pixel_id), count(*) as cnt');
        $this->dbHandle->from('OF_Conversion_Tracking');
        $this->dbHandle->where('action','submitted');
        $this->dbHandle->where('is_shiksha_visitor',0);
        if(!empty($fromDate))
            $this->dbHandle->where("time BETWEEN '". $fromDate. "' and '". $toDate."'");
        $this->dbHandle->group_by('pixel_id');
        $this->dbHandle->order_by("cnt", "DESC");
        $result = $this->dbHandle->get()->result_array();
        foreach ($result as $key => $value) {
            $pixelWiseData[$value['pixel_id']]['submitted']['nonShikshaVisitors'] = $value['cnt'];
        }

        return $pixelWiseData;
        // _p($pixelWiseData);die;
     }

     function getPBTPixelDetails($pixelIds){

        $result = array();
        if(empty($pixelIds))
            return $result;

        $this->initiateModel();

        $this->dbHandle->select('pixel_id, group_concat(course_id) as courses');
        $this->dbHandle->from('OF_ExternalForms');
        $this->dbHandle->where('status','live');
        $this->dbHandle->where_in('pixel_id',$pixelIds);
        $this->dbHandle->group_by('pixel_id');
        $result = $this->dbHandle->get()->result_array();
        $allCourses = array();
        $reversePixelCourseMapping = array();
        foreach ($result as $key => $value) {
            // $pixelWiseData[$value['pixel_id']]['courses'] = $value['courses'];
            $courses = explode(",", $value['courses']);
            $pixelWiseData[$value['pixel_id']]['courses'] = implode(", ", $courses);
            $reversePixelCourseMapping[$courses[0]] = $value['pixel_id'];
            $allCourses = array_merge($courses,$allCourses);
        }

        $sql = "select crs.course_id, inst.name from shiksha_institutes inst inner join shiksha_courses crs on(crs.primary_id = inst.listing_id and crs.status='live' and inst.status='live') and crs.course_id in (".implode(",", $allCourses).")";

        $result = $this->dbHandle->query($sql)->result_array();
        foreach ($result as $key => $value) {
            $pixelWiseData[$reversePixelCourseMapping[$value['course_id']]]['clientname'] = $value['name'];
        }

        return $pixelWiseData;

     }

     function getResponseDataOfCourses($courseIds , $time){

        $this->initiateModel();

        $sql = "select user.firstname, user.lastname, user.mobile, user.email, res.action, res.submit_date, res.listing_type_id  From tempLMSTable res inner join tuser user on(res.userId = user.userid) where listing_type='course' and submit_date>='".$time."' and res.listing_type_id IN (?) order by submit_date desc limit 1000";

        $result = $this->dbHandle->query($sql, array($courseIds))->result_array();

        return $result;
     }


    function getCoursesOfInstitutes($instituteIds){

        $result = array();
        if(empty($instituteIds)){
            return $result;
        }

        $this->initiateModel();

        $sql = "SELECT primary_id, course_id, name FROM shiksha_courses WHERE status='live' AND primary_id IN (?)";

        $rs = $this->db->query($sql, array($instituteIds))->result_array();
        foreach ($rs as $key => $value) {
            $result[$value['course_id']] = $value;
        }

        return $result;
    }

    function getInstituteDetails($instituteIds){

        $result = array();
        if(empty($instituteIds)){
            return $result;
        }

        $this->initiateModel();

        $sql = "select listing_id, name from shiksha_institutes where status='live' and listing_id in (?)";

        $rs = $this->db->query($sql, array($instituteIds))->result_array();
        foreach ($rs as $key => $value) {
            $result[$value['listing_id']] = $value;
        }

        return $result;
    }

    function getPageIdentifiersList($team){
        if($team ==""){
            return array();
        }

        $this->initiateModel("read");
        $this->dbHandle->select('distinct(page)');
        $this->dbHandle->from('tracking_pagekey');
        if($team == 'studyabroad'){
            $this->dbHandle->where("site","Study Abroad");
        }else if($team == 'domestic'){
            $this->dbHandle->where("site !=","Study Abroad");
        }
        
        $result = $this->dbHandle->get()->result_array();
        //echo  $this->dbHandle->last_query();die;
        return $result;
    }

    function getCourseListingsByClient($clientId){
        if(!is_numeric($clientId)){
            return array();
        }
        $this->initiateModel("read");
        $this->dbHandle->select("listing_type_id, listing_title, pack_type");
        $this->dbHandle->from("listings_main");
        $this->dbHandle->where("username", $clientId);
        $this->dbHandle->where("listing_type", "course");
        $this->dbHandle->where("status", "live");
        $this->dbHandle->order_by("listing_title", "asc");
        $result = $this->dbHandle->get()->result_array();
        // echo $this->dbHandle->last_query();
        return $result;
    }

    function getUserDetails($userIds){
        if(is_array($userIds) && count($userIds)>0 ){
            $this->initiateModel("read");
            $this->dbHandle->select("userId, email, displayname");
            $this->dbHandle->from("tuser");
            $this->dbHandle->where_in("userId", $userIds);
            $result = $this->dbHandle->get()->result_array();
            return $result;
        }
        return array();
    }

    function getPageGroupPageList($pageGroupList, $site = "DOMESTIC"){
        //var_dump($pageGroupList);die;
        if(is_array($pageGroupList)){
            $this->initiateModel("read");
            $this->dbHandle->select("pageGroup, page");
            $this->dbHandle->from("tracking_pagekey");

            if(count($pageGroupList) >0 && $pageGroupList[0] != "all"){
                $this->dbHandle->where_in("pageGroup", $pageGroupList);
            }

            if($site == "DOMESTIC"){
                $this->dbHandle->where("site", "Domestic");
            }else{
                $this->dbHandle->where("site", "Study Abroad");
            }
            $this->dbHandle->group_by("pageGroup,page");
            $result = $this->dbHandle->get()->result_array();
            //echo $this->dbHandle->last_query();die;
            return $result;
        }
        return array();   
    }

    function getShikshaPageGroupsList($site){
        if($site ==""){
            return array();
        }

        $this->initiateModel("read");
        $this->dbHandle->select('distinct(pageGroup)');
        $this->dbHandle->from('tracking_pagekey');
        if($site == "DOMESTIC"){
            $this->dbHandle->where("site", "Domestic");
        }else{
            $this->dbHandle->where("site", "Study Abroad");
        }
        
        $result = $this->dbHandle->get()->result_array();
        //echo  $this->dbHandle->last_query();die;
        return $result;   
    }

    function getUserDetailsByEmail($email =""){
        if(empty($email)){
            return;
        }
        $this->initiateModel("read");
        $this->dbHandle->select("userId, email");
        $this->dbHandle->from("tuser");
        $this->dbHandle->where_in("email", $email);
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }
}
