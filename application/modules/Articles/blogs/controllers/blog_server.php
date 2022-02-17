<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit

This class provides the Blog Server Web Services.
The blog_client.php makes call to this server using XML RPC calls.
*/

class Blog_server extends MX_Controller
{

	/*
	*	index function to recieve the incoming request
	*/

	function index(){

		//load XML RPC Libs
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('blogconfig');
		$this->load->helper('url');

        	$this->dbLibObj = DbLibCommon::getInstance('Blog');

		//Define the web services method

		$config['functions']['getBlogCountForBoards'] = array('function' => 'Blog_server.getBlogCountForBoards');

		$config['functions']['getPopularBlogsForCMS'] = array('function' => 'Blog_server.getPopularBlogsForCMS');

		$config['functions']['getRecentPostedBlogs'] = array('function' => 'Blog_server.getRecentPostedBlogs');

		$config['functions']['getMyBlogs'] = array('function' => 'Blog_server.getMyBlogs');

		$config['functions']['getRelatedBlogs'] = array('function' => 'Blog_server.getRelatedBlogs');

		$config['functions']['getBlog'] = array('function' => 'Blog_server.getBlog');

		$config['functions']['createBlog'] = array('function' => 'Blog_server.createBlog');

		$config['functions']['sUpdateBlog'] = array('function' => 'Blog_server.sUpdateBlog');

		$config['functions']['getMostContributingBloggers'] = array('function' => 'Blog_server.getMostContributingBloggers');

		$config['functions']['getUserBlogs'] = array('function' => 'Blog_server.getUserBlogs');

		$config['functions']['getMyBlogCount'] = array('function' => 'Blog_server.getMyBlogCount');

		$config['functions']['updateViewCount'] = array('function' => 'Blog_server.updateViewCount');

		$config['functions']['checkTitle'] = array('function' => 'Blog_server.checkTitle');

		$config['functions']['getBlogsFeeds'] = array('function' => 'Blog_server.getBlogsFeeds');

		$config['functions']['getBlogsForHomePages'] = array('function' => 'Blog_server.getBlogsForHomePages');
		$config['functions']['getBlogsForStudyAbroad'] = array('function' => 'Blog_server.getBlogsForStudyAbroad');

		$config['functions']['getBlogTitleComplete'] = array('function' => 'Blog_server.getBlogTitleComplete');

		$config['functions']['getBlogForIndex'] = array('function' => 'Blog_server.getBlogForIndex');

		$config['functions']['sGetChapterArticles'] = array('function' => 'Blog_server.sGetChapterArticles');

		$config['functions']['getPopularBlogsForHomePage'] = array('function' => 'Blog_server.getPopularBlogsForHomePage');

		$config['functions']['deleteBlog'] = array('function' => 'Blog_server.deleteBlog');

		$config['functions']['getExam'] = array('function' => 'Blog_server.getExam');

		$config['functions']['getExamParents'] = array('function' => 'Blog_server.getExamParents');

		$config['functions']['getExamsForParent'] = array('function' => 'Blog_server.getExamsForParent');

		$config['functions']['getExamParentDetails'] = array('function' => 'Blog_server.getExamParentDetails');

		$config['functions']['updateBlogsForUrls'] = array('function' => 'Blog_server.updateBlogsForUrls');

		$config['functions']['sgetExamsForProducts'] = array('function' => 'Blog_server.sgetExamsForProducts');
		$config['functions']['getTestPrepInfoForGroups'] = array('function' => 'Blog_server.getTestPrepInfoForGroups');

		$config['functions']['sShowArticleList'] = array('function' => 'Blog_server.sShowArticleList');
		$config['functions']['sGetArticlesWithImage'] = array('function' => 'Blog_server.sGetArticlesWithImage');
		$config['functions']['updateImageUrl'] = array('function' => 'Blog_server.updateImageUrl');

		$config['functions']['getListingsExamsCategories'] = array('function' => 'Blog_server.getListingsExamsCategories');
		$config['functions']['getExamsCategoryAll'] = array('function' => 'Blog_server.getExamsCategoryAll');

        $config['functions']['saddImagesToArticle'] = array('function' => 'Blog_server.saddImagesToArticle');

        $config['functions']['sgetBlogImages'] = array('function' => 'Blog_server.sgetBlogImages');

        $config['functions']['sgetFlavorArticles'] = array('function' => 'Blog_server.sgetFlavorArticles');

        $config['functions']['sgetBlogPagesIndex'] = array('function' => 'Blog_server.sgetBlogPagesIndex');
	
		$config['functions']['sgetLatestUpdatesForHomePage'] = array('function' => 'Blog_server.sgetLatestUpdatesForHomePage');

		$config['functions']['sgetAllLatestArticles'] = array('function' => 'Blog_server.sgetAllLatestArticles');        

        $config['functions']['getStudyAbroadStepsWidget'] = array('function' => 'Blog_server.getStudyAbroadStepsWidget');
        $config['functions']['getStudyAbroadSnippetWidget'] = array('function' => 'Blog_server.getStudyAbroadSnippetWidget');
	$config['functions']['sgetArticleWidgetsData'] = array('function' => 'Blog_server.sgetArticleWidgetsData');
	$config['functions']['scheckIfFeatured'] = array('function' => 'Blog_server.scheckIfFeatured');

		//initialize
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}

    function getListingsExamsCategories($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $parentId = $parameters['1'] ;
        $type = $parameters['2'] ;
        $typeId = $parameters['3'] ;

	$dbHandle = $this->_loadDatabaseHandle();
        switch($type){
            case 'course':
                $queryCmd = "SELECT blogId as categoryId , blogTitle as categoryName FROM blogTable WHERE parentId = ? AND blogType = 'exam' AND status='live' and blogId in (select parentId from listingExamMap, blogTable where listingExamMap.type='course' and listingExamMap.typeId = ? and listingExamMap.status='live' and listingExamMap.examId =  blogId and typeOfMap='testprep')";

                break;
            case 'institute':
            default:
                $queryCmd = "SELECT blogId as categoryId , blogTitle as categoryName FROM blogTable WHERE parentId = ? AND blogType = 'exam' AND status='live' and blogId in (select parentId from listingExamMap, blogTable, institute_courses_mapping_table where listingExamMap.type='course' and  listingExamMap.status='live' and listingExamMap.typeId = institute_courses_mapping_table.course_id and listingExamMap.examId =  blogId and typeOfMap='testprep' and institute_courses_mapping_table.institute_id = ? )";
                break;
        }
        $msgArray = array();
        $query = $dbHandle->query($queryCmd, array($parentId,$typeId));
        foreach ($query->result_array() as $row){
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getExamsCategoryAll($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $parentId = $parameters['1'] ;
	$dbHandle = $this->_loadDatabaseHandle();
        $msgArray = array();
        $queryCmd = "SELECT blogId as categoryId , blogTitle as categoryName FROM blogTable WHERE parentId = ? AND blogType = 'exam' AND status='live'";
        $query = $dbHandle->query($queryCmd, array($parentId) );
        foreach ($query->result_array() as $row){
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

	/*
	*	Creates storage tables for a specified application ID
	*/
	function createStorageTables($appID){

		//connect DB
		//XXX to be done later
	}


	/*
	 *       Get the popular blogs for home pages
       	*/
        function getBlogsForHomePages($request){
                $parameters = $request->output_parameters();
                $appID=$parameters['0'];
                $categoryId = $parameters['1'];
                $boardId=$this->getBoardChilds($categoryId);
                $startFrom=$count=$parameters['2'];
                $count=$parameters['3'];
                $countryId=$parameters['4'];
                $key=$parameters['5'];
                $parentId=$parameters['6'];
                $cache=$parameters['7'];
                $blogType=isset($parameters['7'])?$parameters['7']:'blogs';
                if($cache == 0)
                {

                    $orderbyclause = 'order by rand()';
                }
                else
                {
                    $orderbyclause = 'order by popularityView desc';
                }
                if($countryId != 1 && $countryId != '') {
                    $countryClause = ' AND bt.countryId IN ('. $countryId .')';
                }
                //connect DB
		$dbHandle = $this->_loadDatabaseHandle();

              $queryCmd = 'select SQL_CALC_FOUND_ROWS bt.blogId,bt.summary as blogText,bt.blogTitle, bt.url, t.displayname,(t.avtarimageurl) userImage, ' .
				       ' (select level from userPointLevel where minLimit<=upv.userPointValue limit 1) level, '.
                                       '(0.25 * bt.blogView + '.
                                       ' 0.75 * (select count(distinct msgId) count from messageTable where boardId=bt.boardId and threadId=bt.discussionTopic)) popularityView'.
                                       ' from blogTable bt, tuser t, userPointSystem upv where bt.status=\'live\' and  bt.boardId in(?) and bt.userId=t.userid '. $countryClause .
				       ' and bt.userId=upv.userId '.$orderbyclause.' LIMIT '. $startFrom .',' .$count;


               $query = $dbHandle->query($queryCmd,array($boardId));

               $msgArray = array();
               $articleIds = '\'\'';

	       foreach ($query->result_array() as $row){
            $articleIds .= ','. $row['blogId'] ;
			array_push($msgArray,array($row,'struct'));
	       }

           $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
           $query = $dbHandle->query($queryCmd);
           $totalRows = 0;
           foreach ($query->result() as $row) {
               $totalRows = $row->totalRows;
           }
           if($totalRows < $count) {
                if($parentId != $categoryId){
                    $queryCmd = 'SELECT parentId FROM categoryBoardTable WHERE parentId= ?';
                    $query = $dbHandle->query($queryCmd, array($parentId) );
                    $resultSet = array_pop($query->result());
                    $newBoardIds = $this->getBoardChilds($resultSet->parentId);
                }else {
                    $newBoardIds = $boardId;
                }
               $queryCmd = 'select bt.blogId, bt.blogTitle,bt.summary as blogText, bt.url, t.displayname,(t.avtarimageurl) userImage, ' .
                   ' (select level from userPointLevel where minLimit<=upv.userPointValue limit 1) level, '.
                   '(0.25 * bt.blogView + '.
                           ' 0.75 * (select count(distinct msgId) count from messageTable where boardId=bt.boardId and threadId=bt.discussionTopic)) popularityView'.
                   ' from blogTable bt, tuser t, userPointSystem upv where bt.status=\'live\' and  bt.boardId in('.$newBoardIds.') and bt.userId=t.userid and bt.userId=upv.userId and bt.blogId NOT IN (?)'. $orderbyclause.' LIMIT '. $startFrom .',' . ($count - $totalRows);

               $query = $dbHandle->query($queryCmd,array(rtrim($articleIds, ',')));
               foreach ($query->result_array() as $row){
                   array_push($msgArray,array($row,'struct'));
               }
           }
           $mainArr = array();
           array_push($mainArr,array(
                       array(
                           'results'=>array($msgArray,'struct'),
                           'total'=>array($totalRows,'string'),
                           ),'struct')
                   );//close array_push
           $response = array($mainArr,'struct');


           //asda
           if($blogType != 'blogs')
           {
                //get faq and entrance exams as well



           }
           return $this->xmlrpc->send_response($response);
       }

        function getBlogsForStudyAbroad($request)
        {
            $parameters = $request->output_parameters();
            $appID=$parameters['0'];
            $categoryId = $parameters['1'];
            $boardId=$this->getBoardChilds($categoryId);
            $startFrom=$count=$parameters['2'];
            $count=$parameters['3'];
            $countryId=$parameters['4'];
            $key=$parameters['5'];
            $parentId=$parameters['6'];
            $cache=$parameters['7'];
            $blogType=isset($parameters['7'])?$parameters['7']:'blogs';
            if($cache == 0)
            {
                $orderbyclause = 'order by rand()';
            }
            else
            {
                $orderbyclause = 'order by popularityView desc';
            }
            if($countryId != 1 && $countryId != '')
            {
                $countryClause = ' AND bc.countryId IN ('. $countryId .')';
                        }

                        $queryCmd1 = 'select SQL_CALC_FOUND_ROWS bt.blogId,bt.summary as blogText,bt.blogTitle, bt.url, t.displayname,(t.avtarimageurl) userImage, ' .
                        ' (select level from userPointLevel where minLimit<=upv.userPointValue limit 1) level, '.
                        '(0.25 * bt.blogView + '.
                            ' 0.75 * (select count(distinct msgId) count from messageTable where boardId=bt.boardId and threadId=bt.discussionTopic)) popularityView'.
                        ' from blogTable bt,blogCountry bc, tuser t, userPointSystem upv where bt.blogId = bc.blogId and bt.status=\'live\' and  bt.boardId in('.$boardId.') and bt.userId=t.userid '. $countryClause .
                        ' and bt.userId=upv.userId ';

                        //connect DB
                        $dbHandle = $this->_loadDatabaseHandle();

                        //Select faq's from cms

                        $queryCmd = "select b.* from tSetIds a inner join blogTable b on a.itemid = b.blogId where a.itemtype = 'faq' and a.country in ($countryId) and a.status = 'live' and b.status = 'live' and a.pagename = 'country' order by priority asc limit 3";
                        $query = $dbHandle->query($queryCmd);
                        $rowscount = $dbHandle->affected_rows() ;
                        $i = 0;
                        foreach($query->result_array() as $row)
                        {
                            $faqs[$i] = $row;
                            $i++;
                        }

                        if($rowscount < 3)
                        {
                            $queryCmd1 .= " and bt.blogtype = 'faq' ". $orderbyclause.' LIMIT '. $startFrom .',' .$count;
                            $query = $dbHandle->query($queryCmd1);
                            $msgArray = array();
                            $articleIds = '\'\'';

                            foreach ($query->result_array() as $row){
                                $faqs[$i] = $row;
                                $i++;
                            }
                        }
                        return $this->xmlrpc->send_response($response);
        }

	/*
	* Get all blogs for a user
	*/
	function getUserBlogs($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];
		$startFrom=$count=$parameters['2'];
		$count=$parameters['3'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$queryCmd = 'select SQL_CALC_FOUND_ROWS blogTable.* from blogTable where status=\'live\' and userId=? ORDER BY  blogTable.lastModifiedDate Desc LIMIT '. $startFrom .',' .$count;

		log_message('debug', 'getUserBlogs query cmd is ' . $queryCmd);
     
                $resultSet = $dbHandle->query($queryCmd, array($userId));
		foreach($resultSet->result_array() as $result) {
			$response['articles'][] = $result;
		}
                $query = 'SELECT FOUND_ROWS() AS totalArticles';
                $resultSet = $dbHandle->query($query);
                $response = array_merge($response, array_pop($resultSet->result_array()));
                return $this->xmlrpc->send_response(json_encode($response));
	}

	/*
	* Get the number of blogs for a board
	*/
	function getBlogCountForBoards($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$countryId=$parameters['1'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		if($countryId>1){
			$queryCmd = 'select count(*) blogCount,boardId from blogTable where status=\'live\' and countryId=? group by boardId';
			$query = $dbHandle->query($queryCmd, array($countryId));
		}
		else{
			$queryCmd = 'select count(*) blogCount,boardId from blogTable where status=\'live\' group by boardId';
			$query = $dbHandle->query($queryCmd);
		}

		log_message('debug', 'getBlogCountForBoard query cmd is ' . $queryCmd);

		$msgArray = array();
		$count=0;
		foreach ($query->result() as $row){
			$msgArray[$row->boardId]=$row->blogCount;
			$count+=$row->blogCount;
		}
		$msgArray[1]=$count;
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);

	}


	/*
	*	Get the related topics across all board's for a given country
	*/
	function getRelatedBlogs($request){
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle();
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$boardId=$this->getBoardChilds($parameters['1']);
		$startFrom=$count=$parameters['2'];
		$count=$parameters['3'];
		$countryId=$parameters['4'];
		$excludeBlogId=$parameters['5'];

		$excludeQuery = '';
		if($excludeBlogId) { 
			$excludeQuery = 'and bt.blogId != ' . $dbHandle->escape($excludeBlogId);
		}

		if($countryId>1){
			$queryCmd = 'select *
			, (select cbt1.name from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as stream  ,(select cbt1.boardId from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as parentCategoryId , cbt.name  ,
			(select count(*) from messageTable mt where mt.threadId = bt.discussionTopic and mt.parentId <> 0 and mt.mainAnswerId=0) as commentCount from blogTable bt inner join categoryBoardTable as
    		 cbt on cbt.boardId = bt.boardId 
			where bt.status=\'live\' and bt.boardId in(?) and bt.countryId in('.$countryId.') '.$excludeQuery.' order by bt.creationDate desc LIMIT '.$startFrom.','.$count;
		}
		else{
			$queryCmd = 'select *
				, (select cbt1.name from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as stream  ,(select cbt1.boardId from categoryBoardTable as cbt1 where cbt1.boardId = cbt.parentId ) 
    		 as parentCategoryId , cbt.name  ,
			(select count(*) from messageTable mt where mt.threadId = bt.discussionTopic and mt.parentId <> 0 and mt.mainAnswerId=0) as commentCount from blogTable bt inner join categoryBoardTable as
    		 cbt on cbt.boardId = bt.boardId  
			where bt.status=\'live\' and bt.boardId in(?) '.$excludeQuery.' order by bt.creationDate desc LIMIT '.$startFrom.','.$count;
		}

		log_message('debug', 'getRelatedBlogs query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd,array($boardId));

		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	/*
	* common lib method XXX to move to common library
	*/
	function getBoardChilds($boardId){
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		$boardIdArray = array();
		$boardIdString='';
		if($dbHandle == ''){
			log_message('error','getRecentEvent can not create db handle');
		}

			$queryCmd = ' SELECT t1.boardId AS lev1, t2.boardId as lev2, t3.boardId as lev3, t4.boardId as lev4 FROM categoryBoardTable AS t1 '.
				 'LEFT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId '.
				 'LEFT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId '.
				 'LEFT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t1.boardId = ?';

		log_message('debug', 'get board child query is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd, array($boardId));
		foreach ($query->result() as $row){

			if(!array_key_exists($row->lev1,$boardIdArray) && !empty($row->lev1)){
				if(strlen($boardIdString)>0){
					$boardIdString .= ' , ';
				}
				$boardIdArray[$row->lev1]=$row->lev1;
				$boardIdString .= $row->lev1;

			}
			if(!array_key_exists($row->lev2,$boardIdArray) && !empty($row->lev2)){
				if(strlen($boardIdString)>0){
					$boardIdString .= ' , ';
				}
				$boardIdArray[$row->lev2]=$row->lev2;
				$boardIdString .= $row->lev2;

			}
			if(!array_key_exists($row->lev3,$boardIdArray) && !empty($row->lev3)){
				if(strlen($boardIdString)>0){
					$boardIdString .= ' , ';
				}
				$boardIdArray[$row->lev3]=$row->lev3;
				$boardIdString .= $row->lev3;

			}
			if(!array_key_exists($row->lev4,$boardIdArray) && !empty($row->lev4)){
				if(strlen($boardIdString)>0){
					$boardIdString .= ' , ';
				}
				$boardIdArray[$row->lev4]=$row->lev4;
				$boardIdString .= $row->lev4;

			}
		}
		if(strlen($boardIdString)==0){
			$boardIdString .= $boardId;
		}
		return $boardIdString;
	}

	/*
	*	Get the topics posted by me
	*/
	function getMyBlogs($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$boardId=$this->getBoardChilds($parameters['1']);
		$startFrom=$count=$parameters['2'];
		$count=$parameters['3'];
		$countryId=$parameters['4'];
		$userId=$parameters['5'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		if($countryId>1){
			$queryCmd = 'select  bt.*, t.displayname,t.userid userId,t.avtarimageurl userImage,(select level from userPointLevel where minLimit<=upv.userPointValue limit 1) level from blogTable bt, tuser t, userPointSystem upv where bt.status=\'live\' and bt.boardId in (?) and bt.userId=? and upv.userId=bt.userId and bt.countryId='.$countryId.' and bt.userId=t.userid order by creationDate desc  LIMIT '. $startFrom .',' .$count;
		}
		else{
			$queryCmd = 'select  bt.*, t.displayname,t.userid userId,t.avtarimageurl userImage,(select level from userPointLevel where minLimit<=upv.userPointValue limit 1) level from blogTable bt, tuser t, userPointSystem upv where bt.status=\'live\' and bt.boardId in (?) and bt.userId=? and upv.userId=bt.userId and bt.userId=t.userid order by creationDate desc  LIMIT '. $startFrom .',' .$count;
		}


		log_message('debug', 'getMyBlogs query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd, array($boardId,$userId));
		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);

	}

	/*
	*	Get the topics posted by me
	*/
	function getMyBlogCount($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$boardId=$this->getBoardChilds($parameters['1']);
		$countryId=$parameters['2'];
		$userId=$parameters['3'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		if($countryId>1){
			$queryCmd = 'select count(*) count from blogTable where status=\'live\' and boardId in (?) and countryId='.$countryId.' and userId=?';
		}
		else{
			$queryCmd = 'select count(*) count from blogTable where status=\'live\' and boardId in (?) and userId=?';
		}


		log_message('debug', 'getMyBlogCount query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd, array($boardId,$userId));
		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);

	}

	/*
	*	Get the recent topics across board's for a given country
	*/
	function getRecentPostedBlogs($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$boardId=$this->getBoardChilds($parameters['1']);
		$startFrom=$parameters['2'];
		$count=$parameters['3'];
		$countryId=$parameters['4'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		if($countryId>1){
			$queryCmd = 'select bt.*, t.displayname,t.userid userId,t.avtarimageurl userImage,(select level from userPointLevel where minLimit<=upv.userPointValue limit 1) level from blogTable bt, tuser t, userPointSystem upv where bt.status=\'live\' and bt.boardId in (?) and countryId=? and bt.userId=t.userid and bt.userId=upv.userId order by creationDate desc  LIMIT '. $startFrom . ' , '. $count;
			$query = $dbHandle->query($queryCmd, array($boardId,$countryId));
		}
		else{
			$queryCmd = 'select bt.*, t.displayname,t.userid userId,t.avtarimageurl userImage,(select level from userPointLevel where minLimit<=upv.userPointValue limit 1) level from blogTable bt, tuser t, userPointSystem upv where bt.status=\'live\' and bt.boardId in (?) and bt.userId=t.userid and bt.userId=upv.userId order by creationDate desc  LIMIT '. $startFrom . ' , '. $count;
			$query = $dbHandle->query($queryCmd,array($boardId));
		}


		log_message('debug', 'getRecentPostedBlogs query cmd is ' . $queryCmd);

		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	/*
	*	Get the category list for a given parent category
	*/
	function getMostContributingBloggers($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$count=$parameters['1'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		$queryCmd = 'select count(*) count,userId from blogTable where status=\'live\' group by userId order by userId ASC LIMIT 0,'.$count;
		log_message('debug', 'getMostContributingUser query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd);
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
							array(
								'Count'=>array($row->count,'string'),
								'UserId'=>array($row->userId,'string')
							),'struct')
				   );//close array_push

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	/*
	*	get the blog detail
	*/
	function getBlog($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$blogId=$parameters['1'];
		$pageNum =$parameters['2'];
                $status = ($parameters['3'] =='draft' || $parameters['3'] =='live')? $parameters['3']: '';

	        $this->load->model('ArticleModel');
        	$BlogInfo = $this->ArticleModel->getBlogInfo($blogId,'blogs', $pageNum,$status);
	        $BlogInfo = base64_encode(json_encode($BlogInfo));
		return $this->xmlrpc->send_response($BlogInfo);
	}

	/*
	* This function gives feed to alert
	*/
	function getBlogsFeeds($request){
		$parameters = $request->output_parameters();
		$startDate=$parameters['0'];
		$endDate=$parameters['1'];
		$appID=1;
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		$queryCmd = 'select blogId,boardId,blogTitle,userId,substr(summary,1,100)blogText,countryId from blogTable where status=\'live\' and creationDate>=? and creationDate<=?';

		log_message('debug', 'getBlogsFeeds query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd, array($startDate,$endDate) );
		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	/*
	*	Update the blog view count from beacon
	*/
	function updateViewCount($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$blogId=$parameters['1'];
                  $this->load->model('Viewcountmodel');
        $this->Viewcountmodel->updateViewCounts($request,"blogs");
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');

		$queryCmd = 'update blogTable set blogView=blogView + 1 where blogId=?';

		log_message('debug', 'updateViewCount query cmd is ' . $queryCmd);

		if(!$dbHandle->query($queryCmd, array($blogId))){
			$response = array(array('error'=>'UpdateViewCount Query Failed','struct'));
			return $this->xmlrpc->send_response($response);
		}
		$response = array('added','struct');
		return $this->xmlrpc->send_response($response);
	}

	/*
	*	delete the blog
	*/
	function deleteBlog($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$blogId=$parameters['1'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');

		$queryCmd = 'update blogTable set status=\'deleted\' where blogId=?';

		log_message('debug', 'deleteBlog query cmd is ' . $queryCmd);

		if(!$dbHandle->query($queryCmd,  array($blogId))){
			$response = array(array('error'=>'deleteBlog Query Failed','struct'));
			return $this->xmlrpc->send_response($response);
		}

		//After this blog is deleted, we will also delete any mapping of this blog on the Country pagename
		$queryCmd = "update tSetIds set status='deleted' where itemid=? and itemtype NOT IN ('question')";
		$dbHandle->query($queryCmd,  array($blogId));
		//Deletion done from tSetIds table

		/* Deleting the record from the index */
		$this->load->library('listing_client');
		$listingCient = new Listing_client();
		$indexResponse = $listingCient->deleteListing($appID,'blog',$blogId);

		$response = array('updated','struct');
		return $this->xmlrpc->send_response($response);
	}

	/*
	*	add a new topic (which is also a message) in a message table $appID,$board_id,$topic_name
	*/
	function createBlog($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$reqArray=json_decode(base64_decode($parameters['1']), true);

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');

		//get the topic ID
        if($reqArray['blogType'] != 'exam') {
            $reqArray['blogTypeValues'] = null;
        }

		$data =array(
			'boardId'=>$reqArray['board_id'],
			'userId'=>$reqArray['user_id'],
			'blogTitle'=>$reqArray['blogTitle'],
			'mailerTitle'=>$reqArray['mailerTitle'],
			'mailerSnippet'=>$reqArray['mailerSnippet'],
                        //'blogText'=>$reqArray['blogTxt'],
			'chapterNumber'=>$reqArray['chapterNumber'],
			'chapterName'=>$reqArray['chapterName'],
			'countryId'=>$reqArray['country'],
			'bookName'=>$reqArray['bookName'],
			'discussionTopic'=>$reqArray['topicId'],
			'blogType'=>$reqArray['blogType'],
			'parentId'=>$reqArray['parentId'],
			'summary'=>$reqArray['summary'],
			'seoTitle'=>$reqArray['seoTitle'],
			'seoDescription'=>$reqArray['seoDescription'],
			'seoKeywords'=>$reqArray['seoKeywords'],
			'tags'=>$reqArray['tags'],
			'acronym'=>$reqArray['acronym'],
			'lastModifiedDate'=>date('Y-m-d H:i:s'),
			'blogImageURL'=>$reqArray['blogImageURL'],
			'blogTypeValues'=>$reqArray['blogTypeValue'],
      'blogRelevancy'=>$reqArray['blogRelevancy'],
			'noIndex'=>$reqArray['noIndex'],
                        'status'=>$reqArray['status'],
			'blogLayout'=>$reqArray['articleLayout'],
      'homepageImgURL'=>$reqArray['homepageImgURL']

		);
		$queryCmd = $dbHandle->insert_string('blogTable',$data);

		log_message('debug', 'createBlog query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd);
		$blogId=$dbHandle->insert_id();


        $blogDescriptions = $reqArray['blogTxt'];
        $blogDescriptionTags = $reqArray['blogDescriptionTags'];
        $this->addDescriptionsToArticle($dbHandle,$blogDescriptions, $blogDescriptionTags, $blogId) ;
		$this->addQnAToArticle($dbHandle,$reqArray['blogQnADesc'], $reqArray['blogQnADescriptionTags'], $reqArray['blogQnASequenceTags'],$blogId,$reqArray['status']);
		$this->addslideshowToArticle($dbHandle,$reqArray['blogslideshowDesc'], $reqArray['blogslideshowDescTag'],$reqArray['blogslideshowDescTagSub'], $reqArray['blogslideshowSequenceTag'],$reqArray['blogslideshowDescImage'],$blogId,$reqArray['status']);
        $this->addLDBCourses($blogId,$reqArray['ldbCourseList'],$reqArray['status']);
      //  $countries1 = $reqArray['country'] ;

      //  $this->addCountriesToBlog($countries1, $blogId);

		//Added by Ankur for adding URL in blogs
		if(empty($reqArray['seoUrl']))
		{
		    $blogURLField = $reqArray['blogTitle'];
		} else {
		    $blogURLField = $reqArray['seoUrl'];
		}
		$this->updateUrl($blogId, $reqArray['blogType'], $blogURLField,$reqArray['board_id']);
		$this->updateRelatedDate($blogId,$reqArray['relatedDate']);
		//End Code added by Ankur
	      
		$response = array(
					array(
						'blogId'=>array($blogId)),
					'struct');
		return $this->xmlrpc->send_response($response);
	}

    function addDescriptionsToArticle($dbHandle,$blogDescriptions, $blogDescriptionTags, $blogId) {
        if(empty($dbHandle) || empty($blogDescriptions) || empty($blogId)) {
            return false;
        }
        $queryCmd = 'DELETE FROM blogDescriptions WHERE blogId = ?';
        $query = $dbHandle->query($queryCmd, array($blogId));
        $queryCmd = 'INSERT INTO blogDescriptions (blogId, description, descriptionTag) VALUES ';
        $data = '';
        for($blogDescriptionCount = 0; $blogDescriptionCount <count($blogDescriptions); $blogDescriptionCount++ ) {
            if(!isset($blogDescriptions[$blogDescriptionCount])) continue;
            $blogDescription = $blogDescriptions[$blogDescriptionCount];
            if(trim(strip_tags($blogDescription)) != '')
                $data  .= '(' . $dbHandle->escape($blogId) .','. $dbHandle->escape($blogDescription) .','. $dbHandle->escape($blogDescriptionTags[$blogDescriptionCount]) .'),';
        }
        if($data == '') return false;
        $queryCmd .=  trim($data,',');
        $query = $dbHandle->query($queryCmd);
    }
	
	function addQnAToArticle($dbHandle,$blogQnADesc, $blogQnADescriptionTags, $blogQnASequenceTags,$blogId,$status ='live'){
		if(empty($dbHandle) || empty($blogQnADesc) || empty($blogId)) {
            return false;
        }
        $queryCmd = 'DELETE FROM blogQnA WHERE blogId = '. $dbHandle->escape($blogId);
        $query = $dbHandle->query($queryCmd);
		$queryCmd = 'INSERT INTO `blogQnA` (
			`blogId` ,
			`question` ,
			`answer` ,
			`sequence` ,
			`status`
			) VALUES';
        $data = '';
        for($blogDescriptionCount = 0; $blogDescriptionCount <count($blogQnADesc); $blogDescriptionCount++ ) {
            if(!isset($blogQnADesc[$blogDescriptionCount])) continue;
            if(trim(strip_tags($blogQnADesc[$blogDescriptionCount])) != '')
                $data  .= '(' . $dbHandle->escape($blogId) .','. $dbHandle->escape($blogQnADescriptionTags[$blogDescriptionCount]) .','. $dbHandle->escape($blogQnADesc[$blogDescriptionCount]) .','. $dbHandle->escape($blogQnASequenceTags[$blogDescriptionCount]) .','.$dbHandle->escape($status).'),';
        }
        if($data == '') return false;
        $queryCmd .=  trim($data,',');
		error_log($queryCmd);
        $query = $dbHandle->query($queryCmd);
	}
	
	
	function addslideshowToArticle($dbHandle,$blogslideshowDesc, $blogslideshowDescriptionTags,$blogslideshowDescriptionTagsSub, $blogslideshowSequenceTags,$blogslideshowDescImage,$blogId,$status='live'){
		error_log("here");
		if(empty($dbHandle) || empty($blogslideshowDesc) || empty($blogId)) {
            return false;
        }
        
        $queryCmd = 'DELETE FROM blogSlideShow WHERE blogId = ?';
        $query = $dbHandle->query($queryCmd, array($blogId));
		$queryCmd = 'INSERT INTO `blogSlideShow` (
			`blogId` ,
			`title` ,
			`subTitle`,
			`description` ,
			`sequence` ,
			`image`,
			`status`
			) VALUES';
        $data = '';
        for($blogDescriptionCount = 0; $blogDescriptionCount <count($blogslideshowDesc); $blogDescriptionCount++ ) {
            if(!isset($blogslideshowDescImage[$blogDescriptionCount])) continue;
            if(trim(strip_tags($blogslideshowDescImage[$blogDescriptionCount])) != '')
                $data  .= '(' . $dbHandle->escape($blogId) .','. $dbHandle->escape($blogslideshowDescriptionTags[$blogDescriptionCount]) .','. $dbHandle->escape($blogslideshowDescriptionTagsSub[$blogDescriptionCount]).','. $dbHandle->escape($blogslideshowDesc[$blogDescriptionCount]) .','. $dbHandle->escape($blogslideshowSequenceTags[$blogDescriptionCount]) .','. $dbHandle->escape($blogslideshowDescImage[$blogDescriptionCount]) .','.$dbHandle->escape($status).'),';
        }
        if($data == '') return false;
        $queryCmd .=  trim($data,',');
		error_log($queryCmd);
        $query = $dbHandle->query($queryCmd);
	}


    function addCountriesToBlog($countries1, $blogId) {
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = 'DELETE FROM blogCountry WHERE blogId = ?';
        $query = $dbHandle->query($queryCmd, array($blogId));

        for($k = 0;$k<count($countries1); $k++)
        {
            $data =array(
                    'blogId'=>$blogId,
                    'countryId'=>$countries1[$k],
                    );
            $queryCmd = $dbHandle->insert_string('blogCountry',$data);
            $query = $dbHandle->query($queryCmd);
        }
    }

	/*
	Function to update the url field of the blog.
	*/
	private function updateUrl($blogId, $blogType, $blogTitle,$boardId) {
	$dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd ='SELECT t1.blogTitle AS lev1, t2.blogTitle as lev2, t3.blogTitle as lev3 from blogTable AS t1 RIGHT JOIN blogTable AS t2 ON t2.parentId = t1.blogId RIGHT JOIN blogTable AS t3 ON t3.parentId = t2.blogId where t1.status = "live" and (t3.blogId = ? or t2.blogId = ? or t1.blogId=?) and t3.blogId= ? group by lev1, lev2, lev3';


		$response = $dbHandle->query($queryCmd, array($blogId,$blogId,$blogId,$blogId));
		foreach ($response->result_array() as $row){
            $lev1 = $row['lev1'] == '' ? '' : $row['lev1'];
            $lev2 = $row['lev2'] == '' ? '' : $row['lev2'];
            $lev3 = $row['lev3'] == '' ? '' : $row['lev3'];
            $lev2 = $lev1!='' ? '/'. $lev2 : $lev2;
            $lev3 = $lev2!='' ? '/'. $lev3 : $lev3;
			$url = str_replace(' ', '-',$lev1 . $lev2 .$lev3);
			break;
                }
		/*
		# commented URL generation code check for EXAM type
		if($blogType == 'exam') {
			$data['url'] = getSeoUrl($blogId,'exam','') . $url;
		} else {
		*/
		//Modified by Ankur for adding URl in Blogs on 1 March
		//$data['url'] = getSeoUrl($blogId,'blog',$blogTitle) ;
                $arr = array();
                $arr['blogType'] = $blogType;
		$data['url'] = getSeoUrl($blogId,'blog',seo_url($blogTitle,"-",30),$arr) ;

		//If the Sub-Category of the Article belongs to Engineering, replace www with engineering in the URL
		/*if($blogType!='news'){
		$boardArray = array();
		$queryCmd = "SELECT boardId FROM categoryBoardTable WHERE parentId=2 and flag='national'";
		$response = $dbHandle->query($queryCmd);
		foreach ($response->result_array() as $row){
			$boardArray[] = $row['boardId'];
		}
		if(in_array($boardId,$boardArray) || $boardId==48){
			$data['url'] = str_replace("www.","engineering.",$data['url']);
		}
		}*/
		//End changes for Engineering

		/*} */
		$where = 'blogId = "'.$blogId .'"';
		$queryCmd = $dbHandle->update_string('blogTable',$data, $where);
		$response = $dbHandle->query($queryCmd);
	}
	/*
	* check if title already exist and return the boardId and threadId
	*/
    function checkTitle($request)    {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $title=trim($parameters['1']);

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

        $queryCmd = 'select blogId from blogTable where status=\'live\' and blogTitle=?';


        $query = $dbHandle->query($queryCmd, array($title));
        $msgArray = array();
        foreach ($query->result_array() as $row){
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    	//Added by shirish
    	function getBlogTitleComplete($request){
        	$parameters=$request->output_parameters();
        	$appID=$parameters['0'];
        	$partTitle=trim($parameters['1']);

	        //connect DB
		$dbHandle = $this->_loadDatabaseHandle();

        	$queryCmd = 'select blogId,blogTitle from blogTable where status=\'live\' and blogTitle like \''.$partTitle.'%\'';

	        log_message('debug', ' getBlogTitleComplete query cmd is ' . $queryCmd);

	        $query = $dbHandle->query($queryCmd);
        	$msgArray = array();
	        foreach ($query->result_array() as $row)
        	{
	              array_push($msgArray,array($row,'struct'));
        	}
	        $response = array($msgArray,'struct');
    	}


	function getPopularBlogsForCMS($request){
    $dbHandle = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$boardId=$this->getBoardChilds($parameters['1']);
		$startFrom=$count=$parameters['2'];
		$count=$parameters['3'];
		$countryId=$parameters['4'];
		$searchType = $parameters['5'];
		$searchText = $parameters['6'];
		$postedBy = $parameters['7'];
		$orderType = $parameters['8'];
		$userId = $parameters['9'];
		$articleType = $parameters['10'];
    	$articleStatus = $parameters['11'];
		$searchText = escapeMyString($searchText);

		$searchTextQuery = '';
		$authorQuery = '';
		$authorQueryWhere = '';
		$userTypeQuery = '';
		$countryQuery = '';
		
		// creating queries on the basis of search criteria
		if($searchType == 'Tag') {
			$searchTextQuery = " and bt.tags LIKE '%".$searchText."%' ";		
		}else if($searchType == 'Title'){
			$searchTextQuery = " and bt.blogTitle LIKE '%".$searchText."%' ";
		}else if($searchType == 'Author') {
			$authorQuery = " inner join tuser as tu on tu.userid = bt.userId ";
			$authorQueryWhere = " and  (tu.displayName LIKE '%".$searchText."%' OR tu.firstname LIKE '%".$searchText."%' OR tu.lastname LIKE '%".$searchText."%') ";
		}

		if($articleType != '' && $articleType != 'none'){
			$searchTextQuery .= " and bt.blogType = '".$articleType."'";
		}
		
		// setting for articles posted by logged in user
		if($postedBy == 'me') {
			$userTypeQuery = ' and bt.userId = '.$userId;
		}
		
		// setting order clasuse on the basis of front end value
		if($orderType == 'ASC') {
			$orderQuery = " order by bt.creationDate ASC ";
		}else {
			$orderQuery = " order by bt.creationDate DESC ";
		}
		
		if($countryId>1){
			$countryQuery = " and bt.countryId = ".$countryId;
		}
		
    if($articleStatus !='all'){
      $status = "bt.status = '".$articleStatus."'";
      error_log("???".$status);
    }else{
      $status = "bt.status in ('live','draft')";
    }

		$queryCmd = 'select SQL_CALC_FOUND_ROWS bt.blogId, bt.blogTitle, bt.creationDate, bt.userId, bt.blogView, bt.countryId, bt.boardId, bt.blogType, bt.status from blogTable bt '. $authorQuery .' where '.$status.$authorQueryWhere.$searchTextQuery.$userTypeQuery.' and bt.boardId in('.$boardId.') '.$countryQuery.$orderQuery.' LIMIT '. $startFrom .',' .$count;

		log_message('debug', 'getPopularBlogsForCMS query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd);

		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		//$response = array($msgArray,'struct');



		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
		$mainArr = array();
		array_push($mainArr,array(
							array(
								'results'=>array($msgArray,'struct'),
								'total'=>array($totalRows,'string'),
							),'struct')
			);//close array_push
		$response = array($mainArr,'struct');
		return $this->xmlrpc->send_response($response);

	}

    function getBlogForIndex($request)
    {

        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $blogId=$parameters['1'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');

        $queryCmd = "select b1.*, msgCount from blogTable b1 inner join messageTable m1 on b1.discussionTopic = m1.threadId  where b1.status='live' and b1.blogId=? group by b1.blogId";

        log_message('error','getBlogForIndex query is '.$queryCmd);
        $query = $dbHandle->query($queryCmd, array($blogId));
        $msgArray = array();

        foreach ($query->result_array() as $row)
        {
            $this->load->model('ArticleModel');
            $blogDesc= $this->ArticleModel->getArticleDescriptions($dbHandle,$blogId);
            foreach($blogDesc as $blogDescription) {
                $row[0]['blogText'] .= $blogDescription['description'];
            }
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

	function sGetChapterArticles($request){
	//connect DB
    $dbHandle = $this->_loadDatabaseHandle();
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$chapterNumber= $dbHandle->escape($parameters['1']) ;
		$chapterName=$dbHandle->escape($parameters['2']);
		$bookName=$dbHandle->escape($parameters['3']);
		$whereClause = 'bt.status=\'live\' and ';
		$whereClause .= ($chapterNumber != '' ) ? 'bt.chapterNumber = '.$chapterNumber :'';
		$whereClause .= ($whereClause != '' && ($chapterNumber == '' && ($chapterName != '' || $bookName!= ''))) ? ' AND ' : '';
		$whereClause .= ($chapterNumber == '' && $chapterName != '' ) ? 'bt.chapterName = '.$chapterName :'';
		$whereClause .= ($whereClause != '' && $bookName!= '') ? ' AND ' : '';
		$whereClause .= ($bookName != '' ) ? 'bt.bookName = "'.$bookName.'" ' :'';
		$msgArray = array();
		if($whereClause != '') {
			$queryCmd = 'SELECT bt.blogId, bt.blogTitle, bt.url FROM blogTable bt WHERE '. $whereClause 	;//  LIMIT '. $startFrom . ' , '. $count;
			log_message('debug', 'getRecentPostedBlogs query cmd is ' . $queryCmd);
			$query = $dbHandle->query($queryCmd);
			foreach ($query->result_array() as $row){
				array_push($msgArray,array($row,'struct'));
			}
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function getPopularBlogsForHomePage($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$limitCount= $parameters['1'] ;
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$msgArray = array();
		$queryCmd = "select (blogTable.blogView+tSearchSnippetStatTemp.count)/2 as popularityScore, blogTable.blogTitle, blogTable.blogId, blogTable.url, blogTable.summary, blogTable.summary as blogText from tSearchSnippetStatTemp,blogTable where blogTable.status='live' and tSearchSnippetStatTemp.type=\"blog\" and tSearchSnippetStatTemp.listingId=blogTable.blogId and blogTable.blogType = 'kumkum' and blogTable.status = 'live' order by popularityScore limit ".$limitCount;
		log_message('debug', 'getPopularBlogsForHomePage query cmd is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
        }
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	/*
	* Function to get Exam category
	*/
	function getExam($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
    $status = ($parameters['1'] =='draft') ? 'draft' : 'live';
    $fromWhere= $parameters['2'] ;
		//connect DB
    $op = 'read';
    if($fromWhere == 'cms'){
      $op = 'write';
    }
		$dbHandle = $this->_loadDatabaseHandle($op);
		$msgArray = array();
		$queryCmd = "select blogId,blogTitle,acronym,summary, blogImageURL, url from blogTable where parentId=0 and blogType='exam' and status='live' order by blogView desc";
		log_message('debug', 'getExam query cmd is ' . $queryCmd);
error_log_shiksha($queryCmd);
		$query = $dbHandle->query($queryCmd);
		foreach ($query->result_array() as $row){
			$examQueryCmd = "select blogId,blogTitle,acronym,url from blogTable where parentId=? and blogType='exam' and status='live' order by blogView desc";
error_log_shiksha($examQueryCmd);
			$examQuery = $dbHandle->query($examQueryCmd, array($row['blogId']));
			$examArray = array();
			foreach ($examQuery->result_array() as $examRow){
				array_push($examArray,array($examRow,'struct'));
			}
			$row['exam']=array($examArray,'struct');
			array_push($msgArray,array($row,'struct'));
		}

		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function getExamParents($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$categoryId = $parameters['1'] ;
		$dbHandle = $this->_loadDatabaseHandle();
		$msgArray = array();
		$queryCmd = "SELECT blogId, blogTitle, url, blogImageURL FROM blogTable WHERE parentId = '0' AND blogType = 'exam' AND status='live' AND boardId = ?";
		$query = $dbHandle->query($queryCmd, array($categoryId));
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function getExamsForParent($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$parentId = $parameters['1'] ;
		$dbHandle = $this->_loadDatabaseHandle();
		$msgArray = array();
		$queryCmd = "SELECT blogId, blogTitle, url, acronym, summary, summary as blogText FROM blogTable WHERE parentId = ? AND blogType = 'exam' AND status='live'";
		$query = $dbHandle->query($queryCmd, array($parentId));
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function getExamParentDetails($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$examId = $parameters['1'] ;
		$dbHandle = $this->_loadDatabaseHandle();
		$msgArray = array();
		$queryCmd = "SELECT blogId, blogTitle, url FROM blogTable WHERE blogId = (SELECT parentId FROM blogTable WHERE blogId=? and blogType='exam' ) AND blogType = 'exam' AND status='live'";
		$query = $dbHandle->query($queryCmd, array($examId));
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);

	}

	function sUpdateBlog($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$reqArray=json_decode(base64_decode($parameters['1']), true);

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');

		//get the topic ID
        if($reqArray['blogType'] != 'exam') {
            $reqArray['blogTypeValues'] = null;
        }

		$data =array(
			'boardId'=>$reqArray['board_id'],
			//'userId'=>$reqArray['user_id'],
			'blogTitle'=>$reqArray['blogTitle'],
            'mailerTitle'=>$reqArray['mailerTitle'],
            'mailerSnippet'=>$reqArray['mailerSnippet'],
			//'blogText'=>$reqArray['blogTxt'],
			'countryId'=>$reqArray['country'],
			'chapterNumber'=>$reqArray['chapterNumber'],
			'chapterName'=>$reqArray['chapterName'],
			'bookName'=>$reqArray['bookName'],
			'blogType'=>$reqArray['blogType'],
			'parentId'=>$reqArray['parentId'],
			'summary'=>$reqArray['summary'],
			'seoTitle'=>$reqArray['seoTitle'],
			'seoDescription'=>$reqArray['seoDescription'],
			'seoKeywords'=>$reqArray['seoKeywords'],
			'tags'=>$reqArray['tags'],
			'acronym'=>$reqArray['acronym'],
			'blogImageURL'=>$reqArray['blogImageURL'],
			'blogTypeValues'=>$reqArray['blogTypeValue'],
      'blogRelevancy'=>$reqArray['blogRelevancy'],
			'noIndex'=>$reqArray['noIndex'],
                        'status'=>$reqArray['status'],
			'blogLayout'=>$reqArray['articleLayout'],
      'homepageImgURL'=>$reqArray['homepageImgURL']

		);
	        
                $chkStatus = $this->getBlogStatusById($reqArray['blogId']);
                if($chkStatus == 'draft' && $reqArray['status'] == 'live')
                {
                    $data['creationDate'] = date('Y-m-d H:i:s');
                }
                
        	if($reqArray['updateDate']){
			$data['lastModifiedDate'] = date('Y-m-d H:i:s');
		}
		
        if(isset($reqArray['blogImageURL']) && $reqArray['blogImageURL'] != ''){
            $data['blogImageURL'] = $reqArray['blogImageURL'];
        }
		$blogId = $reqArray['blogId'];
		$where = 'blogId = '.$blogId;
		$queryCmd = $dbHandle->update_string('blogTable',$data, $where);
		log_message('debug', 'updateBlog query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd);

        $blogDescriptions = $reqArray['blogTxt'];
        $blogDescriptionTags = $reqArray['blogDescriptionTags'];
        $this->addDescriptionsToArticle($dbHandle,$blogDescriptions, $blogDescriptionTags, $blogId) ;
		$this->addQnAToArticle($dbHandle,$reqArray['blogQnADesc'], $reqArray['blogQnADescriptionTags'], $reqArray['blogQnASequenceTags'],$blogId,$reqArray['status']);
		//error_log(print_r($reqArray,true),3,"/home/amit/Desktop/log.txt");
		$this->addslideshowToArticle($dbHandle,$reqArray['blogslideshowDesc'], $reqArray['blogslideshowDescTag'],$reqArray['blogslideshowDescTagSub'], $reqArray['blogslideshowSequenceTag'],$reqArray['blogslideshowDescImage'],$blogId,$reqArray['status']);
        $this->addLDBCourses($blogId,$reqArray['ldbCourseList'],$reqArray['status']);
	$this->updateRelatedDate($blogId,$reqArray['relatedDate']);
     //   $countries1 = $reqArray['country'] ;
    //    $this->addCountriesToBlog($countries1, $blogId);
		//Commented by Ankur on 1 March. We will not update the URL
		//$this->updateUrl($blogId,$reqArray['blogType'],$reqArray['blogTitle']);
		$response = array(
					array(
						'blogId'=>array($blogId)),
					'struct');
		return $this->xmlrpc->send_response($response);
	}

	function updateBlogsForUrls($request){
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
		$queryCmd = 'select blogId, blogTitle, blogType from blogTable where status="live"' ;
		$query = $dbHandle->query($queryCmd);
		foreach ($query->result_array() as $row){
			$blogId = $row['blogId'];
			$blogTitle = $row['blogTitle'];
			$blogType = $row['blogType'];
			$blogType = $blogType == '' ? 'kumkum' : $blogType;
			//Commented by Ankur on 1 March. We will not update the URL of the Blogs
			//$this->updateUrl($blogId, $blogType,$blogTitle);
			$data = array('blogType'=>$blogType);
			$where = 'blogId = "'.$blogId .'"';
			$queryCmd = $dbHandle->update_string('blogTable',$data, $where);
			$response = $dbHandle->query($queryCmd);
		}
		return $this->xmlrpc->send_response($response);

	}

    function sgetExamsForProducts($request){
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
        $this->load->model('ArticleModel');
        $results = $this->ArticleModel->getExamsForProducts($dbHandle);
        $msgArray = array();
		foreach ($results as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
    }

    function getTestPrepInfoForGroups($request){
	$parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $blogId=$parameters['1'];
        $start=$parameters['2'];
        $count=$parameters['3'];
        $this->load->model('EventModel');
        $this->load->model('ArticleModel');
        $this->load->model('ListingModel');
        $msgArray = array();
        $examsSelected = $this->EventModel->getEventsForExams($blogId,$start,$count, -1);
        $BlogInfo = $this->ArticleModel->getBlogInfo($blogId,'blogs');
	$Listings = $this->ListingModel->getListingsForExams($blogId,'',$start,$count);
	error_log($Listings);
	$BlogInfo = json_encode($BlogInfo);
	$Listings = json_encode($Listings);
	$examsSelected = json_encode($examsSelected);
        array_push($msgArray, array($BlogInfo, 'string'));
        array_push($msgArray, array($examsSelected, 'string'));
        array_push($msgArray, array($Listings, 'string'));
	$response = array($msgArray,'struct');
	error_log(print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }

    function sShowArticleList($request){
        $dbHandle = $this->_loadDatabaseHandle();
		$this->load->model('CategoryListModel');
        $this->load->model('ArticleModel');
        $parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$criteria = json_decode($parameters['1'], true);
		$orderBy = $parameters['2'];
		$startOffset = $parameters['3'];
		$countOffset = $parameters['4'];
        if(isset($criteria['boardId']) && !empty($criteria['boardId'])) {
                $criteria['boardId']=$this->getBoardChilds($criteria['boardId']);
        }
        if(isset($criteria['subcat']) && !empty($criteria['subcat'])) {
                $criteria['boardId']= $criteria['subcat'];
                unset($criteria['subcat']);
        }

        $data = $this->ArticleModel->getArticlesForCriteria($dbHandle, $criteria, $orderBy, $startOffset, $countOffset);
	//$categoryTree = $this->CategoryModel->getCategoryTree($dbHandle);
        //$data['categoryTree'] = $categoryTree;
        //$response = json_encode($data);
        $mainArr = array();
        $mainArr[0]['results'] = $data;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
	return $this->xmlrpc->send_response($response);
    }

    function sGetArticlesWithImage($request) {
	    $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $criteria=$parameters['1'];
        $groupBy=$parameters['2'];
        //$this->load->database();
        $dbHandle = $this->_loadDatabaseHandle();
		$this->load->model('ArticleModel');
		$snippet = $this->ArticleModel->getArticlesWithImageForCriteria($dbHandle, $criteria, $groupBy);
        $response = json_encode($snippet);
		return $this->xmlrpc->send_response($response);
    }

    /* This method will be used to update the thumbnail urls of the blogs */
    function updateImageUrl($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $blogId = $parameters['1'];
        $url = $parameters['2'];

        $dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = "UPDATE blogTable SET blogImageUrl = ? WHERE blogId = ?";
        error_log($queryCmd, array($url,$blogId));
        $response = $dbHandle->query($queryCmd, array($url,$blogId));
        return $this->xmlrpc->send_response($response);
    }

    function saddImagesToArticle($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $blogId = $parameters['1'];
        $imageDetails = json_decode($parameters['2'], true);
        $status = $parameters['3'];
	$dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = 'DELETE FROM blogImages WHERE blogId = ?';
        $response = $dbHandle->query($queryCmd, array($blogId));
        $blogImageInsertValues = '';
        foreach($imageDetails as $imageDetail) {
            $blogImageInsertValues .=  $blogImageInsertValues != '' ? ',' : '';
            $imageDetail = explode('#', $imageDetail);
            $mediaId = $imageDetail[0];
            $mediaUrl = $imageDetail[1];
            $blogImageInsertValues .= '("'. $blogId .'", "'. $mediaId .'", "'. $mediaUrl .'", "'. $status .'", "'. date('Y-m-d h:i:s') .'")';
        }
        if($blogImageInsertValues != '') {
            $queryCmd = 'INSERT INTO blogImages (blogId, mediaId, imageUrl, status, timeUpdated) VALUES '. $blogImageInsertValues;
            $response = $dbHandle->query($queryCmd);
        }
        return $this->xmlrpc->send_response($response);
    }

    function sgetBlogImages($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $blogId = $parameters['1'];
        $status = ($parameters['3'] !='') ? $parameters['3'] : 'live';
        $fromWhere = $parameters['4'];
        $op = 'read';
        if($fromWhere == 'cms'){
          $op = 'write';
        }
        
	      $dbHandle = $this->_loadDatabaseHandle($op);
        $msgArray = array();
        $queryCmd = 'SELECT * FROM blogImages WHERE blogId = ? AND status = ?';

        $query = $dbHandle->query($queryCmd, array($blogId,$status));
        foreach ($query->result_array() as $row){
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function sgetFlavorArticles($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $criteria = $parameters['1'];
        $orderBy = $parameters['2'];
        $startOffset = $parameters['3'];
        $countOffset = $parameters['4'];

		$dbHandle = $this->_loadDatabaseHandle();
        $criteriaText = '';
        foreach($criteria as $criteriaKey => $criteriaValue) {
            switch($criteriaKey) {
                case 'startDate': $criteriaText .= ' AND pbd.StartDate <= '. $dbHandle->escape($criteriaValue);
                                    break; // The Condition added like this as right now this is the only condition required. If required to make it more generalize make the $criteriaValue as an array with operator and value.
                                    default: $criteriaText .= ' AND '. $key .' = '. $dbHandle->escape($criteriaValue) ;
            }
        }
        $msgArray = array();
        $query = 'SELECT SQL_CALC_FOUND_ROWS bt.*,pbd.startDate, pbd.endDate FROM blogTable bt INNER JOIN PageBlogDb pbd ON pbd.BlogId = bt.blogId WHERE bt.status="live" and bt.blogType!= "news" AND pbd.KeyId = 51 '. $criteriaText .' ORDER BY '. $orderBy .' LIMIT '. $startOffset .','. $countOffset ;

        $resultSet = $dbHandle->query($query);
		$response = array();
		foreach($resultSet->result_array() as $result) {
			$response['articles'][] = $result;
		}
        $query = 'SELECT FOUND_ROWS() AS totalArticles';
        $resultSet = $dbHandle->query($query);
        $response = array_merge($response, array_pop($resultSet->result_array()));
        return $this->xmlrpc->send_response(json_encode($response));
    }
    
    
    function relatedArticlesWidget($examName){
        $appId = 1;
        $orderBy = ' lastModifiedDate desc ';
        $startOffset = 0;
        $countOffset = 6;
        $criteria['blogType'] = 'popular';
        $displayData['examName'] = $examName;
        $examName = preg_replace('/\s+/', '', $examName);
        $examName = strtolower($examName);
        if($examName == 'jeemains')
        {
            $examName = 'jeemain';
        }
        $criteria['tag'] = $examName;
        $this->load->library('blog_client');
        $blog_client = new Blog_client();
        $articlesList = $blog_client->getArticlesForCriteria($appId, $criteria, $orderBy, $startOffset, $countOffset);
        if(is_array($articlesList)){
            $displayData = $articlesList[0]['results'];
        }
        $displayData['articleURL'] = SHIKSHA_HOME."/blogs/shikshaBlog/showArticlesList";
        $this->load->view('relatedArticlesWidget',$displayData);
    }

    function sgetBlogPagesIndex($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $blogId = $parameters['1'];

	$dbHandle = $this->_loadDatabaseHandle();
        $query = 'SELECT descriptionId, descriptionTag FROM blogDescriptions WHERE blogId = ? ORDER BY blogId, descriptionId';
        $result = $dbHandle->query($query, array($blogId));
        $response = array();
        foreach($result->result_array() as $record) {
            $response[$record['descriptionId']] = $record['descriptionTag'];
        }
        return $this->xmlrpc->send_response(json_encode($response));
    }
  
        function sgetLatestUpdatesForHomePage($request){
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $startOffSet = $parameters['1'];
	$dbHandle = $this->_loadDatabaseHandle();
        $query = 'SELECT SQL_CALC_FOUND_ROWS bt.*,pbd.startDate, pbd.endDate FROM blogTable bt INNER JOIN PageBlogDb pbd ON pbd.BlogId = bt.blogId WHERE bt.status="live" AND pbd.KeyId = 52 ORDER BY pbd.startDate Desc LIMIT '. $startOffSet ;
        $resultSet = $dbHandle->query($query);
                $response = array();
                foreach($resultSet->result_array() as $result) {
                        $response['articles'][] = $result;
                }
        $query = 'SELECT FOUND_ROWS() AS totalArticles';
        $resultSet = $dbHandle->query($query);
        $response = array_merge($response, array_pop($resultSet->result_array()));
        return $this->xmlrpc->send_response(json_encode($response));
    }

    function sgetAllLatestArticles($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $startOffset = $parameters['1'];
        $countOffset = $parameters['2'];

		$dbHandle = $this->_loadDatabaseHandle();

        $msgArray = array();
		$query = 'SELECT SQL_CALC_FOUND_ROWS bt.*,pbd.startDate, pbd.endDate FROM blogTable bt INNER JOIN PageBlogDb pbd ON pbd.BlogId = bt.blogId WHERE bt.status="live" and bt.blogType!= "news" AND pbd.KeyId = 52 ORDER BY pbd.startDate Desc LIMIT ' . $startOffset .','. $countOffset;

        $resultSet = $dbHandle->query($query);
		$response = array();
		foreach($resultSet->result_array() as $result) {
			$response['articles'][] = $result;
		}
        $query = 'SELECT FOUND_ROWS() AS totalArticles';
        $resultSet = $dbHandle->query($query);
        $response = array_merge($response, array_pop($resultSet->result_array()));
        return $this->xmlrpc->send_response(json_encode($response));
    }

    function sgetArticleWidgetsData($request) {
        $parameters = $request->output_parameters();
        $widget_type_value = $parameters['0'];
        $categoryID = $parameters['1'];
        $subCatgoryID = $parameters['2'];
        $countryID = $parameters['3'];
        $regionID = $parameters['4'];
        //$this->load->database();
	$dbHandle = $this->_loadDatabaseHandle();
        $artilces_Widgets_Model = $this->load->model('articleWidgets/Articles_Widgets_Model');       
        $articleWidgetsData = $artilces_Widgets_Model->getArticleWidgetsData($dbHandle, $widget_type_value, $categoryID, $subCatgoryID, $countryID, $regionID);

        $articleWidgetsData = base64_encode(gzcompress(json_encode($articleWidgetsData)));
	return $this->xmlrpc->send_response($articleWidgetsData);
    }
	
	function getStudyAbroadStepsWidget($request){
		$parameters = $request->output_parameters();
        $categoryId = $parameters['0'];
        $location_id = $parameters['1'];
        $location_type = $parameters['2'];
		//$this->load->database();
		$dbHandle = $this->_loadDatabaseHandle();
		$artilces_Widgets_Model = $this->load->model('articleWidgets/Articles_Widgets_Model');
		$returnData = $artilces_Widgets_Model->getSAWidgetsData($dbHandle, 'steps', $categoryId, $location_id,$location_type);
		return $this->xmlrpc->send_response(base64_encode(gzcompress(json_encode($returnData))));
	}
	function getStudyAbroadSnippetWidget($request){
		$parameters = $request->output_parameters();
        $categoryId = $parameters['0'];
        $location_id = $parameters['1'];
        $location_type = $parameters['2'];
		//$this->load->database();
		$dbHandle = $this->_loadDatabaseHandle();
		$artilces_Widgets_Model = $this->load->model('articleWidgets/Articles_Widgets_Model');
		$returnData = $artilces_Widgets_Model->getSAWidgetsData($dbHandle, 'top', $categoryId, $location_id,$location_type);
		return $this->xmlrpc->send_response(base64_encode(gzcompress(json_encode($returnData))));
	}

	function scheckIfFeatured($request){
        	$parameters = $request->output_parameters();
	        $appId = $parameters['0'];
	        $blogId = $parameters['1'];

		$dbHandle = $this->_loadDatabaseHandle();
        	$query = 'SELECT IF(blogType="news",1,0) newsCheck  FROM blogTable bt where status = "live" and blogId = ? ';
	        $resultSet = $dbHandle->query($query, array($blogId));
                $response = array();
                foreach($resultSet->result_array() as $result) {
                        $response['articles'][] = $result;
                }
        	return $this->xmlrpc->send_response(json_encode($response));
	}

    function addLDBCourses($blogId,$ldbCourseList,$status='live'){
        $dbHandle = $this->_loadDatabaseHandle('write');
        //Remove all the Course mapping which are already present
        $query = "UPDATE blogLDBCourseMapping SET status = 'deleted' WHERE blogId = ? ";         
        $resultSet = $dbHandle->query($query,array($blogId));

        $ldbCourses = explode(',',$ldbCourseList);
        if(count($ldbCourses) > 0){
            foreach ($ldbCourses as $ldbCourse){
                if($ldbCourse>0){
                    $queryCmd = "INSERT INTO blogLDBCourseMapping (blogId,ldbCourseId,status) VALUES (?,?,?)";
                    $resultSet = $dbHandle->query($queryCmd,array($blogId,$ldbCourse,$status));
                }
            }
        }
        return 1;
    }

	function updateRelatedDate($blogId,$relatedDate){
		$dbHandle = $this->_loadDatabaseHandle('write');
		if($relatedDate!='YYYY-MM-DD' && $relatedDate!='0000-00-00'){
			$query = "UPDATE blogTable SET relatedDate = ? WHERE blogId = ? ";
			$resultSet = $dbHandle->query($query,array($relatedDate,$blogId));
		}
	}
    function getBlogStatusById($blogId){
        $dbHandle = $this->_loadDatabaseHandle('read');
        $query  = 'SELECT status from blogTable where blogId = ? and status != "deleted"';
        $result = $dbHandle->query($query,array($blogId));
        $result = $result->result_array();
        return $result[0]['status'];
    }
}
?>
