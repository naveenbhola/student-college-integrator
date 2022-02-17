<?php
require_once('vendor/autoload.php');
class ESConnectionLib {
    private $clientParams;
    public function getESServerConnection() {
        $this->clientParams = array();
        $this->clientParams['hosts'] = array(ELASTIC_SEARCH_HOST); //shikshaConfig
        return new Elasticsearch\Client($this->clientParams);
    }

    public function getShikshaESServerConnection($timeOutRequired = false, $timeout = 5) {
        $this->clientParams = array();
        $this->clientParams['hosts'] = array(SHIKSHA_ELASTIC_HOST); //shikshaConfig

        if($timeOutRequired == true){
            $this->clientParams['guzzleOptions']['curl.options'][CURLOPT_CONNECTTIMEOUT] = $timeout;
            $this->clientParams['guzzleOptions']['curl.options'][CURLOPT_TIMEOUT] = $timeout;
        }
        return new Elasticsearch\Client($this->clientParams);
    }

    public function getESServerConnectionWithCredentials($timeOutRequired = true) {
        $this->clientParams = array();
        $elastic_host       = ELASTIC_LDB_MONITORING_HOST;
        $elastic_port       = ELASTIC_LDB_MONITORING_PORT;
        $elastic_username   = 'elastic';    //read through config later
        $elastic_password   = 'changeme';
        $elastic_url = 'http://'.$elastic_username.':'.$elastic_password.'@'.$elastic_host.':'.$elastic_port;

        if($timeOutRequired == true){
            $this->clientParams['guzzleOptions']['curl.options'][CURLOPT_CONNECTTIMEOUT] = 5;
            $this->clientParams['guzzleOptions']['curl.options'][CURLOPT_TIMEOUT] = 5;
        }

        $this->clientParams['hosts'] = array($elastic_url); //after x pack
        return new Elasticsearch\Client($this->clientParams);
    }
}
?>
