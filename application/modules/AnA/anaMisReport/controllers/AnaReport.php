<?php

class AnaReport extends MX_Controller {
        
        function init(){
                //error_log(print_r(error_reporting(1)));
                $this->load->model('reportmodel');
        }
        
        //this calls the model function to get data for mis report
        function misDetails(){
                
                $this->init();
                 
                $yesterdaysDate = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
                $yesterdaysDate=date("Y-m-d 00:00:00", $yesterdaysDate);
                
                
                $DateToday = date("Y-m-d 00:00:00");
                $Datefifteendaysback = mktime(0,0,0,date("m"),date("d")-15,date("Y"));
                $Datefifteendaysback=date("Y-m-d 00:00:00", $Datefifteendaysback);
               
                
                $modelInputs = array();
                
                $modelInputs['Date1']=$Datefifteendaysback;
                $modelInputs['Date2']=$yesterdaysDate;    
                $modelInputs['Date3']=$DateToday;                
                
              
                
                $finalResult=$this->reportmodel->getDataForMisReport($modelInputs);
               //_p($finalResult);die();
            
                $this->writeCsv($finalResult);
                
            }
            
            function writeCsv($finalResult){
                                $FileName = "MIS_REPORT_Last_15_Days".date("d-m-y").'.csv';
                                header('Content-Type: application/csv'); 
                                header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
                                
                                $Content = fopen("php://output", "w");
                                
                            
                                fputcsv($Content, array(date("d-m-y")));
                                echo "\n";       
                                fputcsv($Content, array('','EXPERTS','NON-EXPERTS','TOTAL'));
                                $i=0;
                                foreach($finalResult as $result){
                                    
                                        
                                        if($i!=8 && $i!=9)fputcsv($Content,$result);
                                        
                                        if($i==8){
                                              echo "\n";
                                              fputcsv($Content, array('','NumberOfExpertsByOnBoarding','NumberOfExpertsByActivity','TotalNumberOfExperts'));
                                              fputcsv($Content,$result);
                                        }
                                        
                                        if($i==9){
                                            echo "\n USER'S ACTIVITY STATISTICS \n\n";
                             
                                            fputcsv($Content, array('Date','NumberOfQuestionsPosted','NumberOfAnswersPosted','ThumbsUp','ThumbsDown','ReportAbuse','BestAnswer','NumberOfComments','NumberofTimesLoggedIn','NumberofLeads','NumberOfListingsViewed','NumberofResponse'));
                                            fputcsv($Content,$result);
                                        }
                                       
                                        $i++;
                                }
                                
                            fclose($outstream);
            }
            
    }
        

?>