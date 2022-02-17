<?php
require_once('vendor/autoload.php');
use Lullabot\AMP\AMP;
use Lullabot\AMP\Validate\Scope;

class AMPLibrary {
	private static $ampLibrary;
	private function __construct()
	{
		$this->CI  = &get_instance();
		$this->amp = new AMP();
	}
	public static function getInstance(){
    	if(static::$ampLibrary === null){
    		static::$ampLibrary = new AMPLibrary();
    	}
    	return static::$ampLibrary;
    }
        
	function convertHtmlToAmpHtml($html,$cssKey,$isFullHtml = false)
	{
		//first convert inline css to classnames
		$result = $this->convertInlineCssToClassNames($html,$cssKey,$isFullHtml);
		$parsedHtml = $result['html'];
		$externalCss = $result['css'];
		// Load up the HTML into the AMP object

		if($isFullHtml)
		{
			// If you're feeding it a complete document use the following line instead
			$this->amp->loadHtml($parsedHtml, ['scope' => Scope::HTML_SCOPE]);
		}
		else
		{
			// Note that we only support UTF-8 or ASCII string input and output. (UTF-8 is a superset of ASCII) 
			$this->amp->loadHtml($parsedHtml);	
		}

		// If you want some performance statistics (see https://github.com/Lullabot/amp-library/issues/24)
		//$this->amp->loadHtml($html, ['add_stats_html_comment' => true]);

		// Convert to AMP HTML and store output in a variable
		$amp_html = $this->amp->convertToAmpHtml();

		//Remove Invalid Image Tags in AMP Html
		
		$amp_html = $this->removeImageTagsInAmp($amp_html);

		return array('html' => $amp_html, 'css' => $externalCss);

		// Print validation issues and fixes made to HTML provided in the $html string
		//print($this->amp->warningsHumanText());

		// warnings that have been passed through htmlspecialchars() function
		// print($amp->warningsHumanHtml());
	}
	/**
	* below function is used for converting inline style attribute values to classname
	* @param : $input : input html having inline style attribute
	* @param : $isFullHtml : represent the $input value is part of html string or full html document
	*/
	function convertInlineCssToClassNames($input,$cssKey,$isFullHtml= false)
	{
		$doc = new DOMDocument();
		//$input = "<p><a style='color: red;' href='javascript:run();'>Run</a></p><div class='name'>Test</div><br><div style='float:left'>Test</div><br>";
		//$doc->loadHTML($input, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		$tempHtml = $this->checkImageUrlsHasDomainName($input);
		$doc->loadHTML(mb_convert_encoding($tempHtml, 'HTML-ENTITIES', 'UTF-8'));

		$labelCount = 1 ;
		foreach ($doc->getElementsByTagName("*") as $element) {
			if($element->hasAttribute('style'))
			{
				$inlineCss = $element->getAttribute('style');

				if(in_array($inlineCss, $cssArray))
				{
					$className = array_search($inlineCss, $cssArray);
				}
				else
				{
					$cssArray[$cssKey.'_class'.$labelCount] = $inlineCss;	
					$className = $cssKey.'_class'.$labelCount;
					$labelCount++;
				}
				

				if($element->hasAttribute('class'))
				{
					$classNames = $element->getAttribute('class');
					$element->removeAttribute('class');
					$element->setAttribute('class',$classNames.' '.$className);
				}
				else
				{
					$element->setAttribute('class',$className);
				}
				$element->removeAttribute('style');
			}
			//$body .= $doc->saveHTML($element);
		}

		if($isFullHtml)
		{
			$html = $doc->saveHTML();
		}
		else
		{
			$html =	preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $doc->saveHTML()));	
		}
		return array('html' => $html,'css' => $cssArray);
	}
	function checkImageUrlsHasDomainName($html,$domainName = SHIKSHA_HOME)
	{
		$doc = new DOMDocument();
		$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

		foreach ($doc->getElementsByTagName('img') as $element) {
			if($element->hasAttribute('src'))
			{
				$srcValue = $element->getAttribute('src');
				$imgUrl = addingDomainNameToUrl(array('url' => $srcValue,'domainName' => $domainName));
				$element->removeAttribute('src');
				$element->setAttribute('src',$imgUrl);
			}
		}
		$html = $html =	preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $doc->saveHTML()));
		return $html;

	}
	function removeImageTagsInAmp($html)
	{
		$doc = new DOMDocument();
		$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

		$images = $doc->getElementsByTagName('img');
	    $imgs = array();
	    foreach($images as $img) {
	        $imgs[] = $img;
	    }
		foreach ($imgs as $element) {
			$element->parentNode->removeChild($element);
		}
		$html =	preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $doc->saveHTML()));
		return $html;		
	}
}