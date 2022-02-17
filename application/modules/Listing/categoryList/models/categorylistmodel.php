<?php
class CategoryListModel extends MY_Model {
    function __construct(){
        parent::__construct('CategoryList');
		$this->db = $this->getReadHandle();
    }

    function get_category_name($category_id){
        $queryCmd = "select name from categoryBoardTable where boardId=?";
        $query = $this->db->query($queryCmd, array($category_id));
        $results = $query->result_array();
        return $results[0]['name'];

    }

    function getCategoryTree($dbHandle){
        $queryCmd = 'select name, boardId , parentId, urlName from categoryBoardTable order by parentId, name';
        log_message('debug', 'getCategoryTree query cmd is ' . $queryCmd);

        $query = $dbHandle->query($queryCmd);
        $categoryTree = $query->result_array();

        $catTree = array();
        for($categoryTreeCount = 0; $categoryTreeCount < count($categoryTree); $categoryTreeCount++) {
            $parentId = $categoryTree[$categoryTreeCount]['parentId'];
            $categoryId = $categoryTree[$categoryTreeCount]['boardId'];
            if($parentId == 0) {continue;}
            if($parentId == 1) { 
                $catTree[$categoryId] = $categoryTree[$categoryTreeCount];
            } else {
                $catTree[$parentId]['subCategories'][$categoryId] = $categoryTree[$categoryTreeCount];
            }
        }
        return $catTree;
    }

    function getCountryList($dbHandle) {
        $query = 'SELECT * FROM countryTable WHERE countryId > 1';
        $resultSet = $dbHandle->query($query);
        $countryList = array();
        foreach($resultSet->result_array()  as $result) {
            $countryList[$result['countryId']] = $result;
        }
        return $countryList;
    }
    
 	function getShikshaCourseCategories()
    {
        $query = $this->db->query("SELECT DISTINCT sc.CategoryId as category_id as id, cbt.name
		    					   FROM tCourseSpecializationMapping sc
		    					   INNER JOIN categoryBoardTable cbt ON cbt.boardId = sc.CategoryId and sc.status = 'live'");
    	$rows = $query->result();			
    	return $rows;	
    }
    
	function getShikshaCourses($category_id)
    {    	    	
        $query = $this->db->query("SELECT SpecializationId AS shiksha_course_id, CONCAT( CourseName,  ' - ', SpecializationName ) AS shiksha_course_title
                                    FROM tCourseSpecializationMapping ".($category_id?"WHERE CategoryId = ?":"")." ORDER BY CourseName, SpecializationName", array($category_id));

    	$rows = $query->result();			
    	return $rows;	
    }
    
	function getShikshaMappedCourses($course_id)
    {
        $sql = "select distinct clm.LDBCourseID as shiksha_course_id, csm.CategoryId as category_id from clientCourseToLDBCourseMapping clm, tCourseSpecializationMapping csm where clm.clientCourseID = ? and clm.LDBCourseID = csm.SpecializationId and clm.status = 'draft'";
        
    	$query = $this->db->query($sql, array($course_id));
		    					
    	$rows = $query->result();		

    	if(count($rows) == 0)
    	{
                $sql = "select distinct clm.LDBCourseID as shiksha_course_id, csm.CategoryId as category_id from clientCourseToLDBCourseMapping clm, tCourseSpecializationMapping csm where clm.clientCourseID = ? and clm.LDBCourseID = csm.SpecializationId and clm.status = 'live'";
                $query = $this->db->query($sql, array($course_id));
		    					
    		$rows = $query->result();
    	}
    	
    	return $rows;	
    }
	
	
	function setCatgoryPageFatFooter($entityType,$entityId,$locationFlag,$fatFooter){
		$this->db = $this->getWriteHandle();
		$sql = "UPDATE fatFooter set status='history' where pageType=? and entityType=? and entityId=? and locationFlag=?";
    	$query = $this->db->query($sql,array('categorypage',$entityType,$entityId,$locationFlag));
		$sql = "INSERT INTO `fatFooter` (
				`pageType` ,
				`entityType` ,
				`entityId` ,
				`status` ,
				`footerContent` ,
				`locationFlag`,
				`modificationDate`
				)
				VALUES (
				'categorypage', ?, ?, 'live', ?, ?, CURRENT_TIMESTAMP
				)";
    	$query = $this->db->query($sql,array($entityType,$entityId,url_base64_decode($fatFooter),$locationFlag));
	}
	
	function getCatgoryPageFatFooter($entityType,$entityId,$locationFlag){
		$sql = "SELECT footerContent from fatFooter where pageType=? and entityType=? and entityId=? and locationFlag=? and status='live' limit 1";
    	$query = $this->db->query($sql,array('categorypage',$entityType,$entityId,$locationFlag));
		$result = $query->result();
		return $result[0]->footerContent?$result[0]->footerContent:'{}';
	}
}
?>
