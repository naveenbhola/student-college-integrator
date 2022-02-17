<?php
/**
 * Model class file for User profile builder Info
 */

/**
 * Model class for User profile builder Info
 */
class UserProfileBuilderModel extends MY_Model
{
    /**
     * Database Handler
     *
     * @var object
     */
    private $dbHandle = null;
	
	/**
	 * Constructor Function
	 */
	function __construct()
	{
		parent::__construct('User');
	}
	
	/**
	 * Function to initiate the model in read/write mode
	 *
	 * @param string $mode
	 * @param striing $module
	 */
	private function initiateModel($mode = 'read', $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}
	
	
        /**
	 * Function to fetch user profiletype(consumer/producer)
	 *
	 * @param string $userId
	 */
        function getUserProfileType($userId){
            
            $this->initiateModel('read');
            
            $queryCmd = "select userProfileType from tuser_profileType where userId = ?"; 
            $query = $this->dbHandle->query($queryCmd,array($userId));
            $result = $query->result_array();
            
            return $result[0]['userProfileType'];
            
        }
        
	/**
	 * Function to fetch corresponding tags for a user
	 *
	 * @param string $userId
	 * @param string $entityType
	 */
        
        function getUserTagInfo($userId,$followType=array()){
            
            $this->initiateModel('read');

    	    if(!empty($followType)){        		
                $queryCmd = "select * from userProfileFollowCheck where userId = ? and status='live' and entityType = 'tag' and followType in (?)";
                $query1 = $this->dbHandle->query($queryCmd,array($userId,$followType));
                return $query1->result_array();
    	    }else{
                $queryCmd = "select * from userProfileFollowCheck where userId = ? and status='live' and entityType = 'tag'";
                $query1 = $this->dbHandle->query($queryCmd,array($userId));
                
                return $query1->result_array();
            }
                
        }
	
	/**
	 * Function to fetch all tags for a particular entityType
	 *
	 * @param string $entityType
	 */
        
        function getTagInfoForProfileBuilder($entityType){
            
            $this->initiateModel('read');
            
            $queryCmd = "select id,tags from tags where tag_entity = ?  and status='live' ORDER BY tags"; 
            $query = $this->dbHandle->query($queryCmd,array($entityType));
            
            $results =  $query->result_array();
            
            foreach($results as $result){
                
                 $tagInfoArray[$result['id']] = $result['tags'];
            }
        
            return $tagInfoArray;
            
        }
        
	/**
	 * Function to fetch tag name for a set of tagId
	 *
	 * @param string $entityIds
	 */
        function getTagsNameForProfileBuilder($entityIds){
            //code not in use
            $this->initiateModel('read');
            
            $queryCmd = "select tags from tags where id in($entityIds) and status='live' "; 
            $query = $this->dbHandle->query($queryCmd);
            
            $results =  $query->result_array();
        
            return $results;
            
        }
        
	/**
	 * Function to fetch tagId for a set of tag names
	 *
	 * @param string $tagsString
	 */
        function getTagIdForDiffTags($tagsString,$tagEntity){
            
            $this->initiateModel('read');
            $formattedTags = explode(",", $tagsString);
            $queryCmd = "select id,tags from tags where tags in(?) and tag_entity = ? and status='live' ORDER BY id"; 
            $query = $this->dbHandle->query($queryCmd,array($formattedTags,$tagEntity));
            
            $results =  $query->result_array();
            
            foreach($results as $result){
                
                $tagInfoArray[$result['id']] = $result['tags'];
            
            }
        
            return $tagInfoArray;
            
        }
        
        
	/**
	 * Function to insert user profile info in DB for follow check
	 *
	 * @param string $tagsString
	 */
        function insertUserProfileFollowCheckData($userId,$entityType,$followType,$status){
            
             $this->initiateModel('write');
             
             $queryCmd = "INSERT INTO userProfileFollowCheck (userId,entityType,followType,status,creationTime,modificationTime) VALUES (?,?,?,?,now(),now()) ON DUPLICATE KEY UPDATE status = ?, modificationTime = now()";
             
             $query = $this->dbHandle->query($queryCmd, array($userId,$entityType,$followType,$status,$status));
             
            
        }

        function getTagInfoForSpecialization(){
            $this->initiateModel('read');

            $queryCmd = "select id,tags from tags where tag_entity = 'Specialization' and status='live' ORDER BY tags";
            $query = $this->dbHandle->query($queryCmd,array($entityType));

            $results =  $query->result_array();
            $tagIds = '';
            foreach($results as $result){
                 $tagInfoArray[$result['id']] = $result['tags'];
                 $tagIds .= ($tagIds == '')?$result['id']:','.$result['id'];
            }
            
            //Also, fetch their parents
            $finalArray = array();
            if($tagIds != ''){
                $queryCmd = "select t.id, t.tags, tp.tag_id from tags t, tags_parent tp where tp.tag_id IN ($tagIds) and t.status='live' and tp.status='live' and tp.parent_id = t.id ORDER BY t.tags";
                $query = $this->dbHandle->query($queryCmd);
    
                $results =  $query->result_array();
                foreach($results as $result){
                    $finalArray[$result['id']]['name'] = $result['tags'];
                    $finalArray[$result['id']]['tags'][] = array('id'=>$result['tag_id'], 'label'=>$tagInfoArray[$result['tag_id']]);
                }

                function temp($a, $b){
                        if ($a['label'] == $b['label'])
                                return 0;
                        return ($a['label'] > $b['label']);
                }

                foreach ($finalArray as $sortArray){
                        usort($sortArray['tags'],'temp');
			$returnArray[] = $sortArray;
                }
		$finalArray = $returnArray;
            }

            return $finalArray;
        }	
       
        function getCities(){
            $this->initiateModel('read');

            $queryCmd = "SELECT id FROM tags t, tags_parent tp WHERE t.tag_entity='State' AND tp.parent_id = (SELECT id FROM tags WHERE tags='India' AND status='live') AND tp.tag_id=t.id AND tp.status='live' AND t.status='live'";
            $query = $this->dbHandle->query($queryCmd);

            $results =  $query->result_array();
            $tagIds = '';
            foreach($results as $result){
                 $tagIds .= ($tagIds == '')?$result['id']:','.$result['id'];
            }
            
            //Also, fetch Cities
            $tagInfoArray = array();
            if($tagIds != ''){
                $queryCmd = "SELECT id, tags FROM tags t1, tags_parent tp1 WHERE t1.tag_entity='City' AND tp1.parent_id IN ($tagIds) AND tp1.tag_id=t1.id AND tp1.status='live' AND t1.status='live' ORDER BY t1.tags";
                $query = $this->dbHandle->query($queryCmd);
    
                $results =  $query->result_array();
                foreach($results as $result){
                    $tagInfoArray[$result['id']] = $result['tags'];
                }
            }

            return $tagInfoArray;
        }
	
	
	function getCountriesName($countriesId = array()){
		$this->initiateModel('read');
		
		if(!empty($countriesId)){
			//$countriesIdString = implode("','",$countriesId);
			$queryCmd = "select tags,id from tags where ID IN(?)";
			$query = $this->dbHandle->query($queryCmd,array($countriesId));
			$results =  $query->result_array();
			return $results;
		}
		return array();
		
		
	}
	
        
}
