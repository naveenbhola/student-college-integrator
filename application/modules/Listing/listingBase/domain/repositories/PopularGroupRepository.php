<?php

/**
 * Class PopularGroupRepository Responsible for handling the repository functionality for the core tables belonging to the Shiksha Listings
 *
 * @version ShikshaRecatV1.0
 * @author Shiksha Listings Team
 *
 */
class PopularGroupRepository extends ListingBaseRepository{
	function __construct($cache,$populargroupmodel)
    {        
        parent::__construct($cache,$populargroupmodel);

        $this->populargroupmodel = $populargroupmodel;
        $this->cache = $cache;

        $this->CI->load->entity('PopularGroups','listingBase');
        $this->setEntity(new PopularGroups());
    }

    /**
     * To use this repository, first set hierarchy repo as member variables for dependencies.
     * @param object $hierarchyRepo object of hierarchy repo
     */
    public function _setDependencies($hierarchyRepo){
    	$this->hierarchyRepo = $hierarchyRepo;
    	return $this;
    }

    function find($id) {
        $data=  parent::find($id);
        return $data;
    }

    function findMultiple($ids) {       
        if(is_array($ids)){
            $data =  parent::findMultiple($ids); 
        }
        return $data;
    }

    public function getAllPopularGroups($outputFormat = 'array'){
        $result = $this->populargroupmodel->getAllPopularGroups();
        
        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        
        if($outputFormat == 'object') {
            foreach ($result as $key => $value) {
                $popularGroupIds[] = $value['id'];
            }
            $result = $this->findMultiple($popularGroupIds);
        }

        return $result;
    }

    /**
     * To get Popular group by a particular streamId,substreamId,specializationId where streamId is compulsary.
     * By default only ids of popular groups are returned. If object is passed as return type, then objects are returned.
     * @param  int  $streamId         streamId
     * @param  int $substreamId      substreamId
     * @param  int $specializationId specializationId
     * @param  string  $returnType       objects or simple Ids to be returned
     * @return array[int]|array[objects]                    returns array of int or objects
     */
    public function getPopularGroupsByBaseEntities($streamId, $substreamId, $specializationId, $getIdNames = 0, $outputFormat = 'array'){
        $baseEntityArr[0]['streamId']            = $streamId;
        $baseEntityArr[0]['substreamId']         = $substreamId;
        $baseEntityArr[0]['specializationId']    = $specializationId;
        return $this->getPopularGroupsByMultipleBaseEntities($baseEntityArr,$getIdNames,$outputFormat);
    }

    /**
     * To get popular groups for multiple sets where each set containing keys - streamId , substreamId, specializationId.
     * @param  array $hierarchyArr Array od sets
     * @param  string $returnType   objects or simple Ids to be returned
     * @return array[int]|array[objects]                    returns array of int or objects
     */
    public function getPopularGroupsByMultipleBaseEntities($baseEntityArr, $getIdNames = 0, $outputFormat = 'array') {
        /*
         * Format eg. - 
         * $baseEntityArr[0]['streamId'] = 3;
         * $baseEntityArr[0]['substreamId'] = 'any';
         * $baseEntityArr[0]['specializationId'] = 'none'; // to get base courses mapped to - stream id 3, [any] substream, null specialization
         * -----------------
         * $baseEntityArr[1]['streamId'] = 3;
         * $baseEntityArr[1]['substreamId'] = 'none';
         * $baseEntityArr[1]['specializationId'] = 2; // to get base courses mapped to - 3, null, 2
         */

        if(empty($baseEntityArr)) {
            return;
        }
        $data = $this->populargroupmodel->getPopularGroupsByMultipleBaseEntities($baseEntityArr);

        foreach ($data as $key => $value) {
            
            $substream_id = (!empty($value['substream_id'])) ? $value['substream_id']: 0; 
            $specialization_id = (!empty($value['specialization_id'])) ? $value['specialization_id']: 0; 
            $outPutKey = $value['stream_id']."_".$substream_id."_".$specialization_id;
            if($getIdNames) {
                $Obj[$value['popular_group_id']] = $this->find($value['popular_group_id']);
                $name = $Obj[$value['popular_group_id']]->getName();
                $tempData = array();
                $tempData['popular_group_id'] = $value['popular_group_id'];
                $tempData['popular_group_name'] = $name;
                $result[$outPutKey][] = $tempData; 

            } else {
                $tempData = array();
                $tempData['popular_group_id'] = $value['popular_group_id'];
                //$tempData['popular_group_name'] = $name;
                $result[$outPutKey][] = $tempData; 
            }
        }
        
     /*   if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        
        if($outputFormat == 'object') {
            if($getIdNames) {
                $result = $Obj;
            } else {
                $result = $this->findMultiple($result);
            }
        }*/

        return $result;
    }

    /**
     * Get all popularGroups that are possible for given array of streams, array of substreams, array of specializations.
     * The filters are passed as individual array parameters
     * @param  array  $streamIds         Array of streamIds
     * @param  array  $substreamIds      Array of substreamIds
     * @param  array  $specializationIds Array of specializationIds
     * @param  string $returnType        objects or simple Ids to be returned
     * @return array[int]|array[objects]                    returns array of int or objects
     */
    public function getPopularGroupsForAllCombinations($streamIds, $substreamIds, $specializationIds, $getIdNames = 0, $outputFormat = 'array') {
        if(empty($streamIds)) {
            return;
        }
        $streamIds = !is_array($streamIds) ? array($streamIds) : $streamIds;
        if(!empty($substreamIds)) {
            $substreamIds = !is_array($substreamIds) ? array($substreamIds) : $substreamIds;
        }
        if(!empty($specializationIds)) {
            $specializationIds = !is_array($specializationIds) ? array($specializationIds) : $specializationIds;
        }

        $data = $this->populargroupmodel->getPopularGroupsForAllCombinations($streamIds, $substreamIds, $specializationIds);
        
        foreach ($data as $key => $value) {
            if($getIdNames) {
                $Obj[$value['popular_group_id']] = $this->find($value['popular_group_id']);
                $result[$value['popular_group_id']] = $Obj[$value['popular_group_id']]->getName();
            } else {
                $result[] = $value['popular_group_id'];
            }
        }

        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        if($outputFormat == 'object') {
            if($getIdNames) {
                $result = $Obj;
            } else {
                $result = $this->findMultiple($result);
            }
        }
        return $result;
    }

    /**
     * Get pgids mapped to hierarchy ids
     * @param  array $hierarchyIds array of hierarchyIds
     * @return array            mappng between hierarchyIds and array of pgids
     */
    public function getPopularGroupsByHierarchyIds($hierarchyIds, $getIdNames = 0, $outputFormat = 'array') {
        $hierarchyIds = !is_array($hierarchyIds) ? array($hierarchyIds) : $hierarchyIds;
        
        $data = $this->populargroupmodel->getPopularGroupsByHierarchyIds($hierarchyIds);
        
        foreach ($data as $key => $value) {
            if($getIdNames) {
                $Obj[$value['popular_group_id']] = $this->find($value['popular_group_id']);
                $result[$value['hierarchy_id']][] = array('id'=>$value['popular_group_id'], 'name'=>$Obj[$value['popular_group_id']]->getName());
            } else {
                $result[] = $value['popular_group_id'];
            }
        }
        if(!$getIdNames) {
            $result = array_unique($result);
        }

        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        if($outputFormat == 'object') {
            if($getIdNames) {
                $result = $Obj;
            } else {
                $result = $this->findMultiple($result);
            }
        }
        return $result;
    }

    /**
     * Get hierarchy ids mapped to a set of popular group ids
     * @param  array $pgids popular group ids
     * @return array        returns an associative array of pgids and array of hierarchy ids
     */
    public function getHierarchyIdsByPopularGroups($popularGroupIds, $outputFormat = 'array') {
        if(empty($popularGroupIds)) {
            return false;
        }
        $popularGroupIds = !is_array($popularGroupIds) ? array($popularGroupIds) : $popularGroupIds;
        
        $data = $this->populargroupmodel->getHierarchyIdsByPopularGroups($popularGroupIds);

        foreach ($data as $key => $value) {
            $result[$value['popular_group_id']][] = $value['hierarchy_id'];
        }
        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        return $result;
    }

    public function getBaseEntitiesByPopularGroups($popularGroupIds, $streamIds, $specializationIds, $outputFormat = 'array') {
        if(empty($popularGroupIds)) {
            return false;
        }
        $popularGroupIds = !is_array($popularGroupIds) ? array($popularGroupIds) : $popularGroupIds;
        if(!empty($streamIds)) {
            $streamIds = !is_array($streamIds) ? array($streamIds) : $streamIds;
        }
        if(!empty($specializationIds)) {
            $specializationIds = !is_array($specializationIds) ? array($specializationIds) : $specializationIds;
        }
        $flatData = $this->populargroupmodel->getBaseEntitiesByPopularGroupIds($popularGroupIds, $streamIds, $specializationIds);

        if($outputFormat == 'json') {
            $flatData = json_encode($flatData);
        }
        return $flatData;
    }

    public function save($data,$mode){
        $popularGroupData['table']['name']       = $data['name'];
        $popularGroupData['table']['alias']      = $data['alias'];
        $popularGroupData['table']['synonym']    = $data['synonym'];
        $popularGroupData['table']['status']     = 'live';
        $popularGroupData['userId']              = $data['userId'];

        if($mode == 'edit'){
            $popularGroupData['table']['popular_group_id'] = $data['populargroupId'];
        }

        $returnData = $this->populargroupmodel->save($popularGroupData,$mode);

        if($returnData['data']['status'] == 'success'){
            $popularGroupId = $returnData['data']['popular_group_id'];

            $hierarchyArray = $data['hierarchyArray'];
            $hierarchyData = $this->hierarchyRepo->getHierarchiesByMultipleBaseEntities($hierarchyArray);

            $insertData = array();
            foreach($hierarchyData as $hierarchy){
               // $temp['hierarchy_id'] = $hierarchy['hierarchy_id'];
                $temp['stream_id'] = $hierarchy['stream_id'];
                $temp['substream_id'] = $hierarchy['substream_id'];
                $temp['specialization_id'] = $hierarchy['specialization_id'];
                $temp['entity_id'] = $popularGroupId;
                $temp['entity_type'] = 'popular_group';
                $temp['status'] = 'live';
                $temp['created_on'] = date("Y-m-d H:i:s");
                $temp['created_by'] = $data['userId'];
                $temp['updated_by'] = $data['userId'];
                $insertData[] = $temp;
            }
            $this->populargroupmodel->insertHierarchyMapping($popularGroupId,$insertData,$returnData['data']['dbHandle']);
            unset($returnData['data']['dbHandle']);
        }
        return $returnData;
    }
}