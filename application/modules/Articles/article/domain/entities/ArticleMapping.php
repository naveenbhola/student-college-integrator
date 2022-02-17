<?php 
class ArticleMapping{
	private $id;
	private $articleId;
	private $entityId;
	private $entityType;
	private $status;
	private $creationDate;
	private $modificationDate;

	public function getMappingId(){
		return $this->id;
	}

	public function getArticleId(){
		return $this->articleId;
	}

	public function getEntityId(){
		return $this->entityId;
	}

	public function getEntityType(){
		return $this->entityType;
	}

	public function getStatus(){
		return $this->status;
	}

	public function getCreationDate(){
		return $this->creationDate;
	}

	public function getModificationDate(){
		return $this->modificationDate;
	}

	function __set($property,$value)
	{ 
		$this->$property = $value;
	}
}
?>