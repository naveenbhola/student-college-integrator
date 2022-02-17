<?php

class AutoAnsweringLib
{
	
	function __construct(){
		$this->CI=& get_instance();
		$this->serverIP = "127.0.0.1";
		$this->serverPort = "8080";
		$this->qer = "http://172.16.3.248:8986/";
		$this->tagUrl = "http://172.16.3.107:8986/";
		$this->CI->load->config('AutoAnsweringConfig');
	}

	function sendCurlRequest($url,$customData=array()){

		$headers = array();
		$postData = array();

		if(isset($customData['headers'])){
			foreach ($customData['headers'] as $key => $value) {
				$headers[$key] = $value;
			}
		}

		if(isset($customData['postData'])){
			foreach ($customData['postData'] as $key => $value) {
				$postData[$key] = $value;
			}
		}
		
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_FAILONERROR,1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

	    // Add Headers in Request
	    if(!empty($headers)){
	    	curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
	    }

	    // Add Post Data to Request(if any)
	    if(!empty($postData)){
	      $field_string = http_build_query($postData);
	      curl_setopt($ch, CURLOPT_POST, 1);
	      curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
	    }

	    $result = curl_exec($ch);
	    return $result;

	}


	function cleanSentence($str){
	    $url = "http://".$this->serverIP.":".$this->serverPort."/CleanDemo/clean";
		$data['sentence'] = $str;
		$customData['postData'] = $data;
		$result = $this->sendCurlRequest($url,$customData);
		return $result;
	}

	function detectObjOrBack($question){

	    $qerUrl = $this->qer."query_entity_recognition/quiet";
    	$url = $qerUrl."?inkeyword=".urlencode($question)."&action=Submit&output=xmlcand";
	    $sXML = $this->sendCurlRequest($url);
	    $xml1 = json_decode(json_encode(simplexml_load_string($sXML, null, LIBXML_NOCDATA)), true);
	    $objective = $xml1['query_objective_split'];
	    $background = $xml1['query_background_split'];
	    if(trim($objective) != ""){
	      return "objective";
	    } else {
	      return "background";
	    }
  	}


	function listOfAttributes(){
		$f = file_get_contents("/var/www/objectibe/institute_attributes.txt");
		$f = explode("\n", $f);
		foreach ($f as $row) {
			$attributesData = explode("\t",$row);
			$mainAttr = $attributesData[0];
			$matchAttr = $attributesData[1];
			$attrMap[$mainAttr][] = $this->clean_string($matchAttr);
		}

		foreach ($attrMap as $key => $value) {
			$attrMap[$key] = array_values(array_unique($value));
		}

		return $attrMap;
	}

	function clean_string($str = "",$less = false){

		$clean_str = $str;
		if($less == false){
	    	$clean_str = strtolower($str);	
			$clean_str = preg_replace('/[^A-Za-z0-9 ]/', '', $clean_str);
		}
		$wordsArr = explode(" ", $clean_str);
		$clean_str = implode(" ", $wordsArr);

		return $clean_str;
	}

	function matchListingAttributes($input){
	  
	  $input = trim($this->clean_string($input));
	  $input = " ".$input." ";
	  $attrList = $this->listOfAttributes();
	  $result = array();
	  foreach ($attrList as $mainAttr => $attrArray) {
	      foreach ($attrArray as $key => $value) {
	          if((stripos($input, " ".$value." ")!== false)){
	              if(array_key_exists($mainAttr, $result)) {
	              	$result[$mainAttr]++;
	              }else {
	              	$result[$mainAttr] = 1;
	              }
	           }
	       else if((stripos($input, " ".$value.".")!== false)){
	          if(array_key_exists($mainAttr, $result)) {
	          	$result[$mainAttr]++;
	          }else {
	          	$result[$mainAttr] = 1;
	          }
	       }
	      } 
	  }
	  arsort($result);
	  return $result;
	}

	function _sendTagsCURLRequest($question){

	    $urlFields['sentence'] = urlencode($question);
	    
	    $url = $this->tagUrl."/Tagging/tags";
	    $fields_string = "";
	    $customData['postData'] = $urlFields;
	    $result = $this->sendCurlRequest($url,$customData);
	    $result = str_replace("<br />", "\n", $result);
	    $result = trim($result);
	    $tags = explode(",", $result);
	    $tags = array_values(array_unique(array_filter($tags)));
	    return $tags;
	  }

	  function fetchListingTag($tagsArray, $type="Colleges"){
		$result = array();
		foreach ($tagsArray as $value) {
			if($value['tag_entity'] == $type){
				$result[$value['id']] = $value['tags'];
			}

		}
		return $result;
	}

	function makeAndRunSolrQuery($url, $data=array()){

      error_log("==".$url);
      //$url = "http://172.16.2.222:8984/solr/collection1/select?q=*%3A*&wt=json&fq=aa_entity_id:(3050)&fq=aa_entity_type:institute&fq=aa_subcategory_id:(23)&fq=aa_attribute_name:(fees)";

      $finalAnswer = "";
      $sXML = $this->sendCurlRequest($url);
      
    
      $x = json_decode($sXML);
      $y = $x->response;
      $docs = $y->docs;
      $configAttributeNames = $this->CI->config->item('LISTING_ATTR');
      $resultAnswer = "";
      if(!empty($docs)){

        $newDocsList = array();
        foreach ($docs as $key => $value) {
            $newDocsList[$value->aa_attribute_name][] = $value;
        }

        foreach ($newDocsList as $key => $newDocsListElement) {
          
          $finalAnswer = "";
        foreach ($newDocsListElement as $key => $value) {
          // $finalAnswer .=  "<b>".$value->aa_attribute_name."</b> in ".$value->aa_entity_name." for ".$value->aa_course_name."(".$value->aa_specialization_name.") is <b>".$value->aa_attribute_value."</b><br /><br />";
          $finalAnswer .=  "<tr><td>".$configAttributeNames[$value->aa_attribute_name]."</td> <td>".$value->aa_entity_name."</td> <td>".$value->aa_course_name."</td><td>".$value->aa_specialization_name."</td> <td>".$value->aa_attribute_value."</td></tr>";
        }

        if(!empty($finalAnswer)){
          $finalAnswer = "<table border='1'><tr>
          <th>Attribute Name</th>
          <th>Institute</th>
          <th>Course</th>
          <th>Specialization</th>
          <th>Value</th>
          </tr>".$finalAnswer."</table>";
        }

        $resultAnswer = $resultAnswer."<br/>".$finalAnswer;
        }
        
      }else {
        $str = implode(", ", $data['institutes']);
        if($data['courses']){
          $str = $str." ".implode(", ", $data['courses']);
        }
        if($data['specialization']){
          $str = $str. " in ". implode(", ", $data['specialization']);
        }

        $attrNames = array();
        foreach ($data['attributes'] as $value) {
          $attrNames[] = $configAttributeNames[$value];
        }
        $resultAnswer=  "Sorry, we <b>don't</b> have the data for <b>".implode(", ", $attrNames)."</b> of ".$str;
      }
      $institutesURL = "<br />";
      foreach ($data['institutes'] as $key => $value) {
        $institutesURL = $institutesURL."For more information, please visit this url <a target='_blank' href='http://www.shiksha.com/getListingDetail/".$data['instituteTagMap'][$key]."/institute'>".$value."</a><br />";
      }
      echo $resultAnswer.$institutesURL;

  }

  function getStreamLevelTag($tagData){

        $tagType = "Stream";
        $finalTag = array();
        $tagsModel = $this->CI->load->model("v1/tagsmodel");
        
        $resultData = array();
        foreach ($tagData as $tagIdToCheck) {
        	
	        $tags = array("id" => $tagIdToCheck);
	        foreach ($tags as $key => $value) {
	        	if($value['tag_entity'] == $tagType){
	        		$resultData[] = array("tagId" => $value['id'], "tagName" => $value['tags'], "seedTag" => $tagIdToCheck);
	        		continue;
	        	}
	        }

	        // Stage 2 : check for the stream tag in parents
	        $tagList = array();
	        error_log("--------- : ".print_r($tags, true));
	        foreach($tags as $value) {
	                $tagList[] = $value['id'];
	        }
	        $parentTags = $tagsModel->getTagsParent($tagList);
	        error_log("===========STage 2 : ".print_r($parentTags, true));
	        foreach ($parentTags as $value) {
	            if($value['tag_entity'] == $tagType){
	                $resultData[] = array("tagId" => $value['tag_id'], "tagName" => $value['tags'], "seedTag" => $tagIdToCheck);
	                continue;
	            }
	        }

	        // Stage 3 : check for stream tag in grandparents
	        $tagList = array();
	        foreach($parentTags as $value) {
	            $tagList[] = $value['tag_id'];
	        }
	        $parentTags = $tagsModel->getTagsParent($tagList);
	        error_log("===========STage 3 : ".print_r($parentTags, true));
	        foreach ($parentTags as $value) {
	            if($value['tag_entity'] == $tagType){
	                $resultData[] = array("tagId" => $value['tag_id'], "tagName" => $value['tags'], "seedTag" => $tagIdToCheck);
	                continue;
	            }
	        }
    	}

    	return $resultData;
    }

}
?>
