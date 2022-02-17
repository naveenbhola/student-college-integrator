<?php


class AutoAnswer extends MX_Controller {


    public function __construct(){
        $this->autoAnswerLib = $this->load->library('autoAnswer/AutoAnsweringLib');
        $this->load->config('autoAnswer/AutoAnsweringConfig');
        $this->autoAnsweringModel = $this->load->model('autoAnswer/AutoAnsweringModel');
    }


 // Bing Spell Check API
 public function bingAutoCorrectSpell(){
    $url = "https://bingapis.azure-api.net/api/v5/spellcheck?mode=spell";
    $str = $this->input->post('sentence');
    $str = trim($this->autoAnswerLib->cleanSentence($str));

    $str .= " ";
    $data['Text'] = $str;
    $customCurlData['headers'] = array('Ocp-Apim-Subscription-Key: f4e7bd7c2c9f452bb3d84db26d32b927');
    $customCurlData['postData'] = $data;
    $retValue = $this->autoAnswerLib->sendCurlRequest($url);
    $retValue = json_decode($retValue);
    
    $replaceArray = array();
    $flaggedData = $retValue->flaggedTokens;
    
    if(!empty($flaggedData)){
        foreach ($flaggedData as $key => $value) {

            $suggestions = $value->suggestions;
            $offset = $value->offset;

            if(!empty($suggestions)){
                foreach ($suggestions as $key1 => $value1) {
                    $replaceArray[$offset] = $value1->suggestion;
                    break;  // for 1st suggestion only
                }
            }
        }
    }
    
    $replaceArray = array_reverse($replaceArray,true);

    foreach ($replaceArray as $key => $value) {
        $index = strpos($str, " ", $key);
        $len = $index - $key;

        if($len == 0){
            $key++;
            $len = 1;
        }
        $str = substr_replace($str, $value, $key,$len);
    }
    echo $str;
 }

 // Fetch Data for sentencce
 public function fetchDataForSentences(){

    $sentenceArray = $_POST['sentences'];
    $sentenceArray = $this->input->post('sentences');
    $result = array();
    $i = 0;
    foreach ($sentenceArray as $sentence) {
        $result[$i] = array();
        $result[$i]['sentence'] = $sentence;
        $result[$i]['type'] = $this->autoAnswerLib->detectObjOrBack($sentence);   // objective / background
        $x = $this->autoAnswerLib->matchListingAttributes($sentence);
        $y = $this->autoAnswerLib->_sendTagsCURLRequest($sentence);
        if(empty($x))
        {
          $result[$i]['attributes'] = "";
        }else {
          $result[$i]['attributes'] = $x;  
        }

        if(empty($y))
        {
          $result[$i]['tags'] = "";
        }else {
          $z = array();
          foreach ($y as $key => $value) {
            $z[$value] = 1;
          }
          $result[$i]['tags'] = json_encode($z);  
        }
        
        $i++;
    }

    echo json_encode($result);
 }

 public function fetchSolrDataForSentence(){

    $tagsString = $this->input->post('tagsString');
    $attr = $this->input->post('attr');
    error_log("=============================== here=========================");
    $demoTagToCategoryMapping = $this->config->item('TAG_CATEGORY_MAPPING');
    $demoSpecMapping = $this->config->item('TAG_SPECIALIZATION_MAPPING');

    $tagsArray = explode(",", $tagsString);
    $tagsInfo = $this->autoAnsweringModel->fetchTagsInfo($tagsArray);
    $tagsData = array();
    
    foreach ($tagsInfo as $key => $value) {
        $tagsData[] = $value; 
    }

    $listingTagArray = $this->autoAnswerLib->fetchListingTag($tagsData);


    $instituteTagArray = $listingTagArray;
    

    $coursesTagArray = $this->autoAnswerLib->fetchListingTag($tagsData, "Course");
    $specializationTagArray = $this->autoAnswerLib->fetchListingTag($tagsData, "Specialization");
    $matchedCategoryArray = array();
    $matchedSpecializationArray = array();
    
    $solrQuery = "http://172.16.2.222:8984/solr/collection1/select?q=*%3A*&wt=json";
    if(!empty($listingTagArray)){

        $result = $this->autoAnsweringModel->fetchTagsForListing($listingTagArray);

        foreach ($result as $key => $value) {
            $listingTagArray[$value['tag_id']] = $value['entity_id'];
        }

        $solrQuery .= "&fq=aa_entity_id:(".implode(" ", $listingTagArray).")";
        $solrQuery .= "&fq=aa_entity_type:institute";
        // Now since listing tag has been identified, Detect the sub category(23 or 56 -- something like this) & spec
        foreach ($tagsData as $key => $value) {

            if(array_key_exists($value['id'], $demoTagToCategoryMapping)){
                $temp = $demoTagToCategoryMapping[$value['id']];
                if(is_array($temp)){
                    foreach ($temp as $cat) {
                        $matchedCategoryArray[] = $cat;
                    }
                }else {
                    $matchedCategoryArray[] = $demoTagToCategoryMapping[$value['id']];  
                }
                unset($temp);
                //$matchedCategoryArray[] = $demoTagToCategoryMapping[$value['id']];
            }


            if(array_key_exists($value['id'], $demoSpecMapping)){
                $temp = $demoSpecMapping[$value['id']];
                if(is_array($temp)){
                    foreach ($temp as $cat) {
                        $matchedSpecializationArray[] = $cat;
                    }
                }else {
                    $matchedSpecializationArray[] = $demoSpecMapping[$value['id']]; 
                }
                unset($temp);
                //$matchedSpecializationArray[] = $demoSpecMapping[$value['id']];
            }
        }

        
        // category 
        if(!empty($matchedCategoryArray)){
            $matchedCategoryArray = array_unique($matchedCategoryArray);
            error_log("category tags found == ".implode(",", $matchedCategoryArray));
            $solrQuery .= "&fq=aa_subcategory_id:(".urlencode(implode(" ", $matchedCategoryArray)).")";
        }else {
            error_log("category tags NOT found");
        }

        // spec
        if(!empty($matchedSpecializationArray)){
            error_log("specialization tags found == ".implode(",", $matchedSpecializationArray));
            $solrQuery .= "&fq=aa_specialization_id:(".urlencode(implode(" ", $matchedSpecializationArray)).")";
        }else {
            error_log("specialization tags NOT found");
        }

        if(!empty($attr)){
            $attrArray = explode(",", $attr);
            error_log("Attr found == ".implode(",", $attrArray));
            $solrQuery .= "&fq=aa_attribute_name:(".urlencode(implode(" ", $attrArray)).")";
        }else {
            error_log("Attr NOT found");
        }

        $data = array();
        $data['specialization'] = $specializationTagArray;
        $data['attributes'] = $attrArray;
        $data['institutes'] = $instituteTagArray;
        $data['courses'] = $coursesTagArray;
        $data['instituteTagMap'] = $listingTagArray;
        $this->autoAnswerLib->makeAndRunSolrQuery($solrQuery, $data);
        

    }

 }

 public function test(){
    $this->bingAutoCorrectSpell();  
 }

 function test1(){
    $this->load->builder("AutoAnswerBuilder", "autoAnswer");
    // $autoAnswerBuilder = new AutoAnswerBuilder(array("text"=>"I want to know the fees of IIT Delhi"));
    // $autoAnswerBuilder = new AutoAnswerBuilder(array("text"=>"hey what is the fees of college"));
    // $autoAnswerBuilder = new AutoAnswerBuilder(array("text"=>"can you please tell me as how much fees is required for getting admission in IIT D"));
    $autoAnswerBuilder = new AutoAnswerBuilder(array("text"=>"what are the top btech colleges in delhi"));
    
    $autoAnswer = $autoAnswerBuilder->getAutoAnswerBot();
    $response = $autoAnswer->getReply();
    _p("Reply : ".$response);
 }
            
}
?>
