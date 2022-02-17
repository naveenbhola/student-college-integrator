<?php 
class LogLikeliHoodAnalysis{
    private $CI;
    
    public function __construct() {
        $this->CI = &get_instance();
    }
    
    /*
     * Purpose      : To generate logliklihood analysed data based on input parameter
     * inputArray   : associative array
     *                  1. 'dataInputSource'    : function name to get data for analysis. This function to be placed within this library
     *                  2. 'dataOutputSource'   : function name to submit the analysed data. This function to be also placed within this library
     * Constraints  : 1. dataInputSource should provide associative array of unique entityId to unique users array
     *                  Ex. Array =>    'data' =>  Array(   236 => array(12,13,14,15),
     *                                                      237 => array(16,17,18,19),
     *                                                      240 => array(20,24,56,78)),
     *                                  'uniqueCombination' => 12
     *                2. dataOutputSource will be passed associative array of each entity mapped to other entity with scores
     *                  Ex. Array(  236 => array( 237 => 0.98,
     *                                            240 => 0.56),
     *                              237 => array( 236 => 0.67,
     *                                            240 => 0.34),
     *                              240 => array( 237 => 0.45,
     *                                            236 => 0.39))
     */
    public function performLogLikeliHoodAnalysis($inputArray = array()) {
        if(!isset($inputArray['dataInputSource']) || !($inputArray['dataOutputSource']) || !method_exists($this, $inputArray['dataInputSource']) || !method_exists($this, $inputArray['dataOutputSource'])){
            return array();
        }
        $dataForAnalysis = $this->$inputArray['dataInputSource']();
        
        $this->CI->load->library('common/recommendationAlgorithms/LogLikelihoodSimilarity');
        $logLikliHoodObj = new LogLikelihoodSimilarity();
        //$pairDone = array();
        $scores = array();
        $counter = 0;
        foreach ($dataForAnalysis['data'] as $parentEntityId=>$parentUserSet){
            $scores[$parentEntityId] = array();
            foreach ($dataForAnalysis['data'] as $childEntityId=>$childUserSet){
                if($parentEntityId != $childEntityId /*&& !$pairDone[$parentEntityId.':'.$childEntityId]*/){
                    $similarityScore = $logLikliHoodObj->similarity($parentUserSet, $childUserSet, $dataForAnalysis['uniqueCombination']);
                    if($similarityScore > 0){
                        $scores[$parentEntityId][$childEntityId] = $similarityScore;
                    }
                    //error_log(++$counter.'Score calculation done for '.$parentEntityId.':'.$childEntityId.' => '.$scores[$parentEntityId][$childEntityId].' : '.date('H:i:s').PHP_EOL,3,'/tmp/logliklihood.txt');
                    //$pairDone[$parentEntityId.':'.$childEntityId] = TRUE;
                    /*$similarityScore = $logLikliHoodObj->similarity($childUserSet, $parentUserSet, $dataForAnalysis['uniqueCombination']);
                    if($similarityScore > 0){
                        $scores[$childEntityId][$parentEntityId] = $similarityScore;
                        error_log(++$counter.'Score calculation done for '.$childEntityId.':'.$parentEntityId.' => '.$scores[$childEntityId][$parentEntityId].' : '.date('H:i:s').PHP_EOL,3,'/tmp/logliklihood.txt');
                    }*/
                    //$pairDone[$childEntityId.':'.$parentEntityId] = TRUE;
                }
                //error_log('Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/logliklihood.txt');
            }
            //unset($dataForAnalysis['data'][$parentEntityId]);
        }
        unset($dataForAnalysis['data']);
        // pass analysed data to dataOutputSource function
        $this->$inputArray['dataOutputSource']($scores);
        return;
    }
    
    private function getUniversityToUserData(){
        $abroadListingCronLibrary = $this->CI->load->library('listing/AbroadListingCronLibrary');
        $result = $abroadListingCronLibrary->getUniversityToUserData();
        return $result;
    }
    
    private function setUniversityLogLikeliHoodAnalysisData($param) {
        $abroadListingCronLibrary = $this->CI->load->library('listing/AbroadListingCronLibrary');
        $result = $abroadListingCronLibrary->setUniversityLogLikeliHoodAnalysisData($param);
        //error_log('Memory Usage '.((memory_get_usage(TRUE)/(1024*1024))).' : '.date('H:i:s').PHP_EOL,3,'/tmp/logliklihood.txt');
        return;
    }
}
?>

