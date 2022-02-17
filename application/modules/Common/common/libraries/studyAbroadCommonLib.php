<?php

class studyAbroadCommonLib{
    private $CI;
    public function __construct() {
        $this->CI = &get_instance();
    }
     /*
     * Purpose : To send an email to the study abroad team at various times.
     * Params:
     * $subject: This is the subject of the email
     * $message: This is the html of the message to be sent to the team.
     * $cc :self explanatory
     * $bcc : self explanatory
     */
    public function selfMailer($subject="Cron Alert", $message="Hi,<br/><br/>Cron was Started/Ended Successfully.<br/><br/>Regards,<br/>SA Team", $cc="jahangeer.alam@shiksha.com", $bcc="geetu.sadana@shiksha.com"){
        $emailId = 'satech@shiksha.com';
        $alertsClient = $this->CI->load->library('alerts_client');
        $alertsClient->externalQueueAdd("12", SA_ADMIN_EMAIL, $emailId, $subject, $message, "html", '', 'n', array(), $cc, $bcc);
    }

    public function getUserLastVisitTime($userId,$site='shiksha'){
        if(!is_numeric($userId) && !($userId>0)){
            return false;
        }

        if(!(in_array($site, array('studyAbroad','national','shiksha')))){
            return false;
        }

        $isStudyAbroad ='';
        if($site == 'studyAbroad'){
            $isStudyAbroad = 'yes';
        }else if($site == 'national'){
            $isStudyAbroad = 'no';
        }

        $this->MISCommonLib = $this->CI->load->library('trackingMIS/MISCommonLib');
        $visitorSessionId = getVisitorSessionId();

        $elasticQuery = array();
        $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->clientCon = $ESConnectionLib->getShikshaESServerConnection();
        $elasticQuery['index'] = PAGEVIEW_INDEX_NAME;
        $elasticQuery['type'] = 'pageview';
        $elasticQuery['body']['size'] = 1;
        $elasticQuery['body']['_source'] = array("visitTime");
        $mustFilter= array();
        $mustFilter[] = array(
            'term' => array(
                'userId' => $userId
                )
            );

        if(!empty($isStudyAbroad)){
            $mustFilter[] = array(
                'term' => array(
                    'isStudyAbroad' => $isStudyAbroad
                    )
                );
        }

        $mustNotFilter = array(
            'term' => array(
                'sessionId' => $visitorSessionId
                )
            );

        $elasticQuery['body']['query']['bool']['filter']['bool'] = array(
            'must' => $mustFilter,
            'must_not'  => $mustNotFilter
            );
        $elasticQuery['body']['sort']['visitTime']['order'] = 'desc';
        $result = $this->clientCon->search($elasticQuery);
        if(empty($result['hits']['hits'])){
            return '';
        }else{
            $lastVisitTime = $result['hits']['hits'][0]['_source']['visitTime'];
            $lastVisitTime = str_replace('T', ' ', $lastVisitTime);
            $lastVisitTime = convertDateUTCtoIST($lastVisitTime);
            return $lastVisitTime;
        }
    }

    public function getCurrenncyRateDetails($sourceId, $destId){
        $this->CI->load->library('listing/cache/AbroadListingCache');
        $abroadListingCacheLib  = new AbroadListingCache();
        $currencyExchangeData   = $abroadListingCacheLib->getCurrenncyRateDetails($sourceId, $destId);
        if(!empty($currencyExchangeData)){
            return $currencyExchangeData;
        }else{
            $modelObj = $this->CI->load->model('listing/abroadlistingmodel');
            $currencyExchangeData = $modelObj->getCurrenncyRateDetails($sourceId, $destId);
            $abroadListingCacheLib->storeCurrenncyRateDetails($sourceId, $destId, $currencyExchangeData);
            return $currencyExchangeData;
        }
    }

    public function getCities($countryId,$include_virtual){
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder                                = new LocationBuilder;
        $this->locationRepository                       = $locationBuilder->getLocationRepository();
        $citiesData = $this->locationRepository->getCities($countryId,$include_virtual);
        $cities = array();
        foreach($citiesData as $key=>$cityObj){
            $cities[$key]['city_id'] = $cityObj->getId();
            $cities[$key]['city_name'] = $cityObj->getName();
        }
        $cityName = array();
        foreach ($cities as $key => $row)
        {
            $cityName[$key] = $row['city_name'];
        }
        array_multisort($cityName, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $cities);

        //add other city option at the end with id -1
        end($cities);
        $lastIdx = key($cities)+1;
        $cities[$lastIdx] = array('city_id'=>-1,'city_name'=>'Other City');
        reset($cities);
        return $cities;
    }
}
?>
