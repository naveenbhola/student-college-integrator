<?php
class InviteFriends extends MX_Controller {
	function init() {
		$this->load->helper(array('form', 'url','image'));
		$this->load->library('common/ajax');		
        $validity = $this->checkUserValidation();
        if(!is_array($validity) || !$validity) {
//        	header('Location:/');
        }
	}

	function index() {
		$this->init();
		$this->load->view('inviteFriends/showContactsList');
	}
	
    function upload($showSent="false",$numSent=0) {
        $this->init();
        $validity = $this->checkUserValidation();
        if(!is_array($validity) || !$validity) {
        	header('Location:/');
        }
				if($validity[0]['quicksignuser'] == 1)
				{			
					$base64url = base64_encode(site_url('inviteFriends/upload'));
					$url = '/user/Userregistration/index/'.$base64url.'/1';
            				header('Location:' .$url);
					exit();
				}	
        $this->load->view('inviteFriends/upLoadContacts',array("showSent"=>$showSent,"numSent"=>$numSent,"validateuser"=>$validity));
    }
	
	function curlsetopt($url,$post="",$follow=1,$debugmode=0,$header=0){
		global $curlstatus,$cookiepath;
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_USERAGENT, "Contacts Importer");
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_COOKIEJAR,$cookiepath);
		curl_setopt($ch,CURLOPT_COOKIEFILE,$cookiepath);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);


		curl_setopt($ch,CURLOPT_HEADER,$header);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,$follow);
		if($post){curl_setopt($ch, CURLOPT_POST,1); curl_setopt($ch,CURLOPT_POSTFIELDS,$post);}
		$returned=curl_exec($ch);
		$ret = utf8_decode($returned);
		$curlstatus=curl_getinfo($ch);
		curl_close($ch);

		if($debugmode){error_log_shiksha( "<br/>==========================================================================================<br/><b>Calling URL:</b> ".htmlspecialchars($url,ENT_QUOTES)."<br/><b>Cookie Path:</b> ".htmlspecialchars($cookiepath,ENT_QUOTES)."<br/>==========================================================================================<br/><b> Returned = </b>".htmlspecialchars($returned,ENT_QUOTES)."<br/><br/>==========================================================================================<br/><br/>"); }
		return $returned;
	}


	function conv_hiddens($html){
		preg_match_all('|<input[^>]+type="hidden"[^>]+name\="([^"]+)"[^>]+value\="([^"]*)"[^>]*>|',$html,$getinputs,PREG_SET_ORDER);
		return $getinputs;
	}


	function conv_hiddens2txt($getinputs){
		$ac=null;
		foreach($getinputs as $eachinput){$ac.="&".urlencode(html_entity_decode(@$eachinput[1]))."=".urlencode(html_entity_decode(@$eachinput[2]));}
		return $ac;
	}



	function import_yahoo_contacts($email,$password){
		global $curlstatus,$cookiepath,$emailtmp;
		$cookiepath="/tmp/";

		$ct=0;
		while(file_exists($cookiepath."curlcookieY".$ct.".coki")===true){$ct++;}
		$cookiepath.="curlcookieY".$ct.".coki";

		//Automatically inject @domain.com into email if @ is not detected
		if(!strpos($email,"@")){$email.="@yahoo.com";}
		$emailtmp=$email;

		/*Get email domain*/
		preg_match('/.*\@([^\@]*)/',$emailtemp,$getdomain);
		$getdomain=strtolower(trim(@$getdomain[1]));

		$xreturn=$this->curlsetopt("https://login.yahoo.com/config/login?","login=".urlencode($email)."&passwd=".urlencode($password)."&.done=http%3a//mail.yahoo.com",1,0);

		$xreturn=$this->curlsetopt("https://login.yahoo.com/config/verify?.done=http%3a//mail.yahoo.com",0,1,0);

		$xreturn=$this->curlsetopt("http://address.yahoo.com/yab/us?A=B",0,1,0);
		


		if(!strncmp(@$curlstatus['url'],"http://address.yahoo.com/yab/us?",32)){

			$inputs=$this->conv_hiddens($xreturn);

			$xreturn=$this->curlsetopt("http://address.yahoo.com/index.php","submit[action_export_yahoo]=Export+Now".$this->conv_hiddens2txt($inputs),1,0);

			/*Match the first eight fields and get six fields*/
			preg_match_all('|"([^"]*)","([^"]*)","([^"]*)","([^"]*)","([^"]*)","[^"]*","[^"]*","([^"]*)"[^\n]*\n|',$xreturn,$contactemails,PREG_SET_ORDER);

			/*Cancel out the first line (CSV Header)*/
			array_shift($contactemails);

			$tmp=array();
			$contactsList = array();
			$count = 0;
			foreach($contactemails as $contact){

				$getnamea=@$contact[1];
				$getnameb=@$contact[2];
				$getnamec=@$contact[3];

				$getemaila=@$contact[6];
				$getemailb=@$contact[5];

				$getname=@$contact[4];

				if($getnamec||$getnamea||$getnameb){
					$getname=null;
					if($getnamec){$getname.=$getnamec;}
					if($getnamea){if($getname==null){$getname.=$getnamea;}else{$getname.=", ".$getnamea;}}
					if($getnameb){if($getname==null){$getname.=$getnameb;}else{$getname.=", ".$getnameb;}}
				}

				$email=null;
				if($getemaila){$email=$getemaila."@".$getdomain;}
				if($getemailb){$email=$getemailb;}
				if($email&&$this->validateemail($email)){
					$contactsList[$count][0] = "";
					$contactsList[$count][1] = $getname;
					$contactsList[$count][2] = "";
					$contactsList[$count][3] = $email;
					$count++;
				}

			}
	uasort($contactsList, 'cmp');
			$return = array();
			$return[0] = 'Data';
            $return[1] = $contactsList;
            echo $this->load->view('inviteFriends/showContactsList',array("contactsList"=>$contactsList,"type"=>"Yahoo!"), true);
			
			exit(0);
		}

		//if Account is not valid account
		//unlinkcookie();
		echo "ERROR";

	}






	function validateemail($email){
		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {return false;}else{return true;}
	}

function getLinkedInCaptcha($catchaValue,$captchaID,$captchaSecret,$csrfToken,$imageRandNo)
{
    $this->init();
    $ck = $this->input->post('ck');
    $csrfToken = $this->input->post('sig');
    $captchaID_captchaSecret  = $this->input->post('tok');
    $captchaArr = split("###",$captchaID_captchaSecret);
    $captchaID = $captchaArr[0];
    $captchaSecret = $captchaArr[1];
    $captcha = $this->input->post('captcha');
    global $curlstatus,$cookiepath,$emailtmp;
    $cookiepath="/tmp/";
    $cookiepath .="curlcookieO".$ck.".coki";

    $tmp = "outputType=microsoft_outlook&captchaText=$captcha&exportNetwork=Export&captchaID=$captchaID&captchaSecret=".urlencode($captchaSecret)."&csrfToken=".urlencode($csrfToken);
    $authedicated1 = $this->curlsetopt("http://www.linkedin.com/addressBookExport",$tmp,1,0,0);
    $authedicated1 = $this->curlsetopt("http://www.linkedin.com/addressBookExport?exportNetworkRedirect=&outputType=microsoft_outlook",'',1,0,0);
    error_log("INV ".$authedicated1);
    $doc = new DOMDocument (); 
    $doc->loadHTML ( $authedicated1 );
    $elements = $doc->getElementsByTagName ( 'input' );
    $captchaID = "";
    if (! is_null ( $elements )) {
        foreach ( $elements as $element ) {
            if ($element->getAttribute ( 'name' ) == 'captchaID')
                $captchaID = $element->getAttribute ( 'value' );
            if ($element->getAttribute ( 'name' ) == 'captchaSecret')
                $captchaSecret = $element->getAttribute ( 'value' );
            if ($element->getAttribute ( 'name' ) == 'csrfToken')
                $csrfToken = $element->getAttribute ( 'value' );

        }
    }
    error_log("INV ".$captchaID);
    if($captchaID == "") {
        preg_match_all('|"([^"]*)","([^"]*)","([^"]*)","([^"]*)","([^"]*)","([^"]*)"[^\n]*\n|',$authedicated1,$contactemails,PREG_SET_ORDER);
        array_shift($contactemails);
        $contactsList = array();
        $count = 0;
        //print_r($contactemails);
        for($i = 0 ; $i < count($contactemails); $i++) {
            if($contactemails[$i]['1'] == ""){
                $contactsList[$count][0] = "";
                $contactsList[$count][1] = $contactemails[$i]['2']." ".$contactemails[$i]['4'];
                $contactsList[$count][2] = "";
                $contactsList[$count][3] = $contactemails[$i]['6'];
                $count++;
            }
        }
	uasort($contactsList, 'cmp');

        echo $this->load->view('inviteFriends/showContactsList',array("contactsList"=>$contactsList,"type"=>"LinkedIn"), true);
        exit(0);
    }else {
        if (trim ( $captchaID ) == "" || trim ( $captchaSecret ) == "" || trim ( $csrfToken ) == "") {
            echo "ERROR";
            exit(0);
        }
        $captchaImage = 'http://media.linkedin.com/media' . $captchaSecret;
        $captcha = $this->curlsetopt( "$captchaImage", $tmp, 1, 0, 0 );
        $file_name = BASEPATH."../public/images/captcha/test_fileL$ct.jpg";
        $fp = fopen ( $file_name, "w" );
        fwrite ( $fp, $captcha ); // entering data to the file
        fclose ( $fp ); // closing the file pointer
        echo "Captcha:::".base_url()."/public/images/captcha/test_fileL$ct.jpg:::".$ct.":::".$captchaID."###".$captchaSecret.":::".$csrfToken;
    }
}



	function import_linkedin_contacts($email,$password){
		global $curlstatus,$cookiepath,$emailtmp;
		$cookiepath="/tmp/";

		$ct=0;
		while(file_exists($cookiepath."curlcookieO".$ct.".coki")===true){$ct++;}
		$cookiepath.="curlcookieO".$ct.".coki";

        $authedicated1 = $this->curlsetopt("https://www.linkedin.com/secure/login?trk=hb_signin",0,1,0);
        $doc = new DOMDocument ( );
        $doc->loadHTML ( $authedicated1 );
        $elements = $doc->getElementsByTagName ( 'input' );
        if (! is_null ( $elements )) {
            foreach ( $elements as $element ) {
                if ($element->getAttribute ( 'type' ) == 'hidden' && $element->getAttribute ( 'name' ) == 'csrfToken') {
                    $certificate = $element->getAttribute ( 'value' );
                }
            }
        }
        $tmp = "csrfToken=" . urlencode ( $certificate ) . "&session_key=" . urlencode ( $email ) . "&session_password=" . urlencode ( $password ) . "&session_login=" . 'Sign+In' . "&session_login=&session_rikey=";

        $authedicated = $this->curlsetopt("https://www.linkedin.com/secure/login",$tmp,1,0);
        $authedicated = $this->curlsetopt("https://www.linkedin.com/addressBookExport",$tmp,1,0);


        $doc->loadHTML ( $authedicated );
        $elements = $doc->getElementsByTagName ( 'input' );
        if (! is_null ( $elements )) {
            foreach ( $elements as $element ) {
                if ($element->getAttribute ( 'name' ) == 'captchaID')
                    $captchaID = $element->getAttribute ( 'value' );
                if ($element->getAttribute ( 'name' ) == 'captchaSecret')
                    $captchaSecret = $element->getAttribute ( 'value' );
                if ($element->getAttribute ( 'name' ) == 'csrfToken')
                    $csrfToken = $element->getAttribute ( 'value' );

            }
        }
        if (trim ( $captchaID ) == "" || trim ( $captchaSecret ) == "" || trim ( $csrfToken ) == "") {
            echo "ERROR";
            exit(0);
        }
        $captchaImage = 'http://media.linkedin.com/media' . $captchaSecret;
        $captcha = $this->curlsetopt( "$captchaImage", $tmp, 1, 0, 0 );
        $file_name = "/var/www/html/shiksha/public/images/captcha/test_fileL$ct.jpg";

        $file_name = BASEPATH."../public/images/captcha/test_fileL$ct.jpg";
        $fp = fopen ( $file_name, "w" );
        fwrite ( $fp, $captcha ); // entering data to the file
        fclose ( $fp ); // closing the file pointer
        echo "Captcha:::".base_url()."/public/images/captcha/test_fileL$ct.jpg:::".$ct.":::".$captchaID."###".$captchaSecret.":::".$csrfToken;
	}




	function import_orkut_contacts($email,$password){
		global $curlstatus,$cookiepath,$emailtmp;
		$cookiepath="/tmp/";

		$ct=0;
		while(file_exists($cookiepath."curlcookieO".$ct.".coki")===true){$ct++;}
		$cookiepath.="curlcookieO".$ct.".coki";
		$xreturn = $this->curlsetopt("https://www.google.com/accounts/ServiceLogin?service=orkut&continue=http%3A%2F%2Fwww.orkut.com%2FRedirLogin.aspx%3Fmsg%3D0%26page%3Dhttp%253A%252F%252Fwww.orkut.com%252FHome.aspx&hl=en-US&rm=false&passive=true",0,1,0);
		preg_match('/.*GALX"\n.*value="(.*)"/',$xreturn,$data);


		/*Get email domain*/
		preg_match('/.*\@([^\@]*)/',$email,$getdomain);
		$getdomain=strtolower(trim(@$getdomain[1]));

		$xreturn=$this->curlsetopt("https://www.google.com/accounts/ServiceLoginAuth?service=orkut","continue=http%3A%2F%2Fwww.orkut.com%2FRedirLogin.aspx%3Fmsg%3D0%26page%3Dhttp%253A%252F%252Fwww.orkut.com%252FHome.aspx&service=orkut&rm=false&hl=en-US&GALX=".$data[1]."&Email=".urlencode($email)."&Passwd=".urlencode($password)."&rmShown=1&signIn=Sign+in",1,0);
        $xreturn = str_replace('&#39;','\'',$xreturn);
		preg_match('/.*url=\'([^\']*)\'".*/',$xreturn,$redirectDomain);
		$temp = preg_replace("/&amp;/","&",$redirectDomain[1]);
		$xreturn=$this->curlsetopt($temp,0,1,0);
		$xreturn=$this->curlsetopt("http://www.orkut.com/contacts.CSV",0,1,0);
		preg_match('/.*img  height="71px" src="([^"]*)".*/',$xreturn,$captchUrl);
		preg_match("/.*'CGI.POST_TOKEN'[^']*'([^']*)'.*/",$xreturn, $post_token);
		preg_match("/.*'Page.signature.raw'[^']*'([^']*)'.*/",$xreturn, $signature);
		$post_token[1] = urlencode($post_token[1]);
		$signature[1] = urlencode($signature[1]);

		if(trim($signature[1]) == "") {
//			header("Location:test.php");
			echo "ERROR";
			exit(0);
		}

		$xreturn=$this->curlsetopt("http://www.orkut.com".$captchUrl[1],0,1,0);
		$file_name="/var/www/html/shiksha/public/images/captcha/test_file$ct.jpg";               // file name
        $file_name = BASEPATH."../public/images/captcha/test_file$ct.jpg";
		$fp = fopen ($file_name, "w"); 
		fwrite ($fp,$xreturn);          // entering data to the file
		fclose ($fp);                                // closing the file pointer
		$return = array();
		echo "Captcha:::".base_url()."/public/images/captcha/test_file$ct.jpg:::".$ct.":::".$post_token[1].":::".$signature[1];
	}



	function import_gmail_contacts($email,$password){
		global $curlstatus,$cookiepath,$emailtmp;

		$cookiepath="/tmp/";
		$ct=0;
		while(file_exists($cookiepath."curlcookieG".$ct.".coki")===true){$ct++;}
		$cookiepath.="curlcookieG".$ct.".coki";

		//Automatically inject @domain.com into email if @ is not detected
		$emailtmp=$email;

		/*Get email domain*/
		preg_match('/.*\@([^\@]*)/',$email,$getdomain);
		$getdomain=strtolower(trim(@$getdomain[1]));

		$xreturn=$this->curlsetopt("http://mail.google.com/mail/",0,1,0);


		//echo $xreturn;

		/*Get Form Hidden Inputs*/
		$inputs=$this->conv_hiddens($xreturn);
		//echo "\n";
		//print_r($inputs);
		//echo "\n";
		//echo "\n".conv_hiddens2txt($inputs)."\n";


		/*Get Form POST action page*/
		/*Note that the link returned is a relative link not absolute link*/
		preg_match('/<form[^>]+action\="([^"]*)"[^>]*>/',$xreturn,$getlink);

		$xreturn=$this->curlsetopt("https://www.google.com/accounts/ServiceLoginAuth?service=mail","Email=".urlencode($email)."&Passwd=".urlencode($password).$this->conv_hiddens2txt($inputs)."&rmShown=1&signIn=Sign+in",1,0);
		/*Get Javascript Redirection Link !optional -not used*/
        $xreturn = str_replace('&#39;','\'',$xreturn);
		preg_match('/url=\'([^\']*)\'/',$xreturn,$getlink);


		preg_match('/Redirecting/',$xreturn,$getlink);
		if(count($getlink)>0){

			$xreturn=$this->curlsetopt("http://mail.google.com/mail/h/",0,1,0);


			$xreturn=$this->curlsetopt("http://mail.google.com/mail/contacts/data/export?exportType=ALL&groupToExport=&out=OUTLOOK_CSV","",1,0,0);



			/*Match new lines*/
			preg_match_all('|([^\n]*)\n|',$xreturn,$records,PREG_SET_ORDER);

			$tmp=array(); $newfields=array();
			$flagStart = true;
			$contactsList = array();
			$count = 0;
			foreach($records as $record){
				if($flagStart) {
					$flagStart = false;
					continue;
				}
				$arrVal = explode(",",$record[0]);
				$name = $arrVal[5];
				$email = $arrVal[15];
				if($email&&$this->validateemail($email)){
					$contactsList[$count][0] = "";
					$contactsList[$count][1] = $name;
					$contactsList[$count][2] = "";
					$contactsList[$count][3] = $email;
					$count++;
				}

			}
	uasort($contactsList, 'cmp');

            echo $this->load->view('inviteFriends/showContactsList',array("contactsList"=>$contactsList,"type"=>"Gmail"), true);
			exit(0);
		}

		//if Account is not valid account
		echo "ERROR";
	}


	
	function getOrkutCaptcha() {
		$this->init();
		$ck = $this->input->post('ck');
		$sig = $this->input->post('sig');
		$tok = $this->input->post('tok');
		$captcha = $this->input->post('captcha');
		global $curlstatus,$cookiepath,$emailtmp;
		$cookiepath="/tmp/";
		$cookiepath .="curlcookieO".$ck.".coki";
		$temp = "http://www.orkut.com/CaptchaConfirm.aspx?d=%2FFriends.aspx";
		$data = "POST_TOKEN=".urlencode($tok)."&signature=".urlencode($sig)."&cs=".$captcha."&Action.submit=&Action.submit=Submit+Query";
		$xreturn=$this->curlsetopt($temp,$data,1,0);
		$temp = "http://www.orkut.com/contacts.CSV?cs=".$ck;
		$xreturn=$this->curlsetopt($temp,$data,1,0);
		preg_match('/.*img  height="71px" src="([^"]*)".*/',$xreturn,$captchUrl);
		if(count($captchUrl) > 0){
			preg_match("/.*'CGI.POST_TOKEN'[^']*'([^']*)'.*/",$xreturn, $post_token);
			preg_match("/.*'Page.signature.raw'[^']*'([^']*)'.*/",$xreturn, $signature);
			$post_token[1] = urlencode($post_token[1]);
			$signature[1] = urlencode($signature[1]);
			$ct = $ck;

			$xreturn=$this->curlsetopt("http://www.orkut.com".$captchUrl[1],0,1,0);
			$file_name="/var/www/html/shiksha/public/images/captcha/test_file$ct.jpg";
            $file_name = BASEPATH."../public/images/captcha/test_file$ct.jpg";
			$fp = fopen ($file_name, "w");
			fwrite ($fp,$xreturn);          // entering data to the file
			fclose ($fp);                                // closing the file pointer

			echo "Captcha:::".base_url()."/public/images/captcha/test_file$ct.jpg:::".$ct.":::".$post_token[1].":::".$signature[1];
		}else {
			preg_match_all('|"([^"]*)","([^"]*)","([^"]*)","([^"]*)"[^\s]*\s|',$xreturn,$contactemails,PREG_SET_ORDER);
			array_shift($contactemails);
			$contactsList = array();
			$count = 0;
			//print_r($contactemails);
			for($i = 0 ; $i < count($contactemails); $i++) {
                if($contactemails[$i]['1'] == "Friend"){
                    $contactsList[$count][0] = "";
                    $contactsList[$count][1] = $contactemails[$i]['2']." ".$contactemails[$i]['3'];
                    $contactsList[$count][2] = "";
                    $contactsList[$count][3] = $contactemails[$i]['4']; 
                    $count++;
                }
			}


	uasort($contactsList, 'cmp');

            echo $this->load->view('inviteFriends/showContactsList',array("contactsList"=>$contactsList,"type"=>"Orkut"), true);
			exit(0);


		}
	}






	function getUseNamePassword() {
		$this->init();
		$username = $this->input->post('userName');
		$password = $_POST['passwd'];
		$type = $this->input->post('type');
	
		if($type == "orkut") {
			$this->import_orkut_contacts($username, $password);
		}else if($type == "linkedin") {
			$this->import_linkedin_contacts($username, $password);
		}else {
			if($type == "yahoo") {
				$this->import_yahoo_contacts($username, $password);
			}else {
				$this->import_gmail_contacts($username, $password);
			}
		}
	}
}

function cmp($a,$b){
	$str1 = $a[1];
	$str2 = $b[1];
	if($str1 == '') {
		$str1 = $a[3];
	}
	if($str2 == '') {
		$str2 = $b[3];
	}
	return (strcmp(strtolower($str1),strtolower($str2)));
}
?>	
