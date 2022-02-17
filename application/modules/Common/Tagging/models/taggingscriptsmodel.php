<?php
class TaggingScriptsModel extends MY_Model
{ 
	private $dbHandle = '';
	private $actualTagsArray;
	/**
	* Constructor Function 
	*/	
	function __construct(){
		parent::__construct('User');
	}

	
	/**
	* To Initiate the dbHandler instance
	*/
	private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
			$this->dbHandle = $this->getWriteHandle();
		}		
	}

	public function addTag($data = array(),$indexEntry = true){
		if(empty($data) || empty($data['tagName']))
			return 0;
		$this->initiateModel('write');
		$sql = "INSERT INTO tags
				(tags,description,tag_entity,creationTime)
				VALUES
				(?,?,?,'".date("Y-m-d H:i:s")."')";
		
		$query = $this->dbHandle->query($sql, array($data['tagName'],$data['description'],$data['tagEntity']));
		$insert_id = $this->dbHandle->insert_id();

		if($insert_id > 0){
			$sql = "UPDATE tags set main_id = ? where id = ? and status = 'live'";
			$this->dbHandle->query($sql, array($insert_id,$insert_id));
			if($indexEntry)	
			{
				$sql = "INSERT INTO tags_tracking_details(action,subaction,tag_id,data,email) VALUES(?,?,?,?,?)";
				$query = $this->dbHandle->query($sql,array("Add","",$insert_id,"","ankit.bansal1989@gmail.com"));
				$sql = "INSERT INTO indexlog(operation, listing_type, listing_id) VALUES('index','tag',?)";
				$this->dbHandle->query($sql, array($insert_id));	
			}
		}
		return $insert_id;
		
	}

	public function createParentChildMapping($dataToInsert=array()){
		$this->initiateModel("write");
		if(empty($dataToInsert)) return;
		$this->dbHandle->insert_batch("tags_parent",$dataToInsert);
	}


	public function isSynonymsExist($textName = '',$entityName='')
	{
		$this->initiateModel();		
		if(empty($textName) || empty($entityName))
			return;

		$sql = "SELECT count(id) as c FROM tags WHERE tags = ? AND tag_entity = ? AND status = 'live'";

		$rs = $this->dbHandle->query($sql,array($textName,$entityName))->result_array();

		return $rs[0]['c'] >= 1 ? true : false;
	}
	function createSynonymForTags($mainId,$tagName,$tagEntity)
	{
		$this->initiateModel('write');
		if(empty($mainId) || empty($tagName) || empty($tagEntity))
			return;
		error_log('Tagging '.$mainId.'/'.$tagName.'/'.$tagEntity);

		$tableName = 'tags';
		$entityMapping['main_id'] = $mainId;
        $entityMapping['tag_entity'] = $tagEntity;
        $entityMapping['tags'] = $tagName;
        $entityMapping['status'] = 'live';
        $entityMapping['creationTime'] = date("Y-m-d H:i:s");

		$this->dbHandle->insert("tags",$entityMapping);
		return $this->dbHandle->insert_id();
	}
	function isTagMappingWithEntityExist($entityId,$tagId,$entityType,$insert=false)
	{
		if(empty($tagId) || empty($entityId) || empty($entityType))
		{
			return;
		}
		$this->initiateModel();
		$sql = "SELECT count(id) as c FROM tags_entity WHERE tag_id = ? AND entity_id = ? AND entity_type = ? AND status = 'live'";
		$rs = $this->dbHandle->query($sql,array($tagId,$entityId,$entityType))->result_array();
		$bool = ($rs[0]['c'] >= 1 ) ? true : false;
		if(!$insert || $bool)
			return $bool;
		else
		{
			$this->initiateModel('write');
			$tableName = 'tags_entity';
	    	$entityMapping['tag_id'] = $tagId;
	        $entityMapping['entity_id'] = $entityId;
	        $entityMapping['entity_type'] = $entityType;
	        $entityMapping['status'] = 'live';
	        $entityMapping['creationTime'] = date("Y-m-d H:i:s");
	    	$this->dbHandle->insert($tableName,$entityMapping);
		}
	}
	function createTagInShiksha($tagName,$tagEntity)
	{	
		if(empty($tagName) || empty($tagEntity))
			return;
		error_log('Tagging 123/'.$tagName,$tagEntity);
		$this->initiateModel();
		$sql = "SELECT distinct id FROM tags WHERE tags = ? AND tag_entity = ? AND status = 'live'";
		$rs = $this->dbHandle->query($sql,array($tagName,$tagEntity))->result_array();
		if(empty($rs[0]['id']))
		{
			return $this->addTag(array('tagName' => $tagName,'tagEntity' => $tagEntity),false);
		}
		else
			 return $rs[0]['id'];
	}
	function addMappingWithParent($tagId,$parentId)//$dataToInsert=array()
	{
		if(empty($tagId) || empty($parentId))
			return;
		$this->initiateModel('write');
		$sql = "SELECT count(tag_id) as c FROM tags_parent WHERE tag_id = ? AND parent_id = ? AND status = 'live'";
		$rs = $this->dbHandle->query($sql,array($tagId,$parentId))->result_array();
		if(empty($rs[0]['c']))
		{
			$this->initiateModel('write');
			$tableName = 'tags_parent';
	    	$entityMapping['tag_id'] = $tagId;
	        $entityMapping['parent_id'] = $parentId;
	        $entityMapping['creationTime'] = date("Y-m-d H:i:s");
			$this->dbHandle->insert($tableName,$entityMapping);			
		}
		
	}
	function getDataFromBaseAttributeList($type = 'Education Type')
	{
		if(empty($type))
			return;

		$this->initiateModel();
		$sql = "SELECT value_id, value_name FROM base_attribute_list WHERE attribute_name = ? AND status = 'live'";

		$rs = $this->dbHandle->query($sql, array($type))->result_array();
		$result = array();
		foreach ($rs as $key => $value) {
			$result[$value['value_id']] = $value['value_name'];
		}
		return $result;
	}
	function updateShikshaEntitiesForNewMappings($tagName,$tagEntity)
	{
		if(empty($tagName) || empty($tagEntity))
			return;
		$this->initiateModel();
		$sql = "SELECT distinct id FROM tags WHERE tags = ? AND status = 'live'";
		$rs = $this->dbHandle->query($sql,array($tagName))->result_array();
		if(empty($rs[0]['id']))
		{
			return $this->addTag(array('tagName' => $tagName,'tagEntity' => $tagEntity),false);
		}
		else
		{
			$this->initiateModel('write');
			$sql = "UPDATE tags SET tag_entity = ? WHERE tags = ? AND status = 'live' AND id = ?";
			$this->dbHandle->query($sql,array($tagEntity,$tagName,$rs[0]['id']));
			return $rs[0]['id'];
		}
	}

	// will return availbale substreams in shiksha, return array consist of key as substream and value as synonyms of substream
	function getSubstreams()
	{
		$this->initiateModel();
		$sql = "SELECT substream_id, name, primary_stream_id, synonym FROM substreams WHERE status = 'live'";
		$rs = $this->dbHandle->query($sql)->result_array();
		$result = array();

		foreach ($rs as $key => $value) {
			$result[$value['substream_id']] = array('name' => $value['name'],'stream_id' => $value['primary_stream_id'],'synonym' => explode(',', $value['synonym']));
		}
		return $result;
	}
	function updateSynonymsOfTags($mainId,$tagName,$tagEntity)
	{
		if( empty($mainId)|| empty($tagName) || empty($tagEntity))
			return;
		$this->initiateModel();
		$sql = "SELECT distinct id FROM tags WHERE tags = ? AND status = 'live'";
		$rs = $this->dbHandle->query($sql,array($tagName))->result_array();
		if(empty($rs[0]['id']))
		{
			$this->createSynonymForTags($mainId,$tagName,$tagEntity);
		}
		else
		{
			$this->initiateModel('write');
			$sql = "UPDATE tags SET tag_entity = ?, main_id = ? WHERE tags = ? AND status = 'live' AND id = ?";
			$this->dbHandle->query($sql,array($tagEntity,$mainId,$tagName,$rs[0]['id']));
			return $rs[0]['id'];
		}
	}
	function isParentExistAddMapping($tagId,$parentId,$parentEntity)
	{
		if(empty($tagId) || empty($parentId) || empty($parentEntity))
			return;
		$this->initiateModel();

		$sql = "SELECT tag_id FROM tags_entity WHERE entity_id = ? AND entity_type = ? AND status = 'live'";
		$rs = $this->dbHandle->query($sql,array($parentId,$parentEntity))->result_array();

		if(!empty($rs[0]['tag_id']))
		{
			$this->addMappingWithParent($tagId,$rs[0]['tag_id']);
		}
	}
	function getStreams()
	{
		$this->initiateModel();
		$sql = "SELECT stream_id, name, synonym FROM streams WHERE status = 'live'";
		$rs = $this->dbHandle->query($sql)->result_array();
		$result = array();

		foreach ($rs as $key => $value) {
			$result[$value['stream_id']] = array('name' => $value['name'],'synonym' => explode(',', $value['synonym']));
		}
		return $result;		
	}
	function getSpecializations()
	{
		$this->initiateModel();
		$sql = "SELECT specialization_id, name, primary_stream_id, primary_substream_id, synonym WHERE status = 'live' AND type = 'specialization'";

		$rs = $this->dbHandle->query($sql)->$result_array();
		$result = array();

		foreach ($rs as $key => $value) {
			$result[$value['specialization_id']] = array('name' => $value['name'],'synonym' => explode(',', $value['synonym']), 'substream_id' => $value['primary_substream_id'], 'stream_id' => $value['primary_stream_id']);
		}
		return $result;
	}
	function isTagAlreadyExists($tagName)
	{
		if(empty($tagName))
			return;
		$this->initiateModel();
		$sql = "SELECT distinct id FROM tags WHERE tags = ? AND status = 'live'";
		$rs = $this->dbHandle->query($sql,array($tagName))->result_array();
		return empty($rs[0]['id']) ? false : true;
	}
	function getParentTags($tag_id)
	{
		$result = array();
		if(!empty($tag_id))
		{
			$this->initiateModel('write');
			$sql = "SELECT parent_id FROM tags_parent WHERE tag_id =? AND status = 'live'";
			$rs = $this->dbHandle->query($sql,array($tag_id))->result_array();
			foreach ($rs as $key => $value) {
				$result[] = $value['parent_id'];
			}
			$sql = "UPDATE tags_parent SET status= 'deleted' WHERE tag_id = ?";
			$this->dbHandle->query($sql,array($tag_id));
		}
		return $result;
	}
	function getChildTags($tag_id)
	{
		$result = array();
		if(!empty($tag_id))
		{
			$this->initiateModel('write');
			$sql = "SELECT tag_id FROM tags_parent WHERE parent_id =? AND status = 'live'";
			$rs = $this->dbHandle->query($sql,array($tag_id))->result_array();
			foreach ($rs as $key => $value) {
				$result[] = $value['tag_id'];
			}
			$sql = "UPDATE tags_parent SET status= 'deleted' WHERE parent_id = ?";
			$this->dbHandle->query($sql,array($tag_id));
		}
		return $result;
	}
	function updateContentMapping($tag_id,$new_tag_id)
	{
		if(empty($tag_id) || empty($new_tag_id))
		{
			return;
		}
		$this->initiateModel('write');

		$sql = "UPDATE tags_content_mapping set tag_id = ?,modificationTime= ? WHERE tag_id = ? AND status = 'live'";
		$this->dbHandle->query($sql,array($new_tag_id,date("Y-m-d H:i:s"),$tag_id));
	}
	function removeDuplicates($tag_id,$new_tag_id)
	{
		if(empty($tag_id))
			return;
		$this->initiateModel('write');
		$sql = "UPDATE tags SET status= 'deleted' WHERE main_id = ?";
		$this->dbHandle->query($sql,array($tag_id));
		$sql = "UPDATE tags_entity SET status= 'deleted' WHERE tag_id = ? AND status = 'live'";
		$this->dbHandle->query($sql,array($tag_id));
	}
	function removeStreams($tag_id)
	{
		if(empty($tag_id))
			return;
		$this->initiateModel('write');
		$sql = "UPDATE tags set status = 'deleted' WHERE main_id = ?";
		$this->dbHandle->query($sql,array($tag_id));

		$sql = "SELECT tag_id from tags_parent WHERE parent_id = ? AND status = 'live'";
		$rs = $this->dbHandle->query($sql,array($tag_id))->result_array();
		$result = array();
		foreach ($rs as $key => $value) {
			if(!empty($value['tag_id']))
			{
				$result[] = $value['tag_id'];
			}
		}

		if(!empty($result) && count($result) > 0)
		{
			$sql = "UPDATE tags SET status = 'deleted' WHERE tag_entity = 'Stream varients' AND id in (".implode(',', $result).")";	
			$this->dbHandle->query($sql);
		}

		$sql = "UPDATE tags_parent set status = 'deleted' WHERE parent_id = ? or tag_id = ?";
		$this->dbHandle->query($sql,array($tag_id,$tag_id));

		$sql = "UPDATE tags_entity set status = 'deleted' WHERE tag_id = ?";
		$this->dbHandle->query($sql,array($tag_id));
	}
	function getTagId($tags)
	{
		if(empty($tags))
			return;


		$this->initiateModel('write');

		$sql = "SELECT id from tags WHERE tags = ? AND status = 'live' AND tag_entity = 'Stream varients'";

		$rs = $this->dbHandle->query($sql,array($tags))->result_array();

		return $rs[0]['id'];
	}
	function deleteTagsFromSolr()
	{
		$this->initiateModel('read');

		$sql = "SELECT id FROM tags where status = 'deleted'";

		$result = $this->dbHandle->query($sql)->result_array();
		$rs = array();

		foreach ($result as $key => $value) {
			$rs[] = $value['id'];
		}
		$this->insertIntoIndexLog($rs);
	}
	function insertIntoIndexLog($rs)
	{
		if(empty($rs) && count($rs) == 0)
		{
			return;
		}
		$this->initiateModel('write');

		$tableName = 'indexlog';
		foreach ($rs as $key => $value) {
			$entityMapping['operation'] = 'delete';
	        $entityMapping['listing_type'] = 'tag';
	        $entityMapping['listing_id'] = $value;
			$this->dbHandle->insert("indexlog",$entityMapping);	
		}
		
	}



}
