<?php

class Articles_Widgets_Model extends MY_Model
{
    function __construct()
    {
        parent::__construct('Blog');
    }

    function getArticleWidgetsData($dbHandle, $widget_type_value, $categoryID, $subCatgoryID, $countryID, $regionID) {

        $tempRegionID = $regionID;

        if($subCatgoryID == 1) // If it is a Main Category Page..
        $operatedCategoryID = $categoryID;
        else
        $operatedCategoryID = $subCatgoryID;

        // To see if request is from abroad region cat page..
        if($regionID != 0 && $countryID == 1) {
            $countryID = 0;
        }

        // To see if request is from abroad country cat page..
        if($regionID != 0 && $countryID > 2) {
            $regionID = 0;
        }
        
       $resultData = $this->getArtilceIDs($dbHandle, $widget_type_value, $operatedCategoryID, $countryID, $regionID);
       // $articleIDs = $resultData->articleIDs;
       $articleIDs = $resultData[0];
       
       if($articleIDs == "" && ($operatedCategoryID != $categoryID || $tempRegionID > 0))
       {
           // error_log("============================= AMIT 2nd call ============================= ");
           if($tempRegionID > 0) {
               $tempCountryID = 0;
           } else {
               $tempCountryID = $countryID;
           }
           
           // Lets see if the Main Category has articles associated with it..
           $resultData = $this->getArtilceIDs($dbHandle, $widget_type_value, $categoryID, $tempCountryID, $tempRegionID);
           // $articleIDs = $resultData->articleIDs;
           $articleIDs = $resultData[0];
           if( $articleIDs == "")
           {
               return "";
           }
           $operatedCategoryID = $categoryID; // As we are fetching the Main Category Articles.

           $countryID = $tempCountryID;
           $regionID = $tempRegionID;

       }

        if($articleIDs == "") {
            return "";
        }

       // $sql = "SELECT blogId, boardId, blogTitle, url, discussionTopic from blogTable, articles_widgets_data where blogTable.blogId in (".$articleIDs.") AND blogTable.status = 'live' and articles_widgets_data.articleID = blogTable.blogId and articles_widgets_data.status = 'live' AND articles_widgets_data.categoryID = ".$operatedCategoryID." AND articles_widgets_data.widgetType = '".$widget_type_value."' AND articles_widgets_data.`countryID` = '".$countryID."' AND articles_widgets_data.`regionID` = '".$regionID."' GROUP BY blogId ORDER BY articles_widgets_data.displayOrder, articles_widgets_data.id";
	$sql = "SELECT blogId, boardId, blogTitle, url, discussionTopic, summary from blogTable where blogId in (?) AND blogTable.status = 'live' ORDER BY FIELD( blogId, ".$articleIDs.") ";
       //error_log("AMIT ".$sql);
       $articles_info_rs = $dbHandle->query($sql,array($articleIDs));
       // error_log("query is ".print_r($articles_info_rs->num_rows($articles_info_rs),true),3,'/home/infoedge/Desktop/log.txt');
       if($articles_info_rs->num_rows($articles_info_rs) == 0) {
            return "";
       }

       $i = 0;
       $commentThreadID = "";
       foreach ($articles_info_rs->result() as $row)
        {
            $response[$i]['articleID'] = $row->blogId;
            $response[$i]['categoryID'] = $row->boardId;
            // $response[$i]['articleTitle'] = strlen($row->blogTitle) <= 45 ? $row->blogTitle : (substr($row->blogTitle, 0, 45)."...");
            $response[$i]['articleTitle'] = $row->blogTitle;
            $response[$i]['articleURL'] = $row->url;
            $response[$i]['discussionTopic'] = $row->discussionTopic;
			$response[$i]['summary'] = $row->summary;
            $threadIDInfo[$row->discussionTopic]['articleID'] = $row->blogId;
            if($i != 0)
            $commentThreadID .= ", ";

            $commentThreadID .= $row->discussionTopic;
            $i++;
        }
        
        // Getting no. of comments agains the articles now..
        if($widget_type_value == "latest_news") {
           $get_comment_query = "select count(msgId) as totalComments, threadId from messageTable where threadId in (?) AND parentId = threadId AND status in ('live', 'closed') group by threadId";
           $comment_info_rs = $dbHandle->query($get_comment_query,array($commentThreadID));
           foreach ($comment_info_rs->result() as $row) {               
               $threadIDInfo[$row->threadId]['totalComments'] = $row->totalComments;               
           }
           
           $totalRecords = count($response);
           for($i=0; $i < $totalRecords; $i++){
                if($response[$i]['articleID'] == $threadIDInfo[$response[$i]['discussionTopic']]['articleID'])
                    $response[$i]['comments'] = $threadIDInfo[$response[$i]['discussionTopic']]['totalComments'];
           }
       }

        // if($resultData != "" && $resultData->imageName != "")
 	// $response['imageName'] = MEDIAHOSTURL."/mediadata/images/categoryPageWidgetsImages/".$resultData->imageName;

        if(isset($resultData[1]) && $resultData[1] != "" ) {
            $response['imageName'] = $resultData[1];
        }
 
        return $response;        
    }

    function getArtilceIDs($dbHandle, $widget_type_value, $operatedCategoryID, $countryID, $regionID) {
	$this->db = $this->getReadHandle();
	
	$set_articles_query = "SELECT articleID as articleIDs, imageName FROM `articles_widgets_data` WHERE `widgetType` = ?  AND `status` = 'live' ".($operatedCategoryID > 1 ? "AND `categoryID` = '".$operatedCategoryID."'" : "")." AND `countryID` = ? AND `regionID` = ? ORDER BY displayOrder, id";
	
        $ResultRS = $this->db->query($set_articles_query, array($widget_type_value,$countryID,$regionID));
        $result = array();
        $result[0] = "";
        foreach ($ResultRS->result_array() as $row)
        {
            $result[0]  .= ($result[0] == ""? "" : ",").$row['articleIDs'];
            if($row['imageName'] != "") {
                $articleImage = MEDIAHOSTURL."/mediadata/images/categoryPageWidgetsImages/".$row['imageName'];
            }
        }

        if($result[0] != "" && $articleImage != "") {
                $result[1] = $articleImage;
        }

        /*
        // $set_articles_query = "SELECT group_concat(articleID) as articleIDs, imageName FROM `articles_widgets_data` WHERE `categoryID` = '".$operatedCategoryID."' AND `widgetType` = '".$widget_type_value."' AND `status` = 'live' GROUP BY categoryID order by id";
        $set_articles_query = "SELECT group_concat(articleID ORDER BY displayOrder, id) as articleIDs, imageName FROM `articles_widgets_data` WHERE `categoryID` = '".$operatedCategoryID."' AND `widgetType` = '".$widget_type_value."' AND `countryID` = '".$countryID."' AND `regionID` = '".$regionID."' AND `status` = 'live' GROUP BY categoryID";
        // error_log("AMIT ".$set_articles_query);
        $set_articles_rs = $dbHandle->query($set_articles_query);
        $resultData = $set_articles_rs->row();
         * 
         */
        return ($result);
    }

    function getSAWidgetsData($dbHandle, $widget, $categoryId, $location_id,$location_type){
            $queryCmd = 'SELECT sapw.article_id, sapw.image_url,sapw.article_type,bt.blogTitle,bt.url,bt.summary'
                                    .' FROM `studyAbroadPagesWidgets` sapw'
                                    .' JOIN blogTable bt on (bt.blogId = sapw.article_id and bt.status="live")'
                                    .' WHERE sapw.location_id = ?'
                                    .' AND sapw.location_type = ?'
                                    .' AND sapw.widgetType = ?'
                                    .' AND sapw.category_id = ?'
                                    .' AND sapw.status = "live"'
                                    .' ORDER BY article_type , article_position ASC';
            $Result = $dbHandle->query($queryCmd, array($location_id,$location_type,$widget,$categoryId) );
            $result = array();
            foreach ($Result->result_array() as $row)
            {
                    $tmpResult = array();
                    $tmpResult['article_id']  = $row['article_id'];
                    $tmpResult['image_url']  = $row['image_url'];
                    $tmpResult['url']  = $row['url'];
                    $tmpResult['blogTitle']  = $row['blogTitle'];
                    $tmpResult['summary']  = $row['summary'];
                    $result[$row['article_type']][]  = $tmpResult;

            }
            return $result;
    }
    
	
	function getArticlesInWidget($widget)
	{
		$dbHandle = $this->getReadHandle();
		$sql =  "SELECT awd.*,bt.blogId, bt.boardId, bt.blogTitle as articleTitle, bt.url as articleURL, bt.discussionTopic,bt.creationDate  ".
				"FROM articles_widgets_data awd ".
				"INNER JOIN blogTable bt ON bt.blogId = awd.articleID ".
				"WHERE awd.widgetType = ? AND awd.status = 'live' AND bt.status = 'live' ".
				"ORDER BY awd.displayOrder, awd.id";
		$query = $dbHandle->query($sql,array($widget));
		echo $dbHandle->last_query();
		return $query->result_array();
	}
}
