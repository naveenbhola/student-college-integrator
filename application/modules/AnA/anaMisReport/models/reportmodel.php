<?php
class reportmodel extends MY_Model {
        
        
        private $dbHandle = '';
        function __construct(){
		parent::__construct('AnA');
	}
        
        private function initiateModel($operation='read'){
                $this->dbHandle = $this->getReadHandle();
        }
            
        /******************************************************        
         this function generates all the data for the ana report
         Input Parameters : array containing current date and yesterdays date
        **********************************************************/
        
        
        public function getDataForMisReport($modelInputs){
                if(!is_resource($dbHandleSent)){
			$this->initiateModel();
		}else{
			$this->dbHandle = $dbHandleSent;
		}

                $finalResult= array();
                
                /*******************************************************
                 query to get total  number of questions posted in last 1 day
                 ******************************************************/
                
                $query1 = "SELECT COUNT(msgId) AS NumberOfQuestions FROM
                                messageTable where creationDate  >= ?
                                AND creationDate  <= ? AND mainAnswerId = '-1'
                                AND fromOthers = 'User'
                                AND userId IN(SELECT userId FROM  userpointsystembymodule
                                where userpointvaluebymodule >='1000' AND modulename='AnA')" ;
                                
                $noOfQuestions = $this->dbHandle->query($query1,array($modelInputs['Date2'],$modelInputs['Date3']));
                $noOfQuestions = $noOfQuestions->result_array();
                
                $finalResult['0']['field']="NumberOfQuestions";
                $finalResult['0']['NumberOfQuestionsbyExpert']=$noOfQuestions['0']['NumberOfQuestions'];
                
                $query1 = "SELECT COUNT(msgId) AS NumberOfQuestions FROM messageTable where creationDate  >= ? AND creationDate  <= ? AND mainAnswerId = '-1' AND fromOthers = 'User' ";
                $noOfQuestions = $this->dbHandle->query($query1,array($modelInputs['Date2'],$modelInputs['Date3']));
                $noOfQuestions = $noOfQuestions->result_array();
               
                
                $finalResult['0']['NumberOfQuestionsNonExperts']=$noOfQuestions['0']['NumberOfQuestions']- $finalResult['0']['NumberOfQuestionsbyExpert'];
                $finalResult['0']['TotalNumberOfQuestions']=$noOfQuestions['0']['NumberOfQuestions'];
                
                /*******************************************************
                query to get number of answers posted in last one day
                /*******************************************************/
                
                $query2 = "SELECT COUNT(msgId) AS NumberOfAnswers FROM
                                messageTable where creationDate  >= ?
                                AND creationDate  <= ? AND mainAnswerId = '0'
                                AND fromOthers = 'User'
                                AND userId IN(SELECT userId FROM  userpointsystembymodule
                                where userpointvaluebymodule >='1000' AND modulename='AnA' )" ;
                                
                $noOfAnswers = $this->dbHandle->query($query2,array($modelInputs['Date2'],$modelInputs['Date3']));
                $noOfAnswers = $noOfAnswers->result_array();
                
              
                $finalResult['1']['field']="NumberOfAnswers";
                $finalResult['1']['NumberOfAnswersbyExpert']=$noOfAnswers['0']['NumberOfAnswers'];
                
                $query2 = "SELECT COUNT(msgId) AS NumberOfAnswers FROM messageTable where creationDate  >= ? AND creationDate  <= ? AND mainAnswerId = '0' AND fromOthers = 'User' ";
                $noOfAnswers = $this->dbHandle->query($query2,array($modelInputs['Date2'],$modelInputs['Date3']));
                $noOfAnswers = $noOfAnswers->result_array();
               
                
                $finalResult['1']['NumberOfAnswersNonExperts']=$noOfAnswers['0']['NumberOfAnswers']-$finalResult['1']['NumberOfAnswersbyExpert'];
                $finalResult['1']['TotalNumberOfAnswers']=$noOfAnswers['0']['NumberOfAnswers'];
                
                
                
                /*******************************************************
                query to get number of registrations on ANA section
                /*******************************************************/
                
                
                $query3= "SELECT COUNT(id) AS NumberOfRegistrations FROM  `tusersourceInfo`  WHERE  `referer` LIKE  '%https://ask.shiksha.com/%'  AND time >= ? AND time <= ? AND `type` = 'registration'  ORDER BY  `id` DESC ";
                $count = $this->dbHandle->query($query3,array($modelInputs['Date2'],$modelInputs['Date3']));
                $count = $count->result_array();
                 
                $finalResult['2']['field']="NumberOfRegistrations";
                $finalResult['2']['NumberOfRegistrationsbyExpert']='--';
                $finalResult['2']['NumberOfRegistrationsbyNonExpert']='--';
                $finalResult['2']['NumberOfRegistrations']=$count['0']['NumberOfRegistrations'];
                
                /********************************
                query to get number of thumbsup
                ********************************/
                
                $query4= "SELECT COUNT(msgId) AS NumberOfthumbsup FROM  `messageTable`
                                WHERE  creationDate  >= ? AND creationDate  <= ?
                                AND digUp = ? AND fromOthers = ?  AND userId IN(SELECT userId FROM
                                userpointsystembymodule where userpointvaluebymodule >='1000'
                                AND modulename='AnA')" ;
                                
                $count = $this->dbHandle->query($query4,array($modelInputs['Date2'],$modelInputs['Date3'],'1','User'));
                $count = $count->result_array();
                
                $finalResult['3']['field']="NumberOfthumbsup";
                $finalResult['3']['NumberOfthumbsupbyexperts']=$count['0']['NumberOfthumbsup'];
                
                $query4= "SELECT COUNT(msgId) AS NumberOfthumbsup FROM  `messageTable`  WHERE  creationDate  >= ? AND creationDate  <= ? AND digUp = ? AND fromOthers = ?" ;
                $count = $this->dbHandle->query($query4,array($modelInputs['Date2'],$modelInputs['Date3'],'1','User'));
                $count = $count->result_array();
                
                $finalResult['3']['NumberOfthumbsupbyNonexperts']=$count['0']['NumberOfthumbsup']-$finalResult['3']['NumberOfthumbsupbyexperts'];
                $finalResult['3']['TotalNumberOfthumbsup']=$count['0']['NumberOfthumbsup'];
                
                /***********************************
                query to get number of thumbs down
                ***********************************/
                
                
                $query5= "SELECT COUNT(msgId) AS NumberOfthumbsdown FROM  `messageTable`
                                WHERE  creationDate  >= ? AND creationDate  <= ?
                                AND digDown = ? AND fromOthers = ?  AND userId IN(SELECT userId FROM
                                userpointsystembymodule where userpointvaluebymodule >='1000'
                                AND modulename='AnA')" ;
                                
                $count = $this->dbHandle->query($query5,array($modelInputs['Date2'],$modelInputs['Date3'],'1','User'));
                $count = $count->result_array();
                
                $finalResult['4']['field']="NumberOfthumbsdown";
                $finalResult['4']['NumberOfthumbsdownbyexperts']=$count['0']['NumberOfthumbsdown'];
                
                
                $query5= "SELECT COUNT(msgId) AS NumberOfthumbsdown FROM  `messageTable`  WHERE  creationDate  >= ? AND creationDate  <= ? AND digDown = ? AND fromOthers = ?" ;
                $count = $this->dbHandle->query($query5,array($modelInputs['Date2'],$modelInputs['Date3'],'1','User'));
                $count = $count->result_array();
                
                $finalResult['4']['NumberOfthumbsdownbyNonexperts']=$count['0']['NumberOfthumbsdown']-$finalResult['4']['NumberOfthumbsdownbyexperts'];
                $finalResult['4']['NumberOfthumbsdown']=$count['0']['NumberOfthumbsdown'];
                
                /************************************
                query to get number of report abuse
                ************************************/
                $query6= "SELECT COUNT(Id) AS NumberOfabuse FROM  `tReportAbuseLog`  WHERE  creationDate  >= ? AND creationDate  <= ? AND (entityType=? OR entityType=?) AND userId IN(SELECT userId FROM
                                userpointsystembymodule where userpointvaluebymodule >='1000'
                                AND modulename='AnA')" ;
                $count = $this->dbHandle->query($query6,array($modelInputs['Date2'],$modelInputs['Date3'],'Question','Answer'));
                $count = $count->result_array();
                
                $finalResult['5']['field']="NumberOfAbuse";
                $finalResult['5']['NumberOfabuseByExperts']=$count['0']['NumberOfabuse'];
                
                $query6= "SELECT COUNT(Id) AS NumberOfabuse FROM  `tReportAbuseLog`  WHERE  creationDate  >= ? AND creationDate  <= ? AND (entityType=? OR entityType=?)" ;
                $count = $this->dbHandle->query($query6,array($modelInputs['Date2'],$modelInputs['Date3'],'Question','Answer'));
                $count = $count->result_array();
                
                $finalResult['5']['NumberOfabuseByNonExperts']=$count['0']['NumberOfabuse']-$finalResult['5']['NumberOfabuseByExperts'];
                $finalResult['5']['NumberOfabuse']=$count['0']['NumberOfabuse'];
                
              
                 /*******************************
                query to get number of comments
                ********************************/
                  
                $query7= "SELECT COUNT(msgId) AS NumberOfComments FROM  `messageTable`  WHERE  creationDate  >= ? AND creationDate  <= ? AND mainAnswerId = parentId AND fromOthers = ? AND userId IN(SELECT userId FROM
                                userpointsystembymodule where userpointvaluebymodule >='1000'
                                AND modulename='AnA')"  ;
                $count = $this->dbHandle->query($query7,array($modelInputs['Date2'],$modelInputs['Date3'],'User'));
                $count = $count->result_array();
                
                $finalResult['6']['field']="NumberOfComments";
                $finalResult['6']['NumberOfcommentsByExperts']=$count['0']['NumberOfComments'];
                
                $query7= "SELECT COUNT(msgId) AS NumberOfComments FROM  `messageTable`  WHERE  creationDate  >= ? AND creationDate  <= ? AND mainAnswerId = parentId AND fromOthers = ?" ;
                $count = $this->dbHandle->query($query7,array($modelInputs['Date2'],$modelInputs['Date3'],'User'));
                $count = $count->result_array();
                
                $finalResult['6']['NumberOfcommentsBynonExperts']=$count['0']['NumberOfComments']- $finalResult['6']['NumberOfcommentsByExperts'];
                $finalResult['6']['totalNumberOfComments']=$count['0']['NumberOfComments'];
             
              
                /***********************************
                query to get number of best answers
                /************************************/
                
              
                $query7= "SELECT COUNT( bestAnsId ) AS NumberOfBestAnswers, userId
                                FROM  `messageTableBestAnsMap` m1, messageTable m2
                                WHERE m1.creation_time >=  ?
                                AND m1.creation_time <=  ?
                                AND m1.bestAnsId = m2.msgId
                                AND userId
                                IN (
                                SELECT userId
                                FROM userpointsystembymodule
                                WHERE userpointvaluebymodule >=  '1000'
                                AND modulename =  'AnA'
                                )GROUP BY userId";
                $count = $this->dbHandle->query($query7,array($modelInputs['Date2'],$modelInputs['Date3']));
                $count = $count->result_array();
                
                $finalResult['7']['field']="NumberOfBestAnswers";
                $finalResult['7']['NumberOfBestAnswersbyExperts']=$count['0']['NumberOfBestAnswers'];
                
                $query7= "SELECT COUNT(bestAnsId) AS NumberOfBestAnswers FROM  `messageTableBestAnsMap` WHERE  creation_time  >= ? AND creation_time  <= ? " ;
                $count = $this->dbHandle->query($query7,array($modelInputs['Date2'],$modelInputs['Date3']));
                $count = $count->result_array();
               
                $finalResult['7']['NumberOfBestAnswersbyNonExperts']=$count['0']['NumberOfBestAnswers']-$finalResult['7']['NumberOfBestAnswersbyExperts'];
                $finalResult['7']['NumberOfBestAnswers']=$count['0']['NumberOfBestAnswers']; 
                  
                /************************************************
                 query to get number of experts from onboarding
                 ***********************************************/
                
                $query7= "SELECT COUNT(userId) AS NumberOfExpertsByOnBoarding FROM  `expertOnboardTable`  WHERE  creationDate  >= ? AND creationDate  <= ? " ;
                $count = $this->dbHandle->query($query7,array($modelInputs['Date2'],$modelInputs['Date3']));
                $count = $count->result_array();
                $finalResult['8']['field']="ExpertsCount";
                $finalResult['8']['NumberOfExpertsByOnBoarding']=$count['0']['NumberOfExpertsByOnBoarding'];
                
                /***************************************************
                 query to get number of experts  by doing activity
                 /**************************************************/
                
                $query8= "SELECT COUNT(userId) AS NumberOfExpertsByActivity FROM  `userpointsystembymodule`  WHERE  lastLevelUpgradedTime   >= ? AND lastLevelUpgradedTime   <= ? AND userpointvaluebymodule >='1000' AND modulename='AnA' and userpointvaluebymodule <'2500'";  
                $count = $this->dbHandle->query($query8,array($modelInputs['Date2'],$modelInputs['Date3']));
                $count = $count->result_array();
                $finalResult['8']['NumberOfExpertsByActivity']=$count['0']['NumberOfExpertsByActivity']-$finalResult['NumberOfExpertsByOnBoarding'];
                
              
                $finalResult['8']['totalNumberOfExperts']= $finalResult['8']['NumberOfExpertsByOnBoarding']+$finalResult['8']['NumberOfExpertsByActivity'];
              
                
                 //query to get users who asked question for last 15 days considering past 1 day in each loop..
                $queryx="SELECT DISTINCT userId FROM messageTable where creationDate  >= ? AND creationDate  <= ? AND fromOthers = 'User' AND mainAnswerId = '-1'"; 
                $daybfreyesterdaysDate = mktime(0,0,0,date("m"),date("d")-2,date("Y"));
                $daybfreyesterdaysDate=date("Y-m-d 00:00:00", $daybfreyesterdaysDate);
                
                $count = $this->dbHandle->query($queryx,array($modelInputs['Date1'],$daybfreyesterdaysDate));
                $resul = $count->result_array();
                
                
                $x=array();
                
                //loop to get data for each user one by one by matching their userids
                $i=9;
                foreach($resul as $res){
                        
                        $userId=$res['userId'];
                        
                        $x['date']=date("d-m-Y",strtotime($modelInputs['Date2']));
                        $finalResult[$i]= $x;
                        
                        
                      
                        
                        $query9="SELECT count(userId) AS numberofQues FROM messageTable where creationDate  >= ? AND creationDate  <= ? AND fromOthers = 'User' AND mainAnswerId = '-1' and userId= $userId GROUP BY userId "; 
                        $count = $this->dbHandle->query($query9,array($modelInputs['Date2'],$modelInputs['Date3']));
                       
                        $count = $count->result_array();
                         
                        
                        if($count!=null){
                                foreach($count as $c){
                                        $x['numberOfQues']+= $c['numberofQues'];
                                       
                                                
                                }
                        }
                        else{
                                $x['numberOfQues']+=0;
                        }
                        $finalResult[$i]= $x;
                }
                
                $queryx="SELECT DISTINCT userId FROM messageTable where creationDate  >= ? AND creationDate  <= ? AND fromOthers = 'User' AND mainAnswerId = '-1'"; 
                $count = $this->dbHandle->query($queryx,array($modelInputs['Date1'],$modelInputs['Date2']));
                $resul = $count->result_array();
                
                  foreach($resul as $res){
                        
                        $userId=$res['userId'];
                        

                        $query10="SELECT userId,COUNT(userId) AS numberofAns FROM messageTable where creationDate  >= ? AND creationDate  <= ? AND fromOthers = 'User' AND mainAnswerId = '0' and userId= $userId GROUP BY userId"; 
                        $count = $this->dbHandle->query($query10,array($modelInputs['Date2'],$modelInputs['Date3']));
                        $count = $count->result_array();
                      
                
                        if($count!=null){
                                foreach($count as $c){
                                        $x['numberofAns']+=$c['numberofAns'];  
                                }
                        }
                        else{
                                $x['numberOfAns']+=0;    
                        }
                
                        $finalResult[$i]=$x;
                        
                        
                        $query11="SELECT userId,COUNT(userId) AS NumberOfthumbsup FROM messageTable where creationDate  >= ? AND creationDate  <= ? AND fromOthers = 'User'and userId= $userId  AND digUp =1 GROUP BY userId"; 
                        $count = $this->dbHandle->query($query11,array($modelInputs['Date2'],$modelInputs['Date3']));
                        $count = $count->result_array();
                
                                        
                        if($count!=null){
                                foreach($count as $c){
                                        $x['NumberOfthumbsup']+=$c['NumberOfthumbsup'];  
                                }
                        }
                        else{
                                $x['NumberOfthumbsup']+=0;    
                        }
                
                        $finalResult[$i]=$x;
                        
                        
                        $query12="SELECT userId,COUNT(userId) AS NumberOfthumbsdown FROM messageTable where creationDate  >= ? AND creationDate  <= ? AND fromOthers = 'User'and userId= $userId AND digDown ='1' GROUP BY userId"; 
                        $count = $this->dbHandle->query($query12,array($modelInputs['Date2'],$modelInputs['Date3']));
                        $count = $count->result_array();
                
                        if($count!=null){
                                foreach($count as $c){
                                        $x['NumberOfthumbsdown']+=$c['NumberOfthumbsdown'];  
                                }
                        }
                        else{
                                $x['NumberOfthumbsdown']+=0;    
                        }
                        $finalResult[$i]=$x;
                        
                        //query to get number of report abuse
                
                        $query13= "SELECT COUNT(userId) AS NumberOfabuse FROM  `tReportAbuseLog`  WHERE  creationDate  >= ? AND creationDate  <= ? AND (entityType=? OR entityType=?) AND userId= $userId " ;
                        $count = $this->dbHandle->query($query13,array($modelInputs['Date2'],$modelInputs['Date3'],'Question','Answer'));
                        $count = $count->result_array();
                        
                        if($count!=null){
                                foreach($count as $c){
                                        $x['NumberOfabuse']+=$c['NumberOfabuse'];  
                                }
                        }
                        else{
                                $x['NumberOfabuse']+=0;    
                        }
                        $finalResult[$i]=$x;
                        
                        
                        
                        //query to get number of best answers selected by user 
                        $query14  = "SELECT COUNT(userId) as NumberOfBestAnswers FROM messageTable t1 LEFT JOIN  messageTableBestAnsMap t2 ON t1.msgId = t2.threadId WHERE creation_time  >= ? AND creation_time  <= ? AND userId= $userId " ;                        
                        $count = $this->dbHandle->query($query14,array($modelInputs['Date2'],$modelInputs['Date3']));
                        $count = $count->result_array();
                
                                        
                        if($count!=null){
                                foreach($count as $c){
                                        $x['NumberOfBestAnswers']+=$c['NumberOfBestAnswers'];  
                                }
                        }
                        else{
                                $x['NumberOfBestAnswers']+=0;    
                        }
                
                        $finalResult[$i]=$x;
                       
                       
                       
                        //query to get number of comments
                        $query15= "SELECT COUNT(userId) AS NumberOfComments FROM  `messageTable`  WHERE  creationDate  >= ? AND creationDate  <= ? AND mainAnswerId = parentId AND fromOthers = ? AND userId=$userId GROUP BY userId" ;
                        $count = $this->dbHandle->query($query15,array($modelInputs['Date2'],$modelInputs['Date3'],'User'));
                        $count = $count->result_array();
                        
                        if($count!=null){
                                foreach($count as $c){
                                        $x['NumberOfComments']+=$c['NumberOfComments'];  
                                }
                        }
                        else{
                                $x['NumberOfComments']+=0;    
                        }
                
                        $finalResult[$i]=$x;
                        
                        //no of times the user who asked questn logged in that day..
                        $query16= "SELECT  count(userId) as LoginNumber FROM  `tuserLoginTracking` WHERE  `activityTime` >= ? AND  `activityTime` <= ? AND userId=? AND activity='Login' ";
                        $count = $this->dbHandle->query($query16,array($modelInputs['Date2'],$modelInputs['Date3'],$userId));
                        $count = $count->result_array();
        
                        
                        if($count!=null){
                                foreach($count as $c){
                                       $x['activityRecord']+=$c['LoginNumber'];
                                }
                        }
                        else{
                                $x['activityRecord']+=0;    
                        }
                
                        $finalResult[$i]=$x;
                
                
                        //users who became lead        
                        $query17= "SELECT  `userid` as leads,count(userid) as numofLeads
                                     FROM  `tLeadInfo` 
                                     WHERE  `submitdate` >=  ?
                                     AND  `submitdate` <=  ?
                                     and userid=$userId
                                     GROUP BY userid";

                        $count = $this->dbHandle->query($query17,array($modelInputs['Date2'],$modelInputs['Date3']));
                        $count = $count->result_array();
                                
                      
                        if($count!=null){
                                foreach($count as $c){
                                        $x['ifLead']+=$c['numofLeads'];
                                        $finalResult[$i]=$x;
                                }
                        }
                        else{
                                $x['ifLead']+=0;
                                $finalResult[$i]=$x;
                        }
                
                        
                
                        //users who viewed listing
                        $query18="SELECT userId, COUNT( userId ) AS vCount
                                        FROM  tempLMSTable 
                                        WHERE  submit_date >=  ?
                                        AND submit_date <=  ?
                                        AND  action LIKE  'Viewed_Listing' and userId=$userId
                                        AND listing_subscription_type='paid'
                                        GROUP BY userId";
             
                
                        $count = $this->dbHandle->query($query18,array($modelInputs['Date2'],$modelInputs['Date3']));
                        $count = $count->result_array();
                              
                        if($count!=null){
                                foreach($count as $c){
                                        $x['viewListing']+=$c['vCount'];
                                        $finalResult[$i]=$x;
                                }
                        }
                        else{
                                $x['viewListing']+=0;
                                $finalResult[$i]=$x;
                        }
                      
                       
                
                        //users who became response
                        $query19 = "SELECT userId, COUNT( userId ) AS vCount
                                        FROM  `tempLMSTable` 
                                        WHERE  `submit_date` >=  ?
                                        AND submit_date <=  ?
                                        AND  `action` LIKE  'Request_E-Brochure' and userId=$userId
                                        AND listing_subscription_type='paid'
                                        GROUP BY userId";
                        
                        $count = $this->dbHandle->query($query19,array($modelInputs['Date2'],$modelInputs['Date3']));
                        $count = $count->result_array();
                              
                        if($count!=null){
                                foreach($count as $c){
                                        $x['response']+=$c['vCount'];
                                        $finalResult[$i]=$x;
                                }
                        }
                        else{
                                $x['response']+=0;
                                $finalResult[$i]=$x;
                        }
                
                }
                
                return  $finalResult;
        }
}

?>
