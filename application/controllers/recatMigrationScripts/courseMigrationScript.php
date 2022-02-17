    <?php
    define('XLSPATH','/var/www/trunk/etc/XLSReader/');
    require_once XLSPATH.'excel_reader2.php';

    class CourseMigrationScript extends MX_Controller {    
        
    function index(){
        $this->load->model('recatscriptmodel');  
        $this->baseattributemodel=$this->load->model('listingBase/baseattributemodel');        
        $this->load->library("examPages/ExamMainLib");
        $this->examMainLib = new ExamMainLib();

        $start=0;
        $batchSize=100;

        $oldCourses=$this->recatscriptmodel->getOldCourses($start,$batchSize);
        while(count($oldCourses)>0  && $start<100){
            $course_id_arr=array();
            $course_id_csv='';
            for($i=0;$i<count($oldCourses);$i++)
            {
                $course_id_arr['id'][]=$oldCourses[$i]['course_id'];
            }
            $course_id_csv=implode(',', $course_id_arr['id']);

            
            $courseTypeInformation=$this->recatscriptmodel->getOldCourseTypeData($course_id_csv);
            
            foreach ($courseTypeInformation as $value) {
                $new_course_type=$this->recatscriptmodel->getNewCourseTypeData($value);
                if(!empty($new_course_type['course_type']))
                {                    
                    $hierarchy_arr[$value[clientCourseID]]['course_type']=$new_course_type['course_type'][0];
                }
                 else{
                  $course_id_arr[id]=array_diff($course_id_arr[id],array($value[clientCourseID])); //remove from original array if 
                                                                                                   //hierarchy not found
                }

            }

            $course_id_csv=implode(',', $course_id_arr['id']);
            $listingMainAttributes=$this->recatscriptmodel->getlistingMainAttributes($course_id_csv);
            $eligibleExams=$this->recatscriptmodel->getEligibleExams($course_id_csv);

            for($j=0;$j<count($eligibleExams);$j++){
                $exam[$eligibleExams[$j]['typeId']][$eligibleExams[$j]['examId']]['marks']=$eligibleExams[$j]['marks'];
                $exam[$eligibleExams[$j]['typeId']][$eligibleExams[$j]['examId']]['marks_type']=$eligibleExams[$j]['marks_type'];
           }
            for($j=0;$j<count($listingMainAttributes);$j++){
                $lm[$listingMainAttributes[$j]['listing_type_id']]['subscriptionId']=$listingMainAttributes[$j]['subscriptionId'];
                $lm[$listingMainAttributes[$j]['listing_type_id']]['expiry_date']=$listingMainAttributes[$j]['expiry_date'];
                $lm[$listingMainAttributes[$j]['listing_type_id']]['submit_date']=$listingMainAttributes[$j]['submit_date'];
                $lm[$listingMainAttributes[$j]['listing_type_id']]['last_modify_date']=$listingMainAttributes[$j]['last_modify_date'];
                $lm[$listingMainAttributes[$j]['listing_type_id']]['pack_type']=$listingMainAttributes[$j]['pack_type'];
                $lm[$listingMainAttributes[$j]['listing_type_id']]['client_id']=$listingMainAttributes[$j]['username'];
           }
            $query='';
            foreach($oldCourses as $d)
            {
                if(array_search($d['course_id'],$course_id_arr[id]))
                { 
                $arr['course_id']   =$d['course_id'];
                $arr['name']=  addslashes($d['courseTitle']);
                $arr['parent_id']=$d['institute_id'];
                $arr['parent_type']="institute";
                $arr['primary_institute_id']=$d['institute_id'];
                $arr['course_order']=$d['course_order'];
                $course_type=$this->baseattributemodel->getValueIdByValueName(array(strtolower('Academic'))); // Always academic, so can we haeccode same
                $arr['course_type']= $course_type[0]['value_id'];
                $old_course_level=$d['course_level'];
                if($old_course_level=='Dual Degree')
                {
                    $course_variant=$this->baseattributemodel->getValueIdByValueName(array(strtolower('Double'))); 
                    $arr['course_variant']= $course_variant[0]['value_id'];
                }else
                {
                    $course_variant=$this->baseattributemodel->getValueIdByValueName(array(strtolower('Single'))); 
                    $arr['course_variant']= $course_variant[0]['value_id'];
                }
                $arr['executive']=null;
                $arr['twinning']=1;
                $arr['integrated']=0;
                $arr['dual']=0;
                $old_course_type=$d['course_type'];
                if($old_course_type=='Full Time')
                { 
                    $education_type=$this->baseattributemodel->getValueIdByValueName(array(strtolower('Full Time'))); 
                    $arr['education_type']= $education_type[0]['value_id'];
                }elseif($old_course_type=='Part Time')
                {
                    $education_type=$this->baseattributemodel->getValueIdByValueName(array(strtolower('Part Time'))); 
                    $arr['education_type']= $education_type[0]['value_id'];
                }else{
                    $education_type=$this->baseattributemodel->getValueIdByValueName(array(strtolower('Full Time'))); 
                    $arr['education_type']= $education_type[0]['value_id'];
                }

                
                $arr['subscription_id']=$lm[$d['course_id']]['subscriptionId'];
                $arr['expiry_date']=$lm[$d['course_id']]['expiry_date'];
                $arr['pack_type']=$lm[$d['course_id']]['pack_type'];
                $arr['client_id']=$lm[$d['course_id']]['client_id'];
                if($old_course_type=='Correspondence')
                {
                    $delivery_method=$this->baseattributemodel->getValueIdByValueName(array(strtolower('Correspondence'))); 
                    $arr['delivery_method']= $delivery_method[0]['value_id'];
                }elseif($old_course_type=='E - learning' || $old_course_type=='E Learning' || $old_course_type=='E-learning')
                {
                   //  _p($old_course_type);
                    $delivery_method=$this->baseattributemodel->getValueIdByValueName(array(strtolower('Online'))); 
                    $arr['delivery_method']= $delivery_method[0]['value_id'];
                }else
                    $arr['delivery_method']= null;
                $arr['status']= "live";
                $arr['created_on']= $lm[$d['course_id']]['submit_date'];
                $arr['created_by']= 1;
                $arr['updated_on']= $lm[$d['course_id']]['last_modify_date'];
                $arr['updated_by']= 1;
                
                $queryarr[]=implode("','", $arr);
                
                //******************Prepare Fees Data*******************/
                $feesarr['course_id']=$d['course_id'];
                $feesarr['fees_value']=$d['fees_value'];
                $feesarr['fees_unit']=$d['fees_unit'];
                $feesarr['fees_type']="total";
                $feesarr['category']='GEN';
                $feesarr['period']='year';
                $feesarr['status']= "live";
                $feesarr['created_on']= $lm[$d['course_id']]['submit_date'];
                $feesarr['created_by']= 1;
                $feesarr['updated_on']= $lm[$d['course_id']]['last_modify_date'];
                $feesarr['updated_by']= 1;
                $queryfeesarr[]=implode("','", $feesarr);

                //$d['course_id']=818;
                /* ****************Prepare Exams Data**********************/
                foreach ($exam[$d['course_id']] as $examid => $examdata) {                
                    $examarr['course_id']=$d['course_id'];
                    $examarr['exam_id']=$examid;
                    $examName = $this->examMainLib->getExamDetailsByIds($examarr['exam_id']);
                    $examarr['exam_name']=$examName[$value['exam_id']]['examName'];
                    
                    $examarr['marks']=$examdata['marks'];
                    $examarr['marks_type']=$examdata['marks_type'];
                    $examarr['status']= "live";
                    $examarr['created_on']= $lm[$d['course_id']]['submit_date'];
                    $examarr['created_by']= 1;
                    $examarr['updated_on']= $lm[$d['course_id']]['last_modify_date'];
                    $examarr['updated_by']= 1;
                    $queryexamarr[]=implode("','", $examarr);
                }
                
        

        /****************************Course Type Information *******************/
        //_p($hierarchy_arr);
        //_p($d['course_id']);
        foreach ($hierarchy_arr[$d['course_id']] as $key => $value) {                         
                    $typeInfo['course_id']=$d['course_id'];
                    $typeInfo['type']="entry";
                    $typeInfo['credential'] = $value['credential'];
                    $typeInfo['course_level']= $value['level'] ;              
                    $typeInfo['base_course']=$value['course_id'] ;  
                    $typeInfo['stream_id']=$value['stream_id'] ;  
                    $typeInfo['substream_id']=$value['substream_id'] ;  
                    $typeInfo['specialization_id']=$value['specialization_id'] ;  
                    $typeInfo['primary_hierarchy']=0;
                    $typeInfo['status']= "live";
                    $typeInfo['created_on']= $lm[$d['course_id']]['submit_date'];
                    $typeInfo['created_by']= 1;
                    $typeInfo['updated_on']= $lm[$d['course_id']]['last_modify_date'];
                    $typeInfo['updated_by']= 1;
                    $querytypeInfo[]=implode("','", $typeInfo);
                }
            }
        }
        //_p($hierarchy_arr);
        //_p($arr);
        //_p($queryarr);    
        //_p($examarr);
        //_p($queryexamarr);
        //_p($queryfeesarr);
       // die();
        // $courseaddresult=$this->recatscriptmodel->migratetoNewCourse($queryarr);
        // $courseInforesult=$this->recatscriptmodel->migrateCourseTypeData($querytypeInfo);
        // $examaddresult=$this->recatscriptmodel->migrateExamData($queryexamarr);
        // $feesaddresult=$this->recatscriptmodel->migratefeesData($queryfeesarr);
        // $start=$start+$batchSize;
        $queryarr='';
        $querytypeInfo='';
        $queryexamarr='';
        $queryfeesarr='';
        $oldCourses=$this->recatscriptmodel->getOldCourses($start,$batchSize);
        }


    }

    function parseSheet($sheet){
        
            $data = new Spreadsheet_Excel_Reader($sheet);
            $sheets=count($data->sheets);
            $sheetData = array();
            for($k=0;$k<$sheets;$k++){
                $sheetData=array();
                $rows = $data->rowcount($k);
                $cols = $data->colcount($k);
           
                for($i=2; $i <= $rows; $i++) {
                    $details = array();
                    for($j=1; $j <= $cols; $j++){               
                        $details[] = $data->val($i, $j, $k);
                    }
                $sheetData[] = $details;        
            }
            $completeData[$data->boundsheets[$k][name]]=$sheetData;
    	}
        
    	_p("Processing done for XLS: " . $sheet);
    	_p("***************************************************************");
        return $completeData;
        
        }
    }
