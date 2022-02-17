  <?php
    
    /**
     *
     *  
     * 
     * @category LDB
     * @author Shiksha Team
     * @link https://www.shiksha.com
     */
    
    class Ldbmis extends MX_Controller {
	
        
        function init()
	{
		 $this->load->helper(array('form', 'url','date','image','shikshaUtility'));
		 //$this->load->helper(array('url','form','shikshautility'));
		$this->load->library(array('SearchAgents_client','ajax','enterprise_client','LDB_Client','ajax','category_list_client','alerts_client'));
		$this->userStatus = $this->checkUserValidation();
	}
        
        
        function RunMisOnCron()
        {
            $this->Newsearchagentreportmis();
	    $this->Responsereportmis();
            $this->Searchagentdetailmis();
            $this->Ldbreportmis();
        }
        
	
	/**
	* API for generating Ldb report
	*/
        
        function Ldbreportmis()
        {
            
		    echo " \n ----- Ldbreportmis  starts  ----- \n ";
		    $this->benchmark->mark('code_start');
                    $appId = 1;
		    $this->load->library('Ldbmis_client');
		    $misObj = new Ldbmis_client();
		
		    $arr = $misObj->getleadviewdata();
	    			
		    	    
			 foreach($arr as $traversalarray)
			{

		   
			$arraytosend = $traversalarray['data'];
			$recipient = $traversalarray['email'];
			
			
			 if(!empty($recipient) && isset($recipient)) 
			    {
			   $csv = $this->formatLdbreportdata($arraytosend);
			
			    $subject = "LDB leads viewed by clients ";

			   $this->sendEmailtoSelectedContacts($recipient,$csv,$subject);  
			    }
 
			    
    		}
		    
		  $this->benchmark->mark('code_end');
		  echo(" mis total time taken to run code:::  ".$this->benchmark->elapsed_time('code_start', 'code_end')."seconds");
		 echo " ---------------  Ldbreportmis  ends  --------- \n ";

		
        }
        
	/**
	* API for generating New searchagents report
	*/
        
        function Newsearchagentreportmis()
        {
            
                    $appId = 1;
		    $this->load->library('Ldbmis_client');
		    $misObj = new Ldbmis_client();
		    $newsearchagentarray = $misObj->getnewsearchsagents();
		    		
		    echo "\n -------  Newsearchagentreportmis  starts  ------ \n ";
		    $this->benchmark->mark('code_start');

		    $arrtosend = $newsearchagentarray['data'];
		    $recipient = $newsearchagentarray['email'];
		   
		   if(!empty($arrtosend) && isset($arrtosend)) 
			{ 
		    $csv = $this->newsearchagentsreportdata($arrtosend);
		    $subject = "Search Agents created by Branch";
		    
		   
		    $this->sendEmailtoSelectedContacts($recipient,$csv,$subject);
			}

		  $this->benchmark->mark('code_end');
		  echo(" MIS total time taken to run code:::".$this->benchmark->elapsed_time('code_start', 'code_end')."seconds");
		  echo " -------  Newsearchagentreportmis  ends  ----- \n ";

            
        }
        
	/**
	* API for generating Searchagent details report
	*/
	
        function Searchagentdetailmis()
        {
                    $appId = 1;
		    $this->load->library('Ldbmis_client');
		    $misObj = new Ldbmis_client();

		    echo "  \n ---------- Searchagentdetailmis  starts  --------- \n ";
		    $this->benchmark->mark('code_start');


		    $arr = $misObj->getdataforsearchagent();

		    foreach($arr as $traversalarray)
		    {
			    $arrtosend = $traversalarray['data'];
			    $recipient = $traversalarray['email'];
		    
			     if(!empty($recipient) && isset($recipient)) 
				{
			       $csv = $this->searchagentreportdata($arrtosend);
			       
			       $subject = "Leads delivered to client ";
			     
			       $this->sendEmailtoSelectedContacts($recipient,$csv,$subject);  
				}
	    		}
		    $this->benchmark->mark('code_end');
		    echo(" total time taken to run code:::  ".$this->benchmark->elapsed_time('code_start', 'code_end')."seconds");
		    echo " -----------  Searchagentdetailmis  ends  --------- \n";


	}

	/**
	* API for generating response report
	*/
        
        function Responsereportmis()
        {
	  
		    $appId = 1;
		    $this->load->library('Ldbmis_client');
		    $misObj = new Ldbmis_client();
                    
		    echo " \n ------ Responsereportmis   starts ----- \n";
		    $this->benchmark->mark('code_start');

		    $arr = $misObj->getresponseviewdata();
		    
		   foreach($arr as $traversalarray)
		    {
			    $arrtosend= $traversalarray['data'];
			    $recipient = $traversalarray['email'];
		    
			     if(!empty($recipient) && isset($arrtosend)) 
				{
			       $csv = $this->formatresponsereportdata($arrtosend);
			     			       
				$subject = "Responses made for clients ";

			       $this->sendEmailtoSelectedContacts($recipient,$csv,$subject);  
				 }
	    		}
		    $this->benchmark->mark('code_end');
		    echo("total time taken to run code:::  ".$this->benchmark->elapsed_time('code_start', 'code_end')."seconds");
		    echo "  ----- Responsereportmis  ends  ---- \n ";
	
		   
	    }
	    
	    
	    
	function formatLdbreportdata($array)
	{
	  

	  
	   $filename = date(Ymdhis).' data.csv';
            $mime = 'text/x-csv';
            $this->init();
	  
	  
	   $columnListArray = array();
            $columnListArray[]='ClientId';
            $columnListArray[]='ClientName';
            $columnListArray[]='salesperson';
            $columnListArray[]='Branch';
	    $columnListArray[]='Leads_viewed/sms/email';
            $columnListArray[]='CourseName';
            $columnListArray[]='creditused';
            $columnListArray[]='creditsleft';
            
	    $ColumnList = $columnListArray;
            $csv = '';
            
            foreach ($ColumnList as $ColumnName){
                $csv .= '"'.$ColumnName.'",';
            }
                    $csv .= "\n";
	  
	  
	  foreach ($array as $lead){
                foreach ($ColumnList as $ColumnName){
                    $csv .= '"'.$lead[$ColumnName].'",';
                }
                $csv .= "\n";
            }
            
	   $data = $csv;
           return ($data);
	  
	  
	}
	
	
	
	function formatresponsereportdata($array)
	{
	  
	  
	   $filename = date(Ymdhis).' data.csv';
            $mime = 'text/x-csv';
            $this->init();
	  
	  
	   $columnListArray = array();
            $columnListArray[]='ClientId';
            $columnListArray[]='ClientName';
            $columnListArray[]='ListingName';
            $columnListArray[]='ResponsesMade';

            
	     $ColumnList = $columnListArray;
            $csv = '';
            
            foreach ($ColumnList as $ColumnName){
                $csv .= '"'.$ColumnName.'",';
            }
                    $csv .= "\n";
	  
	  
	  foreach ($array as $lead){
                foreach ($ColumnList as $ColumnName){
                    $csv .= '"'.$lead[$ColumnName].'",';
                }
                $csv .= "\n";
            }
            
	   $data = $csv;
           return ($data);
	  
	  
	}
	
	
	function newsearchagentsreportdata($array)
	{
	  
	   $filename = date(Ymdhis).' data.csv';
            $mime = 'text/x-csv';
            $this->init();
	  
	//  $Arr = json_decode(base64_decode($array));
	  
	   $columnListArray = array();
	    $columnListArray[]='clientid';
            $columnListArray[]='ClientName';
	    $columnListArray[]='SalesPerson';
            $columnListArray[]='branch';
	    $columnListArray[]='searchagentid';
            $columnListArray[]='searchagentName';
            $columnListArray[]='created_on';
            
            
	     $ColumnList = $columnListArray;
            $csv = '';
            
            foreach ($ColumnList as $ColumnName){
                $csv .= '"'.$ColumnName.'",';
            }
                    $csv .= "\n";
	  
	  
	  foreach ($array as $lead){
                foreach ($ColumnList as $ColumnName){
                    $csv .= '"'.$lead[$ColumnName].'",';
                }
                $csv .= "\n";
            }
            
	   $data = $csv;
           return ($data); 
	  
	  
	}
	
	
	
	function searchagentreportdata($array)
	{
	  
	  
	  
	   $filename = date(Ymdhis).' data.csv';
            $mime = 'text/x-csv';
            $this->init();
	  
	  
	   $columnListArray = array();
            $columnListArray[]='clientid';
	    $columnListArray[]='clientname';
	    $columnListArray[]='salesperson';
	    $columnListArray[]='Branch';
            $columnListArray[]='searchagentid';
            $columnListArray[]='searchagentName';
            $columnListArray[]='leads_daily_limit';
	    $columnListArray[]='Leadsdelivered';
	    $columnListArray[]='Deliverytype';
	    $columnListArray[]='CPL';
	    $columnListArray[]='creditsused';
	    $columnListArray[]='creditsleft';
            
            
	     $ColumnList = $columnListArray;
            $csv = '';
            
            foreach ($ColumnList as $ColumnName){
                $csv .= '"'.$ColumnName.'",';
            }
                    $csv .= "\n";
	  
	  
	  foreach ($array as $lead){
                foreach ($ColumnList as $ColumnName){
                    $csv .= '"'.$lead[$ColumnName].'",';
                }
                $csv .= "\n";
            }
            
	   $data = $csv;
           return ($data);
	  
	  
	  
	}
	
	
	
	
	
	function sendEmailtoSelectedContacts($email,$csv,$namearray)
       {
	  $appid = 1;
	  $this->load->library('alerts_client');
	  $alertClientObj = new Alerts_client();
	
	   static $counter =0;
          $file = date("Y-m-d H:i:s").$counter;
	  $csvName = $file.".csv";
	  $Checksubject = $namearray;
	  
	  $this->load->library('Ldbmis_client');
	  $misObj = new Ldbmis_client();

	  $type_id = $misObj->updateAttachment($appid);
	  	  
	  $attachmentResponse = $alertClientObj->createAttachment("12",$type_id,'COURSE','E-Brochure',$csv,$csvName,'text');

	  $attachmentidresponse = $alertClientObj->getAttachmentId("12",$type_id,'COURSE','E-Brochure',$csvName);
	  
	  $attachmentId = $attachmentidresponse[0]['id'];

	  $attachmentArray=array();
	  array_push($attachmentArray,$attachmentId);
	  

	  $date = date("d-m-Y");
	  
	  $newdate = strtotime ( '-7 day' , strtotime ( $date ) ) ;
	  
	  $newdate = date ( 'd-m-Y' , $newdate );
	  
	  $content = "No content";
	  $counter++;		 
			 
	   $subject = $namearray;		 
			 
	  $subject .=" ".$newdate." to ".$date;
	  
	   if($Checksubject == 'Search Agents created by Branch')
	   {
	    	$response=$alertClientObj->externalQueueAdd("12","info@shiksha.com",'saurabh.gupta@shiksha.com',$subject,$content,$contentType="text",'','y',$attachmentArray);

	    	$response=$alertClientObj->externalQueueAdd("12","info@shiksha.com",'ravi.raj@shiksha.com',$subject,$content,$contentType="text",'','y',$attachmentArray);

	    	$response=$alertClientObj->externalQueueAdd("12","info@shiksha.com",'konark.arora@shiksha.com',$subject,$content,$contentType="text",'','y',$attachmentArray);
	    
		 $response=$alertClientObj->externalQueueAdd("12","info@shiksha.com",'sachin.singhal@brijj.com',$subject,$content,$contentType="text",'','y',$attachmentArray);
	  
		 $response=$alertClientObj->externalQueueAdd("12","info@shiksha.com",'abhishek.jain@naukri.com',$subject,$content,$contentType="text",'','y',$attachmentArray);

		$response=$alertClientObj->externalQueueAdd("12","info@shiksha.com",'ashish.mishra@shiksha.com',$subject,$content,$contentType="text",'','y',$attachmentArray);
    
		$response=$alertClientObj->externalQueueAdd("12","info@shiksha.com",'vikas.k@shiksha.com',$subject,$content,$contentType="text",'','y',$attachmentArray);

		$response=$alertClientObj->externalQueueAdd("12","info@shiksha.com",'raghuvansh.gaurav@naukri.com',$subject,$content,$contentType="text",'','y',$attachmentArray);

	  }
	  
	else{
	    $response=$alertClientObj->externalQueueAdd("12","info@shiksha.com",$email,$subject,$content,$contentType="text",'','y',$attachmentArray);
	    } 
	  
       
       }
	
	
    }
	
	
        
