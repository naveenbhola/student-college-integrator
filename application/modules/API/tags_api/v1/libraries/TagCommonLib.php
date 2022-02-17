<?php
class TagCommonLib {

    private $CI;

    function __construct() {
        $this->CI = &get_instance();
    }

    function getRelatedTags($tagId, $userId){
        $this->CI->load->library('v1/SearchRelatedEntities');
        $relatedEntities = new SearchRelatedEntities();
        $relatedEntities = $relatedEntities->getRelatedEntity($tagId, $type = "tag");
        if(is_array($relatedEntities) && count($relatedEntities)>0){
		//Fetch the Tag Array
		$tagArray = array();
		foreach ($relatedEntities as $tag){
			$tagArray[] = $tag['id'];
		}

	        $this->CI->load->model('TagsModel');
		//Find the number of followers for each Tag
		$followerCountArray = $this->CI->TagsModel->entityFollowerCount($tagArray);
		//Find if the logged-in user is following tag
		if($userId > 0){
			$isUserFollowingArr = $this->CI->TagsModel->isUserFollowingTag($userId, $tagArray);
		}
		//Now, merge evrything
		foreach ($relatedEntities as $relatedTag){
			$tagId = $relatedTag['id'];
			$relatedTag['tagId'] = $relatedTag['id'];
			$relatedTag['tagName'] = $relatedTag['tags'];
			$relatedTag['isUserFollowing'] = isset($isUserFollowingArr[$tagId])?$isUserFollowingArr[$tagId]:'false';
			$relatedTag['tagFollowers'] = $followerCountArray[$tagId];
			unset($relatedTag['id']);
			unset($relatedTag['tags']);
			$finalArray[] = $relatedTag;
		}
                return $finalArray;
        }
	return array();
    }

    function getTopContributorsForTag($tagId, $tagName, $userId){

    	$tagsmodel = $this->CI->load->model("tagsmodel");
    	$this->CI->load->library("common_api/APICommonCacheLib");
    	$apiCommonCacheLib = new APICommonCacheLib();
    	$topContributors = array();
    	$tcName = $tagName;
    	$topContributorsIds = $apiCommonCacheLib->getTopContributorsForTag($tagId);

    	// check for parents
    	$parentIds = array();
    	if(empty($topContributorsIds)){
    		$parentTags = $tagsmodel->getTagsParent(array($tagId));

    		foreach ($parentTags as $value) {
    			$parentIds[] = $value['tag_id'];
				$topContributorsIds = $apiCommonCacheLib->getTopContributorsForTag($value['tag_id']);    			

				if($topContributorsIds){
					$tcName = $value['tags'];
					break;
				}
    		}
    	}

    	// check for grand parents
    	if(empty($topContributorsIds) && $parentIds){
    		$grandParentTags = $tagsmodel->getTagsParent($parentIds);

    		foreach ($grandParentTags as $value) {
				$topContributorsIds = $apiCommonCacheLib->getTopContributorsForTag($value['tag_id']);    			

				if($topContributorsIds){
					$tcName = $value['tags'];
					break;
				}
    		}
    	}

    	if($topContributorsIds)
    		$topContributors = $tagsmodel->topContributorsDetails($topContributorsIds, $userId);

    	$result = array();
    	if($topContributorsIds){
		//In case, Tag name is empty, fetch its name from the DB
		if($tcName == ''){
			$tagDetailsArray = $tagsmodel->getTagsDetailsById(array($tagId));
			$tcName = isset($tagDetailsArray[$tagId]['tags'])?$tagDetailsArray[$tagId]['tags']:'';
		}
    		$result['tcTagName'] = $tcName;
    		$result['topContributors'] = $topContributors;
    	}

    	return $result;
    }

}
?>
