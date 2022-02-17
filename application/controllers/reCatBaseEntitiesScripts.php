<?php

require_once XLSREADER_PATH.'/excel_reader2.php';

class ReCatBaseEntitiesScripts extends MX_Controller {    
    
    function index($spreadSheets){
        $this->load->model('recatscriptmodel');  

        $this->load->builder('ListingBaseBuilder', 'listingBase');
        $this->ListingBaseBuilder    = new ListingBaseBuilder();
        $this->hierarchyRepo = $this->ListingBaseBuilder->getHierarchyRepository();
        
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
             case 'LDB Course Migration':
                $this->addBaseEntityMigration($sheet);
                break;
            }
        }
    }

function addStreams($data){     
    if(!empty($data))
    {
        foreach($data as $d){
            $arr['name']   =addslashes($d[0]);
            $arr['alias']  =addslashes($d[1]);
            $arr['synonym']=addslashes($d[2]) ;
            $arr['display_order']=$d[3];
        
        $stream_id=$this->recatscriptmodel->addStreams($arr);
        if(!empty($stream_id))
        {
            $hierarchyresult = $this->hierarchyRepo->insertIntoHierarchyTable(array('streamId'=>$stream_id));
            
        }		
    }
}
_p("Streams added successfully");
}

function addSubstreams($data){
    if(!empty($data))
    {
        foreach($data as $d){
            $arr['name']   =addslashes($d[0]);
            $arr['alias']  =addslashes($d[1]);
            $arr['synonym']=addslashes($d[2]);
            $arr['display_order']=$d[3];
            $arr['primary_stream']=$this->recatscriptmodel->getIdbyName($d[4],'stream');
            
        $substream_id=$this->recatscriptmodel->addSubstreams($arr);
        if(!empty($substream_id) && !empty($arr['primary_stream']))
        {
            $hierarchyresult = $this->hierarchyRepo->insertIntoHierarchyTable(array('streamId'=>$arr['primary_stream'],'substreamId'=>$substream_id));
           
        }		
    }
}
_p("SubStreams added successfully");
}

function addSpecializations($data){
   if(!empty($data))
    {
        foreach($data as $d){
            $arr['name']   =addslashes($d[0]);
            $arr['alias']  =addslashes($d[1]);
            $arr['synonym']=addslashes($d[2]);
            $arr['type']=strtolower($d[3]);
            $arr['primary_stream']=$this->recatscriptmodel->getIdbyName($d[4],'stream');
            $arr['primary_substream']=$this->recatscriptmodel->getIdbyName($d[5],'substream');
            
        $specialization_id=$this->recatscriptmodel->addSpecializations($arr);
        if(!empty($specialization_id) && (!empty($arr['primary_stream']) || !empty($arr['primary_substream']) ))
        {
            $hierarchyresult = $this->hierarchyRepo->insertIntoHierarchyTable(array('streamId'=>$arr['primary_stream'],
                                                                                    'substreamId'=>$arr['primary_substream'],
                                                                                    'specializationId'=>$specialization_id)
                                                                            );
             
        }		
    }
}
_p("Specializations added successfully");
}
function addBasecourses($data)
{
   if(!empty($data))
    {
        $this->baseattributemodel=$this->load->model('listingBase/baseattributemodel');        
        foreach($data as $d){
            $arr['name']   =addslashes($d[0]);
            $arr['alias']  =addslashes($d[1]);
            $arr['synonym']=addslashes($d[2]);
            $credentialDetails=$this->baseattributemodel->getValueIdByValueName(array(strtolower($d[3])));
            $arr['credential']= $credentialDetails[0]['value_id'];
            $courselevelDetails=$this->baseattributemodel->getValueIdByValueName(array(strtolower($d[4])));
            $arr['courselevel']=$courselevelDetails[0]['value_id'];            
            $arr['is_executive']=$d[5]=='Yes'?1:0;
            $arr['is_popular']=$d[6]=='Yes'?1:0;
            $arr['is_hyperlocal']=$d[7]=='Yes'?1:0; 
            
            $basecourse_id=$this->recatscriptmodel->addBasecourses($arr);
            _p($basecourse_id); 
        }
    } 
    _p("Basecourses added successfully");
}
function addHierarchies($data)
{
    if(!empty($data))
    {  
        foreach($data as $d){
            $primary_stream=$this->recatscriptmodel->getIdbyName($d[0],'stream');
            $primary_substream=$this->recatscriptmodel->getIdbyName($d[1],'substream');
            $primary_specialization=$this->recatscriptmodel->getIdbyName($d[2],'specialization');
       
     $result = $this->hierarchyRepo->insertIntoHierarchyTable(array('streamId'=>$primary_stream,'substreamId'=>$primary_substream,'specializationId'=>$primary_specialization));
     
        }
    }
    _p("Hierarchies added successfully");
}

function addCoursemapping($data){
     $this->model=$this->load->model('listingBase/basecoursemodel');   
    if(!empty($data))
    {  
        foreach($data as $d){
            $temp=array();
            $stream=$this->recatscriptmodel->getIdbyName($d[0],'stream');
            $substream=$this->recatscriptmodel->getIdbyName($d[1],'substream');
            $specialization=$this->recatscriptmodel->getIdbyName($d[2],'specialization');
            $baseCourse=$this->recatscriptmodel->getIdbyName($d[3],'course');
       
            $hierarchy_id=$this->hierarchyRepo->getHierarchyIdByBaseEntities($stream,$substream,$specialization);
            
            if(empty($hierarchyid)){
                $result = $this->hierarchyRepo->insertIntoHierarchyTable(array('streamId'=>$stream,'substreamId'=>$substream,'specializationId'=>$specialization));
                _p($result[data][hierarchy_id][0]);
                $hierarchy_id=$result[data][hierarchy_id][0];
            }
            $temp['hierarchy_id'] = $hierarchy_id;
            $temp['entity_id'] = $baseCourse;
            $temp['entity_type'] ='base_course';
            $temp['stream_id'] = $stream;
            $temp['substream_id'] = empty($substream)?'null':$substream;
            $temp['specialization_id'] = empty($specialization)?'null':$specialization;;
            $insertData[] = $temp;
            _p($temp);
            $this->recatscriptmodel->addEntityHierarchyMapping($temp);
        } 
    }
    _p("Course to Hierarchy added successfully");
}

function addBaseEntityMigration($data)
{
    if(!empty($data))
    {       $this->baseattributemodel=$this->load->model('listingBase/baseattributemodel');
            foreach($data as $d)
            {
            $arr=array();
            $arr['oldcategory_id']=$d[0];
            $oldsubcategory_arr=$this->recatscriptmodel->getOldSubCategoryId($d[2],$d[0]);
            $arr['oldsubcategory_id']=$oldsubcategory_arr[0]['boardId'];
            $oldspecialization_arr=$this->recatscriptmodel->getOldSpecializationId($d[3],$d[4],$d[0]);
            $arr['oldspecialization_id']=$oldspecialization_arr[0]['SpecializationId'];
            $arr['stream']=$this->recatscriptmodel->getIdbyName($d[5],'stream');
            $arr['substream']=$this->recatscriptmodel->getIdbyName($d[6],'substream');
            $arr['specialization']=$this->recatscriptmodel->getIdbyName($d[7],'specialization');
            $arr['baseCourse']=$this->recatscriptmodel->getIdbyName($d[8],'course');
            $deliverymethod=$this->baseattributemodel->getValueIdByValueName(array(strtolower($d[11])));
            $arr['deliverymethod']=$deliverymethod[0]['value_id'];            
            // $placeoflearning=$this->baseattributemodel->getValueIdByValueName(array(strtolower($d[10])));
            // $arr['placeoflearning']=$placeoflearning[0]['value_id'];            
            $educationtypeDetails=$this->baseattributemodel->getValueIdByValueName(array(strtolower($d[9])));
            $arr['educationtype']=$educationtypeDetails[0]['value_id'];            
            $credentialDetails=$this->baseattributemodel->getValueIdByValueName(array(strtolower($d[12])));
            $arr['credential']= $credentialDetails[0]['value_id'];
            $courselevelDetails=$this->baseattributemodel->getValueIdByValueName(array(strtolower($d[13])));
            $arr['courselevel']=$courselevelDetails[0]['value_id'];            
            $insertData[] = $arr;
            $this->recatscriptmodel->addBaseEntityMigration($arr);
        }
    }
    _p("Old entities (category/subcategory) to new entities mappping added successfully");
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
