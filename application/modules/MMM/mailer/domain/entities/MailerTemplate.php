<?php

class MailerTemplate
{
	private $templateId;

	private $subject;

	private $htmlTemplate;

	private $templateVars;

	private $variableMapping;

	public function __construct($templateData)
	{

		$this->templateId   = $templateData['templateId'];
		$this->subject      = htmlspecialchars_decode($templateData['subject']);
		$this->htmlTemplate = htmlspecialchars_decode($templateData['htmlTemplate']);
		$this->templateVars = $templateData['templateVars'];
		$this->_createVariableMapping();
	}

	public function getTemplateId()
	{
		return $this->templateId;
	}

	public function getSubject()
	{
		return $this->subject;
	}

	public function getTemplate()
	{
		return $this->htmlTemplate;
	}

	public function getTemplateVariables()
	{
		return $this->templateVars;
	}

	private function _createVariableMapping()
	{
		$this->variableMapping = array();
		foreach($this->templateVars as $k=>$v){
			if ($v['flagother'] != 'true') {
				$this->variableMapping[$v['varField']][] = $v['varname'];
			}
		}
	}

	public function replaceStaticTemplateVars()
	{
		foreach($this->templateVars as $k=>$v){
			if($v['varname'] == 'tracker' || $v['varname'] == 'widgettracker' || $v['varname'] == 'templateId' || $v['varname'] == 'mailerId' || $v['varname'] == 'mailId' || $v['varname'] == 'ip' || $v['varname'] == 'currentDate'){
			}
			else if ($v['flagother'] == 'true') {
				$this->subject = $this->replace($v['varname'], $v['varvalue'],$this->subject);
				$this->htmlTemplate = $this->replace($v['varname'], $v['varvalue'], $this->htmlTemplate);
			}
		}
	}

	public function getVariables()
	{
		$variables = array();
		foreach($this->templateVars as $k=>$v) {
			if ($v['flagother'] != 'true') {
				$variables[$v['varTable']][] = $v['varField'];
			}
		}
		return $variables;
	}

	public function replaceWidgets($widgetData)
	{
		$html = $this->htmlTemplate;
		foreach($widgetData as $k=>$v) {
			foreach($this->variableMapping[$k] as $varname) {
				$html = $this->replace($varname, $v, $html);
			}
		}
		return $html;
	}

	public function replaceSubject($widgetData)
	{
		if(!$widgetData['customSubject']) {
			$subject = $this->subject;
		}
		else {
			$subject =$widgetData['customSubject'];
		}


		$widgetData['LastAppliedInstitute'] = $widgetData['instituteName'];		// fix for SA mailer
		//unset (widgetData[''])

		foreach($widgetData as $k=>$v) {
			foreach($this->variableMapping[$k] as $varname) {
				$subject = $this->replace($varname, $v, $subject);
			}
		}

	
		// to take care of any unreplaced variables in subject replace them with empty string
		foreach($this->variableMapping as $k=>$v) {
			foreach($v as $k1=>$v1){
				$subject = $this->replace($v1, "", $subject);
			}
		}
		$subject = $this->replace('mailerId', "", $subject);
		$subject = $this->replace('tracker', "", $subject);
		$subject = $this->replace('widgettracker', "", $subject);

		unset($widgetData);
		return $subject;
	}

	public function replaceCSVData($data)
	{
		foreach($this->templateVars as $k=>$v){
			$variableMap[$v['varvalue']] = $v['varname'];
		}
		
		$html = $this->htmlTemplate;
		foreach($data as $k=>$v) {
			$html = $this->replace($variableMap[$k], $v, $html);
		}

		unset($variableMap);
		return $html;
	}

	public function replaceCSVSubject($data)
	{
		foreach($this->templateVars as $k=>$v){
			$variableMap[$v['varvalue']] = $v['varname'];
		}
		
		$subject = $this->subject;
		foreach($data as $k=>$v) {
			$subject = $this->replace($variableMap[$k], $v, $subject);
		}
		// handle case where a csv doesnt have data for a variable mentioned in template
		foreach($variableMap as $k=>$v){
			$subject = $this->replace($v, "", $subject);
		}
		return $subject;
	}

	public function replace($key, $value, $html)
	{
		$comment = "<!-- #".$key." --><!-- ".$key."# -->";
		return str_replace($comment,$value, $html);
	}
	

	public function replaceUrl($key, $value, $template, $url)
	{
		$pattern = '/\'([^"\']*#'.$key.'[^"\']*'.$key.'#[^"\']*)\'/';
		preg_match_all($pattern, $template, $matches);
		$matches = $matches[1];
		for($i=0;$i < count($matches); $i++) {
			$matches[$i] = str_replace("<!-- #".$key." --><!-- ".$key."# -->", '', $matches[$i]);
			$replacement = "'$url/".url_base64_encode($matches[$i])."/$value'";
			$template = preg_replace($pattern, $replacement, $template,1);
		}
		$pattern = '/"([^\'"]*#'.$key.'[^\'"]*'.$key.'#[^\'"]*)\"/';
		preg_match_all($pattern, $template, $matches);
		$matches = $matches[1];
		for($i=0;$i < count($matches); $i++) {
			$matches[$i] = str_replace("<!-- #".$key." --><!-- ".$key."# -->", '', $matches[$i]);
			$replacement = "'$url/".url_base64_encode($matches[$i])."/$value'";
			$template = preg_replace($pattern, $replacement, $template,1);
		}
		unset($matches);
		unset($replacement);
		unset($pattern);
		return $template;
	}

	public function buildTemplate($data,$isCSV = FALSE)
	{
		$template = '';
		if($isCSV) {
			$template = $this->replaceCSVData($data);
		}
		else {
			$template = $this->replaceStaticTemplateVars();
			$template = $this->replaceWidgets($data);
		}
		
		$template = $this->replace('mailerId','mailer-'.intval($data['mailerId']),$template);
		$template = $this->replace('mailId',$data['mailid'],$template);
		$template = $this->replace('ip',SHIKSHA_HOME,$template);
		$template = $this->replace('encodedemail',$data['encodedMail'],$template);
		$template = $this->replace('username',$data['FirstName'],$template);
		$template = $this->replace('currentDate',date('Ymd'),$template);
		$template = $template." <div style='display:none'><img src='".SHIKSHA_TRACK_HOME."/mailer/Mailer/WidgetLogin/MQ--/mailer-".intval($data['mailerId'])."/".$data['encodedMail']."/".$this->templateId."/".intval($data['mailid'])."' /></div>";

		$template = $this->replaceUrl("tracker", "mailer-".intval($data['mailerId'])."/".$data['email']."/".$this->templateId."/".intval($data['mailid']), $template, "https://".THIS_CLIENT_IP."/index.php/mailer/Mailer/blank");
		
		$template = $this->replaceUrl("widgettracker", "mailer-".intval($data['mailerId'])."/".$data['encodedMail']."/".$this->templateId."/".intval($data['mailid']), $template, "https://".THIS_CLIENT_IP."/index.php/mailer/Mailer/WidgetLogin");
		
        $oldsymbol = array("‘","’","“", "”", "`", "–", "…");
        $newSymbol = array("&#8242;", "&#8242;", "&#8243;", "&#8243;", "&#8245;", "&#8210;", "&#8230;");
		$template = str_replace($oldsymbol,$newSymbol, $template);

		unset($data);
		return $template;
	}

	public function buildSubject($data,$isCSV = FALSE)
	{
		$subject = '';
		if($isCSV) {
			$subject = $this->replaceCSVSubject($data);
		}
		else {
			$subject = $this->replaceSubject($data);
		}
		
		return $subject;
	}
}
