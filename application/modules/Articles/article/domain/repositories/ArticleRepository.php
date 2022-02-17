<?php
class ArticleRepository extends EntityRepository
{	
	private $articleModel;
	function __construct($dao,$cache,$model)
	{  
		parent::__construct($dao,$cache,$model);
		/*
		 * Load entities required
		 */
		$this->articleModel = $model;
		$this->CI->load->entities(array('Article', 'ArticleDescription', 'ArticleQNA', 'ArticleSlideshow', 'ArticleMapping'),'article');
	}

	function find($articleId, $ampViewFlag = false){
        $articleDataResults = $this->articleModel->getArticleData($articleId);
        switch ($articleDataResults[0]['blogLayout']) {

            case 'qna':
            
                $articleOtherData = $this->articleModel->getBlogQnA($articleId, false, $ampViewFlag);
                // _p($articleOtherData); die('ank');
                break;

            case 'slideshow':
                show_404();
                $articleOtherData = $this->articleModel->getBlogSlideShow($articleId);
                break;

            default:
                $articleOtherData = $this->articleModel->getArticleDescription($articleId, false, $ampViewFlag);
                break;
        }
        
        $articleMappingData = $this->articleModel->getArticleMappingData($articleId);
        $article = $this->_loadOne($articleDataResults, $articleOtherData, $articleMappingData);
        if(!empty($article)){
            return current($article);
        }
        else{
            return $article;
        }
      
    }

    function findMultiple($articleIds, $description = false){
        $articleDataResults   = $this->articleModel->getArticleData($articleIds, true);
	if($description){
	        $articleIdArr         = array();$temp1 = array();$temp2 = array();
        	foreach($articleDataResults as $key=>$value){
                	$articleIdArr[] = $value['blogId'];
	        }
        	$generalData              = $this->articleModel->getArticleDescription($articleIdArr, true);
	        foreach($generalData as $blogId=>$value){
        	        $temp1[] = $blogId;
	        }
        	$remainingArticleIds  = array_diff($articleIdArr, $temp1); 

	        $qnaData              = $this->articleModel->getBlogQnA($remainingArticleIds, true);
        	foreach($qnaData as $blogId=>$value){
                	$temp2[] = $blogId;
	        }
        	$nextremainingArticleIds  = array_diff($remainingArticleIds, $temp2);
	        $slideshowData            = $this->articleModel->getBlogSlideShow($nextremainingArticleIds, true);

        	$articleOtherData         = $generalData + $qnaData + $slideshowData;
	        $article                  = $this->_loadMultiple($articleDataResults, $articleOtherData);
	}else{
		$article = $this->_loadMultiple($articleDataResults);
	}
        return $article;
    }
 
    function findAll(){ 
        $articleIds = $this->model->getAllArticleIds();
        $articleDataResults = $this->model->getMultipleArticleData($articleIds);
        $career = $this->_loadMultiple($articleDataResults);
        return $article;
    }

    private function _loadMultiple($results, $other='')
    {
	if(!empty($other)){
	        foreach ($results as $key => $value) {
        	    $data      = $this->_load(array($value['blogId']=>$value), $other[$value['blogId']]);
	            $articles[$value['blogId']] =  $data[$value['blogId']];
        	}
	}else{
		$articles = $this->_load($results);
	}
        return ($articles);
    }

    private function _loadOne($results, $other, $articleMappingData)
    {
        $articles = $this->_load($results, $other, $articleMappingData);
        return ($articles);
    }

    public function getArticleListBasedOnHierarchy($hierarchyIds,$limit,$type,$articleIds){
        $articleDataResults = $this->model->getArticlesBasedOnHierarchy($hierarchyIds,$limit,$type,$articleIds);
        return $this->_loadMultiple($articleDataResults);
    }

    public function getArticleListBasedOnPopularCourse($courseId,$limit,$type,$articleIds){
        $articleDataResults = $this->model->getArticlesBasedOnCourse($courseId,$limit,$type,$articleIds);
        return $this->_loadMultiple($articleDataResults);
    }

    public function getArticlesBasedOnEntity($entityId,$entityType, $limit,$type,$articleIds){
        $articleDataResults = $this->model->getArticlesBasedOnEntity($entityId,$entityType,$limit,$type,$articleIds);
        return $this->_loadMultiple($articleDataResults);
    }

    public function getAllArticleList($limit,$type){
        $articleDataResults = $this->model->getAllArticles($limit,$type);
        return $this->_loadMultiple($articleDataResults);
    }

    private function _load($results, $other, $articleMappingData)
    {
        if(is_array($results) && count($results)) {
            foreach($results as $articleId => $result){
                $article = $this->_createArticle($result, $other, $articleMappingData);
                $articles[$result['blogId']] = $article;
            }
        }
        return $articles;
    }

    private function _createArticle($result, $description, $articleMappingData)
    {
        $article = new Article;
        $articleData = (array) $result;
        $this->fillObjectWithData($article,$articleData);
        $this->_loadDescription($article, $description);
        if(!empty($articleMappingData)){
            $this->_loadMappingData($article, $articleMappingData);
        }
        return $article;
    }

    private function _loadDescription($article, $description){
        $blogLayout = $article->getBlogLayout();
        foreach ($description as $value) {
            switch($blogLayout){
                case 'qna':
                    $desc = new ArticleQNA;
                    break;
                case 'slideshow':
                    $desc = new ArticleSlideshow;
                    break;
                default:
                    $desc = new ArticleDescription;
            }
            $this->fillObjectWithData($desc, $value);
            $article->setDescription($desc);
        }    
    }

    private function _loadMappingData($article, $articleMappingData){
        foreach ($articleMappingData as $value) {
            $mapping = new ArticleMapping;
            $this->fillObjectWithData($mapping, $value);
            $article->setBlogMapping($mapping);
        }
    }
	
}
