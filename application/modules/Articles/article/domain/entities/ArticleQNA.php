<?php 
class ArticleQNA{
	private $id;
	private $blogId;
	private $question;
	private $questionCss;
	private $answer;
	private $answerCss;
	private $sequence;
	private $status;
	private $imageData;

	public function getQnaId(){
		return $this->id;
	}
	
	public function getBlogId(){
		return $this->blogId;
	}

	public function getQuestion(){
		return $this->question;
	}

	public function getQuestionCss(){
		return $this->questionCss;
	}

	public function getAnswer(){
		return $this->answer;
	}

	public function getAnswerCss(){
		return $this->answerCss;
	}

	public function getSequence(){
		return $this->sequence;
	}

	public function getStatus(){
		return $this->status;
	}

	function __set($property, $value)
	{ 
		$this->$property = $value;
	}

    public function getImageData()
    {
        return $this->imageData;
    }

    public function setImageData($imageData)
    {
        $this->imageData = $imageData;
    }
}
?>