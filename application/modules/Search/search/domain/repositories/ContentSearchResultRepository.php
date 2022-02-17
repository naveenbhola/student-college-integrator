<?php

class ContentSearchResultRepository extends EntityRepository {
	
	private $searchWrapper;
	public function __construct() {
		parent::__construct();
		$this->CI->load->entities(array('QuestionDocument', 'ArticleDocument', 'DiscussionDocument', 'TagDocument'),'search');
		$this->CI->load->builder('SearchBuilder');
		SearchBuilder::loadSolrDataProcessor();
	}
	
	public function getContent($contentData = array()) {
		if(!is_array($contentData) || empty($contentData)){
			return array();	
		}
		$contentSearchResultEntities = $this->getContentResultEntitiesFromSolrDocuments($contentData['data']);
		return $contentSearchResultEntities;
	}
	
	private function getContentResultEntitiesFromSolrDocuments($solrContentDocumentList = array()){
		$contentDocumentEntities = array();
		if(is_array($solrContentDocumentList) && !empty($solrContentDocumentList)){
			foreach($solrContentDocumentList as $solrContentDocument){
				switch($solrContentDocument['facetype']){
					case 'question':
						$contentDocumentEntities[] = $this->getQuestionDocumentEntity($solrContentDocument);
						break;
						
					case 'article':
						$contentDocumentEntities[] = $this->getArticleDocumentEntity($solrContentDocument);
						break;
					
					case 'discussion':
						$contentDocumentEntities[] = $this->getDiscussionDocumentEntity($solrContentDocument);
						break;

					case 'tag':
						$contentDocumentEntities[] = $this->getTagDocumentEntity($solrContentDocument);
						break;
				}
			}
		}
		return $contentDocumentEntities;
	}
	
	private function getQuestionDocumentEntity($solrContentDocument = array()){
		$questionDocumentEntity = false;
		if(is_array($solrContentDocument) && !empty($solrContentDocument)){
			if($solrContentDocument['facetype'] != "question"){
				return false; 
			}
			$questionDocumentData = SolrDataProcessor::getQuestionDocumentData($solrContentDocument);
			$questionDocumentEntity = new QuestionDocument();
			$this->fillObjectWithData($questionDocumentEntity, $questionDocumentData);
			
			$questionCategoryData = SolrDataProcessor::getQuestionCategory($solrContentDocument);
			$categoryEntities = array();
			foreach($questionCategoryData as $category){
				$categoryEntity = new Category();
				$this->fillObjectWithData($categoryEntity, $category);
				$questionDocumentEntity->setQuestionCategory($category['boardId'], $categoryEntity);
			}
		}
		return $questionDocumentEntity;
	}
	
	private function getArticleDocumentEntity($solrContentDocument = array()){
		$articleDocumentEntity = false;
		if(is_array($solrContentDocument) && !empty($solrContentDocument)){
			if($solrContentDocument['facetype'] != "article"){
				return false; 
			}
			$articleDocumentData = SolrDataProcessor::getArticleDocumentData($solrContentDocument);
			$articleDocumentEntity = new ArticleDocument();
			$this->fillObjectWithData($articleDocumentEntity, $articleDocumentData);
			
			$articleCategoryData = SolrDataProcessor::getArticleCategory($solrContentDocument);
			$categoryEntities = array();
			foreach($articleCategoryData as $category){
				$categoryEntity = new Category();
				$this->fillObjectWithData($categoryEntity, $category);
				$articleDocumentEntity->setCategoryEntity($category['boardId'], $categoryEntity);
			}
		}
		return $articleDocumentEntity;
	}
	
	private function getDiscussionDocumentEntity($solrContentDocument = array()){
		$discussionDocumentEntity = false;
		if(is_array($solrContentDocument) && !empty($solrContentDocument)){
			if($solrContentDocument['facetype'] != "discussion"){
				return false; 
			}
			$discussionDocumentData = SolrDataProcessor::getDiscussionDocumentData($solrContentDocument);
			$discussionDocumentEntity = new DiscussionDocument();
			$this->fillObjectWithData($discussionDocumentEntity, $discussionDocumentData);
		}
		return $discussionDocumentEntity;
	}

	private function getTagDocumentEntity($solrContentDocument = array()){
		$tagDocumentEntity = false;
		if(is_array($solrContentDocument) && !empty($solrContentDocument)){
			if($solrContentDocument['facetype'] != "tag"){
				return false; 
			}
			$tagDocumentData = SolrDataProcessor::getTagDocumentData($solrContentDocument);
			$tagDocumentEntity = new TagDocument();
			$this->fillObjectWithData($tagDocumentEntity, $tagDocumentData);
		}
		return $tagDocumentEntity;
	}
}