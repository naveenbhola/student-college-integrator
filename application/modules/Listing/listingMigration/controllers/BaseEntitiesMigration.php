<?php

require_once XLSREADER_PATH.'/excel_reader2.php';

class BaseEntitiesMigration extends MX_Controller {    
    
    function index($spreadSheets){
        return;
        $this->load->model('baseentitiesmigrationmodel','recatscriptmodel');  
        $this->load->builder('ListingBaseBuilder', 'listingBase');
        $this->ListingBaseBuilder    = new ListingBaseBuilder();
        $this->hierarchyRepo = $this->ListingBaseBuilder->getHierarchyRepository();

        
        $this->logMessage($initialMessage,"\n\n\nStarting Migrating...");

        $spreadSheets = array(XLSREADER_PATH."BaseEntitiesMapping.xls");            
        $parsedSheetData=$this->parseSheet($spreadSheets[0]);
        foreach($parsedSheetData as $key=>$sheet){
            switch ($key){
            case 'Streams':
                $this->addStreams($sheet);
                break;
            case 'Sub-Streams':
                $this->addSubstreams($sheet);
                break;
            case 'Specialization':
                $this->addSpecializations($sheet);
                break;
            case 'Course':
                $this->addBasecourses($sheet);
                break;
            case 'Hierarchy Creation':
                $this->addHierarchies($sheet);
                break;
            case 'Course Mapping':
                $this->addCoursemapping($sheet);
                break;
            case 'SubCat + LDB Mapping':
                $this->addBaseEntityMigration($sheet);
                break;
            }
        }
    }

function addStreams($data){
    return;
    $this->logMessage("Adding Streams::");
    if(!empty($data))
    {
        foreach($data as $d){
             $d=array_filter($d);          
             if(!empty($d)){
                $arr=array();
                $arr['name']   =addslashes($d[0]);
                $arr['alias']  =addslashes($d[1]);
                $arr['synonym']=addslashes($d[2]) ;
                $arr['display_order']=$d[3];
        
                $stream_id=$this->recatscriptmodel->addStreams($arr);
                if(empty($stream_id)){
                    $this->logMessage("Adding Streams::Below stream not inserted");
                    $this->logMessage($arr);
                }
                else
                {
                    $hierarchyresult = $this->hierarchyRepo->insertIntoHierarchyTable(array('streamId'=>$stream_id));
                    // if($hierarchyresult['data']['status']=='fail')
                    //     $this->logMessage("Adding Streams::Hierarchy already exists for stream_id::".$stream_id);
                }
            }
        }
    }
$this->logMessage("Adding Streams::DONE");
_p("Streams added successfully");
}

function addSubstreams($data){
    return;
    $this->logMessage("Adding SubStreams::");
    if(!empty($data))
    {
        foreach($data as $d){
            $d=array_filter($d);          
            if(!empty($d)){
                $arr=array();
                $arr['name']   =addslashes($d[0]);
                $arr['alias']  =addslashes($d[1]);
                $arr['synonym']=addslashes($d[2]);
                $arr['display_order']=$d[3];
                $arr['primary_stream']=$this->recatscriptmodel->getIdbyName($d[4],'stream');
                if(empty($arr['primary_stream']) && !empty($d[4]))
                    $this->logMessage("Adding SubStreams:".$arr['name'].":Data not found for primary stream: ".$d[4]); 
            
                $substream_id=$this->recatscriptmodel->addSubstreams($arr);
                if(empty($substream_id)){
                    $this->logMessage("Adding SubStreams::Below substream not inserted");
                    $this->logMessage($arr);
                }

                if(!empty($substream_id) && !empty($arr['primary_stream']))
                {
                    $hierarchyresult = $this->hierarchyRepo->insertIntoHierarchyTable(array('streamId'=>$arr['primary_stream'],'substreamId'=>$substream_id));
                    // if($hierarchyresult['data']['status']=='fail')
                    //     $this->logMessage("Adding SubStreams:".$arr['name'].":Hierarchy already exists for substream_id::".$substream_id);
                }else
                {
                   $this->logMessage("Adding SubStreams::Below substream not inserted in Hierarchy as data is missing"); 
                   $this->logMessage($d[4]." : ".$arr['name']);
                }		
            }
        }
    }
 $this->logMessage("Adding SubStreams::DONE");   
_p("SubStreams added successfully");
}

function addSpecializations($data){
    return;
   $this->logMessage("Adding Specializations::");
   if(!empty($data))
    {
        foreach($data as $d){
            $d=array_filter($d);          
            if(!empty($d)){
                $arr=array();
                $arr['name']   =addslashes($d[0]);
                $arr['alias']  =addslashes($d[1]);
                $arr['synonym']=addslashes($d[2]);
                $arr['type']=strtolower($d[3]);

                $arr['primary_stream']=$this->recatscriptmodel->getIdbyName($d[4],'stream');
                if(empty($arr['primary_stream']) && !empty($d[4]))
                   $this->logMessage("Adding Specializations:".$arr['name'].":Data not found for primary stream: ".$d[4]); 

                $arr['primary_substream']=$this->recatscriptmodel->getIdbyName($d[5],'substream');
                if(empty($arr['primary_substream']) && !empty($d[5]))
                   $this->logMessage("Adding Specializations:".$arr['name'].":Data not found for primary substream: ".$d[5]); 
            
                $specialization_id=$this->recatscriptmodel->addSpecializations($arr);
                if(empty($specialization_id)){
                    $this->logMessage("Adding Specializations::Below specialization not inserted");
                    $this->logMessage($arr);
                }
                if(!empty($specialization_id) && (!empty($arr['primary_stream']) || !empty($arr['primary_substream']) ))
                {
                    $hierarchyresult = $this->hierarchyRepo->insertIntoHierarchyTable(array('streamId'=>$arr['primary_stream'],
                                                                                    'substreamId'=>$arr['primary_substream'],
                                                                                    'specializationId'=>$specialization_id)
                                                                            );
                // if($hierarchyresult['data']['status']=='fail')
                //     $this->logMessage("Adding Specializations:".$arr['name'].":Hierarchy already exists for specialization::".$specialization_id);
                }
                else
                {
                   $this->logMessage("Adding Specializations::Below specialization not inserted in Hierarchy as data is missing"); 
                   $this->logMessage($d[4]." : ". $d[5]." : ".$arr['name']);
                }		
            } 
        }
    
}
$this->logMessage("Adding Specializations::DONE");
_p("Specializations added successfully");
}

function addBasecourses($data)
{
    return;
   $this->logMessage("Adding Basecourses::");
   if(!empty($data))
    {
        $this->baseattributemodel=$this->load->model('listingBase/baseattributemodel');        
        foreach($data as $d){
            $d=array_filter($d);          
            if(!empty($d)){
                $arr=array();
                $arr['name']   =addslashes($d[0]);
                $arr['alias']  =addslashes($d[1]);
                $arr['synonym']=addslashes($d[2]);
                $credentialArr=explode(',', $d[3]);

                foreach ($credentialArr as $key => $credential) {
                    $credentialDetails=$this->baseattributemodel->getValueIdByValueName(array(strtolower(trim($credential))));
                    $arr['credential_'.$key]= $credentialDetails[0]['value_id'];
                    if(empty($arr['credential_'.$key]) && !empty($credential))
                        $this->logMessage("Adding Basecourses:". $arr['name']." :credential missing");
                 
                }

                $courselevelDetails=$this->baseattributemodel->getValueIdByValueName(array(strtolower($d[4])));
                $arr['courselevel']=$courselevelDetails[0]['value_id'];            
                if(empty($arr['courselevel']) && !empty($d[4]))
                    $this->logMessage("Adding Basecourses:". $arr['name']." :course level missing");

                if($arr['credential_0']=='11' && empty($arr['courselevel']))
                    $arr['courselevel']=19;
                $arr['is_executive']=$d[5]=='Yes'?1:0;
                $arr['is_popular']=$d[6]=='Yes'?1:0;
                $arr['is_hyperlocal']=$d[7]=='Yes'?1:0; 
            
                $basecourse_id=$this->recatscriptmodel->addBasecourses($arr);
                if(empty($basecourse_id)){
                    $this->logMessage("Adding Basecourses::Below Basecourse is not inserted");
                    $this->logMessage($arr);
                }
            }
        }
    } 
$this->logMessage("Adding Basecourses::DONE");
_p("Basecourses added successfully");
}

function addHierarchies($data)
{
    return;
    $this->logMessage("Adding Hierarchies::");
    if(!empty($data))
    {  
        foreach($data as $d){
            $d=array_filter($d);          
            if(!empty($d)){                
                $primary_stream=$this->recatscriptmodel->getIdbyName($d[0],'stream');
                if(empty($primary_stream) && !empty($d[0]))
                   $this->logMessage("Adding Hierarchies::Data not found for primary stream: ".$d[0]); 

                $primary_substream=$this->recatscriptmodel->getIdbyName($d[1],'substream');
                if(empty($primary_substream) && !empty($d[1]))
                   $this->logMessage("Adding Hierarchies::Data not found for primary substream: ".$d[1]); 
            
                $primary_specialization=$this->recatscriptmodel->getIdbyName($d[2],'specialization');
                if(empty($primary_specialization) && !empty($d[2]))
                   $this->logMessage("Adding Hierarchies::Data not found for primary specialization: ".$d[2]); 

               if(!empty($primary_stream)){
                $hierarchyresult = $this->hierarchyRepo->insertIntoHierarchyTable(array('streamId'=>$primary_stream,'substreamId'=>$primary_substream,'specializationId'=>$primary_specialization));
                // if($hierarchyresult['data']['status']=='fail')
                //     $this->logMessage("Adding Hierarchies::Hierarchy already exists::\n stream:".$primary_stream.' substream:'.$primary_substream.' Specialization:'.$primary_specialization);
                }else
                {
                    $this->logMessage("Adding Hierarchies::Data not inserted in Hierarchy");  
                    $this->logMessage($d[0]." : ". $d[1]." : ".$d['2']); 
                }
        }
    }
}
$this->logMessage("Adding Hierarchies::DONE");
_p("Hierarchies added successfully");
}

function addCoursemapping($data){
    return;
    $this->logMessage("Adding coursemapping::");
    if(!empty($data))
    {  
        foreach($data as $d){
            $d=array_filter($d);          
            if(!empty($d)){   
                $hierarchy_id='';
                $stream=$this->recatscriptmodel->getIdbyName($d[0],'stream');
                if(empty($stream) && !empty($d[0]))
                   $this->logMessage("Adding coursemapping::Data not found for primary stream: ".$d[0]);

                $substream=$this->recatscriptmodel->getIdbyName($d[1],'substream');
                if(empty($substream) && !empty($d[1]))
                   $this->logMessage("Adding coursemapping::Data not found for primary substream: ".$d[1]);
            
                $specialization=$this->recatscriptmodel->getIdbyName($d[2],'specialization');
                if(empty($specialization) && !empty($d[2]))
                   $this->logMessage("Adding coursemapping::Data not found for primary specialization: ".$d[2]);
            
                $baseCourse=$this->recatscriptmodel->getIdbyName($d[3],'course');
                if(empty($baseCourse) && !empty($d[3]))
                   $this->logMessage("Adding coursemapping::Data not found for base course: ".$d[3]);
       
                $substream_id=!empty($substream)?$substream:'none';
                $specialization_id=!empty($specialization)?$specialization:'none';
                //echo "stream:".$stream. $d[0]." SubStreams:".$substream.$d[1]." Specialization:".$specialization.$d[2];
                $hierarchy_id=$this->hierarchyRepo->getHierarchyIdByBaseEntities($stream,$substream_id,$specialization_id);
            
                if(empty($hierarchy_id[0])){
                    $hierarchyresult = $this->hierarchyRepo->insertIntoHierarchyTable(array('streamId'=>$stream,'substreamId'=>$substream,'specializationId'=>$specialization));
                    $hierarchy_id=$hierarchyresult['data']['hierarchy_id'];
                    // if($hierarchyresult['data']['status']=='fail')
                    //     $this->logMessage("Adding coursemapping::Hierarchy already exists::\n stream:".$primary_stream.' substream:'.$primary_substream.' Specialization:'.$primary_specialization);
                }

                if(!empty($baseCourse) && !(empty($stream))){
                    $temp=array();
                    $temp['hierarchy_id'] = $hierarchy_id[0];
                    $temp['entity_id'] = $baseCourse;
                    $temp['entity_type'] ='base_course';
                    $temp['stream_id'] = $stream;
                    $temp['substream_id'] = empty($substream)?'null':$substream;
                    $temp['specialization_id'] = empty($specialization)?'null':$specialization;
                    $mapping_id=$this->recatscriptmodel->addEntityHierarchyMapping($temp);

                if(empty($mapping_id)){
                    $this->logMessage("Adding coursemapping::Below course mapping is not inserted");
                    $this->logMessage($temp);
                }
            }
        } 
    }
    }
    $this->logMessage("Adding coursemapping::DONE");
    _p("Course to Hierarchy added successfully");
}

function addBaseEntityMigration($data)
{   
    return;
    $this->logMessage("Adding BaseEntityMapping::");
    $this->baseattributemodel=$this->load->model('listingBase/baseattributemodel');

    if(!empty($data))
    {   
        foreach($data as $d)
        {
            $d=array_filter($d);          
            if(!empty($d))
            { 
            $arr=array();
            $arr['oldcategory_id']=$d[0];
            $oldsubcategory_arr=$this->recatscriptmodel->getOldSubCategoryId($d[2],$d[0]);
            $arr['oldsubcategory_id']=$oldsubcategory_arr[0]['boardId'];
            if(empty($arr['oldsubcategory_id']) && !empty($d[2]))
                   $this->logMessage("Adding BaseEntityMapping::Data not found for old subcategory: ".$d[2]);

            $oldspecialization_arr=$this->recatscriptmodel->getOldSpecializationId($d[3],$d[0],$arr['oldsubcategory_id']);
            $arr['oldspecialization_id']=$oldspecialization_arr[0]['SpecializationId'];
            if(empty($arr['oldspecialization_id']) && !empty($d[4]))
                   $this->logMessage("Adding BaseEntityMapping::Data not found for old Specialization: ".$d[4].' ID :'.$d[3]);

            $arr['stream']=$this->recatscriptmodel->getIdbyName($d[6],'stream');
            if(empty($arr['stream']) && !empty($d[6]))
                   $this->logMessage("Adding BaseEntityMapping::Data not found for primary stream: ".$d[6]);

            $arr['substream']=$this->recatscriptmodel->getIdbyName($d[7],'substream');
            if(empty($arr['substream']) && !empty($d[7]))
                   $this->logMessage("Adding BaseEntityMapping::Data not found for primary substream: ".$d[7]);

            $arr['specialization']=$this->recatscriptmodel->getIdbyName($d[8],'specialization');
            if(empty($arr['specialization']) && !empty($d[8]))
                   $this->logMessage("Adding BaseEntityMapping::Data not found for primary specialization: ".$d[8]);

            $arr['baseCourse']=$this->recatscriptmodel->getIdbyName($d[9],'course');
            if(empty($arr['baseCourse']) && !empty($d[9]))
                   $this->logMessage("Adding BaseEntityMapping::Data not found for base course: ".$d[9]);

            $deliverymethod=$this->baseattributemodel->getValueIdByValueName(array(strtolower($d[11])));
            $arr['deliverymethod']=$deliverymethod[0]['value_id'];            
            if(empty($arr['deliverymethod']) && !empty($d[11]))
                $this->logMessage("Adding BaseEntityMapping::deliverymethod missing::".$d[11]);

            $educationtypeDetails=$this->baseattributemodel->getValueIdByValueName(array(strtolower($d[10])));
            $arr['educationtype']=$educationtypeDetails[0]['value_id'];            
            if(empty($arr['educationtype']) && !empty($d[10]))
                $this->logMessage("Adding BaseEntityMapping::educationtype missing::".$d[10]);
            
            $credentialDetails=$this->baseattributemodel->getValueIdByValueName(array(strtolower($d[12])));
            $arr['credential']= $credentialDetails[0]['value_id'];
            if(empty($arr['credential']) && !empty($d[12]))
                $this->logMessage("Adding BaseEntityMapping::credential missing::".$d[12]);
            
            $courselevelDetails=$this->baseattributemodel->getValueIdByValueName(array(strtolower($d[13])));
            $arr['courselevel']=$courselevelDetails[0]['value_id'];  
            if(empty($arr['courselevel']) && !empty($d[13]))
                $this->logMessage("Adding BaseEntityMapping::courselevel missing::".$d[13]);          

            $mapping_id=$this->recatscriptmodel->addBaseEntityMigration($arr);
            if(empty($mapping_id)){
                    $this->logMessage("Adding BaseEntityMapping::Below entity type mapping is not inserted");
                    $this->logMessage($arr);
            }
        }
    }
    }
    _p("Old entities (category/subcategory) to new entities mappping added successfully");
}

function parseSheet($sheet){
    return;
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
                    $details[] = trim($data->val($i, $j, $k));
                }
            $sheetData[] = $details;        
        }
        $completeData[$data->boundsheets[$k][name]]=$sheetData;
	}
    
	_p("Processing done for XLS: " . $sheet);
	_p("***************************************************************");
    return $completeData;
    
    }
    private static function logMessage($messageToLog, $mode = FILE_APPEND)
    {
        return;
        $logFileName    = "/tmp/baseentitiesmigration-log" . date('Y-m-d');

        if ($logFileName == '') {
            $logFileName = "./log" . date('Y-m-d H:i:s');
        }

        if (gettype($messageToLog) == 'string' && $messageToLog != '') { // in case of string
            file_put_contents($logFileName, "\n" . $messageToLog, $mode); // add a new line for the sake of clarity
        } else if (gettype($messageToLog) == 'array' && count($messageToLog) > 0) { // in case of array
            file_put_contents($logFileName, "\n" . print_r($messageToLog, true), $mode); // add a new line for the sake of clarity
        } else if (gettype($messageToLog) == 'object' && $messageToLog !== null) {
            file_put_contents($logFileName, "\n" . print_r($messageToLog, true), $mode); // add a new line for the sake of clarity
        }
    }

}
