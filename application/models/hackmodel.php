<?php 

class HackModel extends MY_Model 
{
    function __construct() {
        parent::__construct('Listing');
    }

    private function initiateModel($operation='read'){
        if($operation=='read'){
            $this->dbHandle = $this->getReadHandle();
        }else{
            $this->dbHandle = $this->getWriteHandle();
        }       
    }

	function initDB(){
		$this->initiateModel();
		return  $this->dbHandle;
	}	
	function getCareerPath($careerName){



        $this->initiateModel('read');

        $sql = "Select * from CP_CareerTable where status = 'live'";
        $q = $this->dbHandle->query($sql);
        $result = $q->result_array();
        $c = array();
        foreach($result as $key=>$v){
            $c[strtolower($v['name'])] = $v['careerId'];

	    if(strtolower($v['name']) == strtolower($careerName)){
		$careerDetails = $v;
	    }
        }
        $careerName = strtolower($careerName);
        $careerId = isset($c[$careerName]) ? $c[$careerName] : 0;
	
        
        $res = array();
        $queryCmd = "select cpt.careerId,cpst.id,cpst.stepOrder,cpst.stepTitle,cpst.stepDescription,cpt.pathName,cpt.pathId from  CP_CareerPathTable cpt left join CP_CareerPathStepsTable cpst on (cpt.pathId = cpst.pathId) where cpt.careerId in (?) and cpt.status = 'live' and cpst.status = 'live' order by cpt.pathId,cpst.stepOrder";
        $query = $this->dbHandle->query($queryCmd, array($careerId));
        $numOfRows = $query->num_rows();
        $result = $query->result_array();
        $tmp = array();
        $j=0;
        if($numOfRows!=0){
            foreach($result as $key=>$value){
                if($value['stepTitle'] || $value['stepDescription']){

			                    $res[$value['pathId']]['careerId'] = $value['careerId'];
                    $res[$value['pathId']]['pathId'] = $value['pathId'];
                    $res[$value['pathId']]['pathName'] = $value['pathName'];
                    $res[$value['pathId']]['steps'][$j]['id'] = $value['id'];
                    $res[$value['pathId']]['steps'][$j]['stepOrder'] = $value['stepOrder'];
                    $res[$value['pathId']]['steps'][$j]['stepTitle'] = $value['stepTitle'];
                    $res[$value['pathId']]['steps'][$j]['stepDescription'] = $value['stepDescription'];

                }
                $j++;
            }
        }

	$response['path']  = $res;
	$response['careerid'] = $careerId;
        $response['shortDesc'] = $careerDetails['shortDescription'];
        $response['minSalary'] = $careerDetails['minimumSalaryInLacs'];
        $response['maxSalary'] = $careerDetails['maximumSalaryInLacs'];

	return $response;
    }
/*
     public function getAllStreamsData(){
        $this->initiateModel('read');
        $sql = "select * from streams ";
        $query = $this->dbHandle->query($sql);
        $result = $query->result_array();
        $streams = array();
        foreach ($result as $key => $value) {
            $streams[$value['stream_id']] = array();
            $streams[$value['stream_id']]['id'] = $value['stream_id'];
            $streams[$value['stream_id']]['name'] = $value['name'];
            $streams[$value['stream_id']]['avg_ctc'] = 1;
            $streams[$value['stream_id']]['ctc85'] = 1;
            $streams[$value['stream_id']]['ctc50'] = 1;
            $streams[$value['stream_id']]['ctc25'] = 1;
        }
        return $streams;
    }
*/
    public function getAllStreamsData(){
        $this->initiateModel('read');
        $sql = "select * from streams";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $streams = array();
        $ids = array();

        foreach ($result as $key => $value) {
            $ids [] = $value['id'];
            $streams[$value['stream_id']] = array();
            $streams[$value['stream_id']]['id'] = $value['stream_id'];
            $streams[$value['stream_id']]['name'] = $value['name'];
        }

        $sql = "SELECT AVG(avg_ctc) as avg_ctc, stream_id from test_naukri_functional_salary_data_custom group by stream_id";
        $query = $this->dbHandle->query($sql);
        $r = $query->result_array();
        $re = array();
        foreach ($r as $key => $value) {
            $re[$value['stream_id']] = round($value['avg_ctc'],2);
        }


        foreach ($streams as $key => &$value) {
            if(!empty($re[$key])){
                $value['avg_ctc'] = $re[$key];
            }else{
                unset($streams[$key]);
            }
        }
        
    
        return $streams;
    }
    function getCareerFromHierarchy($stream,  $substreamSpec = null) {
        $this->initiateModel('read');
        $streamId = $this->getEntityIdByName('stream', $stream);
        $substreamId = 0;
        $specializationId = 0;
        if($substreamSpec) {
            $substreamId = $this->getEntityIdByName('substream', $substreamSpec);
            $specializationId = $this->getEntityIdByName('specialization', $substreamSpec);    
        }
	$this->dbHandle->distinct();
        $this->dbHandle->select('careerId,name');
        $this->dbHandle->from('test_career_mapping_table');
        $this->dbHandle->where('stream_id',$streamId);
        if($substreamId) {
            $this->dbHandle->where('substream_id',$substreamId);
        }
        if($specializationId) {
            $this->dbHandle->where('specialization_id',$specializationId);
        }
        $result = $this->dbHandle->get()->result_array();
        $returnArray = array();
        foreach ($result as $key => $value) {
            $returnArray[$value['careerId']] = $value['name'];
        }
        return $returnArray;
    }

    function getCareerHierarchy($careerId) {
        $this->initiateModel('read');
        $this->dbHandle->distinct();
        $this->dbHandle->select('stream_id , substream_id , specialization_id');
        $this->dbHandle->from('test_career_mapping_table');
        $this->dbHandle->where('careerId',$careerId);
        $result = $this->dbHandle->get()->result_array();
        $returnArray = array();
        foreach ($result as $key => $value) {
            $returnArray = $value;
        }
        return $returnArray;
    }

    function getEntityIdByName($entityType, $entityName) {
        $this->initiateModel('read');
        switch ($entityType) {
            case 'stream':
                $tableName = 'streams';
                $columnName = 'stream_id';
                break;

            case 'substream':
                $tableName = 'substreams';
                $columnName = 'substream_id';
                break;

            case 'specialization':
                $tableName = 'specializations';
                $columnName = 'specialization_id';
                break;
            
            default:
                $tableName = 'streams';
                $columnName = 'stream_id';
                break;
        }

        $this->dbHandle->select($columnName);
        $this->dbHandle->from($tableName);
        $this->dbHandle->where('LOWER(name)', strtolower($entityName));
        $result = $this->dbHandle->get()->result_array();
        return $result[0][$columnName];
    }   
 
    }

    

