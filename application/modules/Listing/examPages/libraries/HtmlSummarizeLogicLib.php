<?php
class HtmlSummarizeLogicLib
{
  public function init($htmlContent)
  {
    $time = microtime(true);
    $this->maxCharCount = 400;
    $this->minCharCount = 200; 
    $this->charCount = 0;
    $this->removeNodes = false;  
    $this->xmlDoc = new DOMDocument();
    $this->xmlDoc->encoding = 'utf-8';
    $htmlContent = trim($htmlContent);
    while(1)
    {
      if(($sub = substr($htmlContent,0,4))=="<br>")
      {  
        $htmlContent = substr($htmlContent, 4);
      }
      else
        break;
    }
    //$this->xmlDoc->loadHTML($htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD); 
    $this->xmlDoc->loadHTML(utf8_decode("<div>".$htmlContent."</div>"));
    $this->itemsToRemove  = array();
  }
	public function summarizeData($htmlContent)
	{
    //file_put_contents("/var/www/html/b.txt","htmlContenttttttttt".$htmlContent."\n\n\n\n\n\n\n\n\n\n\n\n\n",FILE_APPEND);

    $this->init($htmlContent);

    $this->xmlDoc->removeChild($this->xmlDoc->doctype);           
    $this->xmlDoc->replaceChild($this->xmlDoc->firstChild->firstChild->firstChild, $this->xmlDoc->firstChild);


		$docElement = $this->xmlDoc->documentElement;
    //file_put_contents("/var/www/html/b.txt","htmlContent".$this->xmlDoc->saveHTML()."\n\n\n\n\n\n\n\n",FILE_APPEND);


		foreach ($docElement->childNodes as $item) 
		{
      //file_put_contents("/var/www/html/a.txt",print_r($item,true)."\n",FILE_APPEND);
      //file_put_contents("/var/www/html/a.txt",var_export($item->nodeType,true)."charCount".$this->charCount."      removeNodes ".$this->removeNodes."         \n",FILE_APPEND);
      // file_put_contents("/var/www/html/a.txt",XML_ELEMENT_NODE."\n",FILE_APPEND);

			if($this->charCount>=$this->maxCharCount)
				$this->removeNodes=true;
			if($this->removeNodes)
			{
        $this->itemsToRemove[] = $item;
			}
      else
      {
			if($item->nodeType==XML_TEXT_NODE || ($item->nodeType==XML_ELEMENT_NODE && in_array($item->tagName,array("p","h4","h1","h2","h3"))))
			{
        $this->handleText($item);
			}
			elseif($item->nodeType==XML_ELEMENT_NODE)
			{
        //file_put_contents("/var/www/html/a.txt","tagname".$item->tagName."\n",FILE_APPEND);
        switch($item->tagName)
        {
          case "br": 
          case "b":
            break;
          case "ul":
            $this->getUlNodesToBeRemoved($item);
            break;
          case "table":
          case "iframe":
          case "amp-iframe":
          case "img":
            if($this->charCount > $this->minCharCount)
            {
              $this->itemsToRemove[] = $item;
            }
            elseif($item->tagName=="table")
            {
              $this->getTableNodesToBeRemoved($item);
            }
            $this->removeNodes = true;
            break;
        }

			}
      }

      //file_put_contents("/var/www/html/a.txt","removeNodes".$this->removeNodes."\n",FILE_APPEND);
      //file_put_contents("/var/www/html/a.txt","itemsToRemove".print_r($this->itemsToRemove,true)."\n\n\n\n",FILE_APPEND);
		}
    $this->remove();
    error_log((microtime(true) - $time) . ' elapsed');
    //file_put_contents("/var/www/html/b.txt",$this->xmlDoc->saveHTML()."\n\n\n\n\n\n\n\n",FILE_APPEND);
		return $this->xmlDoc->saveHTML();
	} 
  public function handleText(&$item)
  {
          //file_put_contents("/var/www/html/a.txt","tagname agaon".$item->tagName."\n",FILE_APPEND);
          $haveChild = $item->hasChildNodes();
          if(!$haveChild && ($numberOfCharNeeded = $this->maxCharCount-$this->charCount)>0 && strlen($item->nodeValue)>$numberOfCharNeeded)
          {
              $newString = substr($item->nodeValue,0,$numberOfCharNeeded)."...";
              $this->removeNodes = true;
              $item->nodeValue = $newString;
          }
          elseif($haveChild)
          {
                          //file_put_contents("/var/www/html/a.txt","haveChild"."\n",FILE_APPEND);
            foreach($item->childNodes as $k=>$v)
            {
              //file_put_contents("/var/www/html/a.txt","tagname agaon".print_r($v,true)."\n",FILE_APPEND);
              if($v->tagName=="iframe" || $v->tagName=="img" || $v->tagName=="amp-iframe")
              {
                if($this->charCount > $this->minCharCount)
                {
                  $this->itemsToRemove[] = $item;
                }
                $this->removeNodes = true;
              }
            }
          }
            $this->charCount+=strlen($item->nodeValue);
  }

  public function getTableNodesToBeRemoved(&$item)
  {
      foreach($item->childNodes as $subNode)
      {
        $k1=0;
        foreach($subNode->childNodes as $subSubNode)
        {

          $k1++;
          if($k1>3)
          {
            $this->itemsToRemove[]= $subSubNode;
          }
        }
      }
    $this->removeNodes=true;
  }
  public function getUlNodesToBeRemoved(&$item)
  {
    $k1=0;
    foreach($item->childNodes as $subNode)
    {
      $k1++;
      if($k1>3)
      {
        $this->itemsToRemove[]=$subNode;
      }
    }
    $this->removeNodes=true;
  }
  public function remove()
  {
    //file_put_contents("/var/www/html/b.txt","before any remove".$this->xmlDoc->saveHTML()."\n\n\n\n\n\n\n\n",FILE_APPEND);

    if(is_array($this->itemsToRemove) && !empty($this->itemsToRemove))
    {
      foreach($this->itemsToRemove as $subNode)
      {
	if (is_object($subNode->parentNode))
	{
		$subNode->parentNode->removeChild($subNode);
	}

      }
    }
    unset($this->itemsToRemove);
  }
}
