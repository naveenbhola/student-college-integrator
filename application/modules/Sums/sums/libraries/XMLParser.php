<?php
class XMLParser
{
	private $arrXmlData = array(); ///<<< going forward rename this to arrTempData 
	private $strXML ="";
	private $arrFinalDataToWrite =array();
	public $arrXmlErr = array();
	public $arrNonXmlErr = array();
	public $arrDocInfo=array();
	public $reqType='';
	public $xmlCommitName ='commitpoint';
	private $ARR_REQUEST_TYPES =array('insert'=>1, 'modify'=>1,'delete'=>1,'synchronized'=>1);
	
	public function parseXML($strXml)
	{
		libxml_use_internal_errors(true);
		$domDoc = new DOMDocument;	
		$domDoc->loadXML($strXml);
		$this->strXML = $strXml;
		//print_r($this);
		if($this->setXMLErrors())
		{
			return array();	
		}
		$xmlRootNode = $domDoc->documentElement; //This is a convenience attribute that allows direct access to the child node that is the document element of the document.
		$this->getDocInfo($domDoc->getElementsByTagName('WSRequest')->item(0));//Doc Node //This function returns a new instance of class DOMNodeList containing the elements with a given tag name
		// New Code To Support New XML Format 
		$arrCommitNodes = $domDoc->getElementsByTagName($this->xmlCommitName);
		if(count($arrCommitNodes))
		{
			foreach($arrCommitNodes as $commitNode)	
			{
				$canProcess = $this->processEachCommitNode($commitNode);
				if(!$canProcess)
				{
					// Set Error Here Cant not move further;
					return array(); 
				}
			}
		}
		else
		{
			$this->setValidateErrors('No commit point found... Exiting.....');
			return array();
		}
		return $this->arrFinalDataToWrite;
	}
	private function setValidateErrors($strError)
	{
		$this->arrXmlErr[] = $strError;	
	}	
	private function setXMLErrors()
	{
		$arrErr = libxml_get_errors();
		$errFound= 0;	
		if(isset($arrErr) && count($arrErr))
		{
			foreach ($arrErr as $err)
			{
				$this->arrXmlErr[] = trim($err->message) . ' at Line: ' . $err->line;
			}
			$errFound =1;
			libxml_clear_errors();
		}
		return $errFound;	
	}
	private function getXmlData($xmlNode)
	{
		/*
		 *	if a node 'N' having a child node as leaf node 'Cs', 'N' indicates a table
		 *	'Cs' are the fields of Table A
		 */
		/*
		 *	We can not use text type nodes as leaf nodes 
		 *	coz we need to use all text nodes as the value of its parent node
		 */
		$listChildNodes = $xmlNode->childNodes;
		$nodeName = $xmlNode->nodeName;
		$arrNodeData=array();
		foreach($listChildNodes as $currnode)
		{
			$childTextNode = '';
			if($this->isLeaf($currnode,$childTextNode))
			{
				//add into $arrNodeData 
				$arrNodeData[$currnode->nodeName] = $childTextNode;
			}
			else if($currnode->nodeType == XML_ELEMENT_NODE)
			{
				$this->getXmlData($currnode);
			}
		}
		if(count($arrNodeData))
		{
			$this->arrXmlData[$nodeName][]=$arrNodeData;	
		}
	}
	private function getDocInfo($xmlDocNode)
	{
		if($xmlDocNode){
			$this->arrDocInfo["TransactionID"] = $xmlDocNode->getAttribute("TransactionID");
			$this->arrDocInfo["UserID"] = $xmlDocNode->getAttribute("UserID");
			$this->arrDocInfo["RequestDate"] = $xmlDocNode->getAttribute("RequestDate");
			$this->arrDocInfo["xmlns"] = $xmlDocNode->getAttribute("xmlns");
			$this->arrDocInfo["actiontype"] =array();
		}
		else {
			$this->arrDocInfo["TransactionID"] = '';
			$this->arrDocInfo["UserID"] = '';
			$this->arrDocInfo["RequestDate"] = '';
			$this->arrDocInfo["xmlns"] = '';
			$this->arrDocInfo["actiontype"] = array();
		}
		return 0;
	}		
	private function isLeaf($xmlNode,&$textChildName)
	{
		$textChildName ='';
		$listChildNode =$xmlNode->childNodes;
		if($listChildNode->length == 1)
		{
			/*	if($listChildNode[0]->nodeType == XML_TEXT_NODE)
				{
				$textChildName = trim($childTextNode->nodeValue);
				return true;
				}
				return false;   */
			foreach($listChildNode as $childTextNode)
			{
				if($childTextNode->nodeType == XML_TEXT_NODE)
				{
					$textChildName = trim($childTextNode->nodeValue);
					return true;
				}
				return false;	
			}	
		} 
		return false;
	}
	private function processEachCommitNode($commitNode)
	{
		$commitId = $commitNode->getAttribute('id')+0 ;
		$actionType = $commitNode->getAttribute("ActionType") ;
		/*
		 *	If invalid action type discard this:::	
		 */
		if(!$this->ARR_REQUEST_TYPES[strtolower($actionType)])
		{
			$this->setValidateErrors('Invalid request type : '.$actionType);
			return 0;	
		}	
		$this->getXmlData($commitNode);
		$this->arrFinalDataToWrite[$commitId]= $this->arrXmlData;
		$this->arrDocInfo["actiontype"][$commitId] =  $actionType;
		$this->arrXmlData=array();
		return 1;
	}
	public function createResponse($objParser,$arrErrs,$arrNonXmlErrs,$noOfRowsAffected,$Sumsmapping)
	{
		$i =0;
		foreach($Sumsmapping as $traversalmappping){
			$i++;
			$Navmappingbycommitid[$i] = $traversalmappping;	
		}
		$strResponse = "";
		$errFound =0;
		$this->arrNonXmlErr=$arrNonXmlErrs;
		$processedCommitId =0;
		//_p($this->arrDocInfo);
		if(count($this->arrXmlErr))	
		{
			$strResponse .=" <commitpoint id='$processedCommitId' ActionType ='".$this->arrDocInfo["actiontype"][$processedCommitId]."' ActionStatus = 'false' >";
			$strResponse .="<parseError>"; 
			$errFound =1;
			foreach($this->arrXmlErr as $xmlError)
			{
				$strResponse .="<error>$xmlError</error>";					
			}
			$strResponse .="</parseError>";
                        $strResponse .="</commitpoint>";
		}
		if(isset($this->arrNonXmlErr) && count($this->arrNonXmlErr))
		{
			foreach($this->arrNonXmlErr as $processedCommitId => $arrErr)
			{
				$strResponse .=" <commitpoint id='$processedCommitId' ActionType ='".$this->arrDocInfo["actiontype"][$processedCommitId]."' ActionStatus = 'false' >";
				$errFound =1;
				//print_r($arrErr);
				foreach($arrErr as $err)
				{			
					$strResponse.= "<error><code>".$err['code']."</code><message>".$err['message']."</message></error>";
				}
				$strResponse .="</commitpoint>";
			}
		}
		else
		{
			foreach($this->arrDocInfo["actiontype"] as $commitId => $actionType){
				$strResponse .="<commitpoint id='$commitId' ActionType ='$actionType' SumsId = '$Navmappingbycommitid[$commitId]' ActionStatus = 'true' ></commitpoint> "; 
			}
		}	
		
		$strResponse ="<?xml version='1.0' encoding='utf-8'?><Response><WSResponse Status='".($errFound?0:1)."' TransactionID='".$this->arrDocInfo["TransactionID"]."' UserID='".$this->arrDocInfo["UserID"]."' ResponseDate='".date('Y-m-d')."T".date('H:i:s')."'> <Trigger Name='SyncNAV'> <ReturnValue>$strResponse</ReturnValue></Trigger></WSResponse></Response>" ;
		//echo $strResponse;
		return $strResponse;	
	}
	public function createErrorResponse($response,$commitId,$actiontype,$failuremessage)
	{
		$strResponse = "";
		$errFound =0;
				
		if($response == false){
                        $strResponse .="<commitpoint id='$commitId' ActionType ='".$actiontype."' ActionStatus = 'false' >";
			$strResponse .="<ErrorDetail><Code>ERROR</Code><Message>"; 
			$errFound =1;
			
			// if cases added for valiations error
			if($failuremessage == '-1'){
			$strResponse .="This Email ID already exist.";		
			}
			elseif($failuremessage == '0'){
				
				$strResponse .="Mobile No validation check";
			}
			
			$strResponse .="</Message></ErrorDetail>";
                        $strResponse .="</commitpoint>";
		}
		$strResponse ="<?xml version='1.0' encoding='utf-8'?><Response><WSResponse Status='".($errFound?0:1)."' TransactionID='".$this->arrDocInfo["TransactionID"]."' UserID='".$this->arrDocInfo["UserID"]."' ResponseDate='".date('Y-m-d')."T".date('H:i:s')."'> <Trigger Name='SyncNAV'> <ReturnValue>$strResponse</ReturnValue></Trigger></WSResponse></Response>" ;
		echo $strResponse;
		return $strResponse;
	}
	function CreateErrEle(&$errVal, $errKey)
	{
		$errVal = "<ERROR CODE='$errKey'>$errVal</ERROR>";
	}
}
?>
