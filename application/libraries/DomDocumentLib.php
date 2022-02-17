<?php
	/**
	 * this library is used for parse on dynamic html content like javascript/jquery
	 * author : Nithish Reddy
	 */
	class DomDocumentLib
	{
		
		function __construct()
		{
			$this->CI = & get_instance();
		}

		/***
		* below function is used for fetching tags from html string for TOC content
		* @param $htmlString
		* @param $uniqIdentifier
		* @param $fetchTag
		* @param $exceptParentTag
		* @param $isFullHtml
		*
		*/
		function getTagsInDynamicHtmlContent($htmlString,$uniqIdentifier,$fetchTag,$exceptParentTag,$isFullHtml) {

			if(empty($fetchTag)){
				return array('html' => $htmlString,'htmlModified' => false);
			}

			if(!is_bool($isFullHtml)){
				$isFullHtml = false;
			}

			if(empty($uniqIdentifier)){
				$uniqIdentifier = "eleid";
			}else{
				$uniqIdentifier = strtolower($uniqIdentifier);
            	$uniqIdentifier = trim(str_replace(' ', '', $uniqIdentifier));
			}

			if(strlen($uniqIdentifier) > 20){
				$uniqIdentifier = hash("md5", $uniqIdentifier);
			}

			$htmlString = html_entity_decode($htmlString);
			$doc = new DOMDocument();
			$doc->loadHTML(mb_convert_encoding($htmlString, 'HTML-ENTITIES', 'UTF-8'));

			if(is_array($fetchTag) && !empty($fetchTag)){
			    $exceptParentTagQueryString = '';
			    if (!empty($exceptParentTag)){
                    $exceptParentTagQueryString = '[not(ancestor::'.$exceptParentTag.')]';
                }
                $xpathQueryString = "";
                foreach ($fetchTag as $fetchTagValue){
                    $xpathQueryString .= " //".$fetchTagValue.$exceptParentTagQueryString." |";
                }
                $xpathQueryString = substr($xpathQueryString, 0, -1);
                $xpath = new DOMXPath($doc);
                $tags = $xpath->query($xpathQueryString);
            }elseif(!empty($exceptParentTag)){
				$xpath = new DOMXPath($doc);
				$tags = $xpath->query('//'.$fetchTag.'[not(ancestor::'.$exceptParentTag.')]');
			}else{
			    $tags = $doc->getElementsByTagName($fetchTag);
			}
			$tagsCount = 0;
			$tocContent = "";
			//$ampTocContent = "";
			foreach ($tags as $node) {
				$h2Text = $node->nodeValue;
				$h2Text = htmlentities($h2Text);
                $h2Text = str_replace("&nbsp;", "", $h2Text);
				if(empty($h2Text)){
					$node->setAttribute('id',"");
					continue;
				}
				$identifier = $uniqIdentifier.'_toc_'.$tagsCount++;
			    $node->setAttribute('id',$identifier);
			    $tocContent .= "<p class = 'toc' data-scrol='".$identifier."' on='tap:".$identifier.".scrollTo(duration=200)'>".$node->nodeValue."</p>";
			  //  $ampTocContent .= '<a id="discusn" on="tap:'.$identifier.'.scrollTo(duration=200)">'.$node->nodeValue.'</a>';
			}
			if($isFullHtml) {
				$html = $doc->saveHTML();
			}
			else {
				$html =	preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $doc->saveHTML()));	
			}
			$result = array();
			$result['tocContent'] = $tocContent;
			$result['html'] = $html;
			$result['htmlModified'] = ($tags->length == 0) ? false : true;
			return $result;
		}
	}
?>