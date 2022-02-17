<?php
class NewPage extends MX_Controller {
	function init() {
		$this->load->helper(array('form', 'url'));
		$this->load->library('ajax');
        $this->load->library('paymentClient');
        global $validity;
        $validity = $this->checkUserValidation();
        global $logged;
        if($validity == "false" ) {
            $logged = "No";
        }else {
            $logged = "Yes";
        }

    }

    function mailAlert($functionName = ''){
    	if($functionName){
    		mail('mansi.gupta@shiksha.com','inside newPage.php controller','Function Name : '.$functionName.' is being used.');
    		mail('naveen.bhola@shiksha.com','inside newPage.php controller','Function Name : '.$functionName.' is being used.');
    	}
    }

    function mailerlogs() {
        $this->load->view('payment/getMailerData.php');
    }

    function newlogs() {
	    $dbConfig = array( 'hostname'=>'localhost');
	    $this->load->library('logsconfig');
	    $this->logsconfig->getDbConfig($appId,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);
	    $queryCmd = "Select distinct(domain) from trackUrls;";
	    $query = $dbHandle->query($queryCmd);
        $domains = array();
        $count = 0;
	    foreach ($query->result() as $row){
		    $domains[$count] = $row->domain; 
            $count++;
	    }


        $this->load->view('payment/getNewData.php',array("domains"=>$domains));
    }

    function logs() {
        $this->load->view('payment/getData.php');
    }
    function printLogs() {
	    $finalOutput = "";
	    set_time_limit(0);
	    $sDate = $this->input->post('sDate');
		$eDate = $this->input->post('eDate');
	    if(strlen(trim($eDate)) < 12) {
		    $eDate = $eDate." 23:59:59";
	    }else {
		    $eDate = $eDate;
	    }
	    $dbConfig = array( 'hostname'=>'localhost');
	    $this->load->library('logsconfig');
	    $this->logsconfig->getDbConfig($appId,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);
	    $queryCmd = "Select * from log2 where start = ? and end = ?";
	    $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
	    foreach ($query->result() as $row){
		    echo $row->wholeHtml; 
	    }
    }



    function printMailerLogs() {
	    $finalOutput = "";
	    set_time_limit(0);
		$sDate = $this->input->post('sDate');
		$eDate = $this->input->post('eDate');
	    if(strlen(trim($eDate)) < 12) {
		    $eDate = $eDate." 23:59:59";
	    }else {
		    $eDate = $eDate;
	    }
	    $dbConfig = array( 'hostname'=>'localhost');
	    $this->load->library('logsconfig');
	    $this->logsconfig->getDbConfig($appId,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);
	    $queryCmd = "Select * from log3 where start = ? and end = ?";
	    $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
	    foreach ($query->result() as $row){
		    echo $row->wholeHtml; 
	    }
    }






    function getMailerLogs() {
    	return false;
    	$this->mailAlert('getMailerLogs');
	    $finalOutput = "";
	    set_time_limit(0);
		$sDate = $this->input->post('sDate');
		$eDate = $this->input->post('eDate');
	    if(strlen(trim($eDate)) < 12) {
		    $eDate = $eDate." 23:59:59";
	    }else {
		    $eDate = $eDate;
	    }
	    $dbConfig = array( 'hostname'=>'localhost');
	    $this->load->library('logsconfig');
	    $this->logsconfig->getDbConfig($appId,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);
	    $phpSessionFirstPage = array();
	    $print = array();
	    $iter = 0;
	    $phpSessionLoggedInArr = array();
	    $k=0;
	    $rowsGot = 0;
        $queryCmd = "Select * from trackMailerUrls";
        $queryUrls = $dbHandle->query($queryCmd);
	    $file=fopen("/var/www/html/shiksha/urlFile1","r");
	    while(true) {
		    $tempIter = $iter+10000;
		    $queryCmd = "Select id,phpSession,cookie,refrer,url from log1 where time > ? and time < ? order by id limit $iter,10000";
		    //echo $queryCmd;
		    $iter = $tempIter;
		    $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
		    $numRows = 0;
		    foreach ($query->result() as $row){
			    if(!isset($phpSessionFirstPage[$row->phpSession])) {
				    if(strlen($row->refrer) > 9) {
					    $phpSessionFirstPage[$row->phpSession] = $row->refrer;
				    }
			    }
			    $cookie = $row->cookie;
			    $pos = strripos($cookie,"[user]");
			    if($pos === false) {
			    }else {
				    if(!isset($print[$row->phpSession])){
					    $print[$row->phpSession] = "1";
					    $phpSessionLoggedInArr[$k] = "\"".$row->phpSession."\"";
					    $k++;
				    }
			    }
			    $lastId = $row->id;
			    $numRows++;

		    }
		    if($numRows < 10000) {
			    break;
		    }
	    }
	    $phpSessionLoggedIn = implode(",",$phpSessionLoggedInArr);
	            //echo count($phpSessionFirstPage)."<br/>";
	          //echo count($print);


	    $totalPageViews=0;
	    $totalTriedReg = 0;
	    $totalDidReg = 0;
	    $lastPageArr = array();
	    $didRegistrationBannerCookie = array();
	    $didRegistrationPhpSessionBanner = array();
	    $finalOutput .= "<table border='1'>";
	    //echo "<table border='1'>";






    foreach ($queryUrls->result() as $row){
		    $finalOutput .= "<tr><td>";
		    //echo "<tr><td>";

		    $url = trim($row->url);
            $globalUrl = $url; 
		    $finalOutput .= $url."<br/></td><td>";
            echo $url;

		    //echo $url."<br/>";
		    //echo "</td><td>";

		    if($url == ""){
			    continue;
		    }
		    $urlGroup = array();
		    $listingPageViews = 0;
		    $becameLeads = 0;
		    $searched = 0;


		    $queryCmd = "Select count(*) as totalCount from log1 where url=? and time > ? and time < ?";
		    //echo $queryCmd;
		    //            echo $queryCmd;
		    $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
		    foreach ($query->result() as $row){
			    $totalCountUrl = $row->totalCount; 
			    $finalOutput .= "Total PageViews = ".$row->totalCount."<br/>";
			    //echo "Total PageViews = ".$row->totalCount."<br/>";
			    $totalPageViews = $totalPageViews + $row->totalCount;
		    }
		    $finalOutput .= "</td><td>";
		    //echo "</td><td>";
		    $queryCmd = "Select phpSession,cookie from log1 where (refrer='\"$url\"' or url='\"$url\"') and time > ? and time < ? and phpSession!=\"\" order by id";
		    //echo $queryCmd;
		    $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
		    $phpSessionArr = array();
		    $phpSessionArrAll = array();
		    $phpSessionRevArr = array();
		    $phpSessionArr[0] = "\"1234\"";
		    $phpSessionArrAll[0] = "\"1234\"";
		    $k=1;
		    $l=1;
		    foreach ($query->result() as $row){
			    if(trim($row->phpSession) == "") {
				    continue;
			    }
			    if(preg_match("/[^0-9A-Za-z]/i", $row->phpSession)) {
				    continue;
			    }
			    $cookie = $row->cookie;             
			    //                $pos = strripos($cookie,"[user]");  
			    $phpSessionArrAll[$l] = "\"".$row->phpSession."\"";
			    $l++;
			    //              if($pos === false) {                
			    if($phpSessionRevArr[$row->phpSession] != "1") {
				    $phpSessionArr[$k] = "\"".$row->phpSession."\"";
				    $k++;
				    $phpSessionRevArr[$row->phpSession] = "1";
			    }
			    //            }
		    }
		    $finalOutput .= "Unique People Visited anotherPage = ".(count($phpSessionArr)-1);
		    $finalOutput .= "</td><td>";
		    //echo "Unique People Visited anotherPage = ".(count($phpSessionArr)-1); 
		    //echo "</td><td>";
		    $phpSession = implode(",",$phpSessionArr);
		    $phpSessionAll = implode(",",$phpSessionArrAll);
		    $queryCmd = "Select url from log1 where phpSession in ($phpSessionAll)";
		    //echo $queryCmd;
		    //echo $queryCmd;
		    $query = $dbHandle->query($queryCmd);
		    $numRows = 0;
		    $countPages = 0;
		    foreach ($query->result() as $row){
			    $countPages++;
			    $hostName = preg_replace('/(https:\/\/[^\/]*)\/.*/', '$1', $row->url);
			    if(isset($urlGroup[$hostName])) {
				    $urlGroup[$hostName]++;
			    }else {
				    $urlGroup[$hostName] = 1;
			    }
                preg_match('/getListingDetail/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                if(count($matches) > 0) {
                    $listingPageViews++;
                }
                preg_match('/Listing\/requestInfo/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                if(count($matches) > 0) {
                    $becameLeads++;
                }
                preg_match('/search\/index/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                if(count($matches) > 0) {
                    $searched++;
                }

		    }
		    //            echo $countPages;
		    $hostName = preg_replace('/(https:\/\/[^\/]*)\/.*/', '$1', $url);
		    if(isset($urlGroup[$hostName])) {
			    $urlGroup[$hostName] += $totalCountUrl;
		    }else { 
			    $urlGroup[$hostName] = $totalCountUrl;
            }
            preg_match('/getListingDetail/', $url, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                $listingPageViews += $totalCountUrl;
            }
            preg_match('/Listing\/requestInfo/', $url, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                $becameLeads += $totalCountUrl;
            }
            preg_match('/search\/index/', $url, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                $searched += $totalCountUrl;
            }





		    $queryCmd = "select * from log1 where phpSession in ($phpSession) order by id";
		    //echo $queryCmd;

		    $query = $dbHandle->query($queryCmd);
		    $visitedRegistrationPage = array();
		    $didRegistrationPage = array();
		    foreach ($query->result() as $row){
			    $url = $row->url;
			    $pos = strripos($row->cookie,"[user]");
			    $pos = strripos($url,"Userregistration/submit");
			    if($pos === false) {
			    }else {
				    $visitedRegistrationPage[$row->phpSession] = "1";
				    $lastPageArr[$row->phpSession] = 1;
			    }
			    $pos = strripos($url,"userfromhome");
			    if($pos === false) {
			    }else {
				    $visitedRegistrationPage[$row->phpSession] = "1";
				    $lastPageArr[$row->phpSession] = 1;
			    }
			    $pos = strripos($url,"Userregistration/quicksignup");
			    if($pos === false) {
			    }else {
				    $visitedRegistrationPage[$row->phpSession] = "1";
				    $lastPageArr[$row->phpSession] = 1;
			    }
                $pos = strripos($url,"Userregistration/userfromMarketingPage");
                if($pos === false) {
                }else {
                    $visitedRegistrationPage[$row->session] = "1";
                    $lastPageArr[$row->session] = 1;
                }


			    $pos = strripos($url,"Listing/requestInfo");
			    if($pos === false) {
			    }else {
				    $visitedRegistrationPage[$row->phpSession] = "1";
				    $lastPageArr[$row->phpSession] = 1;
			    }
			    $lastPageArr[$row->phpSession]++;
			    //                if($lastPageArr[$row->phpSession] == 2) {
			    //                    echo $row->url."<br/>";
			    //                }
			    if($visitedRegistrationPage[$row->phpSession] == "1") {
				    $cookie = $row->cookie;
				    $pos = strripos($cookie,"[user]");
				    if($pos === false) {
				    }else {
					    if(!isset($didRegistrationPage[$row->phpSession])) {
						    $didRegistrationPage[$row->phpSession] = "1";
						    $didRegistrationPhpSessionBanner[$row->phpSession] = "1";
						    $didRegistrationBannerCookie[$row->phpSession] = $row->cookie;

						    //                            echo $lastPageArr[$row->phpSession]." ";
					    }
				    }
			    }

		    }
		    //       foreach($didRegistrationPage as $key=>$val) {
		    //           echo "<br/>$key";
		    //       }
		    $finalOutput .= "<br/>Tried Registering = ".count($visitedRegistrationPage);
		    //echo "<br/>Tried Registering = ".count($visitedRegistrationPage);

		    //echo "</td><td>";

		    $totalTriedReg = $totalTriedReg + count($visitedRegistrationPage);
		    $finalOutput .= "<br/>Did Registration(full+request) = ".count($didRegistrationPage);
		    //echo "<br/>Did Registration(full+request) = ".count($didRegistrationPage);
		    $finalOutput .= "</td><td>";
		    //echo "</td><td>";
		    foreach($urlGroup as $key=>$val) {
			    $finalOutput .= $key." ".$urlGroup[$key]."<br/>";


			    //echo $key." ".$urlGroup[$key]."<br/>";

		    }
		    $finalOutput .= "</td><td>";
            $finalOutput .= "Number of Listing Viewed: ".$listingPageViews."<br/>Number of Leads Formed: ".$becameLeads."<br/>Number of searches made".$searched."<br/>";
		    $finalOutput .= "</td></tr>";
		    //echo "</td></tr>";
		    $totalDidReg = $totalDidReg + count($didRegistrationPage);
	    }
	    $finalOutput .= "</table>";
	    $finalOutput .= "Total = ".$totalPageViews." Page Views   ".$totalTriedReg." Registration attempts    ".$totalDidReg." Sucessful Registration ";
	    //echo "</table>";
	    //echo "Total = ".$totalPageViews." Page Views   ".$totalTriedReg." Registration attempts    ".$totalDidReg." Sucessful Registration ";

	    /*       $totalUsersLoggedIn = count($phpSessionLoggedInArr);
		     $visitedRegistrationPage = array();
		     $didRegistrationPage = array();
		     echo "<div id='show'; style='display:none'>"; 
		     $finalCount = 0;
		     $finalNotCount = 0;

		     for($i = 0;$i < $totalUsersLoggedIn; $i =$i+100) {
		     if($i+100 > $totalUsersLoggedIn) {
		     $toAdd = $totalUsersLoggedIn-$i;
		     }else {
		     $toAdd = 100;
		     }
		     $tempArrayFor100 = array();
		     $l=0;
		     for($k=$i;$k<$i+$toAdd;$k++) {
		     $tempArrayFor100[$l] = $phpSessionLoggedInArr[$k];
		     $l++;
		     }
		     $phpSessionLoggedIn = implode(",",$tempArrayFor100);

		     $queryCmd = "select url, cookie, phpSession from log1 where phpSession in ($phpSessionLoggedIn) and phpSession!=\"\" order by id";
	    //                echo "<br/>".count($phpSessionLoggedInArr);
	    //            echo "$queryCmd";

	    $query = $dbHandle->query($queryCmd);
	    //                var_dump($query->result());
	    foreach ($query->result() as $row){
	    $url = $row->url;
	    $pos = strripos($row->cookie,"[user]");
	    $pos = strripos($url,"Userregistration/submit");
	    if($pos === false) {
	    }else {
	    $visitedRegistrationPage[$row->phpSession] = "1";
	    }
	    $pos = strripos($url,"userfromhome");
	    if($pos === false) {
	    }else {
	    $visitedRegistrationPage[$row->phpSession] = "1";
	    }
	    $pos = strripos($url,"Userregistration/quicksignup");
	    if($pos === false) {
	    }else {
	    $visitedRegistrationPage[$row->phpSession] = "1";
	    }
	    $pos = strripos($url,"Listing/requestInfo");
	    if($pos === false) {
	    }else {
	    $visitedRegistrationPage[$row->phpSession] = "1";
	    }
	    //                    echo "<br/>".$visitedRegistrationPage[$row->phpSession];
	    if($visitedRegistrationPage[$row->phpSession] == "1") {
	    $cookie = $row->cookie;
	    $pos = strripos($cookie,"[user]");
	    if($pos === false) {
	    }else {
	    if(!isset($didRegistrationPage[$row->phpSession])) {
	    $didRegistrationPage[$row->phpSession] = "1";
	    if (preg_match("/(google-)|(yahoo-)|(rediff-)/i", $phpSessionFirstPage[$row->phpSession])) {
	    $finalNotCount++;
	    //                        echo "<br/>".$row->phpSession." ".$phpSessionFirstPage[$row->phpSession]."<br/>";
	    } else {
	    if(!isset($didRegistrationPhpSessionBanner[$row->phpSession])) {
	    $finalCount++;
	    echo "<br/>".$row->phpSession." ".$phpSessionFirstPage[$row->phpSession]."<br/>";
	    // echo "<br/>".$row->cookie;

	    }
	    }
	    }
	    }
    }

    }
    }
    foreach($didRegistrationBannerCookie as $key=>$val) {
	    //                echo "<br/>".$val;
    }
    echo "</div>";
    echo "<br/><a href='#' onClick='javascript: document.getElementById(\"show\").style.display=\"\";'>Show All $finalCount (Registration done directly from site)</a>";*/
	    $finalOutput .= "<br/><br/><a href='https://linuxcp10325.dn.net/payment/newPage/mailerlogs'>Back</a>";
	    //echo "<br/><br/><a href='https://linuxcp10325.dn.net/payment/newPage/logs'>Back</a>";
	//echo $finalOutput;
	$queryCmd = 'insert into log3 values("", ?,?, "'.mysql_escape_string($finalOutput).'") on duplicate key update wholeHtml="'.mysql_escape_string($finalOutput).'"';
	echo $queryCmd;
    $dbConfig = array( 'hostname'=>'localhost');
    //error_log('debug'. 'getProductData query cmd is ' . $queryCmd);
    $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
    }

    function getLeads() {
        echo "<html><title>
        Leads Search on the basis of Questions asked by the users.
        </title><body>
        <style type='text/css'>
        .logoNewClass {
        background:transparent url(https://172.16.3.226/public/images/headerImage.gif) no-repeat scroll 0pt -689px;
        display:block;
        padding-bottom:65px;
        padding-left:229px;
        }
        </style>
        <span title='Shiksha.com Home :: Education Information Circle' class='logoNewClass' > </span>
        <br/>
        
        <form action='/payment/newPage/showLeads' method='POST'> Search Leads: <input type='text' name='q' />
        <br/>
<br/>

<input id='selectedSearchType1' type='radio' value='or' autocomplete='off' name='type'/>
Or b/w words
<br/>
<input id='selectedSearchType2' type='radio' checked='checked'  value='and'  autocomplete='off' name='type'/>
And b/w words
<br/>
<br/>
        
        
        <input type='submit' value='Go' /></form></body></html>";
    }

    function sortArray($data, $sortBasis){
        $count = 0;
        $returnArr = array();
        $doneArr = array();
        for($i = 0; $i < count($data);$i++) {
            $value = "-1";
            $key = "-1";
            $max = "-100000";
            foreach($data as $key1=>$value1) {
                if($doneArr[$key1] == "1") {
                    continue;
                }
                if($value1[$sortBasis] > $max) {
                    $max = $value1[$sortBasis];
                    $value = $value1;
                    $key = $key1;
                }
            }
            $returnArr[$count] = $value;
            $doneArr[$key] = "1";
            $count++;
        }
        return $returnArr;
    }

    function showLeads() {
    	return false;
    	$this->mailAlert('showLeads');
        $query = $this->input->post('q');
        $query = trim($query);
        echo "<br/>Searched Query = <b>".$query."</b><br/>";
        $type = $this->input->post('type');
        if($type == "or") {
            $query = preg_replace('/[^a-zA-Z]+/', '|', $query);
            $query = "'(^(".$query.")\\\\^)|([0-9](".$query.")\\\\^)'";
            $query = "and relatedTags REGEXP $query";
        }else{
            $query = preg_replace('/[^a-zA-Z]+/', ' ', $query);
            $queryArr = split(" ",$query);
            $query = "";
            for($i = 0; $i < count($queryArr); $i++) {
                $query .= "and relatedTags REGEXP '(^".$queryArr[$i]."\\\\^)|([0-9]".$queryArr[$i]."\\\\^)' ";
            }
        }
        //echo "$query";
        $dbConfig = array( 'hostname'=>'localhost');
        $this->load->library('leadsconfig');
        $this->leadsconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);
        $queryCmd = "select * from lead, shiksha.tuser, shiksha.countryCityTable where 1=1 $query and tuser.userid=lead.userId and countryCityTable.city_id=tuser.city";
        $query = $dbHandle->query($queryCmd);
        //echo $queryCmd;
        $count = 0;
        $finalData = array();
        $finalDataRev = array();
        foreach ($query->result() as $row){ 
            if($finalDataRev[$row->displayname][0] != "1") {
                $finalData[$count] = array();
                $finalData[$count]['id'] = $row->userId;
                $finalData[$count]['dname'] = $row->displayname;
                $finalData[$count]['name'] = $row->firstname." ".$row->lastname;
                $finalData[$count]['city'] = $row->city_name;
                $finalData[$count]['productId'] = $row->productId;
                $finalData[$count]['product'] = $row->productName;
                $finalData[$count]['points'] = $row->points;
                $finalData[$count]['email'] = $row->email;
                $finalData[$count]['mobile'] = $row->mobile;
                $finalDataRev[$row->displayname] = array();
                $finalDataRev[$row->displayname][0] = "1";
                $finalDataRev[$row->displayname][1] = $count;
                $count++;
            }else {
                $finalData[$finalDataRev[$row->displayname][1]]['points'] += $row->points;
                $finalData[$finalDataRev[$row->displayname][1]]['productId'] .= ",".$row->productId;
            }
        }
        $finalData = $this->sortArray($finalData, "points");
        echo "<html><body><table border='1'>";
        echo "<tr><th>UserId</th><th>Name</th><th>City</th><th>Email</th><th>Mobile Num</th><th>Question Ids</th><th>Product</th><th>Score</th></tr>";
        for($i = 0; $i < count($finalData); $i++) {
            echo "<tr>";
            echo "<td><a target='_blank' href='https://ask.shiksha.com/messageBoard/MsgBoard/userAskAndAnswer/".$finalData[$i]['dname']."'>";
            echo $finalData[$i]['id'];
            echo "</a></td>";
            echo "<td><a target='_blank' href='https://shiksha.com/getUserProfile/".$finalData[$i]['dname']."'>";
            echo $finalData[$i]['name'];
            echo "</a></td>";
            echo "<td>";
            echo $finalData[$i]['city'];
            echo "</td>";
            echo "<td>";
            echo $finalData[$i]['email'];
            echo "</td>";
            echo "<td>";
            echo $finalData[$i]['mobile'];
            echo "</td>";
            echo "<td>";
            if($finalData[$i]['product'] == "ask") {
                $tempQuestionIdArr = explode(",",$finalData[$i]['productId']);
                for($questionIdCounter = 0; $questionIdCounter < count($tempQuestionIdArr) ; $questionIdCounter++){
                echo "<a href='https://ask.shiksha.com/getTopicDetail/".$tempQuestionIdArr[$questionIdCounter]."/' target='_blank'>";
                echo $tempQuestionIdArr[$questionIdCounter];
                echo "</a>";
                echo ",";
                }
            }
            else{
                echo $finalData[$i]['productId'];
            }
            echo "</td>";
            echo "<td>";
            echo $finalData[$i]['product'];
            echo "</td>";
            echo "<td>";
            echo $finalData[$i]['points'];
            echo "</td>";
            echo "</tr>";
        }
        echo "</body></html>";
    }



    function insertMailerUrl() {
        $url= $this->input->post('url');
        $urlArr = split("[\n\r\t ,]+", $url);
        print_r($urlArr);
        $domain= $this->input->post('domain');
        $dbConfig = array( 'hostname'=>'localhost');
        $this->load->library('logsconfig');
        $this->logsconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);
        for($i = 0 ; $i < count($urlArr); $i++) {
            $queryCmd = "insert into trackMailerUrls values('', NOW(),? ,?) on duplicate key update url=?";
            if($dbHandle->query($queryCmd, array($urlArr[$i], $domain, $urlArr[$i]))) {
            }
        }
        header("Location: https://snapdragon.infoedge.com/payment/newPage/addMailerUrl");
    }


    function addMailerUrl() {
        $queryCmd = "Select * from trackMailerUrls";   
        $dbConfig = array( 'hostname'=>'localhost');
        $this->load->library('logsconfig');
        $this->logsconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);


        $queryUrls = $dbHandle->query($queryCmd);
        $count = 0;
        $finalData = array();
        foreach ($queryUrls->result() as $row){ 
            $finalData[$count] = $row->url;
            $count++;
        }
            
        $this->load->view('payment/addUrlMailer.php',array("queryUrls"=>$finalData));
    }








    function insertLogUrl() {
        $url= $this->input->post('url');
        $urlArr = split("[\n\r\t ,]+", $url);
        print_r($urlArr);
        $domain= $this->input->post('domain');
        $dbConfig = array( 'hostname'=>'localhost');
        $this->load->library('logsconfig');
        $this->logsconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);
        for($i = 0 ; $i < count($urlArr); $i++) {
            $queryCmd = "insert into trackUrls values('', NOW(), ? ,?) on duplicate key update url=?";
            if($dbHandle->query($queryCmd, array($urlArr[$i], $domain, $urlArr[$i]))) {
            }
        }
        header("Location: https://snapdragon.infoedge.com/payment/newPage/addUrl");
    }


    function addUrl() {
        $queryCmd = "Select * from trackUrls";   
        $dbConfig = array( 'hostname'=>'localhost');
        $this->load->library('logsconfig');
        $this->logsconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);


        $queryUrls = $dbHandle->query($queryCmd);
        $count = 0;
        $finalData = array();
        foreach ($queryUrls->result() as $row){ 
            $finalData[$count] = $row->url;
            $count++;
        }
            
        $this->load->view('payment/addUrl.php',array("queryUrls"=>$finalData));
    }

    function getSearchLogs() {
	    $finalOutput = "";
	    set_time_limit(0);
	    $sDate = $_REQUEST['sDate'];
		
		$post_sDate = $this->input->post('sDate');
		$post_eDate = $this->input->post('eDate');
		
        if(strlen(trim($post_sDate))> 0)
        {
            if(strlen(trim($post_eDate)) < 12) {
                $eDate = $post_eDate." 23:59:59";
            }else {
                $eDate = $post_eDate;
            }
        }
        else
        {
            $eDate = $post_sDate." 23:59:59";
        }
	    $dbConfig = array( 'hostname'=>'localhost');
	    $this->load->library('logsconfig');
	    $this->logsconfig->getDbConfig($appId,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);
    }

    function exportCsv($sDate,$eDate,$regexArr,$domain) {
    	return false;
    	$this->mailAlert('exportCsv');
        header("Content-type: text/x-csv");
        $filename =preg_replace('/[^A-Za-z0-9]/', '',$sDate.$eDate.$regex);
        header("Content-Disposition: attachment; filename=".$filename.".csv");

        $finalOutput = "";
        $dbConfig = array( 'hostname'=>'localhost');
        $this->load->library('logsconfig');
        $this->logsconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);
        $phpSessionFirstPage = array();
        $print = array();
        $iter = 0;
        $phpSessionLoggedInArr = array();
        $k=0;
        $rowsGot = 0;
        if($domain == "") {
            $queryCmd = "Select * from trackUrls";
        }else {
            if($domain == "Not Specified") {
                $domain = "";
            }
            $queryCmd = "Select * from trackUrls where domain=".$dbHandle->escape($domain);
        }
        $queryUrls = $dbHandle->query($queryCmd);
        $totalPageViews = 0;

        foreach ($queryUrls->result() as $row){
            $url = trim($row->url);
            $domain = $row->domain;
            if($url == ""){
                continue;
            }

            $regexFlag = 1;
            for($i = 0; $i < count($regexArr); $i++) {
                preg_match('/'.trim($regexArr[$i]).'/', $url, $matches, PREG_OFFSET_CAPTURE);
                if(count($matches) <= 0) {
                    $regexFlag = 0;
                    break;
                }
            }
            if($regexFlag != 0) {
                $dbConfig = array( 'hostname'=>'localhost');
                $this->load->library('logsconfig');
                $this->logsconfig->getDbConfig($appId,$dbConfig);
                $dbHandle = $this->load->database($dbConfig,TRUE);
                $finalOutput .= $url."<br/>,";
                $urlGroup = array();
                $listingPageViews = 0;
                $becameLeads = 0;
                $searched = 0;
                $queryCmd = "select distinct(session) from logTracking where url=? and time > ? and time < ?"; 
                $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
                $k= 0;
                $sessionArr = array();
                foreach ($query->result() as $row){
                    $sessionArr[$k] = "'".$row->session."'";
                    $k++;
                }
                if(count($sessionArr) == 0) {
                    $finalOutput .= ",,,,,\n";
                    continue;
                }
                $sessionStr = implode(",",$sessionArr);



                $queryCmd = "Select count(distinct(session)) as totalCount from logTracking where url=? and time > ? and time < ?";
                $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
                foreach ($query->result() as $row){
                    $totalCountUrl = $row->totalCount;
                    $finalOutput .= "Total PageViews Distinct Users = ".$row->totalCount."<br/>";
                    //echo "Total PageViews = ".$row->totalCount."<br/>";
//                    $totalPageViews = $totalPageViews + $row->totalCount;
                }
                $queryCmd = "Select count(*) as totalCount from logTracking where url=? and time > ? and time < ?"; 
                $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
                foreach ($query->result() as $row){
                    $totalCountUrl = $row->totalCount;
                    $finalOutput .= "Total PageViews = ".$row->totalCount."<br/>";
                    //echo "Total PageViews = ".$row->totalCount."<br/>";
                    $totalPageViews = $totalPageViews + $row->totalCount;
                }
                $finalOutput .= ",";


                $queryCmd = "select count(*) as countUser  from logTracking where session in ($sessionStr) and time > ? and time < ? group by session";
                $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
                $totalCount = 0;
                foreach ($query->result() as $row){
                    if($row->countUser >= 2) {
                        $totalCount++;
                    }
                }
                $finalOutput .= "Unique Users To next page = ".$totalCount."<br/>";
                $finalOutput .= ",";
                $queryCmd = "Select * from logTracking where session in ($sessionStr) and time > ? and time < ?"; 
                $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
                $visitedRegistrationPage = array();
                $didRegistrationPage = array();
                foreach ($query->result() as $row){
                    $url = $row->url;
                    $pos = strripos($row->cookie,"[user]");
                    $pos = strripos($url,"Userregistration/submit");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }
                    $pos = strripos($url,"userfromhome");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }
                    $pos = strripos($url,"Userregistration/quicksignup");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }
                    $pos = strripos($url,"Listing/requestInfo");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }
                    $lastPageArr[$row->session]++;
                }
                $queryCmd = "Select * from logTracking where session in ($sessionStr) and time > ? and time < ?";
                $query = $dbHandle->query($queryCmd, array($sDate, $eDate));

                foreach ($query->result() as $row){
                    if($visitedRegistrationPage[$row->session] == "1") {
                        $cookie = $row->cookie;
                        $pos = strripos($cookie,"[user]");
                        if($pos === false) {
                        }else {
                            if(!isset($didRegistrationPage[$row->session])) {
                                $didRegistrationPage[$row->session] = "1";
                                $didRegistrationPhpSessionBanner[$row->session] = "1";
                                $didRegistrationBannerCookie[$row->session] = $row->cookie;
                            }
                        }
                    }

                }

                $finalOutput .= "<br/>Tried Registering = ".count($visitedRegistrationPage);
                //echo "<br/>Tried Registering = ".count($visitedRegistrationPage);
                $finalOutput .= ",";

                //echo "</td><td>";

                $totalTriedReg = $totalTriedReg + count($visitedRegistrationPage);
                $finalOutput .= "<br/>Did Registration(full+request) = ".count($didRegistrationPage);
                //echo "<br/>Did Registration(full+request) = ".count($didRegistrationPage);
                $finalOutput .= ",";

                $queryCmd = "Select url from logTracking where session in ($sessionStr) and time > ? and time < ?"; 
                $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
                $numRows = 0;
                $countPages = 0;
                foreach ($query->result() as $row){
                    $countPages++;
                    $hostName = preg_replace('/(https:\/\/[^\/]*)\/.*/', '$1', $row->url);
                    if(isset($urlGroup[$hostName])) {
                        $urlGroup[$hostName]++;
                    }else {
                        $urlGroup[$hostName] = 1;
                    }
                    preg_match('/getListingDetail/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                    if(count($matches) > 0) {
                        $listingPageViews++;
                    }
                    preg_match('/Listing\/requestInfo/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                    if(count($matches) > 0) {
                        $becameLeads++;
                    }
                    preg_match('/search\/index/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                    if(count($matches) > 0) {
                        $searched++;
                    }

                }
                foreach($urlGroup as $key=>$val) {
                    $finalOutput .= $key." ".$urlGroup[$key]."<br/>";


                    //echo $key." ".$urlGroup[$key]."<br/>";

                }
                $finalOutput .= ",";
                $finalOutput .= "Number of Listing Viewed: ".$listingPageViews."<br/>Number of Leads Formed: ".$becameLeads."<br/>Number of searches made".$searched."<br/>";
                $finalOutput .= ",";
                $finalOutput .= $domain;
                $finalOutput .= "\n";
                $totalDidReg = $totalDidReg + count($didRegistrationPage);
            }
        }

        echo $finalOutput;


    }


    function marketingUsersCsv($url, $sDate, $eDate) {
    	return false;
    	$this->mailAlert('marketingUsersCsv');
        $finalOutput = "";
        $url = base64_decode($url);
        $sDate = base64_decode($sDate);
        $eDate = base64_decode($eDate);
        header("Content-type: text/x-csv");
        $filename =preg_replace('/[^A-Za-z0-9]/', '',$sDate.$eDate.$url);
        header("Content-Disposition: attachment; filename=".$filename.".csv");
        $queryCmd = "select distinct(session) from logTracking where url=? and time > ? and time < ?"; 
        $dbConfig = array( 'hostname'=>'localhost');
        $this->load->library('logsconfig');
        $this->logsconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);

        $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
        $k= 0;
        $sessionArr = array();
        foreach ($query->result() as $row){
            $sessionArr[$k] = "'".$row->session."'";
            $k++;
        }
        $sessionArrStr = implode(",",$sessionArr);
        $queryCmd = "select distinct(substring_index(substring_index(cookie,'[user] => ',-1),'|',1)) as email from logTracking where cookie like '%[user] => %' and session in ($sessionArrStr) and time > ? and time < ?"; 
        $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
        $emailsArr = array();
        $k= 0;
        foreach ($query->result() as $row){
            $emailsArr[$k] = "'".$row->email."'";
            $k++;
        }
        $emailsArrStr = implode(",",$emailsArr);
        $queryCmd = "select shiksha.tuser.displayname as dname, shiksha.categoryBoardTable.name as category, DATEDIFF(CURDATE(), shiksha.tuser.dateofbirth) as age, shiksha.countryCityTable.city_name as city,shiksha.tuser.mobile,shiksha.tuser.email, shiksha.tuserInterest.keyValue as probCategory from shiksha.tuser left join shiksha.tuserInterest on (shiksha.tuserInterest.userId=shiksha.tuser.userid) left join shiksha.categoryBoardTable on (shiksha.categoryBoardTable.boardId=shiksha.tuserInterest.keyValue), shiksha.countryCityTable  where email in ($emailsArrStr)  and shiksha.tuserInterest.keyofInterest='category'and shiksha.countryCityTable.city_id=shiksha.tuser.city";

        $query = $dbHandle->query($queryCmd);
        $finalData = array();
        $count = 0;
        $finalOutput .= "NAME,Category,City,Age,Mobile,Email\n";
        foreach ($query->result() as $row){ 
                $finalData[$count] = array();
                if(!is_numeric($row->probCategory)) {
                    $finalData[$count]['category'] = $row->probCategory;
                }
                $finalData[$count]['dname'] = str_replace(",","",$row->dname);
                $finalData[$count]['category'] .= str_replace(",","",$row->category);
                $finalData[$count]['city'] = str_replace(",","",$row->city);
                $finalData[$count]['age'] = str_replace(",","",(int)($row->age/365));
                $finalData[$count]['mobile'] = str_replace(",","",$row->mobile);
                $finalData[$count]['email'] = str_replace(",","",$row->email);
                $finalOutput .= "<a href='https://shiksha.com/getUserProfile/".$finalData[$count]['dname']."'>".$finalData[$count]['dname']."</a>,".$finalData[$count]['category'].",".$finalData[$count]['city'].",".$finalData[$count]['age'].",".$finalData[$count]['mobile'].",".$finalData[$count]['email']."\n";
                $count++;

        }
        echo $finalOutput;
    }

    

    function marketingUsers($url, $sDate, $eDate) {
    	return false;
    	$this->mailAlert('marketingUsers');
        $finalOutput .= "<form method='GET' action='/payment/newPage/marketingUsersCsv/".$url."/".$sDate."/".$eDate."'><input type='submit' value='Export As Csv'/></form><table border='1'>";
        $url = base64_decode($url);
        $sDate = base64_decode($sDate);
        $eDate = base64_decode($eDate);
        $queryCmd = "select distinct(session) from logTracking where url=? and time > ? and time < ?"; 
        $dbConfig = array( 'hostname'=>'localhost');
        $this->load->library('logsconfig');
        $this->logsconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);

        $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
        $k= 0;
        $sessionArr = array();
        foreach ($query->result() as $row){
            $sessionArr[$k] = "'".$row->session."'";
            $k++;
        }
        $sessionArrStr = implode(",",$sessionArr);
        $queryCmd = "select distinct(substring_index(substring_index(cookie,'[user] => ',-1),'|',1)) as email from logTracking where cookie like '%[user] => %' and session in ($sessionArrStr) and time > ? and time < ?"; 
        $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
        $emailsArr = array();
        $k= 0;
        foreach ($query->result() as $row){
            $emailsArr[$k] = "'".$row->email."'";
            $k++;
        }
        $emailsArrStr = implode(",",$emailsArr);
        $queryCmd = "select shiksha.tuser.displayname as dname, shiksha.categoryBoardTable.name as category, DATEDIFF(CURDATE(), shiksha.tuser.dateofbirth) as age, shiksha.countryCityTable.city_name as city,shiksha.tuser.mobile,shiksha.tuser.email, shiksha.tuserInterest.keyValue as probCategory  from shiksha.tuser left join shiksha.tuserInterest on (shiksha.tuserInterest.userId=shiksha.tuser.userid) left join shiksha.categoryBoardTable on (shiksha.categoryBoardTable.boardId=shiksha.tuserInterest.keyValue), shiksha.countryCityTable  where email in ($emailsArrStr)  and shiksha.tuserInterest.keyofInterest='category'and shiksha.countryCityTable.city_id=shiksha.tuser.city";

        $query = $dbHandle->query($queryCmd);
        $finalData = array();
        $count = 0;
        $finalOutput .= "<table border='1'>";
        $finalOutput .= "<tr><th>NAME</th><th>Category</th><th>City</th><th>Age</th><th>Mobile</th><th>Email</th></tr>";
        foreach ($query->result() as $row){ 
                $finalData[$count] = array();

                if(!is_numeric($row->probCategory)) {
                    $finalData[$count]['category'] = $row->probCategory;
                }
                $finalData[$count]['dname'] = $row->dname;
                $finalData[$count]['category'] .= $row->category;
                $finalData[$count]['city'] = $row->city;
                $finalData[$count]['age'] = (int)($row->age/365);
                $finalData[$count]['mobile'] = $row->mobile;
                $finalData[$count]['email'] = $row->email;
                $finalOutput .= "<tr><tr><td><a href='https://shiksha.com/getUserProfile/".$finalData[$count]['dname']."'>".$finalData[$count]['dname']."</a></td>"."<td>".$finalData[$count]['category']."</td>"."<td>".$finalData[$count]['city']."</td>"."<td>".$finalData[$count]['age']."</td>"."<td>".$finalData[$count]['mobile']."</td>"."<td>".$finalData[$count]['email']."</td>"."</tr>";
                $count++;

        }
        $finalOutput .= "</table>";
        echo $finalOutput;
        
    }
    function allMarketingUsersCSV($sDate="", $eDate="") {
    	return false;
    	$this->mailAlert('allMarketingUsersCSV');
        $queryCmd = "Select * from trackUrls";
        $dbConfig = array( 'hostname'=>'localhost');
        $this->load->library('logsconfig');
        $this->logsconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);
        $queryUrls = $dbHandle->query($queryCmd);
        $totalPageViews = 0;
        if($sDate == "")
        {
            $sDate = '2009-03-01';
        }
        if($eDate == "")
        {
            $eDate = '2009-04-01';
        }
        header("Content-type: text/x-csv");
        $filename =preg_replace('/[^A-Za-z0-9]/', '',$sDate.$eDate);
        header("Content-Disposition: attachment; filename=".$filename.".csv");
        echo "NAME,Category,City,Age,Mobile,Email,Url,Domain\n";
        foreach ($queryUrls->result() as $row){
            $url = trim($row->url);
            $domain = trim($row->domain);
            $queryCmd = "select distinct(session) from logTracking where url=? and time > ? and time < ?"; 
            $dbConfig = array( 'hostname'=>'localhost');
            $this->load->library('logsconfig');
            $this->logsconfig->getDbConfig($appId,$dbConfig);
            $dbHandle = $this->load->database($dbConfig,TRUE);

            $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
            $k= 0;
            $j= 0;
            $sessionArr = array();
            $resultCount = count($query->result());
            foreach ($query->result() as $row){
                $sessionArr[$k] = "'".$row->session."'";
                $k++;
                $j++;
                if($k > 999 || $j == $resultCount)
                {
                    if(count($sessionArr) > 0)
                    {
                        $sessionArrStr = implode(",",$sessionArr);
                        $queryCmd = "select distinct(substring_index(substring_index(cookie,'[user] => ',-1),'|',1)) as email from logTracking where cookie like '%[user] => %' and session in ($sessionArrStr) and time > ? and time < ?"; 
                        $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
                        $emailsArr = array();
                        $k= 0;
                        foreach ($query->result() as $row){
                            $emailsArr[$k] = "'".$row->email."'";
                            $k++;
                        }
                        if(count($emailsArr) > 0)
                        {
                            $emailsArrStr = implode(",",$emailsArr);
                            $queryCmd = "select shiksha.tuser.displayname as dname, shiksha.categoryBoardTable.name as category, DATEDIFF(CURDATE(), shiksha.tuser.dateofbirth) as age, shiksha.countryCityTable.city_name as city,shiksha.tuser.mobile,shiksha.tuser.email, shiksha.tuserInterest.keyValue as probCategory  from shiksha.tuser left join shiksha.tuserInterest on (shiksha.tuserInterest.userId=shiksha.tuser.userid) left join shiksha.categoryBoardTable on (shiksha.categoryBoardTable.boardId=shiksha.tuserInterest.keyValue), shiksha.countryCityTable  where email in ($emailsArrStr)  and shiksha.tuserInterest.keyofInterest='category'and shiksha.countryCityTable.city_id=shiksha.tuser.city";

                            $query = $dbHandle->query($queryCmd);
                            $finalData = array();
                            $count = 0;
                            foreach ($query->result() as $row){ 
                                $finalData[$count] = array();

                                if(!is_numeric($row->probCategory)) {
                                    $finalData[$count]['category'] = $row->probCategory;
                                }
                                $finalData[$count]['dname'] = $row->dname;
                                $finalData[$count]['category'] .= $row->category;
                                $finalData[$count]['city'] = $row->city;
                                $finalData[$count]['age'] = (int)($row->age/365);
                                $finalData[$count]['mobile'] = $row->mobile;
                                $finalData[$count]['email'] = $row->email;
                                echo "<a href='https://shiksha.com/getUserProfile/".$finalData[$count]['dname']."'>".$finalData[$count]['dname']."</a>,".$finalData[$count]['category'].",".$finalData[$count]['city'].",".$finalData[$count]['age'].",".$finalData[$count]['mobile'].",".$finalData[$count]['email'].",".$url.",".$domain."\n";
                                $count++;
                            }
                        }
                    }
                    $k=0;
                }
            }
        }

    }

    function allMarketingUsers($sDate="", $eDate="") {
    	return false;
    	$this->mailAlert('allMarketingUsers');
        $queryCmd = "Select * from trackUrls";
        $dbConfig = array( 'hostname'=>'localhost');
        $this->load->library('logsconfig');
        $this->logsconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);
        $queryUrls = $dbHandle->query($queryCmd);
        $totalPageViews = 0;
        if($sDate == "")
        {
            $sDate = '2009-03-01';
        }
        if($eDate == "")
        {
            $eDate = '2009-04-01';
        }
        echo "<table border='1'>";
        echo "<tr><th>NAME</th><th>Category</th><th>City</th><th>Age</th><th>Mobile</th><th>Email</th><th>Url</th><th>Domain</th></tr>";
        foreach ($queryUrls->result() as $row){
            $url = trim($row->url);
            $domain = trim($row->domain);
            $queryCmd = "select distinct(session), cookie from logTracking where url=? and time > ? and time < ? order by time"; 
            $dbConfig = array( 'hostname'=>'localhost');
            $this->load->library('logsconfig');
            $this->logsconfig->getDbConfig($appId,$dbConfig);
            $dbHandle = $this->load->database($dbConfig,TRUE);

            $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
            $k= 0;
            $j= 0;
            $sessionArr = array();
            $resultCount = count($query->result());
            foreach ($query->result() as $row){
                $sessionArr[$k] = "'".$row->session."'";
                $k++;
                $j++;
                if($k > 999 || $j == $resultCount)
                {
                    if(count($sessionArr) > 0)
                    {
                        $sessionArrStr = implode(",",$sessionArr);
                        $queryCmd = "select distinct(substring_index(substring_index(cookie,'[user] => ',-1),'|',1)) as email from logTracking where cookie like '%[user] => %' and session in ($sessionArrStr) and time > ? and time < ?"; 
                        $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
                        $emailsArr = array();
                        $k= 0;
                        foreach ($query->result() as $row){
                            $emailsArr[$k] = "'".$row->email."'";
                            $k++;
                        }
                        if(count($emailsArr) > 0)
                        {
                            $emailsArrStr = implode(",",$emailsArr);
                            $queryCmd = "select shiksha.tuser.displayname as dname, shiksha.categoryBoardTable.name as category, DATEDIFF(CURDATE(), shiksha.tuser.dateofbirth) as age, shiksha.countryCityTable.city_name as city,shiksha.tuser.mobile,shiksha.tuser.email, shiksha.tuserInterest.keyValue as probCategory  from shiksha.tuser left join shiksha.tuserInterest on (shiksha.tuserInterest.userId=shiksha.tuser.userid) left join shiksha.categoryBoardTable on (shiksha.categoryBoardTable.boardId=shiksha.tuserInterest.keyValue), shiksha.countryCityTable  where email in ($emailsArrStr)  and shiksha.tuserInterest.keyofInterest='category'and shiksha.countryCityTable.city_id=shiksha.tuser.city";

                            $query = $dbHandle->query($queryCmd);
                            $finalData = array();
                            $count = 0;
                            foreach ($query->result() as $row){ 
                                $finalData[$count] = array();

                                if(!is_numeric($row->probCategory)) {
                                    $finalData[$count]['category'] = $row->probCategory;
                                }
                                $finalData[$count]['dname'] = $row->dname;
                                $finalData[$count]['category'] .= $row->category;
                                $finalData[$count]['city'] = $row->city;
                                $finalData[$count]['age'] = (int)($row->age/365);
                                $finalData[$count]['mobile'] = $row->mobile;
                                $finalData[$count]['email'] = $row->email;
                                echo "<tr><tr><td><a href='https://shiksha.com/getUserProfile/".$finalData[$count]['dname']."'>".$finalData[$count]['dname']."</a></td>"."<td>".$finalData[$count]['category']."</td>"."<td>".$finalData[$count]['city']."</td>"."<td>".$finalData[$count]['age']."</td>"."<td>".$finalData[$count]['mobile']."</td>"."<td>".$finalData[$count]['email']."</td>"."<td>".$url."</td>"."<td>".$domain."</td>"."</tr>";
                                $count++;
                            }
                        }
                    }
                    $k=0;
                }
            }
        }
        echo "</table>";

    }



    function getLogMis() {
    	return false;
    	$this->mailAlert('getLogMis');
        error_log("TYUI ");
        $finalOutput = "";

        set_time_limit(0);
        $sDate = $this->input->post('sDate');
		$eDate = $this->input->post('eDate');
        if(strlen(trim($eDate)) < 12) {
            $eDate = $eDate." 23:59:59";
        }else {
            $eDate = $eDate;
        }
        $regex = $this->input->post('urlregex');
        $regexArr = split(",",$regex);
        $domain = $this->input->post('domain');
        if($this->input->post('export_csv') == "1") {
            $this->exportCsv($sDate,$eDate,$regexArr,$domain);
            exit(0);
        }
        $dbConfig = array( 'hostname'=>'localhost');
        $this->load->library('logsconfig');
        $this->logsconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);
        $phpSessionFirstPage = array();
        $print = array();
        $iter = 0;
        $phpSessionLoggedInArr = array();
        $k=0;
        $rowsGot = 0;
        if($domain == "") {
            $queryCmd = "Select * from trackUrls";
        }else {
            if($domain == "Not Specified") {
                $domain = "";
            }
            $queryCmd = "Select * from trackUrls where domain=".$dbHandle->escape($domain);
        }
        $queryUrls = $dbHandle->query($queryCmd);
        $totalPageViews = 0;
        $finalOutput .= "<table border='1'>";

        foreach ($queryUrls->result() as $row){
            $url = trim($row->url);
            $finalUrls = $url;
            $domain = $row->domain;
            if($url == ""){
                continue;
            }
            $regexFlag = 1;
            for($i = 0; $i < count($regexArr); $i++) {
                preg_match('/'.trim($regexArr[$i]).'/', $url, $matches, PREG_OFFSET_CAPTURE);
                if(count($matches) <= 0) {
                    $regexFlag = 0;
                    break;
                }
            }
            if($regexFlag != 0) {
                $dbConfig = array( 'hostname'=>'localhost');
                $this->load->library('logsconfig');
                $this->logsconfig->getDbConfig($appId,$dbConfig);
                $dbHandle = $this->load->database($dbConfig,TRUE);
                $finalOutput .= "<tr><td>";
                $finalOutput .= $url."<br/></td><td>";
                $urlGroup = array();
                $listingPageViews = 0;
                $becameLeads = 0;
                $searched = 0;
                $queryCmd = "select distinct(session) from logTracking where url=? and time > ? and time < ?"; 
                $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
                $k= 0;
                $sessionArr = array();
                foreach ($query->result() as $row){
                    $sessionArr[$k] = "'".$row->session."'";
                    $k++;
                }
                if(count($sessionArr) == 0) {
                    $finalOutput .= "</td></tr>";
                    continue;
                }
                $sessionStr = implode(",",$sessionArr);



                $queryCmd = "Select count(distinct(session)) as totalCount from logTracking where url=? and time > ? and time < ?";
                $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
                foreach ($query->result() as $row){
                    $totalCountUrl = $row->totalCount;
                    $finalOutput .= "Total PageViews Distinct Users = ".$row->totalCount."<br/>";
                    //echo "Total PageViews = ".$row->totalCount."<br/>";
//                    $totalPageViews = $totalPageViews + $row->totalCount;
                }
                $queryCmd = "Select count(*) as totalCount from logTracking where url=? and time > ? and time < ?"; 
                $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
                foreach ($query->result() as $row){
                    $totalCountUrl = $row->totalCount;
                    $finalOutput .= "Total PageViews = ".$row->totalCount."<br/>";
                    //echo "Total PageViews = ".$row->totalCount."<br/>";
                    $totalPageViews = $totalPageViews + $row->totalCount;
                }
                $finalOutput .= "</td><td>";


                $queryCmd = "select count(*) as countUser  from logTracking where session in ($sessionStr) and time > ? and time < ? group by session";
                $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
                $totalCount = 0;
                foreach ($query->result() as $row){
                    if($row->countUser >= 2) {
                        $totalCount++;
                    }
                }
                $finalOutput .= "Unique Users To next page = ".$totalCount."<br/>";
                $finalOutput .= "</td><td>";
                $queryCmd = "Select * from logTracking where session in ($sessionStr) and time > ? and time < ?"; 
                $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
                $visitedRegistrationPage = array();
                $didRegistrationPage = array();
                foreach ($query->result() as $row){
                    $url = $row->url;
                    $pos = strripos($row->cookie,"[user]");
                    $pos = strripos($url,"Userregistration/submit");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }
                    $pos = strripos($url,"userfromhome");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }
                    $pos = strripos($url,"Userregistration/quicksignup");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }
                    error_log("TYUI ".$url);
                    $pos = strripos($url,"Userregistration/userfromMarketingPage");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }

                    $pos = strripos($url,"Listing/requestInfo");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }
                    $lastPageArr[$row->session]++;
                }
                $queryCmd = "Select * from logTracking where session in ($sessionStr) and time > ? and time < ?";
                $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));

                foreach ($query->result() as $row){
                    if($visitedRegistrationPage[$row->session] == "1") {
                        $cookie = $row->cookie;
                        $pos = strripos($cookie,"[user]");
                        if($pos === false) {
                        }else {
                            if(!isset($didRegistrationPage[$row->session])) {
                                $didRegistrationPage[$row->session] = "1";
                                $didRegistrationPhpSessionBanner[$row->session] = "1";
                                $didRegistrationBannerCookie[$row->session] = $row->cookie;
                            }
                        }
                    }

                }

                $finalOutput .= "<br/>Tried Registering = ".count($visitedRegistrationPage);

                //echo "<br/>Tried Registering = ".count($visitedRegistrationPage);
                $finalOutput .= "</td><td>";

                //echo "</td><td>";

                $totalTriedReg = $totalTriedReg + count($visitedRegistrationPage);
                $finalOutput .= "<br/>Did Registration(full+request) = ".count($didRegistrationPage);
                //echo "<br/>Did Registration(full+request) = ".count($didRegistrationPage);
                $finalUrls = base64_encode($finalUrls);
                $finalSDate = base64_encode($sDate);
                $finalEDate = base64_encode($eDate);
                $finalOutput .= "<br/><a href='https://snapdragon.infoedge.com/payment/newPage/marketingUsers/$finalUrls/$finalSDate/$finalEDate' target='_blank'>Get Users</a></td><td>";
                $finalOutput .= "</td><td>";

                $queryCmd = "Select url from logTracking where session in ($sessionStr) and time > ? and time < ?"; 
                $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
                $numRows = 0;
                $countPages = 0;
                foreach ($query->result() as $row){
                    $countPages++;
                    $hostName = preg_replace('/(https:\/\/[^\/]*)\/.*/', '$1', $row->url);
                    if(isset($urlGroup[$hostName])) {
                        $urlGroup[$hostName]++;
                    }else {
                        $urlGroup[$hostName] = 1;
                    }
                    preg_match('/getListingDetail/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                    if(count($matches) > 0) {
                        $listingPageViews++;
                    }
                    preg_match('/Listing\/requestInfo/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                    if(count($matches) > 0) {
                        $becameLeads++;
                    }
                    preg_match('/search\/index/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                    if(count($matches) > 0) {
                        $searched++;
                    }

                }
                foreach($urlGroup as $key=>$val) {
                    $finalOutput .= $key." ".$urlGroup[$key]."<br/>";


                    //echo $key." ".$urlGroup[$key]."<br/>";

                }
                $finalOutput .= "</td><td>";
                $finalOutput .= "Number of Listing Viewed: ".$listingPageViews."<br/>Number of Leads Formed: ".$becameLeads."<br/>Number of searches made".$searched."<br/>";
                $finalOutput .= "</td><td>";
                $finalOutput .= $domain;
                $finalOutput .= "</td></tr>";
                $totalDidReg = $totalDidReg + count($didRegistrationPage);
            }
        }
        $finalOutput .= "</table>";
        $finalOutput .= "Total = ".$totalPageViews." Page Views   ".$totalTriedReg." Registration attempts    ".$totalDidReg." Sucessful Registration ";

        echo $finalOutput;
    }



    function trackLogRequests() {
    	return false;
    	$this->mailAlert('trackLogRequests');
	    $finalOutput = "";
	    set_time_limit(0);
	    $sDate = $this->input->post('sDate');
		$eDate = $this->input->post('eDate');
	    if(strlen(trim($eDate)) < 12) {
		    $eDate = $eDate." 23:59:59";
	    }else {
		    $eDate = $eDate;
	    }
	    $dbConfig = array( 'hostname'=>'localhost');
	    $this->load->library('logsconfig');
	    $this->logsconfig->getDbConfig($appId,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);
	    $phpSessionFirstPage = array();
	    $print = array();
	    $iter = 0;
	    $phpSessionLoggedInArr = array();
	    $k=0;
	    $rowsGot = 0;
 //       $queryCmd = "Select * from trackUrls";
 //       $queryUrls = $dbHandle->query($queryCmd);

//        foreach ($queryUrls->result() as $row){
//		    $url = trim($row->url);
//            echo $url."\n";
//		    if($url == ""){
//			    continue;
//		    }
            $queryCmd = "Select url from trackUrls";
            echo $queryCmd;
            $urls = $dbHandle->query($queryCmd);
            $urlArray = array();
            $count =0 ;
            foreach ($urls->result() as $row){
                $url = trim($row->url);
                $urlArray[$count] = "'".$url."'";
                $count++;
            }
            $finalUrls = implode(",",$urlArray);
            echo $finalUrls;

            $queryCmd = "Select distinct(session) as tracker from log1 where url in ($finalUrls) and time > ? and time < ?";
            echo $queryCmd;
            $sessions = $dbHandle->query($queryCmd, array($sDate, $eDate));
            $sessionArray = array();
            $count =0 ;
            foreach ($sessions->result() as $row){
                $session = trim($row->tracker);
                $sessionArray[$count] = "'".$session."'";
                $count++;
            }
            echo "<br/>".$count."<br/>";
            $finalSessions = implode(",",$sessionArray);
            echo $finalSessions;


            $queryCmd = "insert into logTracking (select * from log1 where session in ($finalSessions) and time > ? and time < ?) on duplicate key update url=logTracking.url";
            echo $queryCmd;
            $queryUrls = $dbHandle->query($queryCmd, array($sDate, $eDate));






           $queryCmd = "Select distinct(session) as tracker from log9 where url in ($finalUrls) and time > ? and time < ?";
            echo $queryCmd;
            $sessions = $dbHandle->query($queryCmd, array($sDate, $eDate));
            $sessionArray = array();
            $count =0 ;
            foreach ($sessions->result() as $row){
                $session = trim($row->tracker);
                $sessionArray[$count] = "'".$session."'";
                $count++;
            }
            echo "<br/>".$count."<br/>";
            $finalSessions = implode(",",$sessionArray);
            echo $finalSessions;


            $queryCmd = "insert into logTracking (select * from log9 where session in ($finalSessions) and time > ? and time < ?) on duplicate key update url=logTracking.url";
            echo $queryCmd;
            $queryUrls = $dbHandle->query($queryCmd, array($sDate, $eDate));







//	    }
    }




    function trackMailerRequests() {
	    $finalOutput = "";
	    set_time_limit(0);
	    $sDate = $this->input->post('sDate');
		$eDate = $this->input->post('eDate');
	    if(strlen(trim($eDate)) < 12) {
		    $eDate = $eDate." 23:59:59";
	    }else {
		    $eDate = $eDate;
	    }
	    $dbConfig = array( 'hostname'=>'localhost');
	    $this->load->library('logsconfig');
	    $this->logsconfig->getDbConfig($appId,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);
	    $phpSessionFirstPage = array();
	    $print = array();
	    $iter = 0;
	    $phpSessionLoggedInArr = array();
	    $k=0;
	    $rowsGot = 0;
        $queryCmd = "Select * from trackMailerUrls";
        $queryUrls = $dbHandle->query($queryCmd);

        foreach ($queryUrls->result() as $row){
		    $url = trim($row->url);
            echo $url."\n";
		    if($url == ""){
			    continue;
		    }
            $queryCmd = "insert into mailerTracking (select * from log1 where session in (Select distinct(session) as tracker from log1 where url=? and time > ? and time < ?) and time > ? and time < ?) on duplicate key update url=?";
            $queryUrls = $dbHandle->query($queryCmd, array($url, $sDate, $eDate, $sDate, $eDate, $url));
	    }
    }


    function getMailerMis() {
	    $finalOutput = "";
	    set_time_limit(0);
        $url = $this->input->post('url');
	    $dbConfig = array( 'hostname'=>'localhost');
	    $this->load->library('logsconfig');
	    $this->logsconfig->getDbConfig($appId,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);


        $queryCmd = "Select url from mailerTracking where session in (Select session from mailerTracking where url=?)";
        //echo $queryCmd;
        //echo $queryCmd;
        $query = $dbHandle->query($queryCmd, array($url));
        $numRows = 0;
        $countPages = 0;
        $listingPageViews = 0;
        $becameLeads = 0;
        $searched = 0;
        foreach ($query->result() as $row){
            $countPages++;
            $hostName = preg_replace('/(https:\/\/[^\/]*)\/.*/', '$1', $row->url);
            preg_match('/getListingDetail/', $row->url, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                $listingPageViews++;
            }
            preg_match('/Listing\/requestInfo/', $row->url, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                $becameLeads++;
            }
            preg_match('/search\/index/', $row->url, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                $searched++;
            }

        }
        //            echo $countPages;
        preg_match('/getListingDetail/', $url, $matches, PREG_OFFSET_CAPTURE);
        if(count($matches) > 0) {
            $listingPageViews += $totalCountUrl;
        }
        preg_match('/Listing\/requestInfo/', $url, $matches, PREG_OFFSET_CAPTURE);
        if(count($matches) > 0) {
            $becameLeads += $totalCountUrl;
        }
        preg_match('/search\/index/', $url, $matches, PREG_OFFSET_CAPTURE);
        if(count($matches) > 0) {
            $searched += $totalCountUrl;
        }










	    $phpSessionFirstPage = array();
	    $print = array();
	    $iter = 0;
	    $phpSessionLoggedInArr = array();
	    $k=0;
	    $rowsGot = 0;
        $queryCmd = "Select * from trackMailerUrls";
        $queryUrls = $dbHandle->query($queryCmd);
        foreach ($queryUrls->result() as $row){
		    $url = trim($row->url);
            echo $url."\n";
		    if($url == ""){
			    continue;
		    }
            $queryCmd = "insert into mailerTracking (select * from log1 where session in (Select distinct(session) as tracker from log1 where url=? and time > ? and time < ?) and time > ? and time < ?) on duplicate key update url=?";
            $queryUrls = $dbHandle->query($queryCmd, array($url, $sDate, $eDate, $sDate, $eDate, $url));
	    }
    }





    function getLogs() {
    	return false;
    	$this->mailAlert('getLogs');
	    $finalOutput = "";
	    set_time_limit(0);
	    //$sDate = '2008-10-01';
	    //$eDate = '2008-11-30';
	    $sDate = $this->input->post('sDate');
		$eDate = $this->input->post('eDate');
	    if(strlen(trim($eDate)) < 12) {
		    $eDate = $eDate." 23:59:59";
	    }else {
		    $eDate = $eDate;
	    }
	    $dbConfig = array( 'hostname'=>'localhost');
	    $this->load->library('logsconfig');
	    $this->logsconfig->getDbConfig($appId,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);
	    $phpSessionFirstPage = array();
	    $print = array();
	    $iter = 0;
	    $phpSessionLoggedInArr = array();
	    $k=0;
	    $rowsGot = 0;
        $queryCmd = "Select * from trackUrls";
        $queryUrls = $dbHandle->query($queryCmd);
	    $file=fopen("/var/www/html/shiksha/urlFile2","r");
	    while(true) {
		    $tempIter = $iter+10000;
		    $queryCmd = "Select id,phpSession,cookie,refrer,url from log1 where time > ? and time < ? order by id limit $iter,10000";
		    //echo $queryCmd;
		    $iter = $tempIter;
		    $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
		    $numRows = 0;
		    foreach ($query->result() as $row){
			    if(!isset($phpSessionFirstPage[$row->phpSession])) {
				    if(strlen($row->refrer) > 9) {
					    $phpSessionFirstPage[$row->phpSession] = $row->refrer;
				    }
			    }
			    $cookie = $row->cookie;
			    $pos = strripos($cookie,"[user]");
			    if($pos === false) {
			    }else {
				    if(!isset($print[$row->phpSession])){
					    $print[$row->phpSession] = "1";
					    $phpSessionLoggedInArr[$k] = "\"".$row->phpSession."\"";
					    $k++;
				    }
			    }
			    $lastId = $row->id;
			    $numRows++;

		    }
		    if($numRows < 10000) {
			    break;
		    }
	    }
	    $phpSessionLoggedIn = implode(",",$phpSessionLoggedInArr);
	            echo count($phpSessionFirstPage)."<br/>";
	          //echo count($print);


	    $totalPageViews=0;
	    $totalTriedReg = 0;
	    $totalDidReg = 0;
	    $lastPageArr = array();
	    $didRegistrationBannerCookie = array();
	    $didRegistrationPhpSessionBanner = array();
	    $finalOutput .= "<table border='1'>";
	    //echo "<table border='1'>";






    foreach ($queryUrls->result() as $row){
		    $finalOutput .= "<tr><td>";
		    //echo "<tr><td>";

		    $url = trim($row->url);
		    $finalOutput .= $url."<br/></td><td>";
            echo $url;

		    //echo $url."<br/>";
		    //echo "</td><td>";

		    if($url == ""){
			    continue;
		    }
		    $urlGroup = array();
		    $listingPageViews = 0;
		    $becameLeads = 0;
		    $searched = 0;


		    $queryCmd = "Select count(*) as totalCount from log1 where url=? and time > ? and time < ?";
		    //echo $queryCmd;
		    //            echo $queryCmd;
		    $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
		    foreach ($query->result() as $row){
			    $totalCountUrl = $row->totalCount; 
			    $finalOutput .= "Total PageViews = ".$row->totalCount."<br/>";
			    //echo "Total PageViews = ".$row->totalCount."<br/>";
			    $totalPageViews = $totalPageViews + $row->totalCount;
		    }
		    $finalOutput .= "</td><td>";
		    //echo "</td><td>";
		    $queryCmd = "Select phpSession,cookie from log1 where (refrer='\"$url\"' or url='\"$url\"') and time > ? and time < ? and phpSession!=\"\" order by id";
		    //echo $queryCmd;
		    $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
		    $phpSessionArr = array();
		    $phpSessionArrAll = array();
		    $phpSessionRevArr = array();
		    $phpSessionArr[0] = "\"1234\"";
		    $phpSessionArrAll[0] = "\"1234\"";
		    $k=1;
		    $l=1;
		    foreach ($query->result() as $row){
			    if(trim($row->phpSession) == "") {
				    continue;
			    }
			    if(preg_match("/[^0-9A-Za-z]/i", $row->phpSession)) {
				    continue;
			    }
			    $cookie = $row->cookie;             
			    //                $pos = strripos($cookie,"[user]");  
			    $phpSessionArrAll[$l] = "\"".$row->phpSession."\"";
			    $l++;
			    //              if($pos === false) {                
			    if($phpSessionRevArr[$row->phpSession] != "1") {
				    $phpSessionArr[$k] = "\"".$row->phpSession."\"";
				    $k++;
				    $phpSessionRevArr[$row->phpSession] = "1";
			    }
			    //            }
		    }
		    $finalOutput .= "Unique People Visited anotherPage = ".(count($phpSessionArr)-1);
		    $finalOutput .= "</td><td>";
		    //echo "Unique People Visited anotherPage = ".(count($phpSessionArr)-1); 
		    //echo "</td><td>";
		    $phpSession = implode(",",$phpSessionArr);
		    $phpSessionAll = implode(",",$phpSessionArrAll);
		    $queryCmd = "Select url from log1 where phpSession in ($phpSessionAll)";
		    //echo $queryCmd;
		    //echo $queryCmd;
		    $query = $dbHandle->query($queryCmd);
		    $numRows = 0;
		    $countPages = 0;
		    foreach ($query->result() as $row){
			    $countPages++;
			    $hostName = preg_replace('/(https:\/\/[^\/]*)\/.*/', '$1', $row->url);
			    if(isset($urlGroup[$hostName])) {
				    $urlGroup[$hostName]++;
			    }else {
				    $urlGroup[$hostName] = 1;
			    }
                preg_match('/getListingDetail/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                if(count($matches) > 0) {
                    $listingPageViews++;
                }
                preg_match('/Listing\/requestInfo/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                if(count($matches) > 0) {
                    $becameLeads++;
                }
                preg_match('/search\/index/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                if(count($matches) > 0) {
                    $searched++;
                }

		    }
		    //            echo $countPages;
		    $hostName = preg_replace('/(https:\/\/[^\/]*)\/.*/', '$1', $url);
		    if(isset($urlGroup[$hostName])) {
			    $urlGroup[$hostName] += $totalCountUrl;
		    }else { 
			    $urlGroup[$hostName] = $totalCountUrl;
            }
            preg_match('/getListingDetail/', $url, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                $listingPageViews += $totalCountUrl;
            }
            preg_match('/Listing\/requestInfo/', $url, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                $becameLeads += $totalCountUrl;
            }
            preg_match('/search\/index/', $url, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                $searched += $totalCountUrl;
            }





		    $queryCmd = "select * from log1 where phpSession in ($phpSession) order by id";
		    //echo $queryCmd;

		    $query = $dbHandle->query($queryCmd);
		    $visitedRegistrationPage = array();
		    $didRegistrationPage = array();
		    foreach ($query->result() as $row){
			    $url = $row->url;
			    $pos = strripos($row->cookie,"[user]");
			    $pos = strripos($url,"Userregistration/submit");
			    if($pos === false) {
			    }else {
				    $visitedRegistrationPage[$row->phpSession] = "1";
				    $lastPageArr[$row->phpSession] = 1;
			    }
			    $pos = strripos($url,"userfromhome");
			    if($pos === false) {
			    }else {
				    $visitedRegistrationPage[$row->phpSession] = "1";
				    $lastPageArr[$row->phpSession] = 1;
			    }
                $pos = strripos($url,"Userregistration/userfromMarketingPage");
                if($pos === false) {
                }else {
                    $visitedRegistrationPage[$row->session] = "1";
                    $lastPageArr[$row->session] = 1;
                }


			    $pos = strripos($url,"Userregistration/quicksignup");
			    if($pos === false) {
			    }else {
				    $visitedRegistrationPage[$row->phpSession] = "1";
				    $lastPageArr[$row->phpSession] = 1;
			    }
			    $pos = strripos($url,"Listing/requestInfo");
			    if($pos === false) {
			    }else {
				    $visitedRegistrationPage[$row->phpSession] = "1";
				    $lastPageArr[$row->phpSession] = 1;
			    }
			    $lastPageArr[$row->phpSession]++;
			    //                if($lastPageArr[$row->phpSession] == 2) {
			    //                    echo $row->url."<br/>";
			    //                }
			    if($visitedRegistrationPage[$row->phpSession] == "1") {
				    $cookie = $row->cookie;
				    $pos = strripos($cookie,"[user]");
				    if($pos === false) {
				    }else {
					    if(!isset($didRegistrationPage[$row->phpSession])) {
						    $didRegistrationPage[$row->phpSession] = "1";
						    $didRegistrationPhpSessionBanner[$row->phpSession] = "1";
						    $didRegistrationBannerCookie[$row->phpSession] = $row->cookie;

						    //                            echo $lastPageArr[$row->phpSession]." ";
					    }
				    }
			    }

		    }
		    //       foreach($didRegistrationPage as $key=>$val) {
		    //           echo "<br/>$key";
		    //       }
		    $finalOutput .= "<br/>Tried Registering = ".count($visitedRegistrationPage);
		    //echo "<br/>Tried Registering = ".count($visitedRegistrationPage);
		    $finalOutput .= "</td><td>";

		    //echo "</td><td>";

		    $totalTriedReg = $totalTriedReg + count($visitedRegistrationPage);
		    $finalOutput .= "<br/>Did Registration(full+request) = ".count($didRegistrationPage);
		    //echo "<br/>Did Registration(full+request) = ".count($didRegistrationPage);
		    $finalOutput .= "</td><td>";
		    //echo "</td><td>";
		    foreach($urlGroup as $key=>$val) {
			    $finalOutput .= $key." ".$urlGroup[$key]."<br/>";


			    //echo $key." ".$urlGroup[$key]."<br/>";

		    }
		    $finalOutput .= "</td><td>";
            $finalOutput .= "Number of Listing Viewed: ".$listingPageViews."<br/>Number of Leads Formed: ".$becameLeads."<br/>Number of searches made".$searched."<br/>";
		    $finalOutput .= "</td></tr>";
		    //echo "</td></tr>";
		    $totalDidReg = $totalDidReg + count($didRegistrationPage);
	    }
	    $finalOutput .= "</table>";
	    $finalOutput .= "Total = ".$totalPageViews." Page Views   ".$totalTriedReg." Registration attempts    ".$totalDidReg." Sucessful Registration ";
	    //echo "</table>";
	    //echo "Total = ".$totalPageViews." Page Views   ".$totalTriedReg." Registration attempts    ".$totalDidReg." Sucessful Registration ";

	    /*       $totalUsersLoggedIn = count($phpSessionLoggedInArr);
		     $visitedRegistrationPage = array();
		     $didRegistrationPage = array();
		     echo "<div id='show'; style='display:none'>"; 
		     $finalCount = 0;
		     $finalNotCount = 0;

		     for($i = 0;$i < $totalUsersLoggedIn; $i =$i+100) {
		     if($i+100 > $totalUsersLoggedIn) {
		     $toAdd = $totalUsersLoggedIn-$i;
		     }else {
		     $toAdd = 100;
		     }
		     $tempArrayFor100 = array();
		     $l=0;
		     for($k=$i;$k<$i+$toAdd;$k++) {
		     $tempArrayFor100[$l] = $phpSessionLoggedInArr[$k];
		     $l++;
		     }
		     $phpSessionLoggedIn = implode(",",$tempArrayFor100);

		     $queryCmd = "select url, cookie, phpSession from log1BCK_START_TO_DEC where phpSession in ($phpSessionLoggedIn) and phpSession!=\"\" order by id";
	    //                echo "<br/>".count($phpSessionLoggedInArr);
	    //            echo "$queryCmd";

	    $query = $dbHandle->query($queryCmd);
	    //                var_dump($query->result());
	    foreach ($query->result() as $row){
	    $url = $row->url;
	    $pos = strripos($row->cookie,"[user]");
	    $pos = strripos($url,"Userregistration/submit");
	    if($pos === false) {
	    }else {
	    $visitedRegistrationPage[$row->phpSession] = "1";
	    }
	    $pos = strripos($url,"userfromhome");
	    if($pos === false) {
	    }else {
	    $visitedRegistrationPage[$row->phpSession] = "1";
	    }
	    $pos = strripos($url,"Userregistration/quicksignup");
	    if($pos === false) {
	    }else {
	    $visitedRegistrationPage[$row->phpSession] = "1";
	    }
	    $pos = strripos($url,"Listing/requestInfo");
	    if($pos === false) {
	    }else {
	    $visitedRegistrationPage[$row->phpSession] = "1";
	    }
	    //                    echo "<br/>".$visitedRegistrationPage[$row->phpSession];
	    if($visitedRegistrationPage[$row->phpSession] == "1") {
	    $cookie = $row->cookie;
	    $pos = strripos($cookie,"[user]");
	    if($pos === false) {
	    }else {
	    if(!isset($didRegistrationPage[$row->phpSession])) {
	    $didRegistrationPage[$row->phpSession] = "1";
	    if (preg_match("/(google-)|(yahoo-)|(rediff-)/i", $phpSessionFirstPage[$row->phpSession])) {
	    $finalNotCount++;
	    //                        echo "<br/>".$row->phpSession." ".$phpSessionFirstPage[$row->phpSession]."<br/>";
	    } else {
	    if(!isset($didRegistrationPhpSessionBanner[$row->phpSession])) {
	    $finalCount++;
	    echo "<br/>".$row->phpSession." ".$phpSessionFirstPage[$row->phpSession]."<br/>";
	    // echo "<br/>".$row->cookie;

	    }
	    }
	    }
	    }
    }

    }
    }
    foreach($didRegistrationBannerCookie as $key=>$val) {
	    //                echo "<br/>".$val;
    }
    echo "</div>";
    echo "<br/><a href='#' onClick='javascript: document.getElementById(\"show\").style.display=\"\";'>Show All $finalCount (Registration done directly from site)</a>";*/
	    $finalOutput .= "<br/><br/><a href='https://linuxcp10325.dn.net/payment/newPage/logs'>Back</a>";
	    //echo "<br/><br/><a href='https://linuxcp10325.dn.net/payment/newPage/logs'>Back</a>";
	//echo $finalOutput;
	$queryCmd = 'insert into log2 values("", ?, ?, "'.mysql_escape_string($finalOutput).'") on duplicate key update wholeHtml="'.mysql_escape_string($finalOutput).'"';
	echo $queryCmd;
    $dbConfig = array( 'hostname'=>'localhost');
    //error_log('debug'. 'getProductData query cmd is ' . $queryCmd);
    $query = $dbHandle->query($queryCmd, array($sDate, $eDate));



    }
    function blank()
    {

        echo "";
    }

    function index($pageId = 1, $productId = 0) {
        global $logged;
        $appID = 1;
        if($pageId == 2) {
            $this->init();
            $paymentClientObj = new PaymentClient();
            $productDataList =  $paymentClientObj->getProductData($appID,$productId);
            $this->load->view('payment/productSecond',array("productDataList"=>$productDataList,"logged"=>$logged));		  
        }
        if($pageId == 3) {
            $this->init();
            $paymentClientObj = new PaymentClient();
            $productDataList =  $paymentClientObj->getProductData($appID,$productId);
            $this->load->view('payment/productThird',array("productDataList"=>$productDataList,"success"=>"registerUser"));
        }
        if($pageId == 4) {
            $this->init();
            $this->load->library('login_client');
            $loginClientObj = new login_client();
            //error_log(print_r($_REQUEST,true)."kdfhkljsdfklhasdhasdfh");
            if($logged == "No") {
                $email = $_POST['email'];
                $password = $_POST['password'];
                //error_log("data = ".$email." $password");
                $password = urldecode($password);
                //error_log("data = ".$email." $password");

                $loginData = $loginClientObj->validateuser("$email|$password","");
                //error_log("data12334567678 = ".print_r($loginData,true));
                //            $loginData[0]['userid'] = 10;
                if(isset($loginData[0]['userid'])) {
                    
                    setcookie("user",$email."|".$password,time()+36000,"/");
                    //error_log("data1233456767812 = ".print_r($loginData,true));
                    $userid = $loginData[0]['userid'];
                    $paymentClientObj = new PaymentClient();
                    //error_log($userid);
                    //error_log("user id = ".$userid);
                    $productDataList =  $paymentClientObj->getProductForUser(1,$userid);
                    //error_log(print_r($productDataList,true));
                    if(isset($productDataList[0])) {
                        echo "REDIRECT||".base_url() ."/listing/Listing/addCourse";
                    }else {
                        $productDataList =  $paymentClientObj->getProductData($appID,$productId);

                        $this->load->view('payment/productFourth',array("productDataList"=>$productDataList,"userid"=>$loginData[0]['userid']));		  
                    }
                }else {
                    //error_log("returning khali");
                    echo "";
                }
            }else {
                $paymentClientObj = new PaymentClient();
                $productDataList =  $paymentClientObj->getProductData($appID,$productId);
                global $validity;

                $this->load->view('payment/productFourth',array("productDataList"=>$productDataList,"userid"=>$validity[0]['userid']));
            }

        }
        if($pageId == 5) {
            $this->init();
            $userId = $this->input->post("userId"); 
            $paymentClientObj = new PaymentClient();
            $productDataList =  $paymentClientObj->addTransaction($appID,$productId,$userId,$this->input->post("paymentOption"));
            global $validity;
            $this->load->view('payment/productFifth',array("productDataList"=>$productDataList,"paymentOption"=>$this->input->post("paymentOption"),"userId"=>$this->input->post("userId"),"userName"=>$validity[0]["displayname"]));
            //user id should be get by Neha's service by giving her cookie
        }




    }
    function getMailerLogsTrial() {
    	return false;
    	$this->mailAlert('getMailerLogsTrial');
	    $finalOutput = "";
	    set_time_limit(0);
		$post_sDate = $this->input->post('sDate');
		$post_eDate = $this->input->post('eDate');
	    $sDate = isset($post_sDate)?$post_sDate:'2009-04-23';
	    $eDate = isset($post_eDate)?$post_eDate:$post_sDate;
	    if(strlen(trim($post_eDate)) < 12) {
		    $eDate = $post_eDate." 23:59:59";
	    }else {
		    $eDate = $post_eDate;
	    }
	    $dbConfig = array( 'hostname'=>'localhost');
	    $this->load->library('logsconfig');
	    $this->logsconfig->getDbConfig($appId,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);
	    $phpSessionFirstPage = array();
	    $print = array();
	    $iter = 0;
	    $phpSessionLoggedInArr = array();
	    $k=0;
	    $rowsGot = 0;
        $queryCmd = "Select * from trackMailerUrls";
        $queryUrls = $dbHandle->query($queryCmd);
	    $file=fopen("/var/www/html/shiksha/urlFile1","r");
	    while(true) {
		    $tempIter = $iter+10000;
		    $queryCmd = "Select id,phpSession,cookie,refrer,url from log1 where time > ? and time < ? order by id limit $iter,10000";
		    //echo $queryCmd;
		    $iter = $tempIter;
		    $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
		    $numRows = 0;
		    foreach ($query->result() as $row){
			    if(!isset($phpSessionFirstPage[$row->phpSession])) {
				    if(strlen($row->refrer) > 9) {
					    $phpSessionFirstPage[$row->phpSession] = $row->refrer;
				    }
			    }
			    $cookie = $row->cookie;
			    $pos = strripos($cookie,"[user]");
			    if($pos === false) {
			    }else {
				    if(!isset($print[$row->phpSession])){
					    $print[$row->phpSession] = "1";
					    $phpSessionLoggedInArr[$k] = "\"".$row->phpSession."\"";
					    $k++;
				    }
			    }
			    $lastId = $row->id;
			    $numRows++;

		    }
		    if($numRows < 10000) {
			    break;
		    }
	    }
	    $phpSessionLoggedIn = implode(",",$phpSessionLoggedInArr);
	            //echo count($phpSessionFirstPage)."<br/>";
	          //echo count($print);


	    $totalPageViews=0;
	    $totalTriedReg = 0;
	    $totalDidReg = 0;
	    $lastPageArr = array();
	    $didRegistrationBannerCookie = array();
	    $didRegistrationPhpSessionBanner = array();
        $userInterestArray = array();
	    $finalOutput .= "<table border='1'>";
	    //echo "<table border='1'>";






    foreach ($queryUrls->result() as $row){
		    $finalOutput .= "<tr><td>";
		    //echo "<tr><td>";

		    $url = trim($row->url);
            $globalUrl = $url; 
		    $finalOutput .= $url."<br/></td><td>";
            echo $url;

		    //echo $url."<br/>";
		    //echo "</td><td>";

		    if($url == ""){
			    continue;
		    }
		    $urlGroup = array();
		    $listingPageViews = 0;
		    $becameLeads = 0;
		    $searched = 0;


		    $queryCmd = "Select count(*) as totalCount from log1 where url=? and time > ? and time < ?";
		    //echo $queryCmd;
		    //            echo $queryCmd;
		    $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
		    foreach ($query->result() as $row){
			    $totalCountUrl = $row->totalCount; 
			    $finalOutput .= "Total PageViews = ".$row->totalCount."<br/>";
			    //echo "Total PageViews = ".$row->totalCount."<br/>";
			    $totalPageViews = $totalPageViews + $row->totalCount;
		    }
		    $finalOutput .= "</td><td>";
		    //echo "</td><td>";
		    $queryCmd = "Select phpSession,cookie from log1 where (refrer='\"$url\"' or url='\"$url\"') and time > ? and time < ? and phpSession!=\"\" order by id";
		    //echo $queryCmd;
		    $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
		    $phpSessionArr = array();
		    $phpSessionArrAll = array();
		    $phpSessionRevArr = array();
		    $phpSessionArr[0] = "\"1234\"";
		    $phpSessionArrAll[0] = "\"1234\"";
		    $k=1;
		    $l=1;
		    foreach ($query->result() as $row){
			    if(trim($row->phpSession) == "") {
				    continue;
			    }
			    if(preg_match("/[^0-9A-Za-z]/i", $row->phpSession)) {
				    continue;
			    }
			    $cookie = $row->cookie;             
			    //                $pos = strripos($cookie,"[user]");  
			    $phpSessionArrAll[$l] = "\"".$row->phpSession."\"";
			    $l++;
			    //              if($pos === false) {                
			    if($phpSessionRevArr[$row->phpSession] != "1") {
				    $phpSessionArr[$k] = "\"".$row->phpSession."\"";
				    $k++;
				    $phpSessionRevArr[$row->phpSession] = "1";
			    }
			    //            }
		    }
		    $finalOutput .= "Unique People Visited anotherPage = ".(count($phpSessionArr)-1);
		    $finalOutput .= "</td><td>";
		    //echo "Unique People Visited anotherPage = ".(count($phpSessionArr)-1); 
		    //echo "</td><td>";
		    $phpSession = implode(",",$phpSessionArr);
		    $phpSessionAll = implode(",",$phpSessionArrAll);
		    $queryCmd = "Select url from log1 where phpSession in ($phpSessionAll)";
		    //echo $queryCmd;
		    //echo $queryCmd;
		    $query = $dbHandle->query($queryCmd);
		    $numRows = 0;
		    $countPages = 0;
		    foreach ($query->result() as $row){
			    $countPages++;
			    $hostName = preg_replace('/(https:\/\/[^\/]*)\/.*/', '$1', $row->url);
			    if(isset($urlGroup[$hostName])) {
				    $urlGroup[$hostName]++;
			    }else {
				    $urlGroup[$hostName] = 1;
			    }
                preg_match('/getListingDetail/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                if(count($matches) > 0) {
                    $listingPageViews++;
                }
                preg_match('/Listing\/requestInfo/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                if(count($matches) > 0) {
                    $becameLeads++;
                }
                preg_match('/search\/index/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                if(count($matches) > 0) {
                    $searched++;
                }

		    }
		    //            echo $countPages;
		    $hostName = preg_replace('/(https:\/\/[^\/]*)\/.*/', '$1', $url);
		    if(isset($urlGroup[$hostName])) {
			    $urlGroup[$hostName] += $totalCountUrl;
		    }else { 
			    $urlGroup[$hostName] = $totalCountUrl;
            }
            preg_match('/getListingDetail/', $url, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                $listingPageViews += $totalCountUrl;
            }
            preg_match('/Listing\/requestInfo/', $url, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                $becameLeads += $totalCountUrl;
            }
            preg_match('/search\/index/', $url, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                $searched += $totalCountUrl;
            }





		    $queryCmd = "select * from log1 where phpSession in ($phpSession) order by id";
		    //echo $queryCmd;

		    $query = $dbHandle->query($queryCmd);
		    $visitedRegistrationPage = array();
		    $didRegistrationPage = array();
		    foreach ($query->result() as $row){
			    $url = $row->url;
			    $pos = strripos($row->cookie,"[user]");
			    $pos = strripos($url,"Userregistration/submit");
			    if($pos === false) {
			    }else {
				    $visitedRegistrationPage[$row->phpSession] = "1";
				    $lastPageArr[$row->phpSession] = 1;
			    }
			    $pos = strripos($url,"userfromhome");
			    if($pos === false) {
			    }else {
				    $visitedRegistrationPage[$row->phpSession] = "1";
				    $lastPageArr[$row->phpSession] = 1;
			    }
                $pos = strripos($url,"Userregistration/userfromMarketingPage");
                if($pos === false) {
                }else {
                    $visitedRegistrationPage[$row->session] = "1";
                    $lastPageArr[$row->session] = 1;
                }


			    $pos = strripos($url,"Userregistration/quicksignup");
			    if($pos === false) {
			    }else {
				    $visitedRegistrationPage[$row->phpSession] = "1";
				    $lastPageArr[$row->phpSession] = 1;
			    }
			    $pos = strripos($url,"Listing/requestInfo");
			    if($pos === false) {
			    }else {
				    $visitedRegistrationPage[$row->phpSession] = "1";
				    $lastPageArr[$row->phpSession] = 1;
			    }
			    $lastPageArr[$row->phpSession]++;
			    //                if($lastPageArr[$row->phpSession] == 2) {
			    //                    echo $row->url."<br/>";
			    //                }
			    if($visitedRegistrationPage[$row->phpSession] == "1") {
				    $cookie = $row->cookie;
				    $pos = strripos($cookie,"[user]");
				    if($pos === false) {
				    }else {
					    if(!isset($didRegistrationPage[$row->phpSession])) {
                            $user_registration_email = strstr($cookie,"[user] => ");
                            $user_registration_email = strstr($user_registration_email,'|',true);
                            $UserInterestquery = "select categoryBoardTable.name from shiksha.tuser, shiksha.tuserInterest, shiksha.categoryBoardTable where tuser.email=\"".$user_registration_email."\" and tuser.userid= tuserInterest.userId and tuserInterest.keyofInterest = \"category\" and tuserInterest.keyValue = categoryBoardTable.boardId";
                            $UserInterestResult = $dbHandle->query($UserInterestquery)->results();
                            $userInterestArray[$row->phpSession] = $UserInterestResult->name;
						    $didRegistrationPage[$row->phpSession] = "1";
						    $didRegistrationPhpSessionBanner[$row->phpSession] = "1";
						    $didRegistrationBannerCookie[$row->phpSession] = $row->cookie;
						    //echo $lastPageArr[$row->phpSession]." ";
					    }
				    }
			    }

		    }
		    //       foreach($didRegistrationPage as $key=>$val) {
		    //           echo "<br/>$key";
		    //       }
		    $finalOutput .= "<br/>Tried Registering = ".count($visitedRegistrationPage);
		    //echo "<br/>Tried Registering = ".count($visitedRegistrationPage);

		    //echo "</td><td>";

		    $totalTriedReg = $totalTriedReg + count($visitedRegistrationPage);
		    $finalOutput .= "<br/>Did Registration(full+request) = ".count($didRegistrationPage);
            foreach($userInterestArray as $key=>$value)
            {
                if(!isset($categorySplit[$value]))
                {
                    $categorySplit[$value] = 1;
                }
                else
                {
                    $categorySplit[$value]++;
                }
            }
            echo "<pre>".print_r($categorySplit)."</pre>";
		    //echo "<br/>Did Registration(full+request) = ".count($didRegistrationPage);
		    $finalOutput .= "</td><td>";
		    //echo "</td><td>";
		    foreach($urlGroup as $key=>$val) {
			    $finalOutput .= $key." ".$urlGroup[$key]."<br/>";


			    //echo $key." ".$urlGroup[$key]."<br/>";

		    }
		    $finalOutput .= "</td><td>";
            $finalOutput .= "Number of Listing Viewed: ".$listingPageViews."<br/>Number of Leads Formed: ".$becameLeads."<br/>Number of searches made".$searched."<br/>";
		    $finalOutput .= "</td></tr>";
		    //echo "</td></tr>";
		    $totalDidReg = $totalDidReg + count($didRegistrationPage);
	    }
	    $finalOutput .= "</table>";
	    $finalOutput .= "Total = ".$totalPageViews." Page Views   ".$totalTriedReg." Registration attempts    ".$totalDidReg." Sucessful Registration ";
	    //echo "</table>";
	    //echo "Total = ".$totalPageViews." Page Views   ".$totalTriedReg." Registration attempts    ".$totalDidReg." Sucessful Registration ";

	    /*       $totalUsersLoggedIn = count($phpSessionLoggedInArr);
		     $visitedRegistrationPage = array();
		     $didRegistrationPage = array();
		     echo "<div id='show'; style='display:none'>"; 
		     $finalCount = 0;
		     $finalNotCount = 0;

		     for($i = 0;$i < $totalUsersLoggedIn; $i =$i+100) {
		     if($i+100 > $totalUsersLoggedIn) {
		     $toAdd = $totalUsersLoggedIn-$i;
		     }else {
		     $toAdd = 100;
		     }
		     $tempArrayFor100 = array();
		     $l=0;
		     for($k=$i;$k<$i+$toAdd;$k++) {
		     $tempArrayFor100[$l] = $phpSessionLoggedInArr[$k];
		     $l++;
		     }
		     $phpSessionLoggedIn = implode(",",$tempArrayFor100);

		     $queryCmd = "select url, cookie, phpSession from log1 where phpSession in ($phpSessionLoggedIn) and phpSession!=\"\" order by id";
	    //                echo "<br/>".count($phpSessionLoggedInArr);
	    //            echo "$queryCmd";

	    $query = $dbHandle->query($queryCmd);
	    //                var_dump($query->result());
	    foreach ($query->result() as $row){
	    $url = $row->url;
	    $pos = strripos($row->cookie,"[user]");
	    $pos = strripos($url,"Userregistration/submit");
	    if($pos === false) {
	    }else {
	    $visitedRegistrationPage[$row->phpSession] = "1";
	    }
	    $pos = strripos($url,"userfromhome");
	    if($pos === false) {
	    }else {
	    $visitedRegistrationPage[$row->phpSession] = "1";
	    }
	    $pos = strripos($url,"Userregistration/quicksignup");
	    if($pos === false) {
	    }else {
	    $visitedRegistrationPage[$row->phpSession] = "1";
	    }
	    $pos = strripos($url,"Listing/requestInfo");
	    if($pos === false) {
	    }else {
	    $visitedRegistrationPage[$row->phpSession] = "1";
	    }
	    //                    echo "<br/>".$visitedRegistrationPage[$row->phpSession];
	    if($visitedRegistrationPage[$row->phpSession] == "1") {
	    $cookie = $row->cookie;
	    $pos = strripos($cookie,"[user]");
	    if($pos === false) {
	    }else {
	    if(!isset($didRegistrationPage[$row->phpSession])) {
	    $didRegistrationPage[$row->phpSession] = "1";
	    if (preg_match("/(google-)|(yahoo-)|(rediff-)/i", $phpSessionFirstPage[$row->phpSession])) {
	    $finalNotCount++;
	    //                        echo "<br/>".$row->phpSession." ".$phpSessionFirstPage[$row->phpSession]."<br/>";
	    } else {
	    if(!isset($didRegistrationPhpSessionBanner[$row->phpSession])) {
	    $finalCount++;
	    echo "<br/>".$row->phpSession." ".$phpSessionFirstPage[$row->phpSession]."<br/>";
	    // echo "<br/>".$row->cookie;

	    }
	    }
	    }
	    }
    }

    }
    }
    foreach($didRegistrationBannerCookie as $key=>$val) {
	    //                echo "<br/>".$val;
    }
    echo "</div>";
    echo "<br/><a href='#' onClick='javascript: document.getElementById(\"show\").style.display=\"\";'>Show All $finalCount (Registration done directly from site)</a>";*/
	    $finalOutput .= "<br/><br/><a href='https://linuxcp10325.dn.net/payment/newPage/mailerlogs'>Back</a>";
	    //echo "<br/><br/><a href='https://linuxcp10325.dn.net/payment/newPage/logs'>Back</a>";
	//echo $finalOutput;
	$queryCmd = 'insert into log3 values("", ?, ?, "'.mysql_escape_string($finalOutput).'") on duplicate key update wholeHtml="'.$finalOutput.'"';
	echo $queryCmd;
    $dbConfig = array( 'hostname'=>'localhost');
    //error_log('debug'. 'getProductData query cmd is ' . $queryCmd);
    $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
    }

function getLogMisTrial() {
		return false;
		$this->mailAlert('getLogMisTrial');
        $finalOutput = "";

        set_time_limit(0);
		$post_sDate = $this->input->post('sDate');
		$post_eDate = $this->input->post('eDate');
	    $sDate = isset($post_sDate)?$post_sDate:'2009-04-23';
	    $eDate = isset($post_eDate)?$post_eDate:$post_sDate." 23:59:59";
	    if(strlen(trim($post_eDate)) < 12) {
		    $eDate = $post_eDate." 23:59:59";
	    }else {
		    $eDate = $post_eDate;
	    }
		
        $regex = $this->input->post('urlregex');
        $regexArr = split(",",$regex);
        $domain = $this->input->post('domain');
        if($this->input->post('export_csv') == "1") {
            $this->exportCsv($sDate,$eDate,$regexArr,$domain);
            exit(0);
        }
        $dbConfig = array( 'hostname'=>'localhost');
        $this->load->library('logsconfig');
        $this->logsconfig->getDbConfig($appId,$dbConfig);
        $dbHandle = $this->load->database($dbConfig,TRUE);
        $phpSessionFirstPage = array();
        $print = array();
        $iter = 0;
        $phpSessionLoggedInArr = array();
        $k=0;
        $rowsGot = 0;
        if($domain == "" || !isset($domain)) {
            $queryCmd = "Select * from trackUrls";
        }else {
            if($domain == "Not Specified") {
                $domain = "";
            }
            $queryCmd = "Select * from trackUrls where domain=".$dbHandle->escape($domain);
        }
        $queryUrls = $dbHandle->query($queryCmd);
        $totalPageViews = 0;
        $finalOutput .= "<table border='1'>";

        foreach ($queryUrls->result() as $row){
            $url = trim($row->url);
            $finalUrls = $url;
            $domain = $row->domain;
            if($url == ""){
                continue;
            }
            $regexFlag = 1;
            for($i = 0; $i < count($regexArr); $i++) {
                preg_match('/'.trim($regexArr[$i]).'/', $url, $matches, PREG_OFFSET_CAPTURE);
                if(count($matches) <= 0) {
                    $regexFlag = 0;
                    break;
                }
            }
            if($regexFlag != 0) {
                $dbConfig = array( 'hostname'=>'localhost');
                $this->load->library('logsconfig');
                $this->logsconfig->getDbConfig($appId,$dbConfig);
                $dbHandle = $this->load->database($dbConfig,TRUE);
                $finalOutput .= "<tr><td>";
                $finalOutput .= $url."<br/></td><td>";
                $urlGroup = array();
                $listingPageViews = 0;
                $becameLeads = 0;
                $searched = 0;
                $queryCmd = "select distinct(session) from logTracking where url=? and time > ? and time < ?"; 
                $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
                $k= 0;
                $sessionArr = array();
                foreach ($query->result() as $row){
                    $sessionArr[$k] = "'".$row->session."'";
                    $k++;
                }
                if(count($sessionArr) == 0) {
                    $finalOutput .= "</td></tr>";
                    continue;
                }
                $sessionStr = implode(",",$sessionArr);



                $queryCmd = "Select count(distinct(session)) as totalCount from logTracking where url=? and time > ? and time < ?";
                $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
                foreach ($query->result() as $row){
                    $totalCountUrl = $row->totalCount;
                    $finalOutput .= "Total PageViews Distinct Users = ".$row->totalCount."<br/>";
                    //echo "Total PageViews = ".$row->totalCount."<br/>";
//                    $totalPageViews = $totalPageViews + $row->totalCount;
                }
                $queryCmd = "Select count(*) as totalCount from logTracking where url=? and time > ? and time < ?"; 
                $query = $dbHandle->query($queryCmd, array($url, $sDate, $eDate));
                foreach ($query->result() as $row){
                    $totalCountUrl = $row->totalCount;
                    $finalOutput .= "Total PageViews = ".$row->totalCount."<br/>";
                    //echo "Total PageViews = ".$row->totalCount."<br/>";
                    $totalPageViews = $totalPageViews + $row->totalCount;
                }
                $finalOutput .= "</td><td>";


                $queryCmd = "select count(*) as countUser  from logTracking where session in ($sessionStr) and time > ? and time < ? group by session";
                $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
                $totalCount = 0;
                foreach ($query->result() as $row){
                    if($row->countUser >= 2) {
                        $totalCount++;
                    }
                }
                $finalOutput .= "Unique Users To next page = ".$totalCount."<br/>";
                $finalOutput .= "</td><td>";
                $queryCmd = "Select * from logTracking where session in ($sessionStr) and time > ? and time < ?"; 
                $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
                $visitedRegistrationPage = array();
                $didRegistrationPage = array();
                $categorySplit = array();
                $userInterestArray = array();
                foreach ($query->result() as $row){
                    $url = $row->url;
                    $pos = strripos($row->cookie,"[user]");
                    $pos = strripos($url,"Userregistration/submit");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }
                    $pos = strripos($url,"userfromhome");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }
                    $pos = strripos($url,"Userregistration/quicksignup");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }
                    $pos = strripos($url,"Userregistration/userfromMarketingPage");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }


                    $pos = strripos($url,"Listing/requestInfo");
                    if($pos === false) {
                    }else {
                        $visitedRegistrationPage[$row->session] = "1";
                        $lastPageArr[$row->session] = 1;
                    }
                    $lastPageArr[$row->session]++;
                }
                $queryCmd = "Select * from logTracking where session in ($sessionStr) and time > ? and time < ?";
                $query = $dbHandle->query($queryCmd, array($sDate, $eDate));

                foreach ($query->result() as $row){
                    if($visitedRegistrationPage[$row->session] == "1") {
                        $cookie = $row->cookie;
                        $pos = strripos($cookie,"[user]");
                        if($pos === false) {
                        }else {
                            if(!isset($didRegistrationPage[$row->session])) {
                                $user_registration_email = strstr($cookie,"[user] => ");
                                $user_registration_email = strstr($user_registration_email,'|',true);
                                $UserInterestquery = "select categoryBoardTable.name from shiksha.tuser, shiksha.tuserInterest, shiksha.categoryBoardTable where tuser.email=\"".trim($user_registration_email)."\" and tuser.userid= tuserInterest.userId and tuserInterest.keyofInterest = \"category\" and tuserInterest.keyValue = categoryBoardTable.boardId";
                                $UserInterestResult = $dbHandle->query($UserInterestquery);
                                $UserInterestResult1 = $UserInterestResult->result();
                                $userInterestArray[$row->phpSession] = $UserInterestResult1[0]->name;
                                $didRegistrationPage[$row->session] = "1";
                                $didRegistrationPhpSessionBanner[$row->session] = "1";
                                $didRegistrationBannerCookie[$row->session] = $row->cookie;
                            }
                        }
                    }

                }

                $finalOutput .= "<br/>Tried Registering = ".count($visitedRegistrationPage);

                //echo "<br/>Tried Registering = ".count($visitedRegistrationPage);
                $finalOutput .= "</td><td>";

                //echo "</td><td>";

                $totalTriedReg = $totalTriedReg + count($visitedRegistrationPage);
                $finalOutput .= "<br/>Did Registration(full+request) = ".count($didRegistrationPage);
                foreach($userInterestArray as $key=>$value)
                {
                    if(!isset($categorySplit[$value]))
                    {
                        $categorySplit[$value] = 1;
                    }
                    else
                    {
                        $categorySplit[$value]++;
                    }
                }
                $finalOutput.= "<pre>".print_r($categorySplit,true)."</pre>";


                //echo "<br/>Did Registration(full+request) = ".count($didRegistrationPage);
                $finalUrls = base64_encode($finalUrls);
                $finalSDate = base64_encode($sDate);
                $finalEDate = base64_encode($eDate);
                $finalOutput .= "<br/><a href='https://snapdragon.infoedge.com/payment/newPage/marketingUsers/$finalUrls/$finalSDate/$finalEDate' target='_blank'>Get Users</a></td><td>";
                $finalOutput .= "</td><td>";

                $queryCmd = "Select url from logTracking where session in ($sessionStr) and time > ? and time < ?"; 
                $query = $dbHandle->query($queryCmd, array($sDate, $eDate));
                $numRows = 0;
                $countPages = 0;
                foreach ($query->result() as $row){
                    $countPages++;
                    $hostName = preg_replace('/(https:\/\/[^\/]*)\/.*/', '$1', $row->url);
                    if(isset($urlGroup[$hostName])) {
                        $urlGroup[$hostName]++;
                    }else {
                        $urlGroup[$hostName] = 1;
                    }
                    preg_match('/getListingDetail/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                    if(count($matches) > 0) {
                        $listingPageViews++;
                    }
                    preg_match('/Listing\/requestInfo/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                    if(count($matches) > 0) {
                        $becameLeads++;
                    }
                    preg_match('/search\/index/', $row->url, $matches, PREG_OFFSET_CAPTURE);
                    if(count($matches) > 0) {
                        $searched++;
                    }

                }
                foreach($urlGroup as $key=>$val) {
                    $finalOutput .= $key." ".$urlGroup[$key]."<br/>";


                    //echo $key." ".$urlGroup[$key]."<br/>";

                }
                $finalOutput .= "</td><td>";
                $finalOutput .= "Number of Listing Viewed: ".$listingPageViews."<br/>Number of Leads Formed: ".$becameLeads."<br/>Number of searches made".$searched."<br/>";
                $finalOutput .= "</td><td>";
                $finalOutput .= $domain;
                $finalOutput .= "</td></tr>";
                $totalDidReg = $totalDidReg + count($didRegistrationPage);
            }
        }
        $finalOutput .= "</table>";
        $finalOutput .= "Total = ".$totalPageViews." Page Views   ".$totalTriedReg." Registration attempts    ".$totalDidReg." Sucessful Registration ";

        echo $finalOutput;
    }
   }
   ?>
