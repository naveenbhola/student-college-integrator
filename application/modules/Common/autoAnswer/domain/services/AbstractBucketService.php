<?php

class AbstractBucketService{

	private $request;

	function __construct(){
		
	}

	function findPattern($bucketsConfig, $request){

		$text = $request->getRequestText();
		$text = $this->sanitizeText($text);

		foreach ($bucketsConfig as $key=>$pattern) {
			
			$textToBeMatched = $this->sanitizeText($pattern['pattern']);

			// _p("Matching : ".$pattern['type']." === ".$textToBeMatched." ==== ".$text);
			if($pattern['type'] == 'static' && $textToBeMatched == $text){
				return $key;
			}
			else{
				$textToBeMatched = "/^".$textToBeMatched."/";
				
				if(strpos($pattern['pattern'], "{*}") !== false){
					$textToBeMatched = str_replace("{*}", "[\w\W]*", $textToBeMatched);
					// _p("Regex Stage1 : ".$textToBeMatched." ==== ".$text);
					if(preg_match($textToBeMatched, $text, $matches)){
						return $key;
					}
				}

				// for {*,n}
				if(preg_match("/[\{][\*][,][\d][}]/", $textToBeMatched, $matchedPos)){
					$wordCount = preg_replace("/[^0-9]/","",$matchedPos);
					$wordCount = $wordCount[0]-1;

					$replaceRegEx = "";
					if($wordCount > 1)
						$replaceRegEx = "([\S]+[\s][\S]+){".$wordCount."}";
					else
						$replaceRegEx = "[\S]+";

					$textToBeMatched = preg_replace("/[\{][\*][,][\d][}]/", $replaceRegEx, $textToBeMatched);
					// _p("Regex Stage2: ".$textToBeMatched." ==== ".$text." === ".$pattern['pattern']);
					if(preg_match($textToBeMatched, $text, $matches)){
						return $key;
					}
				}
				// for {*,n,m}
				if(preg_match("/[\{][\*][,][\d][,][\d][}]/", $textToBeMatched, $matchedPos)){
					// _p($textToBeMatched);
					$wordCount = preg_replace("/[^,0-9]/","",$matchedPos);
					// _p($wordCount);
					$wordCount = explode(",", $wordCount[0]);
					$n = $wordCount[1];
					$m = $wordCount[2];

					$replaceRegEx = "";
					if($wordCount > 1){
						// $replaceRegEx = "([\S]+[\s][\S]+){".$n.",".$m."}";
						$replaceRegEx = "[ ]?([\s]?[\S]+[\s]?){".$n.",".$m."}[ ]?";
					}
					else
						$replaceRegEx = "[\S]+";

					$patternRegex = preg_replace("/[\{][\*][,][\d][,][\d][}]/", $replaceRegEx, $textToBeMatched);
					// _p("Regex Stage3: ".$patternRegex." ==== ".$text." === ".$pattern['pattern']);
					// _p("Regex : ".$patternRegex." ==== ".$text." === ".$patternRegex);
					if(preg_match($patternRegex, $text, $matches)){
						return $key;
					}
					
				}

				// last check : exact match
				if(preg_match($textToBeMatched, $text, $matches)){
						return $key;
				}
				
			}
		}

		return false;
	}

	function sanitizeText($text){

		$text = strtolower($text);

		return $text;
	}

	function contains($str, array $arr)
	{
	    foreach($arr as $a) {
	        if (stripos($str,$a) !== false) return true;
	    }
	    return false;
	}
}
?>