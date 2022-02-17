<?php 
class Article{
	private $blogId;
	private $blogTitle;
	private $blogType;
	private $url;
	private $newUrl;
	private $creationDate;
	private $userId;
	private $mailerTitle;
	private $discussionTopic;
	private $countryId;
	private $blogView;
	private $status;
	private $blogImageURL;
	private $blogRelevancy;
	private $lastModifiedDate;
	private $summary;
	private $acronym;
	private $blogTypeValues;
	private $seoTitle;
	private $seoKeywords;
	private $seoDescription;
	private $tags;
	private $blogLayout;
	private $showOnHomePage;
	private $homepageImgURL;
	private $description;
	private $mapping;
	private $chapterNumber;
	private $chapterName;
	private $bookName;
	private $noIndex;

	public function getId(){
		return $this->blogId;
	}

	public function getTitle(){
		return $this->blogTitle;
	}

	public function getType(){
		return $this->blogType;
	}

	public function getUrl(){
		return $this->url;
	}

	public function getAmpUrl(){
		$ampUrl = $this->getUrl();
		return str_replace('/articles/', '/articles/amp/', $ampUrl);
	}

	public function getBlogId(){
		return $this->blogId;
	}

	public function getBlogTitle(){
		return $this->blogTitle;
	}

	public function getBlogType(){
		return $this->blogType;
	}

	public function getBlogUrl(){
		return $this->url;
	}

	public function getUrlNew(){
		return $this->newUrl;
	}

	public function getCreationDate(){
		return $this->creationDate;
	}

	public function getCreatorId(){
		return $this->userId;
	}

	public function getMailerTitle(){
		return $this->mailerTitle;
	}

	public function getDiscussionTopicId(){
		return $this->discussionTopic;
	}

	public function getCountryId(){
		return $this->countryId;
	}

	public function getViewCount(){
		return $this->blogView;
	}

	public function getStatus(){
		return $this->status;
	}

	public function getBlogImageURL(){
		return $this->blogImageURL;
	}

	public function getBlogRelevancy(){
		return $this->blogRelevancy;
	}

	public function getLastModifiedDate(){
		return $this->lastModifiedDate;
	}

	public function getSummary(){
		return $this->summary;
	}

	public function getAcronym(){
		return $this->acronym;
	}

	public function getBlogTypeValues(){
		return $this->blogTypeValues;
	}

	public function getSeoTitle(){
		return $this->seoTitle;
	}

	public function getSeoKeywords(){
		return $this->seoKeywords;
	}

	public function getSeoDescription(){
		return $this->seoDescription;
	}

	public function getTags(){
		return $this->tags;
	}

	public function getBlogLayout(){
		return ($this->blogLayout) ? $this->blogLayout : 'general';
	}

	public function showOnHomePageFlag(){
		return $this->showOnHomePage;
	}

	public function getHomepageImgURL(){
		return $this->homepageImgURL;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($value){
		$this->description[] = $value;
	}

	public function getBlogMapping(){
		return $this->mapping;
	}

	public function setBlogMapping($value){
		$this->mapping[] = $value;
	}

	public function getBookChapterNumber(){
		return $this->chapterNumber;
	}

	public function getBookChapterName(){
		return $this->chapterName;
	}

	public function getBookName(){
		return $this->bookName;
	}

	public function getBlogNoIndex(){
		return $this->noIndex;
	}

	function __set($property,$value)
	{ 
		$this->$property = $value;
	}
}
?>